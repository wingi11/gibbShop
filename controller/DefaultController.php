<?php
require_once "../lib/View.php";
require_once '../repository/UserRepository.php';

class DefaultController {
	private $userRepository;

	/**
	 * User Repository der variable zuweisen
	 *
	 * AdminController constructor.
	 */
	public function __construct() {
		$this->userRepository = new UserRepository();
	}

	/**
	 * Das Home anzeigen
	 */
	public function index() {
		$view = new View("default_index");
		$view->title = "Home";
		$view->display();
	}

	/**
	 * Benutzer erstellen
	 *
	 * @throws Exception
	 */
	public function doCreate() {
		if ($_POST['send']) {
			if ($_POST['password'] == $_POST['repeat-password']) {
				$username = $_POST['user_name'];
				$firstName = $_POST['first_name'];
				$lastName = $_POST['last_name'];
				$email = $_POST['email'] . "@bbcag.ch";
				$date = $_POST['birthdate'];
				$password = $_POST['password'];
				$teamId = $_POST['team'];


				if ($this->userRepository->create($username, $firstName, $lastName, $email, $date, $password, $teamId)) {
					$title = "Erfolgreich Registriert";
					$message = "Herzlich Willkommen auf der eBbc Verkaufswebseite!";
					$this->userRepository->setMessageForUser($_SESSION["user"]["id"], $title, $message, 1);
					header('Location: /settings');
				} else {
					header('Location: /');
				}
			} else {
				$_SESSION["messages"] = array("Die Passwörter stimmen nicht überein");
				header('Location: /');
			}

		}

	}

	/**
	 * Benutzer einloggen
	 *
	 * @throws Exception
	 */
	public function login() {
		if (isset($_POST['user_name']) && isset($_POST['password'])) {

			if ($this->userRepository->loginUser($_POST['user_name'], $_POST['password'])) {
				header('Location: /shop');
			} else {
				header('Location: /');
			}
		} else {
			$_SESSION["messages"] = array("Die Loginfelder dürfen nicht leer sein");
			header('Location: /');
		}
	}

	/**
	 * Benutzer ausloggen
	 */
	public function logout() {
		unset($_SESSION["user"]["email"]);
		unset($_SESSION["user"]["username"]);
		unset($_SESSION["loggedin"]);
		session_destroy();
		header('Location: /');
	}


}