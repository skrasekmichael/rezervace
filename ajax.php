<?php 

function isLogged($args)
{
	session_start(); 
	return isset($_SESSION["online"]) && $_SESSION["online"] != 1;
}

function logOut($args)
{
	session_start(); 
	$_SESSION["online"] = 1;
	session_destroy();
	return $_SESSION["online"];
}

header('Content-Type: application/json');
$result = array();
   
if(!isset($_POST['functionname']) ) 
	$result['error'] = 'Nenalezeno jméno funkce!'; 
	
if(!isset($_POST['arguments']) ) 
	$result['error'] = 'Nenalezeny argumenty funkce!'; 
	
if(!isset($result['error']) ) 
{
	try 
	{
		$result["result"] = $_POST["functionname"]($_POST["arguments"]);
	} catch (Exception $e)
	{
		$result["error"] = $e;
	}
}

echo json_encode($result);