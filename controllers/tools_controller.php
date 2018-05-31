<?php

class tools_controller extends controller
{
    protected $controller;
    	
	public function main($data)
	{	
        if ($this->user->type->level > STAFF)
            $this->redirect("error/404");

        //mazání
        if (isset($_POST["delete_user"]))
        {
            $user_id = $_POST["user_id"];
            User::DeleteUser($user_id);
            $this->refresh();
        }
        
        //registrace od Admina
        if (isset($_POST["pridat"]){   
            $email = $_POST["email"];
            $password = $_POST["password"];
            $fname = $_POST["name"];
            $lname = $_POST["surname"];
            $tel = $_POST["telNumber"];
            
            $data = User::SignUp($email, $password, $fname, $lname, $tel);
            if ($data[0])
            {
                $this->refresh();
            }
            else
                $this->data["signup_error"] = $data[1];
        }   

        $this->data["title"] = "Správa";

        if (isset($_POST["vyber"])){
            $this->data["choice"]= $_POST["vyber"]    
        }
    
        $list_of_users = "";
        $users = User::GetUsers();
        foreach ($users as $user)
        {
            $list_of_users .= "<form method='post'><tr>";
            $list_of_users .= "<td><input type='hidden' value='" . $user->id . "' name='user_id'>" . $user->id . "</td><td>" . $user->firstName . "</td><td>" . $user->lastName .  "</td>";
            $list_of_users .= "<td>" . $user->email . "</td><td>" . $user->tel . "</td><td>" . $user->type->decription . "</td><td><input type='submit' name='delete_user' value='smazat'></td>";
            $list_of_users .= "</tr></form>";
        }
        $this->data["users"] = $list_of_users;
          
        //code for loading sEvents table
        $list_of_events = "<table>";
        //tohle udělám v budoucím modelu event
        $events = sEvent::loadEvents();
        foreach ($events as $event)
        {
            $list_of_event .= "<tr><td>" . $event->name . "</td><td>" . $event->from .  "</td><td>".$event->to."</td><td>".$event->description."</td><td><input type='submit' name='delete_event' value='smazat'></td></tr>";
        } 
        $list_of_events .= "</table>";
        $this->data["events"] = $list_of_events;     
    }
}

