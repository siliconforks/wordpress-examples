<?php

/*
Plugin Name: Example Settings API
Plugin URI: https://github.com/siliconforks/wordpress-examples
Description: Shows how to use the settings API.
License: GPLv2 or later
*/

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/*
This is mostly based on the code here:
  https://developer.wordpress.org/plugins/settings/custom-settings-page/
  https://codex.wordpress.org/Creating_Options_Pages
*/
class Example_Settings_API {
	/**
	 * This is used in register_setting() and settings_fields().
	 */
	const OPTION_GROUP = 'example-option-group';

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

	const PAGE_SLUG = 'example-settings-api-slug';

	const CAPABILITY = 'manage_options';

	const SETTINGS_MESSAGES_ID = 'example-settings-api-messages';

	const SETTINGS_MESSAGE_ID = 'example-settings-api-message';

	const SETTINGS_SECTION_ID = 'example-settings-section';

	const SETTINGS_FIELD_ID = 'example-settings-field';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function settings_section( $args ) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>">
			Settings section description
		</p>
		<?php
	}

	public function settings_field( $args ) {
		$value = '';
		$option = get_option( self::OPTION_NAME );
		if ( isset( $option[self::OPTION_KEY] ) ) {
			$value = $option[self::OPTION_KEY];
		}
		?>
		<input type="text" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[<?php echo esc_attr( self::OPTION_KEY ); ?>]" value="<?php echo esc_attr( $value ); ?>">
		<?php
	}

	public function admin_menu() {
		add_menu_page( 'Settings API Page Title', 'Settings API Page Menu Title', self::CAPABILITY, self::PAGE_SLUG, array( $this, 'settings_page' ) );
	}

	public function settings_page() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error( self::SETTINGS_MESSAGES_ID, self::SETTINGS_MESSAGE_ID, 'Settings saved.', 'updated' );
		}
		settings_errors( self::SETTINGS_MESSAGES_ID );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( self::OPTION_GROUP );
				do_settings_sections( self::PAGE_SLUG );
				submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php
	}

	public function admin_init() {
		register_setting( self::OPTION_GROUP, self::OPTION_NAME );
		add_settings_section( self::SETTINGS_SECTION_ID, 'Settings Section Title', array( $this, 'settings_section' ), self::PAGE_SLUG );
		add_settings_field( self::SETTINGS_FIELD_ID, 'Settings Field Title', array( $this, 'settings_field' ), self::PAGE_SLUG, self::SETTINGS_SECTION_ID );
	}
}

new Example_Settings_API();
