<?php
/**
 * Genaral Tab For Theme Option.
 *
 * @package iteck
 */

Redux::setSection($iteck_pre, array(
	'id' => 'general',
	'icon' => 'el el-home',
	'title' => esc_html__('General', 'iteck'),
	'desc' => esc_html__('Welcome to the theme options', 'iteck'),
));

$mode=false;
If ($mode==true):
	Redux::setSection($iteck_pre, array(
		'id' => 'iteck_mode',
		"subsection" => true,
		'title' => esc_html__('Iteck Mode', 'iteck'),
		'icon' => 'el el-brush',
		'fields' => array(
			array(
				'id'       => 'iteck_theme_mode',
				'type'     => 'button_set',
				'customizer' => true,
				'title'    => esc_html__('Website Dark/Light Mode', 'iteck'),
				'subtitle' => esc_html__('Enable dark color scheme for your website', 'iteck'),
				'desc' => esc_html__('Auto: Mode at the Operating System of each user', 'iteck'),
				'options' => array(
					'light_mode' => esc_html__( 'Light', 'iteck' ),
					'auto_mode' => esc_html__( 'Auto','iteck'),
					'dark_mode' => esc_html__( 'Dark','iteck'),
					),
				'default' => 'light_mode',
			),
			array(
				'id'       => 'iteck_mode_switcher',
				'type'     => 'button_set',
				'customizer' => true,
				'title'    => esc_html__('Color mode switcher', 'iteck'),
				'desc' => esc_html__('Enable color mode switcher for your website', 'iteck'),
				'options' => array(
					'on' => esc_html__( 'On', 'iteck' ),
					'off' => esc_html__( 'Off','iteck'),
					),
				'default' => 'off',
			),  
		),
	));
endif;

Redux::setSection($iteck_pre, array(
	'id' => 'style',
	"subsection" => true,
	'title' => esc_html__('Styling', 'iteck'),
	'desc' => esc_html__('Configuration the style settings', 'iteck'),
	'icon' => 'el el-brush',
	'fields' => array(
		array(
			'id'       => 'iteck_main_color', 
			'type'     => 'color',
			'title'    => esc_html__('Main Color Scheme', 'iteck'), 
			'subtitle' => esc_html__('Pick your color scheme (default: #501E9C).', 'iteck'),
			'default'  => '#501E9C',
			'validate' => 'color',
		),
		array(
			'id'       => 'iteck_primary_color', 
			'type'     => 'color',
			'title'    => esc_html__('primary Color Scheme', 'iteck'), 
			'subtitle' => esc_html__('Pick your color scheme (default: #8169F1).', 'iteck'),
			'default'  => '#8169F1',
			'validate' => 'color',
		), 
		array(
			'id'       => 'iteck_secondary_color',
			'type'     => 'color',
			'title'    => esc_html__('Secondary Color Scheme', 'iteck'), 
			'subtitle' => esc_html__('Pick your color scheme (default: #A44CEE).', 'iteck'),
			'default'  => '#A44CEE',
			'validate' => 'color',
		), 
		array(
			'id'       => 'iteck_ternary_color',
			'type'     => 'color',
			'title'    => esc_html__('Ternary Color Scheme', 'iteck'), 
			'subtitle' => esc_html__('Pick your color scheme (default: #FF847F).', 'iteck'),
			'default'  => '#FF847F',
			'validate' => 'color',
		), 
		array(
			'id'       => 'iteck_color_scheme',
			'type'     => 'color',
			'title'    => esc_html__('Hyperlink Color', 'iteck'), 
			'subtitle' => esc_html__('Pick your color for hyperlink. Default color is black #999999', 'iteck'),
			'default'  => '#999999',
			'validate' => 'color',
		), 
		array(
			'id'       => 'iteck_custom_hovers',
			'type'     => 'color',
			'title'    => esc_html__('Hyperlink color on hover state', 'iteck'), 
			'subtitle' => esc_html__('Pick your color for hover state in hyperlink. Default color is #12c2e9', 'iteck'),
			'default'  => '#12c2e9',
			'validate' => 'color',
		),  
		array(
			'id'       => 'iteck_heading_color',
			'type'     => 'color',
			'title'    => esc_html__('Color on Heading', 'iteck'), 
			'subtitle' => esc_html__('Pick your color for heading text. Default color is black #000000', 'iteck'),
			'default'  => '#000000',
			'validate' => 'color',
		), 
		array(
			'id'       => 'iteck_general_color',
			'type'     => 'color',
			'title'    => esc_html__('Color on General Paragraph', 'iteck'), 
			'subtitle' => esc_html__('Pick your color for general paragraph text. Default color is black #666', 'iteck'),
			'default'  => '#666666',
			'validate' => 'color', 
		), 
		array(
			'id'       => 'iteck_stick_menu',
			'type'     => 'color',
			'title'    => esc_html__('Sticky Menu Background color (for menu with black background & All Sticky Custom Menu)', 'iteck'), 
			'subtitle' => esc_html__('Pick your background color for sticky menu in white text header. Default color is #fff', 'iteck'),
			'default'  => '#ffffff',
			'validate' => 'color',
		), 
		array(
			'id'       => 'iteck_stick_menu2',
			'type'     => 'color',
			'title'    => esc_html__('Sticky Menu Background color (for menu with white background)', 'iteck'), 
			'subtitle' => esc_html__('Pick your background color for sticky menu in white text header. Default color is #ffffff', 'iteck'),
			'default'  => '#ffffff',
			'validate' => 'color',
		), 
		 array(
			'id'       => 'iteck_menu_border',
			'type'     => 'color',
			'title'    => esc_html__('Sticky Menu BBorder color (for menu with transparent background)', 'iteck'), 
			'subtitle' => esc_html__('Pick your border color for sticky menu in transparent text header. Default color is #ffffff', 'iteck'),
			'default'  => '#ffffff',
			'validate' => 'color',
		),
		array(
			'id'       => 'iteck_footer_color',
			'type'     => 'color',
			'title'    => esc_html__('Standard Footer Background color', 'iteck'), 
			'subtitle' => esc_html__('Pick your background color for standard footer. Default color is black #202020', 'iteck'),
			'default'  => '#13161D',
			'validate' => 'color',
		),
		array(
			'id'       => 'iteck_footer_color',
			'type'     => 'color',
			'title'    => esc_html__('Standard Footer Background color', 'iteck'), 
			'subtitle' => esc_html__('Pick your background color for standard footer. Default color is black #202020', 'iteck'),
			'default'  => '#13161D',
			'validate' => 'color',
		),
	),
));

