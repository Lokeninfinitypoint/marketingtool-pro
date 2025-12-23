<?php
/**
 * Marketing Agency Theme Customizer
 *
 * @package Marketing Agency
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

function marketing_agency_custom_controls() {
	load_template( trailingslashit( get_template_directory() ) . '/inc/custom-controls.php' );
}
add_action( 'customize_register', 'marketing_agency_custom_controls' );

function marketing_agency_customize_register( $wp_customize ) {

	load_template( trailingslashit( get_template_directory() ) . '/inc/icon-picker.php' );

	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage'; 
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'blogname', array( 
		'selector' => '.logo .site-title a', 
	 	'render_callback' => 'marketing_agency_customize_partial_blogname',
	)); 

	$wp_customize->selective_refresh->add_partial( 'blogdescription', array( 
		'selector' => 'p.site-description', 
		'render_callback' => 'marketing_agency_customize_partial_blogdescription',
	));

	//add home page setting pannel
	$marketing_agency_parent_panel = new Marketing_Agency_WP_Customize_Panel( $wp_customize, 'marketing_agency_panel_id', array(
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title' => esc_html__( 'Homepage Settings', 'marketing-agency' ),
		'priority' => 10,
	));

	//Top Header
	$wp_customize->add_section( 'marketing_agency_top_header' , array(
    	'title' => esc_html__( 'Top Header', 'marketing-agency' ),
		'panel' => 'marketing_agency_panel_id'
	) );

	$wp_customize->add_setting( 'marketing_agency_header_first_color', array(
	    'default' => '#5d17df',
	    'sanitize_callback' => 'sanitize_hex_color'
  	));
  	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'marketing_agency_header_first_color', array(
  		'label' => __('Header First Color Option', 'marketing-agency'),
	    'section' => 'header_image',
	    'settings' => 'marketing_agency_header_first_color',
  	)));

  	$wp_customize->add_setting( 'marketing_agency_header_second_color', array(
	    'default' => '#0985f9',
	    'sanitize_callback' => 'sanitize_hex_color'
  	));
  	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'marketing_agency_header_second_color', array(
  		'label' => __('Header Second Color Option', 'marketing-agency'),
	    'section' => 'header_image',
	    'settings' => 'marketing_agency_header_second_color',
  	))); 

	$wp_customize->add_setting('marketing_agency_header_img_position',array(
	  'default' => 'center top',
	  'transport' => 'refresh',
	  'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_header_img_position',array(
		'type' => 'select',
		'label' => __('Header Image Position','marketing-agency'),
		'section' => 'header_image',
		'choices' 	=> array(
			'left top' 		=> esc_html__( 'Top Left', 'marketing-agency' ),
			'center top'   => esc_html__( 'Top', 'marketing-agency' ),
			'right top'   => esc_html__( 'Top Right', 'marketing-agency' ),
			'left center'   => esc_html__( 'Left', 'marketing-agency' ),
			'center center'   => esc_html__( 'Center', 'marketing-agency' ),
			'right center'   => esc_html__( 'Right', 'marketing-agency' ),
			'left bottom'   => esc_html__( 'Bottom Left', 'marketing-agency' ),
			'center bottom'   => esc_html__( 'Bottom', 'marketing-agency' ),
			'right bottom'   => esc_html__( 'Bottom Right', 'marketing-agency' ),
		),
	));

	//Sticky Header

	$wp_customize->add_setting( 'marketing_agency_hide_show_topbar_section',array(
	  	'default' => 1,
	  	'transport' => 'refresh',
	  	'sanitize_callback' => 'marketing_agency_switch_sanitization'
  	));
  	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_hide_show_topbar_section',array(
	  	'label' => esc_html__( 'Show / Hide Topbar','marketing-agency' ),
	  	'section' => 'marketing_agency_top_header',
  	)));

	$wp_customize->add_setting( 'marketing_agency_sticky_header',array(
	    'default' => 0,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'marketing_agency_switch_sanitization'
  	) );
  	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_sticky_header',array(
	    'label' => esc_html__( 'Sticky Header','marketing-agency' ),
	    'section' => 'marketing_agency_top_header'
  	)));

	$wp_customize->add_setting('marketing_agency_email_address_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('marketing_agency_email_address_text',array(
		'label'	=> esc_html__('Email Text','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => esc_html__( 'Mail', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_top_header',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_email_address',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_email'
	));	
	$wp_customize->add_control('marketing_agency_email_address',array(
		'label'	=> esc_html__('Email Address','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => esc_html__( 'support@email.com', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_top_header',
		'type'=> 'text'
	));

    $wp_customize->add_setting('marketing_agency_email_icon',array(
		'default'	=> 'far fa-envelope',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new marketing_agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_email_icon',array(
		'label'	=> __('Add Email Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_top_header',
		'setting'	=> 'marketing_agency_email_icon',
		'type'		=> 'icon'
	)));
	
	$wp_customize->add_setting('marketing_agency_phone_number_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('marketing_agency_phone_number_text',array(
		'label'	=> esc_html__('Phone Text','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => esc_html__( 'Phone', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_top_header',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_phone_number',array(
		'default'=> '',
		'sanitize_callback'	=> 'marketing_agency_sanitize_phone_number'
	));	
	$wp_customize->add_control('marketing_agency_phone_number',array(
		'label'	=> esc_html__('Phone Number','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => esc_html__( '+00 123 456 7890', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_top_header',
		'type'=> 'text'
	));

    $wp_customize->add_setting('marketing_agency_phone_icon',array(
		'default'	=> 'fas fa-phone',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new marketing_agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_phone_icon',array(
		'label'	=> __('Add Phone Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_top_header',
		'setting'	=> 'marketing_agency_phone_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('marketing_agency_getstarted_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('marketing_agency_getstarted_text',array(
		'label'	=> esc_html__('Button Text','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => esc_html__( 'GET STARTED', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_top_header',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_getstarted_link',array(
		'default'=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));	
	$wp_customize->add_control('marketing_agency_getstarted_link',array(
		'label'	=> esc_html__('Button Link','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => esc_html__( 'https://example.com/page', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_top_header',
		'type'=> 'url'
	));

	$wp_customize->add_setting( 'marketing_agency_header_search',array(
    	'default' => 1,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new marketing_agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_header_search',array(
      	'label' => esc_html__( 'Show / Hide Search','marketing-agency' ),
      	'section' => 'marketing_agency_top_header'
    )));

    $wp_customize->add_setting('marketing_agency_search_icon',array(
		'default'	=> 'fas fa-search',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new marketing_agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_search_icon',array(
		'label'	=> __('Add Search Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_top_header',
		'setting'	=> 'marketing_agency_search_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('marketing_agency_search_close_icon',array(
		'default'	=> 'fa fa-window-close',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new marketing_agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_search_close_icon',array(
		'label'	=> __('Add Search Close Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_top_header',
		'setting'	=> 'marketing_agency_search_close_icon',
		'type'		=> 'icon'
	)));

	//Menus Settings
	$wp_customize->add_section( 'marketing_agency_menu_section' , array(
    	'title' => __( 'Menus Settings', 'marketing-agency' ),
		'panel' => 'marketing_agency_panel_id'
	) );

	$wp_customize->add_setting('marketing_agency_navigation_menu_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_navigation_menu_font_size',array(
		'label'	=> __('Menus Font Size','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_menu_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_navigation_menu_font_weight',array(
        'default' => 600,
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_navigation_menu_font_weight',array(
        'type' => 'select',
        'label' => __('Menus Font Weight','marketing-agency'),
        'section' => 'marketing_agency_menu_section',
        'choices' => array(
        	'100' => __('100','marketing-agency'),
            '200' => __('200','marketing-agency'),
            '300' => __('300','marketing-agency'),
            '400' => __('400','marketing-agency'),
            '500' => __('500','marketing-agency'),
            '600' => __('600','marketing-agency'),
            '700' => __('700','marketing-agency'),
            '800' => __('800','marketing-agency'),
            '900' => __('900','marketing-agency'),
        ),
	) );

	// text trasform
	$wp_customize->add_setting('marketing_agency_menu_text_transform',array(
		'default'=> 'Capitalize',
		'sanitize_callback'	=> 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_menu_text_transform',array(
		'type' => 'radio',
		'label'	=> __('Menus Text Transform','marketing-agency'),
		'choices' => array(
            'Uppercase' => __('Uppercase','marketing-agency'),
            'Capitalize' => __('Capitalize','marketing-agency'),
            'Lowercase' => __('Lowercase','marketing-agency'),
        ),
		'section'=> 'marketing_agency_menu_section',
	));

	$wp_customize->add_setting('marketing_agency_menus_item_style',array(
        'default' => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_menus_item_style',array(
        'type' => 'select',
        'section' => 'marketing_agency_menu_section',
		'label' => __('Menu Item Hover Style','marketing-agency'),
		'choices' => array(
            'None' => __('None','marketing-agency'),
            'Zoom In' => __('Zoom In','marketing-agency'),
        ),
	) );

	$wp_customize->add_setting('marketing_agency_header_menus_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'marketing_agency_header_menus_color', array(
		'label'    => __('Menus Color', 'marketing-agency'),
		'section'  => 'marketing_agency_menu_section',
	)));

	$wp_customize->add_setting('marketing_agency_header_menus_hover_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'marketing_agency_header_menus_hover_color', array(
		'label'    => __('Menus Hover Color', 'marketing-agency'),
		'section'  => 'marketing_agency_menu_section',
	)));

	$wp_customize->add_setting('marketing_agency_header_submenus_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'marketing_agency_header_submenus_color', array(
		'label'    => __('Sub Menus Color', 'marketing-agency'),
		'section'  => 'marketing_agency_menu_section',
	)));

	$wp_customize->add_setting('marketing_agency_header_submenus_hover_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'marketing_agency_header_submenus_hover_color', array(
		'label'    => __('Sub Menus Hover Color', 'marketing-agency'),
		'section'  => 'marketing_agency_menu_section',
	)));

	//Social links
	$wp_customize->add_section(
		'marketing_agency_social_links', array(
			'title'		=>	__('Social Links', 'marketing-agency'),
			'priority'	=>	null,
			'panel'		=>	'marketing_agency_panel_id'
		));

	$wp_customize->add_setting('marketing_agency_social_icons',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_social_icons',array(
		'label' =>  __('Steps to setup social icons','marketing-agency'),
		'description' => __('<p>1. Go to Dashboard >> Appearance >> Widgets</p>
			<p>2. Add Vw Social Icon Widget in Social Media.</p>
			<p>3. Add social icons url and save.</p>','marketing-agency'),
		'section'=> 'marketing_agency_social_links',
		'type'=> 'hidden'
	));
	$wp_customize->add_setting('marketing_agency_social_icon_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_social_icon_btn',array(
		'description' => "<a target='_blank' href='". admin_url('widgets.php') ." '>Setup Social Icons</a>",
		'section'=> 'marketing_agency_social_links',
		'type'=> 'hidden'
	));

	//Slider
	$wp_customize->add_section( 'marketing_agency_slidersettings' , array(
    	'title' => esc_html__( 'Slider Settings', 'marketing-agency' ),
    	'description' => __('Free theme has 3 slides options, For unlimited slides and more options <a class="go-pro-btn" target="blank" href="https://www.vwthemes.com/products/marketing-agency-wordpress-theme">GET PRO</a>','marketing-agency'),
		'panel' => 'marketing_agency_panel_id'
	) );

    //Selective Refresh
    $wp_customize->selective_refresh->add_partial('marketing_agency_slider_arrows',array(
		'selector'        => '#slider .carousel-caption h1',
		'render_callback' => 'marketing_agency_customize_partial_marketing_agency_slider_arrows',
	));

	$wp_customize->add_setting( 'marketing_agency_slider_arrows',array(
    	'default' => 1,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ));  
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_slider_arrows',array(
      	'label' => esc_html__( 'Show / Hide Slider','marketing-agency' ),
      	'section' => 'marketing_agency_slidersettings'
    )));

    $wp_customize->add_setting('marketing_agency_slider_type',array(
        'default' => 'Default slider',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	) );
	$wp_customize->add_control('marketing_agency_slider_type', array(
        'type' => 'select',
        'label' => __('Slider Type','marketing-agency'),
        'section' => 'marketing_agency_slidersettings',
        'choices' => array(
            'Default slider' => __('Default slider','marketing-agency'),
            'Advance slider' => __('Advance slider','marketing-agency'),
        ),
	));

	$wp_customize->add_setting('marketing_agency_advance_slider_shortcode',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_advance_slider_shortcode',array(
		'label'	=> __('Add Slider Shortcode','marketing-agency'),
		'section'=> 'marketing_agency_slidersettings',
		'type'=> 'text',
		'active_callback' => 'marketing_agency_advance_slider'
	));

	$wp_customize->add_setting('marketing_agency_slider_image',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'marketing_agency_slider_image',array(
        'label' => __('Slider Background Image','marketing-agency'),
        'description' => esc_html__('Slider image size (1400 x 600)','marketing-agency'),
        'section' => 'marketing_agency_slidersettings',
        'active_callback' => 'marketing_agency_default_slider'
	)));

	for ( $count = 1; $count <= 3; $count++ ) {
		$wp_customize->add_setting( 'marketing_agency_slider_page' . $count, array(
			'default'  => '',
			'sanitize_callback' => 'marketing_agency_sanitize_dropdown_pages'
		) );
		$wp_customize->add_control( 'marketing_agency_slider_page' . $count, array(
			'label'    => esc_html__( 'Select Slider Page', 'marketing-agency' ),
			'description' => esc_html__('Slider image size (500 x 500)','marketing-agency'),
			'section'  => 'marketing_agency_slidersettings',
			'type'     => 'dropdown-pages',
			'active_callback' => 'marketing_agency_default_slider'
		) );
	}

	$wp_customize->add_setting('marketing_agency_banner_button_label',array(
		'default' => 'GET STARTED',
		'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_banner_button_label',array(
		'label' => __('Button','marketing-agency'),
		'section' => 'marketing_agency_slidersettings',
		'setting' => 'marketing_agency_banner_button_label',
		'type' => 'text',
		'active_callback' => 'marketing_agency_default_slider'
	));

	$wp_customize->add_setting('marketing_agency_top_button_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('marketing_agency_top_button_url',array(
		'label'	=> __('Add Button URL','marketing-agency'),
		'section'	=> 'marketing_agency_slidersettings',
		'setting'	=> 'marketing_agency_top_button_url',
		'type'	=> 'url',
		'active_callback' => 'marketing_agency_default_slider'
	));

	//content layout
	$wp_customize->add_setting('marketing_agency_slider_content_option',array(
        'default' => 'Left',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control(new Marketing_Agency_Image_Radio_Control($wp_customize, 'marketing_agency_slider_content_option', array(
        'type' => 'select',
        'label' => esc_html__('Slider Content Layouts','marketing-agency'),
        'section' => 'marketing_agency_slidersettings',
        'choices' => array(
            'Left' => esc_url(get_template_directory_uri()).'/assets/images/slider-content1.png',
            'Center' => esc_url(get_template_directory_uri()).'/assets/images/slider-content2.png',
            'Right' => esc_url(get_template_directory_uri()).'/assets/images/slider-content3.png',
    ),'active_callback' => 'marketing_agency_default_slider'
    )));

    //Slider content padding
    $wp_customize->add_setting('marketing_agency_slider_content_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_slider_content_padding_top_bottom',array(
		'label'	=> __('Slider Content Padding Top Bottom','marketing-agency'),
		'description'	=> __('Enter a value in %. Example:20%','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '50%', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_slidersettings',
		'type'=> 'text',
		'active_callback' => 'marketing_agency_default_slider'
	));

	$wp_customize->add_setting('marketing_agency_slider_content_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_slider_content_padding_left_right',array(
		'label'	=> __('Slider Content Padding Left Right','marketing-agency'),
		'description'	=> __('Enter a value in %. Example:20%','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '50%', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_slidersettings',
		'type'=> 'text',
		'active_callback' => 'marketing_agency_default_slider'
	));

    //Slider excerpt
	$wp_customize->add_setting( 'marketing_agency_slider_excerpt_number', array(
		'default'              => 25,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'marketing_agency_slider_excerpt_number', array(
		'label'       => esc_html__( 'Slider Excerpt length','marketing-agency' ),
		'section'     => 'marketing_agency_slidersettings',
		'type'        => 'range',
		'settings'    => 'marketing_agency_slider_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),'active_callback' => 'marketing_agency_default_slider'
	) );

	$wp_customize->add_setting( 'marketing_agency_slider_speed', array(
		'default'  => 3000,
		'sanitize_callback'	=> 'marketing_agency_sanitize_float'
	) );
	$wp_customize->add_control( 'marketing_agency_slider_speed', array(
		'label' => esc_html__('Slider Transition Speed','marketing-agency'),
		'section' => 'marketing_agency_slidersettings',
		'type'  => 'number',
		'active_callback' => 'marketing_agency_default_slider'
	) );

	//About Section
	$wp_customize->add_section('marketing_agency_about', array(
		'title'       => __('About Section', 'marketing-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','marketing-agency'),
		'priority'    => null,
		'panel'       => 'marketing_agency_panel_id',
	));

	$wp_customize->add_setting('marketing_agency_about_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_about_text',array(
		'description' => __('<p>1. More options for about section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for about section.</p>','marketing-agency'),
		'section'=> 'marketing_agency_about',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('marketing_agency_about_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_about_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=marketing_agency_guide') ." '>More Info</a>",
		'section'=> 'marketing_agency_about',
		'type'=> 'hidden'
	));
 
	//Services
	$wp_customize->add_section('marketing_agency_services',array(
		'title'	=> __('Services Section','marketing-agency'),
		'description' => __('For more options of services section <br/><a class="go-pro-btn" target="blank" href="https://www.vwthemes.com/products/marketing-agency-wordpress-theme">GET PRO</a>','marketing-agency'),
		'panel' => 'marketing_agency_panel_id',
	));

	$wp_customize->add_setting('marketing_agency_section_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('marketing_agency_section_title',array(
		'label'	=> __('Section Title','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( 'Lorem ipsum dolor sit amet, ', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_services',
		'type'=> 'text'
	));	

	$categories = get_categories();
		$cat_posts = array();
			$i = 0;
			$cat_posts[]='Select';	
		foreach($categories as $category){
			if($i==0){
			$default = $category->slug;
			$i++;
		}
		$cat_posts[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('marketing_agency_services_category',array(
		'default'	=> 'select',
		'sanitize_callback' => 'marketing_agency_sanitize_choices',
	));
	$wp_customize->add_control('marketing_agency_services_category',array(
		'type'    => 'select',
		'choices' => $cat_posts,
		'label' => __('Select Category to display Latest Post','marketing-agency'),		
		'section' => 'marketing_agency_services',
	));

	//Services excerpt
	$wp_customize->add_setting( 'marketing_agency_services_excerpt_number', array(
		'default'              => 20,
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'marketing_agency_services_excerpt_number', array(
		'label'       => esc_html__( 'Services Excerpt length','marketing-agency' ),
		'section'     => 'marketing_agency_services',
		'type'        => 'range',
		'settings'    => 'marketing_agency_services_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	//Promotion Section
	$wp_customize->add_section('marketing_agency_promotion', array(
		'title'       => __('Promotion Section', 'marketing-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','marketing-agency'),
		'priority'    => null,
		'panel'       => 'marketing_agency_panel_id',
	));

	$wp_customize->add_setting('marketing_agency_promotion_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_promotion_text',array(
		'description' => __('<p>1. More options for promotion section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for promotion section.</p>','marketing-agency'),
		'section'=> 'marketing_agency_promotion',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('marketing_agency_promotion_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_promotion_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=marketing_agency_guide') ." '>More Info</a>",
		'section'=> 'marketing_agency_promotion',
		'type'=> 'hidden'
	));

	//Features Section
	$wp_customize->add_section('marketing_agency_features', array(
		'title'       => __('Features Section', 'marketing-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','marketing-agency'),
		'priority'    => null,
		'panel'       => 'marketing_agency_panel_id',
	));

	$wp_customize->add_setting('marketing_agency_features_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_features_text',array(
		'description' => __('<p>1. More options for features section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for features section.</p>','marketing-agency'),
		'section'=> 'marketing_agency_features',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('marketing_agency_features_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_features_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=marketing_agency_guide') ." '>More Info</a>",
		'section'=> 'marketing_agency_features',
		'type'=> 'hidden'
	));

	//Case Studies Section
	$wp_customize->add_section('marketing_agency_case_studies', array(
		'title'       => __('Case Studies Section', 'marketing-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','marketing-agency'),
		'priority'    => null,
		'panel'       => 'marketing_agency_panel_id',
	));

	$wp_customize->add_setting('marketing_agency_case_studies_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_case_studies_text',array(
		'description' => __('<p>1. More options for case studies section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for case studies section.</p>','marketing-agency'),
		'section'=> 'marketing_agency_case_studies',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('marketing_agency_case_studies_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_case_studies_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=marketing_agency_guide') ." '>More Info</a>",
		'section'=> 'marketing_agency_case_studies',
		'type'=> 'hidden'
	));

	//Progress Tips Section
	$wp_customize->add_section('marketing_agency_progress_tips', array(
		'title'       => __('Progress Tips Section', 'marketing-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','marketing-agency'),
		'priority'    => null,
		'panel'       => 'marketing_agency_panel_id',
	));

	$wp_customize->add_setting('marketing_agency_progress_tips_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_progress_tips_text',array(
		'description' => __('<p>1. More options for progress tips section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for progress tips section.</p>','marketing-agency'),
		'section'=> 'marketing_agency_progress_tips',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('marketing_agency_progress_tips_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_progress_tips_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=marketing_agency_guide') ." '>More Info</a>",
		'section'=> 'marketing_agency_progress_tips',
		'type'=> 'hidden'
	));

	//our marketing Section
	$wp_customize->add_section('marketing_agency_our_marketing', array(
		'title'       => __('Our Marketing Section', 'marketing-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','marketing-agency'),
		'priority'    => null,
		'panel'       => 'marketing_agency_panel_id',
	));

	$wp_customize->add_setting('marketing_agency_our_marketing_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_our_marketing_text',array(
		'description' => __('<p>1. More options for our marketing section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for our marketing section.</p>','marketing-agency'),
		'section'=> 'marketing_agency_our_marketing',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('marketing_agency_our_marketing_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_our_marketing_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=marketing_agency_guide') ." '>More Info</a>",
		'section'=> 'marketing_agency_our_marketing',
		'type'=> 'hidden'
	));

	//pricing plan Section
	$wp_customize->add_section('marketing_agency_pricing_plan', array(
		'title'       => __('Pricing Plan Section', 'marketing-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','marketing-agency'),
		'priority'    => null,
		'panel'       => 'marketing_agency_panel_id',
	));

	$wp_customize->add_setting('marketing_agency_pricing_plan_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_pricing_plan_text',array(
		'description' => __('<p>1. More options for pricing plan section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for pricing plan section.</p>','marketing-agency'),
		'section'=> 'marketing_agency_pricing_plan',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('marketing_agency_pricing_plan_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_pricing_plan_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=marketing_agency_guide') ." '>More Info</a>",
		'section'=> 'marketing_agency_pricing_plan',
		'type'=> 'hidden'
	));

	//Team Section
	$wp_customize->add_section('marketing_agency_team', array(
		'title'       => __('Team Section', 'marketing-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','marketing-agency'),
		'priority'    => null,
		'panel'       => 'marketing_agency_panel_id',
	));

	$wp_customize->add_setting('marketing_agency_team_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_team_text',array(
		'description' => __('<p>1. More options for team section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for team section.</p>','marketing-agency'),
		'section'=> 'marketing_agency_team',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('marketing_agency_team_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_team_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=marketing_agency_guide') ." '>More Info</a>",
		'section'=> 'marketing_agency_team',
		'type'=> 'hidden'
	));

	//Video Section
	$wp_customize->add_section('marketing_agency_video', array(
		'title'       => __('Video Section', 'marketing-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','marketing-agency'),
		'priority'    => null,
		'panel'       => 'marketing_agency_panel_id',
	));

	$wp_customize->add_setting('marketing_agency_video_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_video_text',array(
		'description' => __('<p>1. More options for video section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for video section.</p>','marketing-agency'),
		'section'=> 'marketing_agency_video',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('marketing_agency_video_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_video_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=marketing_agency_guide') ." '>More Info</a>",
		'section'=> 'marketing_agency_video',
		'type'=> 'hidden'
	));

	//blog Section
	$wp_customize->add_section('marketing_agency_blog', array(
		'title'       => __('Blog Section', 'marketing-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','marketing-agency'),
		'priority'    => null,
		'panel'       => 'marketing_agency_panel_id',
	));

	$wp_customize->add_setting('marketing_agency_blog_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_blog_text',array(
		'description' => __('<p>1. More options for blog section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for blog section.</p>','marketing-agency'),
		'section'=> 'marketing_agency_blog',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('marketing_agency_blog_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_blog_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=marketing_agency_guide') ." '>More Info</a>",
		'section'=> 'marketing_agency_blog',
		'type'=> 'hidden'
	));

	//home contact Section
	$wp_customize->add_section('marketing_agency_home_contact', array(
		'title'       => __('Home Contact Section', 'marketing-agency'),
		'description' => __('<p class="premium-opt">Premium Theme Features</p>','marketing-agency'),
		'priority'    => null,
		'panel'       => 'marketing_agency_panel_id',
	));

	$wp_customize->add_setting('marketing_agency_home_contact_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_home_contact_text',array(
		'description' => __('<p>1. More options for home contact section.</p>
			<p>2. Unlimited images options.</p>
			<p>3. Color options for home contact section.</p>','marketing-agency'),
		'section'=> 'marketing_agency_home_contact',
		'type'=> 'hidden'
	));

	$wp_customize->add_setting('marketing_agency_home_contact_btn',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_home_contact_btn',array(
		'description' => "<a class='go-pro' target='_blank' href='". admin_url('themes.php?page=marketing_agency_guide') ." '>More Info</a>",
		'section'=> 'marketing_agency_home_contact',
		'type'=> 'hidden'
	));

	//Footer Text
	$wp_customize->add_section('marketing_agency_footer',array(
		'title'	=> esc_html__('Footer Settings','marketing-agency'),
		'description' => __('For more options of footer section <br/><a class="go-pro-btn" target="blank" href="https://www.vwthemes.com/products/marketing-agency-wordpress-theme">GET PRO</a>','marketing-agency'),
		'panel' => 'marketing_agency_panel_id',
	));	

	$wp_customize->add_setting( 'marketing_agency_footer_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new marketing_agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_footer_hide_show',array(
      'label' => esc_html__( 'Show / Hide Footer','marketing-agency' ),
      'section' => 'marketing_agency_footer'
    )));

 	// font size
	$wp_customize->add_setting('marketing_agency_button_footer_font_size',array(
		'default'=> 25,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_button_footer_font_size',array(
		'label'	=> __('Footer Heading Font Size','marketing-agency'),
  		'type'        => 'number',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
		'section'=> 'marketing_agency_footer',
	));

	$wp_customize->add_setting('marketing_agency_button_footer_heading_letter_spacing',array(
		'default'=> 1,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_button_footer_heading_letter_spacing',array(
		'label'	=> __('Heading Letter Spacing','marketing-agency'),
  		'type'        => 'number',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
	),
		'section'=> 'marketing_agency_footer',
	));

	// text trasform
	$wp_customize->add_setting('marketing_agency_button_footer_text_transform',array(
		'default'=> 'Capitalize',
		'sanitize_callback'	=> 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_button_footer_text_transform',array(
		'type' => 'radio',
		'label'	=> __('Heading Text Transform','marketing-agency'),
		'choices' => array(
			'Uppercase' => __('Uppercase','marketing-agency'),
			'Capitalize' => __('Capitalize','marketing-agency'),
			'Lowercase' => __('Lowercase','marketing-agency'),
		),
		'section'=> 'marketing_agency_footer',
	));

	$wp_customize->add_setting('marketing_agency_footer_heading_weight',array(
        'default' => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_footer_heading_weight',array(
        'type' => 'select',
        'label' => __('Heading Font Weight','marketing-agency'),
        'section' => 'marketing_agency_footer',
        'choices' => array(
        	'100' => __('100','marketing-agency'),
            '200' => __('200','marketing-agency'),
            '300' => __('300','marketing-agency'),
            '400' => __('400','marketing-agency'),
            '500' => __('500','marketing-agency'),
            '600' => __('600','marketing-agency'),
            '700' => __('700','marketing-agency'),
            '800' => __('800','marketing-agency'),
            '900' => __('900','marketing-agency'),
        ),
	) );

  	$wp_customize->add_setting('marketing_agency_footer_template',array(
	    'default'	=> esc_html('marketing-agency-footer-one'),
	    'sanitize_callback'	=> 'marketing_agency_sanitize_choices'
  	));
 	$wp_customize->add_control('marketing_agency_footer_template',array(
        'label'	=> esc_html__('Footer style','marketing-agency'),
        'section'	=> 'marketing_agency_footer',
        'setting'	=> 'marketing_agency_footer_template',
        'type' => 'select',
        'choices' => array(
            'marketing-agency-footer-one' => esc_html__('Style 1', 'marketing-agency'),
            'marketing-agency-footer-two' => esc_html__('Style 2', 'marketing-agency'),
            'marketing-agency-footer-three' => esc_html__('Style 3', 'marketing-agency'),
            'marketing-agency-footer-four' => esc_html__('Style 4', 'marketing-agency'),
            'marketing-agency-footer-five' => esc_html__('Style 5', 'marketing-agency'),
        )
  	));

	$wp_customize->add_setting('marketing_agency_footer_background_color', array(
		'default'           => '#333333',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'marketing_agency_footer_background_color', array(
		'label'    => __('Footer Background Color', 'marketing-agency'),
		'section'  => 'marketing_agency_footer',
	)));

	$wp_customize->add_setting('marketing_agency_footer_background_image',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'marketing_agency_footer_background_image',array(
        'label' => __('Footer Background Image','marketing-agency'),
        'section' => 'marketing_agency_footer'
	)));

	$wp_customize->add_setting('marketing_agency_footer_img_position',array(
	  'default' => 'center center',
	  'transport' => 'refresh',
	  'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_footer_img_position',array(
		'type' => 'select',
		'label' => __('Footer Image Position','marketing-agency'),
		'section' => 'marketing_agency_footer',
		'choices' 	=> array(
			'left top' 		=> esc_html__( 'Top Left', 'marketing-agency' ),
			'center top'   => esc_html__( 'Top', 'marketing-agency' ),
			'right top'   => esc_html__( 'Top Right', 'marketing-agency' ),
			'left center'   => esc_html__( 'Left', 'marketing-agency' ),
			'center center'   => esc_html__( 'Center', 'marketing-agency' ),
			'right center'   => esc_html__( 'Right', 'marketing-agency' ),
			'left bottom'   => esc_html__( 'Bottom Left', 'marketing-agency' ),
			'center bottom'   => esc_html__( 'Bottom', 'marketing-agency' ),
			'right bottom'   => esc_html__( 'Bottom Right', 'marketing-agency' ),
		),
	)); 

	// Footer
	$wp_customize->add_setting('marketing_agency_img_footer',array(
		'default'=> 'scroll',
		'sanitize_callback'	=> 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_img_footer',array(
		'type' => 'select',
		'label'	=> __('Footer Background Attatchment','marketing-agency'),
		'choices' => array(
            'fixed' => __('fixed','marketing-agency'),
            'scroll' => __('scroll','marketing-agency'),
        ),
		'section'=> 'marketing_agency_footer',
	));

	$wp_customize->add_setting('marketing_agency_footer_widgets_heading',array(
        'default' => 'Left',
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_footer_widgets_heading',array(
        'type' => 'select',
        'label' => __('Footer Widget Heading','marketing-agency'),
        'section' => 'marketing_agency_footer',
        'choices' => array(
        	'Left' => __('Left','marketing-agency'),
            'Center' => __('Center','marketing-agency'),
            'Right' => __('Right','marketing-agency')
        ),
	) );

	$wp_customize->add_setting('marketing_agency_footer_widgets_content',array(
        'default' => 'Left',
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_footer_widgets_content',array(
        'type' => 'select',
        'label' => __('Footer Widget Content','marketing-agency'),
        'section' => 'marketing_agency_footer',
        'choices' => array(
        	'Left' => __('Left','marketing-agency'),
            'Center' => __('Center','marketing-agency'),
            'Right' => __('Right','marketing-agency')
        ),
	) );

	// footer padding
	$wp_customize->add_setting('marketing_agency_footer_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_footer_padding',array(
		'label'	=> __('Footer Top Bottom Padding','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
      'placeholder' => __( '10px', 'marketing-agency' ),
    ),
		'section'=> 'marketing_agency_footer',
		'type'=> 'text'
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('marketing_agency_footer_text', array( 
		'selector' => '.copyright p', 
		'render_callback' => 'marketing_agency_customize_partial_marketing_agency_footer_text', 
	));

	$wp_customize->add_setting( 'marketing_agency_copyright_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new marketing_agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_copyright_hide_show',array(
      'label' => esc_html__( 'Show / Hide Copyright','marketing-agency' ),
      'section' => 'marketing_agency_footer'
    )));

	$wp_customize->add_setting( 'marketing_agency_copyright_first_color', array(
	    'default' => '#5d17df',
	    'sanitize_callback' => 'sanitize_hex_color'
  	));
  	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'marketing_agency_copyright_first_color', array(
  		'label' => __('Copyright First Color Option', 'marketing-agency'),
	    'section' => 'marketing_agency_footer',
	    'settings' => 'marketing_agency_copyright_first_color',
  	)));

  	$wp_customize->add_setting( 'marketing_agency_copyright_second_color', array(
	    'default' => '#0985f9',
	    'sanitize_callback' => 'sanitize_hex_color'
  	));
  	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'marketing_agency_copyright_second_color', array(
  		'label' => __('Copyright Second Color Option', 'marketing-agency'),
	    'section' => 'marketing_agency_footer',
	    'settings' => 'marketing_agency_copyright_second_color',
  	))); 
	
	$wp_customize->add_setting('marketing_agency_footer_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('marketing_agency_footer_text',array(
		'label'	=> esc_html__('Copyright Text','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => esc_html__( 'Copyright 2020, .....', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_copyright_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_copyright_font_size',array(
		'label'	=> __('Copyright Font Size','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_copyright_alignment',array(
        'default' => 'center',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control(new Marketing_Agency_Image_Radio_Control($wp_customize, 'marketing_agency_copyright_alignment', array(
        'type' => 'select',
        'label' => esc_html__('Copyright Alignment','marketing-agency'),
        'section' => 'marketing_agency_footer',
        'settings' => 'marketing_agency_copyright_alignment',
        'choices' => array(
            'left' => esc_url(get_template_directory_uri()).'/assets/images/copyright1.png',
            'center' => esc_url(get_template_directory_uri()).'/assets/images/copyright2.png',
            'right' => esc_url(get_template_directory_uri()).'/assets/images/copyright3.png'
    ))));

	$wp_customize->add_setting( 'marketing_agency_footer_scroll',array(
    	'default' => 1,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ));  
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_footer_scroll',array(
      	'label' => esc_html__( 'Show / Hide Scroll to Top','marketing-agency' ),
      	'section' => 'marketing_agency_footer'
    )));

    //Selective Refresh
	$wp_customize->selective_refresh->add_partial('marketing_agency_scroll_to_top_icon', array( 
		'selector' => '.scrollup i', 
		'render_callback' => 'marketing_agency_customize_partial_marketing_agency_scroll_to_top_icon', 
	));

    $wp_customize->add_setting('marketing_agency_scroll_to_top_icon',array(
		'default'	=> 'fas fa-long-arrow-alt-up',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_scroll_to_top_icon',array(
		'label'	=> __('Add Scroll to Top Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_footer',
		'setting'	=> 'marketing_agency_scroll_to_top_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('marketing_agency_scroll_to_top_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_scroll_to_top_font_size',array(
		'label'	=> __('Icon Font Size','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_scroll_to_top_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_scroll_to_top_padding',array(
		'label'	=> __('Icon Top Bottom Padding','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_scroll_to_top_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_scroll_to_top_width',array(
		'label'	=> __('Icon Width','marketing-agency'),
		'description'	=> __('Enter a value in pixels Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_scroll_to_top_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_scroll_to_top_height',array(
		'label'	=> __('Icon Height','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'marketing_agency_scroll_to_top_border_radius', array(
		'default'              => '',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'marketing_agency_scroll_to_top_border_radius', array(
		'label'       => esc_html__( 'Icon Border Radius','marketing-agency' ),
		'section'     => 'marketing_agency_footer',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

    $wp_customize->add_setting('marketing_agency_scroll_top_alignment',array(
        'default' => 'Right',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control(new Marketing_Agency_Image_Radio_Control($wp_customize, 'marketing_agency_scroll_top_alignment', array(
        'type' => 'select',
        'label' => esc_html__('Scroll To Top','marketing-agency'),
        'section' => 'marketing_agency_footer',
        'settings' => 'marketing_agency_scroll_top_alignment',
        'choices' => array(
            'Left' => esc_url(get_template_directory_uri()).'/assets/images/layout1.png',
            'Center' => esc_url(get_template_directory_uri()).'/assets/images/layout2.png',
            'Right' => esc_url(get_template_directory_uri()).'/assets/images/layout3.png'
    ))));

    // footer social icon
	$wp_customize->add_setting( 'marketing_agency_footer_icon',array(
		'default' => false,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
  	) );
	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_footer_icon',array(
		'label' => esc_html__( 'Show / Hide Footer Social Icon','marketing-agency' ),
		'section' => 'marketing_agency_footer'
  	)));

    $wp_customize->add_setting('marketing_agency_align_footer_social_icon',array(
        'default' => 'center',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_align_footer_social_icon',array(
        'type' => 'select',
        'label' => __('Social Icon Alignment ','marketing-agency'),
        'section' => 'marketing_agency_footer',
        'choices' => array(
            'left' => __('Left','marketing-agency'),
            'right' => __('Right','marketing-agency'),
            'center' => __('Center','marketing-agency'),
        ),
	) );

	$wp_customize->add_setting( 'marketing_agency_copyright_sticky',array(
      'default' => 0,
      'transport' => 'refresh',
      'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_copyright_sticky',array(
      'label' => esc_html__( 'Show / Hide Sticky Copyright','marketing-agency' ),
      'section' => 'marketing_agency_footer'
    )));

   $wp_customize->add_setting('marketing_agency_footer_social_icons_font_size',array(
       'default'=> 16,
       'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('marketing_agency_footer_social_icons_font_size',array(
    'label' => __('Social Icon Font Size','marketing-agency'),
    	'type'        => 'number',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
		'section'=> 'marketing_agency_footer',
	 ));

	//Blog Post
	$wp_customize->add_panel( $marketing_agency_parent_panel );

	$BlogPostParentPanel = new Marketing_Agency_WP_Customize_Panel( $wp_customize, 'marketing_agency_blog_post_parent_panel', array(
		'title' => esc_html__( 'Blog Post Settings', 'marketing-agency' ),
		'panel' => 'marketing_agency_panel_id',
		'priority' => 20,
	));

	$wp_customize->add_panel( $BlogPostParentPanel );

	// Add example section and controls to the middle (second) panel
	$wp_customize->add_section( 'marketing_agency_post_settings', array(
		'title' => esc_html__( 'Post Settings', 'marketing-agency' ),
		'panel' => 'marketing_agency_blog_post_parent_panel',
	));

	//Blog layout
    $wp_customize->add_setting('marketing_agency_blog_layout_option',array(
        'default' => 'Default',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
    ));
    $wp_customize->add_control(new Marketing_Agency_Image_Radio_Control($wp_customize, 'marketing_agency_blog_layout_option', array(
        'type' => 'select',
        'label' => esc_html__('Blog Layouts','marketing-agency'),
        'section' => 'marketing_agency_post_settings',
        'choices' => array(
            'Default' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout1.png',
            'Center' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout2.png',
            'Left' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout3.png',
    ))));

	$wp_customize->add_setting('marketing_agency_theme_options',array(
        'default' => 'Right Sidebar',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_theme_options',array(
        'type' => 'select',
        'label' => esc_html__('Post Sidebar Layout','marketing-agency'),
        'description' => esc_html__('Here you can change the sidebar layout for posts. ','marketing-agency'),
        'section' => 'marketing_agency_post_settings',
        'choices' => array(
            'Left Sidebar' => esc_html__('Left Sidebar','marketing-agency'),
            'Right Sidebar' => esc_html__('Right Sidebar','marketing-agency'),
            'One Column' => esc_html__('One Column','marketing-agency'),
            'Three Columns' => esc_html__('Three Columns','marketing-agency'),
            'Four Columns' => esc_html__('Four Columns','marketing-agency'),
            'Grid Layout' => esc_html__('Grid Layout','marketing-agency')
        ),
	) );

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('marketing_agency_toggle_postdate', array( 
		'selector' => '.post-main-box h2 a', 
		'render_callback' => 'marketing_agency_customize_partial_marketing_agency_toggle_postdate', 
	));

    $wp_customize->add_setting('marketing_agency_toggle_postdate_icon',array(
		'default'	=> 'fas fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_toggle_postdate_icon',array(
		'label'	=> __('Add Post Date Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_post_settings',
		'setting'	=> 'marketing_agency_toggle_postdate_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'marketing_agency_toggle_postdate',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_toggle_postdate',array(
        'label' => esc_html__( 'Show / Hide Post Date','marketing-agency' ),
        'section' => 'marketing_agency_post_settings'
    )));

    $wp_customize->add_setting('marketing_agency_toggle_author_icon',array(
		'default'	=> 'fas fa-user',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_toggle_author_icon',array(
		'label'	=> __('Add Author Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_post_settings',
		'setting'	=> 'marketing_agency_toggle_author_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'marketing_agency_toggle_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_toggle_author',array(
		'label' => esc_html__( 'Show / Hide Author','marketing-agency' ),
		'section' => 'marketing_agency_post_settings'
    )));

    $wp_customize->add_setting('marketing_agency_toggle_comments_icon',array(
		'default'	=> 'fa fa-comments',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_toggle_comments_icon',array(
		'label'	=> __('Add Comments Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_post_settings',
		'setting'	=> 'marketing_agency_toggle_comments_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'marketing_agency_toggle_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_toggle_comments',array(
		'label' => esc_html__( 'Show / Hide Comments','marketing-agency' ),
		'section' => 'marketing_agency_post_settings'
    )));

    $wp_customize->add_setting('marketing_agency_toggle_time_icon',array(
		'default'	=> 'fas fa-clock',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_toggle_time_icon',array(
		'label'	=> __('Add Time Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_post_settings',
		'setting'	=> 'marketing_agency_toggle_time_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'marketing_agency_toggle_time',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_toggle_time',array(
		'label' => esc_html__( 'Show / Hide Time','marketing-agency' ),
		'section' => 'marketing_agency_post_settings'
    )));

    $wp_customize->add_setting( 'marketing_agency_featured_image_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
	));
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_featured_image_hide_show', array(
		'label' => esc_html__( 'Show / Hide Featured Image','marketing-agency' ),
		'section' => 'marketing_agency_post_settings'
    )));

    $wp_customize->add_setting( 'marketing_agency_featured_image_border_radius', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'marketing_agency_featured_image_border_radius', array(
		'label'       => esc_html__( 'Featured Image Border Radius','marketing-agency' ),
		'section'     => 'marketing_agency_post_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting( 'marketing_agency_featured_image_box_shadow', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'marketing_agency_featured_image_box_shadow', array(
		'label'       => esc_html__( 'Featured Image Box Shadow','marketing-agency' ),
		'section'     => 'marketing_agency_post_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Featured Image
	$wp_customize->add_setting('marketing_agency_blog_post_featured_image_dimension',array(
       'default' => 'default',
       'sanitize_callback'	=> 'marketing_agency_sanitize_choices'
	));
  	$wp_customize->add_control('marketing_agency_blog_post_featured_image_dimension',array(
		'type' => 'select',
		'label'	=> __('Blog Post Featured Image Dimension','marketing-agency'),
		'section'	=> 'marketing_agency_post_settings',
		'choices' => array(
		'default' => __('Default','marketing-agency'),
		'custom' => __('Custom Image Size','marketing-agency'),
      ),
  	));

	$wp_customize->add_setting('marketing_agency_blog_post_featured_image_custom_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
		));
	$wp_customize->add_control('marketing_agency_blog_post_featured_image_custom_width',array(
		'label'	=> __('Featured Image Custom Width','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
    	'placeholder' => __( '10px', 'marketing-agency' ),),
		'section'=> 'marketing_agency_post_settings',
		'type'=> 'text',
		'active_callback' => 'marketing_agency_blog_post_featured_image_dimension'
		));

	$wp_customize->add_setting('marketing_agency_blog_post_featured_image_custom_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_blog_post_featured_image_custom_height',array(
		'label'	=> __('Featured Image Custom Height','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
    	'placeholder' => __( '10px', 'marketing-agency' ),),
		'section'=> 'marketing_agency_post_settings',
		'type'=> 'text',
		'active_callback' => 'marketing_agency_blog_post_featured_image_dimension'
	));

    $wp_customize->add_setting( 'marketing_agency_excerpt_number', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'marketing_agency_excerpt_number', array(
		'label'       => esc_html__( 'Excerpt length','marketing-agency' ),
		'section'     => 'marketing_agency_post_settings',
		'type'        => 'range',
		'settings'    => 'marketing_agency_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting('marketing_agency_meta_field_separator',array(
		'default'=> '|',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_meta_field_separator',array(
		'label'	=> __('Add Meta Separator','marketing-agency'),
		'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','marketing-agency'),
		'section'=> 'marketing_agency_post_settings',
		'type'=> 'text'
	));

    $wp_customize->add_setting('marketing_agency_blog_page_posts_settings',array(
        'default' => 'Into Blocks',
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_blog_page_posts_settings',array(
        'type' => 'select',
        'label' => __('Display Blog Posts','marketing-agency'),
        'section' => 'marketing_agency_post_settings',
        'choices' => array(
        	'Into Blocks' => __('Into Blocks','marketing-agency'),
            'Without Blocks' => __('Without Blocks','marketing-agency')
        ),
	) );

    $wp_customize->add_setting('marketing_agency_excerpt_settings',array(
        'default' => 'Excerpt',
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_excerpt_settings',array(
        'type' => 'select',
        'label' => esc_html__('Post Content','marketing-agency'),
        'section' => 'marketing_agency_post_settings',
        'choices' => array(
        	'Content' => esc_html__('Content','marketing-agency'),
            'Excerpt' => esc_html__('Excerpt','marketing-agency'),
            'No Content' => esc_html__('No Content','marketing-agency')
        ),
	) );

	$wp_customize->add_setting('marketing_agency_blog_excerpt_suffix',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_blog_excerpt_suffix',array(
		'label'	=> __('Add Excerpt Suffix','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '[...]', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_post_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'marketing_agency_blog_pagination_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_blog_pagination_hide_show',array(
		'label' => esc_html__( 'Show / Hide Blog Pagination','marketing-agency' ),
		'section' => 'marketing_agency_post_settings'
    )));

	$wp_customize->add_setting( 'marketing_agency_blog_pagination_type', array(
        'default'			=> 'blog-page-numbers',
        'sanitize_callback'	=> 'marketing_agency_sanitize_choices'
    ));
    $wp_customize->add_control( 'marketing_agency_blog_pagination_type', array(
        'section' => 'marketing_agency_post_settings',
        'type' => 'select',
        'label' => __( 'Blog Pagination', 'marketing-agency' ),
        'choices'		=> array(
            'blog-page-numbers'  => __( 'Numeric', 'marketing-agency' ),
            'next-prev' => __( 'Older Posts/Newer Posts', 'marketing-agency' ),
    )));

    // Button Settings
	$wp_customize->add_section( 'marketing_agency_button_settings', array(
		'title' => esc_html__( 'Button Settings', 'marketing-agency' ),
		'panel' => 'marketing_agency_blog_post_parent_panel',
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('marketing_agency_button_text', array( 
		'selector' => '.post-main-box .more-btn a', 
		'render_callback' => 'marketing_agency_customize_partial_marketing_agency_button_text', 
	));

    $wp_customize->add_setting('marketing_agency_button_text',array(
		'default'=> esc_html__('Read More','marketing-agency'),
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_button_text',array(
		'label'	=> esc_html__('Add Button Text','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => esc_html__( 'Read More', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_button_settings',
		'type'=> 'text'
	));

	// font size button
	$wp_customize->add_setting('marketing_agency_button_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_button_font_size',array(
		'label'	=> __('Button Font Size','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
      	'placeholder' => __( '10px', 'marketing-agency' ),
    ),
    	'type'        => 'text',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
		'section'=> 'marketing_agency_button_settings',
	));

	$wp_customize->add_setting( 'marketing_agency_button_border_radius', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'marketing_agency_button_border_radius', array(
		'label'       => esc_html__( 'Button Border Radius','marketing-agency' ),
		'section'     => 'marketing_agency_button_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting('marketing_agency_button_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_button_padding_top_bottom',array(
		'label'	=> esc_html__('Padding Top Bottom','marketing-agency'),
		'description' => esc_html__('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => esc_html__( '10px', 'marketing-agency' ),
        ),
		'section' => 'marketing_agency_button_settings',
		'type' => 'text'
	));

	$wp_customize->add_setting('marketing_agency_button_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_button_padding_left_right',array(
		'label'	=> esc_html__('Padding Left Right','marketing-agency'),
		'description' => esc_html__('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => esc_html__( '10px', 'marketing-agency' ),
        ),
		'section' => 'marketing_agency_button_settings',
		'type' => 'text'
	));

	$wp_customize->add_setting('marketing_agency_button_letter_spacing',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_button_letter_spacing',array(
		'label'	=> __('Button Letter Spacing','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
      	'placeholder' => __( '10px', 'marketing-agency' ),
    ),
    	'type'        => 'text',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
		'section'=> 'marketing_agency_button_settings',
	));

	// text trasform
	$wp_customize->add_setting('marketing_agency_button_text_transform',array(
		'default'=> 'Uppercase',
		'sanitize_callback'	=> 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_button_text_transform',array(
		'type' => 'radio',
		'label'	=> __('Button Text Transform','marketing-agency'),
		'choices' => array(
            'Uppercase' => __('Uppercase','marketing-agency'),
            'Capitalize' => __('Capitalize','marketing-agency'),
            'Lowercase' => __('Lowercase','marketing-agency'),
        ),
		'section'=> 'marketing_agency_button_settings',
	));

	// Related Post Settings
	$wp_customize->add_section( 'marketing_agency_related_posts_settings', array(
		'title' => esc_html__( 'Related Posts Settings', 'marketing-agency' ),
		'panel' => 'marketing_agency_blog_post_parent_panel',
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('marketing_agency_related_post_title', array( 
		'selector' => '.related-post h3', 
		'render_callback' => 'marketing_agency_customize_partial_marketing_agency_related_post_title', 
	));

    $wp_customize->add_setting( 'marketing_agency_related_post',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_related_post',array(
		'label' => esc_html__( 'Show / Hide Related Post','marketing-agency' ),
		'section' => 'marketing_agency_related_posts_settings'
    )));

    $wp_customize->add_setting('marketing_agency_related_post_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_related_post_title',array(
		'label'	=> esc_html__('Add Related Post Title','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => esc_html__( 'Related Post', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_related_posts_settings',
		'type'=> 'text'
	));

   	$wp_customize->add_setting('marketing_agency_related_posts_count',array(
		'default'=> 3,
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_related_posts_count',array(
		'label'	=> esc_html__('Add Related Post Count','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => esc_html__( '3', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_related_posts_settings',
		'type'=> 'number'
	));

	$wp_customize->add_setting( 'marketing_agency_related_posts_excerpt_number', array(
		'default'              => 20,
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'marketing_agency_related_posts_excerpt_number', array(
		'label'       => esc_html__( 'Related Posts Excerpt length','marketing-agency' ),
		'section'     => 'marketing_agency_related_posts_settings',
		'type'        => 'range',
		'settings'    => 'marketing_agency_related_posts_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting( 'marketing_agency_related_toggle_postdate',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'marketing_agency_switch_sanitization'
  	));
  	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_related_toggle_postdate',array(
	    'label' => esc_html__( 'Show / Hide Post Date','marketing-agency' ),
	    'section' => 'marketing_agency_related_posts_settings'
  	)));

  	$wp_customize->add_setting('marketing_agency_related_postdate_icon',array(
	    'default' => 'fas fa-calendar-alt',
	    'sanitize_callback' => 'sanitize_text_field'
  	));
  	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
  	$wp_customize,'marketing_agency_related_postdate_icon',array(
	    'label' => __('Add Post Date Icon','marketing-agency'),
	    'transport' => 'refresh',
	    'section' => 'marketing_agency_related_posts_settings',
	    'setting' => 'marketing_agency_related_postdate_icon',
	    'type'    => 'icon'
  	)));

	$wp_customize->add_setting( 'marketing_agency_related_toggle_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
  	));
  	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_related_toggle_author',array(
		'label' => esc_html__( 'Show / Hide Author','marketing-agency' ),
		'section' => 'marketing_agency_related_posts_settings'
  	)));

  	$wp_customize->add_setting('marketing_agency_related_author_icon',array(
	    'default' => 'fas fa-user',
	    'sanitize_callback' => 'sanitize_text_field'
  	));
  	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
  	$wp_customize,'marketing_agency_related_author_icon',array(
	    'label' => __('Add Author Icon','marketing-agency'),
	    'transport' => 'refresh',
	    'section' => 'marketing_agency_related_posts_settings',
	    'setting' => 'marketing_agency_related_author_icon',
	    'type'    => 'icon'
  	)));

	$wp_customize->add_setting( 'marketing_agency_related_toggle_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
  	) );
  	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_related_toggle_comments',array(
		'label' => esc_html__( 'Show / Hide Comments','marketing-agency' ),
		'section' => 'marketing_agency_related_posts_settings'
  	)));

  	$wp_customize->add_setting('marketing_agency_related_comments_icon',array(
	    'default' => 'fa fa-comments',
	    'sanitize_callback' => 'sanitize_text_field'
  	));
  	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
  	$wp_customize,'marketing_agency_related_comments_icon',array(
	    'label' => __('Add Comments Icon','marketing-agency'),
	    'transport' => 'refresh',
	    'section' => 'marketing_agency_related_posts_settings',
	    'setting' => 'marketing_agency_related_comments_icon',
	    'type'    => 'icon'
  	)));

	$wp_customize->add_setting( 'marketing_agency_related_toggle_time',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
  	) );
  	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_related_toggle_time',array(
		'label' => esc_html__( 'Show / Hide Time','marketing-agency' ),
		'section' => 'marketing_agency_related_posts_settings'
  	)));

  	$wp_customize->add_setting('marketing_agency_related_time_icon',array(
	    'default' => 'fas fa-clock',
	    'sanitize_callback' => 'sanitize_text_field'
  	));
  	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
  	$wp_customize,'marketing_agency_related_time_icon',array(
	    'label' => __('Add Time Icon','marketing-agency'),
	    'transport' => 'refresh',
	    'section' => 'marketing_agency_related_posts_settings',
	    'setting' => 'marketing_agency_related_time_icon',
	    'type'    => 'icon'
  	)));

  	$wp_customize->add_setting('marketing_agency_related_post_meta_field_separator',array(
		'default'=> '|',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_related_post_meta_field_separator',array(
		'label'	=> __('Add Meta Separator','marketing-agency'),
		'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','marketing-agency'),
		'section'=> 'marketing_agency_related_posts_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'marketing_agency_related_image_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
	));
  	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_related_image_hide_show', array(
		'label' => esc_html__( 'Show / Hide Featured Image','marketing-agency' ),
		'section' => 'marketing_agency_related_posts_settings'
  	)));

    $wp_customize->add_setting( 'marketing_agency_related_image_box_shadow', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'marketing_agency_related_image_box_shadow', array(
		'label'       => esc_html__( 'Related post Image Box Shadow','marketing-agency' ),
		'section'     => 'marketing_agency_related_posts_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

    $wp_customize->add_setting('marketing_agency_related_button_text',array(
		'default'=> esc_html__('Read More','marketing-agency'),
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_related_button_text',array(
		'label'	=> esc_html__('Add Button Text','marketing-agency'),
		'input_attrs' => array(
      'placeholder' => esc_html__( 'Read More', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_related_posts_settings',
		'type'=> 'text'
	));

	// Single Posts Settings
	$wp_customize->add_section( 'marketing_agency_single_blog_settings', array(
		'title' => __( 'Single Post Settings', 'marketing-agency' ),
		'panel' => 'marketing_agency_blog_post_parent_panel',
	));

  	$wp_customize->add_setting('marketing_agency_single_postdate_icon',array(
		'default'	=> 'fas fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_single_postdate_icon',array(
		'label'	=> __('Add Post Date Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_single_blog_settings',
		'setting'	=> 'marketing_agency_single_postdate_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'marketing_agency_single_postdate',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'marketing_agency_switch_sanitization'
	) );
	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_single_postdate',array(
	    'label' => esc_html__( 'Show / Hide Date','marketing-agency' ),
	   'section' => 'marketing_agency_single_blog_settings'
	)));

	$wp_customize->add_setting('marketing_agency_single_author_icon',array(
		'default'	=> 'fas fa-user',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_single_author_icon',array(
		'label'	=> __('Add Author Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_single_blog_settings',
		'setting'	=> 'marketing_agency_single_author_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'marketing_agency_single_author',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'marketing_agency_switch_sanitization'
	) );
	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_single_author',array(
	    'label' => esc_html__( 'Show / Hide Author','marketing-agency' ),
	    'section' => 'marketing_agency_single_blog_settings'
	)));

   	$wp_customize->add_setting('marketing_agency_single_comments_icon',array(
		'default'	=> 'fa fa-comments',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_single_comments_icon',array(
		'label'	=> __('Add Comments Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_single_blog_settings',
		'setting'	=> 'marketing_agency_single_comments_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'marketing_agency_single_comments',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'marketing_agency_switch_sanitization'
	) );
	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_single_comments',array(
	    'label' => esc_html__( 'Show / Hide Comments','marketing-agency' ),
	    'section' => 'marketing_agency_single_blog_settings'
	)));

  	$wp_customize->add_setting('marketing_agency_single_time_icon',array(
		'default'	=> 'fas fa-clock',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_single_time_icon',array(
		'label'	=> __('Add Time Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_single_blog_settings',
		'setting'	=> 'marketing_agency_single_time_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'marketing_agency_single_time',array(
	    'default' => 1,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'marketing_agency_switch_sanitization'
	) );

	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_single_time',array(
	    'label' => esc_html__( 'Show / Hide Time','marketing-agency' ),
	    'section' => 'marketing_agency_single_blog_settings'
	)));

	$wp_customize->add_setting( 'marketing_agency_single_post_breadcrumb',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_single_post_breadcrumb',array(
		'label' => esc_html__( 'Show / Hide Breadcrumb','marketing-agency' ),
		'section' => 'marketing_agency_single_blog_settings'
    )));

    // Single Posts Category
  	$wp_customize->add_setting( 'marketing_agency_single_post_category',array(
		'default' => true,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
  	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_single_post_category',array(
		'label' => esc_html__( 'Show / Hide Category','marketing-agency' ),
		'section' => 'marketing_agency_single_blog_settings'
    )));

	$wp_customize->add_setting( 'marketing_agency_toggle_tags',array(
		'default' => 0,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
	));
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_toggle_tags', array(
		'label' => esc_html__( 'Show / Hide Tags','marketing-agency' ),
		'section' => 'marketing_agency_single_blog_settings'
    )));

	$wp_customize->add_setting( 'marketing_agency_singlepost_image_box_shadow', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'marketing_agency_singlepost_image_box_shadow', array(
		'label'       => esc_html__( 'Single post Image Box Shadow','marketing-agency' ),
		'section'     => 'marketing_agency_single_blog_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

   	$wp_customize->add_setting('marketing_agency_single_post_meta_field_separator',array(
		'default'=> '|',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_single_post_meta_field_separator',array(
		'label'	=> __('Add Meta Separator','marketing-agency'),
		'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','marketing-agency'),
		'section'=> 'marketing_agency_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'marketing_agency_single_blog_post_navigation_show_hide',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
	));
	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_single_blog_post_navigation_show_hide', array(
		  'label' => esc_html__( 'Show / Hide Post Navigation','marketing-agency' ),
		  'section' => 'marketing_agency_single_blog_settings'
	)));

	//navigation text
	$wp_customize->add_setting('marketing_agency_single_blog_prev_navigation_text',array(
		'default'=> 'PREVIOUS',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_single_blog_prev_navigation_text',array(
		'label'	=> __('Post Navigation Text','marketing-agency'),
		'input_attrs' => array(
    'placeholder' => __( 'PREVIOUS', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_single_blog_next_navigation_text',array(
		'default'=> 'NEXT',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_single_blog_next_navigation_text',array(
		'label'	=> __('Post Navigation Text','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( 'NEXT', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_single_blog_settings',
		'type'=> 'text'
	));

    $wp_customize->add_setting('marketing_agency_single_blog_comment_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('marketing_agency_single_blog_comment_title',array(
		'label'	=> __('Add Comment Title','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( 'Leave a Reply', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_single_blog_comment_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('marketing_agency_single_blog_comment_button_text',array(
		'label'	=> __('Add Comment Button Text','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( 'Post Comment', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_single_blog_comment_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_single_blog_comment_width',array(
		'label'	=> __('Comment Form Width','marketing-agency'),
		'description'	=> __('Enter a value in %. Example:50%','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '100%', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_single_blog_settings',
		'type'=> 'text'
	));

	// Grid layout setting
	$wp_customize->add_section( 'marketing_agency_grid_layout_settings', array(
		'title' => __( 'Grid Layout Settings', 'marketing-agency' ),
		'panel' => 'marketing_agency_blog_post_parent_panel',
	));

  	$wp_customize->add_setting('marketing_agency_grid_postdate_icon',array(
		'default'	=> 'fas fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_grid_postdate_icon',array(
		'label'	=> __('Add Post Date Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_grid_layout_settings',
		'setting'	=> 'marketing_agency_grid_postdate_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'marketing_agency_grid_postdate',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_grid_postdate',array(
        'label' => esc_html__( 'Show / Hide Post Date','marketing-agency' ),
        'section' => 'marketing_agency_grid_layout_settings'
    )));

	$wp_customize->add_setting('marketing_agency_grid_author_icon',array(
		'default'	=> 'fas fa-user',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_grid_author_icon',array(
		'label'	=> __('Add Author Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_grid_layout_settings',
		'setting'	=> 'marketing_agency_grid_author_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'marketing_agency_grid_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_grid_author',array(
		'label' => esc_html__( 'Show / Hide Author','marketing-agency' ),
		'section' => 'marketing_agency_grid_layout_settings'
    )));

   	$wp_customize->add_setting('marketing_agency_grid_comments_icon',array(
		'default'	=> 'fa fa-comments',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_grid_comments_icon',array(
		'label'	=> __('Add Comments Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_grid_layout_settings',
		'setting'	=> 'marketing_agency_grid_comments_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting( 'marketing_agency_grid_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_grid_comments',array(
		'label' => esc_html__( 'Show / Hide Comments','marketing-agency' ),
		'section' => 'marketing_agency_grid_layout_settings'
    )));

    $wp_customize->add_setting( 'marketing_agency_grid_image_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
	));
  	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_grid_image_hide_show', array(
		'label' => esc_html__( 'Show / Hide Featured Image','marketing-agency' ),
		'section' => 'marketing_agency_grid_layout_settings'
  	)));

 	$wp_customize->add_setting('marketing_agency_grid_post_meta_field_separator',array(
		'default'=> '|',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_grid_post_meta_field_separator',array(
		'label'	=> __('Add Meta Separator','marketing-agency'),
		'description' => __('Add the seperator for meta box. Example: "|", "/", etc.','marketing-agency'),
		'section'=> 'marketing_agency_grid_layout_settings',
		'type'=> 'text'
	));  

  	$wp_customize->add_setting('marketing_agency_display_grid_posts_settings',array(
	    'default' => 'Into Blocks',
	    'transport' => 'refresh',
	    'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_display_grid_posts_settings',array(
	    'type' => 'select',
	    'label' => __('Display Grid Posts','marketing-agency'),
	    'section' => 'marketing_agency_grid_layout_settings',
	    'choices' => array(
	    	'Into Blocks' => __('Into Blocks','marketing-agency'),
	        'Without Blocks' => __('Without Blocks','marketing-agency')
	    ),
	) );

	$wp_customize->add_setting('marketing_agency_grid_button_text',array(
		'default'=> esc_html__('Read More','marketing-agency'),
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_grid_button_text',array(
		'label'	=> esc_html__('Add Button Text','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => esc_html__( 'Read More', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_grid_layout_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_grid_excerpt_suffix',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_grid_excerpt_suffix',array(
		'label'	=> __('Add Excerpt Suffix','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '[...]', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_grid_layout_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_grid_excerpt_settings',array(
        'default' => 'Excerpt',
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_grid_excerpt_settings',array(
        'type' => 'select',
        'label' => esc_html__('Grid Post Content','marketing-agency'),
        'section' => 'marketing_agency_grid_layout_settings',
        'choices' => array(
        	'Content' => esc_html__('Content','marketing-agency'),
            'Excerpt' => esc_html__('Excerpt','marketing-agency'),
            'No Content' => esc_html__('No Content','marketing-agency')
        ),
	) );

	$wp_customize->add_setting( 'marketing_agency_grid_featured_image_border_radius', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'marketing_agency_grid_featured_image_border_radius', array(
		'label'       => esc_html__( 'Grid Featured Image Border Radius','marketing-agency' ),
		'section'     => 'marketing_agency_grid_layout_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting( 'marketing_agency_grid_featured_image_box_shadow', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'marketing_agency_grid_featured_image_box_shadow', array(
		'label'       => esc_html__( 'Grid Featured Image Box Shadow','marketing-agency' ),
		'section'     => 'marketing_agency_grid_layout_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Other settings
	$wp_customize->add_panel( $marketing_agency_parent_panel );

	$OtherParentPanel = new Marketing_Agency_WP_Customize_Panel( $wp_customize, 'marketing_agency_other_parent_panel', array(
		'title' => esc_html__( 'Other Settings', 'marketing-agency' ),
		'panel' => 'marketing_agency_panel_id',
		'priority' => 20,
	));

	$wp_customize->add_panel( $OtherParentPanel );

	// Layout
	$wp_customize->add_section( 'marketing_agency_left_right', array(
    	'title' => esc_html__( 'General Settings', 'marketing-agency' ),
		'panel' => 'marketing_agency_other_parent_panel'
	) );

	$wp_customize->add_setting('marketing_agency_width_option',array(
        'default' => 'Full Width',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control(new Marketing_Agency_Image_Radio_Control($wp_customize, 'marketing_agency_width_option', array(
        'type' => 'select',
        'label' => esc_html__('Width Layouts','marketing-agency'),
        'description' => esc_html__('Here you can change the width layout of Website.','marketing-agency'),
        'section' => 'marketing_agency_left_right',
        'choices' => array(
            'Full Width' => esc_url(get_template_directory_uri()).'/assets/images/full-width.png',
            'Wide Width' => esc_url(get_template_directory_uri()).'/assets/images/wide-width.png',
            'Boxed' => esc_url(get_template_directory_uri()).'/assets/images/boxed-width.png',
    ))));

	$wp_customize->add_setting('marketing_agency_page_layout',array(
        'default' => 'One Column',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_page_layout',array(
        'type' => 'select',
        'label' => esc_html__('Page Sidebar Layout','marketing-agency'),
        'description' => esc_html__('Here you can change the sidebar layout for pages. ','marketing-agency'),
        'section' => 'marketing_agency_left_right',
        'choices' => array(
            'Left Sidebar' => esc_html__('Left Sidebar','marketing-agency'),
            'Right Sidebar' => esc_html__('Right Sidebar','marketing-agency'),
            'One Column' => esc_html__('One Column','marketing-agency')
        ),
	) );

    $wp_customize->add_setting( 'marketing_agency_single_page_breadcrumb',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_single_page_breadcrumb',array(
		'label' => esc_html__( 'Show / Hide Page Breadcrumb','marketing-agency' ),
		'section' => 'marketing_agency_left_right'
    )));

    //Wow Animation
	$wp_customize->add_setting( 'marketing_agency_animation',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ));
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_animation',array(
        'label' => esc_html__( 'Show / Hide Animations','marketing-agency' ),
        'description' => __('Here you can disable overall site animation effect','marketing-agency'),
        'section' => 'marketing_agency_left_right'
    )));

    $wp_customize->add_setting('marketing_agency_reset_all_settings',array(
      'sanitize_callback'	=> 'sanitize_text_field',
   	));
   	$wp_customize->add_control(new Marketing_Agency_Reset_Custom_Control($wp_customize, 'marketing_agency_reset_all_settings',array(
      'type' => 'reset_control',
      'label' => __('Reset All Settings', 'marketing-agency'),
      'description' => 'marketing_agency_reset_all_settings',
      'section' => 'marketing_agency_left_right'
   	)));

    //Pre-Loader
	$wp_customize->add_setting( 'marketing_agency_loader_enable',array(
        'default' => 0,
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_loader_enable',array(
        'label' => esc_html__( 'Show / Hide Pre-Loader','marketing-agency' ),
        'section' => 'marketing_agency_left_right'
    )));

	$wp_customize->add_setting('marketing_agency_preloader_bg_color', array(
		'default'           => '#0985f9',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'marketing_agency_preloader_bg_color', array(
		'label'    => __('Pre-Loader Background Color', 'marketing-agency'),
		'section'  => 'marketing_agency_left_right',
	)));

	$wp_customize->add_setting('marketing_agency_preloader_border_color', array(
		'default'           => '#ffffff',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'marketing_agency_preloader_border_color', array(
		'label'    => __('Pre-Loader Border Color', 'marketing-agency'),
		'section'  => 'marketing_agency_left_right',
	)));

	$wp_customize->add_setting('marketing_agency_preloader_bg_img',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'marketing_agency_preloader_bg_img',array(
        'label' => __('Preloader Background Image','marketing-agency'),
        'section' => 'marketing_agency_left_right'
	)));

	$wp_customize->add_setting('marketing_agency_bradcrumbs_alignment',array(
        'default' => 'Left',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_bradcrumbs_alignment',array(
        'type' => 'select',
        'label' => __('Bradcrumbs Alignment','marketing-agency'),
        'section' => 'marketing_agency_left_right',
        'choices' => array(
            'Left' => __('Left','marketing-agency'),
            'Right' => __('Right','marketing-agency'),
            'Center' => __('Center','marketing-agency'),
        ),
	) );

  //404 Page Setting
	$wp_customize->add_section('marketing_agency_404_page',array(
		'title'	=> __('404 Page Settings','marketing-agency'),
		'panel' => 'marketing_agency_other_parent_panel',
	));

	$wp_customize->add_setting('marketing_agency_404_page_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('marketing_agency_404_page_title',array(
		'label'	=> __('Add Title','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '404 Not Found', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_404_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_404_page_content',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('marketing_agency_404_page_content',array(
		'label'	=> __('Add Text','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( 'Looks like you have taken a wrong turn, Dont worry, it happens to the best of us.', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_404_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_404_page_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_404_page_button_text',array(
		'label'	=> __('Add Button Text','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( 'GO BACK', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_404_page',
		'type'=> 'text'
	));

	//No Result Page Setting
	$wp_customize->add_section('marketing_agency_no_results_page',array(
		'title'	=> __('No Results Page Settings','marketing-agency'),
		'panel' => 'marketing_agency_other_parent_panel',
	));	

	$wp_customize->add_setting('marketing_agency_no_results_page_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('marketing_agency_no_results_page_title',array(
		'label'	=> __('Add Title','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( 'Nothing Found', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_no_results_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_no_results_page_content',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('marketing_agency_no_results_page_content',array(
		'label'	=> __('Add Text','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_no_results_page',
		'type'=> 'text'
	));

	//Social Icon Setting
	$wp_customize->add_section('marketing_agency_social_icon_settings',array(
		'title'	=> __('Social Icons Settings','marketing-agency'),
		'panel' => 'marketing_agency_other_parent_panel',
	));

	$wp_customize->add_setting('marketing_agency_social_icon_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_social_icon_font_size',array(
		'label'	=> __('Icon Font Size','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_social_icon_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_social_icon_padding',array(
		'label'	=> __('Icon Padding','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_social_icon_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_social_icon_width',array(
		'label'	=> __('Icon Width','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
    'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_social_icon_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_social_icon_height',array(
		'label'	=> __('Icon Height','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_social_icon_settings',
		'type'=> 'text'
	));

	//Responsive Media Settings
	$wp_customize->add_section('marketing_agency_responsive_media',array(
		'title'	=> esc_html__('Responsive Media','marketing-agency'),
		'panel' => 'marketing_agency_other_parent_panel',
	));

	$wp_customize->add_setting( 'marketing_agency_resp_topbar_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'marketing_agency_switch_sanitization'
  	));  
  	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_resp_topbar_hide_show',array(
      'label' => esc_html__( 'Show / Hide Topbar','marketing-agency' ),
      'section' => 'marketing_agency_responsive_media'
  	)));

	$wp_customize->add_setting( 'marketing_agency_stickyheader_hide_show',array(
	    'default' => 0,
	    'transport' => 'refresh',
	    'sanitize_callback' => 'marketing_agency_switch_sanitization'
  	));  
  	$wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_stickyheader_hide_show',array(
	    'label' => esc_html__( 'Show / Hide Sticky Header','marketing-agency' ),
	    'section' => 'marketing_agency_responsive_media'
  	)));

    $wp_customize->add_setting( 'marketing_agency_resp_slider_hide_show',array(
      	'default' => 1,
     	'transport' => 'refresh',
      	'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ));  
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_resp_slider_hide_show',array(
      	'label' => esc_html__( 'Show / Hide Slider','marketing-agency' ),
      	'section' => 'marketing_agency_responsive_media'
    )));

    $wp_customize->add_setting( 'marketing_agency_sidebar_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ));  
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_sidebar_hide_show',array(
      	'label' => esc_html__( 'Show / Hide Sidebar','marketing-agency' ),
      	'section' => 'marketing_agency_responsive_media'
    )));

    $wp_customize->add_setting( 'marketing_agency_responsive_preloader_hide',array(
        'default' => false,
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_responsive_preloader_hide',array(
        'label' => esc_html__( 'Show / Hide Preloader','marketing-agency' ),
        'section' => 'marketing_agency_responsive_media'
    )));

    $wp_customize->add_setting( 'marketing_agency_resp_scroll_top_hide_show',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ));  
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_resp_scroll_top_hide_show',array(
      	'label' => esc_html__( 'Show / Hide Scroll To Top','marketing-agency' ),
      	'section' => 'marketing_agency_responsive_media'
    )));

     $wp_customize->add_setting('marketing_agency_resp_menu_toggle_btn_bg_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'marketing_agency_resp_menu_toggle_btn_bg_color', array(
		'label'    => __('Toggle Button Bg Color', 'marketing-agency'),
		'section'  => 'marketing_agency_responsive_media',
	)));

    $wp_customize->add_setting('marketing_agency_res_open_menu_icon',array(
		'default'	=> 'fas fa-bars',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_res_open_menu_icon',array(
		'label'	=> __('Add Open Menu Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_responsive_media',
		'setting'	=> 'marketing_agency_res_open_menu_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('marketing_agency_res_close_menus_icon',array(
		'default'	=> 'fas fa-times',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Marketing_Agency_Fontawesome_Icon_Chooser(
        $wp_customize,'marketing_agency_res_close_menus_icon',array(
		'label'	=> __('Add Close Menu Icon','marketing-agency'),
		'transport' => 'refresh',
		'section'	=> 'marketing_agency_responsive_media',
		'setting'	=> 'marketing_agency_res_close_menus_icon',
		'type'		=> 'icon'
	)));

    //Woocommerce settings
	$wp_customize->add_section('marketing_agency_woocommerce_section', array(
		'title'    => __('WooCommerce Layout', 'marketing-agency'),
		'priority' => null,
		'panel'    => 'woocommerce',
	));

    //Shop Page Featured Image
	$wp_customize->add_setting( 'marketing_agency_shop_featured_image_border_radius', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'marketing_agency_shop_featured_image_border_radius', array(
		'label'       => esc_html__( 'Shop Page Featured Image Border Radius','marketing-agency' ),
		'section'     => 'marketing_agency_woocommerce_section',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting( 'marketing_agency_shop_featured_image_box_shadow', array(
		'default'              => '0',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'marketing_agency_sanitize_number_range'
	) );
	$wp_customize->add_control( 'marketing_agency_shop_featured_image_box_shadow', array(
		'label'       => esc_html__( 'Shop Page Featured Image Box Shadow','marketing-agency' ),
		'section'     => 'marketing_agency_woocommerce_section',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'marketing_agency_woocommerce_shop_page_sidebar', array( 'selector' => '.post-type-archive-product #sidebar', 
		'render_callback' => 'marketing_agency_customize_partial_marketing_agency_woocommerce_shop_page_sidebar', ) );

    //Woocommerce Shop Page Sidebar
	$wp_customize->add_setting( 'marketing_agency_woocommerce_shop_page_sidebar',array(
		'default' => 0,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_woocommerce_shop_page_sidebar',array(
		'label' => esc_html__( 'Show / Hide Shop Page Sidebar','marketing-agency' ),
		'section' => 'marketing_agency_woocommerce_section'
    )));

    $wp_customize->add_setting('marketing_agency_shop_page_layout',array(
        'default' => 'Right Sidebar',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_shop_page_layout',array(
        'type' => 'select',
        'label' => __('Shop Page Sidebar Layout','marketing-agency'),
        'section' => 'marketing_agency_woocommerce_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','marketing-agency'),
            'Right Sidebar' => __('Right Sidebar','marketing-agency'),
        ),
	) );

    //Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'marketing_agency_woocommerce_single_product_page_sidebar', array( 'selector' => '.single-product #sidebar', 
		'render_callback' => 'marketing_agency_customize_partial_marketing_agency_woocommerce_single_product_page_sidebar', ) );

    //Woocommerce Single Product page Sidebar
	$wp_customize->add_setting( 'marketing_agency_woocommerce_single_product_page_sidebar',array(
		'default' => 0,
		'transport' => 'refresh',
		'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_woocommerce_single_product_page_sidebar',array(
		'label' => esc_html__( 'Show / Hide Single Product Sidebar','marketing-agency' ),
		'section' => 'marketing_agency_woocommerce_section'
    )));

    $wp_customize->add_setting('marketing_agency_single_product_layout',array(
        'default' => 'Right Sidebar',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_single_product_layout',array(
        'type' => 'select',
        'label' => __('Single Product Sidebar Layout','marketing-agency'),
        'section' => 'marketing_agency_woocommerce_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','marketing-agency'),
            'Right Sidebar' => __('Right Sidebar','marketing-agency'),
        ),
	) );

	$wp_customize->add_setting('marketing_agency_products_btn_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_products_btn_padding_top_bottom',array(
		'label'	=> __('Products Button Padding Top Bottom','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_woocommerce_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_products_btn_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_products_btn_padding_left_right',array(
		'label'	=> __('Products Button Padding Left Right','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_woocommerce_section',
		'type'=> 'text'
	));

	//Products Sale Badge
	$wp_customize->add_setting('marketing_agency_woocommerce_sale_position',array(
        'default' => 'right',
        'sanitize_callback' => 'marketing_agency_sanitize_choices'
	));
	$wp_customize->add_control('marketing_agency_woocommerce_sale_position',array(
        'type' => 'select',
        'label' => __('Sale Badge Position','marketing-agency'),
        'section' => 'marketing_agency_woocommerce_section',
        'choices' => array(
            'left' => __('Left','marketing-agency'),
            'right' => __('Right','marketing-agency'),
        ),
	) );

	$wp_customize->add_setting('marketing_agency_woocommerce_sale_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_woocommerce_sale_font_size',array(
		'label'	=> __('Sale Font Size','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_woocommerce_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_woocommerce_sale_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_woocommerce_sale_padding_top_bottom',array(
		'label'	=> __('Sale Padding Top Bottom','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_woocommerce_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('marketing_agency_woocommerce_sale_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('marketing_agency_woocommerce_sale_padding_left_right',array(
		'label'	=> __('Sale Padding Left Right','marketing-agency'),
		'description'	=> __('Enter a value in pixels. Example:20px','marketing-agency'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'marketing-agency' ),
        ),
		'section'=> 'marketing_agency_woocommerce_section',
		'type'=> 'text'
	));

    // Related Product
    $wp_customize->add_setting( 'marketing_agency_related_product_show_hide',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'marketing_agency_switch_sanitization'
    ) );
    $wp_customize->add_control( new Marketing_Agency_Toggle_Switch_Custom_Control( $wp_customize, 'marketing_agency_related_product_show_hide',array(
        'label' => esc_html__( 'Show / Hide Related product','marketing-agency' ),
        'section' => 'marketing_agency_woocommerce_section'
    )));

    // Has to be at the top
	$wp_customize->register_panel_type( 'Marketing_Agency_WP_Customize_Panel' );
	$wp_customize->register_section_type( 'Marketing_Agency_WP_Customize_Section' );
}

add_action( 'customize_register', 'marketing_agency_customize_register' );

load_template( trailingslashit( get_template_directory() ) . '/inc/logo/logo-resizer.php' );

if ( class_exists( 'WP_Customize_Panel' ) ) {
  	class Marketing_Agency_WP_Customize_Panel extends WP_Customize_Panel {
	    public $panel;
	    public $type = 'marketing_agency_panel';
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
  	class Marketing_Agency_WP_Customize_Section extends WP_Customize_Section {
	    public $section;
	    public $type = 'marketing_agency_section';
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
function marketing_agency_customize_controls_scripts() {
	wp_enqueue_script( 'marketing-agency-customizer-controls', get_theme_file_uri( '/assets/js/customizer-controls.js' ), array(), '1.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'marketing_agency_customize_controls_scripts' );

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class Marketing_Agency_Customize {

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
		$manager->register_section_type( 'Marketing_Agency_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section( new Marketing_Agency_Customize_Section_Pro( $manager,'marketing_agency_upgrade_pro_link', array(
			'priority'   => 1,
			'title'    => esc_html__( 'Marketing Agency Pro', 'marketing-agency' ),
			'pro_text' => esc_html__( 'UPGRADE PRO', 'marketing-agency' ),
			'pro_url'  => esc_url('https://www.vwthemes.com/products/marketing-agency-wordpress-theme'),
		) )	);

		// Register sections.
		$manager->add_section(new Marketing_Agency_Customize_Section_Pro($manager,'marketing_agency_get_started_link',array(
			'priority'   => 1,
			'title'    => esc_html__( 'DOCUMENTATION', 'marketing-agency' ),
			'pro_text' => esc_html__( 'DOCS', 'marketing-agency' ),
			'pro_url'  => esc_url('https://preview.vwthemesdemo.com/docs/free-marketing-agency/'),
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

		wp_enqueue_script( 'marketing-agency-customize-controls', trailingslashit( esc_url(get_template_directory_uri()) ) . '/assets/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'marketing-agency-customize-controls', trailingslashit( esc_url(get_template_directory_uri()) ) . '/assets/css/customize-controls.css' );

		wp_localize_script(
		'marketing-agency-customize-controls',
		'marketing_agency_customizer_params',
		array(
			'ajaxurl' =>	admin_url( 'admin-ajax.php' )
		));
	}
}

// Doing this customizer thang!
Marketing_Agency_Customize::get_instance();