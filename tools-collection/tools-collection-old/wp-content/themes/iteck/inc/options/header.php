<?php
/**
 * Header Tab For Theme Option.
 *
 * @package iteck
 */

Redux::setSection($iteck_pre, array(
	'title'  => esc_html__( 'Header', 'iteck' ),
	'icon' => 'el el-credit-card',
));

Redux::setSection($iteck_pre, array(
	"subsection" => true,
	'title' => esc_html__('Header settings', 'iteck'),
	'desc' => esc_html__('Assign menu for header section.', 'iteck'),
	'icon' => 'el el-brush',
	'fields' => [
		[
			'id'       => 'iteck_header_set', 
			'type'     => 'select',
			'title'    => esc_html__('Header type', 'iteck'),
			'options' => array(
				'default' => esc_html__('Standard Header', 'iteck'),
				'custom' => esc_html__('Custom Header', 'iteck'),
				'no_header' => esc_html__( 'No Header', 'iteck' )
			),
			'default'     => 'default',
		],

		[
			'id'    => 'iteck_header_set_list',
			'type'  => 'select',
			'desc' => esc_html__('(Only if custom header selected as header type))', 'iteck'),
			'title'    => esc_html__('Custom Header format', 'iteck'),
			'data'  => 'posts',
			'args'  => array(
				'post_type' => 'header',
				'orderby'   => 'title',
				'order'     => 'ASC',
			),
			'required' => ['iteck_header_set', '=', 'custom'],
		],  

		[
			'id'       => 'iteck_header_position',
			'type'     => 'select',
			'title'    => esc_html__('Header Position', 'iteck'), 
			'options' => array(
				'head_white' => esc_html__( 'Relative Position with Background, ', 'iteck' ),
				'head_trans' => esc_html__( 'Absolute Position, Transperant','iteck'),
			), 
			'default'  => 'head_white',
			
		],

		[
			'id'       => 'iteck_menu_position',
			'type'     => 'select',
			'title'    => esc_html__('Menu Position', 'iteck'), 
			'options' => array(
				'right' => esc_html__('Right', 'iteck'),
				'center' => esc_html__('Center', 'iteck'),
			), 
			'default'  => 'right',
		],

	]
));

Redux::setSection($iteck_pre, array(
	'id' => 'header_logo',
	"subsection" => true,
	'title' => esc_html__('Header logo', 'iteck'),
	'desc' => esc_html__('Configuration the style settings', 'iteck'),
	'icon' => 'el el-brush',
	'fields' => array(
		array(
			'id'       => 'iteck_header_logo_white',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__('Logo White Text', 'iteck'), 
			'subtitle' => esc_html__('Upload your logo for white text (standard) header (Recommended size 240x80px)', 'iteck'),
			'default'  => array('url'=>get_template_directory_uri().'/assets/images/logo-white.png'),
		), 
		array(
			'id'       => 'iteck_header_logo_dark',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__('Logo Dark Text', 'iteck'), 
			'subtitle' => esc_html__('Upload your logo for dark text (standard) header (Recommended size 240x80px)', 'iteck'),
			'default'  => array('url'=>get_template_directory_uri().'/assets/images/logo-dark.png'),
		),
		array(
			'id'       => 'iteck_logo_dim',
			'type'     => 'dimensions',
			'height' => true,
             'width' => false,
			'units'    => array('em','px','%'),
			'title'    => esc_html__('Logo dimensions Height', 'iteck'), 
			'subtitle' => esc_html__('Enable or disable any piece of this field. Width, Height, or Units.)', 'iteck'),
			'default' => ['height' => 25], 
		), 
     
	)
));

