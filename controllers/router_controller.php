<?php

class router_controller extends controller
{
	protected $controller;
	
	private function parseURL($url)
	{
		$url = substr($url, strlen($this->main_url));
		$new_url = parse_url($url);
		$new_url["path"] = ltrim($new_url["path"], "/");
		$new_url["path"] = trim($new_url["path"]);
		$new_url["path"] = str_replace('%20', ' ', $new_url["path"]);
		$new_url["path"] = str_replace('+', '*', $new_url["path"]);
		$split_path = explode("/", $new_url["path"]);
		return $split_path;
	}

	public function main($args)
	{
		$data = $this->parseURL($args[0]);
		$controller = $data[0];
		$commands = array_slice($data, 1);
		return [$controller, $commands];
	}
}
