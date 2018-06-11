<?php

class ImpressumController {
	/**
	 * Impressum anzeigen
	 */
	public function index() {
		$view = new View("impressum");
		$view->title = "Impressum";
		$view->display();
	}
}