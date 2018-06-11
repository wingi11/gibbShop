<?php
require_once "../lib/View.php";
require_once '../repository/UserRepository.php';
require_once '../repository/ProductRepository.php';

class AdminController {

	private $userRepository;
	private $productRepository;

	/**
	 * User Repository der variable zuweisen
	 *
	 * AdminController constructor.
	 */
	public function __construct() {
		$this->userRepository = new UserRepository();
		$this->productRepository = new ProductRepository();
	}

	/**
	 * Auf das login oder falls eingeloggt auf die Verwaltenseite weiterleiten
	 */
	public function index() {
		if (!isset($_SESSION["adminloggedin"])) {
			$view = new View("adminlogin");
			$view->title = "Admin Login";
			$view->display();
		} else {
			$view = new View("admin");
			$view->title = "Admin Site";
			$view->users = $this->userRepository->getAllUsers();
			$view->display();
		}
	}

	/**
	 * Admin login
	 */
	public function login() {
		if (!$this->userRepository->loginAdmin($_POST["username"], $_POST["password"])) {
			$_SESSION["messages"] = array("Falsche Logindaten");
		}
		header('Location: /admin');
	}

	/**
	 * Benutzer deaktiveren
	 */
	public function deactivateUser() {
		if (isset($_SESSION["adminloggedin"])) {
			$this->userRepository->lockUser($_POST["userid"], 0);
			header('Location: /admin');
		} else {
			header('Location: /');
		}
	}

	/**
	 * Benutzer aktiveren
	 */
	public function activateUser() {
		if (isset($_SESSION["adminloggedin"])) {
			$this->userRepository->lockUser($_POST["userid"], 1);
			header('Location: /admin');
		} else {
			header('Location: /');
		}
	}

	/**
	 * Admin ausloggen
	 */
	public function logout() {
		session_destroy();
		header('Location: /admin');
	}

	/**
	 * Nachricht einem Benutzer senden
	 *
	 * @throws Exception
	 */
	public function sendMsg() {
		if (isset($_SESSION["adminloggedin"])) {
			$this->userRepository->setMessageForUser($_POST["userid"], $_POST["title"], $_POST["desc"], $_POST["color"]);
			header('Location: /admin');
		} else {
			header('Location: /');
		}
	}

	/**
	 * Benutzer löschen
	 *
	 * @throws Exception
	 */
	public function deleteUser() {
		if (isset($_SESSION["adminloggedin"])) {
			foreach($this->productRepository->getProductsFromUser($_POST["userid"]) as $product){
				$this->productRepository->deleteProductById($product[0]);
			}
			$this->userRepository->deleteUser("users", $_POST["userid"]);
			header('Location: /admin');
		} else {
			header('Location: /');
		}
	}
}