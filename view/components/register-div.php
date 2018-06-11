<div id="register" style="display: none">
	<form action="/Default/doCreate" name="registerform" method="post">
		<div class="input-field col s12">
			<input name="user_name" type="text" id="user_name" required class="validate">
			<label for="user_name">Benutzername</label>
		</div>
        <div class="row">
            <div class="input-field col s7">
                <input name="email" type="text" pattern="[^.']+\.[^.']+" id="email" required class="validate" title="Vorname.Name">
                <label for="email">Email</label>
            </div>
            <div class="input-field col s5">
                <input type="text" value="@iet-gibb.ch" readonly class="valid" tabindex="-1">
            </div>
        </div>
		<div class="input-field col s12">
			<input name="first_name" type="text" id="first_name" required class="validate">
			<label for="first_name">Vorname</label>
		</div>
		<div class="input-field col s12">
			<input name="last_name" type="text" id="last_name" required class="validate">
			<label for="last_name">Nachname</label>
		</div>
		<div class="input-field col s12">
			<input type="text" name="birthdate" id="birthdate" class="datepicker">
			<label for="birthdate">Geburtstag</label>
		</div>

		<div class="input-field col s12">
			<input name="password" id="password" type="password" required value="">
			<label for="password">Passwort</label>
		</div>
		<div class="input-field col s12">
			<input name="repeat-password" id="repeat-password" type="password" required value="">
			<label for="repeat-password">Passwort Wiederholen</label>
		</div>
		<div class="input-field col s12" id="dropdown-div" style="margin-left: 5px">
			<div id="dropdown">
				<select name="team" id="team" required>
					<option value="0" disabled selected>Klasse wählen</option>
					<option value="1">Klasse A</option>
					<option value="2">Klasse B</option>
					<option value="2">Klasse B</option>
					<option value="3">Klasse C</option>
					<option value="4">Klasse D</option>
					<option value="5">Klasse E</option>
					<option value="6">Klasse F</option>
				</select>
			</div>
		</div>


		<div class="row">
			<button class="col s6 btn waves-effect waves-light normal-btn disabled" type="submit" id="registBTN"
					value="Submit" name="send">Registrieren
			</button>
			<div class="col s6 changeTologin" style="padding-top: 5px"><a href="#"
																		  class="waves-effect waves-light darken-text-2"
																		  style=" padding-left: 12px;padding-right: 12px; color: black; font-size: 17px">Einloggen</a>
			</div>
		</div>
	</form>

</div>