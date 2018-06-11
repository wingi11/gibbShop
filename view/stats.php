<?php
$height = 300;
?>

<div class="row">
	<div class="col s1"></div>
	<div class="col s10">
		<ul id="tabs-swipe-demo" class="tabs">
			<li class="tab col s6"><a class="active" href="#test-swipe-1">Ansichten pro Team</a></li>
			<li class="tab col s6 <?php if ($product[6] == 1) {
				echo " disabled";
			} ?>"><a class="" href="#test-swipe-2">Auktions Statistiken</a></li>
		</ul>
		<div id="test-swipe-1" class="col s12">
			<div class="col s6"></div>
			<p class="card-title">Ansichten pro Klasse</p>
			<div class="diagram-stats col s6">
				<?php include "components/diagram-element.php"; ?>
			</div>
		</div>
		<div id="test-swipe-2" class="col s12">
			<div class="row stats-container">
				<div class="card col s3 ebbcColor">
					<div class="card-content white-text">
						<span class="card-title"><span class="bold"><?= $bieter ?></span> Bieter <i
									class="material-icons right inline stat-icon">person</i></span>
					</div>
				</div>
				<div class="col s1"></div>
				<div class="card col s3 ebbcColor">
					<div class="card-content white-text">
						<span class="card-title"><span class="bold"><?= $rise ?>%</span> Preisanstieg <i
									class="material-icons right inline stat-icon">trending_up</i></span>
					</div>
				</div>
				<div class="col s1"></div>
				<div class="card col s3 ebbcColor">
					<div class="card-content white-text">
						<span class="card-title"><span class="bold"><?= $product[3] ?> CHF</span> <span
									class="stats-small-text">Startpreis</span><i
									class="material-icons right inline stat-icon">attach_money</i></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col s12 m4 l2"></div>
</div>
