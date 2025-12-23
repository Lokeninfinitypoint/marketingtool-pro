<?php
/**
 * @package Software Agency
 * Setup the WordPress core custom header feature.
 *
 * @uses software_agency_header_style()
*/
function software_agency_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'software_agency_custom_header_args', array(
		'default-text-color'     => 'fff',
		'header-text' 			 =>	false,
		'width'                  => 1400,
		'height'                 => 80,
		'flex-width'    		 => true,
		'flex-height'    		 => true,
		'wp-head-callback'       => 'software_agency_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'software_agency_custom_header_setup' );

if ( ! function_exists( 'software_agency_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see software_agency_custom_header_setup().
 */
add_action( 'wp_enqueue_scripts', 'software_agency_header_style' );

function software_agency_header_style() {
	//Check if user has defined any header image.
	if ( get_header_image() ) :
	$custom_css = "
        .middle-bar{
			background-image:url('".esc_url(get_header_image())."');
			background-position: center top;
			background-size: cover;
		}";
	   	wp_add_inline_style( 'software-agency-basic-style', $custom_css );
	endif;
}
endif;