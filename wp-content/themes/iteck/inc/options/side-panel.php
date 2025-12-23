<?php
/**
 * Side panel Tab For Theme Option.
 *
 * @package iteck
 */
Redux::setSection($iteck_pre, array(
	'title'  => esc_html__( 'Side Panel', 'iteck' ),
	'icon' => 'el-icon-lines',
));

Redux::setSection($iteck_pre, array(
	"subsection" => true,
	'title' => esc_html__('Side Panel settings', 'iteck'),
	'desc' => esc_html__('Assign menu for side panel section.', 'iteck'),
	'icon' => 'el el-brush',
	'fields' => array(
		array(
			'id'       => 'iteck_sidepanel_set',
			'type'     => 'select',
			'title'    => esc_html__('Side Panel', 'iteck'),
			'options' => array(
				'default' => esc_html__('Standard Side panel', 'iteck'),
				'custom' => esc_html__('Custom Side panel', 'iteck'),
			),
		),
		array(
			'id'    => 'iteck_sidepanel_set_list',
			'type'  => 'select',
			'title'    => esc_html__('Side panel', 'iteck'),
			'data'  => 'posts',
			'args'  => array(
				'post_type' => 'sidepanel',
				'orderby'   => 'title',
				'order'     => 'ASC',
			)
		),     
	),
));

?>