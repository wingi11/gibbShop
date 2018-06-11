<?php
$anzahlItems = count($data["Daten"]);
?>
<svg width="<?php echo $anzahlItems * 100 + 75; ?>" height="<?php echo $height + 40; ?>">
	<linearGradient id="verlauf" x1="0%" y1="0%" x2="0%" y2="100%">
		<stop offset="0%" stop-color="#A611E8"/>
		<stop offset="100%" stop-color="#A6539B"/>
	</linearGradient>

	<rect x="0" y="0" width="<?php echo($anzahlItems * 60 + 105); ?>" height="1" fill="#a3a3a3" class="z-depth-2"/>
	<text x="5" y="20" fill="#a3a3a3"><?php echo $data["max"]; ?></text>

	<rect x="0" y="<?php echo $height / 2; ?>" width="<?php echo($anzahlItems * 60 + 105); ?>" height="1" fill="#d3d3d3"
		  class="z-depth-2"/>

	<rect x="0" y="<?php echo $height - 1; ?>" width="<?php echo($anzahlItems * 60 + 105); ?>" height="2" fill="gray"
		  class="z-depth-2"/>
	<text x="5" y="<?php echo $height + 18; ?>"><?php echo $data["rowDesc"]; ?></text>

	<?php
	$i = 0;

	//Baut die einzelnen Balken auf
	foreach ($data["Daten"] as $date) {
		$anzahl = $date["anzahl"];
		$percent = $anzahl / ($data["max"] / 100);

		$desc = $date["desc"];
		$h = (($height / 100) * $percent);
		$y = ($height - $h);

		$anzahlOffset = strlen($anzahl);
		$descOffset = strlen($desc);
		//echo "<rect x='".($i*60+80)."' y='0' width='60' height='$height' fill='#ddedff' class='diagram-bg' class='z-depth-2'/>";
		echo "<rect x='" . ($i * 60 + 85) . "' y='$y' width='50' height='$h' class='diagram' fill='url(#verlauf)' class='z-depth-2'/>";

		if ($h < 30) {
			echo "<text x='" . ($i * 60 + 95 + (9 / $anzahlOffset)) . "' y='" . ($y - 4) . "' style='fill:black'>$anzahl</text>";
		} else {
			echo "<text x='" . ($i * 60 + 95 + (9 / $anzahlOffset)) . "' y='" . ($y + 22) . "' style='fill:white'>$anzahl</text>";
		}
		echo "<text x='" . ($i * 60 + 95 + (9 / $descOffset)) . "' y='" . ($height + 18) . "'>$desc</text>";
		echo "<rect x='" . ($i * 60 + 80) . "' y='0' width='60' height='$height' fill='#ddedff' class='diagram-selector'/>";
		$i++;
	}
	?>
</svg>


