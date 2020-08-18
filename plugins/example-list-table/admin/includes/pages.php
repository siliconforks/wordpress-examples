<?php

namespace Example_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const CAPABILITY = 'manage_options';

const PAGE_SLUG = 'example-list-table';
const ADD_PAGE_SLUG = 'example-list-table-new';
const EDIT_PAGE_SLUG = 'example-list-table-edit';

add_action( 'admin_menu', static function () {
	$page_hook_suffix = add_menu_page( 'Example List Table', 'List Table', CAPABILITY, PAGE_SLUG, static function () {
		List_Page::page();
	} );

	add_action( 'load-' . $page_hook_suffix, static function () {
		require __DIR__ . '/class-list-page.php';
		List_Page::load();
	} );

	add_submenu_page( PAGE_SLUG, 'Example List Table', 'All Items', CAPABILITY, PAGE_SLUG );

	$add_page_hook_suffix = add_submenu_page( PAGE_SLUG, 'Add New Item to Example List Table', 'Add New', CAPABILITY, ADD_PAGE_SLUG, static function () {
		Add_Page::page();
	} );

	add_action( 'load-' . $add_page_hook_suffix, static function () {
		require __DIR__ . '/class-add-page.php';
		Add_Page::load();
	} );

	$edit_page_hook_suffix = add_submenu_page( PAGE_SLUG, 'Edit Item in Example List Table', 'Edit', CAPABILITY, EDIT_PAGE_SLUG, static function () {
		Edit_Page::page();
	} );

	add_action( 'load-' . $edit_page_hook_suffix, static function () {
		require __DIR__ . '/class-edit-page.php';
		Edit_Page::load();
	} );
} );

add_action( 'admin_head', static function () {
	// we don't actually want the edit page to appear in the menu
	// https://core.trac.wordpress.org/ticket/18850#comment:11
	remove_submenu_page( PAGE_SLUG, EDIT_PAGE_SLUG );
} );

add_filter( 'set-screen-option', static function ( $screen_option, $option, $value ) {
	if ( $option === 'toplevel_page_example_list_table_per_page' ) {
		$screen_option = $value;
	}
	return $screen_option;
}, 10, 3 );
