<?php
/**
 * Header Tab For Theme Option.
 *
 * @package iteck
 */

// Font options for family. size and fully typography.

if ( ! function_exists( 'iteck_enqueue_fonts_url' ) ) :

function iteck_enqueue_fonts_url() {
    $iteck_fonts_url = '';
    $iteck_fonts     = array();
    $iteck_main_font_weight = array();
    $iteck_alt_font_weight = array();
    $iteck_font_subsets = array();
    global $iteck_theme_settings;
    
    /* For Main Font Weight */
    $iteck_main_font_weight_array = ( isset( $iteck_theme_settings['main_font_weight'] ) ) ? $iteck_theme_settings['main_font_weight'] : '';
    if( !empty( $iteck_main_font_weight_array ) ) {
        foreach ($iteck_main_font_weight_array as $key => $value) {
            if( $value == 1 ){
                $iteck_main_font_weight[] = $key;
            }
        }
    }

    if( !empty( $iteck_main_font_weight ) ) {
        $iteck_main_font_weight = implode( ',', $iteck_main_font_weight );
    } else {
        $iteck_main_font_weight = '100,300,400,500,700,900';
    }

    if( iteck_option('main_font')){ 
        $iteck_fonts[] = $iteck_theme_settings['main_font']['font-family'].':'.$iteck_main_font_weight;
    }

    /* For Alt Font Weight */
    $iteck_alt_font_weight_array = ( isset( $iteck_theme_settings['alt_font_weight'] ) ) ? $iteck_theme_settings['alt_font_weight'] : '';
    if( !empty( $iteck_alt_font_weight_array ) ) {
        foreach ($iteck_alt_font_weight_array as $key => $value) {
            if( $value == 1 ){
                $iteck_alt_font_weight[] = $key;
            }
        }
    }

    if( !empty( $iteck_alt_font_weight ) ) {
        $iteck_alt_font_weight = implode( ',', $iteck_alt_font_weight );
    } else {
        $iteck_alt_font_weight = '100,200,300,400,500,600,700,800,900';
    }
    if( iteck_option('alt_font')){
        $iteck_fonts[] = $iteck_theme_settings['alt_font']['font-family'].':'.$iteck_alt_font_weight;
    }else{
        $iteck_fonts[] = 'Asap:100,200,300,400,500,600,700,800,900';
    }

    /* For Font Subsets */
    $iteck_main_font_subsets = ( isset( $iteck_theme_settings['main_font_languages'] ) ) ? $iteck_theme_settings['main_font_languages'] : '' ;
    if( !empty( $iteck_main_font_subsets ) ) {
        foreach ($iteck_main_font_subsets as $key => $value) {
            if( $value == 1 ){
                $iteck_font_subsets[] = $key;
            }
        }
    }
    if( !empty( $iteck_font_subsets ) ) {
        $iteck_main_font_subsets = implode( ',',  $iteck_font_subsets );
    } else {
        $iteck_main_font_subsets = '';
    }

    if ( $iteck_fonts ) {
        $iteck_fonts_url = add_query_arg( array(
            'family' => urlencode( implode( '|', $iteck_fonts ) ),
            'subset' => urlencode( $iteck_main_font_subsets ),
        ), '//fonts.googleapis.com/css' );
    }
    return $iteck_fonts_url;
}
endif;

if ( ! function_exists( 'iteck_font_scripts' ) ) :
    function iteck_font_scripts() {
        $disable_google_fonts = iteck_option( 'disable_google_fonts' );
        if( $disable_google_fonts != 1 ) {
            wp_enqueue_style( 'iteck-fonts', iteck_enqueue_fonts_url(), array(), null );
        }
    }
endif;
add_action( 'wp_enqueue_scripts', 'iteck_font_scripts' );

?>