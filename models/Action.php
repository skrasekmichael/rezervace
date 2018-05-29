<?php

class Action
{
    public $type;
    public $id;
    public $from;
    public $to;    
    public $places = [];
    public $max = 1;
    public $count = 1;
    public $for;

    //načte akci jako rezervaci
    public function load_as_reservation($id, $from, $to, $place_id, $max, $count, $for)
    {
        $this->type = 0;
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->max = $max;
        $this->count = $count;
        $this->places[] = Place::GetPlaceById($place_id);
        $this->for = $for;
    }

    public static function CreateReservation($place_id, $from, $to, $count, $for, $user_id)
    {
        Db::query("INSERT INTO reservation (`idreservation`, `place`, `from`, `to`, `count`, `for`) VALUES (null, $place_id, '$from', '$to', $count, $for)");
        $res_id = Db::query_first("SELECT LAST_INSERT_ID()");
        Db::query("INSERT INTO user_has_reservation (user_iduser, reservation_idreservation) VALUES ($user_id, $res_id)");
    }

}