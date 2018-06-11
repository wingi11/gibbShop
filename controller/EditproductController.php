<?php

require_once '../repository/ProductRepository.php';

class EditproductController {

	/**
	 * Die Produkteditierenseite anzeiten
	 *
	 * @throws Exception
	 */
	public function index() {
		$pr = new ProductRepository();
		if ($_SESSION["loggedin"] && isset($_POST["edit"]) && $pr->isProductFrom($_POST["prodid"], $_SESSION["user"]["id"])) {
			$product = $pr->getProductById($_POST["prodid"]);
			$view = new View("editproduct");
			$view->title = "Produkt bearbeiten";
			$view->prodId = $product[0];
			$view->prodName = $product[1];
			$order = array("<br/>", "<br>");
			$replace = '&#13';

			$description = str_replace($order, $replace, $product[2]);
			$view->description = $description;
			$price = explode(".", $product[3]);
			$view->priceF = $price[0];
			$view->prodId = $product[0];
			$view->imgName = $product[5];
			if (isset($price[1])) {
				$view->priceR = $price[1];
			} else {
				$view->priceR = "00";
			}

			$view->display();
		} else {
			header('Location: /');
		}
	}

	/**
	 * Die Produktänderungen Speichern
	 *
	 * @throws Exception
	 */
	public function update() {
		if ($_SESSION["loggedin"]) {
			$target_dir = "../public/images/product_images/";
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));

			if (isset($_POST["submit"])) {
				$check = getimagesize($_FILES["product_image"]["tmp_name"]);
				if ($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
					$imageName = "";
					$target_file = $target_dir . $_POST["id"] . "." . $imageFileType;
					move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file);
				} else {
					echo "File is not an image.";
					$uploadOk = 0;
				}
				if (isset($_POST["product_name"]) && isset($_POST["product_description"])) {
					$prodRepo = new productRepository();
					$prodRepo->update($_POST["id"], $_POST["product_name"], $_POST["product_description"], $_POST["product_price"], $_POST["price_type"], $imageFileType, $_POST['expiryDateEdit']);
				}
				echo $uploadOk;
				header('Location: /myproducts');
			}
		}
	}
}