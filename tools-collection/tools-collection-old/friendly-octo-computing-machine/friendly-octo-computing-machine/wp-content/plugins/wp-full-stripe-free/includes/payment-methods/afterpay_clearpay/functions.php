<?php

/**
 * Adds `shipping_address_collection` to Stripe Checkout Session when using Afterpay / Clearpay.
 *
 * @since 4.4.4
 *
 * @param array $args Arguments used to create a Checkout Session.
 * @return array
 */
function add_shipping_address_collection( $args ): array
{
	if ( ! in_array( 'afterpay_clearpay', $args['payment_method_types'], true ) ) {
		return $args;
	}

	$args['shipping_address_collection'] = [
		'allowed_countries' => []
	];

	return $args;
}
add_filter(
	'wpfs_get_session_args_from_payment_form_request',
	__NAMESPACE__ . '\\add_shipping_address_collection',
	20
);
