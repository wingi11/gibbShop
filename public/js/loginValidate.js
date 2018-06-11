var password = document.getElementById("password");
var repeat_password = document.getElementById("repeat-password");

var registBtn = document.getElementById("registBTN");
var user_name = document.getElementById("user_name");
var email = document.getElementById("email");
var first_name = document.getElementById("first_name");
var last_name = document.getElementById("last_name");
var birthdate = document.getElementById("birthdate");
var team = document.getElementById("team");
var teamDropdown = document.getElementById("dropdown");

var inputs = [user_name, email, first_name, last_name, birthdate, password, repeat_password];

var isTeamValid = false;

/**Passwort vergleichen*/
function validatePassword() {

	password.classList.remove("invalid");
	repeat_password.classList.remove("invalid");
	password.classList.remove("valid");
	repeat_password.classList.remove("valid");
	if (password.value !== repeat_password.value) {
		repeat_password.classList.add('invalid');
		password.classList.add('invalid');
	} else {
		repeat_password.classList.add('valid');
		password.classList.add('valid');
	}
}


//schaut ob alle inputs valid sind und enabled den absendebutton
function enableRegistBTN() {
	var validInputs = 0;

	for (var j = 0; j < inputs.length; j++) {
		var classNames = inputs[j].className.split(' ');
		for (var k = 0; k < classNames.length; k++) {
			if (classNames[k] === "valid") {
				validInputs += 1;
			}
		}
		if (validInputs === inputs.length && isTeamValid) {
			registBtn.classList.remove('disabled');
		} else {
			registBtn.classList.add('disabled');
		}
	}
}

/**
 * Geburtsdatum validieren
 */
function validateBirthdate() {
	birthdate.classList.remove("invalid");
	birthdate.classList.remove("valid");
	if (birthdate.value.length === 10) {
		birthdate.classList.add('valid');
	} else {
		birthdate.classList.add('invalid');
	}
}

/**
 * Team validieren
 *
 * @returns {boolean}
 */
function validateTeam() {
	teamDropdown.style.borderBottom = "";
	teamDropdown.style.boxShadow = "";
	if (document.getElementsByClassName("selected") != document.getElementsByClassName("disabled")) {
		teamDropdown.style.borderBottom = "1px solid #4CAF50";
		teamDropdown.style.boxShadow = "0 1px 0 0 #4CAF50";
		isTeamValid = true;
		enableRegistBTN();
		return true;
	} else {
		teamDropdown.style.borderBottom = "1px solid #F44336";
		teamDropdown.style.boxShadow = "0 1px 0 0 #F44336";
		return false;
	}
}


if (user_name != null) {
	user_name.onchange = enableRegistBTN;
}
if (email != null) {
	email.onchange = enableRegistBTN;
}
if (first_name != null) {
	first_name.onchange = enableRegistBTN;
}
if (last_name != null) {
	last_name.onchange = enableRegistBTN;
}
if (birthdate != null) {
	birthdate.onchange = enableRegistBTN;
}
if (password != null) {
	password.onchange = enableRegistBTN;
}
if (repeat_password != null) {
	repeat_password.onchange = enableRegistBTN;
}
if (birthdate != null) {
	birthdate.onchange = validateBirthdate;
}
if (team != null) {
	team.onchange = validateTeam;
}
if (password != null) {
	password.onchange = validatePassword;
}
if (repeat_password != null) {
	repeat_password.onkeyup = validatePassword;
}
