<?php
namespace Elementor;

function iteck_insert_elementor($atts){
    if(!class_exists('Elementor\Plugin')){
        return '';
    }
    if(!isset($atts['id']) || empty($atts['id'])){
        return '';
    }

    $post_id = $atts['id'];
    $response = Plugin::instance()->frontend->get_builder_content_for_display($post_id);
    return $response;
}
add_shortcode('INSERT_ELEMENTOR','Elementor\iteck_insert_elementor');


function iteck_enable_elementor(){
    add_post_type_support( 'iteck_global_templates', 'elementor' );
}
add_action('elementor/init','Elementor\iteck_enable_elementor');


function print_iteck_data($sections){
    foreach ( $sections as $section_data ) {
        $section = new Element_Section( $section_data );

        $section->print_element();
    }
}
/**
 *  Enable the use of shortcodes in text widgets.
 */
add_filter( 'widget_text', 'do_shortcode' );

