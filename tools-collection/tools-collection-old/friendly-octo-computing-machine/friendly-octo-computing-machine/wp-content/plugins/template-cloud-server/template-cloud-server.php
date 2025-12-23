<?php
/**
 * Plugin Name: Template Cloud Server
 * Description: Share your block patterns across websites.
 * Version: 1.0.3
 * Author: ThemeIsle
 * Author URI: https://themeisle.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * WordPress Available: no
 * Requires License: yes
 *
 * @package template-cloud-server
 */

namespace TI\Template_Cloud;

/**
 * Setup plugin constants.
 */
function setup_constants() {
	define( 'TI_TC_SERVER_VERSION', '1.0.3' );
	define( 'TI_TC_SERVER_URL', plugin_dir_url( __FILE__ ) );
	define( 'TI_TC_SERVER_PATH', __DIR__ . '/' );
	define( 'TI_TC_BASE_FILE', __FILE__ );
	define( 'TI_TC_SERVER_BASENAME', plugin_basename( __FILE__ ) );
}

/**
 * Load the dependencies.
 */
function load_dependencies() {
	require_once TI_TC_SERVER_PATH . 'vendor/autoload.php';
}

/**
 * Run the plugin.
 */
function run_plugin() {
	$plugin = new \TI\Template_Cloud\Main();
	$plugin->init();
}

setup_constants();
load_dependencies();
run_plugin();
