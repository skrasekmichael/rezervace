<?php

class DayInterval
{
    public $intervals = [];

    public function init($place)
    {
        for ($i = $place->open_from; $i < $place->open_to; $i += 0.5)
        {
            $this->intervals[(string)$i] = []; 
        }
    }

    public function getCount($key)
    {
        $c = 0;
        for ($i = 0; $i < count($this->intervals[$key]); $i++)
        {
            $c += $this->intervals[$key][$i]->for;
        }
        return $c;
    }
}
