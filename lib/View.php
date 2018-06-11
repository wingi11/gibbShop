<?php

class View {
	private $viewfile;

	private $properties = array();

	/**
	 * Das viewfile auswählen
	 *
	 * View constructor.
	 * @param $viewfile
	 */
	public function __construct($viewfile) {
		$this->viewfile = "../view/$viewfile.php";
	}

	/**
	 * Per magische setter eine Variable erstellen
	 *
	 * @param $key
	 * @param $value
	 */
	public function __set($key, $value) {
		if (!isset($this->$key)) {
			$this->properties[$key] = $value;
		}
	}

	/**
	 * Per magische getter eine Variable zurückgeben
	 *
	 * @param $key
	 * @return mixed
	 */
	public function __get($key) {
		if (isset($this->properties[$key])) {
			return $this->properties[$key];
		}
	}

	/**
	 * Die seite anzeigen
	 */
	public function display() {
		extract($this->properties);

		require '../view/header.php';
		require $this->viewfile;
		require '../view/footer.php';
		//require './../view/footer.php';
	}
}