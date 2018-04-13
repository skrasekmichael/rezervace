<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

//mb_internal_encoding("UTF-8");
session_start();

function autoloader($class)
{
	if (preg_match('/controller$/', $class))
		require("controllers/" . $class . ".php");
	else
		require("models/" . $class . ".php");
}

spl_autoload_register("autoloader");

require("../connect.php");
Db::connect($host, $user, $password, $database);

$router = new router_controller();
$data = $router->main(array($_SERVER['REQUEST_URI']));

$compiler = new compiler_controller();
$compiler->main($data);
$compiler->print_result();
