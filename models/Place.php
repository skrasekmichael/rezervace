<?php 

class Place
{
	public $id;
	public $sport;
	public $field;
	public $max;
	public $price0;
	public $price1;
	public $open_from;
	public $open_to;

	function load($id, $sport, $field, $price0, $price1, $max, $from, $to)
	{
		//načtení proměnných
		$this->id = $id;
		$this->sport = $sport;
		$this->field = $field;
		$this->price0 = $price0;
		$this->price1 = $price1;
		$this->max = $max;
		$this->open_from = $from;
		$this->open_to = $to;
	}

	public function getKey()
	{
		return $this->sport . $this->field; 
	}

	public function toString()
	{
		return $this->sport . " " . $this->field;
	}

	//vrátí místo podle ID
	public static function GetPlaceById($id)
	{
		$place = new Place();
		$data = Db::query_one("SELECT * FROM place WHERE idplace = ? ORDER BY sport", [$id]);
		$place->load($data["idplace"], $data["sport"], $data["field"], $data["price0"], $data["price1"], $data["max"], $data["open_from"], $data["open_to"]);
		return $place;
	}

	public static function GetPlace($sport, $field)
	{
		$place_id = Db::query_first("SELECT idplace, sport, field FROM place WHERE sport = ? AND field = ? ORDER BY sport", [$sport, $field]);
		$place = Place::GetPlaceById($place_id);
		return $place;
	}

	//načtení všech míst možných k rezervaci
	public static function GetPlaces()
	{
		$array = [];
		$data = Db::query_all("SELECT * FROM place");
		
		for ($i = 0; $i < count($data); $i++)
		{
			$place = new Place();
			$place->load($data[$i]["idplace"], $data[$i]["sport"], $data[$i]["field"], $data[$i]["price0"], $data[$i]["price1"], $data[$i]["max"], $data[$i]["open_from"], $data[$i]["open_to"]);
			$array[] = $place;
		}

		return $array;
	}
}
