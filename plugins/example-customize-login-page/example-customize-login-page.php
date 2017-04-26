<?php

/*
Plugin Name: Example Customize Login Page
Plugin URI: https://github.com/siliconforks/wordpress-examples
Description: Shows how to customize the wp-login.php page.
License: GPLv2 or later
*/

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

define( 'EXAMPLE_CUSTOMIZE_LOGIN_PAGE_URL', plugin_dir_url( __FILE__ ) );

// https://codex.wordpress.org/Customizing_the_Login_Form
class Example_Customize_Login_Page {
	public function __construct() {
		add_action( 'login_enqueue_scripts', array( $this, 'login_enqueue_scripts' ) );
		add_filter( 'login_headerurl', array( $this, 'login_headerurl' ) );
		add_filter( 'login_headertitle', array( $this, 'login_headertitle' ) );
	}

	public function login_enqueue_scripts() {
		wp_enqueue_style( 'example-customize-login-page', EXAMPLE_CUSTOMIZE_LOGIN_PAGE_URL . '/css/style.css' );
	}

	public function login_headerurl() {
		return home_url();
	}

	public function login_headertitle() {
		return get_bloginfo( 'name' );
	}
}

new Example_Customize_Login_Page();
