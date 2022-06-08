<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @package    Wc_Bank_Transfer_Select
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once( WP_PLUGIN_DIR . '/bank-transfer-select-for-woocommerce/admin/class-bank-transfer-select-for-woocommerce-admin.php' );

if ( !class_exists( 'Wc_Bank_Transfer_Select_Admin' ) ) exit;

$plugin_admin = new Wc_Bank_Transfer_Select_Admin( 'bank-transfer-select-for-woocommerce', '1.0' );
$plugin_admin->dc_purge_menu_html_transients();

delete_option( 'dc_menu_html_index' );
delete_option( 'dc_menu_nonces_index' );
delete_option( 'dc_mc_nocache_menus' );
