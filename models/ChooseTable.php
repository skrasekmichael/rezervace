<?php
class ChooseTable
{
    private $helpNum = -1;
    public $rows = [];
    
    //nat�hne usery z datab�ze 
    function loadUsers()
    {
        /* 
            toto je pošeéfený ve třídě User
        while($row = mysql_fetch_array(Db::query_all("SELECT * FROM user")){
            $rows[$helpNum][$row]
        } */
        return User::GetUsers();
    }
    //nat�hne Eventy z datab�ze 
    function loadEvents()
    {
        while($row = mysql_fetch_array(Db::query_all("SELECT * FROM events"))
        {
            $rows[$helpNum][$row]
        }
        return $rows;
    }
    //admin vytvo�� usera
    function createUser($type, $email, $password, $firstname, $lastname, $tel, $avatar){
        /*
            toto je pošeéfený ve třídě User
        if (Db::query("SELECT email FROM user WHERE email = '$email'") == 0)
        {
            $password = hash("SHA512", $password . $email);
            $query="INSERT INTO user (iduser, type, email, password, firstname, lastname, tel, avatar) VALUES (NULL, '$type', '$email', '$password', '$firstname', '$lastname', '$tel', '$avatar');";
            return [true, "Vytvo�en� nov�ho u�ivatele prob�hlo �sp�b�. "];
        }
        else
            return [false, "U�ivatel s t�mto emailem je ji� vytvo�en. "];
        }*/

        return User::SignUp($email, $password, $firstname, $lastname, $tel);
    }
    //admin vytvori novy Event
    function createEvent($name, $from, $to, $count, $desc){
        if (Db::query("SELECT for FROM event WHERE for = '$name'") == 0)
        {
            $query="INSERT INTO event (idevent, for, from, to, count, description) VALUES (NULL, $name, $from, $to, $count, $desc);";
            return [true, "Ud�lost byla �sp�n� vytvo�ena."];
        }
        else
            return [false, "Ud�lost je ji� vytvo�en�."];
    }
    //admin sma�e Event
    function delEvent($id){
        Db::query_all("DELETE * FROM events WHERE idevent='$id'");
        return loadEvents();
    }
    //admin sma�e usera
    function delUser($id){
        //tohle asi taky přesunu do třídy user ... tady to z hlediska logiky nemá co dělat
        Db::query_all("DELETE * FROM user WHERE iduser='$id'");
        return loadUsers();
    }
}
?>                 