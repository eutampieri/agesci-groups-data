<?php
global $agd_db_version;
$agd_db_version = '1.0';

function agd_install() {
        global $wpdb;
        global $agd_db_version;

        $table_name = $wpdb->prefix . 'group_data';
        
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                id CHAR(10) PRIMARY KEY,
                name TEXT NOT NULL,
                address TEXT NOT NULL,
                latitude REAL,
                longitude REAL,
                city TEXT NOT NULL,
                province TEXT NOT NULL,
                email TEXT NOT NULL,
                vat_no INTEGER NOT NULL,
                legal_representative TEXT NOT NULL,
                parish TEXT,
                diocese TEXT
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        add_option( 'agd_db_version', $agd_db_version );
}
