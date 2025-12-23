<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://hostinger.com
 * @since      1.0.0
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/admin
 */

use Hostinger\WpMenuManager\Menus;
use Hostinger\WpHelper\Utils;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/admin
 * @author     Hostinger <info@hostinger.com>
 */
class Hostinger_Ai_Assistant_Admin {
    public const MCP_SETTINGS_UPDATED_OPTION     = 'hostinger_mcp_settings_updated';
    public const MCP_CHOICE_OPTION               = 'hostinger_mcp_choice';
    public const MCP_ENABLE_DELETE_TOOLS_SETTING = 'enable_delete_tools';
    public const WORDPRESS_MCP_SETTINGS_OPTION   = 'wordpress_mcp_settings';

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private string $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private string $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct( string $plugin_name, string $version ) {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles(): void {
        if ( $this->is_hostinger_menu_page() ) {
            wp_enqueue_style( $this->plugin_name, HOSTINGER_AI_ASSISTANT_ASSETS_URL . '/css/hostinger-ai-assistant-admin.css', array(), $this->version, 'all' );
        }

        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_style( 'hostinger_ai_assistant_woo_styles', HOSTINGER_AI_ASSISTANT_ASSETS_URL . '/css/woo-styles.css', array(), $this->version, 'all' );
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts(): void {
        $translations  = new Hostinger_Frontend_Translations();
        $global_params = array_merge(
            $translations->get_frontend_translations(),
            array(
                'tabUrl' => admin_url() . 'admin.php?page=hostinger-ai-assistant',
            )
        );

        if ( $this->is_hostinger_menu_page() ) {
            wp_enqueue_script(
                $this->plugin_name,
                HOSTINGER_AI_ASSISTANT_ASSETS_URL . '/js/hostinger-ai-assistant-admin.js',
                array(
                    'jquery',
                    'wp-i18n',
                ),
                $this->version,
                false
            );
            wp_localize_script( $this->plugin_name, 'hostingerAiAssistant', $global_params );
        }

        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_script(
                'hostinger_ai_assistant_woo_requests',
                HOSTINGER_AI_ASSISTANT_ASSETS_URL . '/js/hostinger-woo-requests.js',
                array(
                    'jquery',
                    'wp-i18n',
                ),
                $this->version,
                false
            );
        }

        wp_enqueue_script(
            'hostinger_chatbot',
            HOSTINGER_AI_ASSISTANT_ASSETS_URL . '/js/hostinger-chatbot.js',
            array(
                'jquery',
                'wp-i18n',
            ),
            $this->version,
            array( 'strategy' => 'defer' ),
            false
        );

        $user   = wp_get_current_user();
        $locale = get_user_locale();

        wp_localize_script(
            'hostinger_chatbot',
            'hostingerChatbot',
            array_merge(
                $translations->get_chatbot_translations(),
                array(
                    'nonce'             => wp_create_nonce( 'wp_rest' ),
                    'chatbot_uri'       => esc_url_raw( rest_url() ),
                    'user_id'           => ! empty( $user->ID ) ? $user->ID : 0,
                    'mcp_choice'        => get_option( 'hostinger_mcp_choice', '' ),
                    'mcp_plugin_active' => is_plugin_active( 'wordpress-mcp/wordpress-mcp.php' ),
                    'language'          => $locale,
                )
            )
        );
    }

    public function enqueue_custom_editor_assets(): void {
        $translations  = new Hostinger_Frontend_Translations();
        $global_params = array_merge(
            $translations->get_frontend_translations(),
            array(
                'tabUrl' => admin_url() . 'admin.php?page=hostinger-ai-assistant',
            )
        );

        wp_enqueue_script(
            'custom-link-in-toolbar',
            HOSTINGER_AI_ASSISTANT_ASSETS_URL . '/js/hostinger-buttons.js',
            array(
                'jquery',
                'wp-blocks',
                'wp-dom',
                'wp-i18n',
            ),
            $this->version,
            false
        );
        wp_set_script_translations( 'custom-link-in-toolbar', 'hostinger-ai-assistant' );
        wp_localize_script( 'custom-link-in-toolbar', 'hostingerAiAssistant', $global_params );
    }

    public function add_ai_assistant_menu_item( $submenus ): array {
        $submenus[] = array(
            'page_title' => __( 'AI Content Creator', 'hostinger-ai-assistant' ),
            'menu_title' => __( 'AI Content Creator', 'hostinger-ai-assistant' ),
            'capability' => 'edit_posts',
            'menu_slug'  => 'hostinger-ai-assistant',
            'callback'   => array( $this, 'create_ai_assistant_tab_view' ),
            'menu_order' => 10,
        );

        return $submenus;
    }

    public function add_admin_bar_item( array $menu_items ): array {
        $menu_items[] = array(
            'id'    => 'hostinger-ai-assistant-ai-content-creator',
            'title' => esc_html__( 'AI Content Creator', 'hostinger-ai-assistant' ),
            'href'  => admin_url( 'admin.php?page=hostinger-ai-assistant' ),
        );

        return $menu_items;
    }

    /**
     * Add AI Assistant view
     *
     * @since    1.0.0
     */
    public function create_ai_assistant_tab_view(): void {
        echo Menus::renderMenuNavigation();
        include_once HOSTINGER_AI_ASSISTANT_ABSPATH . 'admin/partials/hostinger-ai-assistant-tab-view.php';
    }

    public function check_and_update_mcp_settings(): void {
        if ( get_option( self::MCP_SETTINGS_UPDATED_OPTION, false ) ) {
            return;
        }

        $mcp_choice = get_option( self::MCP_CHOICE_OPTION, 0 );
        if ( ! $mcp_choice ) {
            return;
        }

        $this->update_mcp_settings();
        update_option( self::MCP_SETTINGS_UPDATED_OPTION, true );
    }

    /**
     * @return bool
     */
    private function is_hostinger_menu_page(): bool {
        $admin_path = parse_url( admin_url(), PHP_URL_PATH );

        $pages = array(
            $admin_path . 'admin.php?page=' . Menus::MENU_SLUG,
        );

        $pages[] = $admin_path . 'admin.php?page=hostinger-ai-assistant';

        $utils = new Utils();

        foreach ( $pages as $page ) {
            if ( $utils->isThisPage( $page ) ) {
                return true;
            }
        }

        return false;
    }

    private function update_mcp_settings(): void {
        $current_settings = get_option( self::WORDPRESS_MCP_SETTINGS_OPTION, array() );
        $updated_settings = array_merge(
            $current_settings,
            array( self::MCP_ENABLE_DELETE_TOOLS_SETTING => true )
        );

        update_option( self::WORDPRESS_MCP_SETTINGS_OPTION, $updated_settings );
    }
}
