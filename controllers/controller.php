<?php

abstract class controller
{
    protected $data = array();
    protected $main_url = "";
    protected $template = "index";
    protected $root;
    protected $scripts = ["script"];
    protected $styles = ["style"];
    public $user;

    function __construct()
    {
        $this->root = "../" . $this->main_url;
        $this->data["footer"] = "Copyright &copy; 2018 Michael Škrášek, Tomáš Szabó, Michal Ruiner, Martin Bielik";
        $this->data["title"] = "rezervace";
        $this->data["root"] = $this->root;
        $this->data["base"] = ".";
    }

    protected function loadCSS()
    {
        $this->data["styles"] = "";
        for ($i = 0; $i < count($this->styles); $i++)
            $this->data["styles"] .= $this->getCSS($this->styles[$i]);
    }

    protected function loadJS()
    {
        $this->data["scripts"] = "<script type='text/javascript'>var isLogged = " . $this->data["user"]->isLogged() . ";</script>";
        for ($i = 0; $i < count($this->scripts); $i++)
            $this->data["scripts"] .= $this->getJS($this->scripts[$i]);
    }

    protected function getCSS($path)
    {
        $path = "styles/$path.css";
        if (file_exists($path))
            return "<link rel='stylesheet' type='text/css' href='" . $path . "'>";
        return "";
    }

    protected function getJS($path)
    {
        $path = "scripts/$path.js";
        if (file_exists($path))
            return "<script src='" . $path . "' type='text/javascript'></script>";
        return "";
    }

    public function print_result()
    {
        extract($this->data);
        require("./views/" . $this->template . ".phtml");
    }
	
	public function redirect($url)
	{

		header("Location: " . $this->root . "/" . $url);
		header("Connection: close");
        exit;
    }

    public function load_home()
    {
        $this->redirect("");
    }

    abstract function main($args);
}