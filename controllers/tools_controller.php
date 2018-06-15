<?php

class tools_controller extends controller
{
	protected $controller;
		
	public function main($data)
	{	
		if ($this->user->type->level > STAFF)
			$this->redirect("error/404");

		//mazání
		if (isset($_POST["delete_user"]))
		{
			$user_id = $_POST["user_id"];
			User::DeleteUser($user_id);
			$this->refresh();
		}

		$this->data["title"] = "Správa";

		$levels_combobox = new ComboBox();
		$levels = [];
		$keys = [];
		$index = 1;
		while (true)
		{
			$type = UserType::FromId($index);
			if ($type == null) 
				break;
			$keys[] = $type->id;
			$levels[] = $type->description;
			$index++;
		} 
		$levels_combobox->init($levels, $keys);
		$this->data["levels_combobox"] = $levels_combobox;

		//registrace od Admina
		if (isset($_POST["create_user"]))
		{   
			$email = $_POST["email"];
			$password = $_POST["password"];
			$fname = $_POST["name"];
			$lname = $_POST["surname"];
			$tel = $_POST["telNumber"];
			$level = $_POST["level"];
			
			$data = User::SignUp($email, $password, $fname, $lname, $tel, $level);
			if ($data[0])
				$this->refresh();
			else
				$this->data["create_account_error"] = $data[1];
		}
	
		$list_of_users = "";
		$users = User::GetUsers();
		foreach ($users as $user)
		{
			$list_of_users .= "<form method='post'><tr>";
			$list_of_users .= "<td><input type='hidden' value='" . $user->id . "' name='user_id'>" . $user->id . "</td><td>" . $user->firstName . "</td><td>" . $user->lastName .  "</td>";
			$list_of_users .= "<td>" . $user->email . "</td><td>" . $user->tel . "</td><td>" . $user->type->description . "</td><td><input type='submit' name='delete_user' value='smazat'></td>";
			$list_of_users .= "</tr></form>";
		}
		$this->data["users"] = $list_of_users;
	}
}
