<?php
/**
 * Plugin Name: Ajax Load More for REST API
 * Plugin URI: https://connekthq.com/plugins/ajax-load-more/extensions/rest-api/
 * Description: An Ajax Load More extension for infinite scrolling with the WordPress REST API
 * Text Domain: ajax-load-more-rest-api
 * Author: Darren Cooney
 * Author URI: https://connekthq.com
 * Version: 1.2.6
 * License: GPL
 * Copyright: Connekt Media & Darren Cooney
 * Requires Plugins: ajax-load-more
 *
 * @package ALMRESTAPI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Bail if accessed directly.
}

/**
 * Define plugin constants.
 */
define( 'ALM_RESTAPI_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALM_RESTAPI_URL', plugins_url( '', __FILE__ ) );
define( 'ALM_RESTAPI_VERSION', '1.2.6' );
define( 'ALM_RESTAPI_RELEASE', 'June 5, 2025' );

/**
 * Display admin notice if plugin does not meet the requirements.
 */
function alm_restapi_admin_notice() {
	$slug = 'ajax-load-more';
	// Ajax Load More Notice.
	if ( get_transient( 'alm_restapi_admin_notice' ) ) {
		$install_url = get_admin_url() . '/update.php?action=install-plugin&plugin=' . $slug . '&_wpnonce=' . wp_create_nonce( 'install-plugin_' . $slug );
		$message     = '<div class="error">';
		$message    .= '<p>You must install and activate the core Ajax Load More plugin before using the Ajax Load More Rest API extension.</p>';
		$message    .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, 'Install Ajax Load More Now' ) . '</p>';
		$message    .= '</div>';
		echo wp_kses_post( $message );
		delete_transient( 'alm_restapi_admin_notice' );
	}
}
add_action( 'admin_notices', 'alm_restapi_admin_notice' );

