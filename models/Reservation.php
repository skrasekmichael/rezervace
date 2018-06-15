<?php

class Reservation
{
	public $type;
	public $id;
	public $from;
	public $to;	
	public $place;
	public $max = 1;
	public $count = 1;
	public $for;
	public $user;

	public $duration;
	
	//načte akci jako rezervaci
	public function load($id, $user_id, $from, $to, $place_id, $max, $count, $for)
	{
		$this->type = 0;
		$this->id = $id;
		$this->from =  MyDate::FromTimestamp(strtotime($from));
		$this->to = MyDate::FromTimestamp(strtotime($to));
		$this->max = $max;
		$this->count = $count;
		$this->place = Place::GetPlaceById($place_id);
		$this->for = $for;
		$this->user = new User($user_id);
	}

	public function get_start_end()
	{	  
		$start = $this->from->getHour() + $this->from->getMin() / 60;
		$end = $this->to->getHour() + $this->to->getMin() / 60;

		return [$start, $end];
	}

	public function get_dates()
	{
		$dates = [];
		$date = $this->from->clone();
		for ($i = 0; $i < $this->count; $i++)
		{
			$dates[] = $date->clone();
			$date->change(["day" => 7]);
		}
		return $dates;
	}

	public function clone()
	{
		return Reservation::GetReservation($this->id);
	}

	public static function CreateReservation($place_id, $from, $to, $count, $for, $user_id)
	{
		Db::query("INSERT INTO reservation (`idreservation`, `iduser`, `place`, `from`, `to`, `count`, `for`) VALUES (null, ?, ?, ?, ?, ?, ?)", [$user_id, $place_id, $from, $to, $count, $for]);
	}

	public static function GetReservation($id)
	{
		$data = Db::query_one("SELECT * FROM reservation WHERE idreservation = ? ORDER BY `from`", [$id]);
		$r = new Reservation();
		$r->load($data["idreservation"], $data["iduser"], $data["from"], $data["to"], $data["place"], 1, $data["count"], $data["for"]);
		return $r;
	}

	public static function Delete($id)
	{
		Db::query("DELETE FROM reservation WHERE idreservation = ?", [$id]);
	}

	public static function GetReservations($user = null)
	{
		$rs = [];
		if ($user == null)
			$data = Db::query_all("SELECT * FROM reservation ORDER BY `from`");
		else
			$data = Db::query_all("SELECT * FROM reservation WHERE iduser = " . $user->id . " ORDER BY `from`");

		for ($i = 0; $i < count($data); $i++)
		{   
			//načtení akce
			$r = new Reservation();
			$r->load($data[$i]["idreservation"], $data[$i]["iduser"], $data[$i]["from"], $data[$i]["to"], $data[$i]["place"], 1, $data[$i]["count"], $data[$i]["for"]);
			$rs[] = $r;
		}
		return $rs;
	}
}
