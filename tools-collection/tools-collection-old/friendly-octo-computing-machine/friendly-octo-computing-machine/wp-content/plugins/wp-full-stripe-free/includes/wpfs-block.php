<?php
/**
 * Block class.
 */
class MM_WPFS_Block {

	const WPFS_PLUGIN_SLUG = 'wp-full-stripe';
	const WPFS_REST_API_VERSION = 'v1';
	const WPFS_REST_ROUTE = 'block/forms';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_block' ] );
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register block.
	 * 
	 * @return void
	 */
	public function register_block() {
		register_block_type( WP_FULL_STRIPE_PATH . '/assets/build' );
	}

	/**
	 * Rest Namespace.
	 */
	private function get_namespace() {
		return self::WPFS_PLUGIN_SLUG . '/' . self::WPFS_REST_API_VERSION;
	}

	/**
	 * Rest Route.
	 */
	private function get_route() {
		return '/' . self::WPFS_REST_ROUTE;
	}

	/**
	 * Register routes.
	 * 
	 * @return void
	 */
	public function register_routes() {
		register_rest_route( $this->get_namespace(), $this->get_route(), [
			[
				'methods' => WP_REST_Server::READABLE,
				'callback' => [ $this, 'callback' ],
				'permission_callback' => [ $this, 'permission_callback' ],
			]
		]);
	}

	/**
	 * Callback.
	 * 
	 * @return WP_REST_Response
	 */
	public function callback() {
		$db    = new MM_WPFS_Database();
		$forms = $db->getAllForms();

		for ( $idx = 0; $idx < count( $forms ); $idx++ ) {
			$form = $forms[ $idx ];
			$form->shortcode = MM_WPFS_Shortcode::createShortCodeByForm( $form );
			$forms[ $idx ] = $form;
		}

		return rest_ensure_response( $forms );
	}

	/**
	 * Permission callback.
	 * 
	 * @return bool
	 */
	public function permission_callback() {
		return current_user_can( 'edit_posts' );
	}
}
