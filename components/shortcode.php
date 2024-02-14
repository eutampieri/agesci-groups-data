<?php
function agd_shorttag_map( $atts ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'group_data';

    $points = [];
    $result = $wpdb->get_results("SELECT * FROM ".$table_name);
    foreach($result as $group) {
        array_push($points, [
            "coords" => [$group->longitude, $group->latitude],
            "popup" => "<h2>".$group->name."</h2>".
            "<p><strong>Indirizzo:</strong> ".$group->address . " " . $group->zip . " " . $group->city . "(" . $group->province . ")</p>".
            "<p><strong>Indirizzo mail:</strong> <a href=\"mailto:" . $group->email . "\">".$group->email."</a></p>".
            "<p><strong>Codice fiscale:</strong> ".$group->vat_no."</a></p>".
            "<p><strong>Zona:</strong> ".$group->zone."</a></p>".
            "<p><strong>Parrocchia:</strong> ".$group->parish."</a></p>".
            "<p><strong>Diocesi:</strong> ".$group->diocese."</a></p>"
        ]);
    }

	return agesci_maps_get_map_markers(["positions" => $points, "bbox" => [
            [max(array_map(fn($value): float => $value["coords"][0], $points)) + 0.25, min(array_map(fn($value): float => $value["coords"][1], $points)) - 0.25],
            [min(array_map(fn($value): float => $value["coords"][0], $points)) - 0.25, max(array_map(fn($value): float => $value["coords"][1], $points)) + 0.25]
        ]]);
}