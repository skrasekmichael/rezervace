<?php

//načtení avatarů
foreach (glob("images/avatars/*.png") as $file)
    User::$avatars[] = $file;

class User
{
    public static $avatars = [];

    public $type;
    public $firstName;
    public $lastName;
    public $email;
    public $tel;
    public $id;
    public $avatar = 1;
    
    private $password;

    public function isLogged()
    {
        //pokud je uživatel GUEST ... není přihlášený
        return !($this->type->level == GUEST);
    }

    public function __construct($id)
    {
        $this->init($id);
    }

    //vrátí lštu zobrazující v menu
    public function profile()
    {
        return "<div class='profile_bar'><div class='avatar'><img src='" . User::$avatars[$this->avatar] . "'></div><div class='name'>" . $this->firstName . "&nbsp;" . $this->lastName . "</div></div>";
    }

    //načtení uživatele z databáze s daným ID
    private function init($id)
    {
        $this->type = UserType::FromLevel(GUEST); //výchozí typ uživatele
        $this->id = $id;

        $data = Db::query_one("SELECT * FROM user WHERE iduser = $id");

        $this->type = UserType::FromId($data[1]); //přepsání typu uživatele

        //pokud je účet neaktivní
        if ($this->type->level == REGISTRED)
        {
            //aktivace účtu
            $this->type = UserType::FromLevel(ACTIV);
            $this->update("type", Db::query_one("SELECT idusertype, level FROM usertype WHERE level = " . ACTIV)[1]);
        }

        //pokud se nejedná o GUESTA
        if ($this->type->level != GUEST)
        {
            //načtení uživatelských informací
            $this->email = $data[2];
            $this->password = $data[3];
            $this->firstName = $data[4];
            $this->lastName = $data[5];
            $this->tel = $data[6];
            $this->avatar = $data[7];
        }
    }

    private function update($var, $value)
    {
        Db::query("UPDATE user SET $var = '$value' WHERE iduser = " . $this->id);
    }

    public function changePassword($old, $new, $new_check)
    {
        if (strlen($new) < 4)
            return [false, "heslo musí mít alespoň 4 znaky"];

        if ($new != $new_check)
            return [false, "hesla se neshodují"];

        $password = hash("SHA512", $old . $this->email);
        if ($password != $this->password)
            return [false, "nesprávně zadané heslo"];

        $new_password = hash("SHA512", $new . $this->email);
        $this->update("password", $new_password);
        return [true, "heslo bylo změněno"];
    }

    public function setAvatar($index)
    {
        $this->update("avatar", $index);
    }

    public function getDay($date)
    {
        //vrátí pole s rezervacemi dne
    }

    public static function GetUsers()
    {
        $users = [];
        $data = Db::query_all("SELECT * FROM user");
        for ($i = 0; $i < count($data); $i++)
        {
            $user = new User($data[$i]["iduser"]);
            if ($user->type->level != GUEST)
                $users[] = $user;
        }
        return $users;
    }

    public static function SignUp($email, $password, $fname, $lname, $tel)
    {
        if (Db::query("SELECT email FROM user WHERE email = '$email'") == 0)
        {
            $password = hash("SHA512", $password . $email);

            Db::query("INSERT INTO user (iduser, type, email, password, firstname, lastname, tel, avatar) VALUES (NULL, " . UserType::FromLevel(REGISTRED)->id . ", '$email', '$password', '$fname', '$lname', '$tel', 1)");

            return [true, "Registrace proběhla úspěšbě. "];
        }
        else
            return [false, "Uživatel s tímto emailem je již zaregistrován. "];
    }

    //přihlášení uživatele
    public static function SignIn($email, $password)
    {
        $password = hash("SHA512", $password . $email); //vrátí otisk hesla + soli (emailu), který je uložený v databázi
        $data = Db::query_all("SELECT iduser, email, password FROM user WHERE email = '$email'"); 

        //pokud uživtael s tímto emailem existuje
        if (count($data) == 1)
        {
            //pokud se otisk hesla shoduje s otiskem v databázi
            if ($data[0][2] == $password)
            {
                //načte uživatele s tímto ID
                $user = new User($data[0][0]);
                return [true, $user]; 
            }
            else
                return [false, "Nesprávně zadané heslo. "];
        }
        else
        {
            return [false, "Uživatel s tímto emailem není zaregistrován. "];
        }
    }
    
    //mazání uživatele
    public static function DeleteUser($id)
    {
        if (Db::query("SELECT iduser FROM user WHERE iduser=$id") == 1)
        {
            Db::query("DELETE FROM user WHERE iduser=$id");
            return [true, "Vymazání se podařilo"];
        }
        else
            return [false,"Uživatel s daným ID neexistuje!"];
    }
}