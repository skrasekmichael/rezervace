<?php

//konstanty typů uživatelů s oprávněním
$_usertypes = Db::query_all("SELECT level, name FROM usertype");
for ($i = 0; $i < count($_usertypes); $i++)
    eval("const " . $_usertypes[$i]["name"] . " = " . $_usertypes[$i]["level"] . ";");


class UserType
{
    /*
    const ADMINISTRATOR = 1; 
    const STAFF = 2;   
    const VIP = 3;   
    const ACTIV = 4; 
    const REGISTERED = 5; 
    const GUEST = 6;
    ... old version
    */

    public $id;
    public $level;
    public $decription;

    //načte typ uživatele podle ID
    public static function FromId($id)
    {
        $data = Db::query_one("SELECT * FROM usertype WHERE idusertype = $id");
        $user = new UserType();
        $user->id = $data[0];
        $user->level = $data[1];
        $user->decription = $data[2];
        return $user;
    }

    //vrátí typ uživatele podle oprávnění
    public static function FromLevel($level)
    {
        $data = Db::query_one("SELECT * FROM usertype WHERE level = $level");
        $user = new UserType();
        $user->id = $data[0];
        $user->level = $data[1];
        $user->decription = $data[2];
        return $user;
    }
}