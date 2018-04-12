<?php

class signin_controller extends controller
{
    protected $controller;
	
	public function main($data)
	{	
        $this->data["title"] = "Přihlásit";
        $this->styles[] = "signin";

        if (isset($_POST["signin"]))   
        {
            $result = User::SignIn($_POST["email"], $_POST["password"]);
            if ($result[0])
            {
                $this->user = $result[1];
                $_SESSION["online"] = $this->user->id;
                $this->load_home();
            }
            else
                $this->data["signin_error"] = $result[1];
        }
    }
}