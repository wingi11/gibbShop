<?php
require_once "../lib/Dispatcher.php";
require_once '../lib/View.php';
include "../repository/UserRepository.php";

$userRepo = new UserRepository();

session_start();
// Bei jedem seiten neuladen die Nachrichten für einen Benutzer aktualisieren
if (isset($_SESSION["loggedin"])) {
	$_SESSION["user"]["msg"] = $userRepo->getMessagesFromUser($_SESSION["user"]["id"]);

	if($userRepo->isUserLocked($_SESSION["user"]["id"])){
		unset($_SESSION["loggedin"]);
		session_destroy();
	}
}

$dispatcher = new Dispatcher();
$dispatcher->dispatch();