if ( ! class_exists( 'ALMRESTAPI' ) ) :

	/**
	 * Initiate the class.
	 */
	class ALMRESTAPI {

		/**
		 * Set up construct functions.
		 */
		public function __construct() {
			add_action( 'alm_rest_api_installed', [ &$this, 'alm_rest_api_installed' ] );
			add_action( 'alm_rest_api_settings', [ &$this, 'alm_rest_api_settings' ] );
			add_action( 'wp_enqueue_scripts', [ &$this, 'alm_rest_api_enqueue_scripts' ] );
			add_action( 'alm_get_rest_api_template', [ &$this, 'alm_get_rest_api_template' ], 10, 2 );
			add_filter( 'alm_rest_api_shortcode', [ &$this, 'alm_rest_api_shortcode' ], 10, 6 );
			load_plugin_textdomain( 'ajax-load-more-rest-api', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
			require_once ALM_RESTAPI_PATH . 'endpoints.php';
		}

		/**
		 * Enqueue the scripts
		 *
		 *  @since 1.0
		 */
		public function alm_rest_api_enqueue_scripts() {
			wp_enqueue_script( 'wp-util' ); // Load WP Utils for templates.
		}

		/**
		 * Adds underscore template to page.
		 *
		 * @param string $repeater The Repeater Template name.
		 * @param string $type The Repeater Template type.
		 * @since 1.0
		 */
		public function alm_get_rest_api_template( $repeater, $type ) {
			if ( is_admin() || defined( 'REST_REQUEST' ) && REST_REQUEST ) {
				/**
				 * Bail if in WP admin.
				 *
				 * @see https://wordpress.stackexchange.com/a/367515/12868
				 */
				return;
			}
			$template = alm_get_current_repeater( $repeater, $type );
			require $template;
		}

		/**
		 * An empty function to determine if REST API is installed.
		 *
		 *  @since 1.0
		 */
		public function alm_rest_api_installed() {
			// phpcs:ignore
			// Empty return
		}

		/**
		 * Build REST API shortcode params and send back to core ALM as data attributes.
		 *
		 * @param  string $restapi     The value for restapi (true/false).
		 * @param  string $base_url    The Rest API base URL.
		 * @param  string $namespace   The Rest API namespace.
		 * @param  string $endpoint    The Rest API endpoint.
		 * @param  string $template_id The template ID.
		 * @param  string $debug       Should the returned data be debugged.
		 * @return string              The generated HTML data attributes.
		 * @since 1.0
		 */
		public function alm_rest_api_shortcode( $restapi, $base_url, $namespace, $endpoint, $template_id, $debug ) {
			$return  = ' data-restapi="' . $restapi . '"';
			$return .= ' data-restapi-base-url="' . $base_url . '"';
			$return .= ' data-restapi-namespace="' . $namespace . '"';
			$return .= ' data-restapi-endpoint="' . $endpoint . '"';
			$return .= ' data-restapi-template-id="' . $template_id . '"';
			$return .= ' data-restapi-debug="' . $debug . '"';
			return $return;
		}

		/**
		 * Create the Previous Post settings panel.
		 *
		 * @since 1.0
		 */
		public function alm_rest_api_settings() {
			register_setting(
				'alm_rest_api_license',
				'alm_rest_api_license_key',
				'alm_rest_api_sanitize_license'
			);

			add_settings_section(
				'alm_rest_api_settings',
				esc_html__( 'REST API Settings', 'ajax-load-more-rest-api' ),
				'alm_rest_api_callback',
				'ajax-load-more'
			);

			add_settings_field(
				'_alm_rest_api_base_url',
				esc_html__( 'Base URL', 'ajax-load-more-rest-api' ),
				'alm_rest_api_base_url_callback',
				'ajax-load-more',
				'alm_rest_api_settings'
			);

			add_settings_field(
				'_alm_rest_api_namespace',
				esc_html__( 'Namespace', 'ajax-load-more-rest-api' ),
				'alm_rest_api_namespace_callback',
				'ajax-load-more',
				'alm_rest_api_settings'
			);

			add_settings_field(
				'_alm_rest_api_endpoint',
				esc_html__( 'Endpoint', 'ajax-load-more-rest-api' ),
				'alm_rest_api_endpoint_callback',
				'ajax-load-more',
				'alm_rest_api_settings'
			);
		}
	}

	/**
	 * REST API Setting Heading
	 *
	 * @since 1.0
	 */
	function alm_rest_api_callback() {
		$html = '<p>' . __( 'Set default parameters for your installation of the <a href="http://connekthq.com/plugins/ajax-load-more/add-ons/rest-api/">REST API</a> add-on.', 'ajax-load-more-rest-api' ) . '</p>';
		echo wp_kses_post( $html );
	}

	/**
	 * Base URL setting.
	 *
	 * @since 1.0
	 */
	function alm_rest_api_base_url_callback() {
		$options = get_option( 'alm_settings' );
		if ( ! isset( $options['_alm_rest_api_base_url'] ) ) {
			$options['_alm_rest_api_base_url'] = '/wp-json';
		}

		$html  = '<label for="alm_settings[_alm_rest_api_base_url]">' . __( 'Set default shortcode value for [<em>restapi_base</em>].', 'ajax-load-more-rest-api' ) . '</label><br/>';
		$html .= '<input type="text" id="alm_settings[_alm_rest_api_base_url]" name="alm_settings[_alm_rest_api_base_url]" value="' . $options['_alm_rest_api_base_url'] . '" placeholder="/wp-json" /> ';
		echo $html; //phpcs:ignore
	}

	/**
	 * Rest Namespace setting.
	 *
	 * @since 1.0
	 */
	function alm_rest_api_namespace_callback() {
		$options = get_option( 'alm_settings' );
		if ( ! isset( $options['_alm_rest_api_namespace'] ) ) {
			$options['_alm_rest_api_namespace'] = 'ajaxloadmore';
		}

		$html  = '<label for="alm_settings[_alm_rest_api_namespace]">' . __( 'Set default shortcode value for [<em>restapi_namespace</em>].', 'ajax-load-more-rest-api' ) . '</label><br/>';
		$html .= '<input type="text" id="alm_settings[_alm_rest_api_namespace]" name="alm_settings[_alm_rest_api_namespace]" value="' . $options['_alm_rest_api_namespace'] . '" placeholder="ajaxloadmore" /> ';
		echo $html; //phpcs:ignore
	}

	/**
	 *  Rest Endpoint setting.
	 *
	 *  @since 1.0
	 */
	function alm_rest_api_endpoint_callback() {
		$options = get_option( 'alm_settings' );
		if ( ! isset( $options['_alm_rest_api_endpoint'] ) ) {
			$options['_alm_rest_api_endpoint'] = 'posts';
		}

		$html  = '<label for="alm_settings[_alm_rest_api_endpoint]">' . __( 'Set default shortcode value for [<em>restapi_endpoint</em>].', 'ajax-load-more-rest-api' ) . '</label><br/>';
		$html .= '<input type="text" id="alm_settings[_alm_rest_api_endpoint]" name="alm_settings[_alm_rest_api_endpoint]" value="' . $options['_alm_rest_api_endpoint'] . '" placeholder="posts" /> ';
		echo $html; //phpcs:ignore
	}

	/**
	 * The main function responsible for returning Ajax Load More REST API.
	 *
	 * @since 1.0
	 */
	function alm_restapi() {
		global $alm_restapi;
		if ( ! isset( $alm_restapi ) ) {
			$alm_restapi = new ALMRESTAPI();
		}
		return $alm_restapi;
	}
	alm_restapi();

endif;