Redux::setSection($iteck_pre, array(
	'id' => 'preloader',
	"subsection" => true,
	'title' => esc_html__('Preloader', 'iteck'),
	'desc' => esc_html__('Configuration the style settings', 'iteck'),
	'icon' => 'el el-brush',
	'fields' => array(
		array(
			'id'       => 'iteck_preloader_set',
			'type'     => 'button_set',
			'title'    => esc_html__('Preloader Setting', 'iteck'),
			'options' => array(
					'show_all' => esc_html__('Show in All pages', 'iteck'),
					'show_home' => esc_html__('Show in Home page only', 'iteck'),
					'not_show' => esc_html__('Hide in all pages', 'iteck'),
				),
		),
		array(
			'id'       => 'iteck_preloader_type',
			'type'     => 'button_set',
			'title'    => esc_html__('Preloader Type', 'iteck'),
			'options' => array(
					'circle' => esc_html__('Circle', 'iteck'),
					'progress' => esc_html__('Progress', 'iteck'),
				),
			'default'  => 'progress',
		),

		array(
			'id'       => 'iteck_loader_color',
			'type'     => 'color',
			'title'    => esc_html__('Color Scheme', 'iteck'), 
			'subtitle' => esc_html__('Pick your color scheme (default: #12c2e9).', 'iteck'),
			'default'  => '#12c2e9',
			'validate' => 'color',
		),       
	),
)); 

Redux::setSection($iteck_pre, array(
	'id' => 'cursor',
	"subsection" => true,
	'title' => esc_html__('Cursor', 'iteck'),
	'desc' => esc_html__('Select your cursor type', 'iteck'),
	'icon' => 'el el-brush',
	'fields' => array(
		array(
			'id'       => 'iteck_cursor_set',
			'type'     => 'button_set',
			'customizer' => true,
			'title'    => esc_html__('Theme Cursor Type', 'iteck'),
			'options' => array(
					'none' => esc_html__('None', 'iteck'),
					'1' => esc_html__('Style 1', 'iteck'),
				),
			'default' => 'none',
		),      
	),
));

