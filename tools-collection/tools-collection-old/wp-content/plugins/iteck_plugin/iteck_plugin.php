<?php
/**
 * Plugin Name: Iteck Theme Addons
 * Plugin URI: https://themescamp.com/
 * Description: This is plugin bundle for Iteck WordPress Theme.
 * Author: themesCamp
 * Author URI: https://themescamp.com
 * Version: 1.1.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ITECK__FILE__', __FILE__ );
define( 'ITECK_URL', plugins_url( '/', ITECK__FILE__ ) );
define( 'ITECK_PLUGIN_BASE', plugin_basename( ITECK__FILE__ ) );


/**
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 1.0.0
 */
function iteck_plg_load() {
	// Load localization file
	load_plugin_textdomain( 'iteck_plg' );

	// Require the main plugin file 
	require( __DIR__ . '/plugin.php' );

}
add_action( 'plugins_loaded','iteck_plg_load' );


function iteck_plg_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Iteck Plugin is not working because you are using an old version of Elementor.', 'iteck_plg' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'iteck_plg' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}


//adding reduxoptions into themes
/**
 * Optional: set 'ot_show_pages' filter to false.
 * This will hide the settings & documentation pages.
 */
add_filter( 'ot_show_pages', '__return_false' );
	/**
 * Optional: set 'ot_show_new_layout' filter to false.
 * This will hide the "New Layout" section on the Theme Options page.
 */
add_filter( 'ot_show_new_layout', '__return_false' );


//==============================================Theme Enhancement============================================

// Remove the calculated image sizes
add_filter( 'wp_calculate_image_sizes', '__return_false' );


// Remove iframe obsolete attribute
function iteck_remove_iframe_attributes($content){
    return str_replace(array('<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0"', '</iframe>'), array('<iframe ', '</iframe>'), $content);
}
add_filter('the_content', 'iteck_remove_iframe_attributes');

// Remove noscript obsolete attribute
function iteck_remove_noscript_attributes($content){
    return str_replace(array('<noscript><img'), array('<img'), $content);
}
add_filter('the_content', 'iteck_remove_noscript_attributes');


// Remove font-display from the header
function iteck_start_wp_head_buffer()
{
    ob_start();
}
function iteck_end_wp_head_buffer()
{
    $head_content = ob_get_clean();
    $head_content = str_replace('font-display:swap;', '', $head_content);

    echo $head_content;
}
add_action('wp_head', 'iteck_start_wp_head_buffer', 0);
add_action('wp_head', 'iteck_end_wp_head_buffer', PHP_INT_MAX);

//--------------------------------------------------------------------------------------------


//include elementor addon
include('inc/elementor-addon.php');

//include elementor addon
include('inc/elemntor-extras.php');

//include portfolio custom post type,metaboxes & single portfolio script
include('inc/portfolio.php');
include('inc/portfolio-metaboxes.php');

//include page metabox
include('inc/page-metaboxes.php');

//include post metabox
include('inc/post-metaboxes.php');
include('meta-box/meta-box.php');

//include custom footer
include('inc/footer.php');

//include custom header
include('inc/header.php');

//include side panel
include('inc/side-panel.php');

//include admin custom script 
include('inc/admin-script.php');

//include single portfolio function
include('inc/single-portfolio.php');


//included newsletter widget
include('inc/newsletter.php');

//included custom widget
include('inc/about-us.php');

//included recent posts widget
include('inc/recent-posts.php');

//included sharing
include('inc/sharebox.php');

//included User roles
include('inc/user-roles.php');

//included one click importer
include('inc/one-click.php');

//included shortcode importer
include('inc/shortcode.php');

//included breadcrumbs
include('inc/breadcrumbs.php');

//included license
include('inc/license.php');

function iteck_admin_styles() {
  wp_enqueue_style('admin-styles', ITECK_URL.'inc/css/admin.css');
  wp_enqueue_style('iteck-admin-collection-styles', ITECK_URL.'assets/css/iteck-elementor-iframe.css');
}
add_action('admin_enqueue_scripts', 'iteck_admin_styles');


//plugin translation
function iteck_textdomain_translation() {
    load_plugin_textdomain('iteck_plg', false, dirname(plugin_basename(__FILE__)) . '/lang/');
} // end custom_theme_setup
add_action('after_setup_theme', 'iteck_textdomain_translation');

