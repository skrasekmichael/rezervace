<?php

class sUsers_controller extends controller
{
    protected $controller;
	
	public function saveUser($data)
	{   
        $email = $_POST["email"];
        $password = $_POST["password"];
        $fname = $_POST["name"];
        $lname = $_POST["surname"];
        $tel = $_POST["telNumber"];
        
        //registrace od Admina
        $data = User::SignUp($email, $password, $fname, $lname, $tel);
        if ($data[0])
        {
            $this->load_home();
        }
        else
            $this->data["signup_error"] = $data[1];
    }
    public function delUser($data)
    {
        $data = User::DelUser();
    }