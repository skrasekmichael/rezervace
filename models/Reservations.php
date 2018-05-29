<?php

class Reservations
{
    private $data = [];
    private $places = [];
    private $dates = [];
    private $hours = [];

    public function load($places, $date)
    {
        $this->places = $places;

        //deklarace otevírací doby
        for ($i = 0; $i < count($places); $i++)
        {
            $hour = new Hour();
            $hour->load($places[$i]);
            $this->hours[$places[$i]->sport . $places[$i]->field] = $hour;
        }

        //načtení rezervací
        $data = Db::query_all("SELECT * FROM reservation ORDER BY `from`");
        for ($i = 0; $i < count($data); $i++)
        {   
            //načtení akce
            $action = new Action();
            $action->load_as_reservation($data[$i]["idreservation"], $data[$i]["from"], $data[$i]["to"], $data[$i]["place"], 1, $data[$i]["count"], $data[$i]["for"]);
            $key = $action->places[0]->sport . $action->places[0]->field;

            $from = MyDate::FromTimestamp(strtotime($action->from)); //otevírací doba
            $to = MyDate::FromTimestamp(strtotime($action->to)); //zavírací doba

            $next_day = $date->clone(); 
            $next_day->change(["day" => 1]);

            /*echo $from->toString() . " > " . $date->toString() . " = " . ($from->timestamp > $date->timestamp) . ";";
            echo $to->toString() . " < " . $next_day->toString() . " = " . ($to->timestamp < $next_day->timestamp) . ";<br>"; ... for debuging*/

            //pokud datum rezervace souhlasí s vybraným datem
            if ($from->timestamp > $date->timestamp && $to->timestamp < $next_day->timestamp)
            {
                $start = $from->getHour() + $from->getMin() / 60;
                $end = $to->getHour() + $to->getMin() / 60;
                //projití otevírací doby po 30 min
                for ($j = $start; $j < $end; $j += 0.5)
                {
                    $this->hours[$key]->full[(string)$j] += $action->for; //nastavení obsazenosti v dane půlhodině
                    $this->hours[$key]->count = abs($start - $end); //délka rezervace
                    echo $this->hours[$key]->count . "<br>";
                }
            }
        }
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
                    $print .= "<td colspan='2'><div class='thead number'>" . $adate->toString("H:i") . "</div></td>";
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
        $row .= "<td class='field'>" . $place->field . "</td><td></td>";
        
        //řídící proměnné
        $td = 0;
        $key = $place->sport . $place->field;

        //procházení po 30 min
        for ($j = $place->open_from; $j < $place->open_to; $j += 0.5)
        {
            $class = "hour";
            $text = "";

            //nastavení akce po kliknutí
            $onclick = "onclick=\"set('" . $place->sport . "', '" . $place->field . "', '" . $adate->timestamp . "', $td, '$field_as_class')\"";

            $span = 1; //výchozí šířka buňky

            //zablokování rezervací do minulosti
            if ($adate->timestamp + 30 * 60 < MyDate::Now()->timestamp)
                $class = "blocked";
            else if ($adate->timestamp < MyDate::Now()->timestamp) //zablokování rezervací na aktuální čas
                $class = "now";
            else if ($this->hours[$key]->full[(string)$j] == 0) //volno
            {         
                $text = "0/" . $place->max;
                $td++;
            }
            else
            {
                // ... na tuto dobu jsou vytvořené rezervace

                //pokud je naplňěn maximální počet rezervací místa
                if ($this->hours[$key]->full[(string)$j] == $place->max) 
                {
                    $class .= " full";
                    $text = $place->max . "/" . $place->max;
                }
                else if ($this->hours[$key]->full[(string)$j] > 0) //ještě není plno
                {
                    $class .= " noempty";
                    $text = $this->hours[$key]->full[(string)$j] . "/" . $place->max;
                }

                $c = $this->hours[$key]->count; //délka rezervace uživatele

                //pokud je místo pro 1 rezervaci
                if ($place->max == 1)
                {
                    //spojí se čas do dlouhého bloku
                    $adate->change(["min" => $c * 60]); 
                    $j += $c - 0.5;
                    $span = 2 * $c;
                }

                $td++; //byla přidána buňka
            } 

            //text po najetí myši
            $tooltip = ($text != "") ? "tooltip='obsazeno $text'" : "";

            //přidání buňky
            $row .= "<td $tooltip colspan='$span' " . ($class == "hour" ? $onclick : "") . " class='$class'></td>";  

            //pokud je šířka buňky 1
            if ($span == 1)
                $adate->change(["min" => 30]); //posune se čas o 30 min
        }
        return $row . "<td></td></tr>";
    }
}