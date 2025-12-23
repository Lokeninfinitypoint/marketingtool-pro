<?php

use Hostinger\Amplitude\AmplitudeManager;

class Hostinger_Ai_Assistant_Amplitude {
    private const AMPLITUDE_ENDPOINT  = '/v3/wordpress/plugin/trigger-event';
    private const PLUGIN_INSTALL_TYPE = 'hostinger_ai_plugin_installation_type';

    private AmplitudeManager $amplitude_manager;

    public function __construct(
        AmplitudeManager $amplitude_manager
    ) {
        $this->amplitude_manager = $amplitude_manager;

        add_action( 'transition_post_status', array( $this, 'track_published_post' ), 10, 3 );
        add_action( 'transition_post_status', array( $this, 'track_published_post_updates' ), 10, 3 );
        add_action( 'transition_post_status', array( $this, 'track_published_product' ), 10, 3 );
        add_action( 'activate_hostinger_ai_assistant', array( $this, 'track_installed_plugin' ), 10, 3 );
    }

    public function ai_content_created( string $post_type, string $location = 'ai_assistant_ui', array $additional_properties = array() ): void {
        $endpoint = self::AMPLITUDE_ENDPOINT;
        $params   = array(
            'action'       => Hostinger_Ai_Assistant_Amplitude_Actions::AI_CONTENT_CREATE,
            'content_type' => $post_type,
            'location'     => $location,
        );

        $headers = array();

        if ( ! empty( $additional_properties['correlation_id'] ) ) {
            $headers['x-correlation-id'] = $additional_properties['correlation_id'];
        }

        $this->amplitude_manager->sendRequest( $endpoint, $params, $headers );
    }

    public function ai_content_saved( string $post_type, int $post_id, string $location = 'ai_assistant_ui', array $additional_properties = array() ): void {
        $endpoint = self::AMPLITUDE_ENDPOINT;
        $params   = array(
            'action'       => Hostinger_Ai_Assistant_Amplitude_Actions::AI_CONTENT_CREATED,
            'content_type' => $post_type,
            'content_id'   => $post_id,
            'location'     => $location,
        );

        $headers = array();

        if ( ! empty( $additional_properties['correlation_id'] ) ) {
            $headers['x-correlation-id'] = $additional_properties['correlation_id'];
        }

        $this->amplitude_manager->sendRequest( $endpoint, $params, $headers );
    }

    public function ai_content_published( string $post_type, int $post_id, string $location = 'ai_assistant_ui', array $additional_properties = array() ): void {
        $endpoint = self::AMPLITUDE_ENDPOINT;
        $params   = array(
            'action'       => Hostinger_Ai_Assistant_Amplitude_Actions::AI_CONTENT_CREATED_PUBLISHED,
            'content_type' => $post_type,
            'content_id'   => $post_id,
            'location'     => $location,
        );

        $headers = array();

        if ( ! empty( $additional_properties['correlation_id'] ) ) {
            $headers['x-correlation-id'] = $additional_properties['correlation_id'];
        }

        update_option( 'hostinger_content_published', true );
        $this->amplitude_manager->sendRequest( $endpoint, $params, $headers );
    }

    public function track_published_post( string $new_status, string $old_status, WP_Post $post ): void {

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return;
        }

        $post_id                   = $post->ID;
        $ai_content_generated      = get_post_meta( $post_id, 'hostinger_ai_generated', true );
        static $is_action_executed = array();

        if ( isset( $is_action_executed[ $post_id ] ) ) {
            return;
        }

        if ( ( 'draft' === $old_status || 'auto-draft' === $old_status ) && $new_status === 'publish' ) {

            if ( $ai_content_generated && ! wp_is_post_revision( $post_id ) ) {
                $post_type      = get_post_type( $post_id );
                $correlation_id = get_post_meta( $post_id, 'hts_correlation_id', true );
                $this->ai_content_published( $post_type, $post_id, 'ai_assistant_ui', array( 'correlation_id' => $correlation_id ) );
                delete_post_meta( $post_id, 'hts_correlation_id' );
                $is_action_executed[ $post_id ] = true;
            }
        }
    }

    public function track_published_post_updates( string $new_status, string $old_status, WP_Post $post ): void {

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return;
        }

        // Fix issue with hook running twice.
        if ( ! empty( $_REQUEST['meta-box-loader'] ) ) {
            return;
        }

        $post_id                   = $post->ID;
        static $is_action_executed = array();

        if ( isset( $is_action_executed[ $post_id ] ) ) {
            return;
        }

        if ( $new_status === 'publish' ) {

            $has_ai_block = has_block( 'hostinger-ai-plugin/block', $post );

            if ( $has_ai_block && ! wp_is_post_revision( $post_id ) ) {
                $post_type = get_post_type( $post_id );
                $this->ai_content_published( $post_type, $post_id, 'ai_assistant_block' );
                $is_action_executed[ $post_id ] = true;
            }
        }
    }

    public function track_published_product( string $new_status, string $old_status, WP_Post $post ): void {

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return;
        }

        $post_id                   = $post->ID;
        $ai_content_generated      = get_option( 'hts_woo_product_description_created', array() );
        $location                  = 'woocommerce_ui';
        static $is_action_executed = array();

        if ( isset( $is_action_executed[ $post_id ] ) ) {
            return;
        }

        if ( $post->post_type === 'product' && $new_status === 'publish' && $old_status !== 'publish' ) {

            if ( in_array( $post_id, $ai_content_generated, true ) && ! wp_is_post_revision( $post_id ) ) {
                $post_type = get_post_type( $post_id );
                $this->ai_content_published( $post_type, $post_id, $location );
                $index = array_search( $post_id, $ai_content_generated, true );

                // If $post_id exists in $ai_content_generated, remove it.
                if ( $index !== false ) {
                    unset( $ai_content_generated[ $index ] );
                    update_option( 'hts_woo_product_description_created', $ai_content_generated );
                }

                $is_action_executed[ $post_id ] = true;
            }
        }
    }

    public function track_installed_plugin(): void {
        $endpoint                  = self::AMPLITUDE_ENDPOINT;
        $plugin_install_type       = get_option( self::PLUGIN_INSTALL_TYPE, 'WordPress' );
        static $is_action_executed = false;

        if ( $is_action_executed ) {
            return;
        }

        $params = array(
            'action'         => Hostinger_Ai_Assistant_Amplitude_Actions::AI_PLUGIN_INSTALLED,
            'location'       => $plugin_install_type,
            'plugin_name'    => basename( plugin_dir_path( dirname( __DIR__, 1 ) ) ),
            'plugin_version' => HOSTINGER_AI_ASSISTANT_VERSION,
        );

        $is_action_executed = true;
        $this->amplitude_manager->sendRequest( $endpoint, $params );
        delete_option( self::PLUGIN_INSTALL_TYPE );
    }
}
