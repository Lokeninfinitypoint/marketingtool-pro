<?php

namespace TI\Template_Cloud\Modules;

use TI\Template_Cloud\Models\Access_Keys_Model;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_Term;

class Server implements Module_Interface {

	public const API_NAMESPACE = 'ti-template-cloud/v1';

	/**
	 * Initialize the module.
	 */
	public function init() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register the REST API routes.
	 */
	public function register_routes() {
		register_rest_route(
			self::API_NAMESPACE,
			'/patterns',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_patterns' ],
				'permission_callback' => [ $this, 'verify_api_key' ],
			)
		);

		register_rest_route(
			self::API_NAMESPACE,
			'/keys',
			[
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'upsert_key' ],
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'args'                => [
					'name'        => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
					'mode'        => [
						'required'          => true,
						'sanitize_callback' => [ Access_Keys_Model::class, 'sanitize_key_mode' ],
					],
					'collections' => [
						'required'          => true,
						'sanitize_callback' => [ Access_Keys_Model::class, 'sanitize_term_ids_array' ],
					],
					'categories'  => [
						'required'          => true,
						'sanitize_callback' => [ Access_Keys_Model::class, 'sanitize_term_ids_array' ],
					],
					'key'         => [
						'required'          => false,
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);

		register_rest_route(
			self::API_NAMESPACE,
			'/keys/(?P<key>[a-zA-Z0-9-_]+)',
			[
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => [ $this, 'delete_key' ],
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'args'                => [
					'key' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
	}

	/**
	 * Upsert an access key.
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function upsert_key( WP_REST_Request $request ) {
		$params = $request->get_json_params();

		if ( ! is_array( $params ) || ! isset( $params['name'], $params['mode'] ) ) {
			return new WP_REST_Response( [ 'message' => __( 'Invalid Request. Please reload the page and try again.', 'template-cloud-server' ) ], 400 );
		}

		if ( $params['mode'] !== 'all' && ! isset( $params['collections'], $params['categories'] ) ) {
			return new WP_REST_Response( [ 'message' => __( 'Invalid Request. A key should have at least one category or collection.', 'template-cloud-server' ) ], 400 );
		}

		$keys_model = new Access_Keys_Model( isset( $params['key'] ) ? sanitize_text_field( $params['key'] ) : null );

		$keys_model
			->set_name( $params['name'] )
			->set_mode( $params['mode'] );

		if ( $params['mode'] !== 'all' ) {
			$keys_model->set_collections( $params['collections'] )
						->set_categories( $params['categories'] );
		}

		if ( ! isset( $params['key'] ) ) {
			$keys_model
				->set_created_at( time() )
				->set_key( Access_Keys_Model::generate_key() );
		} else {
			$keys_model->set_key( $params['key'] );

			if ( ! $keys_model->exists() ) {
				return new WP_REST_Response(
					[
						'message' => __( 'The key you are trying to update does not exist.', 'template-cloud-server' ),
					],
					404
				);
			}
		}

		$keys_model->save();

		return new WP_REST_Response(
			[
				'keys'    => $keys_model->get_saved_data(),
				'success' => true,
			]
		);
	}

	/**
	 * Delete an access key.
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function delete_key( WP_REST_Request $request ) {
		$key = $request->get_param( 'key' );

		if ( empty( $key ) ) {
			return new WP_REST_Response(
				[
					'message' => __( 'Invalid Request. Please reload the page and try again.', 'template-cloud-server' ),
				],
				400
			);
		}

		$keys_model = new Access_Keys_Model( $key );

		if ( ! $keys_model->exists() ) {
			return new WP_REST_Response(
				[
					'message' => __( 'The key you are trying to delete does not exist.', 'template-cloud-server' ),
				],
				404
			);
		}

		$keys_model->delete();

		return new WP_REST_Response(
			[
				'keys'    => $keys_model->get_saved_data(),
				'success' => true,
			]
		);
	}

	/**
	 * Verify the API key.
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function verify_api_key( WP_REST_Request $request ) {
		$api_key = $request->get_header( 'X-API-Key' );

		if ( ! $api_key ) {
			return false;
		}

		$access_key_model = new Access_Keys_Model( $api_key );

		if ( ! $access_key_model->exists() ) {
			return false;
		}

		$data = $access_key_model->to_array();

		$request->set_param( 'mode', $data['mode'] );
		$request->set_param( 'collections', $data['collections'] );
		$request->set_param( 'categories', $data['categories'] );
		$request->set_param( 'name', $data['name'] );

		return true;
	}

	/**
	 * Get patterns.
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function get_patterns( WP_REST_Request $request ) {
		$access_mode           = $request->get_param( 'mode' );
		$requested_collections = $request->get_param( 'collections' );
		$requested_categories  = $request->get_param( 'categories' );
		$key_name              = $request->get_param( 'name' );

		$terms = get_terms(
			array(
				'taxonomy'   => [ Admin::COLLECTION_TAXONOMY, Admin::CATEGORY_TAXONOMY ],
				'hide_empty' => true,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return new WP_REST_Response(
				[
					'message' => __( 'Could not fetch patterns.', 'template-cloud-server' ),
				],
				500
			);
		}

		if ( $access_mode !== 'all' ) {
			if ( ! is_array( $requested_collections ) || ! is_array( $requested_categories ) ) {
				return new WP_REST_Response(
					[
						'message' => __( 'Something went wrong while getting the pattenrs.', 'template-cloud-server' ),
					],
					500
				);
			}

			$all_terms = array_merge( $requested_categories, $requested_collections );

			$terms = array_filter(
				$terms,
				function ( $collection ) use ( $access_mode, $all_terms ) {
					$is_included = in_array( $collection->term_id, $all_terms );

					return $access_mode === 'include' ? $is_included : ! $is_included;
				}
			);
		}

		$response_data = array();


		foreach ( $terms as $term ) {
			$response_data = array_merge( $response_data, $this->get_patterns_for_taxonomy( $term ) );
		}

		// ensure we don't have 2 patterns with the same 'id' key.
		$seen          = [];
		$response_data = array_filter(
			$response_data,
			function ( $item ) use ( &$seen ) {
				$unique = ! in_array( $item['id'], $seen );
				$seen[] = $item['id'];
				return $unique;
			}
		);


		return new WP_REST_Response(
			[
				'success'  => true,
				'key_name' => $key_name,
				'data'     => $response_data,
			]
		);
	}

	/**
	 * Get patterns for a collection.
	 *
	 * @param WP_Term $term The collection term object.
	 *
	 * @return array
	 */
	public function get_patterns_for_taxonomy( $term ) {
		$cache_data = Cache_Manager::get_collection_from_cache( $term );

		if ( is_array( $cache_data ) && ! empty( $cache_data ) ) {
			return $cache_data;
		}

		$patterns = get_posts(
			array(
				'post_type'      => 'wp_block',
				'posts_per_page' => apply_filters( 'ti_tc_patterns_per_page', 100 ),
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => $term->taxonomy,
						'field'    => 'term_id',
						'terms'    => $term->term_id,
					),
				),
			)
		);

		$patterns_data = array_map(
			function ( $pattern ) {
				return array(
					'id'      => $pattern->ID,
					'title'   => $pattern->post_title,
					'content' => $pattern->post_content,
					'slug'    => $pattern->post_name,
				);
			},
			$patterns
		);

		Cache_Manager::update_collection_cache( $term, $patterns_data );

		return $patterns_data;
	}
}
