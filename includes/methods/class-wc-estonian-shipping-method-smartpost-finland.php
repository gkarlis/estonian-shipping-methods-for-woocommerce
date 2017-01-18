<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Smartpost shipping method
 *
 * @class     WC_Estonian_Shipping_Method_Smartpost_Finland
 * @extends   WC_Shipping_Method
 * @category  Shipping Methods
 * @package   Estonian_Shipping_Methods_For_WooCommerce
 */
class WC_Estonian_Shipping_Method_Smartpost_Finland extends WC_Estonian_Shipping_Method_Smartpost {

	/**
	 * Class constructor
	 */
	function __construct() {
		// Identify method
		$this->id           = 'smartpost_finland';
		$this->method_title = __( 'Smartpost Finland', 'wc-estonian-shipping-methods' );

		// Construct parent
		parent::__construct();

		$this->country      = 'FI';

		// Add/merge form fields
		$this->add_form_fields();
	}

	function add_form_fields() {
		$this->form_fields = array_merge( $this->form_fields, array(
				'terminals_filter' => array(
					'title'                => __( 'Terminals filter', 'wc-estonian-shipping-methods' ),
					'type'                 => 'select',
					'default'              => 'terminals',
					'options'              => array(
						'terminals'          => __( 'Only terminals', 'wc-estonian-shipping-methods' ),
						'postoffices'        => __( 'Only post offices', 'wc-estonian-shipping-methods' ),
						'both'               => __( 'Both', 'wc-estonian-shipping-methods' )
					)
				),
				'terminals_format' => array(
					'title'                => __( 'Terminals format', 'wc-estonian-shipping-methods' ),
					'type'                 => 'select',
					'default'              => 'name',
					'options'              => array(
						'name'             => __( 'Only terminal name', 'wc-estonian-shipping-methods' ),
						'with_address'     => __( 'Name with address', 'wc-estonian-shipping-methods' )
					)
				),
				'sort_terminals' => array(
					'title'                => __( 'Sort terminals by', 'wc-estonian-shipping-methods' ),
					'type'                 => 'select',
					'default'              => 'alphabetically',
					'options'              => array(
						'none'             => __( 'No sorting', 'wc-estonian-shipping-methods' ),
						'alphabetically'   => __( 'Alphabetically', 'wc-estonian-shipping-methods' ),
						'cities_first'     => __( 'Bigger cities first, then alphabetically the rest', 'wc-estonian-shipping-methods' )
					)
				),
				'group_terminals' => array(
					'title'                => __( 'Group terminals', 'wc-estonian-shipping-methods' ),
					'type'                 => 'select',
					'default'              => 'cities',
					'options'              => array(
						'cities'           => __( 'By cities', 'wc-estonian-shipping-methods' )
					)
				)
			)
		);
	}

	/**
	 * Get URL where to fetch terminals from
	 *
	 * @return string Terminals remote URL
	 */
	function get_terminals_url( $shipping_country = '' ) {
		// Get terminals URL if exists
		$terminals_url    = add_query_arg( 'country', $this->country, $this->api_url );

		if( $terminals_url ) {
			if( $this->get_option( 'terminals_filter', 'terminals' ) == 'terminals' ) {
				$terminals_url = add_query_arg( 'type', 'APT', $terminals_url );
			}
			elseif( $this->get_option( 'terminals_filter', 'terminals' ) == 'postoffices' ) {
				$terminals_url = add_query_arg( 'type', 'PO', $terminals_url );
			}
			elseif( $this->get_option( 'terminals_filter', 'terminals' ) == 'both' && $this->terminals_fetched === FALSE ) {
				$terminals_url = add_query_arg( 'type', 'APT', $terminals_url );
			}
			elseif( $this->get_option( 'terminals_filter', 'terminals' ) == 'both' && $this->terminals_fetched === TRUE ) {
				$terminals_url = add_query_arg( 'type', 'PO', $terminals_url );
			}

			$terminals_url = add_query_arg( 'request', 'destinations', $terminals_url );
		}

		return apply_filters( 'wc_shipping_smartpost_terminals_url', $terminals_url, $this->country, $this->api_url );
	}
}