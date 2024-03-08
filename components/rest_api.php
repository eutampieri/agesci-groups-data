<?php
function agd_get_zones() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'group_data';
    $result = $wpdb->get_results("SELECT DISTINCT REPLACE(REPLACE(LOWER(zone), \" \", \"\"), \"zona\", \"\") AS id, zone AS name, CONCAT(CONCAT(\"https://zone.agesci.it/\", REPLACE(LOWER(zone), \" \", \"\")), \"/\") AS website FROM $table_name");
    return $result;
}

function agd_get_groups_in_zone($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'group_data';
    $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE REPLACE(REPLACE(LOWER(zone), \" \", \"\"), \"zona\", \"\")=%s;", $data["zone"]));
    return $result;
}


function agd_get_k_nearest_groups() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'group_data';
    $k = min(10, intval($_GET["k"]));
    $lat = floatval($_GET["lat"]);
    $lon = floatval($_GET["lon"]);
	if(isset($_GET["calculate_duration"])) {
		$k = $k * 2;
	}
    $result = $wpdb->get_results("SELECT *, (latitude - $lat)*(latitude - $lat) + (longitude - $lon) * (longitude - $lon) as distance FROM $table_name ORDER BY distance LIMIT $k;");
	if(isset($_GET["calculate_duration"])) {
		$token = get_option('agd_mapbox_key');
		foreach($result as $r) {
			$url = "https://api.mapbox.com/directions/v5/mapbox/driving/{$r->longitude}%2C{$r->latitude}%3B{$lon}%2C{$lat}?alternatives=false&geometries=geojson&overview=simplified&steps=false&notifications=none&access_token={$token}";
			$r->duration = json_decode(file_get_contents($url), true)["routes"][0]["duration"] / 60;
		}
		usort($result, function($a, $b) { return $a->duration - $b->duration;});
		$result = array_slice($result, 0, $k / 2);
	}
    return $result;
}

