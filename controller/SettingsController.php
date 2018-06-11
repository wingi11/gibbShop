<?php
require_once "../lib/View.php";
require_once '../repository/UserRepository.php';

class SettingsController {

	/**
	 * Einstellungsseite anzeigen
	 */
	public function index() {
		if ($_SESSION["loggedin"]) {
			$view = new View("settings");
			$view->title = "Einstellungen";
			$view->repo = new UserRepository();
			$view->display();
		} else {
			$_SESSION["messages"] = array("Du musst eingeloggt sein um auf diese Seite zu gelangen");
			header('Location: /');
		}
	}

	/**
	 * Das geänderte
	 *
	 * @throws Exception
	 */
	public function change() {
		$repo = new UserRepository();
		$target_dir = "../public/images/user_images/";
		$target_file = basename($_FILES["product_image"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

		$target_file = $target_dir . $_SESSION["user"]["id"] . "." . $imageFileType;
		$target_file_name = $_SESSION["user"]["id"] . "." . $imageFileType;

		$password = null;
		$isImage = false;

		if (isset($_POST["submit"])) {
			if (isset($_POST["password"])) {
				if ($_POST["password"] != "") {
					if ($_POST["password"] == $_POST["repeat-password"]) {
						$password = $_POST["password"];
					} else {
						$_SESSION["messages"] = array("Passwörter stimmen nicht überein");
						$password = null;
					}
				}
			} else {
				$password = null;
			}
			if ($_FILES["product_image"]["tmp_name"] != "") {
				$check = getimagesize($_FILES["product_image"]["tmp_name"]);
				if ($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
					move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file);
					$isImage = true;
				} else {
					echo "File is not an image.";
					$uploadOk = 0;
				}
			}
			$repo->updateSettings($isImage, $target_file_name, $password);
			header('Location: /settings');
		}
	}
}