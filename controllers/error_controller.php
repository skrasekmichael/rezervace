<?php

class error_controller extends controller
{
	protected $controller;
	
	public function main($data)
	{	
		$this->data["title"] = "Chyba";
		$this->data["code"] = 0;
		$this->data["message"] = "nenastaveno";
	}
}
