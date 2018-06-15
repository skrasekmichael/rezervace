<?php

class Reservation
{
    public $type;
    public $id;
    public $from;
    public $to;    
    public $places;
    public $max = 1;
    public $count = 1;
    public $for;
    public $user;

    //načte akci jako rezervaci
    public function load($id, $user_id, $from, $to, $place_id, $max, $count, $for)
    {
        $this->type = 0;
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->max = $max;
        $this->count = $count;
        $this->places = Place::GetPlaceById($place_id);
        $this->for = $for;
        $this->user = new User($user_id);
    }

    public function get_start_end()
    {
        $from = MyDate::FromTimestamp(strtotime($this->from)); //otevírací doba
        $to = MyDate::FromTimestamp(strtotime($this->to)); //zavírací doba
        
        $start = $from->getHour() + $from->getMin() / 60;
        $end = $to->getHour() + $to->getMin() / 60;

        return [$start, $end];
    }

    public function get_dates()
    {
        $dates = [];
        $date = $this->from->clone();
        for ($i = 0; $i < $count; $i++)
        {
            $dates[] = $date->clone();
            $date->change(["day" => 7]);
        }
        return $dates;
    }

    public static function CreateReservation($place_id, $from, $to, $count, $for, $user_id)
    {
        Db::query("INSERT INTO reservation (`idreservation`, `userid`, `place`, `from`, `to`, `count`, `for`) VALUES (null, $iduser_id, $place_id, '$from', '$to', $count, $for)");
    }

    public static function GetReservations($user = null)
    {
        $actions = [];
        if ($user == null)
            $data = Db::query_all("SELECT * FROM reservation ORDER BY `from`");
        else
            $data = Db::query_all("SELECT * FROM reservation WHERE iduser = :userid ORDER BY `from`", ["userid" => $user->id]);

        for ($i = 0; $i < count($data); $i++)
        {   
            //načtení akce
            $action = new Action();
            $action->load($data[$i]["idreservation"], $data[$i]["iduser"], $data[$i]["from"], $data[$i]["to"], $data[$i]["place"], 1, $data[$i]["count"], $data[$i]["for"]);
            $actions[] = $action;
        }
        return $actions;
    }
}
