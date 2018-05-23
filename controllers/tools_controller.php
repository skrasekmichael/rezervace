<?php

class tools_controller extends controller
{
    protected $controller;
	
	public function main($data)
	{	
        $this->data["title"] = "Správa";
    //uvidime co z toho bude, chtel bych aby se nacetla value z tlacitka v dalsi
    //navigaci ohledne spravy a pak ve switchem se udelala spravna tabulka
    //zkusim to udelat do patku, a kdyz ne, tak ti reknu
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
    
    }
}