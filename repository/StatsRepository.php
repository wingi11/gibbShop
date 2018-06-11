<?php
require_once "../lib/Repository.php";

class StatsRepository extends Repository {
	protected $tableName = 'clicks';

	/**
	 * Die Views per Team herauslesen
	 *
	 * @param $productId
	 * @return array
	 * @throws Exception
	 */
	public function getViewsPerTeam($productId) {
		$rows = array();

		$query = "select t.teamName, count(c.users_id) as Anzahl from {$this->tableName} as c
join users as u on c.users_id = u.id
inner join team as t on t.id = u.team_id
where c.product_id = ? group by t.teamName;";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $productId);

		$statement->execute();

		$result = $statement->get_result();
		if (!$result) {
			throw new Exception($statement->error);
		}

		while ($row = $result->fetch_object()) {
			array_push($rows, $row);
		}

		$result->close();

		return $rows;
	}
}