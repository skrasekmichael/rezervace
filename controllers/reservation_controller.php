<?php

class reservation_controller extends controller
{
	protected $controller;
	
	public function main($data)
	{	
		$this->data["title"] = "Rezervace";

		$date = MyDate::Today();

		//pokud stránka obdržela argumenty
		if (count($data[1]) == 1 && $data[1][0] != "")
		{   
			//kontrola jestli má argument správný tvar
			if (is_numeric($data[1][0]))
				$date = MyDate::FromTimestamp($data[1][0]);
			else
				$this->redirect("error/4");
		}

		//pokud stránka obdrží více jak 1 argument
		if (count($data[1]) > 1)
		{
			$this->redirect("error/3");
		}

		//pokud se snaží zobrazit starší datum ... přesměruje na dnešní datum
		if ($date->timestamp < MyDate::Today()->timestamp)
			$this->redirect("reservation/" . MyDate::Today()->timestamp);

		$this->data["date"] = $date;
		$this->data["calendar"] = $this->calendar($date);
		$this->data["repeat"] = $this->repeat($date);

		$repeating = new ComboBox();
		$data = $this->repeat_type($date);
		$repeating->init($data[0], null, $data[1]);
		$this->data["repeat_combobox"] = $repeating;

		$all = new Reservations();
		$all->load($date, $this->user->id);
		$this->data["allreservation"] = $all->get($date);

		//vytvoření rezervace
		if ($this->user->isLogged() && isset($_POST["reservation"]))
		{
			$from = $_POST["from"];
			$duration = $_POST["duration"];
			$to = MyDate::FromTimestamp(strtotime($from));
			$to->change(["min" => $duration * 30]);
			$to = $to->toString("Y-m-d H:i:s");
			$place_id = Place::GetPlace($_POST["sport"], $_POST["field"])->id;
			$for = $_POST["npeople"];
			$index = $_POST["cindex"];
			$count = ($index == 0) ? $_POST["count"] : $repeating->second_data[$index];

			Reservation::CreateReservation($place_id, $from, $to, $count, $for, $this->user->id);
			$this->refresh();
		}

		if (isset($_POST["cancel_reservation"]))
		{
			$id = $_POST["id"];
			$reservation = Reservation::GetReservation($id);
			if ($this->user->id == $reservation->user->id)
				Reservation::Delete($id);
			$this->refresh();
		}
	}

	private function repeat($date)
	{
		$inflection = ["ý pondělí", "ý úterý", "ou středu", "ý čtvrtek", "ý pátek", "ou sobotu", "ou neděli"];
		return "každ" . $inflection[$date->getWeeksDay() - 1];
	}

	private function repeat_type($date)
	{
		$data = [];
		$data[] = "";

		$end_month = new MyDate();
		$end_month->set(["year" => $date->getYear(),"month" => $date->getMonth() + 1]);
		$end_month->change(["day" => -1]);
		$data[] = $this->get_to_date($date->clone(), $end_month);

		$end_year = new MyDate();
		$end_year->set(["year" => $date->getYear() + 1]);
		$end_year->change(["day" => -1]);
		$data[] =  $this->get_to_date($date->clone(), $end_year);

		return [["vlastní", "do konce měsíce:", "do konce roku:"], $data];
	}

	private function get_to_date($start, $end)
	{
		$index = 0;
		while (true)
		{
			if ($start->timestamp > $end->timestamp)
				return $index;
			
			$start->change(["day" => 7]);
			$index++;
		}
		return $index;
	}

	private function calendar($date)
	{
		$months = ["Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen", "Září", "Říjen", "Listopad", "Prosinec"];;
		
		$calendar = "";

		//hlavička tabulky
		$table = "<table><thead><tr>";

		//přepínání mezi měsíci
		$table .= "<div class='month_name'>";
		
		$previous = $date->clone();
		$previous->change(["month" => -1]);
		$previous->set(["day" => 1]);

		$table .= "<td><a href='reservation/" . $previous->timestamp . "'><div class='to_left'><</div></a></td>";
		$table .= "<td colspan='5'>" . $months[$date->getMonth() - 1] . " " . $date->getYear() . "</td>";

		$next = $date->clone();
		$next->change(["month" => 1]);
		$next->set(["day" => 1]);

		$table .= "<td><a href='reservation/" . $next->timestamp . "'><div class='to_right'>></div></a></td>";
		$table .= "</div></tr><tr>";

		$days = ["Po", "Út", "St", "Čt", "Pá", "So", "Ne"];

		for ($i = 0; $i < 7; $i++)
			$table .= "<td class='day_name'>" . $days[$i] . "</td>";
		$table .= "</tr></thead>";

		$adate = $date->clone();
		$adate->set(["day" => 1]);
		$index = $adate->getWeeksDay() - 1;

		$classes = [" last_month", " this_month", " next_month"];
		$type = 0;

		$table .= "<tr>";

		$adate->change(["day" => -$index - 7]);
		for ($i = 0; $i < 8 * 7; $i++)
		{
			if ($adate->getDay() == 1)
				$type++;

			$class = $classes[$type];
			$index = $adate->getWeeksDay() - 1;

			//pokud den odpovídá zobrazovanému datu
			if ($adate->timestamp == $date->timestamp)
				$class = " select_day";

			//pokud den odpovídá dnešnímu datu
			if ($adate->timestamp == MyDate::Today()->timestamp) 
				$class .= " today";

			//pokud je datum starší jak dnešní, nemůže se rezervovat
			if ($adate->timestamp >= MyDate::Today()->timestamp)
				$table .= "<td><a href='reservation/" . $adate->timestamp . "'><div class='number day" . $class . "'>" . $adate->getDay() . "</div></a></td>";	
			else
				$table .= "<td class='number blocked'>" . $adate->getDay() . "</td>";

			//pokud je nedělě, ukončí se řádek
			if ($index == 6)
				$table .= "</tr>";

			//posunutí datumu o den
			$adate->change(["day" => 1]);
		}

		$calendar .= $table . "</table>";
		return $calendar;
	}
}
