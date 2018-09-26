<?php

/*
Plugin Name: Example Ajax Without admin-ajax.php
Plugin URI: https://github.com/siliconforks/wordpress-examples
Description: Shows how to perform Ajax requests without using the admin-ajax.php script.
License: GPLv2 or later
*/

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

define( 'EXAMPLE_AJAX_WITHOUT_ADMIN_AJAX_URL', plugin_dir_url( __FILE__ ) );

class Example_Ajax_Without_Admin_Ajax {
	public function __construct() {
		if ( ! empty( $_GET['example-ajax-without-admin-ajax'] ) ) {
			/*
			This function should always be called early (in the
			'init' action with priority 0) so that DOING_AJAX is
			defined and errors are turned off.
			*/
			add_action( 'init', array( $this, 'define_ajax' ), 0 );

			/*
			This function can be called as early as the 'init'
			action (with priority 0) or as late as the
			'template_redirect' action.
			*/
			add_action( 'init', array( $this, 'do_ajax' ), 0 );
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public static function enqueue_scripts() {
		wp_enqueue_script( 'example-ajax-without-admin-ajax', EXAMPLE_AJAX_WITHOUT_ADMIN_AJAX_URL . 'js/ajax.js', array( 'jquery' ), NULL, FALSE );
		wp_localize_script( 'example-ajax-without-admin-ajax', 'EXAMPLE_AJAX_WITHOUT_ADMIN_AJAX', array( 'ajax_url' => home_url( '/' ) . '?example-ajax-without-admin-ajax=1' ) );
	}

	public function define_ajax() {
		if ( ! defined( 'DOING_AJAX' ) ) {
			/*
			This causes the function wp_doing_ajax() to return TRUE.
			*/
			define( 'DOING_AJAX', true );
		}

		// Turn off display_errors during AJAX events to prevent malformed JSON
		if ( ! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY ) ) {
			@ini_set( 'display_errors', 0 );
		}
		$GLOBALS['wpdb']->hide_errors();
	}

	public function do_ajax() {
		send_origin_headers();
		@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
		@header( 'X-Robots-Tag: noindex' );
		send_nosniff_header();
		nocache_headers();

		$this->handler();

		wp_die();
	}

	public function handler() {
		if ( isset( $_POST['x'] ) && is_string( $_POST['x'] ) ) {
			echo $_POST['x'] + 1;
		}
		else {
			echo 'error';
		}
	}
}

new Example_Ajax_Without_Admin_Ajax();
