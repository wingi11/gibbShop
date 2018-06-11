<div id="modal1" class="modal">
	<form action="admin/sendmsg" method="post">
		<div class="modal-content">
			<h4>Nachricht schreiben</h4>
			<div class="row">
				<div class="input-field">
					<input id="title" type="text" name="title">
					<label for="title">Titel der Nachricht</label>
				</div>
				<div class="input-field">
					<textarea id="desc" maxlength="1000" data-length="1000" name="desc"
							  class="materialize-textarea"></textarea>
					<label for="desc">Nachricht</label>
				</div>
				<div class="input-field col s12">
					<select name="color">
						<option value="1">Grün</option>
						<option value="2">Gelb</option>
						<option value="3">Rot</option>
					</select>
					<label>Nachrichtenfarbe</label>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" value="" name="userid" id="userid">
			<input type="submit" name="submit" value="Nachricht Senden"
				   class="modal-action modal-close waves-effect waves-green btn-flat">
		</div>
	</form>
</div>

<div id="confirmationModal" class="modal">
	<form action="admin/deleteUser" method="post">
		<div class="modal-content">
			<h4>Benutzer wirklich löschen</h4>
		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Abbrechen</a>
			<input type="hidden" value="" name="userid" id="userid2">
			<input type="submit" name="submit" value="Benutzer Löschen"
				   class="modal-action modal-close red-text waves-effect waves-red btn-flat">
		</div>
	</form>
</div>

<div class="row">
	<div class="col s12 m4 l2"></div>
	<div class="col s12 m4 l8">
		<table class="highlight">
			<thead>
			<tr>
				<th>Username</th>
				<th>Vorname</th>
				<th>Nachname</th>
				<th>E-Mail</th>
				<th>Actions</th>
			</tr>
			</thead>

			<tbody>
			<?php
			foreach ($users as $user) {
				?>
				<tr>
					<td><?= $user[1] ?></td>
					<td><?= $user[2] ?></td>
					<td><?= $user[3] ?></td>
					<td><?= $user[0] ?></td>
					<td>
						<?php if ($user[4] == 1) { ?>
							<form action="admin/deactivateUser" class="inline" method="post">
								<input type="hidden" name="userid" value="<?= $user[5] ?>">
								<button name="submit" value="submit" type="submit"
										class="btn-floating btn-small red waves-effect tooltipped" data-position="top"
										data-tooltip="Benutzerkonto sperren"><i class="material-icons">lock_outline</i>
								</button>
							</form>
						<?php } else { ?>
							<form action="admin/activateUser" class="inline" method="post">
								<input type="hidden" name="userid" value="<?= $user[5] ?>">
								<button name="submit" value="submit" type="submit"
										class="btn-floating btn-small green waves-effect tooltipped" data-position="top"
										data-tooltip="Benutzerkonto entsperren"><i class="material-icons">lock_open</i>
								</button>
							</form>
						<?php } ?>
						<button class="btn-floating btn-small green waves-effect tooltipped modal-trigger"
								value="<?= $user[5] ?>" data-target="modal1" onclick="fillData(this)"
								data-position="top" data-tooltip="Nachricht senden" href="#modal1"><i
									class="material-icons">email</i></button>
						<button class="btn-floating btn-small waves-effect red darken-4 tooltipped modal-trigger"
								value="<?= $user[5] ?>" data-target="modal1" onclick="fillData(this)"
								data-position="top" data-tooltip="Benutzerkonto Löschen" href="#confirmationModal"><i
									class="material-icons">clear</i></button>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
	</div>
	<div class="col s12 m4 l2"></div>
</div>