Redux::setSection($iteck_pre, array(
	"subsection" => true,
	'title'  => esc_html__( 'Typography', 'iteck' ),
	'icon' => 'el el-fontsize',
	'fields' => array(

		array(
			'title' => esc_html__( 'Body', 'iteck' ),
			'subtitle' => esc_html__("Choose Size and Style for Body", 'iteck' ),
			'id' => 'font_body',
			'type' => 'typography',
			'font-backup' => false,
			'letter-spacing' => true,
			'text-transform' => true,
			'all_styles' => true,
			'output' => array( 'body' ),
			'default' => array(
				'font-family' =>'',
				'color' =>"",
				'font-style' =>'',
				'font-size' =>'',
				'line-height' =>''
			),
		),

        array(
            'title' => esc_html__( 'Paragraph', 'iteck' ),
            'subtitle' => esc_html__("Choose Size and Style for paragraph", 'iteck' ),
            'id' => 'font_p',
            'type' => 'typography',
            'font-backup' => false,
            'letter-spacing' => true,
            'text-transform' => true,
            'all_styles' => true,
            'output' => array( 'p, body.has-paragraph-style p' ),
            'default' => array(
                'font-family' =>'',
                'color' =>"",
                'font-style' =>'',
                'font-size' =>'',
                'line-height' =>''
            ),
        ),

        array(
            'title' => esc_html__( 'H1 Headings', 'iteck' ),
            'subtitle' => esc_html__("Choose Size and Style for h1", 'iteck' ),
            'id' => 'font_h1',
            'type' => 'typography',
            'font-backup' => false,
            'letter-spacing' => true,
            'text-transform' => true,
            'all_styles' => true,
            'output' => array( 'h1' ),
            'default' => array(
                'color' => '',
                'font-style' => '',
                'font-family' => '',
                'google' => true,
                'font-size' => '',
                'line-height' => ''
            ),
        ),
        array(
            'title' => esc_html__( 'H2 Headings', 'iteck' ),
            'subtitle' => esc_html__("Choose Size and Style for h2", 'iteck' ),
            'id' => 'font_h2',
            'type' => 'typography',
            'font-backup' => false,
            'letter-spacing' => true,
            'text-transform' => true,
            'all_styles' => true,
            'output' => array( 'h2' ),
            'default' => array(
                'color' => '',
                'font-style' => '',
                'font-family' => '',
                'google' => true,
                'font-size' => '',
                'line-height' => ''
            ),
        ),
        array(
            'title' => esc_html__( 'H3 Headings', 'iteck' ),
            'subtitle' => esc_html__("Choose Size and Style for h3", 'iteck' ),
            'id' => 'font_h3',
            'type' => 'typography',
            'font-backup' => false,
            'letter-spacing' => true,
            'text-transform' => true,
            'all_styles' => true,
            'output' => array( 'h3' ),
            'default' => array(
                'color' => '',
                'font-style' => '',
                'font-family' => '',
                'google' => true,
                'font-size' => '',
                'line-height' => ''
            ),
        ),
        array(
            'title' => esc_html__( 'H4 Headings', 'iteck' ),
            'subtitle' => esc_html__("Choose Size and Style for h4", 'iteck' ),
            'id' => 'font_h4',
            'type' => 'typography',
            'font-backup' => false,
            'letter-spacing' => true,
            'text-transform' => true,
            'all_styles' => true,
            'output' => array( 'h4' ),
            'default' => array(
                'color' => '',
                'font-style' => '',
                'font-family' => '',
                'google' => true,
                'font-size' => '',
                'line-height' => ''
            ),
        ),
        array(
            'title' => esc_html__( 'H5 Headings', 'iteck' ),
            'subtitle' => esc_html__("Choose Size and Style for h5", 'iteck' ),
            'id' => 'font_h5',
            'type' => 'typography',
            'font-backup' => false,
            'letter-spacing' => true,
            'text-transform' => true,
            'all_styles' => true,
            'output' => array( 'h5' ),
            'default' => array(
                'color' => '',
                'font-style' => '',
                'font-family' => '',
                'google' => true,
                'font-size' => '',
                'line-height' => ''
            ),
        ),
        array(
            'title' => esc_html__( 'H6 Headings', 'iteck' ),
            'subtitle' => esc_html__("Choose Size and Style for h6", 'iteck' ),
            'id' => 'font_h6',
            'type' => 'typography',
            'font-backup' => false,
            'letter-spacing' => true,
            'text-transform' => true,
            'all_styles' => true,
            'output' => array( 'h6' ),
            'units' => 'px',
            'default' => array(
                'color' => '',
                'font-style' => '',
                'font-family' => '',
                'google' => true,
                'font-size' => '',
                'line-height' => ''
            ),
        ),
		
	),

));

?>