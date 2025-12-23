<?php
/**
 * Footer Tab For Theme Option.
 *
 * @package iteck
 */

Redux::setSection($iteck_pre, array(
	'title'  => esc_html__( 'Footer', 'iteck' ),
	'icon' => 'el el-photo',
));

Redux::setSection($iteck_pre, array(
	"subsection" => true,
	'title' => esc_html__('Footer settings', 'iteck'),
	'desc' => esc_html__('Assign menu for footer section.', 'iteck'),
	'icon' => 'el el-brush',
	'fields' => array(
		array(
			'id'       => 'iteck_footer_set',
			'type'     => 'select',
			'title'    => esc_html__('Footer type', 'iteck'),
			'options' => array(
				'default' => esc_html__('Standard Footer', 'iteck'),
				'custom' => esc_html__('Custom Footer', 'iteck'),
			),
			'default' => 'default',
		),
		array(
			'id'    => 'iteck_footer_set_list',
			'type'  => 'select',
			'title'    => esc_html__('Custom Header name', 'iteck'),
			'data'  => 'posts',
			'args'  => array(
				'post_type' => 'footer',
				'orderby'   => 'title',
				'order'     => 'ASC',
			),
			'required' => ['iteck_footer_set', '=', 'custom'],
		),     
	),
));

Redux::setSection($iteck_pre, array(
	'id' => 'logo',
	"subsection" => true,
	'title' => esc_html__('Footer logo', 'iteck'),
	'desc' => esc_html__('Configuration the style settings', 'iteck'),
	'icon' => 'el el-brush',
	'fields' => array(
		array(
			'id'       => 'iteck_footer_logo_white',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__('Logo White Text', 'iteck'), 
			'subtitle' => esc_html__('Upload your logo for white text (standard) footer (Recommended size 240x80px)', 'iteck'),
			'default'  => array('url'=>get_template_directory_uri().'/assets/images/logo-dark.png'),
		),

		array(
			'id'       => 'iteck_footer_logo_dark',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__('Logo Dark Text', 'iteck'), 
			'subtitle' => esc_html__('Upload your logo for dark text (standard) footer (Recommended size 240x80px)', 'iteck'),
			'default'  => array('url'=>get_template_directory_uri().'/assets/images/logo-white.png'),
		), 
		array(
			'id'       => 'iteck_footer_text',
			'type'     => 'editor',
			'title'    => esc_html__('Footer Text', 'iteck'), 
			'subtitle' => esc_html__('Upload your logo for dark text (standard) footer (Recommended size 240x80px)', 'iteck'),
			'default' => '',
			'args'   => array('teeny'  => true,'textarea_rows'=> 10)
		), 
	)
));

Redux::setSection($iteck_pre, array(
	'id' => 'iteck_footer_social',
	"subsection" => true,
	'title' => esc_html__('Footer social', 'iteck'),
	'desc' => esc_html__('Configuration the footer social icons', 'iteck'),
	'icon' => 'el el-brush',
	'fields' => array(
		array(
			'id'       => 'iteck_footer_enable_social',
			'type'     => 'switch',
			'title'    => esc_html__('Enable Footer Social', 'iteck'), 
			'default'  => true,
		), 
		array(
			'id'       => 'iteck_footer_facebook',
			'type'     => 'text',
			'title'    => esc_html__('Facebook Link', 'iteck'), 
			'subtitle' => esc_html__('Input facebook link here', 'iteck'),
			'required'  => array('iteck_footer_enable_social', 'equals',true),
		),  
		array(
			'id'       => 'iteck_footer_twitter',
			'type'     => 'text',
			'title'    => esc_html__('Twitter Link', 'iteck'), 
			'subtitle' => esc_html__('Input Twitter link here', 'iteck'),
			'required'  => array('iteck_footer_enable_social', 'equals',true),
		), 
		array(
			'id'       => 'iteck_footer_instagram',
			'type'     => 'text',
			'title'    => esc_html__('Instagram Link', 'iteck'), 
			'subtitle' => esc_html__('Input Instagram link here', 'iteck'),
			'required'  => array('iteck_footer_enable_social', 'equals',true),
		),  
		array(
			'id'       => 'iteck_footer_pinterest',
			'type'     => 'text',
			'title'    => esc_html__('Pinterest Link', 'iteck'), 
			'subtitle' => esc_html__('Input Pinterest link here', 'iteck'),
			'required'  => array('iteck_footer_enable_social', 'equals',true),
		), 
		array(
			'id'       => 'iteck_footer_xing',
			'type'     => 'text',
			'title'    => esc_html__('Xing Link', 'iteck'), 
			'subtitle' => esc_html__('Input Xing link here', 'iteck'),
			'required'  => array('iteck_footer_enable_social', 'equals',true),
		),  
		array(
			'id'       => 'iteck_footer_linkedin',
			'type'     => 'text',
			'title'    => esc_html__('LinkedIn Link', 'iteck'), 
			'subtitle' => esc_html__('Input LinkedIn link here', 'iteck'),
			'required'  => array('iteck_footer_enable_social', 'equals',true),
		),  
	)
));

?>