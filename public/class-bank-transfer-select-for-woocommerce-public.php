<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.dicha.gr/
 * @since      1.0
 *
 * @package    Wc_Bank_Transfer_Select
 * @subpackage Wc_Bank_Transfer_Select/public
 */

class Wc_Bank_Transfer_Select_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}



	function dc_gateway_bacs_custom_fields( $description, $payment_id )
	{
		if ( 'bacs' === $payment_id ) {
			ob_start();
			?>
			<div class="dc-bacs-options" style="padding:5px 0;">
				<?php
				woocommerce_form_field ( 'dc_bacs_option', [
					'type'		=> 'select',
					'label'		=> esc_html__( 'Select the bank of your choice for direct bank transfer', 'bank-transfer-select-for-woocommerce' ),
					'required'	=> true,
					'options'	=> Wc_Bank_Transfer_Select_Public::dc_get_available_bacs_bank_names(),
					'class' 	=> [ 'form-row-wide' ],
				]);
				?>
			</div>
			<?php
			$description .= ob_get_clean();
		}

		return $description;
	}


	static function dc_get_available_bacs_bank_names() {
		$bank_names_select_options = [];

		$bacs_options = get_option( 'woocommerce_bacs_accounts', [] );
		if ( empty( $bacs_options ) ) return $bank_names_select_options;

		$available_banks    = [];
		$default_option[''] = esc_html__( 'Select a bank', 'bank-transfer-select-for-woocommerce' );

		foreach ( $bacs_options as $bank ) {
            if ( !empty( $bank['bank_name'] ) && !empty( $bank['iban'] ) ) {
	            $available_banks[ wc_clean( trim( $bank['bank_name'] ) ) ] = esc_html( trim( $bank['bank_name'] ) );
            }
        }

        if ( !empty( $available_banks ) ) {
	        $bank_names_select_options = array_merge( $default_option, $available_banks );
        }

		return apply_filters( 'wc_bank_transfer_select_banks_list', $bank_names_select_options, $bacs_options );
	}


	function dc_bacs_option_validation() {
		if ( isset( $_POST['payment_method'] ) && $_POST['payment_method'] === 'bacs'
		     && isset( $_POST['dc_bacs_option'] ) && '' === $_POST['dc_bacs_option'] ) {
			wc_add_notice( esc_html__( 'Please select the bank that you will make the direct transfer to.', 'bank-transfer-select-for-woocommerce' ), 'error' );
		}
	}


	function save_bacs_option_to_order_meta( $order_id, $data )
	{
		if ( isset( $_POST['dc_bacs_option'] ) && !empty( $_POST['dc_bacs_option'] ) ) {
			update_post_meta( $order_id, 'dc_bacs_option', wc_clean( $_POST['dc_bacs_option'] ) );
		}
	}


	function dc_show_only_selected_bank_details( $banks, $order_id ) {

		$bank_selected = get_post_meta( $order_id, 'dc_bacs_option', true );

		if ( empty( $bank_selected ) ) return $banks;

		foreach ( $banks as $key => $bank ) {

			if ( wc_clean( trim( $bank['bank_name'] ) ) !== $bank_selected ) {
				unset( $banks[ $key ] );
			}
		}

		return $banks;
	}


	function dc_add_bank_name_to_bacs_payment_title( $title, $order ) {

		if ( 'bacs' !== $order->get_payment_method() ) return $title;

		$bank_selected = get_post_meta( $order->get_id(), 'dc_bacs_option', true );

		if ( empty( $bank_selected ) ) return $title;

		$title .= ' (' . $bank_selected . ')';

		return $title;
	}



    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0
     */
    public function enqueue_styles() {

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0
     */
    public function enqueue_scripts() {

    }

}
