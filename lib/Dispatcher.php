<?php

/**
 * Class Dispatcher
 */
class Dispatcher {
	/**
	 * Diese Methode wertet die Request URI aus leitet die Anfrage entsprechend
	 * weiter.
	 */
	public static function dispatch() {
		// URI Zerlegen
		$uri = $_SERVER['REQUEST_URI'];
		$uri = strtok($uri, '?');
		$uri = trim($uri, '/');
		$uriFragments = explode('/', $uri);

		//Namen des Controllers ermitteln
		$controllerName = 'DefaultController';
		if (!empty($uriFragments[0])) {
			$controllerName = $uriFragments[0];
			$controllerName = ucfirst($controllerName);
			$controllerName .= 'Controller';
		}

		//Namen der Methode ermitteln
		$method = 'index';
		if (!empty($uriFragments[1])) {
			$method = $uriFragments[1];
		}


		// Den gewünschten Controller laden falls er existiert
		try {
			if (file_exists("../controller/$controllerName.php")) {
				require_once "../controller/$controllerName.php";
			} else {
				$controllerName = "ErrorController";
				require_once "../controller/$controllerName.php";
			}
		} catch (Exception $e) {
			echo "FATALé ERRoré!";
		}

		// Eine neue Instanz des Controllers wird erstellt und die gewünschte
		//   Methode darauf aufgerufen.
		$controller = new $controllerName();

		if (method_exists($controller, $method)) {
			$controller->$method();


		} else {
			$errorC = new ErrorController();
			$errorC->index();
		}
	}
}
