<?php
/**
 * Payment Confirmation
 *
 * @package WP_Full_Stripe
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Validates the Payment Confirmation data.
 *
 * If the data includes an invalid or incomplete PaymentIntent
 * redirect to the form's failure page.
 */
function validate_payment_confirmation_data(): void
{
	// Ensure we can retrieve a PaymentIntent.
	if ( ! isset(
		$_GET['payment_intent'],
		$_GET['payment_intent_client_secret'],
		$_GET['customer_id'],
		$_GET['form_id']
	) ) {
		return;
	}

	$payment_confirmation_data = wpfs_get_payment_confirmation_data();

	// Ensure we have a Payment Form to reference.
	if ( ! isset( $payment_confirmation_data['form'] ) ) {
		return;
	}

	$payment_intent = isset( $payment_confirmation_data['paymentintents'] )
		? current( $payment_confirmation_data['paymentintents'] )
		: false;

	$failure_page = $payment_confirmation_data['form']->payment_cancelled_page;

	// Redirect to failure if PaymentIntent cannot be found.
	if ( false === $payment_intent ) {
		wp_safe_redirect( $failure_page );
	}

	// Do nothing if the Intent has succeeded.
	if ( 'succeeded' === $payment_intent->status ) {
		return;
	}

	// Do nothing if the Intent did not have an error.
	if ( ! isset( $payment_intent->last_payment_error ) ) {
		return;
	}

	// Do nothing if the Intent is not from Klarna.
	if ( 'klarna' !== $payment_intent->last_payment_error->payment_method->type ) {
		return;
	}

	// Redirect to failure page.
	wp_safe_redirect( $failure_page );
}
