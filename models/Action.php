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

    //načte akci jako rezervaci
    public function load_as_reservation($id, $from, $to, $place_id, $max, $count)
    {
        $this->type = 0;
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->max = $max;
        $this->count = $count;
        $this->places[] = Place::GetPlaceById($place_id);
    }
}