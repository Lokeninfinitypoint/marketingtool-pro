<?php

/**
 * Deployer for Git
 *
 * @link              https://deployer-for-git.com
 * @since             1.0.0
 * @package           Deployer_For_Git
 *
 * @wordpress-plugin
 * Plugin Name:       Deployer for Git
 * Plugin URI:        https://wordpress.org/plugins/deployer-for-git/
 * Description:       This plugin can install and automatically update themes and plugins hosted on GitHub, Bitbucket, GitLab, or Gitea.
 * Version:           1.0.8
 * Author:            Alex Masliychuk
 * Author URI:        https://alex-masliychuk.com/
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       deployer_for_git
 * Domain Path:       /languages
 * Requires PHP:      7.0
 * Requires at least: 4.4
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
if ( !defined( 'DFG_FILE' ) ) {
    define( 'DFG_FILE', __FILE__ );
}
if ( !defined( 'DFG_PATH' ) ) {
    define( 'DFG_PATH', plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'DFG_URL' ) ) {
    define( 'DFG_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'DFG_SLUG' ) ) {
    define( 'DFG_SLUG', 'dfg' );
    // = plugin slug (used for options)
}
if ( !defined( 'DFG_VERSION' ) ) {
    define( 'DFG_VERSION', '1.0' );
}
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Load Freemius SDK.
if ( function_exists( 'dfg_fs' ) ) {
    dfg_fs()->set_basename( false, __FILE__ );
    // phpcs:ignore Universal.ControlStructures.DisallowLonelyIf.Found
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    if ( !function_exists( 'dfg_fs' ) ) {
        /**
         * Create a helper function for easy SDK access.
         */
        function dfg_fs() {
            global $dfg_fs;
            if ( !isset( $dfg_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_13247_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_13247_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once __DIR__ . '/freemius/start.php';
                $dfg_fs = fs_dynamic_init( array(
                    'id'             => '13247',
                    'slug'           => 'deployer-for-git',
                    'premium_slug'   => 'deployer-for-git-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_24e3f32d1fddcf86de310f6883552',
                    'is_premium'     => false,
                    'premium_suffix' => '(Pro)',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                        'days'               => 14,
                        'is_require_payment' => false,
                    ),
                    'menu'           => array(
                        'slug'    => 'deployer-for-git',
                        'support' => false,
                        'network' => true,
                    ),
                    'is_live'        => true,
                ) );
            }
            return $dfg_fs;
        }

        // Load Composer autoload.
        require __DIR__ . '/vendor/autoload.php';
        // Init Freemius.
        dfg_fs();
        dfg_fs()->add_filter( 'show_delegation_option', '__return_false' );
        // Signal that SDK was initiated.
        do_action( 'dfg_fs_loaded' );
    }
}
use DeployerForGit\Helper;
use DeployerForGit\Admin;
use DeployerForGit\ApiRequests\PackageUpdate;
if ( !class_exists( 'DeployerForGitInit' ) ) {
    /**
     * Class DeployerForGitInit
     */
    class DeployerForGitInit {
        /**
         * DeployerForGitInit constructor.
         */
        public function __construct() {
            if ( is_admin() ) {
                add_action( 'plugins_loaded', array($this, 'admin_init') );
                add_action( 'plugins_loaded', array($this, 'load_textdomain') );
            }
            // Init api requests.
            add_action( 'plugins_loaded', array($this, 'api_requests_init') );
            register_activation_hook( DFG_FILE, array($this, 'plugin_activation') );
        }

        /**
         * Set default settings.
         *
         * @return void
         */
        public function plugin_activation() {
            if ( !Helper::get_api_secret() ) {
                Helper::generate_api_secret();
            }
            flush_rewrite_rules();
            // Flush rewrite rules to ensure the new endpoints are registered.
        }

        /**
         * Load and initialize wp-admin side classes
         */
        public function admin_init() {
            new Admin();
        }

        /**
         * Load and initialize api requests classes
         */
        public function api_requests_init() {
            // init theme update webhook endpoint.
            new PackageUpdate();
        }

        /**
         * Load plugin textdomain.
         *
         * @return void
         */
        public function load_textdomain() {
            load_plugin_textdomain( 'deployer-for-git', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }

    }

    new DeployerForGitInit();
}