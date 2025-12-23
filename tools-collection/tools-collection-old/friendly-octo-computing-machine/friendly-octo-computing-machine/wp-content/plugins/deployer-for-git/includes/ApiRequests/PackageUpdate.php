<?php

namespace DeployerForGit\ApiRequests;

use DeployerForGit\DataManager;
use DeployerForGit\Helper;
use DeployerForGit\Logger;
use DeployerForGit\Subpages\InstallPackage;

/**
 * PackageUpdate class
 * Used to handle the package installation or update
 */
class PackageUpdate {

	/**
	 * Constructor
	 * Register the REST API route
	 */
	public function __construct() {
		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					'dfg/v1',
					'/package_update/',
					array(
						array(
							'methods'             => 'GET',
							'callback'            => array( $this, 'update_package_callback' ),
							'permission_callback' => '__return_true',
						),
						array(
							'methods'             => 'POST',
							'callback'            => array( $this, 'update_package_callback' ),
							'permission_callback' => '__return_true',
						),
					)
				);
			}
		);
	}

	/**
	 * Get the package update URL
	 *
	 * @param string $package_slug slug of the package.
	 * @param string $type (theme|plugin).
	 * @return string $url
	 */
	public static function package_update_url( $package_slug = '', $type = 'theme' ) {
		$type = ( 'theme' === $type ) ? 'theme' : 'plugin';
		$url  = sprintf( '%s/wp-json/dfg/v1/package_update?secret=%s&type=%s&package=%s', site_url(), Helper::get_api_secret(), $type, $package_slug );

		return $url;
	}

	/**
	 * Handle the package update request
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return array $response
	 */
	public function update_package_callback( $request ) {
		// Handle the request and return data as JSON.
		$secret       = $request->get_param( 'secret' );
		$package_slug = $request->get_param( 'package' );
		$package_type = $request->get_param( 'type' ) === 'plugin' ? 'plugin' : 'theme';

		do_action( 'dfg_before_api_package_update', $package_slug, $package_type );

		if ( null === $secret || Helper::get_api_secret() !== $secret ) {
			// set status code to 401.
			status_header( 401 );

			return array(
				'success' => false,
				'message' => 'Invalid secret',
			);
		}

		$data_manager = new DataManager();

		if ( 'theme' === $package_type ) {
			$package_details = $data_manager->get_theme( $package_slug );
		} else {
			$package_details = $data_manager->get_plugin( $package_slug );
		}

		if ( false === $package_details ) {
			status_header( 401 );

			return array(
				'success' => false,
				'message' => 'Invalid package',
			);
		}

		try {
			$provider_class_name = Helper::get_provider_class( $package_details['provider'] );

			$provider = new $provider_class_name( $package_details['repo_url'] );

			$package_slug    = $provider->get_package_slug();
			$package_zip_url = $provider->get_zip_repo_url( $package_details['branch'] );

			// If the provider is GitHub, we need to do some additional checks.
			if ( $provider instanceof \DeployerForGit\Providers\GithubProvider ) {
				$github_token    = isset( $package_details['options']['access_token'] ) ? $package_details['options']['access_token'] : '';
				$package_zip_url = $provider->get_zip_repo_url( $package_details['branch'], $github_token );
			}

			$package_install_options = array();

			if ( array_key_exists( 'is_private_repository', $package_details ) && $package_details['is_private_repository'] === true ) {
				$package_install_options = array(
					'is_private_repository' => true,
					'username'              => $package_details['options']['username'],
					'password'              => $package_details['options']['password'],
					'access_token'          => $package_details['options']['access_token'],
				);
			}

			$package_install_options['wp_json_request'] = true;

			$install_result = InstallPackage::install_package_from_zip_url( $package_zip_url, $package_slug, $package_type, $package_details['provider'], $package_install_options );
		} catch ( \Exception $e ) {
			$install_result = new \WP_Error( 'invalid', $e->getMessage() );
		}

		$success = ( is_wp_error( $install_result ) ? false : true );
		$message = '';

		$logger = new Logger();
		if ( $success ) {
			$logger->log( "Package ({$package_type}) \"{$package_slug}\" successfully updated via wp-json" );
			$message = esc_attr__( 'Package updated successfully.', 'deployer-for-git' );

			$flush_cache_setting_enabled = $data_manager->get_flush_cache_setting();
			if ( $flush_cache_setting_enabled ) {
				$logger->log( "Flushing cache after package ({$package_type}) \"{$package_slug}\" update via wp-json" );
				$message .= ' ' . esc_attr__( 'Cache flushed.', 'deployer-for-git' );
				Helper::trigger_cache_flush();
			}
		} elseif ( is_wp_error( $install_result ) ) {
			$error_message = $install_result->get_error_message();

			$logger->log(
				sprintf(
					'Error occurred while updating a package (%1$s) "%2$s" via wp-json "%3$s"',
					$package_type,
					$package_slug,
					$error_message
				)
			);

			$message = $error_message;
		}

		do_action( 'dfg_after_api_package_update', $success, $package_slug, $package_type );

		return array(
			'success'      => $success,
			'message'      => $message,
			'package_type' => $package_type,
			'package_slug' => $package_slug,
		);
	}
}
