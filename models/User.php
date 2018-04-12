<?php

class User
{
    private $avatars = ["https://upload.wikimedia.org/wikipedia/commons/f/f4/User_Avatar_2.png"];

    public $type;
    public $firstName;
    public $lastName;
    public $email;
    public $tel;
    public $id;
    public $avatar = 0;
    
    private $password;

    public function isLogged()
    {
        return !($this->type->level == UserType::GUEST);
    }

    public function __construct($id)
    {
        $this->init($id);
    }

    public function profile()
    {
        return "<div class='profile_bar'><div class='avatar'><img src='" . $this->avatars[$this->avatar] . "'></div><div class='name'>" . $this->firstName . "&nbsp;" . $this->lastName . "</div></div>";
    }

    private function init($id)
    {
        $this->type = UserType::FromLevel(UserType::GUEST);
        $this->id = $id;
        $data = Db::query_one("SELECT * FROM user WHERE iduser = $id");

        $this->type = UserType::FromId($data[1]);
        if ($this->type->level == UserType::REGISTRED)
        {
            $this->type = UserType::FromLevel(UserType::ACTIV);
            $this->update($this->id, "type", Db::query_one("SELECT idusertype, level FROM usertype WHERE level = " . UserType::ACTIV)[1]);
        }

        if ($this->type->level != UserType::GUEST)
        {
            $this->email = $data[2];
            $this->password = $data[3];
            $this->firstName = $data[4];
            $this->lastName = $data[5];
            $this->tel = $data[6];
            $this->avatar = $data[7];
        }
    }

    private function update($id, $var, $value)
    {
        Db::query("UPDATE user SET $var = $value WHERE iduser = $id");
    }

    public function getDay($date)
    {
        //vrátí pole s rezervacemi dne
    }

    public static function SignUp($email, $password, $fname, $lname, $tel)
    {
        if (Db::query("SELECT email FROM user WHERE email = '$email'") == 0)
        {
            $password = hash("SHA512", $password . $email);
            Db::query("INSERT INTO user (iduser, type, email, password, firstname, lastname, tel, avatar) VALUES (NULL, " . UserType::FromLevel(UserType::REGISTRED)->id . ", '$email', '$password', '$fname', '$lname', '$tel', 0)");
            return [true, "Registrace proběhla úspěšbě. "];
        }
        else
            return [false, "Uživatel s tímto emailem je již zaregistrován. "];
    }

    public static function SignIn($email, $password)
    {
        $password = hash("SHA512", $password . $email);
        $data = Db::query_all("SELECT iduser, email, password FROM user WHERE email = '$email'");
        if (count($data) == 1)
        {
            if ($data[0][2] == $password)
            {
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
}