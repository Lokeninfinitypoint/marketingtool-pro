<?php

class Hostinger_Ai_Assistant_Helper {
    public const HOMEPAGE_DISPLAY = 'page';

    /**
     *
     * Check if plugin is active
     *
     * @since    1.0.0
     * @access   public
     */
    public static function is_plugin_active( $plugin_slug ): bool {
        $active_plugins = (array) get_option( 'active_plugins', array() );
        foreach ( $active_plugins as $active_plugin ) {
            if ( strpos( $active_plugin, $plugin_slug . '.php' ) !== false ) {
                return true;
            }
        }

        return false;
    }

    public static function get_api_token(): string {
        $api_token  = '';
        $token_file = HOSTINGER_AI_ASSISTANT_WP_AI_TOKEN;

        if ( file_exists( $token_file ) && ! empty( file_get_contents( $token_file ) ) ) {

            $api_token = file_get_contents( $token_file );
        }

        return $api_token;
    }

    /**
     *
     * Get the host info (domain, subdomain, subdirectory)
     *
     * @since    1.0.0
     * @access   public
     */

    public function get_host_info(): string {
        $host     = $_SERVER['HTTP_HOST'] ?? '';
        $site_url = get_site_url();
        $site_url = preg_replace( '#^https?://#', '', $site_url );

        if ( ! empty( $site_url ) && ! empty( $host ) && strpos( $site_url, $host ) === 0 ) {
            if ( $site_url === $host ) {
                return $host;
            } else {
                return substr( $site_url, strlen( $host ) + 1 );
            }
        }

        return $host;
    }

    public function ajax_error_message( string $message, string $display_error ): void {
        error_log( 'Error: ' . $message );
        if ( ! empty( $display_error ) ) {
            wp_send_json_error( $display_error );
        }
    }

    public function is_preview_domain(): bool {
        if ( function_exists( 'getallheaders' ) ) {
            $headers = getallheaders();
        }

        if ( isset( $headers['X-Preview-Indicator'] ) && $headers['X-Preview-Indicator'] ) {
            return true;
        }

        return false;
    }

    public function get_url_protocol(): string {
        $protocol = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

        return $protocol;
    }

    public function overwrite_url_host( string $url, string $new_host ): string {
        $parsed_url = parse_url( $url );

        if ( $parsed_url === false || ! isset( $parsed_url['scheme'] ) || ! isset( $parsed_url['host'] ) ) {
            error_log( 'Error: Invalid URL' );

            return false;
        }

        $parsed_url['host'] = $new_host;

        $modified_url = $parsed_url['scheme'] . '://';
        if ( isset( $parsed_url['user'] ) && isset( $parsed_url['pass'] ) ) {
            $modified_url .= $parsed_url['user'] . ':' . $parsed_url['pass'] . '@';
        }
        $modified_url .= $parsed_url['host'];
        if ( isset( $parsed_url['port'] ) ) {
            $modified_url .= ':' . $parsed_url['port'];
        }
        if ( isset( $parsed_url['path'] ) ) {
            $modified_url .= $parsed_url['path'];
        }
        if ( isset( $parsed_url['query'] ) ) {
            $modified_url .= '?' . $parsed_url['query'];
        }
        if ( isset( $parsed_url['fragment'] ) ) {
            $modified_url .= '#' . $parsed_url['fragment'];
        }

        return $modified_url;
    }

    public function has_taxonomy_for_post_type( string $post_type, string $taxonomy_slug ): bool {
        $taxonomy_object = get_taxonomy( $taxonomy_slug );

        if ( ! $taxonomy_object ) {
            return false;
        }

        return in_array( $post_type, $taxonomy_object->object_type, true );
    }

    public function post_type_supports_featured_image( string $post_type ): bool {
        $post_type_object = get_post_type_object( $post_type );

        if ( $post_type_object && post_type_supports( $post_type, 'thumbnail' ) ) {
            return true;
        }

        return false;
    }

    public function sanitize_html_string( $html_string ) {
        $cleaned_string = stripslashes( $html_string );
        $cleaned_string = preg_replace( '/\\\\\'/', "'", $cleaned_string ); // Replace escaped single quotes.

        return $cleaned_string;
    }

    public function error_log( string $message ): void {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
            error_log( print_r( $message, true ) );
        }
    }

    public function add_vue_instance(): void {
        ob_start(); ?>
        <div id="vue-app"></div>
        <?php
        echo ob_get_clean();
    }

    public function get_edit_site_url(): string {
        if ( wp_is_block_theme() ) {
            return '';
        }

        $show_on_front = get_option( 'show_on_front' );
        $front_page_id = get_option( 'page_on_front' );

        if ( $show_on_front === self::HOMEPAGE_DISPLAY && $front_page_id ) {
            return add_query_arg(
                array(
                    'post'   => $front_page_id,
                    'action' => 'edit',
                ),
                admin_url( 'post.php' )
            );
        }

        return '';
    }
}

$hostiner_helper = new Hostinger_Ai_Assistant_Helper();
