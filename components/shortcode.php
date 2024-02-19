<?php
function agd_shorttag_map( $atts ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'group_data';

    $points = [];
    $result = $wpdb->get_results("SELECT * FROM ".$table_name);
	$zones = $wpdb->get_results("SELECT DISTINCT zone FROM ".$table_name." ORDER BY longitude*latitude;");
	$palette = [];
	$delta = 360.0 / count($zones);
	foreach($zones as $i => $z) {
		$hue = $i * $delta;
		$rk = fmod(5 + $hue/60, 6);
		$gk = fmod(3 + $hue/60, 6);
		$bk = fmod(1 + $hue/60, 6);
		$r = (1 - max(0, min($rk, 4 - $rk, 1)))*255;
		$g = (1 - max(0, min($gk, 4 - $gk, 1)))*255;
		$b = (1 - max(0, min($bk, 4 - $bk, 1)))*255;
		$palette[$z->zone] = "#".str_pad(dechex($r), 2, "0", STR_PAD_LEFT).str_pad(dechex($g), 2, "0", STR_PAD_LEFT).str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
	}
    foreach($result as $group) {
        array_push($points, [
            "coords" => [$group->longitude, $group->latitude],
            "popup" => "<h2>".$group->name."</h2>".
            "<p><strong>Indirizzo:</strong> ".$group->address . " " . $group->zip . " " . $group->city . "(" . $group->province . ")</p>".
            "<p><strong>Indirizzo mail:</strong> <a href=\"mailto:" . $group->email . "\">".$group->email."</a></p>".
            "<p><strong>Codice fiscale:</strong> ".$group->vat_no."</a></p>".
            "<p><strong>Zona:</strong> ".$group->zone."</a></p>".
            "<p><strong>Parrocchia:</strong> ".$group->parish."</a></p>".
            "<p><strong>Diocesi:</strong> ".$group->diocese."</a></p>",
			"colour" => $palette[$group->zone]
        ]);
    }

	return agesci_maps_get_map_markers(["positions" => $points, "bbox" => [
            [max(array_map(fn($value): float => $value["coords"][0], $points)) + 0.25, min(array_map(fn($value): float => $value["coords"][1], $points)) - 0.25],
            [min(array_map(fn($value): float => $value["coords"][0], $points)) - 0.25, max(array_map(fn($value): float => $value["coords"][1], $points)) + 0.25]
        ]]);
}