Redux::setSection($iteck_pre, array(
	'id' => 'iteck_header_social',
	"subsection" => true,
	'title' => esc_html__('Header social', 'iteck'),
	'desc' => esc_html__('Configuration the header social icons', 'iteck'),
	'icon' => 'el el-brush',
	'fields' => array( 
		array(
			'id'       => 'iteck_header_enable_topmenu',
			'type'     => 'select',
			'title'    => esc_html__('Enable Top Menu', 'iteck'),
			'options' => array(
				'on' => esc_html__('On', 'iteck'),
				'off' => esc_html__('Off', 'iteck'),
			), 
			'default'  => 'off',
		), 
		array(
			'id'       => 'iteck_header_phone',
			'type'     => 'text',
			'title'    => esc_html__('Phone', 'iteck'), 
			'subtitle' => esc_html__('Input phone number', 'iteck'),
			'required'  => array('iteck_header_enable_topmenu', 'equals','on'),
		),
		array(
			'id'       => 'iteck_header_mail',
			'type'     => 'text',
			'title'    => esc_html__('Mail', 'iteck'), 
			'subtitle' => esc_html__('Input mail address', 'iteck'),
			'required'  => array('iteck_header_enable_topmenu', 'equals','on'),
		),
		array(
			'id'       => 'iteck_header_address',
			'type'     => 'text',
			'title'    => esc_html__('Address', 'iteck'), 
			'subtitle' => esc_html__('Input address', 'iteck'),
			'required'  => array('iteck_header_enable_topmenu', 'equals','on'),
		),
		array(
			'id'       => 'iteck_header_join',
			'type'     => 'text',
			'title'    => esc_html__('Join', 'iteck'), 
			'subtitle' => esc_html__('Input Join text', 'iteck'),
			'required'  => array('iteck_header_enable_topmenu', 'equals','on'),
		),
		array(
			'id'       => 'iteck_header_joinlink',
			'type'     => 'text',
			'title'    => esc_html__('Join', 'iteck'), 
			'subtitle' => esc_html__('Input Join link', 'iteck'),
			'required'  => array('iteck_header_enable_topmenu', 'equals','on'),
		),
		array(
			'id'       => 'iteck_header_enable_social',
			'type'     => 'select',
			'title'    => esc_html__('Enable Header Social', 'iteck'),
			'options' => array(
				'on' => esc_html__('On', 'iteck'),
				'off' => esc_html__('Off', 'iteck'),
			), 
			'default'  => 'off',
		), 
		array(
			'id'       => 'iteck_header_social_new_tab',
			'type'     => 'select',
			'title'    => esc_html__('Enable Header Social New Tab', 'iteck'),
			'options' => array(
				'on' => esc_html__('On', 'iteck'),
				'off' => esc_html__('Off', 'iteck'),
			), 
			'default'  => 'off',
			'required'  => array('iteck_header_enable_social', 'equals','on'),
		), 
		array(
			'id'       => 'iteck_header_facebook',
			'type'     => 'text',
			'title'    => esc_html__('Facebook Link', 'iteck'), 
			'subtitle' => esc_html__('Input facebook link here', 'iteck'),
			'required'  => array('iteck_header_enable_social', 'equals','on'),
		),  
		array(
			'id'       => 'iteck_header_twitter',
			'type'     => 'text',
			'title'    => esc_html__('Twitter Link', 'iteck'), 
			'subtitle' => esc_html__('Input Twitter link here', 'iteck'),
			'required'  => array('iteck_header_enable_social', 'equals','on'),
		), 
		array(
			'id'       => 'iteck_header_instagram',
			'type'     => 'text',
			'title'    => esc_html__('Instagram Link', 'iteck'), 
			'subtitle' => esc_html__('Input Instagram link here', 'iteck'),
			'required'  => array('iteck_header_enable_social', 'equals','on'),
		),  
		array(
			'id'       => 'iteck_header_pinterest',
			'type'     => 'text',
			'title'    => esc_html__('Pinterest Link', 'iteck'), 
			'subtitle' => esc_html__('Input Pinterest link here', 'iteck'),
			'required'  => array('iteck_header_enable_social', 'equals','on'),
		), 
		array(
			'id'       => 'iteck_header_xing',
			'type'     => 'text',
			'title'    => esc_html__('Xing Link', 'iteck'), 
			'subtitle' => esc_html__('Input Xing link here', 'iteck'),
			'required'  => array('iteck_header_enable_social', 'equals','on'),
		),  
		array(
			'id'       => 'iteck_header_linkedin',
			'type'     => 'text',
			'title'    => esc_html__('LinkedIn Link', 'iteck'), 
			'subtitle' => esc_html__('Input LinkedIn link here', 'iteck'),
			'required'  => array('iteck_header_enable_social', 'equals','on'),
		),   
		array(
			'id'       => 'iteck_header_youtube',
			'type'     => 'text',
			'title'    => esc_html__('Youtube Link', 'iteck'), 
			'subtitle' => esc_html__('Input Youtube link here', 'iteck'),
			'required'  => array('iteck_header_enable_social', 'equals','on'),
		),  
		array(
			'id'       => 'iteck_header_search',
			'type'     => 'select',
			'title'    => esc_html__('Search Icon', 'iteck'), 
			'subtitle' => esc_html__('To show search icon in header', 'iteck'),
			'options' => array(
				'on' => esc_html__('On', 'iteck'),
				'off' => esc_html__('Off', 'iteck'),
			), 
			'default'  => 'off',
		),  
		array(
			'id'       => 'iteck_header_btn',
			'type'     => 'select',
			'title'    => esc_html__('Button', 'iteck'), 
			'subtitle' => esc_html__('To show Button in header', 'iteck'),
			'options' => array(
				'on' => esc_html__('On', 'iteck'),
				'off' => esc_html__('Off', 'iteck'),
			), 
			'default'  => 'off',
		), 
		array(
			'id'       => 'iteck_menu_btn',
			'type'     => 'text',
			'title'    => esc_html__('Button Text', 'iteck'), 
			'required'  => array('iteck_header_btn', 'equals','on'),
		),
		array(
			'id'       => 'iteck_menu_btn_url',
			'type'     => 'text',
			'title'    => esc_html__('Button URL', 'iteck'),
			'required'  => array('iteck_header_btn', 'equals','on'), 
		),
	)
));

?>