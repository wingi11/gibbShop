$(".button-sidenav").sideNav({
		menuWidth: 300,
		edge: 'right',
		closeOnClick: true,
		draggable: true
	}
);

$('.carousel.carousel-slider').carousel({fullWidth: true});

$(document).ready(function () {
	$('.parallax').parallax();
});

var time = 200;

$(".loggingbtn").click(function () {
	if ($("#logging").css('display') == 'none' && $("#register").css('display') == 'none') {
		$("#logging").slideToggle(time);
	} else {
		if ($("#logging").css('display') == 'none') {
			$("#register").slideToggle(time);
		} else {
			$("#logging").slideToggle(time);
		}
	}
});

$(".changeToRegist").click(function () {
	$("#logging").slideToggle(time);
	setTimeout(function () {
		$("#register").slideToggle(time);
	}, time + 100);

});
$(".changeTologin").click(function () {
	$("#register").slideToggle(time);
	setTimeout(function () {
		$("#logging").slideToggle(time);
	}, time + 100);

});

$(document).ready(function () {
	$('select').material_select();
});

$('.datepicker').pickadate({
	selectMonths: true, // Creates a dropdown to control month
	selectYears: 55, // Creates a dropdown of 15 years to control year,
	today: 'Today',
	format: 'yyyy-mm-dd',
	clear: 'Clear',
	close: 'Ok',
	max: new Date(),
	closeOnSelect: true // Close upon selecting a date,
});
$('.ExpiryDate').pickadate({
	selectMonths: true, // Creates a dropdown to control month
	selectYears: 1, // Creates a dropdown of 15 years to control year,
	today: 'Today',
	format: 'yyyy-mm-dd',
	clear: 'Clear',
	close: 'Ok',
	min: incrementDate(1),
	max: incrementDate(5),
	closeOnSelect: true // Close upon selecting a date,
});

/**
 * Zählt die Tage dazu
 *
 * @param days
 * @returns {Date}
 */
function incrementDate(days) {
	var date = new Date();
	date.setDate(date.getDate() + days);
	return date;
}

/**
 * Wenn kein Bild da ist
 */
function imgNotFound() {
	document.getElementById("preview-img").style.opacity = 0;
}

/**
 * Vorschaubild laden
 * @param input
 */
function readURL(input) {
	if (input.files && input.files[0]) {
		document.getElementById("preview-img").style.opacity = 1;
		var reader = new FileReader();

		reader.onload = function (e) {
			$('#preview-img')
				.attr('src', e.target.result)
				.width(200)
				.height(200);
		};

		reader.readAsDataURL(input.files[0]);
	}
}

$(document).ready(function () {
	$('.materialboxed').materialbox();
});

/**
 * Das Produktlöschenfenster öffnen
 *
 * @param btn
 */
function deleteProd(btn) {
	$('#delModal').modal();
	$('#delModal').modal('open');
	document.getElementById("delProdId").value = btn.value;
}

var dmId = document.getElementsByName("delMsgId");
var mId = document.getElementsByName("mId");

if (dmId != null) {
	for (var i = 0; i < dmId.length; i++) {
		if (mId[i] != null) {
			mId[i].value = dmId[i].value;
		}
	}
}

/**
 * Daten von buttons in hidden Felder speichern
 *
 * @param btn
 */
function fillData(btn) {
	document.getElementById("userid").value = btn.value;
	document.getElementById("userid2").value = btn.value;
}

$(document).ready(function () {
	$('.modal').modal();
});

$(document).ready(function () {
	$('.tooltipped').tooltip();
});

