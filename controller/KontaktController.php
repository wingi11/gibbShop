<?php

class KontaktController {
	/**
	 * Kontaktseite anzeigen
	 */
	public function index() {
		$view = new View("kontakt");
		$view->title = "Kontakt";
		$view->display();
	}
}