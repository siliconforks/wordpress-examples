<?php

namespace Example_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class List_Page {
	private static $list_table;

	private static $ids_to_delete = NULL;

	public static function load() {
		global $wpdb;

		if ( ! current_user_can( CAPABILITY ) ) {
			wp_die( 'Your user account is not authorized to view this page.', 403 );
		}

		require __DIR__ . '/class-example-list-table.php';

		self::$list_table = new Example_List_Table();
		$pagenum = self::$list_table->get_pagenum();

		add_screen_option( 'per_page' );

		switch ( self::$list_table->current_action() ) {
		case 'dodelete':
			check_admin_referer( 'example-list-table-delete' );

			if ( empty( $_REQUEST['example_list_table_items'] ) ) {
				$url = 'admin.php?page=' . PAGE_SLUG;
				wp_redirect( $url );
				exit;
			}

			$ids = array_map( 'intval', (array) $_REQUEST['example_list_table_items'] );

			$delete_count = 0;
			$table = $wpdb->prefix . 'example_list_table';
			foreach ( $ids as $id ) {
				$wpdb->delete( $table, [ 'example_id' => $id ] );
				++$delete_count;
			}

			$url = 'admin.php?page=' . PAGE_SLUG;
			$url = add_query_arg(
				array(
					'delete_count' => $delete_count,
					'update'       => 'del',
				),
				$url
			);
			wp_redirect( $url );
			exit;

			break;
		case 'delete':
			check_admin_referer( 'bulk-example-list-table-items' );

			if ( empty( $_REQUEST['example_list_table_items'] ) && empty( $_REQUEST['id'] ) ) {
				$url = 'admin.php?page=' . PAGE_SLUG;
				wp_redirect( $url );
				exit;
			}

			if ( empty( $_REQUEST['example_list_table_items'] ) ) {
				self::$ids_to_delete = array( intval( $_REQUEST['id'] ) );
			}
			else {
				self::$ids_to_delete = array_map( 'intval', (array) $_REQUEST['example_list_table_items'] );
			}

			break;
		default:
			self::$list_table->prepare_items();
			$total_pages = self::$list_table->get_pagination_arg( 'total_pages' );
			if ( $pagenum > $total_pages && $total_pages > 0 ) {
				wp_redirect( add_query_arg( 'paged', $total_pages ) );
				exit;
			}
			break;
		}
	}

	public static function page() {
		if ( empty( self::$ids_to_delete ) ) {
			self::list_items();
		}
		else {
			self::delete_items();
		}
	}

	private static function list_items() {
		$messages = array();
		if ( isset( $_GET['update'] ) ) {
			switch ( $_GET['update'] ) {
			case 'del':
				$delete_count = isset( $_GET['delete_count'] ) ? (int) $_GET['delete_count'] : 0;
				if ( 1 == $delete_count ) {
					$message = 'Item deleted.';
				}
				else {
					$message = sprintf( '%s items deleted.', $delete_count );
				}
				$messages[] = '<div id="message" class="updated notice is-dismissible"><p>' . $message . '</p></div>';
				break;
			case 'add':
				$message = 'New item created.';
				$messages[] = '<div id="message" class="updated notice is-dismissible"><p>' . $message . '</p></div>';
				break;
			}
		}

		/*
		Note that this will be moved below the heading by JavaScript in wp-admin/js/common.js.
		*/
		foreach ( $messages as $message_html ) {
			echo $message_html;
		}
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline">
				<?php
				echo esc_html( get_admin_page_title() );
				?>
			</h1>
			<a href="<?= 'admin.php?page=' . ADD_PAGE_SLUG ?>" class="page-title-action">Add New</a>
			<?php
			$s = self::$list_table->search_string;
			if ( $s !== '' ) {
				printf( '<span class="subtitle">Search results for &#8220;%s&#8221;</span>', esc_html( $s ) );
			}
			?>
			<hr class="wp-header-end">
			<?php
			self::$list_table->views();
			?>
			<form method="get">
				<input type="hidden" name="page" value="<?= PAGE_SLUG ?>">
				<?php
				self::$list_table->search_box( 'Search Example List Table', 'example-list-table' );
				self::$list_table->display();
				?>
			</form>
		</div>
		<?php
	}

	private static function delete_items() {
		global $wpdb;
		?>
		<form action="admin.php?page=<?= PAGE_SLUG ?>" method="post">
			<?php
			wp_nonce_field( 'example-list-table-delete' );
			?>
			<div class="wrap">
				<h1>Delete Items in Example List Table</h1>
				<p>
					<?php
					if ( count( self::$ids_to_delete ) === 1 ) {
						echo 'You have specified this item for deletion:';
					}
					else {
						echo 'You have specified these items for deletion:';
					}
					?>
				</p>
				<ul>
				<?php
				$table = $wpdb->prefix . 'example_list_table';
				foreach ( self::$ids_to_delete as $id ) {
					$sql = $wpdb->prepare( 'SELECT * FROM ' . $table . ' WHERE example_id = %s', $id );
					$row = $wpdb->get_row( $sql, ARRAY_A );
					if ( $row ) {
						echo '<li>';
						echo '<input type="hidden" name="example_list_table_items[]" value="' . $id . '">';
						echo esc_html( sprintf( 'ID #%1$s: %2$s', $id, $row['example_name'] ) );
						echo '</li>';
					}
				}
				?>
				</ul>
				<input type="hidden" name="action" value="dodelete" />
				<?php
				submit_button( 'Confirm Deletion' );
				?>
			</div>
		</form>
		<?php
	}
}
