<?php

class ComboBox
{
    public $data;
    public $second_data;

    public function init($data, $second_data)
    {
        $this->data = $data;
        $this->second_data = $second_data;
    }

    public function get()
    {
        $print = "";
        for ($i = 0; $i < count($this->data); $i++)
        {
            $print .= "<option value='" . $this->data[$i] . "'>" . $this->data[$i] . " " . $this->second_data[$i] . "</option>";
        }
        return $print;
    }

    public function print()
    {
        echo $this->get();
    }
}
