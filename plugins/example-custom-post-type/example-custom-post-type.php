<?php

/*
Plugin Name: Example Custom Post Type
Plugin URI: https://github.com/siliconforks/wordpress-examples
Description: Shows how to add a custom post type.
License: GPLv2 or later
*/

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * https://codex.wordpress.org/Post_Types
 * https://codex.wordpress.org/Function_Reference/register_post_type
 * https://www.smashingmagazine.com/2012/11/complete-guide-custom-post-types/
 */
class Example_Custom_Post_Type {
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	public function register_post_type() {
		$args = array(
			'labels' => array(
				'name' => 'Example Custom Posts',
				'singular_name' => 'Example Custom Post',
				'add_new_item' => 'Add New Example Custom Post',
				'edit_item' => 'Edit Example Custom Post',
				'new_item' => 'New Example Custom Post',
				'view_item' => 'View Example Custom Post',
				'view_items' => 'View Example Custom Posts',
				'search_items' => 'Search Example Custom Posts',
				'not_found' => 'No example custom posts found',
				'not_found_in_trash' => 'No example custom posts found in Trash',
				'all_items' => 'All Example Custom Posts',
				'archives' => 'Example Custom Post Archives',
				'attributes' => 'Example Custom Post Attributes',
				'insert_into_item' => 'Insert into example custom post',
				'uploaded_to_this_item' => 'Uploaded to this example custom post',
			),
			'exclude_from_search' => TRUE,
			'publicly_queryable' => FALSE,
			'show_in_nav_menus' => FALSE,
			'show_ui' => TRUE,
			'menu_icon' => 'dashicons-wordpress',
			'hierarchical' => FALSE,
			'supports' => array(
				'title',
				'editor',
			),
			'has_archive' => FALSE,
			'delete_with_user' => FALSE,
		);
		register_post_type( 'example_custom_post', $args );
	}
}

new Example_Custom_Post_Type();
