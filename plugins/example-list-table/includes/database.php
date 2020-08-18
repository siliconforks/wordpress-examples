<?php

namespace Example_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function update_database() {
	global $wpdb;

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$charset_collate = $wpdb->get_charset_collate();

	$table_name = $wpdb->prefix . 'example_list_table';
	$sql = "CREATE TABLE $table_name (
		example_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		example_name varchar(100) NOT NULL,
		example_email varchar(100) NOT NULL,
		example_date bigint(20) NOT NULL,
		KEY example_name (example_name, example_id),
		KEY example_email (example_email, example_id),
		KEY example_date (example_date, example_id),
		PRIMARY KEY  (example_id)
	) $charset_collate;";
	dbDelta( $sql );

	update_option( DATABASE_VERSION_OPTION_NAME, DATABASE_VERSION );
}
