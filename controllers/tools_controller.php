<?php

class tools_controller extends controller
{
    protected $controller;
	
	public function main($data)
	{	
        $this->data["title"] = "Správa";
        $this->scripts[] = "sprava";
    //uvidime co z toho bude, chtel bych aby se nacetla value z tlacitka v dalsi
    //navigaci ohledne spravy a pak ve switchem se udelala spravna tabulka
    //zkusim to udelat do patku, a kdyz ne, tak ti reknu
        
    
    //v kontroleru nesmíš vypisovat ... uložíš to do $this->data["jméno proměnný"] a to pak vypíšeš v pohledu ;)
    /*
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
        }       */
        
        //př. jak bych to udělal
        $list_of_users = "<table>";
        $users = User::GetUsers();
        foreach ($users as $user)
        {
            $list_of_users .= "<tr><td>" . $user->firstName . "</td><td>" . $user->lastName .  "</td><td>funkce 1</td><td>funnkce 2 ...</tr>";
        }
        $list_of_users .= "</table>";
        $this->data["users"] = $list_of_users;
    }
}
