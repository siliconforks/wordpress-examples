<?php

/*
Plugin Name: Example List Table
Plugin URI: https://github.com/siliconforks/wordpress-examples
Description: Shows how to use the WP_List_Table class.
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require __DIR__ . '/includes/install.php';

if ( is_admin() ) {
	require __DIR__ . '/admin/includes/pages.php';
}
