<?php

require_once '../repository/ProductRepository.php';

class InserierenController {
	/**
	 * Inserierenseite anzeigen
	 */
	public function index() {
		if ($_SESSION["loggedin"]) {
			$view = new View("inserieren");
			$view->title = "inserieren";
			$view->display();
		} else {
			$_SESSION["messages"] = array("Du musst angemeldet sein um ein Inserat zu erstellen");
			header('Location: /');
		}
	}


	/**
	 * Inserat erstellen
	 *
	 * @throws Exception
	 */
	public function create() {
		if ($_SESSION["loggedin"]) {
			$target_dir = "../public/images/product_images/";
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));

			$tags = explode(",", $_POST["product_tags"]);

			if (isset($_POST["submit"])) {
				$check = getimagesize($_FILES["product_image"]["tmp_name"]);
				if ($_POST['price_type'] == 1) {
					$expiryDate = "0";
				} else {
					$expiryDate = $_POST['expiryDate'];
				}

				if ($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
					$imageName = "";
					if (isset($_POST["product_name"]) && isset($_POST["product_description"])) {
						$prodRepo = new productRepository();
						$imageName = $prodRepo->create($_POST["product_name"], $_POST["product_description"], $tags, $_POST["product_price"], $_POST["price_type"], $imageFileType, $expiryDate);
						if ($imageName != "") {
							//Push mitteilung erstellen
							$userRepo = new UserRepository();
							$title = "
							Dein Produkt, {$_POST["product_name"]} wurde erfolgreich Inseriert
							";
							$message = "
							<a href='/product?ProduktID=$imageName' class='btn normal-btn waves-effect waves-light'>Produkt im Shop anzeigen</a>
							";
							$userRepo->setMessageForUser($_SESSION["user"]["id"], $title, $message, 1);
						}
					}
					$target_file = $target_dir . $imageName . "." . $imageFileType;
					move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file);
				} else {
					echo "File is not an image.";
					$uploadOk = 0;
				}

				echo $uploadOk;
				header('Location: /Shop');
			}
		}
	}
}