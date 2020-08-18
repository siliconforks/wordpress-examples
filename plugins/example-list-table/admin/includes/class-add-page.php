<?php

namespace Example_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Add_Page {
	/**
	 * An array of error messages.
	 * Note that each error message may contain HTML.
	 */
	private static $errors = [];

	public static function load() {
		global $wpdb;

		if ( ! current_user_can( CAPABILITY ) ) {
			wp_die( 'Your user account is not authorized to view this page.', 403 );
		}

		// check for the presence of the nonce to determine whether it was a POST request
		if ( isset( $_POST['example-list-table-create-nonce'] ) && is_string( $_POST['example-list-table-create-nonce'] ) ) {
			if ( ! wp_verify_nonce( $_POST['example-list-table-create-nonce'], 'example-list-table-create' ) ) {
				self::$errors[] = 'Timeout expired.  Please try again.';
				return;
			}

			if ( isset( $_POST['example_list_table_name'] ) && is_string( $_POST['example_list_table_name'] ) ) {
				$name = wp_unslash( trim( $_POST['example_list_table_name'] ) );
			}
			else {
				$name = '';
			}

			if ( isset( $_POST['example_list_table_email'] ) && is_string( $_POST['example_list_table_email'] ) ) {
				$email = wp_unslash( trim( $_POST['example_list_table_email'] ) );
			}
			else {
				$email = '';
			}

			if ( $name === '' ) {
				self::$errors[] = 'You must enter a name';
			}

			if ( count( self::$errors ) === 0 ) {
				$unix_timestamp = time();
				$table = $wpdb->prefix . 'example_list_table';
				$data = [
					'example_name' => $name,
					'example_email' => $email,
					'example_date' => $unix_timestamp,
				];
				$result = $wpdb->insert( $table, $data );
				if ( $result ) {
					$insert_id = $wpdb->insert_id;

					/*
					Redirect to the "Edit" page for the item.
					*/
					$url = admin_url( 'admin.php?page=' . EDIT_PAGE_SLUG . '&update=add&id=' . $insert_id );
					wp_redirect( $url );
					exit;
				}
				else {
					self::$errors[] = 'Could not insert row into database.';
				}
			}
		}
	}

	public static function page() {
		?>
		<div class="wrap">
			<h1>
				<?php
				echo esc_html( get_admin_page_title() );
				?>
			</h1>
			<?php
			if ( count( self::$errors ) > 0 ) {
				?>
				<div class="error">
					<?php
					foreach ( self::$errors as $error_html ) {
						?>
						<p><?= $error_html ?></p>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
			<form method="post">
				<?php
				wp_nonce_field( 'example-list-table-create', 'example-list-table-create-nonce' );

				$name = isset( $_POST['example_list_table_name'] ) && is_string( $_POST['example_list_table_name'] ) ? wp_unslash( $_POST['example_list_table_name'] ) : '';
				$email = isset( $_POST['example_list_table_email'] ) && is_string( $_POST['example_list_table_email'] ) ? wp_unslash( $_POST['example_list_table_email'] ) : '';
				?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="example_list_table_name">Name</label></th>
						<td><input name="example_list_table_name" type="text" id="example_list_table_name" class="regular-text" value="<?= esc_attr( $name ) ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="example_list_table_email">Email</label></th>
						<td><input name="example_list_table_email" type="email" id="example_list_table_email" class="regular-text" value="<?= esc_attr( $email ) ?>" /></td>
					</tr>
				</table>
				<?php
				submit_button( 'Add New Item to Example List Table' );
				?>
			</form>
		</div>
		<?php
	}
}
