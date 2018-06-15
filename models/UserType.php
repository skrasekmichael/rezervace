<?php

//konstanty typů uživatelů s oprávněním
$_usertypes = Db::query_all("SELECT level, name FROM usertype");
for ($i = 0; $i < count($_usertypes); $i++)
	define($_usertypes[$i]["name"], $_usertypes[$i]["level"]);

class UserType
{
	public $id;
	public $level;
	public $description;

	//načte typ uživatele podle ID
	public static function FromId($id)
	{
		if (Db::query("SELECT * FROM usertype WHERE idusertype = ?", [$id]) == 0)
			return null;

		$data = Db::query_one("SELECT * FROM usertype WHERE idusertype = ?", [$id]);
		$user = new UserType();
		$user->id = $data[0];
		$user->level = $data[1];
		$user->description = $data[2];
		return $user;
	}

	//vrátí typ uživatele podle oprávnění
	public static function FromLevel($level)
	{
		$data = Db::query_one("SELECT * FROM usertype WHERE level = ?", [$level]);
		$user = new UserType();
		$user->id = $data[0];
		$user->level = $data[1];
		$user->description = $data[2];
		return $user;
	}
}
