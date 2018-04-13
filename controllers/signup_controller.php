<?php

class signup_controller extends controller
{
    protected $controller;
	
	public function main($data)
	{	
        $this->data["title"] = "Regisrtace uživatele";
        $this->styles[] = "signup";

        if (isset($_POST["signup"]))
        {
            //antispamová kontrola
            if ($_POST["year"] != date("Y"))
            {
                $this->data["signup_error"] = "Nesprávně zadaný rok. ";
                return; 
            }

            //kontrola shody hesel
            if ($_POST["password"] != $_POST["password_check"])
            {
                $this->data["signup_error"] = "Hesla se neshodují. ";
                return;
            }
            
            $email = $_POST["email"];
            $password = $_POST["password"];
            $fname = $_POST["fname"];
            $lname = $_POST["lname"];
            $tel = $_POST["tel"];

            //registrace
            $data = User::SignUp($email, $password, $fname, $lname, $tel);
            if ($data[0])
            {
                $this->load_home();
            }
            else
                $this->data["signup_error"] = $data[1];
            
        }
    }
}