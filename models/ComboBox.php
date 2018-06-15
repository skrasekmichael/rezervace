<?php

class ComboBox
{
	public $data;
	public $second_data;
	public $key_data;

	public function init($data, $key_data = null, $second_data = null)
	{
		$this->key_data = $key_data ?? $data;
		$this->data = $data;
		$this->second_data = $second_data;
	}

	public function get()
	{
		$print = "";
		for ($i = 0; $i < count($this->data); $i++)
		{
			$print .= "<option value='" . $this->key_data[$i] . "'>" . $this->data[$i] . " " . $this->second_data[$i] ?? "" . "</option>";
		}
		return $print;
	}

	public function print()
	{
		echo $this->get();
	}
}
