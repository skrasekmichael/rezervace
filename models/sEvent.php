<?php
class sEvent
{
	private $helpNum = -1;
	public $rows = [];
	
	//nat�hne Eventy z datab�ze 
	function loadEvents()
	{
		while($row = mysql_fetch_array(Db::query_all("SELECT * FROM events")))
		{
			$rows[$helpNum][$row];
		}
		return $rows;
	}
	//admin vytvori novy Event
	function createEvent($name, $from, $to, $count, $desc){
		if (Db::query("SELECT for FROM event WHERE for = '$name'") == 0)
		{
			$query="INSERT INTO event (idevent, for, from, to, count, description) VALUES (NULL, $name, $from, $to, $count, $desc);";
			return [true, "Událost byla úspěšně vytvořena."];
		}
		else
			return [false, "Událost je již vytvořená."];
	}
	//admin smaze Event
	function delEvent($id){
		Db::query_all("DELETE * FROM events WHERE idevent='$id'");
		return loadEvents();
	}
}
