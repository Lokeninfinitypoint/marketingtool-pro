<?php
/**
 * Functions for the Card payment method.
 *
 * @package WP_Full_Stripe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds `payment_intent_data.setup_future_usage` if Card is the only Payment Method
 * for Stripe Checkout.
 *
 */
function checkout_session_setup_future_usage( $session_args ) {
	if ( 'payment' !== $session_args['mode'] ) {
		return $session_args;
	}

	$payment_method_types = isset( $session_args['payment_method_types'] )
		? $session_args['payment_method_types']
		: [];

	if ( false === array_search( 'card', $payment_method_types, true ) ) {
		return $session_args;
	}

	// Set future usage if card is the only Payment Method.
	if ( 1 === count( $payment_method_types ) ) {
		$session_args['payment_intent_data']['setup_future_usage'] = 'off_session';
	}

	return $session_args;
}

//add_filter(
//    'wpfs_get_session_args_from_payment_form_request',
//    __NAMESPACE__ . '\\checkout_session_setup_future_usage',
//    20
//);
