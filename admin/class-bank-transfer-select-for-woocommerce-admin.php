<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Wc_Bank_Transfer_Select
 * @subpackage Wc_Bank_Transfer_Select/admin
 */

class Wc_Bank_Transfer_Select_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version           The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}


	/**
     * Displays selected bank to the admin order edit page.
     * 
	 * @param $order
	 *
	 * @return void
	 */
	function dc_display_bacs_option_order_meta( $order )
	{
		if ( 'bacs' !== $order->get_payment_method() ) return;

        $banks_list     = Wc_Bank_Transfer_Select_Public::dc_get_available_bacs_bank_names();
		$bank_selected  = $order->get_meta( 'dc_bacs_option' );

        // if the selected bank gets deleted, is added back to the list for this order only
        if ( !empty( $bank_selected ) && !in_array( $bank_selected, $banks_list ) ) {
	        $banks_list[ wc_clean( trim( $bank_selected ) ) ] = esc_html( $bank_selected );
        }
		?>
        <div class="address">
            <p<?php if ( empty( $bank_selected ) ) echo ' class="none_set"'; ?>>
                <strong><?php esc_html_e( 'Selected bank for transfer', 'bank-transfer-select-for-woocommerce' ); ?>:</strong>
				<?php echo wp_kses_post( $bank_selected ); ?>
            </p>
        </div>
        <div class="edit_address">
			<?php
			woocommerce_wp_select([
				'id'            => 'dc_bacs_option',
				'label'         => esc_html__( 'Select a bank for transfer', 'bank-transfer-select-for-woocommerce' ),
				'value'         => $bank_selected,
				'options'       => $banks_list,
				'wrapper_class' => 'form-field-wide'
			]);
			?>
        </div>
		<?php
	}


	function dc_save_bacs_option_order_meta( $order_id ) {
		if ( isset( $_POST['dc_bacs_option'] ) ) {
			update_post_meta( $order_id, 'dc_bacs_option', wc_clean( trim( $_POST['dc_bacs_option'] ) ) );
		}
	}



	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0
     *
	 */
	public function enqueue_styles( $hook ) {

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0
	 */
	public function enqueue_scripts( $hook ) {

	}
}
