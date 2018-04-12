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
        for ($i = 0; $i < count($places); $i++)
        {
            $hour = new Hour();
            $hour->load($places[$i]);
            $this->hours[$places[$i]->sport . $places[$i]->field] = $hour;
        }

        //klasické rezervace
        $data = Db::query_all("SELECT * FROM reservation ORDER BY `from`");
        for ($i = 0; $i < count($data); $i++)
        {   
            $action = new Action();
            $action->load_as_reservation($data[$i]["idreservation"], $data[$i]["from"], $data[$i]["to"], $data[$i]["place"], 1, $data[$i]["count"]);
            $key = $action->places[0]->sport . $action->places[0]->field;
            $this->data[$key][strtotime($action->from)] = $action;
            
            $count = Db::query("SELECT * FROM user_has_reservation WHERE reservation_idreservation = " . $action->id);
            $from = MyDate::FromTimestamp(strtotime($action->from));
            $to = MyDate::FromTimestamp(strtotime($action->to));
            $next_day = MyDate::FromTimestamp($date->timestamp);
            $next_day->change(["day" => 1]);

            /*echo $from->toString() . " > " . $date->toString() . " = " . ($from->timestamp > $date->timestamp) . ";";
            echo $to->toString() . " < " . $next_day->toString() . " = " . ($to->timestamp < $next_day->timestamp) . ";<br>";*/
            if ($from->timestamp > $date->timestamp && $to->timestamp < $next_day->timestamp)
            {
                for ($j = $from->getHour(); $j < $to->getHour(); $j += 0.5)
                {
                    $this->hours[$key]->full[(string)$j] += $count;
                    $this->hours[$key]->count = abs(abs($from->getHour() - $to->getHour() - 0.5) - $j);
                }
            }
        }
    }

    public function get($date)
    {
        $print = "";
        $create = true;
        $count = count($this->places);
        for ($i = 0; $i < $count; $i++)
        {
            if ($create)
            {
                $place = $this->places[$i];
                $print .= "<div class='title'>" . $place->sport . "</div><div class='tab'>";
                $print .= "<table class='". $place->sport ."'><thead><tr><td></td>";

                $adate = MyDate::FromTimestamp($date->timestamp);
                $adate->set(["hour" => $place->open_from, "min" => 0, "sec" => 0]);
                for ($j = $place->open_from - 1; $j < $place->open_to; $j += 1)
                {
                    $print .= "<td colspan='2'><div class='thead'>" . $adate->toString("H:i") . "</div></td>";
                    $adate->change(["min" => 60]);
                }

                $print .= "</tr></thead>";
                $create = false;
            }

            $print .= $this->get_row($this->places[$i], $date);
            if ($i + 1 < $count && $this->places[$i + 1]->sport != $this->places[$i]->sport)
            {
                $print .= "</table></div>";
                $create = true;
            }
        }
        return $print . "</table></div>";
    }

    private function get_row($place, $date)
    {
        $adate = MyDate::FromTimestamp($date->timestamp);
        $adate->change(["hour" => $place->open_from]); 
    
        $field_as_class = str_replace(".", "", str_replace(" ", "_", $place->field));
        $row = "<tr class='". $field_as_class."'>";
        $row .= "<td class='field'>" . $place->field . "</td><td></td>";
        $td = 0;
        $key = $place->sport . $place->field;

        for ($j = $place->open_from; $j < $place->open_to; $j += 0.5)
        {
            $class = "hour";
            $text = "";
            $onclick = "onclick=\"set('" . $place->sport . "', '" . $place->field . "', '" . $adate->timestamp . "', $td, '$field_as_class')\"";

            $span = 1;
            if ($adate->timestamp + 30 * 60 < MyDate::Now()->timestamp)
                $class = "blocked";
            else if ($adate->timestamp < MyDate::Now()->timestamp)
                $class = "now";
            else if ($this->hours[$key]->full[$j] == 0)
            {         
                $text = "0/" . $place->max;
                $td++;
            }
            else
            {
                if ($this->hours[$key]->full[(string)$j] == $place->max)
                {
                    $class .= " full";
                    $text = $place->max . "/" . $place->max;
                }
                else if ($this->hours[$key]->full[(string)$j] > 0)
                {
                    $class .= " noempty";
                    $text = $this->hours[$key]->full[(string)$j] . "/" . $place->max;
                }

                $c = $this->hours[$key]->count;
                if ($place->max == 1)
                {
                    $adate->change(["min" => $c * 60]); 
                    $j += $c - 0.5;
                    $span = 2 * $c;
                }

                $td++;
            } 

            $tooltip = ($text != "") ? "tooltip='obsazeno $text'" : "";

            $row .= "<td $tooltip colspan='$span' " . ($class == "hour" ? $onclick : "") . " class='$class'></td>";  
            if ($span == 1)
                $adate->change(["min" => 30]); 
        }
        return $row . "<td></td></tr>";
    }
}