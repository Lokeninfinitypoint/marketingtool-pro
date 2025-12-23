<?php

use Alley\WP\Block_Converter\Block_Converter;
use Hostinger\Amplitude\AmplitudeManager;
use Hostinger\WpHelper\Config;
use Hostinger\WpHelper\Constants;
use Hostinger\WpHelper\Requests\Client;
use Hostinger\WpHelper\Utils as Helper;

class Hostinger_Ai_Assistant_Requests {
    private const GENERATE_CONTENT_ACTION = '/v3/wordpress/plugin/generate-content';
    private const GET_CONTENT_IMAGES_URLS = '/v3/wordpress/plugin/search-images';
    private const WOO_DESCRIPTION_CREATED = 'hts_woo_product_description_created';

    private Hostinger_Ai_Assistant_Requests_Client $client;
    private Hostinger_Ai_Assistant_Amplitude $amplitude;
    private Hostinger_Ai_Assistant_Content_Generation $generate_content;
    private Hostinger_Ai_Assistant_Config $config_handler;
    private Hostinger_Ai_Assistant_Errors $error_handler;
    private Hostinger_Ai_Assistant_Helper $helper;

    public function __construct() {
        $this->helper         = new Hostinger_Ai_Assistant_Helper();
        $this->config_handler = new Hostinger_Ai_Assistant_Config();
        $this->error_handler  = new Hostinger_Ai_Assistant_Errors();
        $this->client         = new Hostinger_Ai_Assistant_Requests_Client(
            $this->config_handler->get_config_value( 'base_rest_uri', HOSTINGER_AI_ASSISTANT_REST_URI ),
            array(
                Hostinger_Ai_Assistant_Config::TOKEN_HEADER  => $this->helper::get_api_token(),
                Hostinger_Ai_Assistant_Config::DOMAIN_HEADER => $this->helper->get_host_info(),
            )
        );

        $helper = new Helper();
        $config = new Config();
        $client = new Client(
            $config->getConfigValue( 'base_rest_uri', Constants::HOSTINGER_REST_URI ),
            array(
                Config::TOKEN_HEADER  => $helper->getApiToken(),
                Config::DOMAIN_HEADER => $helper->getHostInfo(),
            )
        );

        $amplitude_manager      = new AmplitudeManager( $helper, $config, $client );
        $this->amplitude        = new Hostinger_Ai_Assistant_Amplitude( $amplitude_manager );
        $this->generate_content = new Hostinger_Ai_Assistant_Content_Generation();

        add_action( 'init', array( $this, 'define_ajax_events' ), 0 );
    }

    public function define_ajax_events(): void {
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }

        $events = array(
            'get_content_from_description',
            'redirect_to_post_editor_with_content',
            'redirect_to_published_post',
            'woo_product_description_create',
            'upload_unsplash_image',
        );

