<?php
require_once(dirname(__FILE__)."/spreadsheet2json.php");
require_once(dirname(__FILE__)."/geocode.php");
function agd_group_data() {
    $uploaded_file = tempnam(sys_get_temp_dir(), 'groupdata');
    file_put_contents($uploaded_file, base64_decode($_POST["agd_file"]));
    header("Content-Type: application/json");
    switch ($_POST["agd_additional_action"]) {
        case 'get_headings':
            $res = [];
            foreach(agd_load_data($uploaded_file)[0] as $key => $value) {
                array_push($res, [$key, $value]);
            }
            echo json_encode($res);
            break;
        
        case 'send_data':
            global $wpdb;

            $table_name = $wpdb->prefix . 'group_data';

            $wpdb->query("TRUNCATE ".$table_name);
            foreach(agd_load_data($uploaded_file) as $group) {
                $address = $group[$_POST["street"]].", ".$group[$_POST["street_number"]];
                $coords = agd_geocode($group[$_POST["street_number"]], $group[$_POST["street"]], $group[$_POST["city"]], $group[$_POST["zip"]], $group[$_POST["province"]], get_option('agd_mapbox_key'));
                $lat = $coords[1];
                $lon = $coords[0];

                $wpdb->query($wpdb->prepare("INSERT INTO $table_name (`id`, `name`, `address`, `latitude`, `longitude`, `city`, `zip`, `province`, `email`, `vat_no`, `legal_representative`, `zone`, `parish`, `diocese`) VALUES (%s, %s, %s, %f, %f, %s, %s, %s, %s, %s, %s, %s, %s, %s)", [
                    $group[$_POST["id"]],
                    $group[$_POST["name"]],
                    $address,
                    $lat,
                    $lon,
                    $group[$_POST["city"]],
                    $group[$_POST["zip"]],
                    $group[$_POST["province"]],
                    $group[$_POST["email"]],
                    $group[$_POST["vat_no"]],
                    $group[$_POST["legal_representative"]],
                    $group[$_POST["zone"]],
                    $group[$_POST["parish"]],
                    $group[$_POST["diocese"]],
                ]));
            }
            echo json_encode("Dati salvati");
            break;
        default:
            # code...
            break;
    }
    unlink($uploaded_file);
    wp_die();
}
