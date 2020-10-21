<?php

add_action( 'after_setup_theme', static function () {
	add_theme_support( 'woocommerce' );
} );

// https://woocommerce.com/document/woocommerce-theme-developer-handbook/
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', static function () {
	?>
	<main id="content">
	<?php
} );
add_action( 'woocommerce_after_main_content', static function () {
	?>
	</main>
	<?php
} );

// https://github.com/woocommerce/woocommerce/issues/23594
remove_action( 'enqueue_block_assets', 'wp_enqueue_registered_block_scripts_and_styles' );
