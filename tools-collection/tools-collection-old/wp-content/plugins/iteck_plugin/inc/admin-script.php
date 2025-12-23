<?php 
function iteck_portfolio_template_script($hook) {

    wp_enqueue_script( 'portfolio-template', plugins_url( '/js/admin-script.js' , __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'iteck_portfolio_template_script' );