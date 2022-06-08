<?php
/**
 * Plugin Name:       Bank Transfer Select for WooCommerce
 * Description:       Allows customers to select a specific bank to transfer their payment.
 * Version:           1.0
 * Author:            Theo Gkitsos
 * Author URI:        https://www.dicha.gr/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bank-transfer-select-for-woocommerce
 * Domain Path:       /languages
 * Requires at least: 5.7
 * Tested up to:      6.0
 * Requires PHP:      7.2
 * Stable tag:        1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'WC_BANK_TRANSFER_SELECT_VERSION', '1.0' );
define( 'WC_BANK_TRANSFER_SELECT_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'WC_BANK_TRANSFER_SELECT_BASE_FILE', 'bank-transfer-select-for-woocommerce/bank-transfer-select-for-woocommerce.php' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bank-transfer-select-for-woocommerce-activator.php
 */
function activate_wc_bank_transfer_select() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bank-transfer-select-for-woocommerce-activator.php';
    Wc_Bank_Transfer_Select_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bank-transfer-select-for-woocommerce-deactivator.php
 */
function deactivate_wc_bank_transfer_select() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bank-transfer-select-for-woocommerce-deactivator.php';
    Wc_Bank_Transfer_Select_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_bank_transfer_select' );
register_deactivation_hook( __FILE__, 'deactivate_wc_bank_transfer_select' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bank-transfer-select-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0
 */
function run_wc_bank_transfer_select() {
	$plugin = new Wc_Bank_Transfer_Select();
	$plugin->run();
}
run_wc_bank_transfer_select();
