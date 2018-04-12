<?php

class profile_controller extends controller
{
    protected $controller;
	
	public function main($data)
	{	
        if (!$this->user->isLogged())
            $this->redirect("error");
            
        $this->data["title"] = $this->user->firstName . " " . $this->user->lastName;
    }
}