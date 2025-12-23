<?php

class Hostinger_Ai_Assistant_Mcp_Endpoints {
    private const MCP_PLUGIN_SLUG = 'wordpress-mcp';

    public function init(): void {
        add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
    }

    public function register_rest_routes(): void {
        register_rest_route(
            HOSTINGER_AI_ASSISTANT_REST_API_BASE,
            '/toggle-mcp-plugin',
            array(
                'methods'             => 'POST',
                'callback'            => array( $this, 'toggle_mcp_plugin' ),
                'permission_callback' => array( $this, 'permission_check' ),
                'args'                => array(
                    'action' => array(
                        'required'          => true,
                        'validate_callback' => function ( $param ) {
                            return in_array( $param, array( 'setup', 'deny' ), true );
                        },
                        'sanitize_callback' => 'sanitize_text_field',
                        'description'       => 'The action to perform: setup or deny.',
                        'type'              => 'string',
                        'enum'              => array( 'setup', 'deny' ),
                    ),
                ),
            )
        );
    }

    public function toggle_mcp_plugin( WP_REST_Request $request ) {
        $action = $request->get_param( 'action' );

        switch ( $action ) {
            case 'setup':
                $result = $this->setup_mcp_plugin();
                break;
            default:
            case 'deny':
                $result = $this->deny_mcp_plugin();
                break;
        }

        if ( is_wp_error( $result ) ) {
            return $this->handle_wp_error( $result );
        }

        return $result;
    }

    public function setup_mcp_plugin() {
        $install_plugin = $this->install_plugin( $this->get_plugin_update_url() );

        if ( is_wp_error( $install_plugin ) ) {
            return $this->handle_wp_error( $install_plugin );
        }

        $activation_result = $this->activate_plugin( self::MCP_PLUGIN_SLUG . '/' . self::MCP_PLUGIN_SLUG . '.php' );

        if ( is_wp_error( $activation_result ) ) {
            return $this->handle_wp_error( $activation_result );
        }

        $this->apply_initial_settings();

        $response = new WP_REST_Response();
        $response->set_headers( array( 'Cache-Control' => 'no-cache' ) );
        $response->set_status( WP_Http::OK );

        return rest_ensure_response( $response );
    }

    public function deny_mcp_plugin() {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        $plugin_file = self::MCP_PLUGIN_SLUG . '/' . self::MCP_PLUGIN_SLUG . '.php';

        if ( is_plugin_active( $plugin_file ) ) {
            deactivate_plugins( $plugin_file );
        }

        update_option( 'hostinger_mcp_choice', 0 );

        $response = new WP_REST_Response();
        $response->set_headers( array( 'Cache-Control' => 'no-cache' ) );
        $response->set_status( WP_Http::OK );

        return rest_ensure_response( $response );
    }

    public function permission_check(): bool {
        return is_user_logged_in() && current_user_can( 'manage_options' );
    }

    protected function install_plugin( string $url ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        $skin     = new WP_Ajax_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader( $skin );

        return $upgrader->install( $url );
    }

    protected function activate_plugin( string $plugin_file ) {
        return activate_plugin( $plugin_file );
    }

    protected function handle_wp_error( WP_Error $error ): WP_REST_Response {
        if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
            error_log( 'Hostinger Ai Assistant: ' . print_r( $error->get_error_messages(), true ) );
        }

        $data = array(
            'status'  => 'error',
            'errors'  => $error->get_error_code(),
            'message' => implode( ', ', $error->get_error_messages() ),
        );

        $response = new WP_REST_Response();
        $response->set_data( $data );
        $response->set_status( WP_Http::BAD_REQUEST );

        return $response;
    }

    protected function apply_initial_settings(): void {
        $settings = array(
            'enabled'                    => true,
            'features_adapter_enabled'   => false,
            'enable_create_tools'        => true,
            'enable_update_tools'        => true,
            'enable_delete_tools'        => true,
            'enable_rest_api_crud_tools' => false,
        );

        update_option( 'wordpress_mcp_settings', $settings );
        update_option( 'hostinger_mcp_choice', 1 );

        $tool_states = array(
            'wp_update_general_settings' => false,
            'wp_upload_media'            => false,
        );

        update_option( 'wordpress_mcp_tool_states', $tool_states );
    }

    protected function get_plugin_update_url(): string {
        $domain = 'wp-update.hostinger.io';

        if ( isset( $_SERVER['H_STAGING'] ) && filter_var( $_SERVER['H_STAGING'], FILTER_VALIDATE_BOOLEAN ) === true ) {
            $domain = 'wp-update-stage.hostinger.io';
        }

        if ( isset( $_SERVER['H_CANARY'] ) && filter_var( $_SERVER['H_CANARY'], FILTER_VALIDATE_BOOLEAN ) === true ) {
            $domain = 'wp-update-canary.hostinger.io';
        }

        $query = array(
            'action' => 'download',
            'slug'   => self::MCP_PLUGIN_SLUG,
        );

        return 'https://' . $domain . '/?' . http_build_query( $query );
    }
}
