<?php
/*
 * This is a generic license manager for WP Full Pay
 */

class WPFS_License {

	/**
	 * Price ID to licence type map
	 *
	 * @var int[]
	 */
	public static $plans_map = [
		1 => 1,
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 1,
		6 => 2,
		7 => 3,
	];

	/**
	 * Get Namespace.
	 * 
	 * @return string
	 */
	public static function get_namespace() {
		$namespace = basename( dirname( WP_FULL_STRIPE_BASENAME ) );
		$namespace = str_replace( '-', '_', strtolower( trim( $namespace ) ) );
		return $namespace;
	}

	/**
	 * Get the license data.
	 *
	 * @return bool|\stdClass
	 */
	public static function get_data() {
		$namespace = self::get_namespace();
		return get_option( $namespace . '_license_data' );
	}

	/**
	 * Get active license.
	 *
	 * @return bool
	 */
	public static function is_active() {
		$status = self::get_data();

		if ( ! $status ) {
			return false;
		}

		if ( ! isset( $status->license ) ) {
			return false;
		}

		if ( 'valid' !== $status->license ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if license is expired.
	 *
	 * @return bool
	 */
	public static function is_expired() {
		$status = self::get_data();

		if ( ! $status ) {
			return false;
		}

		if ( ! isset( $status->license ) ) {
			return false;
		}

		if ( 'active_expired' !== $status->license ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the license expiration date.
	 *
	 * @param string $format format of the date.
	 * @return false|string
	 */
	public static function get_expiration_date( $format = 'F Y' ) {
		$data = self::get_data();

		if ( isset( $data->expires ) ) {
			$parsed = date_parse( $data->expires );
			$time   = mktime( $parsed['hour'], $parsed['minute'], $parsed['second'], $parsed['month'], $parsed['day'], $parsed['year'] );
			return gmdate( $format, $time );
		}

		return false;
	}

	/**
	 * Get the licence type.
	 * 1 - personal, 2 - business, 3 - agency.
	 *
	 * @return int
	 */
	public static function get_type() {
		$license = self::get_data();
		if ( false === $license ) {
			return -1;
		}

		if ( ! isset( $license->price_id ) ) {
			return -1;
		}

		if ( isset( $license->license ) && ( 'valid' !== $license->license && 'active_expired' !== $license->license ) ) {
			return -1;
		}

		if ( ! array_key_exists( $license->price_id, self::$plans_map ) ) {
			return -1;
		}

		return self::$plans_map[ $license->price_id ];
	}

	/**
	 * Get the license price ID.
	 * 
	 * @return int
	 */
	public static function get_price_id() {
		$license = self::get_data();
		if ( false === $license ) {
			return -1;
		}

		if ( ! isset( $license->price_id ) ) {
			return -1;
		}

		return $license->price_id;
	}

	/**
	 * Get User ID.
	 * 
	 * @return int
	 */
	public static function get_user_id() {
		$license = self::get_data();

		// We don't have user_id like WPFS previously used with freemium, so we use payment_id instead.
		if ( false === $license ) {
			return -1;
		}

		if ( ! isset( $license->payment_id ) ) {
			return -1;
		}

		return $license->payment_id;
	}

	/**
	 * Get License Key.
	 * 
	 * @return string
	 */
	public static function get_key() {
		$license = self::get_data();

		if ( false === $license ) {
			return '';
		}

		if ( ! isset( $license->key ) ) {
			return '';
		}

		return $license->key;
	}

	/**
	 * Get Activation URL.
	 * 
	 * @return string
	 */
	public static function get_activation_url() {
		$admin_url = MM_WPFS_Admin_Menu::getAdminUrlBySlug( MM_WPFS_Admin_Menu::SLUG_SETTINGS_LICENSE );
		return $admin_url;
	}

	/**
	 * Toggle License.
	 * 
	 * @param string $key    License key.
	 * @param string $status License status.
	 * @return array
	 */
	public static function toggle( $key, $status ) {
		$namespace = self::get_namespace();
		$response  = apply_filters( 'themeisle_sdk_license_process_wpfs', $key, $status );

		if ( is_wp_error( $response ) ) {
			return [
				'message' => $response->get_error_message(),
				'success' => false,
			];
		}

		return [
			'success' => true,
			'message' => 'activate' === $status ? __( 'Activated.', 'wp-full-stripe-free' ) : __( 'Deactivated', 'wp-full-stripe-free' ),
			'license' => [
				'key'        => apply_filters( 'product_wpfs_license_key', 'free' ),
				'valid'      => apply_filters( 'product_wpfs_license_status', false ),
				'expiration' => self::get_expiration_date(),
			],
		];
	}
}
