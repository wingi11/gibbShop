<div id="logging" style="display: none">
	<form action="/default/login" method="post">
		<div class="input-field col s12">
			<input name="user_name" type="text" class="validate">
			<label for="user_name">Benutzername</label>
		</div>
		<div class="input-field col s12">
			<input name="password" type="password" class="validate">
			<label for="password">Passwort</label>
		</div>

		<div class="row">
			<button class="col s6 btn waves-effect waves-light normal-btn" type="submit" name="send" value="Submit"
					style="padding-left: 12px;padding-right: 12px">Einlogen
			</button>
			<div class="col s6 changeToRegist" style="padding-top: 5px"><a href="#"
																		   class="waves-effect waves-light darken-text-2"
																		   style="padding-left: 12px;padding-right: 12px; color: black; font-size: 17px">Registrieren</a>
			</div>
		</div>
	</form>

</div>