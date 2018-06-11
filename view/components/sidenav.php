<ul id="slide-out" class="side-nav">
	<li>
		<div class="user-view">
			<div class="background">
			</div>
			<a href="/settings"><img class="circle" src="<?php

				echo "images/user_images/" . $_SESSION["user"]["pb"];

				?>"></a>
			<a href="/settings"><span class="white-text name"><?php echo $_SESSION["user"]["username"] ?></span></a>
			<a href="/settings"><span class="white-text email"><?php echo $_SESSION["user"]["email"] ?></span></a>
		</div>
	</li>
	<li><a class="waves-effect" href="/settings">Einstellungen</a></li>
	<li><a class="waves-effect" href="/myproducts">Meine Produkte</a></li>
	<li><a class="waves-effect" href="/mitteilungen">Mitteilungen
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

			}
			?>
		</a></li>
	<li>
		<div class="divider"></div>
	</li>
	<li><a class="waves-effect" href="/inserieren">Produkt inserieren</a></li>
	<li><a class="waves-effect waves-light red white-text" href="/default/logout">Logout</a></li>
</ul>


