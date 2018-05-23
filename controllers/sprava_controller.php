<?php
class sprava_controller extends controller
{
    protected $controller;
    public $choice;
    
    //kdyz se vybere daná položka v navigaci správy, vyhledá se správná tabulka
    public function main($data){
         switch($choice){
            case "Události":
                $data = ChooseTable::loadEvents;
                if($data[0]){
                    foreach ($data as $row){
                        echo "<tr>$row[1]</tr>";
                        echo "<tr>$row[2]</tr>";
                        echo "<tr>$row[3]</tr>";
                        echo "<tr>$row[4]</tr>";
                        echo "<tr>$row[5]</tr>";
                        echo "<tr>$row[0]</tr>";
                    }
                }
                
            case "Uživatelé":
                $data = ChooseTable::loadUsers;
                if($data[0]){
                    foreach ($data as $row){
                        echo "<tr>$row[1]</tr>";
                        echo "<tr>$row[2]</tr>";
                        echo "<tr>$row[3]</tr>";
                        echo "<tr>$row[4]</tr>";
                        echo "<tr>$row[5]</tr>";
                    }
                }
         }       
    }
}
?>