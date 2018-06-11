<?php
require_once "../lib/View.php";
require_once '../repository/TagRepository.php';
require_once '../repository/ProductRepository.php';

class MyproductsController {
	private $tagRepository;
	private $productRepository;

	/**
	 * Tag und Produktrepo den Variablen zuweisen
	 *
	 * MyproductsController constructor.
	 */
	public function __construct() {
		$this->tagRepository = new TagRepository();
		$this->productRepository = new ProductRepository();
	}

	/**
	 * Meine Produkte anzeigen
	 *
	 * @throws Exception
	 */
	public function index() {
		if ($_SESSION["loggedin"]) {
			$view = new View("myproducts");
			$view->title = "Meine Produkte";
			$view->products = $this->productRepository->getProductsFromUser($_SESSION["user"]["id"]);
			$view->prodRepo = $this->productRepository;
			$view->display();
		} else {
			header('Location: /');
		}
	}

	/**
	 * Produkt löschen
	 *
	 * @throws Exception
	 */
	public function delete() {
		if ($_SESSION["loggedin"]) {
			if (isset($_POST["deleteSubmit"])) {
				if ($this->productRepository->isProductFrom($_POST["delProdId"], $_SESSION["user"]["id"])) {
					$this->productRepository->deleteProductById($_POST["delProdId"]);
					header('Location: /myproducts');
				} else {
					$_SESSION["messages"] = array("Aktion kann nicht ausgeführt werden");
					header('Location: /');
				}
			} else {
				header('Location: /');
			}
		} else {
			header('Location: /');
		}
	}
}