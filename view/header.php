<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="css/materialize.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="shortcut icon" type="image/x-icon" href="images/eBbcLogo.ico">
	<title><?php echo $title ?></title>
</head>

<body>
<nav>
	<div class="nav-wrapper">
		<a href="/" class="brand-logo"><span style="font-size: 16pt;margin-left: 5px;">gibbShop</span></a>
		<ul class="left hide-on-med-and-down">
			<li><a href="/">Home</a></li>
			<li><a href="/shop">Shop</a></li>
			<li><a href="/inserieren">Inserieren</a></li>
		</ul>
		<ul class="right hide-on-med-and-down">
			<?php
			if (isset($_SESSION["loggedin"])) {
				?>
				<li class="right"><a data-activates="slide-out" class="button-sidenav"><img
								src="images/person.svg">
						<?php
						if ($_SESSION["user"]["msg"] != null) {
							$anzahlMsgs = 0;
							foreach ($_SESSION["user"]["msg"] as $msg) {
								if ($msg[4] == 0) {
									$anzahlMsgs++;
								}
							}
							if ($anzahlMsgs != 0) {
								echo "<span class='new badge red'>" . $anzahlMsgs . "</span>";
							}

						} ?>
					</a></li>
				<?php
			} else {
				?>
				<li class="right"><a class="loggingbtn"><img src="images/person.svg" alt="Person icon"></a></li>


				<?php
			}
			?>

			<form class="inline searchfield right" action="/shop" method="get">
				<div class="input-field">
					<i class="material-icons prefix search">search</i>
					<input name="suche" placeholder="Suche" type="text" class="whitetext validate">
				</div>
			</form>
		</ul>
	</div>
</nav>
<?php
if (isset($_SESSION["loggedin"])) {
	require "components/sidenav.php";

} else {
	include "components/login-div.php";
	include "components/register-div.php";
} ?>

<?php
if (isset($_SESSION["messages"])) {
	?>
	<div class="row">
		<div class="col s12 m4 l2"></div>
		<div class="col s12 m4 l8">
			<?php
			foreach ($_SESSION["messages"] as $message) {
				?>
				<div class="card-panel red darken-3 white-text">
					<?php echo $message ?>
				</div>
			<?php }
			$_SESSION["messages"] = null;
			?>
		</div>
	</div>
<?php } ?>
<div class="content">