<?php
require_once '../controller/MyproductsController.php';
$shopController = new MyproductsController();
?>
<!-- Delete Modal -->
<div id="delModal" class="modal">
	<div class="modal-content">
		<h4>Produkt wirklich löschen?</h4>
		<p>Diese Aktion kann nicht Rückgängig gemacht werden.</p>
	</div>
	<div class="modal-footer">
		<form method="post" action="myproducts/delete/">
			<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Abbrechen</a>
			<input type="submit" name="deleteSubmit" value="Produkt Löschen"
				   class="modal-action modal-close red-text waves-effect waves-red btn-flat"/>
			<input type="hidden" id="delProdId" name="delProdId" value="">
		</form>
	</div>
</div>

<div class="shops">
	<div class="row">
		<?php
		foreach ($products as $product) {
			$tags = $prodRepo->getTagByProduct($product[0]);
			?>
			<div class="col s4">
				<div class="card z-depth-4">
					<div class="card-img waves-effect waves-block waves-light">
						<img class="activator" src="images/product_images/<?= $product[5] ?>" alt="<?= $product[5] ?>">
					</div>
					<div class="card-action">
						<p class="card-title activator grey-text text-darken-4 inline"><?= $product[1] ?></p>

						<div class="fixed-action-btn right click-to-toggle">
							<a class="btn-floating normal-btn">
								<i class="large material-icons">menu</i>
							</a>
							<ul class="edit-ul">
								<form action="/stats" method="post" class="inline">
									<input type="hidden" name="prodid" value="<?= $product[0] ?>">
									<li><input type="submit" name="stats" value="trending_up"
											   class="btn-floating round-btn-col material-icons"/></li>
								</form>
								<form action="/editproduct" method="post" class="inline">
									<input type="hidden" name="prodid" value="<?= $product[0] ?>">
									<li><input type="submit" name="edit" value="mode_edit"
											   class="btn-floating round-btn-col material-icons"></li>
								</form>
								<li>
									<button type="submit" name="edit" value="<?= $product[0] ?>"
											onclick="deleteProd(this)"
											class="btn-floating red material-icons modal-trigger">delete
									</button>
							</ul>
						</div>
						<p class="grey-text text-darken-1"> CHF <?= $product[3] ?></p>
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
					<div class="card-reveal">
						<span class="card-title grey-text text-darken-4"><?= $product[1] ?><i
									class="material-icons right">close</i></span>
						<p><?= $product[2] ?></p>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>