        foreach ( $events as $event ) {
            $ajax_event = 'hts_' . $event;
            add_action( 'wp_ajax_' . $ajax_event, array( $this, $event ) );
        }
    }

    public function woo_product_description_create(): void {
        $nonce     = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
        $post_id   = isset( $_POST['post_id'] ) ? sanitize_text_field( $_POST['post_id'] ) : 1;
        $error_msg = $this->error_handler->get_error_message( 'action_failed' );
        $post_type = get_post_type( $post_id ) ?? 'product';

        if ( ! wp_verify_nonce( $nonce, 'generate_content' ) ) {
            $this->helper->ajax_error_message( $error_msg, $error_msg );
        }

        $this->amplitude->ai_content_saved( $post_type, $post_id, 'woocommerce_ui' );
        $existing_values   = get_option( self::WOO_DESCRIPTION_CREATED, array() );
        $existing_values[] = $post_id;
        update_option( self::WOO_DESCRIPTION_CREATED, $existing_values );
    }

    public function get_content_from_description(): void {
        $error_msg         = $this->error_handler->get_error_message( 'action_failed' );
        $unexpected_error  = $this->error_handler->get_error_message( 'unexpected_error' );
        $server_error      = $this->error_handler->get_error_message( 'server_error' );
        $empty_description = $this->error_handler->get_error_message( 'empty_description' );

        try {
            $nonce          = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
            $description    = isset( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';
            $correlation_id = isset( $_POST['correlation_id'] ) ? sanitize_text_field( $_POST['correlation_id'] ) : '';
            $post_type      = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : 'blog_post';
            $voice_tone     = isset( $_POST['voice_tone'] ) ? sanitize_text_field( $_POST['voice_tone'] ) : 'neutral';
            $focus_keywords = isset( $_POST['focus_keywords'] ) ? sanitize_text_field( $_POST['focus_keywords'] ) : '';
            $content_length = isset( $_POST['content_length'] ) ? sanitize_text_field( $_POST['content_length'] ) : '150-300';
            $location       = isset( $_POST['location'] ) ? sanitize_text_field( $_POST['location'] ) : 'ai_assistant_ui';

            if ( ! wp_verify_nonce( $nonce, 'generate_content' ) ) {
                $this->helper->ajax_error_message( $error_msg, $error_msg );
            }

            if ( empty( $description ) ) {
                $this->helper->ajax_error_message( $empty_description, $empty_description );
            }

            $validated_post_type      = $this->generate_content->validate_post_type( $post_type );
            $post_type                = $this->generate_content->map_post_type( $validated_post_type );
            $content_length_validated = $this->generate_content->validate_content_length( $content_length );

            if ( $post_type === 'product_description' ) {
                $content_length_validated = $this->generate_content->validate_content_length( $content_length, true );
            }

            $data = array(
                'post_type'   => $post_type,
                'tone'        => $voice_tone,
                'length'      => $content_length_validated,
                'description' => $description,
            );

            if ( ! empty( $focus_keywords ) ) {
                $data['focus_keyword'] = $focus_keywords;
            }

            $response = $this->client->get( self::GENERATE_CONTENT_ACTION, $data, array( 'X-Correlation-ID' => $correlation_id ) );

            $response_code = wp_remote_retrieve_response_code( $response );
            $response_body = wp_remote_retrieve_body( $response );

            if ( is_wp_error( $response ) || $response_code !== 200 ) {
                $error_message = isset( json_decode( $response_body )->error->message )
                    ? json_decode( $response_body )->error->message
                    : $unexpected_error;
                $this->helper->ajax_error_message( $error_message, $server_error );
            } else {

                $generated_content = reset( json_decode( $response['body'] )->data );

                if ( isset( $generated_content->tags[0] ) && $generated_content->tags[0] !== '' ) {
                    $ai_image_data = $this->get_ai_image_data( $description );
                    if ( $ai_image_data->photo_image_url ) {
                        $generated_content->image = $ai_image_data->photo_image_url;
                    }
                }

                if ( ! empty( $generated_content->content ) ) {
                    $converter = new Block_Converter( $generated_content->content );

                    $generated_content->content_blocks = $converter->convert();
                }

                $this->amplitude->ai_content_created( $post_type, $location, array( 'correlation_id' => $correlation_id ) );

                wp_send_json_success( $generated_content );
            }
        } catch ( Exception $exception ) {
            $this->helper->ajax_error_message( 'Error: ' . $exception->getMessage(), $server_error );
        }
    }

    public function redirect_to_post_editor_with_content(): void {
        $this->generate_content->process_post_action( 'create' );
    }

    public function redirect_to_published_post(): void {
        $this->generate_content->process_post_action( 'publish' );
    }

    public function get_ai_image_data( string $description ): object {
        $unexpected_error = $this->error_handler->get_error_message( 'unexpected_error' );

        if ( ! isset( $description ) || $description === '' ) {
            $this->helper->ajax_error_message( $unexpected_error, $unexpected_error );
        }

        $domain = parse_url( get_site_url(), PHP_URL_HOST );

        try {
            $response = $this->client->post(
                self::GET_CONTENT_IMAGES_URLS,
                array(
                    'domain'      => $domain,
                    'description' => $description,
                    'limit'       => 1,
                )
            );

            $response_code = wp_remote_retrieve_response_code( $response );
            $response_body = wp_remote_retrieve_body( $response );
            $response_data = json_decode( $response_body )->data->list;

            if ( empty( $response_data ) ) {
                return new stdClass();
            }

            if ( is_wp_error( $response ) || $response_code !== 200 ) {
                $error_message = isset( json_decode( $response_body )->error->message ) ? json_decode( $response_body )->error->message : $unexpected_error;
                $this->helper->ajax_error_message( $error_message, $unexpected_error );
            } else {
                $response = reset( json_decode( $response_body )->data->list );

                return $response;
            }
        } catch ( Exception $exception ) {
            $this->helper->ajax_error_message( $exception->getMessage(), $unexpected_error );
        }

        return new stdClass();
    }
}

new Hostinger_Ai_Assistant_Requests();
