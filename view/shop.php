<?php
require_once '../controller/ShopController.php';
require_once '../controller/ShopController.php';
$shopController = new ShopController();
?>

	<div class="row">
		<div class="popularTags">
			<div class="tagList">
				<span> Beliebte Tags: </span>
				<?php
				foreach ($popularTags as $text) {
					$tagHref = '/shop?suche=' . $text;
					?>
					<div class="chip">
						<a href="<?= $tagHref ?>">
							<?= $text ?>
						</a>
					</div>
					<?php
				}
				?>


			</div>
		</div>
	</div>
<?php if (!isset($_GET['suche'])) {
	?>
	<div class="products">
		<div class="row">
			<?php
			foreach ($products as $product) {
				$tags = $productRepository->getTagByProduct($product[0]);

				?>
				<div class="col s4">
					<div class="card shop z-depth-4">
						<div class="card-img waves-effect waves-block waves-light">
							<img class="activator" src="images/product_images/<?= $product[5] ?>"
								 alt="<?= $product[1] ?>">
						</div>
						<div class="card-action">
							<p class="card-title activator grey-text text-darken-4"><?= $product[1] ?><i
										class="material-icons right">more_vert</i></p>
							<p class="grey-text text-darken-1"><span
										class="bold">CHF <?= $productRepository->getMostExpensiveBidOfProduct($product[0]) ?></span>
								von <?php echo $userRepository->getUserById($productRepository->getUserByProduct($product[0]))[1] ?>
							</p>
							<?php
							foreach ($tags as $tag) {
								?>
								<div class="chip">
									<?= $tag ?>
								</div>
								<?php
							}
							?>
						</div>
						<div class="card-reveal stick-of-btn">
                            <span class="card-title grey-text text-darken-4"><?= $product[1] ?><i
										class="material-icons right">close</i></span>
							<div class="discription">
								<p><?= $product[2] ?></p>
							</div>
							<form action="/product" method="get">
								<input type="hidden" name="ProduktID" value="<?= $product[0] ?>">
								<?php
								if ($product[6] == 1) {
									?>
									<button class="col s12 btn waves-effect waves-light normal-btn stick-on-btn"
											type="submit" value="Submit">Kaufen
									</button>
									<?php
								} else {
									?>
									<button class="col s12 btn waves-effect waves-light normal-btn stick-on-btn"
											type="submit" value="Submit">Bieten
									</button>
									<?php
								}
								?>
							</form>
						</div>
					</div>
				</div>
				<?php
			}

			?>
		</div>

	</div>
<?php } else if (isset($shopController->search( $_GET['suche'])[0])) { ?>
	<div class="products">
		<div class="row">
			<?php
			foreach ($shopController->search(htmlspecialchars($_GET['suche'])) as $product) {
				$tags = $productRepository->getTagByProduct($product[0]);

				?>
				<div class="col s4">
					<div class="card shop z-depth-4">
						<div class="card-img waves-effect waves-block waves-light">
							<img class="activator" src="images/product_images/<?= $product[5] ?>"
								 alt="<?= $product[1] ?>">
						</div>
						<div class="card-action">
							<p class="card-title activator grey-text text-darken-4"><?= $product[1] ?><i
										class="material-icons right">more_vert</i></p>
							<p class="grey-text text-darken-1"><span
										class="bold">CHF <?= $productRepository->getMostExpensiveBidOfProduct($product[0]) ?></span>
								von <?php echo $userRepository->getUserById($productRepository->getUserByProduct($product[0]))[1] ?>
							</p>
							<?php
							foreach ($tags as $tag) {
								?>
								<div class="chip">
									<?= $tag ?>
								</div>
								<?php
							}
							?>
						</div>
						<div class="card-reveal stick-of-btn">
                            <span class="card-title grey-text text-darken-4"><?= $product[1] ?><i
										class="material-icons right">close</i></span>
							<div class="discription">
								<p><?= $product[2] ?></p>
							</div>
							<form action="/product" method="get">
								<input type="hidden" name="ProduktID" value="<?= $product[0] ?>">
								<?php
								if ($product[6] == 1) {
									?>
									<button class="col s12 btn waves-effect waves-light normal-btn stick-on-btn"
											type="submit" value="Submit">Kaufen
									</button>
									<?php
								} else {
									?>
									<button class="col s12 btn waves-effect waves-light normal-btn stick-on-btn"
											type="submit" value="Submit">Bieten
									</button>
									<?php
								}
								?>
							</form>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>

	</div>
	<?php
} else {
	?>
	<div class="col s4">
		<div class="card shop z-depth-4">
			<div class="card-content">
                <span class="card-title">Leider konnten wir keine passenden Produkte finden: <span
							class="bold"><?= htmlspecialchars($_GET["suche"]) ?></span></span>
			</div>
		</div>
	</div>
	<?php
}
?>