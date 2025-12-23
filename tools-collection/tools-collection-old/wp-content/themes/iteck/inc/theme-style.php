<?php
function iteck_theme_styles()  
{ 
	// Register the style for the theme 
	wp_enqueue_style ('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '1', 'all' );
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), '1', 'all' );
	wp_enqueue_style('fontawesome', get_template_directory_uri() . '/assets/css/fontawesome.min.css', array(), '1', 'all' );
	wp_enqueue_style('icon-font', get_template_directory_uri() . '/assets/css/icon-font.min.css', array(), '1', 'all' );
	wp_enqueue_style('ionicons', get_template_directory_uri() . '/assets/css/ionicons.min.css', array(), '1', 'all' );
	wp_enqueue_style('bootstrap-icons', get_template_directory_uri() . '/assets/fonts/bootstrap-icons/bootstrap-icons.css', array(), '1', 'all' );
	wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css', array(), '1', 'all' );
	wp_enqueue_style('animate', get_template_directory_uri() . '/assets/css/animate.css', array(), '1', 'all' );
	wp_enqueue_style('magic', get_template_directory_uri() . '/assets/css/magic.css', array(), '1', 'all' );
	wp_enqueue_style('slick', get_template_directory_uri() . '/assets/css/slick.css', array(), '1', 'all' );
	wp_enqueue_style('jquery-fatnav', get_template_directory_uri() . '/assets/css/jquery.fatNav.css', array(), '1', 'all' );
	wp_enqueue_style('animate-headline', get_template_directory_uri() . '/assets/css/animate.headline.css', array(), '1', 'all' );
	wp_enqueue_style('swiper', get_template_directory_uri() . '/assets/css/swiper.min.css', array(), '1', 'all' );

	if( !is_rtl() ) {
	wp_enqueue_style('splitting', get_template_directory_uri() . '/assets/css/splitting.css', array(), '1', 'all' );
	wp_enqueue_style('splitting-cells', get_template_directory_uri() . '/assets/css/splitting-cells.css', array(), '1', 'all' );
	}
	wp_enqueue_style('iteck-style', get_stylesheet_directory_uri() . '/style.css', array(), '1.3.0', 'all' );
	wp_style_add_data('iteck-style', 'rtl', 'replace' );
	if( is_rtl() ) {
		wp_enqueue_style('iteck-theme-rtl', get_stylesheet_directory_uri() . '/assets/css/theme-rtl.css', array(), '1.3.0', 'all' );
	}




}

//google font 
/*
Register Fonts
*/
function iteck_fonts_url() {
	$font_url = '';
	
	/* 
	Translators: If there are characters in your language that are not supported
	by chosen font(s), translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Google font: on or off', 'iteck' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Open Sans:300,400,400i,500,600,600i,700,700i,800,800i,900|Poppins:300,400,500,600,700,800,900|Barlow Condensed:300,400,500,600,700,800,900' ), "//fonts.googleapis.com/css" );
	}
	return $font_url;
}

/*
Enqueue scripts and styles.
*/
function iteck_fonts_style() {
	wp_enqueue_style( 'iteck-fonts', iteck_fonts_url(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'iteck_fonts_style' );

//for google font  in editor
function iteck_fonts_editor_style() {
	$font_url = add_query_arg( 'family', urlencode( 'Open Sans:300,400,400i,500,600,600i,700,700i,800,800i,900|Poppins:300,400,500,600,700,800,900|Barlow Condensed:300,400,500,600,700,800,900' ), "//fonts.googleapis.com/css" );
	add_editor_style( $font_url );
}
add_action( 'after_setup_theme', 'iteck_fonts_editor_style' );

/*
Enqueue admin scripts and styles.
*/
function iteck_admin_style() {
	wp_enqueue_style('iteck-admin-custom', get_stylesheet_directory_uri() . '/assets/css/admin-custom.css', array(), '5.1.1', 'all' );
}
add_action( 'admin_enqueue_scripts', 'iteck_admin_style' );


//Register and load FontAwesome to WP Editor

add_action( 'enqueue_block_editor_assets', 'iteck_editor_fontawesome' );
function iteck_editor_fontawesome() {
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', null, '1');
}

