<?php

/*
Plugin Name: Example Ajax
Plugin URI: https://github.com/siliconforks/wordpress-examples
Description: Shows how to perform Ajax requests using the admin-ajax.php script.
License: GPLv2 or later
*/

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

define( 'EXAMPLE_AJAX_URL', plugin_dir_url( __FILE__ ) );

class Example_Ajax {
	public function __construct() {
		add_action( 'wp_ajax_example_ajax', array( $this, 'ajax' ) );
		add_action( 'wp_ajax_nopriv_example_ajax', array( $this, 'ajax' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public static function enqueue_scripts() {
		wp_enqueue_script( 'example-ajax', EXAMPLE_AJAX_URL . 'js/ajax.js', array( 'jquery' ), NULL, FALSE );
		wp_localize_script( 'example-ajax', 'EXAMPLE_AJAX', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	public function ajax() {
		if ( isset( $_POST['x'] ) && is_string( $_POST['x'] ) ) {
			echo $_POST['x'] + 1;
		}
		else {
			echo 'error';
		}
		wp_die();
	}
}

new Example_Ajax();
