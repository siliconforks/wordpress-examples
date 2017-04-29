<?php

/*
Plugin Name: Example Shortcode
Plugin URI: https://github.com/siliconforks/wordpress-examples
Description: Shows how to add a shortcode.
License: GPLv2 or later
*/

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

class Example_Shortcode {
	public function __construct() {
		add_shortcode( 'example_shortcode', array( $this, 'shortcode' ) );
	}

	public function shortcode( $atts, $content, $name ) {
		$defaults = array(
			'x' => '123',
		);
		$atts = shortcode_atts( $defaults, $atts, $name );
		ob_start();
		?>
		<div>
			<p>
			This is the example shortcode. (name = <?php echo esc_html( $name ); ?>)
			<p>
				Attributes (including defaults):
			</p>
			<table style="border: 1px solid red;">
				<tbody>
					<?php
					foreach ( $atts as $key => $value ) {
						?>
						<tr>
							<th scope="row"><?php echo esc_html( $key ); ?></th>
							<td><?php echo esc_html( $value ); ?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
			<p>
				Content:
			</p>
			<div style="border: 1px solid red;">
				<?php echo $content; ?>
			</div>
		</div>
		<?php
		$result = ob_get_clean();
		return $result;
	}
}

new Example_Shortcode();
