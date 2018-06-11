<div class="row">
	<div class="col s4"></div>
	<div class="col s4">
		<form action="settings/change" method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="col s4"></div>
				<div class="col s4">
					<div class="file-field round user-image">
						<img id="preview-img" class="user-preview-image round" src="<?php
						echo "images/user_images/" . $_SESSION["user"]["pb"];

						?>" onerror="imgNotFound()" alt="Product Image">
						<input type="file" name="product_image" onchange="readURL(this);">
						<p class="center white-text">Bild auswählen</p>
					</div>
				</div>
				<div class="col s4"></div>
			</div>

			<div class="card">
				<div class="card-content">
					<span class="card-title">Einstellungen</span>

					<div class="input-field">
						<input id="password" type="password" name="password" class="validate black-text">
						<label for="password">Neues Passwort</label>
					</div>
					<div class="input-field">
						<input id="repeat-password" type="password" name="repeat-password" class="validate black-text">
						<label for="repeat-password">Passwort wiederholen</label>
					</div>
					<input type="submit" value="Änderungen Speichern" name="submit"
						   class="waves-light waves-effect btn normal-btn">
		</form>
	</div>
</div>
</div>
<div class="col s4"></div>
</div>