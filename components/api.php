<?php
require_once(dirname(__FILE__)."/spreadsheet2json");
function agd_group_data() {
    $uploaded_file = tempnam(sys_get_temp_dir(), 'groupdata');
    file_put_contents($uploaded_file, base64_decode($_POST["agd_file"]));
    switch ($_POST["agd_additional_action"]) {
        case 'get_headings':
            header("Content-Type: application/json");
            $res = [];
            foreach(agd_load_data($uploaded_file) as $key => $value) {
                array_push($res, $key);
            }
            echo json_encode($res);
            break;
        
        default:
            # code...
            break;
    }
    unlink($uploaded_file);
    wp_die();
}
