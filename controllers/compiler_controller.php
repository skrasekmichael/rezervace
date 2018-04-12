<?php

class compiler_controller extends controller
{
	protected $controller;

    public function main($data)
	{	
		//pokud je uživatel přihlášený
		if (isset($_SESSION["online"]))
			$user = new User($_SESSION["online"]); 
		else 
			$user = new User(1); //GUEST

		$controller = $data[0];
		$this->data["user"] = $user;

		if ($controller != "")
		{
			$template = $controller;
			$class = $controller . "_controller";

			if (file_exists("controllers/$class.php"))
				$this->controller = new $class;
			else 
				$this->redirect("error/404/1");

			$this->controller->user = $user;
			$this->controller->data = $this->data;
			$this->controller->main($data);
			$this->data = $this->controller->data;
			$this->scripts = $this->controller->scripts;
			$this->styles = $this->controller->styles;

			if ($this->controller->template == "index")
			{
				if (file_exists("views/$template.phtml"))
					$this->template = $template;
				else
					$this->redirect("error/404/2");
			}
		}	
		
		$this->loadJS();
		$this->loadCSS();
    }

}