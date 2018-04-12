<?php

class Hour
{
    public $place;
    public $max;
    public $full = [];
    public $count = 1;

    public function load($place, $max = null)
    {
        $this->place = $place;
        $this->max = $max ?? $place->max;
        for ($i = 1; $i <= 24; $i += 0.5)
            $this->full[(string)$i] = 0;
    }
}