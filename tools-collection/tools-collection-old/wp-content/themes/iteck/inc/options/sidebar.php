<?php
/**
 * Extras Tab For Theme Option.
 *
 * @package iteck
 */
Redux::setSection($iteck_pre, array(
	'title' => esc_html__('Sidebar', 'iteck'),
	'id' => 'sidebar-option',
	'icon' => 'el el-pause',
));

Redux::setSection($iteck_pre, array(
	'title'  => esc_html__( 'Sidebar Settings', 'iteck' ),
	"subsection" => true,
	'icon' => 'el el-brush',
	'desc' => esc_html__('Note: each Style can be additionally customized within the chiled theme.', 'iteck'),

	'fields' => array(

		array(
			'id' => 'style_sidebar-start',
			'type' => 'section',
			'title' => esc_html__('Style', 'iteck'),
			'indent' => true,
		),

		array(
            'id' => 'iteck_sidebar_layout',
            'type' => 'button_set',
            'title' => esc_html__('Default Sidebar Layout', 'iteck'),
			'subtitle' => esc_html__('Select the style for Sidebar', 'iteck'),
            'options' => [
                '1' => esc_html__('Clean Style', 'iteck'),
                '2' => esc_html__('Boundary Style', 'iteck'),
                '3' => esc_html__('Elegant Style', 'iteck'),
            ],
            'default' => '1'
		),

		array(
			'id' => 'style_sidebar-end',
			'type' => 'section',
			'indent' => false,
		),


		array(
			'id' => 'blog_single_sidebar-start',
			'type' => 'section',
			'title' => esc_html__('Layout', 'iteck'),
			'indent' => true,
		),

		array(
			'id' => 'iteck_single_sidebar_layout',
			'type' => 'button_set',
			'title' => esc_html__('Post Sidebar Layout', 'iteck'),
			'options' => [
				'left' => esc_html__('Left', 'iteck'),
                'none' => esc_html__('None', 'iteck'),
                'right' => esc_html__('Right', 'iteck'),
			],
			'default' => 'right'
		),

		array(
			'id' => 'blog_sidebar_layout',
			'type' => 'image_select',
			'title' => esc_html__('Blog Sidebar Layout', 'iteck'),
			'options' => [

				'left' => [
					'alt' => 'Left',
					'img' => get_template_directory_uri() . '/assets/images/2cl.png'
				],
				'none' => [
					'alt' => 'None',
					'img' => get_template_directory_uri() . '/assets/images/1co.png'
				],
				'right' => [
					'alt' => 'Right',
					'img' => get_template_directory_uri() . '/assets/images/2cr.png'
				]
			],
			'default' => 'right'
		),

		array(
			'id' => 'search_sidebar_layout',
			'type' => 'image_select',
			'title' => esc_html__('Search Sidebar Layout', 'iteck'),
			'options' => [

				'left' => [
					'alt' => 'Left',
					'img' => get_template_directory_uri() . '/assets/images/2cl.png'
				],
				'none' => [
					'alt' => 'None',
					'img' => get_template_directory_uri() . '/assets/images/1co.png'
				],
				'right' => [
					'alt' => 'Right',
					'img' => get_template_directory_uri() . '/assets/images/2cr.png'
				]
			],
			'default' => 'right'
		),

		array(
			'id' => 'blog_single_sidebar-end',
			'type' => 'section',
			'indent' => false,
		)
	),

));

?>