<?php

class profile_controller extends controller
{
    protected $controller;
	
	public function main($data)
	{	
        if (!$this->user->isLogged())
            $this->redirect("error");

        if (isset($_POST["avatar"]))
        {
            $this->user->set_avatar($_POST["avatar_index"]);
            $this->refresh();
        }

        $this->styles[] = "profile";
        $this->scripts[] = "profile";
        $this->data["title"] = $this->user->firstName . " " . $this->user->lastName;
        $this->data["avatars"] = $this->get_images();
    }

    private function get_images()
    {
        $list = "<ul class='avatars'>";
        $images = User::$avatars;
        for ($i = ($this->user->type->level <= STAFF) ? 0 : 1; $i < count($images); $i++)
            $list .= "<li " . (($i == $this->user->avatar) ? "class='select' " : "")  . " onclick='select_avatar($i)'><img src='" . $images[$i] . "' alt='$i'></li>";
        $list .= "</ul><div class='cls'></div>";
        return $list;
    }
}