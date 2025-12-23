<?php
/**
 * The wp_kses_post function is used to ensure any HTML that is not allowed in a post will be escaped.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( isset( $attributes['shortcode'] ) ) {
    echo do_shortcode( $attributes['shortcode'] );
}
?>