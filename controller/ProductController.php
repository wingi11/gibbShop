<?php
require_once "../lib/View.php";
require_once '../repository/TagRepository.php';
require_once '../repository/ProductRepository.php';
require_once '../repository/UserRepository.php';

class ProductController {

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
	 * Ein bestimmtes Produkt anzeigen
	 *
	 * @throws Exception
	 */
	public function index() {
		$view = new View("products");
		$view->title = "Kaufen";
		$view->productRepository = $this->productRepository;
		$view->popularTags = $this->tagRepository->getPopularTags();
		$view->display();
		$this->productRepository->setClick($_GET['ProduktID'], $_SESSION['user']['id']);

	}

	/**
	 * Produkt kaufen
	 *
	 * @throws Exception
	 */
	public function validatePurchase() {
		if (isset($_SESSION["loggedin"]) && $this->productRepository->isProductFrom($_POST["prodId"], $_SESSION["user"]["id"])) {
			$product = $this->productRepository->getProductById($_POST["prodId"]);
			$buyer = $this->userRepository->getUserById($_POST["bUser"]);

			if($product[6] == 0){
				$product[3] = $this->productRepository->getBidsOfProduct($product[0])[0][1];
			}

			if (isset($_POST["annehmen"])) {

				$title = "Du hast den Kauf von <span class='bold'>{$product[1]}</span> angenommen";
				$message = "Käufer:
				<table>
        <thead>
          <tr>
              <th>Vorname</th>
              <th>Nachname</th>
              <th>E-Mail</th>
              <th>Produkt</th>
              <th>Preis</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>{$buyer[2]}</td>
            <td>{$buyer[3]}</td>
            <td><a class='cyan-text lighten-5' href='mailto:{$buyer[0]}?Subject=Kauf%20von%20{$product[1]}' target='_top'>{$buyer[0]}</a></td>
            <td><a class='cyan-text lighten-5' href='/product?ProduktID={$_POST["prodId"]}'>{$product[1]}</a></td>
            <td>CHF {$product[3]}</td>
          </tr>
          </tr>
        </tbody>
      </table>
				";
				$this->userRepository->setMessageForUser($_SESSION["user"]["id"], $title, $message, 1);

				$title = "Du hast das Produkt <span class='bold'>{$product[1]}</span> erfolgreich gekauft!";
				$message = "Verkäufer:
				<table>
        <thead>
          <tr>
              <th>Vorname</th>
              <th>Nachname</th>
              <th>E-Mail</th>
              <th>Produkt</th>
              <th>Preis</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>{$_SESSION["user"]["firstname"]}</td>
            <td>{$_SESSION["user"]["lastname"]}</td>
            <td><a class='cyan-text lighten-5' href='mailto:{$_SESSION["user"]["email"]}?Subject=Kauf%20von%20{$product[1]}' target='_top'>{$_SESSION["user"]["email"]}</td>
            <td><a class='cyan-text lighten-5' href='/product?ProduktID={$_POST["prodId"]}'>{$product[1]}</a></td>
            <td>CHF {$product[3]}</td>
          </tr>
          </tr>
        </tbody>
      </table>";
				$this->userRepository->setMessageForUser($buyer[4], $title, $message, 1);

			} else if (isset($_POST["ablehnen"])) {
				$title = "Du hast den Kauf von <span class='bold'>{$product[1]}</span> abgelehnt";
				$message = "";
				$this->userRepository->setMessageForUser($_SESSION["user"]["id"], $title, $message, 3);

				$title = "Der Kauf von <span class='bold'>{$product[1]}</span> wurde vom Verkäufer abgelehnt";
				$message = "";
				$this->userRepository->setMessageForUser($buyer[4], $title, $message, 3);

				$this->productRepository->setBought($_POST["prodId"], 0);
			}
			$this->userRepository->deleteMessageById($_POST["mId"]);
		}
		header('Location: /mitteilungen');
	}

	/**
	 * Produkt kaufen
	 *
	 * @throws Exception
	 */
	public function kaufen() {
		if (isset($_SESSION["loggedin"])) {
			if (isset($_POST["buyProd"]) && !$this->productRepository->isProductFrom($_POST["prodId"], $_SESSION["user"]["id"])) {
				$this->productRepository->setBought($_POST["prodId"], 1);
				$productName = $this->productRepository->getProductById($_POST["prodId"])[1];
				$user = $this->productRepository->getUserByProduct($_POST["prodId"]);

				$title = "<span class='bold'>{$_SESSION["user"]["firstname"]} {$_SESSION["user"]["lastname"]}</span> möchte dein Produkt, <span class='bold'>$productName</span> kaufen";
				$message = "Du kannst den Kauf ablehnen oder annehmen. Wenn du ihn Ablehnst dann wird er wieder im Shop aufgelistet
			<form method='post' action='/product/validatePurchase'>
			<input type='hidden' name='prodId' value='{$_POST["prodId"]}'/>
			<input type='hidden' name='mId'/>
			<input type='hidden' name='bUser' value='{$_SESSION["user"]["id"]}'/>
			<input type='submit' name='annehmen' value='Annehmen' class='btn green waves-effect'>
			<input type='submit' name='ablehnen' value='Ablehnen' class='btn red waves-effect'>
</form>
			";
				$this->userRepository->setMessageForUser($user, $title, $message, 1);

				$title = "Du hast den Kauf an <span class='bold'>$productName</span> angefragt";
				$message = "Der Verkäufer des Produkts kann deinen Kauf Ab und Annehmen falls er sich entschieden hat wird dir hier eine Nachricht gesendet.";
				$this->userRepository->setMessageForUser($_SESSION["user"]["id"], $title, $message, 2);
				header('Location: /shop');
			} else {
				$_SESSION["messages"] = array("Du kannst nicht dein eigenes Produkt kaufen!");
				header('Location: /product?ProduktID=' . $_POST["prodId"]);
			}
		} else {
			$_SESSION["messages"] = array("Du musst angemeldet sein um ein Produkt zu kaufen");
			header('Location: /shop');
		}
	}

	/**
	 * Für Produkt bieten
	 *
	 * @throws Exception
	 */
	public function AddAuction() {
		if (isset($_SESSION["loggedin"]) && isset($_POST["send"])) {
			$id = $_POST['id'];
			$price = $_POST['price_offset'];
			$oldPrice = $this->productRepository->getMostExpensiveBidOfProduct($id);
			$userOfProduct = $this->productRepository->getUserByProduct($id);
			if ($price >= 1 && !$this->productRepository->isProductFrom($id, $_SESSION["user"]["id"])) {
				$this->productRepository->addBid($id, $_SESSION['user']['id'], $price + $oldPrice);
				$title = '<span class="bold">' . $_SESSION['user']['username'] . '</span>' . " hat " . '<span class="bold">' . 'CHF ' . ($price + $oldPrice) . '</span>' . " für dein Produkt " . '<span class="bold">' . $this->productRepository->getProductById($id)[1] . '</span>' . " geboten.";
				$message = "<a href='/product?ProduktID=$id' class='btn normal-btn waves-effect waves-light'>Produkt im Shop anzeigen</a>";
				$this->userRepository->setMessageForUser($userOfProduct, $title, $message, 1);
			} else {
				$_SESSION["messages"] = array("Du kannst nicht für dein eigenes Produkt oder weniger als CHF 1 bieten");
			}
		} else {
			$_SESSION["messages"] = array("Du musst eingeloggt sein um bieten zu können!");
		}
		header('Location: /product?ProduktID=' . $id);
	}

}