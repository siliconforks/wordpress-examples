<?php

namespace Example_List_Table;

use WP_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Example_List_Table extends WP_List_Table {
	public $search_string = '';

	public function __construct( $args = array() ) {
		parent::__construct(
			array(
				'singular' => 'example-list-table-item',
				'plural'   => 'example-list-table-items',
			)
		);
	}

	public function prepare_items() {
		global $wpdb;

		$this->search_string = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';

		$per_page = $this->get_items_per_page( 'toplevel_page_example_list_table_per_page' );

		$paged = $this->get_pagenum();

		$offset = ( $paged - 1 ) * $per_page;

		if ( isset( $_REQUEST['orderby'] ) ) {
			$orderby = $_REQUEST['orderby'];
		}
		else {
			$orderby = 'name';
		}

		if ( isset( $_REQUEST['order'] ) ) {
			$order = $_REQUEST['order'];
		}
		else {
			$order = 'asc';
		}

		if ( ! in_array( $order, [ 'asc', 'desc' ], TRUE ) ) {
			$order = 'asc';
		}

		switch ( $orderby ) {
		case 'name':
			$order_sql = 'example_name ' . $order . ', example_id ' . $order;
			break;
		case 'email':
			$order_sql = 'example_email' . $order . ', example_id ' . $order;
			break;
		case 'date':
			$order_sql = 'example_date ' . $order . ', example_id ' . $order;
			break;
		default:
			$order_sql = 'example_name ' . $order . ', example_id ' . $order;
			break;
		}

		$table = $wpdb->prefix . 'example_list_table';
		$sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . $table;
		if ( $this->search_string !== '' ) {
			$sql .= ' WHERE example_name LIKE \'%' . esc_sql( $this->search_string ) . '%\'';
			$sql .= ' OR example_email LIKE \'%' . esc_sql( $this->search_string ) . '%\'';
		}
		$sql .= ' ORDER BY ' . $order_sql . ' LIMIT ' . $offset . ', ' . $per_page;
		$this->items = $wpdb->get_results( $sql, ARRAY_A );

		$sql = 'SELECT FOUND_ROWS()';
		$total_items = $wpdb->get_var( $sql );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			)
		);
	}

	public function no_items() {
		echo 'No items found in example list table.';
	}

	protected function get_bulk_actions() {
		$actions = array();
		$actions['delete'] = 'Delete';
		return $actions;
	}

	public function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'name' => 'Name',
			'email' => 'Email',
			'date' => 'Date',
		);

		return $columns;
	}

	protected function get_sortable_columns() {
		$columns = array(
			'name' => 'name',
			'email' => 'email',
			'date' => 'date',
		);

		return $columns;
	}

	protected function handle_row_actions( $item, $column_name, $primary ) {
		if ( $column_name === $primary ) {
			$actions = array();
			$edit_link = 'admin.php?page=' . EDIT_PAGE_SLUG . '&id=' . $item['example_id'];
			$actions['edit'] = '<a href="' . esc_url( $edit_link ) . '">Edit</a>';
			$delete_link = wp_nonce_url( 'admin.php?page=' . PAGE_SLUG . '&action=delete&id=' . $item['example_id'], 'bulk-example-list-table-items' );
			$actions['delete'] = '<a href="' . esc_url( $delete_link ) . '">Delete</a>';
			return $this->row_actions( $actions );
		}
		else {
			return '';
		}
	}

	public function column_cb( $item ) {
		$id = $item['example_id'];
		$name = $item['example_name'];
		?>
		<label class="screen-reader-text" for="cb-select-<?= $id ?>">
			<?= sprintf( 'Select %s', esc_html( $name ) ) ?>
		</label>
		<input type="checkbox" name="example_list_table_items[]" id="cb-select-<?= $id ?>" value="<?= $id ?>" />
		<?php
	}

	public function column_name( $item ) {
		$edit_link = 'admin.php?page=' . EDIT_PAGE_SLUG . '&id=' . $item['example_id'];
		echo '<strong><a href="' . esc_url( $edit_link ) . '">' . esc_html( $item['example_name'] ) . '</a></strong>';
	}

	public function column_email( $item ) {
		echo esc_html( $item['example_email'] );
	}

	public function column_date( $item ) {
		printf(
			__( '%1$s at %2$s' ),
			wp_date( __( 'Y/m/d' ), $item['example_date'] ),
			wp_date( __( 'g:i a' ), $item['example_date'] )
		);
	}
}
