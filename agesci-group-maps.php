<?php
/**
 * Plugin Name: AGESCI Mappe gruppi
 * Plugin URI: https://github.com/eutampieri/agesci-group-maps
 * Description: Memorizza in WordPress i dati dei gruppi
 * Version: 1.0.0
 * Requires at least: 4.9
 * Requires PHP: 7.0
 * Author: Eugenio Tampieri
 * Author URI: https://github.com/eutampieri
 *
 */

require_once("components/upload.php");
require_once("components/api.php");
require_once("components/db.php");
require_once("components/shortcode.php");
require_once("components/rest_api.php");

register_activation_hook( __FILE__, 'agd_install' );

add_action( 'admin_menu', 'agd_menu_callback' );
add_action( 'wp_ajax_agd_group_data', 'agd_group_data' );

add_shortcode( 'groups_map', 'agd_shorttag_map' );

// Register plugin settings
add_action('admin_init', 'register_agd_mapbox_key');

function register_agd_mapbox_key() {
    register_setting('agd_mapbox_key_group', 'agd_mapbox_key');
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'group_data/v1', '/zones', array(
      'methods' => 'GET',
      'callback' => 'agd_get_zones',
    ) );
    register_rest_route( 'group_data/v1', '/zones/(?P<zone>[a-z]+)', array(
      'methods' => 'GET',
      'callback' => 'agd_get_groups_in_zone',
    ) );
    register_rest_route( 'group_data/v1', '/nearest_k_groups', array(
      'methods' => 'GET',
      'callback' => 'agd_get_k_nearest_groups',
    ) );
} );

