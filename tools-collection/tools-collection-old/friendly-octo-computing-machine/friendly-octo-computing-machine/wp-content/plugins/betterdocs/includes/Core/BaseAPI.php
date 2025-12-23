<?php

namespace WPDeveloper\BetterDocs\Core;

use WP_Error;
use WP_REST_Server;
use WP_REST_Response;
use WPDeveloper\BetterDocs\Utils\Base;
use WPDeveloper\BetterDocs\Dependencies\DI\Container;

abstract class BaseAPI extends Base {
	protected $namespace = 'betterdocs';
	protected $version   = 'v1';
	/**
	 * Summary of settings
	 * @var Settings
	 */
	protected $settings;

	/**
	 * Summary of container
	 * @var Container
	 */
	protected $container;

	public function __construct( Settings $settings, Container $container ) {
		$this->settings  = $settings;
		$this->container = $container;

		add_filter( 'rest_prepare_doc_category', [ $this, 'rest_prepare_doc_category' ], 10, 3 );
	}

	public function get_namespace() {
		return $this->namespace . '/' . $this->version;
	}

	public function get( $endpoint, $callback, $args = [] ) {
		return $this->register_endpoint( $endpoint, $callback, $args, WP_REST_Server::READABLE );
	}

	public function post( $endpoint, $callback, $args = [] ) {
		return $this->register_endpoint( $endpoint, $callback, $args );
	}

	public function permission_check() {
		return true;
	}

	protected function register_endpoint( $endpoint, $callback, $args = [], $methods = WP_REST_Server::CREATABLE ) {
		return register_rest_route(
			$this->get_namespace(),
			$endpoint,
			[
				'methods'             => $methods,
				'callback'            => $callback,
				'permission_callback' => [ $this, 'permission_check' ],
				'args'                => $args
			]
		);
	}

	public function register_field( $type, $attribute, $args = [] ) {
		$args = wp_parse_args(
			$args,
			[
				'update_callback' => null,
				'schema'          => [
					'description' => 'Holds the thumbnail URL of doc category',
					'type'        => 'string',
					'format'      => 'url'
				]
			]
		);

		return register_rest_field( $type, $attribute, $args );
	}

	/**
	 * @param $data
	 *
	 * @return WP_REST_Response
	 */
	public function success( $data ) {
		$_data = [
			'success' => true,
			'data'    => $data
		];

		if ( is_string( $data ) ) {
			$_data['message'] = $data;

			unset( $_data['data'] );
		}

		return new WP_REST_Response( $_data, 200 );
	}

	/**
	 * @param $error_code string
	 * @param $error_message string|array
	 * @param $endpoint string
	 * @param $status int
	 * @param $additional_data array
	 *
	 * @return WP_Error
	 */
	public function error( $error_code, $error_message, $status = 500, $additional_data = [] ) {
		$additional_data['status'] = $status;
		return new WP_Error( $error_code, $error_message, $additional_data );
	}

	abstract public function register();

	public function rest_prepare_doc_category( $data, $category, $request ) {
		$nested_subcategory = $request->get_param( 'nested_subcategory', false );
		$_counts            = betterdocs()->query->get_docs_count( $category, $nested_subcategory );

		// Add the custom data to the response
		$data->data['count'] = $_counts;
		return $data;
	}
}
