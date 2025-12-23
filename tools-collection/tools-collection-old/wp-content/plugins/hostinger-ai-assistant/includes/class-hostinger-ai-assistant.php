<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://hostinger.com
 * @since      1.0.0
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/includes
 */

use Hostinger\Amplitude\AmplitudeManager;
use Hostinger\EasyOnboarding\Amplitude\Amplitude;
use Hostinger\Surveys\Rest as SurveysRest;
use Hostinger\Surveys\SurveyManager;
use Hostinger\WpHelper\Config;
use Hostinger\WpHelper\Constants;
use Hostinger\WpHelper\Requests\Client;
use Hostinger\WpHelper\Utils as Helper;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/includes
 * @author     Hostinger <info@hostinger.com>
 */
class Hostinger_Ai_Assistant {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Hostinger_Ai_Assistant_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if ( defined( 'HOSTINGER_AI_ASSISTANT_VERSION' ) ) {
            $this->version = HOSTINGER_AI_ASSISTANT_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'hostinger-ai-assistant';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Hostinger_Ai_Assistant_Loader. Orchestrates the hooks of the plugin.
     * - Hostinger_Ai_Assistant_I18n. Defines internationalization functionality.
     * - Hostinger_Ai_Assistant_Admin. Defines all hooks for the admin area.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        $required_files = array(
            'includes/class-hostinger-ai-assistant-config.php',
            'includes/class-hostinger-ai-assistant-errors.php',
            'includes/class-hostinger-ai-assistant-updates.php',
            'includes/class-hostinger-ai-assistant-helper.php',
            'includes/requests/class-hostinger-ai-assistant-requests-client.php',
            'includes/class-hostinger-ai-assistant-i18n.php',
            'includes/amplitude/class-hostinger-ai-assistant-amplitude-actions.php',
            'includes/amplitude/class-hostinger-ai-assistant-amplitude.php',
            'includes/seo/class-hostinger-ai-assistant-seo.php',
            'includes/content/class-hostinger-ai-assistant-content-generation.php',
            'includes/content/class-hostinger-ai-assistant-content-filters.php',
            'admin/class-hostinger-ai-assistant-translations.php',
            'admin/class-hostinger-ai-assistant-notices.php',
            'includes/class-hostinger-ai-assistant-loader.php',
            'admin/class-hostinger-ai-assistant-admin.php',
            'admin/class-hostinger-ai-assistant-redirects.php',
            'includes/requests/class-hostinger-ai-assistant-requests.php',
            'includes/woocommerce/class-hostinger-ai-assistant-product-ai-metabox.php',
            'includes/chatbot/class-hostinger-ai-assistant-chatbot-endpoints.php',
            'includes/mcp/class-hostinger-ai-assistant-mcp-endpoints.php',
            'includes/mcp/class-hostinger-ai-assistant-mcp-hooks.php',
            'includes/mcp/class-hostinger-ai-assistant-mcp-jwt-auth.php',
            'admin/class-hostinger-ai-assistant-blocks.php',
            'includes/class-hostinger-ai-assistant-surveys.php',
        );

        foreach ( $required_files as $file ) {
            $file_path = plugin_dir_path( __DIR__ ) . $file;
            if ( file_exists( $file_path ) ) {
                require_once $file_path;
            }
        }

        $this->loader = new Hostinger_Ai_Assistant_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Hostinger_Ai_Assistant_I18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Hostinger_Ai_Assistant_I18n();

        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin   = new Hostinger_Ai_Assistant_Admin( $this->get_plugin_name(), $this->get_version() );
        $helper_notices = new Hostinger_Ai_Assistant_Notices();
        $helper         = new Hostinger_Ai_Assistant_Helper();
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'check_and_update_mcp_settings' );

        $this->loader->add_filter( 'hostinger_menu_subpages', $plugin_admin, 'add_ai_assistant_menu_item', 40 );
        $this->loader->add_filter( 'hostinger_admin_menu_bar_items', $plugin_admin, 'add_admin_bar_item', 100 );

        if ( ! Hostinger_Ai_Assistant_Helper::get_api_token() ) {
            $this->loader->add_action( 'admin_notices', $helper_notices, 'api_token_plugin_notice' );
        }

        $this->loader->add_action( 'admin_footer', $helper, 'add_vue_instance' );

        if ( function_exists( 'register_block_type' ) && stripos( wp_get_theme()->get( 'Name' ), 'Hostinger AI theme' ) === false ) {
            $blocks = new Hostinger_Ai_Assistant_Block();

            $this->loader->add_action( 'init', $blocks, 'register_block' );

            if ( is_admin() ) {
                $this->loader->add_action( 'enqueue_block_assets', $blocks, 'enqueue_blocks' );
            }
        }

        $helper = new Helper();
        $config = new Config();
        $client = new Client(
            $config->getConfigValue( 'base_rest_uri', Constants::HOSTINGER_REST_URI ),
            array(
                Config::TOKEN_HEADER  => $helper->getApiToken(),
                Config::DOMAIN_HEADER => $helper->getHostInfo(),
            )
        );

        if ( class_exists( SurveyManager::class ) ) {
            $surveys_rest   = new SurveysRest( $client );
            $survey_manager = new SurveyManager( $helper, $config, $surveys_rest );
            $surveys        = new Surveys( $survey_manager );
            $surveys->init();
        }

        $amplitude_manager = new AmplitudeManager( $helper, $config, $client );
        $amplitude_events  = new Hostinger_Ai_Assistant_Amplitude( $amplitude_manager );

        $this->loader->add_action( 'init', $this, 'initialize_updates' );
    }

    public function initialize_updates(): void {
        $updates = new Hostinger_Ai_Assistant_Updates();
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Hostinger_Ai_Assistant_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version() {
        return $this->version;
    }
}
