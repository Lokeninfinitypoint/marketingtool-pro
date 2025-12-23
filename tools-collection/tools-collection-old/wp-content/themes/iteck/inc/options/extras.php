<?php
/**
 * Extras Tab For Theme Option.
 *
 * @package iteck
 */

Redux::setSection($iteck_pre, array(
	'title'  => esc_html__( 'Extras', 'iteck' ),
	'icon' => 'el el-plus-sign',
));

Redux::setSection($iteck_pre, array( 
	'id' => 'page_404',
	"subsection" => true,
	'title' => esc_html__('404 Page', 'iteck'),
	'icon' => 'el el-brush',
	'fields' => array(
		array(
			'id'       => 'iteck_enable_custom_404',
			'type'     => 'switch',
			'customizer' => true,
			'title'    => esc_html__('Enable custom 404 page', 'iteck'),
			'default' => false,
		),  
		array(
			'id'       => 'iteck_custom_404_page',
			'type'     => 'select',
			'customizer' => true,
			'title'    => esc_html__('Custom 404 page', 'iteck'),
			'data'  => 'pages',

			'required' => array('iteck_enable_custom_404','=',true),
		),
	),
));

?>