<?php

class Db 
{
    private static $connect;

    private static $settings = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		PDO::ATTR_EMULATE_PREPARES => false,
	);

	// Připojí se k databázi pomocí daných údajů
    public static function connect($host, $user, $password, $database) {
		if (!isset(self::$connect)) {
			self::$connect = @new PDO("mysql:host=$host;dbname=$database", $user, $password, self::$settings);
		}
	}
	
	// Spustí dotaz a vrátí z něj první řádek
    public static function query_one($query, $arguments = array()) {
		$return = self::$connect->prepare($query);
		$return->execute($arguments);
		return $return->fetch();
	}

	// Spustí dotaz a vrátí všechny jeho řádky jako pole asociativních polí
    public static function query_all($query, $arguments = array()) {
		$return = self::$connect->prepare($query);
		$return->execute($arguments);
		return $return->fetchAll();
	}
	
	// Spustí dotaz a vrátí z něj první sloupec prvního řádku
    public static function query_first($query, $arguments = array()) {
		$result = self::query_first($query, $arguments);
		return $result[0];
	}
	
	// Spustí dotaz a vrátí počet ovlivněných řádků
	public static function query($query, $arguments = array()) {
		$return = self::$connect->prepare($query);
		$return->execute($arguments);
		return $return->rowCount();
	}
	
}