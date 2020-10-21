<?php

add_action( 'after_setup_theme', static function () {
	$GLOBALS['content_width'] = 640;
}, 0 );

add_action( 'after_setup_theme', static function () {
	load_theme_textdomain( 'example-minimal', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Header', 'example-minimal' ),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
} );

add_action( 'widgets_init', static function () {
	register_sidebar(
		array(
			'name' => esc_html__( 'Sidebar', 'example-minimal' ),
			'id' => 'sidebar-1',
			'description' => esc_html__( 'Add widgets here.', 'example-minimal' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
		)
	);
} );

add_action( 'init', static function () {
	$classic_editor_styles = array(
		'css/normalize.css',
		'editor-style.css',
	);
	add_editor_style( $classic_editor_styles );
} );

add_action( 'wp_enqueue_scripts', static function () {
	wp_enqueue_style( 'normalize', get_template_directory_uri() . '/css/normalize.css', array(), '8.0.1' );
	wp_enqueue_style( 'example-minimal-style', get_stylesheet_uri(), array( 'normalize' ), '1' );

	wp_enqueue_script( 'example-minimal-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1', TRUE );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
} );

/*
https://wordpress.org/support/topic/block-editor-assets-still-enqueued/

Note that the wp-block-library CSS is enqueued in the wp_common_block_scripts_and_styles() function, which is hooked to the 'wp_enqueue_scripts' action with priority 10 (the default).
Therefore, we can dequeue it in a hook function with priority 11.
*/
add_action( 'wp_enqueue_scripts', static function () {
	wp_dequeue_style( 'wp-block-library' );
}, 11 );

require get_template_directory() . '/inc/template-tags.php';

if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}
