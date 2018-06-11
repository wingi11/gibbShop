<?php

class ErrorController {
	/**
	 * Die 404 Seite anzeigen
	 */
	public function index() {
		$view = new View("error");
		$view->title = "Error 404";
		$view->display();
	}
}