<?php

class signout_controller extends controller
{
	protected $controller;
	
	public function main($data)
	{	
		session_destroy();
		$this->load_home();
	}
}
