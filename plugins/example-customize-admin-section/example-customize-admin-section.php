<?php

/*
Plugin Name: Example Customize Admin Section
Plugin URI: https://github.com/siliconforks/wordpress-examples
Description: Shows how to customize the WordPress admin section.
License: GPLv2 or later
*/

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/*
Most of these examples are from:
  https://www.smashingmagazine.com/2012/05/customize-wordpress-admin-easily/
  https://www.sitepoint.com/make-wordpress-simpler-users/
*/
class Example_Customize_Admin_Section {
	public function __construct() {
		add_action( 'add_admin_bar_menus', array( $this, 'add_admin_bar_menus' ) );

		/*
		WordPress adds the theme editor menu item in an 'admin_menu'
		action hook with priority 101.  To remove it, we need an
		'admin_menu' hook with priority 102 or more.
		*/
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 102 );

		remove_action( 'welcome_panel', 'wp_welcome_panel' );
		add_action( 'wp_dashboard_setup', array( $this, 'wp_dashboard_setup' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ) );
		add_filter( 'show_admin_bar', '__return_false' );
	}

	public function add_admin_bar_menus() {
		// remove WordPress menu
		remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );

		// remove comments
		remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );

		// remove "New" menu
		remove_action( 'admin_bar_menu', 'wp_admin_bar_new_content_menu', 70 );
	}

	public function admin_menu() {
		// can remove "Posts" menu item if your site does not have a blog
		remove_menu_page( 'edit.php' );

		// can remove "Comments" menu item if your site does not allow comments
		remove_menu_page( 'edit-comments.php' );

		/*
		Remove the "Editor" menu item.  Note that this can also be done
		by defining DISALLOW_FILE_EDIT to TRUE:
		  https://codex.wordpress.org/Editing_wp-config.php
		*/
		remove_submenu_page( 'themes.php', 'theme-editor.php' );
		remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
	}

	public function wp_dashboard_setup() {
		global $wp_meta_boxes;

		// remove "At a Glance"
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);

		// remove "Activity"
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);

		// remove "Quick Draft"
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);

		// remove "WordPress News"
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	}

	public function admin_footer_text($text) {
		return '<span id="footer-thankyou">Powered by <a href="http://example.com/">example.com</a>.</span>';
	}
}

new Example_Customize_Admin_Section();
