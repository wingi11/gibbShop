<?php
require_once '../controller/ProductController.php';
$productcontroller = new ProductController();
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
<?php
if ($productRepository->isValidProduct($_GET['ProduktID'])) {
	?>
	<div class="products">
		<div class="row">
			<div class="col s2"></div>

			<?php $product = $productRepository->getProductById($_GET["ProduktID"]);
			$tags = $productRepository->getTagByProduct($product[0]) ?>

			<div class="col s4">
				<div class="card product z-depth-4">
					<div class="card-img waves-effect waves-block waves-light">
						<img class="activator" src="images/product_images/<?= $product[5] ?>" alt="<?= $product[1] ?>">

					</div>
					<div class="card-action">
						<p class="card-title activator grey-text text-darken-4"><?= $product[1] ?></p>
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
				</div>
			</div>


			<div class="col s4">
				<div class="col s12">
					<div class="card product z-depth-4">
						<div class="card-action ">
							<p class="card-title activator grey-text text-darken-4">Beschreibung:</p>
							<p><?= $product[2] ?></p>
						</div>
					</div>
				</div>
				<div class="col s12">
					<?php
					if ($product[6] == 1) {
						?>
						<div class="product">
							<form action="/product/kaufen" method="post">
								<input type="hidden" name="prodId" value="<?= $product[0] ?>">
								<input type="submit" name="buyProd" href="/inserieren"
									   value="Für CHF <?= $product[3] ?> Kaufen"
									   class="btn waves-effect waves-light darken-text-2 normal-btn "/>
							</form>
						</div>

						<?php
					} else {
						?>
						<div class="card product fix-height z-depth-4">
							<div class="card-action">
								<p class="card-title activator grey-text text-darken-4 ">
									CHF <?= $productRepository->getMostExpensiveBidOfProduct($product[0]) ?></p>
								<form method="post" action="/product/AddAuction">
									<div class="col s6">
										<input type="hidden" name="old_price" value="<?= $product[3] ?>">
										<input type="hidden" name="id" value="<?= $product[0] ?>">
										<div class="input-field inline price_franken">
											<input id="price_franken" maxlength="4" name="price_offset"
												   oninput="maxLengthCheck(this)" placeholder="0000" type="number"
												   class="validate black-text price_franken">
										</div>
										CHF
									</div>
									<button class=" bidbutton col s6 btn waves-effect waves-light normal-btn"
											type="submit" value="Submit" name="send">dazu Bieten
									</button>

								</form>
							</div>
						</div>
						<?php
						if ($productRepository->hasTenderer($product[0])) {
							?>
							<div class="card product z-depth-4">
								<div class="card-action">
									<p class="bidfont">
										<?php
										foreach ($productRepository->getBidsofProduct($product[0]) as $bid) {
											echo $bid[0] . " hat CHF " . $bid[1] . " für dieses Produkt geboten." . "<br>";
										}


										?>
									</p>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>

				<div class="col s2"></div>
			</div>

		</div>
	</div>
	<?php
}


