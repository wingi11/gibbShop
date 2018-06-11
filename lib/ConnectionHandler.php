<?php

class ConnectionHandler {

	private static $connection = null;

	/**
	 * Privater Constructor
	 *
	 * ConnectionHandler constructor.
	 */
	private function __construct() {
	}

	/**
	 * Die Connection zurückgeben
	 *
	 * @return MySQLi|null
	 * @throws Exception
	 */
	public static function getConnection() {
		// Prüfen ob bereits eine Verbindung existiert
		if (self::$connection === null) {
			// Konfigurationsdatei auslesen
			$config = require '../config.php';
			$host = $config['database']['host'];
			$username = $config['database']['username'];
			$password = $config['database']['password'];
			$database = $config['database']['database'];

			//initialisieren
			self::$connection = new MySQLi($host, $username, $password, $database);
			if (self::$connection->connect_error) {
				$error = self::$connection->connect_error;
				throw new Exception("Verbindungsfehler: $error");
			}
			self::$connection->set_charset('utf8');
		}
		// Verbindung zurückgeben
		return self::$connection;
	}
}