<?php

/*
Plugin Name: Example Add Admin Page
Plugin URI: https://github.com/siliconforks/wordpress-examples
Description: Shows how to add a page to the WordPress admin section.
License: GPLv2 or later
*/

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

class Example_Add_Admin_Page {
	/**
	 * This will be used in the wp_options.option_name column in the
	 * database.
	 */
	const OPTION_NAME = 'example_option';

	/**
	 * The option in the database will be stored as an array (serialized).
	 * The array will have one key.
	 */
	const OPTION_KEY = 'example_key';

	const PAGE_SLUG = 'example-add-admin-page-slug';

	const CAPABILITY = 'manage_options';

	const SETTINGS_MESSAGES_ID = 'example-add-admin-page-messages';

	const SETTINGS_MESSAGE_ID = 'example-add-admin-page-message';

	const NONCE_PARAMETER = 'example-add-admin-page-nonce';

	const NONCE_ACTION = 'example-add-admin-page';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function admin_menu() {
		add_menu_page( 'Admin Page Title', 'Admin Page Menu Title', self::CAPABILITY, self::PAGE_SLUG, array( $this, 'page' ) );
	}

	public function page() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error( self::SETTINGS_MESSAGES_ID, self::SETTINGS_MESSAGE_ID, 'Settings saved.', 'updated' );
		}
		settings_errors( self::SETTINGS_MESSAGES_ID );

		$value = '';
		$option = get_option( self::OPTION_NAME );
		if ( isset( $option[self::OPTION_KEY] ) ) {
			$value = $option[self::OPTION_KEY];
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form method="post">
				<?php
				wp_nonce_field( self::NONCE_ACTION, self::NONCE_PARAMETER );
				?>
				<h2>Admin Page Section Title</h2>
				<p>Admin page section description</p>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">Admin Page Field Title</th>
							<td>
								<input type="text" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[<?php echo esc_attr( self::OPTION_KEY ); ?>]" value="<?php echo esc_attr( $value ); ?>">
							</td>
						</tr>
					</tbody>
				</table>
				<?php
				submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php
	}

	public function admin_init() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		if ( isset( $_POST[self::NONCE_PARAMETER] ) ) {
			if ( ! wp_verify_nonce( $_POST[self::NONCE_PARAMETER], self::NONCE_ACTION ) ) {
				return;
			}

			if ( isset( $_POST[self::OPTION_NAME] ) && is_array( $_POST[self::OPTION_NAME] ) ) {
				if ( isset( $_POST[self::OPTION_NAME][self::OPTION_KEY] ) ) {
					$value = $_POST[self::OPTION_NAME][self::OPTION_KEY];
					update_option( self::OPTION_NAME, array( self::OPTION_KEY => $value ), TRUE );
					$url = admin_url( 'admin.php?page=' . self::PAGE_SLUG . '&settings-updated=true' );
					wp_redirect( $url );
					exit;
				}
			}
		}
	}
}

new Example_Add_Admin_Page();
