<?php

class ComboBox
{
    private $data;

    function init($data)
    {
        $this->data = $data;
    }

    public function get()
    {
        $print = "<select><option value=''></option>";
        for ($i = 0; $i < count($this->data); $i++)
        {
            $print .= "<option value='" . $this->data[$i]->id . "'>" . $this->data[$i]->toString() . "</option>";
        }
        $print .= "</select>";
        echo $print;
    }
}