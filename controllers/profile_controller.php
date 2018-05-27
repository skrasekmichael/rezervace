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
            $this->user->setAvatar($_POST["avatar_index"]);
            $this->refresh();
        }

        if (isset($_POST["change_password"]))
        {
            $result = $this->user->changePassword($_POST["old_password"], $_POST["new_password"], $_POST["check_new_password"]);
            if ($result[0])
                $this->refresh();

            $_POST = [];
            $this->data["passchange_error"] = $result[1];
            $this->data["tabpanel_index"] = 2;
        }

        $this->data["title"] = $this->user->firstName . " " . $this->user->lastName;
        $this->data["avatars"] = $this->get_images();
    }

    private function get_images()
    {
        $list = "<ul class='avatars'>";
        $images = User::$avatars;
        $start = ($this->user->type->level <= STAFF) ? 0 : 1;
        for ($i = $start; $i < count($images); $i++)
            $list .= "<li " . (($i == $this->user->avatar) ? "class='select' " : "")  . " onclick='select_avatar(" . ($i - $start) . ")'><img src='" . $images[$i] . "' alt='$i'></li>";
        $list .= "</ul><div class='cls'></div>";
        return $list;
    }
}