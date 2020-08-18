<?php

namespace Example_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const DATABASE_VERSION = 1;
const DATABASE_VERSION_OPTION_NAME = 'example_list_table_database_version';

add_action( 'plugins_loaded', static function () {
	if ( get_option( DATABASE_VERSION_OPTION_NAME ) != DATABASE_VERSION ) {
		require __DIR__ . '/database.php';
		update_database();
	}
} );
