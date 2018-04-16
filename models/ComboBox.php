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
        $print = "<select>";
        for ($i = 0; $i < count($this->data); $i++)
        {
            $print .= "<option value='" . $this->data[$i] . "'>" . $this->data[$i] . "</option>";
        }
        $print .= "</select>";
        return $print;
    }

    public function print()
    {
        echo $this->get();
    }
}