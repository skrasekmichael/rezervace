<?php

class error_controller extends controller
{
	protected $controller;
	public $max_args = 2;
	
	public function main($data)
	{	
		$this->data["title"] = "Chyba";
		$this->data["code"] = 0;
		$this->data["message"] = "nenastaveno";
	}
}
