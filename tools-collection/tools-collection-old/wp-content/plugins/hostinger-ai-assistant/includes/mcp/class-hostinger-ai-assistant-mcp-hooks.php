<?php

class Hostinger_Ai_Assistant_Mcp_Hooks {
    public function init(): void {
        add_filter( 'woocommerce_rest_prepare_product_object', array( $this, 'filter_product_meta_fields' ), 10, 3 );
        add_filter( 'wordpress_mcp_rest_api_response_error', array( $this, 'filter_response_error' ), 10, 2 );
    }

    public function filter_product_meta_fields( $response, $post, $request ): WP_REST_Response {
        if ( isset( $response->data['meta_data'] ) && is_array( $response->data['meta_data'] ) ) {
            $response->data['meta_data'] = array_values(
                array_filter(
                    $response->data['meta_data'],
                    function ( $meta ) {
                        return ! str_starts_with( $meta->key, '_uag' );
                    }
                )
            );
        }

        return $response;
    }

    public function filter_response_error( WP_Error $error, string $tool_name ): WP_Error {
        if ( $tool_name === 'wp_get_post' && isset( $error->errors['rest_post_invalid_id'] ) ) {
            $error->errors['rest_post_invalid_id'][0] = __( 'Invalid post ID, specified post ID could be from different custom post type.', 'hostinger-ai-assistant' );
        }

        return $error;
    }
}
