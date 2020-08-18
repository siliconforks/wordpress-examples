<?php

namespace Example_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Edit_Page {
	/**
	 * An array of error messages.
	 * Note that each error message may contain HTML.
	 */
	private static $errors = [];

	private static $id;

	private static $item;

	public static function load() {
		global $wpdb;

		/*
		When this page is displayed, the "All Items" menu item should be highlighted.
		*/
		add_filter( 'parent_file', static function ( $parent_file ) {
			return PAGE_SLUG;
		} );
		add_filter( 'submenu_file', static function ( $submenu_file, $parent_file ) {
			return PAGE_SLUG;
		}, 10, 2 );

		if ( ! current_user_can( CAPABILITY ) ) {
			wp_die( 'Your user account is not authorized to view this page.', 403 );
		}

		if ( ! isset( $_GET['id'] ) || ! is_string( $_GET['id'] ) ) {
			wp_die( 'No ID specified.', 400 );
		}

		self::$id = $_GET['id'];

		$table = $wpdb->prefix . 'example_list_table';
		$sql = $wpdb->prepare( 'SELECT * FROM ' . $table . ' WHERE example_id = %s', self::$id );
		self::$item = $wpdb->get_row( $sql, ARRAY_A );
		if ( ! self::$item ) {
			wp_die( 'No item with the specified ID was found in the database.', 404 );
		}

		// check for the presence of the nonce to determine whether it was a POST request
		if ( isset( $_POST['example-list-table-edit-nonce'] ) && is_string( $_POST['example-list-table-edit-nonce'] ) ) {
			if ( ! wp_verify_nonce( $_POST['example-list-table-edit-nonce'], 'example-list-table-edit-' . self::$id ) ) {
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
				$table = $wpdb->prefix . 'example_list_table';
				$data = [
					'example_name' => $name,
					'example_email' => $email,
				];
				$result = $wpdb->update( $table, $data, [ 'example_id' => self::$id ] );
				if ( $result === 0 || $result === 1 ) {
					/*
					Redirect to the current page.
					*/
					$url = admin_url( 'admin.php?page=' . EDIT_PAGE_SLUG . '&update=edit&id=' . self::$id );
					wp_redirect( $url );
					exit;
				}
				else {
					self::$errors[] = 'Could not update row in database.';
				}
			}
		}
	}

	public static function page() {
		$messages = array();
		if ( isset( $_GET['update'] ) ) {
			switch ( $_GET['update'] ) {
			case 'add':
				$message = '<p>New item created.</p>';
				$message .= '<p><a href="admin.php?page=' . PAGE_SLUG . '">&larr; Back to Example List Table</a></p>';
				$messages[] = '<div id="message" class="updated notice is-dismissible">' . $message . '</div>';
				break;
			case 'edit':
				$message = '<p>Item updated.</p>';
				$message .= '<p><a href="admin.php?page=' . PAGE_SLUG . '">&larr; Back to Example List Table</a></p>';
				$messages[] = '<div id="message" class="updated notice is-dismissible">' . $message . '</div>';
				break;
			}
		}
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline">
				Edit Item
				<?php
				echo esc_html( self::$item['example_name'] );
				?>
			</h1>
			<a href="<?= 'admin.php?page=' . ADD_PAGE_SLUG ?>" class="page-title-action">Add New</a>
			<hr class="wp-header-end">
			<?php
			foreach ( $messages as $message_html ) {
				echo $message_html;
			}
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
				wp_nonce_field( 'example-list-table-edit-' . self::$id, 'example-list-table-edit-nonce' );

				$name = isset( $_POST['example_list_table_name'] ) && is_string( $_POST['example_list_table_name'] ) ? wp_unslash( $_POST['example_list_table_name'] ) : self::$item['example_name'];
				$email = isset( $_POST['example_list_table_email'] ) && is_string( $_POST['example_list_table_email'] ) ? wp_unslash( $_POST['example_list_table_email'] ) : self::$item['example_email'];
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
				submit_button( 'Update Item in Example List Table' );
				?>
			</form>
		</div>
		<?php
	}
}
