<?php

class profile_controller extends controller
{
	protected $controller;
	public $max_args = 1;
	
	public function main($data)
	{
		if (!$this->user->isLogged())
			$this->redirect("signin");

		if (count($data[1]) == 1 && $data[1][0] != "")
		{
			switch ($data[1][0])
			{
				case "info": 
					$this->data["tabpanel_index"] = 0;
					break;
				case "reservation": 
					$this->data["tabpanel_index"] = 1;
					break;
				case "settings":
					$this->data["tabpanel_index"] = 2;
					break;
				case "statisctic": 
					$this->data["tabpanel_index"] = 3;
					break;
				default:
					$this->redirect("error/4");
					break;
			}
		}

		if (isset($_POST["avatar"]))
		{
			$this->user->setAvatar($_POST["avatar_index"]);
			$this->redirect("profile/settings");
		}

		if (isset($_POST["change_password"]))
		{
			$result = $this->user->changePassword($_POST["old_password"], $_POST["new_password"], $_POST["check_new_password"]);
			if ($result[0])
				$this->redirect("profile/settings");

			$_POST = [];
			$this->data["passchange_error"] = $result[1];
			$this->data["tabpanel_index"] = 2;
		}

		if (isset($_POST["cancel_reservation"]))
		{
			Reservation::Delete($_POST["res_id"]);
			$this->redirect("profile/reservation");
		}

		if (isset($_POST["delete_account"]))
		{
			$result = $this->user->deleteAccount($_POST["password"]);
			if ($result[0])
			{
				session_destroy();
				$this->redirect("signup");
			}
			else
			{
				$this->data["delete_account_error"] = $result[1];
				$this->data["tabpanel_index"] = 2;
			}
		}

		$this->data["title"] = $this->user->firstName . " " . $this->user->lastName;
		$this->data["avatars"] = $this->get_images();

		$days = ["Pondělí", "Úterý", "Středa", "Čtvrtek", "Pátek", "Sobota", "Neděle"];
		$res = Reservation::GetReservations($this->user);
		$res_list = "<ul class='myres'>";
		for ($i = 0; $i < count($res); $i++)
		{
			$res_list .= "<li><form method='post'>";
			$res_list .= "<div class='left'>";
			$res_list .= "<div class='place'>Místo: {$res[$i]->place->toString()}</div>";
			$res_list .= "<div class='date'>Datum: <a href='reservation/{$res[$i]->from->timestamp}'>{$days[$res[$i]->from->getWeeksDay() - 1]} {$res[$i]->from->toString('d.m.Y')}</a></div>";
			$res_list .= "<div class='time'>Čas: {$res[$i]->from->toString('H:i')} - {$res[$i]->to->toString('H:i')}</div>";
			$res_list .= "<div class='for'>Pro počet lidí: {$res[$i]->for}</div>";
			$res_list .= "</div>";
			$res_list .= "<input type='hidden' name='res_id' value='{$res[$i]->id}'>";
			$res_list .= "<input class='right' type='submit' name='cancel_reservation' value='zrušit rezervaci'>";
			$res_list .= "</form><div class='cls'></div></li>";
		}
		$res_list .= "</ul>";
		$this->data["reservations"] = $res_list;
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
