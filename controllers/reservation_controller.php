<?php

class reservation_controller extends controller
{
    protected $controller;
	
	public function main($data)
	{	
        $this->data["title"] = "Rezervace";
        $this->styles[] = "reservation";
        $this->scripts[] = "reservation";

        $date = MyDate::Today();

        //pokud stránka obdržela argumenty
        if (count($data[1]) == 1 && $data[1][0] != "")
        {   
            //kontrola jestli má argument správný tvar
            if (is_numeric($data[1][0]))
            {
                $date = MyDate::FromTimestamp($data[1][0]);
            }
            else    
            {
                $this->redirect("error/4");
            }
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
        $this->data["calendar"] = $this->calendar($this->data["date"]);

        $all = new Reservations();
        $all->load(Place::GetPlaces(), $this->data["date"]);
        $this->data["allreservation"] = $all->get($date);
    }

    private function calendar($date)
    {
        $months = ["Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen", "Září", "Říjen", "Listopad", "Prosinec"];;
        
        $calendar = "";

        //hlavička tabulky
        $table = "<table><thead><tr>";

        //přepínání mezi měsíci
        $table .= "<div class='month_name'>";
        
        $previous = MyDate::FromTimestamp($date->timestamp);
        $previous->change(["month" => -1]);
        $previous->set(["day" => 1]);

        $table .= "<td><a href='reservation/" . $previous->timestamp . "'><div class='to_left'><</div></a></td>";
        $table .= "<td colspan='5'>" . $months[$date->getMonth() - 1] . " " . $date->getYear() . "</td>";

        $next = MyDate::FromTimestamp($date->timestamp);
        $next->change(["month" => 1]);
        $next->set(["day" => 1]);

        $table .= "<td><a href='reservation/" . $next->timestamp . "'><div class='to_right'>></div></a></td>";
        $table .= "</div></tr><tr>";

        $days = ["Po", "Út", "St", "Čt", "Pá", "So", "Ne"];

        for ($i = 0; $i < 7; $i++)
            $table .= "<td class='day_name'>" . $days[$i] . "</td>";
        $table .= "</tr></thead>";

        $adate = MyDate::FromTimestamp($date->timestamp);
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
                $table .= "<td><a href='reservation/" . $adate->timestamp . "'><div class='day" . $class . "'>" . $adate->getDay() . "</div></a></td>";    
            else
                $table .= "<td class='blocked'>" . $adate->getDay() . "</td>";

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