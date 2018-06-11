<?php

require_once "../lib/View.php";
require_once '../repository/UserRepository.php';

class MitteilungenController {

	private $userRepo;

	/**
	 * User Repo setzen
	 *
	 * MitteilungenController constructor.
	 */
	public function __construct() {
		$this->userRepo = new UserRepository();
	}

	/**
	 * Mitteilungenseite anzeigen
	 *
	 * @throws Exception
	 */
	public function index() {
		if ($_SESSION["loggedin"]) {
			$view = new View("mitteilungen");
			$view->title = "Mitteilungen";

			$view->msgs = $_SESSION["user"]["msg"];

			foreach ($_SESSION["user"]["msg"] as $msg) {
				$this->userRepo->setMessageAsRead($msg[0]);
			}

			$view->display();
		} else {
			header('Location: /');
		}
	}

	/**
	 * Mitteilung löschen
	 *
	 * @throws Exception
	 */
	public function delete() {
		if ($_SESSION["loggedin"] && $this->userRepo->isMessageFrom($_POST["delMsgId"], $_SESSION["user"]["id"]) && isset($_POST["delMsg"])) {
			$this->userRepo->deleteMessageById($_POST["delMsgId"]);
		}
		header('Location: /mitteilungen');
	}
}