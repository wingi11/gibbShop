<?php
require_once '../lib/Repository.php';

class productRepository {
	/**
	 * Stellt ein Produkt in die Datenbank
	 *
	 * @param $name
	 * @param $description
	 * @param $tags
	 * @param $price
	 * @param $priceType
	 * @param $fileExt
	 * @return int|void
	 * @throws Exception
	 */
	function create($name, $description, $tags, $price, $priceType, $fileExt, $expirationDate) {
		date_default_timezone_set('Europe/Vatican');
		$date = date('Y-m-d h:i:s', time());
		$name = htmlspecialchars($name);
		$description = htmlspecialchars($description);

		$order = array("\r\n", "\n", "\r");
		$replace = '<br/>';

		$description = str_replace($order, $replace, $description);
		$e = 0;

		if (!is_numeric($price)) {
			$_SESSION["messages"] = array("Der Preis darf nur Zahlen beinhalten!");
			return;
		}

		$query = "INSERT INTO product (title, description, price, user_id, hasFixpreis, isBought, releasdate, filename, expirationDate) VALUES (?, ?, ?, ?, ?, ? ,?, ?, ?)";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ssdiiisss', $name, $description, $price, $_SESSION["user"]["id"], $priceType, $e, $date, $file, $expirationDate);
		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}

		$prodId = $statement->insert_id;

		$filename = $prodId . "." . $fileExt;

