<?php

require_once '../lib/Repository.php';

class TagRepository extends Repository {

	/**
	 * Die Meisverwendeten Tags herausfiltern und zurückgeben
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getPopularTags() {
		$query = "select count(name) as 'Anzahl', name from tags group by name order by Anzahl DESC limit 8;";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->execute();
		$result = $statement->get_result();

		$popularTags = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_object()) {
				array_push($popularTags, $row->name);
			}

		}
		return $popularTags;
	}

}