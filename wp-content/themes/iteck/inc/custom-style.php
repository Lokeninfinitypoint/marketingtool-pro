<?php


function iteck_custom_css() {

    /* CSS to output */
    $custom_css = '';
    if ( class_exists( 'ReduxFrameworkPlugin' ) && iteck_option( 'iteck_cursor_set' ) == '1') {
    	$custom_css = "body {cursor: none!important;}"; 
    }
    wp_add_inline_style('iteck-style', $custom_css);
}
