<?php
/**
 * Plugin Name: AGESCI Mappe gruppi
 * Plugin URI: https://github.com/eutampieri/agesci-group-maps
 * Description: Memorizza in WordPress i dati dei gruppi
 * Version: 0.1
 * Requires at least: 4.9
 * Requires PHP: 7.0
 * Author: Eugenio Tampieri
 * Author URI: https://github.com/eutampieri
 *
 */

require_once("upload.php");
require_once("api.php");
require_once("components/db.php");

register_activation_hook( __FILE__, 'agd_install' );

add_action( 'admin_menu', 'agd_menu_callback' );
add_action( 'wp_ajax_agd_group_data', 'agd_group_data' );

// Register plugin settings
add_action('admin_init', 'register_agd_mapbox_key');

function register_agd_mapbox_key() {
    register_setting('agd_mapbox_key_group', 'agd_mapbox_key');
}
