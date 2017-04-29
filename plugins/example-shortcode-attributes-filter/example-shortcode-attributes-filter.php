<?php

/*
Plugin Name: Example Shortcode Attributes Filter
Plugin URI: https://github.com/siliconforks/wordpress-examples
Description: Shows how to use the filter created by the shortcode_atts() function.
License: GPLv2 or later
*/

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

class Example_Shortcode_Attributes_Filter {
	public function __construct() {
		add_filter( 'shortcode_atts_example_shortcode', array( $this, 'filter' ) );
	}

	public function filter( $atts ) {
		$atts['x'] = 'FILTERED';
		return $atts;
	}
}

new Example_Shortcode_Attributes_Filter();
