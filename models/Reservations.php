<?php

class Reservations
{
	private $data = [];
	private $places = [];
	private $days = [];
	private $online = null;

	public function load($date, $online, $user = null)
	{
		$this->places = Place::GetPlaces();
		$this->data = $this->filter_reservations(Reservation::GetReservations($user), $date);
		$this->online = $online;

		for ($i = 0; $i < count($this->places); $i++)
		{   
			$interval = new DayInterval();
			$interval->init($this->places[$i]);
			$key = $this->places[$i]->getKey();
			$this->days[$key] = $interval;
		}

		for ($i = 0; $i < count($this->data); $i++)
		{
			$r = $this->data[$i];
			$key = $r->place->getKey();
			$se = $r->get_start_end();

			for ($j = $se[0]; $j < $se[1]; $j += 0.5)
			{
				$temp = $r->clone();
				$temp->duration = $se[1] - $j; 
				$this->days[$key]->intervals[(string)$j][] = $temp;
			}
		}
	}

	public function filter_reservations($data, $date)
	{
		$fdata = [];
		for ($i = 0; $i < count($data); $i++)
		{
			$dates = $data[$i]->get_dates();
			for ($j = 0; $j < count($dates); $j++)
			{
				if ($date->toString("Y-m-d") == $dates[$j]->toString("Y-m-d"))
				{
					$fdata[] = $data[$i];
				}
			}
		}
		return $fdata;
	}

	public function get($date)
	{
		//řídící proměnné
		$print = "";
		$create = true;
		$count = count($this->places);

		//projde všechny místa k rezervac
		for ($i = 0; $i < $count; $i++)
		{
			//pokud se má vytvořit nová záložka
			if ($create) 
			{
				$place = $this->places[$i];
				$print .= "<div class='title'>" . $place->sport . "</div><div class='tab'>";
				$print .= "<table class='". $place->sport ."'><thead><tr><td></td>";

				$adate = $date->clone();
				$adate->set(["hour" => $place->open_from, "min" => 0, "sec" => 0]);

				//hlavička s časy
				for ($j = $place->open_from - 1; $j < $place->open_to; $j += 1)
				{
					$print .= "<th colspan='2'><div class='thead number'>" . $adate->toString("H:i") . "</div></th>";
					$adate->change(["min" => 60]);
				}

				$print .= "</tr></thead>";
				$create = false;
			}

			//přidání řádku se rezervacemi místa vybraného dne
			$print .= $this->get_row($this->places[$i], $date);

			//pokud se změnil typ místa
			if ($i + 1 < $count && $this->places[$i + 1]->sport != $this->places[$i]->sport)
			{
				$print .= "</table></div>"; //uknčení tabulky
				$create = true; //povolení vytvořit novou založku
			}
		}
		return $print . "</table></div>";
	}

	//rezervace vybraného místa na vybraný den
	private function get_row($place, $date)
	{
		$adate = $date->clone();
		$adate->change(["hour" => $place->open_from]); //nastavení datumu na čas otevření

		$field_as_class = str_replace(".", "", str_replace(" ", "_", $place->field)); //převedení druhu místa na CSS třídu
		
		$row = "<tr class='". $field_as_class . "'>";
		$row .= "<th class='field'>" . $place->field . "</th><td></td>";
		
		//řídící proměnné
		$td = 0;
		$key = $place->getKey();

		//procházení po 30 min
		for ($j = $place->open_from; $j < $place->open_to; $j += 0.5)
		{
			$your = -1;
			$class = "hour";
			$text = "";

			//nastavení akce po kliknutí
			$onclick = "onclick=\"set('" . $place->sport . "', '" . $place->field . "', '" . $adate->timestamp . "', $td, '$field_as_class')\"";

			$span = 1; //výchozí šířka buňky

			//zablokování rezervací do minulosti
			if ($adate->timestamp + 30 * 60 < MyDate::Now()->timestamp)
			{
				$class = "blocked";
				$onclick = "";
			}
			else if ($adate->timestamp < MyDate::Now()->timestamp) //zablokování rezervací na aktuální čas
			{
				$class = "now";
				$onclick = "";
			}
			else if (count($this->days[$key]->intervals[(string)$j]) == 0) //volno
			{		 
				$text = "0/" . $place->max;
				$td++;
			}
			else
			{
				// ... na tuto dobu jsou vytvořené rezervace
				$np = $this->days[$key]->getCount((string)$j);

				//kontrola zda se nejedná o rezervaci online uživatele
				for ($i = 0; $i < count($this->days[$key]->intervals[(string)$j]) && $your == -1; $i++)
				{
					if ($this->days[$key]->intervals[(string)$j][$i]->user->id == $this->online)
					{
						$your = $i;
						break;
					}
				}

				//pokud je naplňěn maximální počet rezervací místa
				if ($np == $place->max) 
				{
					$onclick = "";
					$class .= " full";
					$text = $place->max . "/" . $place->max;
				}
				else if ($np > 0) //ještě není plno
				{
					$class .= " noempty";
					$text = $np . "/" . $place->max;
				}

				//pokud je místo pro 1 rezervaci
				if ($place->max == 1)
				{
					$c = $this->days[$key]->intervals[(string)$j][0]->duration;
					//spojí se čas do dlouhého bloku
					$adate->change(["min" => $c * 60]); 
					$j += $c - 0.5;
					$span = 2 * $c;
				}

				$td++; //byla přidána buňka
			}

			//text po najetí myši
			$tooltip = ($text != "") ? "tooltip='obsazeno $text'" : "";

			//pokud je rezervace online uživatele
			if ($your != -1)
			{
				$class .= " user";
				$tooltip = "tooltip='vaše rezervace'";
				$reservation = $this->days[$key]->intervals[(string)$j][$your];

				$week = new MyDate();
				$week->set(["hour" => 24 * 7]);

				$to = $reservation->to->clone();
				$to->change(["day" => 7 * $reservation->count]);

				$count = floor(abs(MyDate::Now()->timestamp - $to->timestamp) / $week->timestamp);
				$onclick = "onclick=\"myres(" . $reservation->id . ", '" . $reservation->from->toString() . "', '" . $reservation->to->toString() . "', '" . $reservation->place->toString() . "', $count, " . $reservation->for . ")\"";
			}

			//přidání buňky
			$row .= "<td $tooltip " . ($span > 1 ? "colspan='$span' " : "") . "$onclick class='$class'></td>";  

			//pokud je šířka buňky 1
			if ($span == 1)
				$adate->change(["min" => 30]); //posune se čas o 30 min
		}
		$row .= "<td></td></tr>";
		//$row .= "<tr class='". $field_as_class . " dynamic'><th class='field'></th><td></td><td colspan='24'><div class='drag from'></div><div class='drag to'></div></tr></td>";
		return $row;
	}
}
