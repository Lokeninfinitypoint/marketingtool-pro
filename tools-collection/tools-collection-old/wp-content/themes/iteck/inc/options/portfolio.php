<?php
/**
 * Portfolio Tab For Theme Option.
 *
 * @package iteck
 */
Redux::setSection($iteck_pre, array(
	'title'  => esc_html__( 'portfolio', 'iteck' ),
	'icon'=>'el el-briefcase',
));
Redux::setSection($iteck_pre, array(
	"subsection" => true,
	'title' => esc_html__('Portfolio settings', 'iteck'),
	'desc' => esc_html__('Configuration portfolio settings', 'iteck'),
	'icon' => 'el el-brush',
	'fields' => array(
		array(
			'id'       => 'iteck_portfolio_all',
			'type'     => 'text',
			'title'    => esc_html__('All categories filter', 'iteck'), 
			'subtitle' => esc_html__('Portfolio Text Filter for all categories', 'iteck'),
			'desc' => esc_html__('Insert your text for portfolio filter for all categories. The default text is "All"', 'iteck'),
			'default'  => 'All',
		),  
		array(
			'id'       => 'iteck_other_port_sub',
			'type'     => 'text',
			'title'    => esc_html__('Other Portfolio Section Subtitle', 'iteck'), 
			'desc' => esc_html__('Insert your text for subt title of other portfolio section on single portfolio page.<br/>Leave it blank if you want to use the default text.', 'iteck'),
			'default'  => 'See our other portfolio',
		),
		array(
			'id'       => 'iteck_other_port_title',
			'type'     => 'text',
			'title'    => esc_html__('Other Portfolio Section Title', 'iteck'), 
			'desc' => esc_html__('Insert your text for title of other portfolio section on single portfolio page.<br/>Leave it blank if you want to use the default text.', 'iteck'),
			'default'  => 'Other portfolio',
		),
	),
));


?>