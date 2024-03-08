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
    $result = $wpdb->get_results($wpdb_prepare("SELECT * FROM $table_name WHERE REPLACE(REPLACE(LOWER(zone), \" \", \"\"), \"zona\", \"\")=%s;", $data["zone"]));
    return $result;
}


function agd_get_k_nearest_groups() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'group_data';
    $k = min(10, intval($_GET["k"]));
    $lat = floatval($_GET["lat"]);
    $lon = floatval($_GET["lon"]);
    $result = $wpdb->get_results("SELECT *, (latitude - $lat)*(latitude - $lat) + (longitude - $lon) * (longitude - $lon) as distance FROM $table_name WHERE showtime>=$ts ORDER BY distance LIMIT $k;");
    return $result;
}

