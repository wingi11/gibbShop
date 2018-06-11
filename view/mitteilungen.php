<div class="row">
	<div class="col s12 m4 l2"></div>
	<div class="col s12 m4 l8">
		<?php


		foreach ($msgs as $msg) {
			switch ($msg[3]) {
				case 1:
					msg($msg[0], $msg[1], $msg[2], "green darken-3");
					break;
				case 2:
					msg($msg[0], $msg[1], $msg[2], "yellow darken-3");
					break;
				case 3:
					msg($msg[0], $msg[1], $msg[2], "red darken-3");
					break;
			}
		}

		function msg($id, $title, $desc, $color) {
			?>
			<div class="card">
				<div class="card-panel <?= $color ?> white-text">
		<span class="card-title"><?= $title ?>
			<form method="post" action="mitteilungen/delete" class="inline">
			<input type="hidden" value="<?= $id ?>" name="delMsgId"/>
			<input class="material-icons right transparent white-text no-border waves-effect" value="close"
				   name="delMsg" type="submit"/>
		</form>
		</span>
					<p class="card-content"><?= $desc ?></p>
				</div>
			</div>
			<?php
		}

		?>
	</div>
</div>

<script>

</script>
