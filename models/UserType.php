<?php

class UserType
{
    const ADMINISTRATOR = 1; 
    const STAFF = 2;   
    const VIP = 3;   
    const ACTIV = 4; 
    const REGISTRED = 5; 
    const GUEST = 6;

    public $id;
    public $level;
    public $decription;

    public static function FromId($id)
    {
        $data = Db::query_one("SELECT * FROM usertype WHERE idusertype = $id");
        $user = new UserType();
        $user->id = $data[0];
        $user->level = $data[1];
        $user->decription = $data[2];
        return $user;
    }

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