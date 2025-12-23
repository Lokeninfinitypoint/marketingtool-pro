<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://hostinger.com
 * @since             1.0.0
 * @package           Hostinger_AI
 *
 * @wordpress-plugin
 * Plugin Name:       Hostinger AI
 * Plugin URI:        https://hostinger.com
 * Description:       Hostinger AI plugin for WordPress.
 * Version: 3.0.16
 * Author:            Hostinger
 * Requires PHP:      8.0
 * Requires at least: 5.0
 * Tested up to:      6.5
 * Author URI:        https://hostinger.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hostinger-ai-assistant
 * Domain Path:       /languages
 */

use Hostinger\Surveys\Loader;
use Hostinger\WpMenuManager\Manager;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Currently plugin version.
 */

define( 'HOSTINGER_AI_ASSISTANT_VERSION', '3.0.16' );

/**
 * Plugin path.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_ABSPATH' ) ) {
    define( 'HOSTINGER_AI_ASSISTANT_ABSPATH', plugin_dir_path( __FILE__ ) );
}

/**
 * Plugin file path.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_PLUGIN_FILE' ) ) {
    define( 'HOSTINGER_AI_ASSISTANT_PLUGIN_FILE', __FILE__ );
}

/**
 * Plugin dir path.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_PLUGIN_URL' ) ) {
    define( 'HOSTINGER_AI_ASSISTANT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Plugin assets path.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_ASSETS_URL' ) ) {
    define( 'HOSTINGER_AI_ASSISTANT_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets' );
}

/**
 * Hostinger config path.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_CONFIG_PATH' ) ) {
    define( 'HOSTINGER_AI_ASSISTANT_CONFIG_PATH', ABSPATH . '/.private/config.json' );
}
/**
 * Hostinger api token path.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_WP_AI_TOKEN' ) ) {
    $path             = explode( '/', __DIR__ );
    $server_root_path = '/' . $path[1] . '/' . $path[2];
    define( 'HOSTINGER_AI_ASSISTANT_WP_AI_TOKEN', $server_root_path . '/.api_token' );
}

/**
 * Hostinger default rest api url.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_REST_URI' ) ) {
    define( 'HOSTINGER_AI_ASSISTANT_REST_URI', 'https://rest-hosting.hostinger.com' );
}

/**
 * Hostinger default hpanel rest api url.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_HPANEL_REST_URI' ) ) {
    define( 'HOSTINGER_AI_ASSISTANT_HPANEL_REST_URI', 'https://hpanel.hostinger.com/api/rest-hosting/' );
}

/**
 * Plugin Rest API base url
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_REST_API_BASE' ) ) {
    define( 'HOSTINGER_AI_ASSISTANT_REST_API_BASE', 'hostinger-ai-assistant/v1' );
}

if ( ! defined( 'HOSTINGER_AI_MINIMUM_PHP_VERSION' ) ) {
    define( 'HOSTINGER_AI_MINIMUM_PHP_VERSION', '8.0' );
}

if ( ! version_compare( phpversion(), HOSTINGER_AI_MINIMUM_PHP_VERSION, '>=' ) ) {

    add_action(
        'admin_notices',
        function () {
            ?>
        <div class="notice notice-error is-dismissible hts-theme-settings">
            <p>
                <?php /* translators: %s: PHP version */ ?>
                <strong><?php echo __( 'Attention:', 'hostinger-ai-assistant' ); ?></strong> <?php echo sprintf( __( 'The Hostinger AI plugin requires minimum PHP version of <b>%s</b>. ', 'hostinger-ai-assistant' ), HOSTINGER_AI_MINIMUM_PHP_VERSION ); ?>
            </p>
            <p>
                <?php /* translators: %s: PHP version */ ?>
                <?php echo sprintf( __( 'You are running <b>%s</b> PHP version.', 'hostinger-ai-assistant' ), phpversion() ); ?>
            </p>
        </div>
            <?php
        }
    );

    return;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hostinger-ai-assistant-activator.php
 */
function activate_hostinger_ai_assistant() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-hostinger-ai-assistant-activator.php';
    Hostinger_Ai_Assistant_Activator::activate();
    do_action( 'activate_hostinger_ai_assistant' );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hostinger-ai-assistant-deactivator.php
 */
function deactivate_hostinger_ai_assistant() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-hostinger-ai-assistant-deactivator.php';
    Hostinger_Ai_Assistant_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_hostinger_ai_assistant' );
register_deactivation_hook( __FILE__, 'deactivate_hostinger_ai_assistant' );

require_once HOSTINGER_AI_ASSISTANT_ABSPATH . 'vendor/autoload_packages.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hostinger-ai-assistant.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_hostinger_ai_assistant() {
    if ( ! class_exists( 'Hostinger_Ai_Assistant' ) ) {
        return;
    }

    $plugin = new Hostinger_Ai_Assistant();

    if ( class_exists( 'Hostinger_Ai_Assistant_Chatbot_Endpoints' ) ) {
        $chatbot = new Hostinger_Ai_Assistant_Chatbot_Endpoints();
        $chatbot->init();
    }

    $plugin->run();

    if ( class_exists( 'Hostinger_Ai_Assistant_Mcp_Endpoints' ) ) {
        $mcp_endpoints = new Hostinger_Ai_Assistant_Mcp_Endpoints();
        $mcp_endpoints->init();
    }

    if ( class_exists( 'Hostinger_Ai_Assistant_Mcp_Hooks' ) ) {
        $mcp_hooks = new Hostinger_Ai_Assistant_Mcp_Hooks();
        $mcp_hooks->init();
    }
}

if ( ! has_action( 'plugins_loaded', 'run_hostinger_ai_assistant' ) ) {
    add_action( 'plugins_loaded', 'run_hostinger_ai_assistant' );
}

if ( ! function_exists( 'hostinger_load_menus' ) ) {
    function hostinger_load_menus(): void {
        $manager = Manager::getInstance();
        $manager->boot();
    }
}

if ( ! has_action( 'plugins_loaded', 'hostinger_load_menus' ) ) {
    add_action( 'plugins_loaded', 'hostinger_load_menus' );
}

if ( ! function_exists( 'hostinger_add_surveys' ) ) {
    function hostinger_add_surveys(): void {
        $surveys = Loader::getInstance();
        $surveys->boot();
    }
}

if ( ! has_action( 'plugins_loaded', 'hostinger_add_surveys' ) ) {
    add_action( 'plugins_loaded', 'hostinger_add_surveys' );
}
