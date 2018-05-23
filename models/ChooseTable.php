<?php
class ChooseTable
{
    private $helpNum = -1;
    public $rows = array;
    
    //natáhne usery z databáze 
    function loadUsers(){
        while($row = mysql_fetch_array(Db::query_all("SELECT * FROM user")){
            $rows[$helpNum][$row]
        }
        return $rows;
    }
    //natáhne Eventy z databáze 
    function loadEvents(){
        while($row = mysql_fetch_array(Db::query_all("SELECT * FROM events")){
            $rows[$helpNum][$row]
        }
        return $rows;
    }
    //admin vytvoøí usera
    function createUser($type, $email, $password, $firstname, $lastname, $tel, $avatar){
        if (Db::query("SELECT email FROM user WHERE email = '$email'") == 0)
        {
            $password = hash("SHA512", $password . $email);
            $query="INSERT INTO user (iduser, type, email, password, firstname, lastname, tel, avatar) VALUES (NULL, '$type', '$email', '$password', '$firstname', '$lastname', '$tel', '$avatar');";
            return [true, "Vytvoøení nového uživatele probìhlo úspìšbì. "];
        }
        else
            return [false, "Uživatel s tímto emailem je již vytvoøen. "];
        }
    //admin vytvori novy Event
    function createEvent($name, $from, $to, $count, $desc){
        if (Db::query("SELECT for FROM event WHERE for = '$name'") == 0)
        {
            $query="INSERT INTO event (idevent, for, from, to, count, description) VALUES (NULL, $name, $from, $to, $count, $desc);";
            return [true, "Událost byla úspìšnì vytvoøena."];
        }
        else
            return [false, "Událost je již vytvoøená."];
    }
    //admin smaže Event
    function delEvent($id){
        Db::query_all("DELETE * FROM events WHERE idevent='$id'");
        return loadEvents();
    }
    //admin smaže usera
    function delUser($id){
        Db::query_all("DELETE * FROM user WHERE iduser='$id'");
        return loadUsers();
    }
}
?>                 