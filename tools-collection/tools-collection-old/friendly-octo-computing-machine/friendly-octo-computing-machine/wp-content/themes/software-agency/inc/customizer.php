<?php
/**
 * Software Agency Theme Customizer
 *
 * @package Software Agency
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

function software_agency_custom_controls() {
	load_template( trailingslashit( get_template_directory() ) . '/inc/custom-controls.php' );
}
add_action( 'customize_register', 'software_agency_custom_controls' );

function software_agency_customize_register( $wp_customize ) {

	load_template( trailingslashit( get_template_directory() ) . '/inc/icon-picker.php' );

	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector' => '.logo .site-title a',
	 	'render_callback' => 'software_agency_customize_partial_blogname',
	));

	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector' => 'p.site-description',
		'render_callback' => 'software_agency_customize_partial_blogdescription',
	));

	//add home page setting pannel
	$software_agency_parent_panel = new Software_Agency_WP_Customize_Panel( $wp_customize, 'software_agency_panel_id', array(
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title' => esc_html__( 'Homepage Settings', 'software-agency' ),
		'priority' => 10,
	));

	//Top Header
	$wp_customize->add_section( 'software_agency_top_header' , array(
    	'title' => esc_html__( 'Top Header', 'software-agency' ),
		'panel' => 'software_agency_panel_id'
	) );

   	// Header Background color
	$wp_customize->add_setting('software_agency_header_background_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'software_agency_header_background_color', array(
		'label'    => __('header Background Color', 'software-agency'),
		'section'  => 'header_image',
	))); 

	$wp_customize->add_setting('software_agency_header_img_position',array(
	  'default' => 'center top',
	  'transport' => 'refresh',
	  'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_header_img_position',array(
		'type' => 'select',
		'label' => __('Header Image Position','software-agency'),
		'section' => 'header_image',
		'choices' 	=> array(
			'left top' 		=> esc_html__( 'Top Left', 'software-agency' ),
			'center top'   => esc_html__( 'Top', 'software-agency' ),
			'right top'   => esc_html__( 'Top Right', 'software-agency' ),
			'left center'   => esc_html__( 'Left', 'software-agency' ),
			'center center'   => esc_html__( 'Center', 'software-agency' ),
			'right center'   => esc_html__( 'Right', 'software-agency' ),
			'left bottom'   => esc_html__( 'Bottom Left', 'software-agency' ),
			'center bottom'   => esc_html__( 'Bottom', 'software-agency' ),
			'right bottom'   => esc_html__( 'Bottom Right', 'software-agency' ),
		),
	));

	//Sticky Header
	$wp_customize->add_setting( 'software_agency_sticky_header',array(
	    'default' => 0,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'software_agency_switch_sanitization'
 	) );
  	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_sticky_header',array(
	    'label' => esc_html__( 'Sticky Header','software-agency' ),
	    'section' => 'software_agency_top_header'
  	)));

	$wp_customize->add_setting('software_agency_top_bar_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_top_bar_text',array(
		'label'	=> esc_html__('Anouncement Text','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'Lorem ipsum dolor sit amet ipsum dolor sit amet.', 'software-agency' ),
        ),
		'section'=> 'software_agency_top_header',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_support_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_support_text',array(
		'label'	=> esc_html__('Support Text','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'Support', 'software-agency' ),
        ),
		'section'=> 'software_agency_top_header',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_support_link',array(
		'default'=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('software_agency_support_link',array(
		'label'	=> esc_html__('Support Link','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'https://www.example.com/support', 'software-agency' ),
        ),
		'section'=> 'software_agency_top_header',
		'type'=> 'url'
	));

	$wp_customize->add_setting('software_agency_wishlist_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_wishlist_text',array(
		'label'	=> esc_html__('Wishlist Text','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'Wishlist', 'software-agency' ),
        ),
		'section'=> 'software_agency_top_header',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_wishlist_link',array(
		'default'=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('software_agency_wishlist_link',array(
		'label'	=> esc_html__('Wishlist Link','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'https://www.example.com/wishlist', 'software-agency' ),
        ),
		'section'=> 'software_agency_top_header',
		'type'=> 'url'
	));

	$wp_customize->add_setting('software_agency_location_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_location_text',array(
		'label'	=> esc_html__('Location Text','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'Our Address', 'software-agency' ),
        ),
		'section'=> 'software_agency_top_header',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_phone_number_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_phone_number_text',array(
		'label'	=> esc_html__('Phone Number Text','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'Reach Us', 'software-agency' ),
        ),
		'section'=> 'software_agency_top_header',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_phone_number',array(
		'default'=> '',
		'sanitize_callback'	=> 'software_agency_sanitize_phone_number'
	));
	$wp_customize->add_control('software_agency_phone_number',array(
		'label'	=> esc_html__('Phone Number','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( '+91 123 456 7890', 'software-agency' ),
        ),
		'section'=> 'software_agency_top_header',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_email_address_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_email_address_text',array(
		'label'	=> esc_html__('Email Address Text','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'Email Us At', 'software-agency' ),
        ),
		'section'=> 'software_agency_top_header',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_email_address',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_email'
	));
	$wp_customize->add_control('software_agency_email_address',array(
		'label'	=> esc_html__('Email Address','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'example@support.com', 'software-agency' ),
        ),
		'section'=> 'software_agency_top_header',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_get_started_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_get_started_text',array(
		'label'	=> esc_html__('Button Text','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'Get Started', 'software-agency' ),
        ),
		'section'=> 'software_agency_top_header',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_get_started_link',array(
		'default'=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('software_agency_get_started_link',array(
		'label'	=> esc_html__('Button Link','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'https://www.example.com/get-started', 'software-agency' ),
        ),
		'section'=> 'software_agency_top_header',
		'type'=> 'url'
	));

	//Menus Settings
	$wp_customize->add_section( 'software_agency_menu_section' , array(
    	'title' => __( 'Menus Settings', 'software-agency' ),
		'panel' => 'software_agency_panel_id'
	) );

	$wp_customize->add_setting('software_agency_navigation_menu_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_navigation_menu_font_size',array(
		'label'	=> __('Menus Font Size','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( '10px', 'software-agency' ),
        ),
		'section'=> 'software_agency_menu_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_navigation_menu_font_weight',array(
        'default' => 600,
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_navigation_menu_font_weight',array(
        'type' => 'select',
        'label' => __('Menus Font Weight','software-agency'),
        'section' => 'software_agency_menu_section',
        'choices' => array(
        	'100' => __('100','software-agency'),
            '200' => __('200','software-agency'),
            '300' => __('300','software-agency'),
            '400' => __('400','software-agency'),
            '500' => __('500','software-agency'),
            '600' => __('600','software-agency'),
            '700' => __('700','software-agency'),
            '800' => __('800','software-agency'),
            '900' => __('900','software-agency'),
        ),
	) );

	// text trasform
	$wp_customize->add_setting('software_agency_menu_text_transform',array(
		'default'=> 'Capitalize',
		'sanitize_callback'	=> 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_menu_text_transform',array(
		'type' => 'radio',
		'label'	=> __('Menus Text Transform','software-agency'),
		'choices' => array(
            'Uppercase' => __('Uppercase','software-agency'),
            'Capitalize' => __('Capitalize','software-agency'),
            'Lowercase' => __('Lowercase','software-agency'),
        ),
		'section'=> 'software_agency_menu_section',
	));

	$wp_customize->add_setting('software_agency_menus_item_style',array(
        'default' => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_menus_item_style',array(
        'type' => 'select',
        'section' => 'software_agency_menu_section',
		'label' => __('Menu Item Hover Style','software-agency'),
		'choices' => array(
            'None' => __('None','software-agency'),
            'Zoom In' => __('Zoom In','software-agency'),
        ),
	) );

	$wp_customize->add_setting('software_agency_header_menus_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'software_agency_header_menus_color', array(
		'label'    => __('Menus Color', 'software-agency'),
		'section'  => 'software_agency_menu_section',
	)));

	$wp_customize->add_setting('software_agency_header_menus_hover_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'software_agency_header_menus_hover_color', array(
		'label'    => __('Menus Hover Color', 'software-agency'),
		'section'  => 'software_agency_menu_section',
	)));

	$wp_customize->add_setting('software_agency_header_submenus_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'software_agency_header_submenus_color', array(
		'label'    => __('Sub Menus Color', 'software-agency'),
		'section'  => 'software_agency_menu_section',
	)));

	$wp_customize->add_setting('software_agency_header_submenus_hover_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'software_agency_header_submenus_hover_color', array(
		'label'    => __('Sub Menus Hover Color', 'software-agency'),
		'section'  => 'software_agency_menu_section',
	)));

	//Social links
	$wp_customize->add_section(
		'software_agency_social_links', array(
		'title'		=>	__('Social Links', 'software-agency'),
		'priority'	=>	null,
		'panel'		=>	'software_agency_panel_id'
	));

	$wp_customize->add_setting('software_agency_social_icons',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_social_icons',array(
		'label' =>  __('Steps to setup social icons','software-agency'),
		'description' => __('<p>1. Go to Dashboard >> Appearance >> Widgets</p>
			<p>2. Add Vw Social Icon Widget in Social Media.</p>
			<p>3. Add social icons url and save.</p>','software-agency'),
		'section'=> 'software_agency_social_links',
		'type'=> 'hidden'
	));
	$wp_customize->add_setting('software_agency_social_icon_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_social_icon_btn',array(
		'description' => "<a target='_blank' href='". admin_url('widgets.php') ." '>Setup Social Icons</a>",
		'section'=> 'software_agency_social_links',
		'type'=> 'hidden'
	));

	//Slider
	$wp_customize->add_section( 'software_agency_slidersettings' , array(
    	'title' => esc_html__( 'Slider Settings', 'software-agency' ),
	  	'description' => __('Free theme has 3 slides options, For unlimited slides and more options </br> <a class="go-pro-btn" target="blank" href="https://www.vwthemes.com/products/software-company-wordpress-theme">GET PRO</a>','software-agency'),
		'panel' => 'software_agency_panel_id'
	) );

    //Selective Refresh
    $wp_customize->selective_refresh->add_partial('software_agency_slider_arrows',array(
		'selector'        => '#slider .carousel-caption h1',
		'render_callback' => 'software_agency_customize_partial_software_agency_slider_arrows',
	));

	$wp_customize->add_setting( 'software_agency_slider_arrows',array(
    	'default' => 1,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'software_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_slider_arrows',array(
      	'label' => esc_html__( 'Show / Hide Slider','software-agency' ),
      	'section' => 'software_agency_slidersettings'
    )));

    $wp_customize->add_setting('software_agency_slider_type',array(
        'default' => 'Default slider',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	) );
	$wp_customize->add_control('software_agency_slider_type', array(
        'type' => 'select',
        'label' => __('Slider Type','software-agency'),
        'section' => 'software_agency_slidersettings',
        'choices' => array(
            'Default slider' => __('Default slider','software-agency'),
            'Advance slider' => __('Advance slider','software-agency'),
        ),
	));

	$wp_customize->add_setting('software_agency_advance_slider_shortcode',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_advance_slider_shortcode',array(
		'label'	=> __('Add Slider Shortcode','software-agency'),
		'section'=> 'software_agency_slidersettings',
		'type'=> 'text',
		'active_callback' => 'software_agency_advance_slider'
	));

	for ( $count = 1; $count <= 3; $count++ ) {
		$wp_customize->add_setting( 'software_agency_slider_page' . $count, array(
			'default'  => '',
			'sanitize_callback' => 'software_agency_sanitize_dropdown_pages'
		) );
		$wp_customize->add_control( 'software_agency_slider_page' . $count, array(
			'label'    => esc_html__( 'Select Slider Page', 'software-agency' ),
			'description' => esc_html__('Slider image size (1400 x 550)','software-agency'),
			'section'  => 'software_agency_slidersettings',
			'type'     => 'dropdown-pages',
			'active_callback' => 'software_agency_default_slider'
		) );
	}

	$wp_customize->add_setting( 'software_agency_slider_title_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_slider_title_hide_show',array(
		'label' => esc_html__( 'Show / Hide Slider Title','software-agency' ),
		'section' => 'software_agency_slidersettings',
		'active_callback' => 'software_agency_default_slider'
    )));

	//content layout
	$wp_customize->add_setting('software_agency_slider_content_option',array(
        'default' => 'Left',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control(new Software_Agency_Image_Radio_Control($wp_customize, 'software_agency_slider_content_option', array(
        'type' => 'select',
        'label' => esc_html__('Slider Content Layouts','software-agency'),
        'section' => 'software_agency_slidersettings',
        'choices' => array(
            'Left' => esc_url(get_template_directory_uri()).'/assets/images/slider-content1.png',
            'Center' => esc_url(get_template_directory_uri()).'/assets/images/slider-content2.png',
            'Right' => esc_url(get_template_directory_uri()).'/assets/images/slider-content3.png',
    ),'active_callback' => 'software_agency_default_slider'
    )));

    //Slider content padding
    $wp_customize->add_setting('software_agency_slider_content_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_slider_content_padding_top_bottom',array(
		'label'	=> __('Slider Content Padding Top Bottom','software-agency'),
		'description'	=> __('Enter a value in %. Example:20%','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( '50%', 'software-agency' ),
        ),
		'section'=> 'software_agency_slidersettings',
		'type'=> 'text',
		'active_callback' => 'software_agency_default_slider'
	));

	$wp_customize->add_setting('software_agency_slider_content_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_slider_content_padding_left_right',array(
		'label'	=> __('Slider Content Padding Left Right','software-agency'),
		'description'	=> __('Enter a value in %. Example:20%','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( '50%', 'software-agency' ),
        ),
		'section'=> 'software_agency_slidersettings',
		'type'=> 'text',
		'active_callback' => 'software_agency_default_slider'
	));

    //Slider excerpt
	$wp_customize->add_setting( 'software_agency_slider_excerpt_number', array(
		'default'              => 25,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'software_agency_sanitize_number_range',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'software_agency_slider_excerpt_number', array(
		'label'       => esc_html__( 'Slider Excerpt length','software-agency' ),
		'section'     => 'software_agency_slidersettings',
		'type'        => 'range',
		'settings'    => 'software_agency_slider_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),'active_callback' => 'software_agency_default_slider'
	) );

	//About Section
	$wp_customize->add_section('software_agency_about', array(
		'title'       => __('About Section', 'software-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','software-agency'),
		'priority'    => null,
		'panel'       => 'software_agency_panel_id',
	));

	$wp_customize->add_setting('software_agency_about_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_about_text',array(
		'description' => __('<p>1. More options for about section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for about section.</p>','software-agency'),
		'section'=> 'software_agency_about',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('software_agency_about_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_about_btn',array(
		'description' => "<a class='go-pro' target='_blank' href=".esc_url(SOFTWARE_AGENCY_BUY_NOW).">More Info</a>",

		'section'=> 'software_agency_about',
		'type'=> 'hidden'
	));

	//Slider height
	$wp_customize->add_setting('software_agency_slider_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_slider_height',array(
		'label'	=> __('Slider Height','software-agency'),
		'description'	=> __('Specify the slider height (px).','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( '500px', 'software-agency' ),
        ),
		'section'=> 'software_agency_slidersettings',
		'type'=> 'text',
		'active_callback' => 'software_agency_default_slider'
	));

	$wp_customize->add_setting( 'software_agency_slider_speed', array(
		'default'  => 4000,
		'sanitize_callback'	=> 'software_agency_sanitize_float'
	) );
	$wp_customize->add_control( 'software_agency_slider_speed', array(
		'label' => esc_html__('Slider Transition Speed','software-agency'),
		'section' => 'software_agency_slidersettings',
		'type'  => 'number',
		'active_callback' => 'software_agency_default_slider'
	) );

	//Product Section
	$wp_customize->add_section('software_agency_product_section',array(
		'title'	=> esc_html__('BestSelling Product Section','software-agency'),
		'description' => __('For more options of the BestSelling Product Section</br> <a class="go-pro-btn" target="blank" href="https://www.vwthemes.com/products/software-company-wordpress-theme">GET PRO</a>','software-agency'),
		'panel' => 'software_agency_panel_id',
	));

	$wp_customize->add_setting( 'software_agency_product_settings' , array(
		'default' => '',
		'sanitize_callback' => 'software_agency_sanitize_dropdown_pages'
	) );
	$wp_customize->add_control( 'software_agency_product_settings' , array(
		'label'    => esc_html__( 'Select Produt Page', 'software-agency' ),
		'section'  => 'software_agency_product_section',
		'type'     => 'dropdown-pages'
	) );

	//Our App Section
	$wp_customize->add_section('software_agency_our_app', array(
		'title'       => __('Our App Section', 'software-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','software-agency'),
		'priority'    => null,
		'panel'       => 'software_agency_panel_id',
	));

	$wp_customize->add_setting('software_agency_our_app_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_our_app_text',array(
		'description' => __('<p>1. More options for our app section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for our app section.</p>','software-agency'),
		'section'=> 'software_agency_our_app',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('software_agency_our_app_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_our_app_btn',array(
		'description' => "<a class='go-pro' target='_blank' href=".esc_url(SOFTWARE_AGENCY_BUY_NOW).">More Info</a>",

		'section'=> 'software_agency_our_app',
		'type'=> 'hidden'
	));

	//services Section
	$wp_customize->add_section('software_agency_services', array(
		'title'       => __('Services Section', 'software-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','software-agency'),
		'priority'    => null,
		'panel'       => 'software_agency_panel_id',
	));

	$wp_customize->add_setting('software_agency_services_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_services_text',array(
		'description' => __('<p>1. More options for services section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for services section.</p>','software-agency'),
		'section'=> 'software_agency_services',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('software_agency_services_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_services_btn',array(
		'description' => "<a class='go-pro' target='_blank' href=".esc_url(SOFTWARE_AGENCY_BUY_NOW).">More Info</a>",

		'section'=> 'software_agency_services',
		'type'=> 'hidden'
	));

	//Interface Designing Section
	$wp_customize->add_section('software_agency_interface_designing', array(
		'title'       => __('Interface Designing Section', 'software-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','software-agency'),
		'priority'    => null,
		'panel'       => 'software_agency_panel_id',
	));

	$wp_customize->add_setting('software_agency_interface_designing_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_interface_designing_text',array(
		'description' => __('<p>1. More options for interface designing section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for interface designing section.</p>','software-agency'),
		'section'=> 'software_agency_interface_designing',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('software_agency_interface_designing_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_interface_designing_btn',array(
		'description' => "<a class='go-pro' target='_blank' href=".esc_url(SOFTWARE_AGENCY_BUY_NOW).">More Info</a>",

		'section'=> 'software_agency_interface_designing',
		'type'=> 'hidden'
	));

	//Introduction Section
	$wp_customize->add_section('software_agency_introduction', array(
		'title'       => __('Introduction Section', 'software-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','software-agency'),
		'priority'    => null,
		'panel'       => 'software_agency_panel_id',
	));

	$wp_customize->add_setting('software_agency_introduction_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_introduction_text',array(
		'description' => __('<p>1. More options for introduction section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for introduction section.</p>','software-agency'),
		'section'=> 'software_agency_introduction',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('software_agency_introduction_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_introduction_btn',array(
		'description' => "<a class='go-pro' target='_blank' href=".esc_url(SOFTWARE_AGENCY_BUY_NOW).">More Info</a>",

		'section'=> 'software_agency_introduction',
		'type'=> 'hidden'
	));

	//Newsletter Section
	$wp_customize->add_section('software_agency_newsletter', array(
		'title'       => __('Newsletter Section', 'software-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','software-agency'),
		'priority'    => null,
		'panel'       => 'software_agency_panel_id',
	));

	$wp_customize->add_setting('software_agency_newsletter_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_newsletter_text',array(
		'description' => __('<p>1. More options for newsletter section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for newsletter section.</p>','software-agency'),
		'section'=> 'software_agency_newsletter',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('software_agency_newsletter_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_newsletter_btn',array(
		'description' => "<a class='go-pro' target='_blank' href=".esc_url(SOFTWARE_AGENCY_BUY_NOW).">More Info</a>",

		'section'=> 'software_agency_newsletter',
		'type'=> 'hidden'
	));

	//Testimonials Section
	$wp_customize->add_section('software_agency_testimonials', array(
		'title'       => __('Testimonials Section', 'software-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','software-agency'),
		'priority'    => null,
		'panel'       => 'software_agency_panel_id',
	));

	$wp_customize->add_setting('software_agency_testimonials_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_testimonials_text',array(
		'description' => __('<p>1. More options for testimonials section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for testimonials section.</p>','software-agency'),
		'section'=> 'software_agency_testimonials',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('software_agency_testimonials_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_testimonials_btn',array(
		'description' => "<a class='go-pro' target='_blank' href=".esc_url(SOFTWARE_AGENCY_BUY_NOW).">More Info</a>",

		'section'=> 'software_agency_testimonials',
		'type'=> 'hidden'
	));

	//Video Section
	$wp_customize->add_section('software_agency_video', array(
		'title'       => __('Video Section', 'software-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','software-agency'),
		'priority'    => null,
		'panel'       => 'software_agency_panel_id',
	));

	$wp_customize->add_setting('software_agency_video_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_video_text',array(
		'description' => __('<p>1. More options for video section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for video section.</p>','software-agency'),
		'section'=> 'software_agency_video',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('software_agency_video_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_video_btn',array(
		'description' => "<a class='go-pro' target='_blank' href=".esc_url(SOFTWARE_AGENCY_BUY_NOW).">More Info</a>",

		'section'=> 'software_agency_video',
		'type'=> 'hidden'
	));

	//Footer Text
	$wp_customize->add_section('software_agency_footer',array(
		'title'	=> esc_html__('Footer Settings','software-agency'),
		'description' => __('For more options of the footer section </br> <a class="go-pro-btn" target="blank" href="https://www.vwthemes.com/products/software-company-wordpress-theme">GET PRO</a>','software-agency'),
		'panel' => 'software_agency_panel_id',
	));

	$wp_customize->add_setting( 'software_agency_footer_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new software_agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_footer_hide_show',array(
		'label' => esc_html__( 'Show / Hide Footer','software-agency' ),
		'section' => 'software_agency_footer'
    )));

 	// font size
	$wp_customize->add_setting('software_agency_button_footer_font_size',array(
		'default'=> 40,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_button_footer_font_size',array(
		'label'	=> __('Footer Heading Font Size','software-agency'),
  		'type'        => 'number',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
		'section'=> 'software_agency_footer',
	));

	$wp_customize->add_setting('software_agency_button_footer_heading_letter_spacing',array(
		'default'=> 1,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_button_footer_heading_letter_spacing',array(
		'label'	=> __('Heading Letter Spacing','software-agency'),
  		'type'        => 'number',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
	),
		'section'=> 'software_agency_footer',
	));

	// text trasform
	$wp_customize->add_setting('software_agency_button_footer_text_transform',array(
		'default'=> 'Capitalize',
		'sanitize_callback'	=> 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_button_footer_text_transform',array(
		'type' => 'radio',
		'label'	=> __('Heading Text Transform','software-agency'),
		'choices' => array(
      'Uppercase' => __('Uppercase','software-agency'),
      'Capitalize' => __('Capitalize','software-agency'),
      'Lowercase' => __('Lowercase','software-agency'),
    ),
		'section'=> 'software_agency_footer',
	));

	$wp_customize->add_setting('software_agency_footer_heading_weight',array(
        'default' => 400,
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_footer_heading_weight',array(
        'type' => 'select',
        'label' => __('Heading Font Weight','software-agency'),
        'section' => 'software_agency_footer',
        'choices' => array(
        	'100' => __('100','software-agency'),
            '200' => __('200','software-agency'),
            '300' => __('300','software-agency'),
            '400' => __('400','software-agency'),
            '500' => __('500','software-agency'),
            '600' => __('600','software-agency'),
            '700' => __('700','software-agency'),
            '800' => __('800','software-agency'),
            '900' => __('900','software-agency'),
        ),
	) );

	$wp_customize->add_setting('software_agency_footer_template',array(
		'default'	=> esc_html('software_agency-footer-one'),
		'sanitize_callback'	=> 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_footer_template',array(
		'label'	=> esc_html__('Footer style','software-agency'),
		'section'	=> 'software_agency_footer',
		'setting'	=> 'software_agency_footer_template',
		'type' => 'select',
		'choices' => array(
			'software_agency-footer-one' => esc_html__('Style 1', 'software-agency'),
			'software_agency-footer-two' => esc_html__('Style 2', 'software-agency'),
			'software_agency-footer-three' => esc_html__('Style 3', 'software-agency'),
			'software_agency-footer-four' => esc_html__('Style 4', 'software-agency'),
			'software_agency-footer-five' => esc_html__('Style 5', 'software-agency'),
		)
	));

	$wp_customize->add_setting('software_agency_footer_background_color', array(
		'default'           => '#11235e',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'software_agency_footer_background_color', array(
		'label'    => __('Footer Background Color', 'software-agency'),
		'section'  => 'software_agency_footer',
	)));

	$wp_customize->add_setting('software_agency_footer_background_image',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'software_agency_footer_background_image',array(
        'label' => __('Footer Background Image','software-agency'),
        'section' => 'software_agency_footer'
	)));

	$wp_customize->add_setting('software_agency_footer_img_position',array(
	  'default' => 'center center',
	  'transport' => 'refresh',
	  'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_footer_img_position',array(
		'type' => 'select',
		'label' => __('Footer Image Position','software-agency'),
		'section' => 'software_agency_footer',
		'choices' 	=> array(
			'left top' 		=> esc_html__( 'Top Left', 'software-agency' ),
			'center top'   => esc_html__( 'Top', 'software-agency' ),
			'right top'   => esc_html__( 'Top Right', 'software-agency' ),
			'left center'   => esc_html__( 'Left', 'software-agency' ),
			'center center'   => esc_html__( 'Center', 'software-agency' ),
			'right center'   => esc_html__( 'Right', 'software-agency' ),
			'left bottom'   => esc_html__( 'Bottom Left', 'software-agency' ),
			'center bottom'   => esc_html__( 'Bottom', 'software-agency' ),
			'right bottom'   => esc_html__( 'Bottom Right', 'software-agency' ),
		),
	)); 

	// Footer
	$wp_customize->add_setting('software_agency_img_footer',array(
		'default'=> 'scroll',
		'sanitize_callback'	=> 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_img_footer',array(
		'type' => 'select',
		'label'	=> __('Footer Background Attatchment','software-agency'),
		'choices' => array(
            'fixed' => __('fixed','software-agency'),
            'scroll' => __('scroll','software-agency'),
        ),
		'section'=> 'software_agency_footer',
	));

	$wp_customize->add_setting('software_agency_footer_widgets_heading',array(
        'default' => 'Left',
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_footer_widgets_heading',array(
        'type' => 'select',
        'label' => __('Footer Widget Heading','software-agency'),
        'section' => 'software_agency_footer',
        'choices' => array(
        	'Left' => __('Left','software-agency'),
            'Center' => __('Center','software-agency'),
            'Right' => __('Right','software-agency')
        ),
	) );

	$wp_customize->add_setting('software_agency_footer_widgets_content',array(
        'default' => 'Left',
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_footer_widgets_content',array(
        'type' => 'select',
        'label' => __('Footer Widget Content','software-agency'),
        'section' => 'software_agency_footer',
        'choices' => array(
        	'Left' => __('Left','software-agency'),
            'Center' => __('Center','software-agency'),
            'Right' => __('Right','software-agency')
        ),
	) );

	// footer padding
	$wp_customize->add_setting('software_agency_footer_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_footer_padding',array(
		'label'	=> __('Footer Top Bottom Padding','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
      	'placeholder' => __( '10px', 'software-agency' ),
    ),
		'section'=> 'software_agency_footer',
		'type'=> 'text'
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('software_agency_footer_text', array(
		'selector' => '.copyright p a',
		'render_callback' => 'software_agency_customize_partial_software_agency_footer_text',
	));

	$wp_customize->add_setting( 'software_agency_copyright_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'software_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new software_agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_copyright_hide_show',array(
      'label' => esc_html__( 'Show / Hide Copyright','software-agency' ),
      'section' => 'software_agency_footer'
    )));

	$wp_customize->add_setting('software_agency_copyright_background_color', array(
		'default'           => '#ffd04a',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'software_agency_copyright_background_color', array(
		'label'    => __('Copyright Background Color', 'software-agency'),
		'section'  => 'software_agency_footer',
	)));

	$wp_customize->add_setting('software_agency_footer_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_footer_text',array(
		'label'	=> esc_html__('Copyright Text','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'Copyright 2020, .....', 'software-agency' ),
        ),
		'section'=> 'software_agency_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_copyright_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_copyright_font_size',array(
		'label'	=> __('Copyright Font Size','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( '10px', 'software-agency' ),
        ),
		'section'=> 'software_agency_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_copyright_alingment',array(
        'default' => 'center',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control(new Software_Agency_Image_Radio_Control($wp_customize, 'software_agency_copyright_alingment', array(
        'type' => 'select',
        'label' => esc_html__('Copyright Alignment','software-agency'),
        'section' => 'software_agency_footer',
        'settings' => 'software_agency_copyright_alingment',
        'choices' => array(
            'left' => esc_url(get_template_directory_uri()).'/assets/images/copyright1.png',
            'center' => esc_url(get_template_directory_uri()).'/assets/images/copyright2.png',
            'right' => esc_url(get_template_directory_uri()).'/assets/images/copyright3.png'
    ))));

	$wp_customize->add_setting( 'software_agency_footer_scroll',array(
    	'default' => 1,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'software_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_footer_scroll',array(
      	'label' => esc_html__( 'Show / Hide Scroll to Top','software-agency' ),
      	'section' => 'software_agency_footer'
    )));

    //Selective Refresh
	$wp_customize->selective_refresh->add_partial('software_agency_scroll_to_top_icon', array(
		'selector' => '.scrollup i',
		'render_callback' => 'software_agency_customize_partial_software_agency_scroll_to_top_icon',
	));

    $wp_customize->add_setting('software_agency_scroll_to_top_icon',array(
		'default'	=> 'fas fa-long-arrow-alt-up',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_scroll_to_top_icon',array(
		'label'	=> __('Add Scroll to Top Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_footer',
		'setting'	=> 'software_agency_scroll_to_top_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('software_agency_scroll_to_top_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_scroll_to_top_font_size',array(
		'label'	=> __('Icon Font Size','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( '10px', 'software-agency' ),
        ),
		'section'=> 'software_agency_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_scroll_to_top_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_scroll_to_top_padding',array(
		'label'	=> __('Icon Top Bottom Padding','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( '10px', 'software-agency' ),
        ),
		'section'=> 'software_agency_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_scroll_to_top_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_scroll_to_top_width',array(
		'label'	=> __('Icon Width','software-agency'),
		'description'	=> __('Enter a value in pixels Example:20px','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( '10px', 'software-agency' ),
        ),
		'section'=> 'software_agency_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_scroll_to_top_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_scroll_to_top_height',array(
		'label'	=> __('Icon Height','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( '10px', 'software-agency' ),
        ),
		'section'=> 'software_agency_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'software_agency_scroll_to_top_border_radius', array(
		'default'              => '',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'software_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'software_agency_scroll_to_top_border_radius', array(
		'label'       => esc_html__( 'Icon Border Radius','software-agency' ),
		'section'     => 'software_agency_footer',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

    $wp_customize->add_setting('software_agency_scroll_top_alignment',array(
        'default' => 'Right',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control(new Software_Agency_Image_Radio_Control($wp_customize, 'software_agency_scroll_top_alignment', array(
        'type' => 'select',
        'label' => esc_html__('Scroll To Top','software-agency'),
        'section' => 'software_agency_footer',
        'settings' => 'software_agency_scroll_top_alignment',
        'choices' => array(
            'Left' => esc_url(get_template_directory_uri()).'/assets/images/layout1.png',
            'Center' => esc_url(get_template_directory_uri()).'/assets/images/layout2.png',
            'Right' => esc_url(get_template_directory_uri()).'/assets/images/layout3.png'
    ))));

     $wp_customize->add_setting('software_agency_reset_footer_settings',array(
		'sanitize_callback'	=> 'sanitize_text_field'
   	));
   	$wp_customize->add_control(new Software_Agency_Reset_Custom_Control($wp_customize, 'software_agency_reset_footer_settings',array(
		'type' => 'reset_control',
		'label' => __('Reset Footer Settings', 'software-agency'),
		'description' => 'software_agency_reset_all_settings',
		'section' => 'software_agency_footer'
   	)));

   	// footer social icon
	$wp_customize->add_setting( 'software_agency_footer_icon',array(
		'default' => false,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
  	) );
	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_footer_icon',array(
		'label' => esc_html__( 'Show / Hide Footer Social Icon','software-agency' ),
		'section' => 'software_agency_footer'
  	)));

   	$wp_customize->add_setting('software_agency_align_footer_social_icon',array(
        'default' => 'center',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_align_footer_social_icon',array(
        'type' => 'select',
        'label' => __('Social Icon Alignment ','software-agency'),
        'section' => 'software_agency_footer',
        'choices' => array(
            'left' => __('Left','software-agency'),
            'right' => __('Right','software-agency'),
            'center' => __('Center','software-agency'),
        ),
	) );

	$wp_customize->add_setting( 'software_agency_copyright_sticky',array(
      'default' => 0,
      'transport' => 'refresh',
      'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_copyright_sticky',array(
      'label' => esc_html__( 'Show / Hide Sticky Copyright','software-agency' ),
      'section' => 'software_agency_footer'
    )));

   $wp_customize->add_setting('software_agency_footer_social_icons_font_size',array(
       'default'=> 16,
       'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('software_agency_footer_social_icons_font_size',array(
    'label' => __('Social Icon Font Size','software-agency'),
    	'type'        => 'number',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
		'section'=> 'software_agency_footer',
	 ));

	//Blog Post
	$wp_customize->add_panel( $software_agency_parent_panel );

	$BlogPostParentPanel = new Software_Agency_WP_Customize_Panel( $wp_customize, 'software_agency_blog_post_parent_panel', array(
		'title' => esc_html__( 'Blog Post Settings', 'software-agency' ),
		'panel' => 'software_agency_panel_id',
		'priority' => 20,
	));

	$wp_customize->add_panel( $BlogPostParentPanel );

	// Add example section and controls to the middle (second) panel
	$wp_customize->add_section( 'software_agency_post_settings', array(
		'title' => esc_html__( 'Post Settings', 'software-agency' ),
		'panel' => 'software_agency_blog_post_parent_panel',
	));

	//Blog layout
    $wp_customize->add_setting('software_agency_blog_layout_option',array(
        'default' => 'Default',
        'sanitize_callback' => 'software_agency_sanitize_choices'
    ));
    $wp_customize->add_control(new Software_Agency_Image_Radio_Control($wp_customize, 'software_agency_blog_layout_option', array(
        'type' => 'select',
        'label' => esc_html__('Blog Layouts','software-agency'),
        'section' => 'software_agency_post_settings',
        'choices' => array(
            'Default' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout1.png',
            'Center' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout2.png',
            'Left' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout3.png',
    ))));

	$wp_customize->add_setting('software_agency_theme_options',array(
        'default' => 'Right Sidebar',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_theme_options',array(
        'type' => 'select',
        'label' => esc_html__('Post Sidebar Layout','software-agency'),
        'description' => esc_html__('Here you can change the sidebar layout for posts. ','software-agency'),
        'section' => 'software_agency_post_settings',
        'choices' => array(
            'Left Sidebar' => esc_html__('Left Sidebar','software-agency'),
            'Right Sidebar' => esc_html__('Right Sidebar','software-agency'),
            'One Column' => esc_html__('One Column','software-agency'),
            'Three Columns' => esc_html__('Three Columns','software-agency'),
            'Four Columns' => esc_html__('Four Columns','software-agency'),
            'Grid Layout' => esc_html__('Grid Layout','software-agency')
        ),
	) );

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('software_agency_toggle_postdate', array(
		'selector' => '.post-main-box h2 a',
		'render_callback' => 'software_agency_customize_partial_software_agency_toggle_postdate',
	));

  	$wp_customize->add_setting('software_agency_toggle_postdate_icon',array(
		'default'	=> 'fas fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_toggle_postdate_icon',array(
		'label'	=> __('Add Post Date Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_post_settings',
		'setting'	=> 'software_agency_toggle_postdate_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'software_agency_toggle_postdate',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_toggle_postdate',array(
        'label' => esc_html__( 'Show / Hide Post Date','software-agency' ),
        'section' => 'software_agency_post_settings'
    )));

    $wp_customize->add_setting('software_agency_toggle_author_icon',array(
		'default'	=> 'fas fa-user',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_toggle_author_icon',array(
		'label'	=> __('Add Author Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_post_settings',
		'setting'	=> 'software_agency_toggle_author_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'software_agency_toggle_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_toggle_author',array(
		'label' => esc_html__( 'Show / Hide Author','software-agency' ),
		'section' => 'software_agency_post_settings'
    )));

    $wp_customize->add_setting('software_agency_toggle_comments_icon',array(
		'default'	=> 'fa fa-comments',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_toggle_comments_icon',array(
		'label'	=> __('Add Comments Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_post_settings',
		'setting'	=> 'software_agency_toggle_comments_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'software_agency_toggle_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_toggle_comments',array(
		'label' => esc_html__( 'Show / Hide Comments','software-agency' ),
		'section' => 'software_agency_post_settings'
    )));

    $wp_customize->add_setting('software_agency_toggle_time_icon',array(
		'default'	=> 'fas fa-clock',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_toggle_time_icon',array(
		'label'	=> __('Add Time Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_post_settings',
		'setting'	=> 'software_agency_toggle_time_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'software_agency_toggle_time',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_toggle_time',array(
		'label' => esc_html__( 'Show / Hide Time','software-agency' ),
		'section' => 'software_agency_post_settings'
    )));

    $wp_customize->add_setting( 'software_agency_featured_image_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
	));
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_featured_image_hide_show', array(
		'label' => esc_html__( 'Show / Hide Featured Image','software-agency' ),
		'section' => 'software_agency_post_settings'
    )));

    $wp_customize->add_setting( 'software_agency_featured_image_border_radius', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'software_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'software_agency_featured_image_border_radius', array(
		'label'       => esc_html__( 'Featured Image Border Radius','software-agency' ),
		'section'     => 'software_agency_post_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting( 'software_agency_featured_image_box_shadow', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'software_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'software_agency_featured_image_box_shadow', array(
		'label'       => esc_html__( 'Featured Image Box Shadow','software-agency' ),
		'section'     => 'software_agency_post_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Featured Image
	$wp_customize->add_setting('software_agency_blog_post_featured_image_dimension',array(
       'default' => 'default',
       'sanitize_callback'	=> 'software_agency_sanitize_choices'
	));
  	$wp_customize->add_control('software_agency_blog_post_featured_image_dimension',array(
		'type' => 'select',
		'label'	=> __('Blog Post Featured Image Dimension','software-agency'),
		'section'	=> 'software_agency_post_settings',
		'choices' => array(
		'default' => __('Default','software-agency'),
		'custom' => __('Custom Image Size','software-agency'),
      ),
  	));

	$wp_customize->add_setting('software_agency_blog_post_featured_image_custom_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
		));
	$wp_customize->add_control('software_agency_blog_post_featured_image_custom_width',array(
		'label'	=> __('Featured Image Custom Width','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
    	'placeholder' => __( '10px', 'software-agency' ),),
		'section'=> 'software_agency_post_settings',
		'type'=> 'text',
		'active_callback' => 'software_agency_blog_post_featured_image_dimension'
		));

	$wp_customize->add_setting('software_agency_blog_post_featured_image_custom_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_blog_post_featured_image_custom_height',array(
		'label'	=> __('Featured Image Custom Height','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
    	'placeholder' => __( '10px', 'software-agency' ),),
		'section'=> 'software_agency_post_settings',
		'type'=> 'text',
		'active_callback' => 'software_agency_blog_post_featured_image_dimension'
	));

    $wp_customize->add_setting( 'software_agency_excerpt_number', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'software_agency_sanitize_number_range',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'software_agency_excerpt_number', array(
		'label'       => esc_html__( 'Excerpt length','software-agency' ),
		'section'     => 'software_agency_post_settings',
		'type'        => 'range',
		'settings'    => 'software_agency_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting('software_agency_meta_field_separator',array(
		'default'=> '|',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_meta_field_separator',array(
		'label'	=> __('Add Meta Separator','software-agency'),
		'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','software-agency'),
		'section'=> 'software_agency_post_settings',
		'type'=> 'text'
	));

    $wp_customize->add_setting('software_agency_blog_page_posts_settings',array(
        'default' => 'Into Blocks',
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_blog_page_posts_settings',array(
        'type' => 'select',
        'label' => __('Display Blog Posts','software-agency'),
        'section' => 'software_agency_post_settings',
        'choices' => array(
        	'Into Blocks' => __('Into Blocks','software-agency'),
            'Without Blocks' => __('Without Blocks','software-agency')
        ),
	) );

    $wp_customize->add_setting('software_agency_excerpt_settings',array(
        'default' => 'Excerpt',
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_excerpt_settings',array(
        'type' => 'select',
        'label' => esc_html__('Post Content','software-agency'),
        'section' => 'software_agency_post_settings',
        'choices' => array(
        	'Content' => esc_html__('Content','software-agency'),
            'Excerpt' => esc_html__('Excerpt','software-agency'),
            'No Content' => esc_html__('No Content','software-agency')
        ),
	) );

	$wp_customize->add_setting('software_agency_blog_excerpt_suffix',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_blog_excerpt_suffix',array(
		'label'	=> __('Add Excerpt Suffix','software-agency'),
		'input_attrs' => array(
            'placeholder' => __( '[...]', 'software-agency' ),
        ),
		'section'=> 'software_agency_post_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'software_agency_blog_pagination_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new software_agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_blog_pagination_hide_show',array(
		'label' => esc_html__( 'Show / Hide Blog Pagination','software-agency' ),
		'section' => 'software_agency_post_settings'
    )));

	$wp_customize->add_setting( 'software_agency_blog_pagination_type', array(
        'default'			=> 'blog-page-numbers',
        'sanitize_callback'	=> 'software_agency_sanitize_choices'
    ));
    $wp_customize->add_control( 'software_agency_blog_pagination_type', array(
        'section' => 'software_agency_post_settings',
        'type' => 'select',
        'label' => __( 'Blog Pagination', 'software-agency' ),
        'choices'		=> array(
            'blog-page-numbers'  => __( 'Numeric', 'software-agency' ),
            'next-prev' => __( 'Older Posts/Newer Posts', 'software-agency' ),
    )));

    // Button Settings
	$wp_customize->add_section( 'software_agency_button_settings', array(
		'title' => esc_html__( 'Button Settings', 'software-agency' ),
		'panel' => 'software_agency_blog_post_parent_panel',
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('software_agency_button_text', array(
		'selector' => '.post-main-box .more-btn a',
		'render_callback' => 'software_agency_customize_partial_software_agency_button_text',
	));

    $wp_customize->add_setting('software_agency_button_text',array(
		'default'=> esc_html__('Read More','software-agency'),
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_button_text',array(
		'label'	=> esc_html__('Add Button Text','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'Read More', 'software-agency' ),
        ),
		'section'=> 'software_agency_button_settings',
		'type'=> 'text'
	));

	// font size button
	$wp_customize->add_setting('software_agency_button_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_button_font_size',array(
		'label'	=> __('Button Font Size','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
      	'placeholder' => __( '10px', 'software-agency' ),
    ),
    	'type'        => 'text',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
		'section'=> 'software_agency_button_settings',
	));

	$wp_customize->add_setting( 'software_agency_button_border_radius', array(
		'default'              => 8,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'software_agency_sanitize_number_range',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'software_agency_button_border_radius', array(
		'label'       => esc_html__( 'Button Border Radius','software-agency' ),
		'section'     => 'software_agency_button_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting('software_agency_button_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_button_padding_top_bottom',array(
		'label'	=> esc_html__('Padding Top Bottom','software-agency'),
		'description' => esc_html__('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( '10px', 'software-agency' ),
        ),
		'section' => 'software_agency_button_settings',
		'type' => 'text'
	));

	$wp_customize->add_setting('software_agency_button_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_button_padding_left_right',array(
		'label'	=> esc_html__('Padding Left Right','software-agency'),
		'description' => esc_html__('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( '10px', 'software-agency' ),
        ),
		'section' => 'software_agency_button_settings',
		'type' => 'text'
	));

	$wp_customize->add_setting('software_agency_button_letter_spacing',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_button_letter_spacing',array(
		'label'	=> __('Button Letter Spacing','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
      	'placeholder' => __( '10px', 'software-agency' ),
    ),
    	'type'        => 'text',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
		'section'=> 'software_agency_button_settings',
	));

	// text trasform
	$wp_customize->add_setting('software_agency_button_text_transform',array(
		'default'=> 'Uppercase',
		'sanitize_callback'	=> 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_button_text_transform',array(
		'type' => 'radio',
		'label'	=> __('Button Text Transform','software-agency'),
		'choices' => array(
            'Uppercase' => __('Uppercase','software-agency'),
            'Capitalize' => __('Capitalize','software-agency'),
            'Lowercase' => __('Lowercase','software-agency'),
        ),
		'section'=> 'software_agency_button_settings',
	));

	// Related Post Settings
	$wp_customize->add_section( 'software_agency_related_posts_settings', array(
		'title' => esc_html__( 'Related Posts Settings', 'software-agency' ),
		'panel' => 'software_agency_blog_post_parent_panel',
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('software_agency_related_post_title', array(
		'selector' => '.related-post h3',
		'render_callback' => 'software_agency_customize_partial_software_agency_related_post_title',
	));

    $wp_customize->add_setting( 'software_agency_related_post',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_related_post',array(
		'label' => esc_html__( 'Show / Hide Related Post','software-agency' ),
		'section' => 'software_agency_related_posts_settings'
    )));

    $wp_customize->add_setting('software_agency_related_post_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_related_post_title',array(
		'label'	=> esc_html__('Add Related Post Title','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'Related Post', 'software-agency' ),
        ),
		'section'=> 'software_agency_related_posts_settings',
		'type'=> 'text'
	));

   	$wp_customize->add_setting('software_agency_related_posts_count',array(
		'default'=> 3,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_related_posts_count',array(
		'label'	=> esc_html__('Add Related Post Count','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( '3', 'software-agency' ),
        ),
		'section'=> 'software_agency_related_posts_settings',
		'type'=> 'number'
	));

	$wp_customize->add_setting( 'software_agency_related_posts_excerpt_number', array(
		'default'              => 20,
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'software_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'software_agency_related_posts_excerpt_number', array(
		'label'       => esc_html__( 'Related Posts Excerpt length','software-agency' ),
		'section'     => 'software_agency_related_posts_settings',
		'type'        => 'range',
		'settings'    => 'software_agency_related_posts_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting( 'software_agency_related_toggle_postdate',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'software_agency_switch_sanitization'
  	));
  	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_related_toggle_postdate',array(
	    'label' => esc_html__( 'Show / Hide Post Date','software-agency' ),
	    'section' => 'software_agency_related_posts_settings'
  	)));

  	$wp_customize->add_setting('software_agency_related_postdate_icon',array(
	    'default' => 'fas fa-calendar-alt',
	    'sanitize_callback' => 'sanitize_text_field'
  	));
  	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
  	$wp_customize,'software_agency_related_postdate_icon',array(
	    'label' => __('Add Post Date Icon','software-agency'),
	    'transport' => 'refresh',
	    'section' => 'software_agency_related_posts_settings',
	    'setting' => 'software_agency_related_postdate_icon',
	    'type'    => 'icon'
  	)));

	$wp_customize->add_setting( 'software_agency_related_toggle_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
  	));
  	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_related_toggle_author',array(
		'label' => esc_html__( 'Show / Hide Author','software-agency' ),
		'section' => 'software_agency_related_posts_settings'
  	)));

  	$wp_customize->add_setting('software_agency_related_author_icon',array(
	    'default' => 'fas fa-user',
	    'sanitize_callback' => 'sanitize_text_field'
  	));
  	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
  	$wp_customize,'software_agency_related_author_icon',array(
	    'label' => __('Add Author Icon','software-agency'),
	    'transport' => 'refresh',
	    'section' => 'software_agency_related_posts_settings',
	    'setting' => 'software_agency_related_author_icon',
	    'type'    => 'icon'
  	)));

	$wp_customize->add_setting( 'software_agency_related_toggle_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
  	) );
  	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_related_toggle_comments',array(
		'label' => esc_html__( 'Show / Hide Comments','software-agency' ),
		'section' => 'software_agency_related_posts_settings'
  	)));

  	$wp_customize->add_setting('software_agency_related_comments_icon',array(
	    'default' => 'fa fa-comments',
	    'sanitize_callback' => 'sanitize_text_field'
  	));
  	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
  	$wp_customize,'software_agency_related_comments_icon',array(
	    'label' => __('Add Comments Icon','software-agency'),
	    'transport' => 'refresh',
	    'section' => 'software_agency_related_posts_settings',
	    'setting' => 'software_agency_related_comments_icon',
	    'type'    => 'icon'
  	)));

	$wp_customize->add_setting( 'software_agency_related_toggle_time',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
  	) );
  	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_related_toggle_time',array(
		'label' => esc_html__( 'Show / Hide Time','software-agency' ),
		'section' => 'software_agency_related_posts_settings'
 	)));

  	$wp_customize->add_setting('software_agency_related_time_icon',array(
	    'default' => 'fas fa-clock',
	    'sanitize_callback' => 'sanitize_text_field'
  	));
  	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
  	$wp_customize,'software_agency_related_time_icon',array(
	    'label' => __('Add Time Icon','software-agency'),
	    'transport' => 'refresh',
	    'section' => 'software_agency_related_posts_settings',
	    'setting' => 'software_agency_related_time_icon',
	    'type'    => 'icon'
  	)));

  	$wp_customize->add_setting('software_agency_related_post_meta_field_separator',array(
		'default'=> '|',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_related_post_meta_field_separator',array(
		'label'	=> __('Add Meta Separator','software-agency'),
		'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','software-agency'),
		'section'=> 'software_agency_related_posts_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'software_agency_related_image_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
	));
  	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_related_image_hide_show', array(
		'label' => esc_html__( 'Show / Hide Featured Image','software-agency' ),
		'section' => 'software_agency_related_posts_settings'
  	)));

  	$wp_customize->add_setting( 'software_agency_related_image_box_shadow', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'software_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'software_agency_related_image_box_shadow', array(
		'label'       => esc_html__( 'Related post Image Box Shadow','software-agency' ),
		'section'     => 'software_agency_related_posts_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

 	$wp_customize->add_setting('software_agency_related_button_text',array(
		'default'=> esc_html__('Read More','software-agency'),
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_related_button_text',array(
		'label'	=> esc_html__('Add Button Text','software-agency'),
		'input_attrs' => array(
      'placeholder' => esc_html__( 'Read More', 'software-agency' ),
        ),
		'section'=> 'software_agency_related_posts_settings',
		'type'=> 'text'
	));

	// Single Posts Settings
	$wp_customize->add_section( 'software_agency_single_blog_settings', array(
		'title' => __( 'Single Post Settings', 'software-agency' ),
		'panel' => 'software_agency_blog_post_parent_panel',
	));

  	$wp_customize->add_setting('software_agency_single_postdate_icon',array(
		'default'	=> 'fas fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_single_postdate_icon',array(
		'label'	=> __('Add Post Date Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_single_blog_settings',
		'setting'	=> 'software_agency_single_postdate_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'software_agency_single_postdate',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'software_agency_switch_sanitization'
	) );
	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_single_postdate',array(
	    'label' => esc_html__( 'Show / Hide Date','software-agency' ),
	   'section' => 'software_agency_single_blog_settings'
	)));

	$wp_customize->add_setting('software_agency_single_author_icon',array(
		'default'	=> 'fas fa-user',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_single_author_icon',array(
		'label'	=> __('Add Author Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_single_blog_settings',
		'setting'	=> 'software_agency_single_author_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'software_agency_single_author',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'software_agency_switch_sanitization'
	) );
	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_single_author',array(
	    'label' => esc_html__( 'Show / Hide Author','software-agency' ),
	    'section' => 'software_agency_single_blog_settings'
	)));

   	$wp_customize->add_setting('software_agency_single_comments_icon',array(
		'default'	=> 'fa fa-comments',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_single_comments_icon',array(
		'label'	=> __('Add Comments Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_single_blog_settings',
		'setting'	=> 'software_agency_single_comments_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'software_agency_single_comments',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'software_agency_switch_sanitization'
	) );
	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_single_comments',array(
	    'label' => esc_html__( 'Show / Hide Comments','software-agency' ),
	    'section' => 'software_agency_single_blog_settings'
	)));

  	$wp_customize->add_setting('software_agency_single_time_icon',array(
		'default'	=> 'fas fa-clock',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_single_time_icon',array(
		'label'	=> __('Add Time Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_single_blog_settings',
		'setting'	=> 'software_agency_single_time_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'software_agency_single_time',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'software_agency_switch_sanitization'
	) );

	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_single_time',array(
	    'label' => esc_html__( 'Show / Hide Time','software-agency' ),
	    'section' => 'software_agency_single_blog_settings'
	)));

	$wp_customize->add_setting( 'software_agency_single_post_breadcrumb',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_single_post_breadcrumb',array(
		'label' => esc_html__( 'Show / Hide Breadcrumb','software-agency' ),
		'section' => 'software_agency_single_blog_settings'
    )));

     // Single Posts Category
  	$wp_customize->add_setting( 'software_agency_single_post_category',array(
		'default' => true,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
  	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_single_post_category',array(
		'label' => esc_html__( 'Show / Hide Category','software-agency' ),
		'section' => 'software_agency_single_blog_settings'
    )));

	$wp_customize->add_setting( 'software_agency_toggle_tags',array(
		'default' => 0,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
	));
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_toggle_tags', array(
		'label' => esc_html__( 'Show / Hide Tags','software-agency' ),
		'section' => 'software_agency_single_blog_settings'
    )));

	$wp_customize->add_setting( 'software_agency_single_blog_post_navigation_show_hide',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
	));
	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_single_blog_post_navigation_show_hide', array(
		'label' => esc_html__( 'Show / Hide Post Navigation','software-agency' ),
		'section' => 'software_agency_single_blog_settings'
	)));

	//navigation text
	$wp_customize->add_setting('software_agency_single_blog_prev_navigation_text',array(
		'default'=> 'PREVIOUS',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_single_blog_prev_navigation_text',array(
		'label'	=> __('Post Navigation Text','software-agency'),
		'input_attrs' => array(
    'placeholder' => __( 'PREVIOUS', 'software-agency' ),
        ),
		'section'=> 'software_agency_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_single_blog_next_navigation_text',array(
		'default'=> 'NEXT',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_single_blog_next_navigation_text',array(
		'label'	=> __('Post Navigation Text','software-agency'),
		'input_attrs' => array(
            'placeholder' => __( 'NEXT', 'software-agency' ),
        ),
		'section'=> 'software_agency_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'software_agency_singlepost_image_box_shadow', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'software_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'software_agency_singlepost_image_box_shadow', array(
		'label'       => esc_html__( 'Single post Image Box Shadow','software-agency' ),
		'section'     => 'software_agency_single_blog_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

   	$wp_customize->add_setting('software_agency_single_post_meta_field_separator',array(
		'default'=> '|',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_single_post_meta_field_separator',array(
		'label'	=> __('Add Meta Separator','software-agency'),
		'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','software-agency'),
		'section'=> 'software_agency_single_blog_settings',
		'type'=> 'text'
	));

    $wp_customize->add_setting('software_agency_single_blog_comment_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('software_agency_single_blog_comment_title',array(
		'label'	=> __('Add Comment Title','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( 'Leave a Reply', 'software-agency' ),
        ),
		'section'=> 'software_agency_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_single_blog_comment_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('software_agency_single_blog_comment_button_text',array(
		'label'	=> __('Add Comment Button Text','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( 'Post Comment', 'software-agency' ),
        ),
		'section'=> 'software_agency_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_single_blog_comment_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_single_blog_comment_width',array(
		'label'	=> __('Comment Form Width','software-agency'),
		'description'	=> __('Enter a value in %. Example:50%','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( '100%', 'software-agency' ),
        ),
		'section'=> 'software_agency_single_blog_settings',
		'type'=> 'text'
	));

	// Grid layout setting
	$wp_customize->add_section( 'software_agency_grid_layout_settings', array(
		'title' => __( 'Grid Layout Settings', 'software-agency' ),
		'panel' => 'software_agency_blog_post_parent_panel',
	));

  	$wp_customize->add_setting('software_agency_grid_postdate_icon',array(
		'default'	=> 'fas fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_grid_postdate_icon',array(
		'label'	=> __('Add Post Date Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_grid_layout_settings',
		'setting'	=> 'software_agency_grid_postdate_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'software_agency_grid_postdate',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_grid_postdate',array(
        'label' => esc_html__( 'Show / Hide Post Date','software-agency' ),
        'section' => 'software_agency_grid_layout_settings'
    )));

	$wp_customize->add_setting('software_agency_grid_author_icon',array(
		'default'	=> 'fas fa-user',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_grid_author_icon',array(
		'label'	=> __('Add Author Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_grid_layout_settings',
		'setting'	=> 'software_agency_grid_author_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'software_agency_grid_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_grid_author',array(
		'label' => esc_html__( 'Show / Hide Author','software-agency' ),
		'section' => 'software_agency_grid_layout_settings'
    )));

   	$wp_customize->add_setting('software_agency_grid_comments_icon',array(
		'default'	=> 'fa fa-comments',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_grid_comments_icon',array(
		'label'	=> __('Add Comments Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_grid_layout_settings',
		'setting'	=> 'software_agency_grid_comments_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'software_agency_grid_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_grid_comments',array(
		'label' => esc_html__( 'Show / Hide Comments','software-agency' ),
		'section' => 'software_agency_grid_layout_settings'
    )));

	$wp_customize->add_setting('software_agency_grid_button_text',array(
		'default'=> esc_html__('Read More','software-agency'),
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_grid_button_text',array(
		'label'	=> esc_html__('Add Button Text','software-agency'),
		'input_attrs' => array(
        'placeholder' => esc_html__( 'Read More', 'software-agency' ),
      ),
		'section'=> 'software_agency_grid_layout_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_grid_excerpt_suffix',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_grid_excerpt_suffix',array(
		'label'	=> __('Add Excerpt Suffix','software-agency'),
		'input_attrs' => array(
            'placeholder' => __( '[...]', 'software-agency' ),
        ),
		'section'=> 'software_agency_grid_layout_settings',
		'type'=> 'text'
	));

    $wp_customize->add_setting('software_agency_grid_excerpt_settings',array(
        'default' => 'Excerpt',
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_grid_excerpt_settings',array(
        'type' => 'select',
        'label' => esc_html__('Grid Post Content','software-agency'),
        'section' => 'software_agency_grid_layout_settings',
        'choices' => array(
        	'Content' => esc_html__('Content','software-agency'),
            'Excerpt' => esc_html__('Excerpt','software-agency'),
            'No Content' => esc_html__('No Content','software-agency')
        ),
	) );

	$wp_customize->add_setting( 'software_agency_grid_image_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
	));
  	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_grid_image_hide_show', array(
		'label' => esc_html__( 'Show / Hide Featured Image','software-agency' ),
		'section' => 'software_agency_grid_layout_settings'
  	)));

 	$wp_customize->add_setting('software_agency_grid_post_meta_field_separator',array(
		'default'=> '|',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_grid_post_meta_field_separator',array(
		'label'	=> __('Add Meta Separator','software-agency'),
		'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','software-agency'),
		'section'=> 'software_agency_grid_layout_settings',
		'type'=> 'text'
	)); 

  	$wp_customize->add_setting('software_agency_display_grid_posts_settings',array(
	    'default' => 'Into Blocks',
	    'transport' => 'refresh',
	    'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_display_grid_posts_settings',array(
	    'type' => 'select',
	    'label' => __('Display Grid Posts','software-agency'),
	    'section' => 'software_agency_grid_layout_settings',
	    'choices' => array(
	    	'Into Blocks' => __('Into Blocks','software-agency'),
	      'Without Blocks' => __('Without Blocks','software-agency')
	      ),
	) );

	$wp_customize->add_setting( 'software_agency_grid_featured_image_border_radius', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'software_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'software_agency_grid_featured_image_border_radius', array(
		'label'       => esc_html__( 'Grid Featured Image Border Radius','software-agency' ),
		'section'     => 'software_agency_grid_layout_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting( 'software_agency_grid_featured_image_box_shadow', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'software_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'software_agency_grid_featured_image_box_shadow', array(
		'label'       => esc_html__( 'Grid Featured Image Box Shadow','software-agency' ),
		'section'     => 'software_agency_grid_layout_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Other Settings
	$wp_customize->add_panel( $software_agency_parent_panel );

	$OtherParentPanel = new Software_Agency_WP_Customize_Panel( $wp_customize, 'software_agency_other_parent_panel', array(
		'title' => esc_html__( 'Other Settings', 'software-agency' ),
		'panel' => 'software_agency_panel_id',
		'priority' => 20,
	));

	$wp_customize->add_panel( $OtherParentPanel );

	// Layout
	$wp_customize->add_section( 'software_agency_left_right', array(
    	'title' => esc_html__( 'General Settings', 'software-agency' ),
		'panel' => 'software_agency_other_parent_panel'
	) );

	$wp_customize->add_setting('software_agency_width_option',array(
        'default' => 'Full Width',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control(new Software_Agency_Image_Radio_Control($wp_customize, 'software_agency_width_option', array(
        'type' => 'select',
        'label' => esc_html__('Width Layouts','software-agency'),
        'description' => esc_html__('Here you can change the width layout of Website.','software-agency'),
        'section' => 'software_agency_left_right',
        'choices' => array(
            'Full Width' => esc_url(get_template_directory_uri()).'/assets/images/full-width.png',
            'Wide Width' => esc_url(get_template_directory_uri()).'/assets/images/wide-width.png',
            'Boxed' => esc_url(get_template_directory_uri()).'/assets/images/boxed-width.png',
    ))));

	$wp_customize->add_setting('software_agency_page_layout',array(
        'default' => 'One Column',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_page_layout',array(
        'type' => 'select',
        'label' => esc_html__('Page Sidebar Layout','software-agency'),
        'description' => esc_html__('Here you can change the sidebar layout for pages. ','software-agency'),
        'section' => 'software_agency_left_right',
        'choices' => array(
            'Left Sidebar' => esc_html__('Left Sidebar','software-agency'),
            'Right Sidebar' => esc_html__('Right Sidebar','software-agency'),
            'One Column' => esc_html__('One Column','software-agency')
        ),
	) );

    $wp_customize->add_setting( 'software_agency_single_page_breadcrumb',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_single_page_breadcrumb',array(
		'label' => esc_html__( 'Show / Hide Page Breadcrumb','software-agency' ),
		'section' => 'software_agency_left_right'
    )));

    //Wow Animation
	$wp_customize->add_setting( 'software_agency_animation',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_animation',array(
        'label' => esc_html__( 'Show / Hide Animation ','software-agency' ),
        'description' => __('Here you can disable overall site animation effect','software-agency'),
        'section' => 'software_agency_left_right'
    )));

    //Pre-Loader
	$wp_customize->add_setting( 'software_agency_loader_enable',array(
        'default' => 0,
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_loader_enable',array(
        'label' => esc_html__( 'Show / Hide Pre-Loader','software-agency' ),
        'section' => 'software_agency_left_right'
    )));

	$wp_customize->add_setting('software_agency_preloader_bg_color', array(
		'default'           => '#349dff',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'software_agency_preloader_bg_color', array(
		'label'    => __('Pre-Loader Background Color', 'software-agency'),
		'section'  => 'software_agency_left_right',
	)));

	$wp_customize->add_setting('software_agency_preloader_border_color', array(
		'default'           => '#ffffff',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'software_agency_preloader_border_color', array(
		'label'    => __('Pre-Loader Border Color', 'software-agency'),
		'section'  => 'software_agency_left_right',
	)));

	$wp_customize->add_setting('software_agency_preloader_bg_img',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'software_agency_preloader_bg_img',array(
        'label' => __('Preloader Background Image','software-agency'),
        'section' => 'software_agency_left_right'
	)));

	$wp_customize->add_setting('software_agency_bradcrumbs_alignment',array(
        'default' => 'Left',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_bradcrumbs_alignment',array(
        'type' => 'select',
        'label' => __('Bradcrumbs Alignment','software-agency'),
        'section' => 'software_agency_left_right',
        'choices' => array(
            'Left' => __('Left','software-agency'),
            'Right' => __('Right','software-agency'),
            'Center' => __('Center','software-agency'),
        ),
	) );

  //404 Page Setting
	$wp_customize->add_section('software_agency_404_page',array(
		'title'	=> __('404 Page Settings','software-agency'),
		'panel' => 'software_agency_other_parent_panel',
	));

	$wp_customize->add_setting('software_agency_404_page_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('software_agency_404_page_title',array(
		'label'	=> __('Add Title','software-agency'),
		'input_attrs' => array(
            'placeholder' => __( '404 Not Found', 'software-agency' ),
        ),
		'section'=> 'software_agency_404_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_404_page_content',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('software_agency_404_page_content',array(
		'label'	=> __('Add Text','software-agency'),
		'input_attrs' => array(
            'placeholder' => __( 'Looks like you have taken a wrong turn, Dont worry, it happens to the best of us.', 'software-agency' ),
        ),
		'section'=> 'software_agency_404_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_404_page_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_404_page_button_text',array(
		'label'	=> __('Add Button Text','software-agency'),
		'input_attrs' => array(
            'placeholder' => __( 'GO BACK', 'software-agency' ),
        ),
		'section'=> 'software_agency_404_page',
		'type'=> 'text'
	));

	//No Result Page Setting
	$wp_customize->add_section('software_agency_no_results_page',array(
		'title'	=> __('No Results Page Settings','software-agency'),
		'panel' => 'software_agency_other_parent_panel',
	));

	$wp_customize->add_setting('software_agency_no_results_page_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('software_agency_no_results_page_title',array(
		'label'	=> __('Add Title','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( 'Nothing Found', 'software-agency' ),
        ),
		'section'=> 'software_agency_no_results_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_no_results_page_content',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('software_agency_no_results_page_content',array(
		'label'	=> __('Add Text','software-agency'),
		'input_attrs' => array(
        'placeholder' => __( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'software-agency' ),
        ),
		'section'=> 'software_agency_no_results_page',
		'type'=> 'text'
	));

	//Social Icon Setting
	$wp_customize->add_section('software_agency_social_icon_settings',array(
		'title'	=> __('Social Icons Settings','software-agency'),
		'panel' => 'software_agency_other_parent_panel',
	));

	$wp_customize->add_setting('software_agency_social_icon_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_social_icon_font_size',array(
		'label'	=> __('Icon Font Size','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'software-agency' ),
        ),
		'section'=> 'software_agency_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_social_icon_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_social_icon_padding',array(
		'label'	=> __('Icon Padding','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'software-agency' ),
        ),
		'section'=> 'software_agency_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_social_icon_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_social_icon_width',array(
		'label'	=> __('Icon Width','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
    'placeholder' => __( '10px', 'software-agency' ),
        ),
		'section'=> 'software_agency_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_social_icon_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_social_icon_height',array(
		'label'	=> __('Icon Height','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'software-agency' ),
        ),
		'section'=> 'software_agency_social_icon_settings',
		'type'=> 'text'
	));

	//Responsive Media Settings
	$wp_customize->add_section('software_agency_responsive_media',array(
		'title'	=> esc_html__('Responsive Media','software-agency'),
		'panel' => 'software_agency_other_parent_panel',
	));

	$wp_customize->add_setting( 'software_agency_stickyheader_hide_show',array(
	    'default' => 0,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'software_agency_switch_sanitization'
  	));  
  	$wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_stickyheader_hide_show',array(
    'label' => esc_html__( 'Show / Hide Sticky Header','software-agency' ),
    'section' => 'software_agency_responsive_media'
  	)));

    $wp_customize->add_setting( 'software_agency_resp_slider_hide_show',array(
      	'default' => 1,
     	'transport' => 'refresh',
      	'sanitize_callback' => 'software_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_resp_slider_hide_show',array(
      	'label' => esc_html__( 'Show / Hide Slider','software-agency' ),
      	'section' => 'software_agency_responsive_media'
    )));

	$wp_customize->add_setting( 'software_agency_metabox_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_metabox_hide_show',array(
      	'label' => esc_html__( 'Show / Hide Metabox','software-agency' ),
      	'section' => 'software_agency_responsive_media'
    )));

    $wp_customize->add_setting( 'software_agency_sidebar_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_sidebar_hide_show',array(
      	'label' => esc_html__( 'Show / Hide Sidebar','software-agency' ),
      	'section' => 'software_agency_responsive_media'
    )));

   	$wp_customize->add_setting( 'software_agency_responsive_preloader_hide',array(
        'default' => false,
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_responsive_preloader_hide',array(
        'label' => esc_html__( 'Show / Hide Preloader','software-agency' ),
        'section' => 'software_agency_responsive_media'
    )));

    $wp_customize->add_setting( 'software_agency_resp_scroll_top_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_resp_scroll_top_hide_show',array(
      	'label' => esc_html__( 'Show / Hide Scroll To Top','software-agency' ),
      	'section' => 'software_agency_responsive_media'
    )));

    $wp_customize->add_setting('software_agency_resp_menu_toggle_btn_bg_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'software_agency_resp_menu_toggle_btn_bg_color', array(
		'label'    => __('Toggle Button Bg Color', 'software-agency'),
		'section'  => 'software_agency_responsive_media',
	)));

    $wp_customize->add_setting('software_agency_res_open_menu_icon',array(
		'default'	=> 'fas fa-bars',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_res_open_menu_icon',array(
		'label'	=> __('Add Open Menu Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_responsive_media',
		'setting'	=> 'software_agency_res_open_menu_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('software_agency_res_close_menu_icon',array(
		'default'	=> 'fas fa-times',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Software_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'software_agency_res_close_menu_icon',array(
		'label'	=> __('Add Close Menu Icon','software-agency'),
		'transport' => 'refresh',
		'section'	=> 'software_agency_responsive_media',
		'setting'	=> 'software_agency_res_close_menu_icon',
		'type'		=> 'icon'
	)));

    //Woocommerce settings
	$wp_customize->add_section('software_agency_woocommerce_section', array(
		'title'    => __('WooCommerce Layout', 'software-agency'),
		'priority' => null,
		'panel'    => 'woocommerce',
	));

    //Shop Page Featured Image
	$wp_customize->add_setting( 'software_agency_shop_featured_image_border_radius', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'software_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'software_agency_shop_featured_image_border_radius', array(
		'label'       => esc_html__( 'Shop Page Featured Image Border Radius','software-agency' ),
		'section'     => 'software_agency_woocommerce_section',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting( 'software_agency_shop_featured_image_box_shadow', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'software_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'software_agency_shop_featured_image_box_shadow', array(
		'label'       => esc_html__( 'Shop Page Featured Image Box Shadow','software-agency' ),
		'section'     => 'software_agency_woocommerce_section',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'software_agency_woocommerce_shop_page_sidebar', array( 'selector' => '.post-type-archive-product #sidebar',
		'render_callback' => 'software_agency_customize_partial_software_agency_woocommerce_shop_page_sidebar', ) );

    //Woocommerce Shop Page Sidebar
	$wp_customize->add_setting( 'software_agency_woocommerce_shop_page_sidebar',array(
		'default' => 0,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_woocommerce_shop_page_sidebar',array(
		'label' => esc_html__( 'Show / Hide Shop Page Sidebar','software-agency' ),
		'section' => 'software_agency_woocommerce_section'
    )));

    $wp_customize->add_setting('software_agency_shop_page_layout',array(
        'default' => 'Right Sidebar',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_shop_page_layout',array(
        'type' => 'select',
        'label' => __('Shop Page Sidebar Layout','software-agency'),
        'section' => 'software_agency_woocommerce_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','software-agency'),
            'Right Sidebar' => __('Right Sidebar','software-agency'),
        ),
	) );

    //Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'software_agency_woocommerce_single_product_page_sidebar', array( 'selector' => '.single-product #sidebar',
		'render_callback' => 'software_agency_customize_partial_software_agency_woocommerce_single_product_page_sidebar', ) );

    //Woocommerce Single Product page Sidebar
	$wp_customize->add_setting( 'software_agency_woocommerce_single_product_page_sidebar',array(
		'default' => 0,
		'transport' => 'refresh',
		'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_woocommerce_single_product_page_sidebar',array(
		'label' => esc_html__( 'Show / Hide Single Product Sidebar','software-agency' ),
		'section' => 'software_agency_woocommerce_section'
    )));

   $wp_customize->add_setting('software_agency_single_product_layout',array(
        'default' => 'Right Sidebar',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_single_product_layout',array(
        'type' => 'select',
        'label' => __('Single Product Sidebar Layout','software-agency'),
        'section' => 'software_agency_woocommerce_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','software-agency'),
            'Right Sidebar' => __('Right Sidebar','software-agency'),
        ),
	) );

	$wp_customize->add_setting('software_agency_products_btn_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_products_btn_padding_top_bottom',array(
		'label'	=> __('Products Button Padding Top Bottom','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'software-agency' ),
        ),
		'section'=> 'software_agency_woocommerce_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('software_agency_products_btn_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('software_agency_products_btn_padding_left_right',array(
		'label'	=> __('Products Button Padding Left Right','software-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','software-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'software-agency' ),
        ),
		'section'=> 'software_agency_woocommerce_section',
		'type'=> 'text'
	));

	//Products Sale Badge
	$wp_customize->add_setting('software_agency_woocommerce_sale_position',array(
        'default' => 'right',
        'sanitize_callback' => 'software_agency_sanitize_choices'
	));
	$wp_customize->add_control('software_agency_woocommerce_sale_position',array(
        'type' => 'select',
        'label' => __('Sale Badge Position','software-agency'),
        'section' => 'software_agency_woocommerce_section',
        'choices' => array(
            'left' => __('Left','software-agency'),
            'right' => __('Right','software-agency'),
        ),
	) );

  	// Related Product
    $wp_customize->add_setting( 'software_agency_related_product_show_hide',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'software_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Software_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'software_agency_related_product_show_hide',array(
        'label' => esc_html__( 'Show / Hide Related product','software-agency' ),
        'section' => 'software_agency_woocommerce_section'
    )));

    // Has to be at the top
	$wp_customize->register_panel_type( 'Software_Agency_WP_Customize_Panel' );
	$wp_customize->register_section_type( 'Software_Agency_WP_Customize_Section' );
}

add_action( 'customize_register', 'software_agency_customize_register' );

load_template( trailingslashit( get_template_directory() ) . '/inc/logo/logo-resizer.php' );

if ( class_exists( 'WP_Customize_Panel' ) ) {
  	class Software_Agency_WP_Customize_Panel extends WP_Customize_Panel {
	    public $panel;
	    public $type = 'software_agency_panel';
	    public function json() {
			$array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type', 'panel', ) );
			$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
			$array['content'] = $this->get_content();
			$array['active'] = $this->active();
			$array['instanceNumber'] = $this->instance_number;
			return $array;
    	}
  	}
}

if ( class_exists( 'WP_Customize_Section' ) ) {
  	class Software_Agency_WP_Customize_Section extends WP_Customize_Section {
	    public $section;
	    public $type = 'software_agency_section';
	    public function json() {
			$array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'panel', 'type', 'description_hidden', 'section', ) );
			$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
			$array['content'] = $this->get_content();
			$array['active'] = $this->active();
			$array['instanceNumber'] = $this->instance_number;

			if ( $this->panel ) {
			$array['customizeAction'] = sprintf( 'Customizing &#9656; %s', esc_html( $this->manager->get_panel( $this->panel )->title ) );
			} else {
			$array['customizeAction'] = 'Customizing';
			}
			return $array;
    	}
  	}
}

// Enqueue our scripts and styles
function software_agency_customize_controls_scripts() {
	wp_enqueue_script( 'software-agency-customizer-controls', get_theme_file_uri( '/assets/js/customizer-controls.js' ), array(), '1.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'software_agency_customize_controls_scripts' );

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class Software_Agency_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	*/
	public function sections( $manager ) {

		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/inc/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'Software_Agency_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section( new Software_Agency_Customize_Section_Pro( $manager,'software_agency_go_pro', array(
			'priority'   => 1,
			'title'    => esc_html__( 'SOFTWARE AGENCY PRO', 'software-agency' ),
			'pro_text' => esc_html__( 'UPGRADE PRO', 'software-agency' ),
			'pro_url'  => esc_url('https://www.vwthemes.com/products/software-company-wordpress-theme'),
		) )	);

		$manager->add_section(new Software_Agency_Customize_Section_Pro($manager,'software_agency_get_started_link',array(
			'priority'   => 1,
			'title'    => esc_html__( 'DOCUMENTATION', 'software-agency' ),
			'pro_text' => esc_html__( 'DOCS', 'software-agency' ),
			'pro_url'  => esc_url('https://preview.vwthemesdemo.com/docs/free-software-agency/'),
		)));
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'software-agency-customize-controls', trailingslashit( get_template_directory_uri() ) . '/assets/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'software-agency-customize-controls', trailingslashit( get_template_directory_uri() ) . '/assets/css/customize-controls.css' );

		wp_localize_script(
		'software-agency-customize-controls',
		'software_agency_customizer_params',
		array(
			'ajaxurl' =>	admin_url( 'admin-ajax.php' )
		));
	}
}

// Doing this customizer thang!
Software_Agency_Customize::get_instance();
