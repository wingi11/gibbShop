/**
 * Die Länge eines Eingabefeldes checken
 *
 * @param object
 */
function maxLengthCheck(object) {
	if (object.value.length > object.maxLength)
		object.value = object.value.slice(0, object.maxLength)
}


$('.chips').material_chip();

$('.chips').on('chip.add', function (e, chip) {
	var maxLen = 15;
	if (chip.tag.length > maxLen) {
		Materialize.toast("<i class='material-icons'>highlight_off</i>Tags dürfen nur " + maxLen + " Zeichen lang sein", 3000, 'red darken-3 white-text');
		$('.chip').slice(-1).children().click();
	}
});

/**
 * den Text der Tags zurückgeben
 *
 * @returns {string}
 */
function getData() {
	var data = $('#tags').material_chip('data');
	if (data != null) {
		var dataString = "";
		for (i = 0; i < data.length; i++) {
			dataString += data[i].tag + ",";
		}
		return dataString;
	}
}

/**
 * Das inserieren validieren
 *
 * @returns {boolean}
 */
function validateInserat() {
	var title = document.forms["inserat"]["product_name"].value;
	var description = document.forms["inserat"]["product_description"].value;
	var priceF = document.forms["inserat"]["price_franken"].value;
	var priceR = document.forms["inserat"]["price_rappen"].value;
	var date = document.forms["inserat"]["expiryDate"].value;
	var img = document.forms["inserat"]["product_image"].value;

	var isValid = true;

	if (title == "" && title != null) {
		Materialize.toast("<i class='material-icons'>highlight_off</i>Noch kein Produktname hinzugefügt", 9000, 'red darken-3 white-text');
		document.getElementById("product_name").className += "validate invalid";
		isValid = false;
	}
	if (getData() != null) {
		if (getData().length == 0) {
			Materialize.toast("<i class='material-icons'>highlight_off</i>Noch keine Tags hinzugefügt", 9000, 'red darken-3 white-text');
			isValid = false;
		}
	}

	if (description == "" && description != null) {
		Materialize.toast("<i class='material-icons'>highlight_off</i>Noch keine Produktbescheibung hinzugefügt", 9000, 'red darken-3 white-text');
		document.getElementById("product_description").className += " validate invalid ";
		isValid = false;
	}
	if (date == "" && date != null && isAuction == true) {
		Materialize.toast("<i class='material-icons'>highlight_off</i>Noch kein Ablaufdatum hinzugefügt", 9000, 'red darken-3 white-text');
		document.getElementById("expiryDate").className += " validate invalid ";
		isValid = false;
	}

	if (priceF == "" && priceF != null) {
		Materialize.toast("<i class='material-icons'>warning</i>Kein Preis hinzugefügt", 9000, 'yellow darken-3 white-text');
		document.getElementById("price_franken").value = "0000";
		isValid = false;
	}
	if (priceF < 0 || priceR < 0) {
		Materialize.toast("<i class='material-icons'>warning</i>Kein gültiger Preis hinzugefügt", 9000, 'yellow darken-3 white-text');
		document.getElementById("price_franken").value = "0000";
		isValid = false;
	}

	if (img == "" && img != null) {
		Materialize.toast("<i class='material-icons'>warning</i>Kein Bild hinzugefügt", 9000, 'red darken-3 white-text');
		document.getElementById("price_franken").value = "0000";
		isValid = false;
	}

	if (priceR == "" && priceR != null) {
		document.getElementById("price_rappen").value = "00";
	}

	if (document.getElementById("product_price") != null) {
		document.getElementById("product_price").value = priceF + "." + priceR;
	}
	if (document.getElementById("product_tags") != null) {
		document.getElementById("product_tags").value = getData();
	}

	return isValid;
}

var expiryDate = document.getElementById("expiryDate");

/**
 * Validiert ablaufdatum
 */
function validateExpiryDate() {

	expiryDate.classList.remove("invalid");
	expiryDate.classList.remove("valid");
	if (expiryDate.value.length === 10) {
		expiryDate.classList.add('valid');
	} else {
		expiryDate.classList.add('invalid');
	}

}

//Wenn sich das Ablaufdatum ändert
if (expiryDate != null) {
	expiryDate.onchange = validateExpiryDate;
}


var radiobtn = document.getElementById('price_auction');
var priceFix = document.getElementById('price_fix');


var isAuction = true;

/**
 * toggled den Datepicker auf show oder hide
 */
function changeExpiryDate() {
	var picker = document.getElementById('expiryDate-picker');
	if (isAuction == false) {
		isAuction = true;
	}
	else isAuction = false;

	if (isAuction && picker.style.display == "none") {
		picker.style.display = 'block';
	} else {
		picker.style.display = 'none';
	}
}

//Wenn sich die Inseratmethode ändert
if (radiobtn != null && priceFix != null) {
	radiobtn.onchange = changeExpiryDate;
	priceFix.onchange = changeExpiryDate;
}