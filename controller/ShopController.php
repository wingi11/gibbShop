<?php
require_once "../lib/View.php";
require_once '../repository/TagRepository.php';
require_once '../repository/ProductRepository.php';
require_once '../repository/UserRepository.php';

class ShopController {
	private $tagRepository;
	private $productRepository;
	private $userRepository;

	/**
	 * Tag, User und Produktrepo den Variablen zuweisen
	 *
	 * ProductController constructor.
	 */
	public function __construct() {
		$this->tagRepository = new TagRepository();
		$this->productRepository = new ProductRepository();
		$this->userRepository = new UserRepository();
	}

	/**
	 * Shop anzeigen
	 *
	 * @throws Exception
	 */
	public function index() {
		$view = new View("shop");
		$view->title = "Shop";
		$view->popularTags = $this->tagRepository->getPopularTags();
		$view->products = $this->productRepository->getProducts();
		$view->productRepository = $this->productRepository;
		$view->userRepository = $this->userRepository;
		$this->sendExpiredMessage();
		$view->display();
	}

	/**
	 * Dem Verkäufer eine ablaufnachricht über sein Produkt senden
	 *
	 * @throws Exception
	 */
	public function sendExpiredMessage() {
		foreach ($this->productRepository->getOldProducts() as $product) {
			if($this->productRepository->isValidProduct($product[0])) {
				if(!$this->productRepository->hasTenderer($product[0])) {
					$title = 'Dein Produkt, ' . '<span class="bold">' . $product[1] . '</span>' . ' ist abgelaufen.';
					$message = "Dein Produkt ist ab sofort nicht mehr verfügbar.";
					$this->userRepository->setMessageForUser($product[2], $title, $message, 2);
				} else {
					$buyer = $this->userRepository->getUserById($this->productRepository->getBidsOfProduct($product[0])[0][2]);

					$title = "<span class='bold'>{$buyer[2]} {$buyer[3]}</span> möchte dein Produkt, <span class='bold'>{$product[1]}</span> kaufen";
					$message = "Du kannst den Kauf ablehnen oder annehmen. Wenn du ihn Ablehnst dann wird er wieder im Shop aufgelistet
					<form method='post' action='/product/validatePurchase'>
					<input type='hidden' name='prodId' value='{$product[0]}'/>
					<input type='hidden' name='mId'/>
					<input type='hidden' name='bUser' value='{$buyer[4]}'/>
					<input type='submit' name='annehmen' value='Annehmen' class='btn green waves-effect'>
					<input type='submit' name='ablehnen' value='Ablehnen' class='btn red waves-effect'>
					</form>
					";
					$this->userRepository->setMessageForUser($product[2], $title, $message, 1);
				}
				$this->productRepository->setBought($product[0], 1);
			}
		}
	}

	/**
	 * Nach Produkten nach Tags suchen
	 *
	 * @param $tags
	 * @return array
	 * @throws Exception
	 */
	public function search($tags) {
		$tagArray = explode(" ", htmlspecialchars($tags));
		$products = $this->productRepository->searchByTag($tagArray);
		$searchedProducts = array();
		foreach ($products as $product) {
			array_push($searchedProducts, $this->productRepository->getProductById($product));
		}
		return $searchedProducts;

	}
}