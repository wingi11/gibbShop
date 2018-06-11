<?php

require_once 'ConnectionHandler.php';

class Repository {

	protected $tableName = null;

	/**
	 * Etwas per ID aus der DB lesen
	 *
	 * @param $id
	 * @return object|stdClass
	 * @throws Exception
	 */
	public function readById($id) {
		// Query erstellen
		$query = "SELECT * FROM {$this->tableName} WHERE id=?";

		// Datenbankverbindung anfordern und, das Query "preparen" (vorbereiten)
		// und die Parameter "binden"
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $id);

		// Das Statement absetzen
		$statement->execute();

		// Resultat der Abfrage holen
		$result = $statement->get_result();
		if (!$result) {
			throw new Exception($statement->error);
		}

		// Ersten Datensatz aus dem Reultat holen
		$row = $result->fetch_object();

		// Datenbankressourcen wieder freigeben
		$result->close();

		// Den gefundenen Datensatz zurückgeben
		return $row;
	}

	/**
	 * Alles aus einer Tabelle auslesen und auf 100 resultate begrenzen
	 *
	 * @param int $max
	 * @return array
	 * @throws Exception
	 */
	public function readAll($max = 100) {
		$query = "SELECT * FROM {$this->tableName} LIMIT 0, $max";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->execute();

		$result = $statement->get_result();
		if (!$result) {
			throw new Exception($statement->error);
		}

		// Datensätze aus dem Resultat holen und in das Array $rows speichern
		$rows = array();
		while ($row = $result->fetch_object()) {
			$rows[] = $row;
		}

		return $rows;
	}

	/**
	 * Etwas aus der DB löschen
	 *
	 * @param $tablename
	 * @param $id
	 * @throws Exception
	 */
	public function deleteById($tablename, $id) {
		$query = "DELETE FROM {$tablename} WHERE id=?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $id);

		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}
	}
}
