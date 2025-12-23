<?php
/**
 * Woocommerce Tab For Theme Option.
 *
 * @package iteck
 */

Redux::setSection($iteck_pre, array(
	'title'  => esc_html__( 'Woocommerce', 'iteck' ),
	'icon' => 'el-icon-shopping-cart',
));

Redux::setSection($iteck_pre, array(
	'id' => 'iteck_woocommerce_config',
	"subsection" => true,
	'title' => esc_html__('Woocommerce settings', 'iteck'),
	'desc' => esc_html__('Configuration the Woocommerce', 'iteck'),
	'icon' => 'el el-brush',
	'fields' => array( 

		array(
			'id'       => 'iteck_header_cart',
			'type'     => 'select',
			'title'    => esc_html__('Cart Icon', 'iteck'), 
			'subtitle' => esc_html__('To show Cart icon in header', 'iteck'),
			'options' => array(
				'on' => esc_html__('On', 'iteck'),
				'off' => esc_html__('Off', 'iteck'),
			), 
			'default'  => 'off',
		)
	)
));

?>