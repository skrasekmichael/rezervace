<?php
class sprava_controller extends controller
{
    protected $controller;
    public $choice;
    
    //kdyz se vybere dan� polo�ka v navigaci spr�vy, vyhled� se spr�vn� tabulka
    public function main($data){
         switch($choice){
            case "Ud�losti":
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
                
            case "U�ivatel�":
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