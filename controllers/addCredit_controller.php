<?php

class addCredit_controller extends controller
{
    protected $controller;
        
    public function main($data)
    {
        if(!isset($this->user->bankNumber))
        {
            $input_bank_number= "Číslo účtu:&nbsp&nbsp&nbsp<input type='number' value='Bankovní účet' name='bank' onfocus='this.value=''' required><br />";
            $this->data["bank"]= $input_bank_number;
        }
    }

    public function check_checkbox($data){
        $checkBox = $_POST["checkTransfer"];
        if ($checkBox){
            if(isset($_POST["bank"])){
                $bankNum = $_POST["bank"]; 
                $this->user->update($this->user->bankNumber, $bankNum);
            }
            $newCredits = $_POST["credits"];
            $this->user->update($newCredits);
            return $this->load_home();
        }
        else
            return $this->data["addCredit_error"] = "Opravdu souhlasíte s převodem? Jestli ano, zatrhněte políčko souhlasu.";    
    }
}
