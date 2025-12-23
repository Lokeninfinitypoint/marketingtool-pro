<?php
/**
 * Blog Tab For Theme Option.
 *
 * @package iteck
 */

// -> START Blog Options
Redux::setSection($iteck_pre, array(
        'title' => esc_html__('Blog', 'iteck'),
        'id' => 'blog-option',
        'icon' => 'el-icon-th',
)); 
Redux::setSection($iteck_pre, array(
	'id' => 'blog',
	"subsection" => true,
	'title' => esc_html__('Blog setting', 'iteck'),
	'desc' => esc_html__('Configuration blog settings', 'iteck'),
    'icon' => 'el el-brush',
	'fields' => array(

		array(
			'id'       => 'iteck_related_image', 
			'type'     => 'select',
			'title'    => esc_html__('Featured Image in Related Posts', 'iteck'),
			'options' => array(
					'show' => esc_html__('Show', 'iteck'),
					'hide' => esc_html__('Hide', 'iteck'),
			),
			'default'  => 'hide',
		),

		array( 
			'id'       => 'iteck_blog_slide_delay',
			'type'     => 'slider',
			'title'    => esc_html__('Blog Slider Delay', 'iteck'), 
			'desc' => esc_html__('Insert the slider delay for slider in blog sidebar,blog wide and single blog post here. The default value 8000', 'iteck'),
			'default'  => 8000,
			"min"       => 1,
			"step"      => 1,
			"max"       => 10000,
			'display_value' => 'label'
		), 
        
        [
            'id' => 'blog_article',
            'type' => 'section',
            'title' => esc_html__('Blog Articles', 'iteck'),
            'indent' => true,
        ],

        [
            'id' => 'iteck_blog_article_layout',
            'type' => 'button_set',
            'title' => esc_html__('Default Blog Article Layout', 'iteck'),
            'desc' => esc_html__('Note: each Style can be additionally customized within the chiled theme.', 'iteck'),
            'options' => [
                '1' => esc_html__('Clean Style', 'iteck'),
                '2' => esc_html__('Boundary Style', 'iteck'),
                '3' => esc_html__('Elegant Style', 'iteck'),
            ],
            'default' => '1'
        ],

		array(
			'id'       => 'iteck_blog_slider',
			'type'     => 'button_set',
			'title'    => esc_html__('Show latest blog slider', 'iteck'),
			'options' => array(
					'show' => esc_html__('Show', 'iteck'),
					'hide' => esc_html__('Hide', 'iteck'),
			),
			'default'  => 'hide',
		),

		array(
			'id'       => 'iteck_blog_slider_title',
			'type'     => 'text',
			'title'    => esc_html__('Blog slider title', 'iteck'), 
			'default'  => 'Our Journal',
			'required' => array( 'iteck_blog_slider', '=', 'show')
		),
		array(
			'id'       => 'iteck_blog_slider_subtitle',
			'type'     => 'text',
			'title'    => esc_html__('Blog slider sub-title', 'iteck'), 
			'default'  => 'Get the latest articles from our journal, writing, discuss and share',
			'required' => array( 'iteck_blog_slider', '=', 'show')
		),

		array(
			'id'       => 'iteck_blog_popular',
			'type'     => 'button_set',
			'title'    => esc_html__('Show Popular blog', 'iteck'),
			'options' => array(
					'show' => esc_html__('Show', 'iteck'),
					'hide' => esc_html__('Hide', 'iteck'),
			),
			'default'  => 'hide',
		),
		array(
			'id'       => 'iteck_blog_popular_title',
			'type'     => 'text',
			'title'    => esc_html__('Blog popular title', 'iteck'), 
			'default'  => 'Popular Posts',
			'required' => array( 'iteck_blog_popular', '=', 'show')
		),

        array(
			'id'       => 'iteck_blog_tags',
			'type'     => 'button_set',
			'customizer' => true,
			'title'    => esc_html__('Blog Tags', 'iteck'),
			'subtitle' => esc_html__('Enable Tags', 'iteck'),
			'options' => array(
				'on' => esc_html__( 'Show', 'iteck' ),
				'off' => esc_html__( 'Hide','iteck'),
				),
			'default' => 'on',
            'required' => array( 'iteck_blog_article_layout', '=', '3' )
		), 
        array(
			'id'       => 'iteck_blog_button',
			'type'     => 'button_set',
			'customizer' => true,
			'title'    => esc_html__('Blog Button (Continue Reading)', 'iteck'),
			'subtitle' => esc_html__('Enable Blog Button (Continue Reading)', 'iteck'),
			'options' => array(
				'on' => esc_html__( 'Show', 'iteck' ),
				'off' => esc_html__( 'Hide','iteck'),
				),
			'default' => 'on',
            'required' => array( 'iteck_blog_article_layout', '=', '3' )
		),
        array(
            'title' => esc_html__( 'Post Excerpt Size (max word count)', 'iteck' ),
            'subtitle' => esc_html__( 'You can control blog post excerpt size with this option.', 'iteck' ),
            'id' => 'excerpt_size',
            'type' => 'slider',
            'default' => 100,
            'min' => 0,
            'step' => 1,
            'max' => 300,
            'display_value' => 'text',
            'required' => array( 'iteck_blog_article_layout', '=', '3' )
        ),
	),

));

?>