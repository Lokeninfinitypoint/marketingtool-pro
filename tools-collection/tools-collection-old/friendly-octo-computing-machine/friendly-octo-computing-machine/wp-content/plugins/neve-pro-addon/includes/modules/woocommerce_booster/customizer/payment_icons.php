<?php
/**
 * Payment icons WooCommerce Booster Module
 *
 * @package WooCommerce Booster
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Customizer;

use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Control;
use Neve\Customizer\Types\Partial;
use Neve\Customizer\Types\Section;
use Neve_Pro\Core\Loader;
use Neve_Pro\Traits\Sanitize_Functions;

/**
 * Class Payment_Icons
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Customizer
 */
class Payment_Icons extends Base_Customizer {
	use Sanitize_Functions;

	/**
	 * All payment options.
	 *
	 * @var array
	 */
	private $payment_options;

	/**
	 * Default payment options.
	 *
	 * @var array
	 */
	public static $order_default_payment_options = array(
		'visa',
		'mastercard',
		'paypal',
		'stripe',
	);

	/**
	 * Payment_Icons constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'setup_variables' ] );
	}

	/**
	 * Setup variables.
	 */
	public function setup_variables() {
		$this->payment_options = self::get_payment_icons_options();
	}

	/**
	 * Get the payment icons options
	 *
	 * @return array
	 */
	private static function get_payment_icons_options() {
		$payment_icons           = array(
			'visa'             => __( 'Visa', 'neve-pro-addon' ),
			'visa-electron'    => __( 'Visa Electron', 'neve-pro-addon' ),
			'paypal'           => __( 'PayPal', 'neve-pro-addon' ),
			'stripe'           => __( 'Stripe', 'neve-pro-addon' ),
			'mastercard'       => __( 'Mastercard', 'neve-pro-addon' ),
			'cash-on-delivery' => __( 'Cash on Delivery', 'neve-pro-addon' ),
			'amazon'           => __( 'Amazon', 'neve-pro-addon' ),
			'american-express' => __( 'American Express', 'neve-pro-addon' ),
			'apple-pay'        => __( 'Apple Pay', 'neve-pro-addon' ),
			'bank-transfer'    => __( 'Bank Transfer', 'neve-pro-addon' ),
			'google-pay'       => __( 'Google Pay', 'neve-pro-addon' ),
			'google-wallet'    => __( 'Google Wallet', 'neve-pro-addon' ),
			'maestro'          => __( 'Maestro', 'neve-pro-addon' ),
			'pay-u'            => __( 'Pay U', 'neve-pro-addon' ),
			'western-union'    => __( 'Western Union', 'neve-pro-addon' ),
		);
		$available_payment_icons = apply_filters( 'neve_payment_options', $payment_icons );

		if ( ! is_array( $available_payment_icons ) ) {
			return $payment_icons;
		}

		return $available_payment_icons;
	}

	/**
	 * Base initialization.
	 */
	public function init() {
		parent::init();
		add_action( 'customize_controls_print_styles', array( $this, 'hide_payment_icons_section' ), 999 );
	}

	/**
	 * Add customizer controls
	 */
	public function add_controls() {
		$this->add_payment_icons_section();
		$this->add_payment_icons_controls();
		$this->partial_refresh();
	}

	/**
	 * Hide the payment icons section
	 */
	public function hide_payment_icons_section() {
		echo '<style>';
		echo '#accordion-section-neve_payment_icons { display: none!important }';
		echo '</style>';
	}

	/**
	 * Add payment icons section
	 */
	public function add_payment_icons_section() {
		$this->add_section(
			new Section(
				'neve_payment_icons',
				array(
					'priority' => 70,
					'title'    => esc_html__( 'Payment Icons', 'neve-pro-addon' ),
					'panel'    => 'woocommerce',
				)
			)
		);
	}

	/**
	 * Add Payment Controls
	 */
	private function add_payment_icons_controls() {
		$this->add_control(
			new Control(
				'neve_enable_payment_icons',
				array(
					'default'           => false,
					'sanitize_callback' => 'neve_sanitize_checkbox',
				),
				array(
					'label'       => esc_html__( 'Enable Payment Icons', 'neve-pro-addon' ),
					'description' => sprintf(
						/* translators: %s is link to section with label 'here'. */
						esc_html__( 'Click %s to edit payment icons', 'neve-pro-addon' ),
						sprintf(
							'<span class="quick-links"><a href="#" data-control-focus="neve_payment_icons_new">%s</a></span>',
							esc_html__( 'here', 'neve-pro-addon' )
						)
					),
					'section'     => 'neve_cart_page_layout',
					'type'        => 'neve_toggle_control',
					'priority'    => 40,
				)
			)
		);

		$default_value = self::get_payment_icons_default_value();
		$this->add_control(
			new Control(
				'neve_payment_icons_new',
				[
					'sanitize_callback' => [ $this, 'sanitize_payment_icons_repeater' ],
					'default'           => $default_value,
				],
				[
					'label'            => esc_html__( 'Payment Icons Order', 'neve-pro-addon' ),
					'section'          => 'neve_payment_icons',
					'fields'           => [
						'title' => [
							'type'  => 'text',
							'label' => __( 'Title', 'neve-pro-addon' ),
						],
					],
					'new_item_fields'  => [
						'title' => [
							'type'  => 'text',
							'label' => __( 'Title', 'neve-pro-addon' ),
						],
						'svg'   => [
							'type'  => Loader::has_compatibility( 'custom_payment_icons' ) ? 'textarea' : 'text',
							'label' => __( 'SVG', 'neve-pro-addon' ),
						],
					],
					'components'       => $this->payment_options,
					'allow_new_fields' => 'yes',
					'priority'         => 10,
				],
				'\Neve\Customizer\Controls\React\Repeater'
			)
		);
	}

	/**
	 * Get the default values for the new payment icons control
	 *
	 * @return string
	 */
	public static function get_payment_icons_default_value() {
		$components    = self::get_payment_icons_options();
		$current_value = get_theme_mod( 'neve_payment_icons', wp_json_encode( self::$order_default_payment_options ) );
		if ( empty( $current_value ) ) {
			return wp_json_encode( array() );
		}
		$current_value    = json_decode( $current_value, true );
		$new_control_data = array();
		foreach ( $current_value as $payment_icon_component ) {
			if ( array_key_exists( $payment_icon_component, $components ) ) {
				$new_control_data[ $payment_icon_component ] = (object) [
					'slug'       => $payment_icon_component,
					'title'      => $components[ $payment_icon_component ],
					'visibility' => 'yes',
					'blocked'    => 'yes',
				];
			}
		}

		foreach ( $components as $component_id => $label ) {
			if ( ! array_key_exists( $component_id, $new_control_data ) ) {
				$new_control_data[ $component_id ] = (object) [
					'slug'       => $component_id,
					'title'      => $label,
					'visibility' => 'no',
					'blocked'    => 'yes',
				];
			}
		}

		return wp_json_encode( array_values( $new_control_data ) );
	}

	/**
	 * Partial refresh
	 */
	private function partial_refresh() {
		$this->add_partial(
			new Partial(
				'neve_payment_icons_new',
				array(
					'selector'            => '.nv-payment-icons-wrapper',
					'settings'            => array(
						'neve_payment_icons_new',
					),
					'render_callback'     => '\Neve_Pro\Modules\Woocommerce_Booster\Views\Payment_Icons::render_payment_icons',
					'container_inclusive' => true,
				)
			)
		);
	}
}
