<?php

class tools_controller extends controller
{
    protected $controller;        
    public $choice;
    	
	public function main($data)
	{	
        if ($this->user->type->level > STAFF)
            $this->redirect("error/404");

        $this->data["title"] = "Správa";
        $choice = $_POST["vyber"];
        if($choice.name=="bUsers")
        {
            $list_of_users = "<table>";
            $users = User::GetUsers();
            foreach ($users as $user)
            {
                $list_of_users .= "<tr><td>" . $user->firstName . "</td><td>" . $user->lastName .  "</td><td>Edit</td><td>X</tr>";
            }
            $list_of_users .= "</table>";
            $this->data["users"] = $list_of_users;
        }else
        {
            //code for loading sEvents table
            $list_of_events = "<table>";
            $events = sEvents::loadEvents();
            foreach ($events as $event)
            {
                $list_of_event .= "<tr><td>" . $event->name . "</td><td>" . $event->from .  "</td><td>".$event->to."</td><td>".$event->description."</td><td>Edit</td><td>X</td></tr>";
            }
            $list_of_users .= "</table>";
            $this->data["users"] = $list_of_users;
        
        }
    }
}
