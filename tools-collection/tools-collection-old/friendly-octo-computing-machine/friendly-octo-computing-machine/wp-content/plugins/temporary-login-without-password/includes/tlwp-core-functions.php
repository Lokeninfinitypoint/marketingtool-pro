<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'get_tlwp_db_version' ) ) {
	/**
	 * Get current db version
	 *
	 * @since 4.0.0
	 */
	function get_tlwp_db_version() {

		$option = get_option( 'tlwp_db_version', '1.0.0' );
		
		return $option;

	}
}

if ( ! function_exists( 'tlwp_maybe_define_constant' ) ) {
	/**
	 * Define constant
	 *
	 * @param $name
	 * @param $value
	 *
	 * @since 4.0.0
	 */
	function tlwp_maybe_define_constant( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
}


if ( ! function_exists( 'tlwp_get_current_date_time' ) ) {
	/**
	 * Get current date time
	 *
	 * @return false|string
	 */
	function tlwp_get_current_date_time() {
		return gmdate( 'Y-m-d H:i:s' );
	}
}

if ( ! function_exists( 'tlwp_increase_memory_limit' ) ) {

	/**
	 * Return memory limit required for ES heavy operations
	 *
	 * @return string
	 *
	 * @since 4.5.4
	 */
	function tlwp_increase_memory_limit() {

		return '512M';
	}
}


if ( ! function_exists( 'tlwp_get_request_data' ) ) {
	/**
	 * Get POST | GET data from $_REQUEST
	 *
	 * @param $var
	 *
	 * @return array|string
	 *
	 * @since 4.1.15
	 */
	function tlwp_get_request_data( $var = '', $default = '', $clean = true ) {
		return tlwp_get_data( $_REQUEST, $var, $default, $clean );
	}
}

if ( ! function_exists( 'tlwp_get_data' ) ) {
	/**
	 * Get data from array
	 *
	 * @param array  $array
	 * @param string $var
	 * @param string $default
	 * @param bool   $clean
	 *
	 * @return array|string
	 *
	 * @since 4.1.15
	 */
	function tlwp_get_data( $array = array(), $var = '', $default = '', $clean = false ) {

		if ( ! empty( $var ) ) {
			$value = isset( $array[ $var ] ) ? wp_unslash( $array[ $var ] ) : $default;
		} else {
			$value = wp_unslash( $array );
		}

		if ( $clean ) {
			$value = tlwp_clean( $value );
		}

		return $value;
	}
}

if ( ! function_exists( 'tlwp_clean' ) ) {
	/**
	 * Clean String or array using sanitize_text_field
	 *
	 * @param $variable Data to sanitize
	 *
	 * @return array|string
	 *
	 * @since 4.1.15
	 */
	function tlwp_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'tlwp_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}


if ( ! function_exists( 'tlwp_remove_utf8_bom' ) ) {

	/**
	 * Remove UTF-8 BOM signature.
	 *
	 * @param string $string String to handle.
	 *
	 * @return string
	 *
	 * @since 4.5.4
	 */
	function tlwp_remove_utf8_bom( $string = '' ) {

		// Check if string contains BOM characters.
		if ( ! empty( $string ) && 'efbbbf' === substr( bin2hex( $string ), 0, 6 ) ) {
			// Remove BOM characters by extracting substring from the original string after the BOM characters.
			$string = substr( $string, 3 );
		}

		return $string;
	}
} 

