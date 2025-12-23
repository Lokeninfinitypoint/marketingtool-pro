<?php
/**
 * @package Marketing Agency
 * Setup the WordPress core custom header feature.
 *
 * @uses marketing_agency_header_style()
*/
function marketing_agency_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'marketing_agency_custom_header_args', array(
		'default-text-color'     => 'fff',
		'header-text' 			 =>	false,
		'width'                  => 1400,
		'height'                 => 202,
		'flex-width'    		 => true,
		'flex-height'    		 => true,
		'wp-head-callback'       => 'marketing_agency_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'marketing_agency_custom_header_setup' );

if ( ! function_exists( 'marketing_agency_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see marketing_agency_custom_header_setup().
 */
add_action( 'wp_enqueue_scripts', 'marketing_agency_header_style' );

function marketing_agency_header_style() {
	//Check if user has defined any header image.
	if ( get_header_image() ) :
	$custom_css = "
        #header{
			background-image:url('".esc_url(get_header_image())."');
			background-position: center top;
		}";
	   	wp_add_inline_style( 'marketing-agency-basic-style', $custom_css );
	endif;
}
endif;