<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @package    Wc_Bank_Transfer_Select
 * @subpackage Wc_Bank_Transfer_Select/includes
 */

class Wc_Bank_Transfer_Select {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      Wc_Bank_Transfer_Select_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0
	 */
	public function __construct() {
		if ( defined( 'WC_BANK_TRANSFER_SELECT_VERSION' ) ) {
			$this->version = WC_BANK_TRANSFER_SELECT_VERSION;
		} else {
			$this->version = '1.0';
		}
		$this->plugin_name = 'bank-transfer-select-for-woocommerce';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wc_Bank_Transfer_Select_Loader. Orchestrates the hooks of the plugin.
	 * - Wc_Bank_Transfer_Select_i18n. Defines internationalization functionality.
	 * - Wc_Bank_Transfer_Select_Admin. Defines all hooks for the admin area.
	 * - Wc_Bank_Transfer_Select_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bank-transfer-select-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bank-transfer-select-for-woocommerce-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bank-transfer-select-for-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bank-transfer-select-for-woocommerce-public.php';

		$this->loader = new Wc_Bank_Transfer_Select_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wc_Bank_Transfer_Select_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Wc_Bank_Transfer_Select_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wc_Bank_Transfer_Select_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'woocommerce_admin_order_data_after_billing_address', $plugin_admin, 'dc_display_bacs_option_order_meta' );
		$this->loader->add_action( 'woocommerce_process_shop_order_meta', $plugin_admin, 'dc_save_bacs_option_order_meta' );
		

        // enqueue styles-scripts
//        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
//		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    }

	/**
	 * Register all of the hooks related to the public-facing functionality of the plugin.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wc_Bank_Transfer_Select_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'woocommerce_gateway_description', $plugin_public, 'dc_gateway_bacs_custom_fields', 20, 2 );
		$this->loader->add_action( 'woocommerce_checkout_process', $plugin_public, 'dc_bacs_option_validation' );
		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'save_bacs_option_to_order_meta', 10, 2 );
		$this->loader->add_filter( 'woocommerce_bacs_accounts', $plugin_public, 'dc_show_only_selected_bank_details', 10, 2 );
		$this->loader->add_filter( 'woocommerce_order_get_payment_method_title', $plugin_public, 'dc_add_bank_name_to_bacs_payment_title', 10, 2 );


//        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
//        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0
	 * @return    Wc_Bank_Transfer_Select_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
