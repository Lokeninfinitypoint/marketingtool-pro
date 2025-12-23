<?php
add_action( 'wp_enqueue_scripts', 'iteck_child_theme_styles',3);
function iteck_child_theme_styles() {
		
    wp_enqueue_style('iteck-parent-style', get_template_directory_uri(). '/style.css',array('bootstrap'));
    wp_enqueue_style('iteck-child-style', get_stylesheet_uri(), array( 'iteck-parent-style') );
}