		$query = "UPDATE product SET filename = ? WHERE id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('si', $filename, $prodId);
		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}

		foreach ($tags as $tag) {
			if (strlen($tag) > 15) $tag = "";
			if ($tag != "") {
				$tag = htmlspecialchars($tag);
				$query = "INSERT INTO tags (name) VALUES (?)";
				$statement = ConnectionHandler::getConnection()->prepare($query);
				$statement->bind_param('s', $tag);
				if (!$statement->execute()) {
					throw new Exception($statement->error);
				}
				$tagId = $statement->insert_id;

				$query = "INSERT INTO product_tags (product_id, tags_id) VALUES (?, ?)";
				$statement = ConnectionHandler::getConnection()->prepare($query);
				$statement->bind_param('ii', $prodId, $tagId);
				if (!$statement->execute()) {
					throw new Exception($statement->error);
				}
			}
		}

		return $prodId;
	}

	/**
	 * Aktualisiert ein bestimmtes Produkt
	 *
	 * @param $id
	 * @param $name
	 * @param $description
	 * @param $price
	 * @param $priceType
	 * @param $fileExt
	 * @param $expirationDate
	 * @throws Exception
	 */
	function update($id, $name, $description, $price, $priceType, $fileExt, $expirationDate) {
		$name = htmlspecialchars($name);
		$description = htmlspecialchars($description);

		$order = array("\r\n", "\n", "\r", "&#13");
		$replace = '<br/>';

		$description = str_replace($order, $replace, $description);

		if (!is_numeric($price)) {
			$_SESSION["messages"] = array("Der Preis darf nur Zahlen beinhalten!");
			return;
		}

		$filename = $id . "." . $fileExt;
		if ($fileExt != "") {
			$query = "UPDATE product SET title=?, description=?, price=?, hasFixpreis=?,filename=?, expirationDate=? WHERE id=?";
			$statement = ConnectionHandler::getConnection()->prepare($query);
			$statement->bind_param('ssdissi', $name, $description, $price, $priceType, $filename, $expirationDate, $id);
			if (!$statement->execute()) {
				throw new Exception($statement->error);
			}
		} else {
			$query = "UPDATE product SET title=?, description=?, price=?, hasFixpreis=?, expirationDate=? WHERE id=?";
			$statement = ConnectionHandler::getConnection()->prepare($query);
			$statement->bind_param('ssdisi', $name, $description, $price, $priceType, $expirationDate, $id);
			if (!$statement->execute()) {
				throw new Exception($statement->error);
			}
		}
	}

	/**
	 * Gibt alle Produktdaten für jedes Produkt zurück in einem Array
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getProducts() {
		$date = getdate();
		$date = $date['year'] . "-" . $date['mon'] . "-" . $date['mday'];
		$query = "select p.id, title, description, price, hasFixPreis, username, isBought, expirationdate, filename from product as p join users as u on p.user_id = u.id where isBought = 0 and (expirationdate > ? or hasFixpreis = 1) order by p.id desc ;";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('s', $date);
		$statement->execute();
		$result = $statement->get_result();

		$products = array();


		if ($result->num_rows > 0) {
			while ($row = $result->fetch_object()) {
				$product = array();
				array_push($product, $row->id);
				array_push($product, $row->title);
				array_push($product, $row->description);
				array_push($product, $row->price);
				array_push($product, $row->username);
				array_push($product, $row->filename);
				array_push($product, $row->hasFixPreis);
				array_push($products, $product);
			}

		}
		return $products;
	}


	/**
	 * Gibt alle Produkte vom angegebenen User an
	 *
	 * @param $userId
	 * @return array
	 * @throws Exception
	 */
	public function getProductsFromUser($userId) {
		$query = "select p.id, title, description, price, username, filename from product as p join users as u on p.user_id = u.id WHERE p.user_id = ? order by p.id desc";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $userId);
		$statement->execute();
		$result = $statement->get_result();

		$products = array();


		if ($result->num_rows > 0) {
			while ($row = $result->fetch_object()) {
				$product = array();
				array_push($product, $row->id);
				array_push($product, $row->title);
				array_push($product, $row->description);
				array_push($product, $row->price);
				array_push($product, $row->username);
				array_push($product, $row->filename);
				array_push($products, $product);
			}

		}
		return $products;
	}

	/**
	 * Gibt ein Produkt mit einer bestimmten ID zurück
	 *
	 * @param $id
	 * @return array
	 * @throws Exception
	 */
	public function getProductById($id) {
		$query = "select p.id, title, hasFixPreis, description, price, username, filename from product as p join users as u on p.user_id = u.id WHERE p.id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $id);
		$statement->execute();
		$result = $statement->get_result();

		$product = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_object()) {
				array_push($product, $row->id);
				array_push($product, $row->title);
				array_push($product, $row->description);
				array_push($product, $row->price);
				array_push($product, $row->username);
				array_push($product, $row->filename);
				array_push($product, $row->hasFixPreis);
			}

		}
		return $product;
	}

	/**
	 * Gibt alle Tags für ein bestimmtes Produkt zurück
	 *
	 * @param $product
	 * @return array
	 * @throws Exception
	 */
	public function getTagByProduct($id) {

		$query = "select t.name from product_tags join tags as t on tags_id = t.id where product_id = $id limit 4;";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->execute();
		$result = $statement->get_result();

		$tags = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_object()) {
				array_push($tags, $row->name);
			}

		}
		return $tags;
	}

	/**
	 * Schaut ob ein bestimmtes Produkt von einem bestimmten User stammt
	 *
	 * @param $prodId
	 * @param $userId
	 * @return bool
	 * @throws Exception
	 */
	public function isProductFrom($prodId, $userId) {
		$query = "SELECT id FROM product WHERE id = ? AND user_id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("ii", $prodId, $userId);

		$statement->execute();
		$result = $statement->get_result();

		if ($result->num_rows == 1) {
			return true;
		}
		return false;
	}

	/**
	 * Bestimmtes Produkt löschen
	 *
	 * @param $prodId
	 * @throws Exception
	 */
	public function deleteProductById($prodId) {
		$query = "SET FOREIGN_KEY_CHECKS = 0";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->execute();

		//Verknüpfung von Tag und Produkt löschen
		$query = "DELETE FROM product_tags WHERE product_id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $prodId);

		$statement->execute();

		//Verknüpfung von auction löschen
		$query = "DELETE FROM auction WHERE product_id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $prodId);

		$statement->execute();

		//Verknüpfung von auction löschen
		$query = "DELETE FROM clicks WHERE product_id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $prodId);

		$statement->execute();

		//Produkt Löschen
		$query = "DELETE FROM product WHERE id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $prodId);

		$statement->execute();

		$query = "SET FOREIGN_KEY_CHECKS = 1";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->execute();
	}

	/**
	 * Ein neues gebot erstellen
	 *
	 * @param $productID
	 * @param $userID
	 * @param $currentprice
	 * @throws Exception
	 */
	public function addBid($productID, $userID, $currentprice) {
		$query = "insert into auction(user_id, product_id, currentPrize) values(?,?,?)";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('iid', $userID, $productID, $currentprice);
		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}
	}

	/**
	 * Ein Klick für die Statistiken in die DB schreiben
	 *
	 * @param $productID
	 * @param $userID
	 * @throws Exception
	 */
	public function setClick($productID, $userID) {
		$query = "insert into clicks(product_id, users_id) values(?, ?);";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $productID, $userID);
		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}

	}

	/**
	 * Schauen ob es einen Bieter hat
	 *
	 * @param $pid
	 * @return bool
	 * @throws Exception
	 */
	public function hasTenderer($pid) {
		$query = "SELECT id FROM auction WHERE product_id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $pid);

		$statement->execute();
		$result = $statement->get_result();

		if ($result->num_rows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Alle Bieter von einem Produkt aufrüfen
	 *
	 * @param $product
	 * @return array
	 * @throws Exception
	 */
	public function getBidsOfProduct($product) {
		$query = "select username, currentprize, user_id from auction join users on user_id = users.id where product_id = ? order by currentprize desc limit 7";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $product);
		$statement->execute();
		$result = $statement->get_result();

		$bids = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_object()) {
				$bid = array();
				array_push($bid, $row->username);
				array_push($bid, $row->currentprize);
				array_push($bid, $row->user_id );
				array_push($bids, $bid);
			}

		}
		return $bids;
	}

	public function getMostExpensiveBidOfProduct($product) {
		if (sizeof($this->getBidsOfProduct($product)) != 0) {
			return $this->getBidsOfProduct($product)[0][1];
		} else {
			return $this->getProductById($product)[3];
		}
	}

	/**
	 * Die Einzigartigen Bieter auslesen
	 *
	 * @param $product
	 * @return array
	 * @throws Exception
	 */
	public function getBidsOfProductDistinct($product) {
		$query = "select distinct user_id from auction where product_id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param("i", $product);
		$statement->execute();
		$result = $statement->get_result();

		$bids = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_object()) {
				array_push($bids, $row->user_id);
			}

		}
		return $bids;
	}

	/**
	 * Ein Produkt als gekauft markieren
	 *
	 * @param $id
	 * @param $isBought
	 * @throws Exception
	 */
	public function setBought($id, $isBought) {
		$query = "update product set isBought = ? where id = ?;";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $isBought, $id);
		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}
	}

	/**
	 * Benutzer nach Produkt id zurückgeben
	 *
	 * @param $id
	 * @return mixed
	 * @throws Exception
	 */
	public function getUserByProduct($id) {
		$query = "select user_id from product where id = ?";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $id);
		$statement->execute();
		$result = $statement->get_result();

		$row = $result->fetch_object();

		return $row->user_id;
	}

	/**
	 * Die abgelaufenen Produkte zurückgeben
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getOldProducts() {
		$date = getdate();
		$date = $date['year'] . "-" . $date['mon'] . "-" . $date['mday'];
		$query = "select id, title, user_id from product where expirationDate < ? and expirationDate not like '0000-00-00'";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('s', $date);
		$statement->execute();
		$result = $statement->get_result();

		$products = array();


		if ($result->num_rows > 0) {
			while ($row = $result->fetch_object()) {
				$product = array();
				array_push($product, $row->id);
				array_push($product, $row->title);
				array_push($product, $row->user_id);
				array_push($products, $product);
			}
		}
		return $products;
	}

	/**
	 * Produkte nach Tags suchen
	 *
	 * @param $tags
	 * @return array
	 * @throws Exception
	 */
	public function searchByTag($tags) {
		$products = array();
		foreach ($tags as $tag) {
			$query = "SELECT product_id FROM product_tags join tags on tags_id = tags.id join product on product_id = product.id where name = ? and isBought = 0";
			$statement = ConnectionHandler::getConnection()->prepare($query);
			$statement->bind_param('s', $tag);
			$statement->execute();
			$result = $statement->get_result();


			if ($result->num_rows > 0) {
				while ($row = $result->fetch_object()) {
					array_push($products, $row->product_id);
				}
			}
		}
		return array_unique($products);
	}

	/**
	 * Schauen ob das Produkt noch nicht gekauft wurde
	 *
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	public function isValidProduct($id) {
		$query = "SELECT id FROM product where id = ? and isBought = 0";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('s', $id);
		$statement->execute();
		$result = $statement->get_result();

		if ($result->num_rows == 1) {
			return true;
		} else {
			return false;
		}
	}


}