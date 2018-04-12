<?php

class MyDate
{
    public $timestamp;

    public static function Now()
    {
        $mydate = new MyDate();
        $mydate->timestamp = time();
        return $mydate;
    }

    public static function Today()
    {
        $mydate = new MyDate();
        $mydate->timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        return $mydate;
    }

    public static function FromTimestamp($timestamp)
    {
        $mydate = new MyDate();
        $mydate->timestamp = $timestamp;
        return $mydate;
    }

    public function getDay() { return intval(date("d", $this->timestamp)); }
    public function getMonth() { return intval(date("m", $this->timestamp)); }
    public function getYear() { return intval(date("Y", $this->timestamp)); }
    public function getHour() { return intval(date("H", $this->timestamp)); }
    public function getMin() { return intval(date("i", $this->timestamp)); }
    public function getSec() { return intval(date("s", $this->timestamp)); }
    public function getWeeksDay() { return intval(date("N", $this->timestamp)); }
    public function getMonthCount() {
        return cal_days_in_month(CAL_GREGORIAN, $this->getMonth(), $this->getYear());
    }

    public function change($data) 
    { 
        $hour = array_key_exists("hour", $data) ? $data["hour"] : null;
        $min = array_key_exists("min", $data) ? $data["min"] : null;
        $sec = array_key_exists("sec", $data) ? $data["sec"] : null;
        $day = array_key_exists("day", $data) ? $data["day"] : null;
        $year = array_key_exists("year", $data) ? $data["year"] : null;
        $month = array_key_exists("month", $data) ? $data["month"] : null;

        $this->timestamp = mktime($this->getHour() + $hour ?? 0, $this->getMin() + $min ?? 0, $this->getSec() + $sec ?? 0, 
        $this->getMonth() + $month ?? 0, $this->getDay() + $day ?? 0, $this->getYear() + $year ?? 0);
    }

    public function set($data) 
    { 
        $hour = array_key_exists("hour", $data) ? $data["hour"] : null;
        $min = array_key_exists("min", $data) ? $data["min"] : null;
        $sec = array_key_exists("sec", $data) ? $data["sec"] : null;
        $day = array_key_exists("day", $data) ? $data["day"] : null;
        $year = array_key_exists("year", $data) ? $data["year"] : null;
        $month = array_key_exists("month", $data) ? $data["month"] : null;

        $this->timestamp = mktime($hour ?? $this->getHour(), $min ?? $this->getMin(), $sec ?? $this->getSec(), 
       $month ?? $this->getMonth(), $day ?? $this->getDay(), $year ?? $this->getYear());
    }

    public function toString($format = "Y-m-d H:i:s") {
        return date($format, $this->timestamp);
    }
}