<?php

abstract class controller
{
    protected $data = array();
    protected $main_url = "";
    protected $template = "index";
    protected $root;
    protected $scripts = ["script", "gallery"];
    protected $styles = ["style", "gallery"];
    public $user;

    function __construct()
    {
        //výchozí proměnné
        $this->root = "/" . $this->main_url;
        $this->data["footer"] = "Copyright &copy; 2018 Michael Škrášek, Tomáš Szabó, Michal Ruiner, <s>Martin Bielik</s>";
        $this->data["title"] = "rezervace";
        $this->data["root"] = $this->root;
        $this->data["base"] = "/";
    }

    protected function loadCSS()  
    {
        $this->data["styles"] = "";
        for ($i = 0; $i < count($this->styles); $i++)
            $this->data["styles"] .= $this->getCSS($this->styles[$i]);
    }

    protected function loadJS()
    {
        $this->data["scripts"] = "";
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
        //extrahuje pole jako proměnné
        extract($this->data);
        //načte pohled
        require("./views/" . $this->template . ".phtml");
    }
	
	public function redirect($url)
	{
        //přesměrování
		header("Location: /" . $url);
		header("Connection: close");
        exit;
    }

    public function refresh()
    {
        header("Refresh:0");
        exit;
    }

    public function load_home()
    {
        $this->redirect("");
    }

    abstract function main($args);
}