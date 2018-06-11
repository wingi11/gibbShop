<?php

require "../repository/StatsRepository.php";
require "../repository/ProductRepository.php";

class StatsController {

	/**
	 * Liest die Informationen aus der datenbank und schmeisst alles in einen Array den das DIagram-Element auslesen kann.
	 *
	 * @throws Exception
	 */
	public function index() {
		if (isset($_POST["stats"])) {
			$sr = new StatsRepository();
			$pr = new ProductRepository();

			if ($pr->isProductFrom($_POST["prodid"], $_SESSION["user"]["id"])) {
				$rows = $sr->getViewsPerTeam($_POST["prodid"]);
				$maxarray = array();

				//Maximale anzahl des Array herausfinden
				foreach ($rows as $row) {
					array_push($maxarray, $row->Anzahl);
				}
				if ($maxarray != null) {
					$max = max($maxarray);
				} else {
					$max = 0;
				}

				$data = array(
					"rowDesc" => "Klasse",
					"Daten" => array(),
					"max" => $max
				);
				foreach ($rows as $row) {
					$teamname = $row->teamName;
					$anzahl = $row->Anzahl;
					$parray = array(
						"desc" => $teamname,
						"anzahl" => $anzahl
					);
					array_push($data["Daten"], $parray);
				}
				$view = new View("stats");
				$view->title = "Statistiken";
				$view->for = "prod1";

				$bids = $pr->getBidsOfProductDistinct($_POST["prodid"]);

				$view->bieter = sizeof($bids);

				$product = $pr->getProductById($_POST["prodid"]);
				$view->product = $product;

				$maxPrice = $pr->getMostExpensiveBidOfProduct($_POST["prodid"]);
				$view->rise = number_format((100 / $product[3]) * $maxPrice - 100, 1);

				$view->data = $data;


				$view->display();
			} else {
				header('Location: /');
			}
		} else {
			header('Location: /');
		}
	}
}