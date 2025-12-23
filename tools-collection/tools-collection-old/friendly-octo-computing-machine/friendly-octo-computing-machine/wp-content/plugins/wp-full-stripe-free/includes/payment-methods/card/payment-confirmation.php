<?php

/**
 * @param $message
 * @param $payment_method
 * @param $subscription
 * @param $upcoming_invoice
 * @return mixed|string
 */
function update_payment_method_message( $message, $payment_method, $subscription, $upcoming_invoice ) {
	// Not a Card.
	if ( ! isset( $payment_method->card ) ) {
		return $message;
	}

	// No upcoming Invoice.
	if ( false === $upcoming_invoice ) {
		return $message;
	}

	$amount_due = $upcoming_invoice->amount_due;
//	$currency   = $upcoming_invoice->currency;

	return wp_kses(
		sprintf(
			/* translators: %1$s Upcoming invoice amount. %2$s Card name. %3$s Card last 4. %4$s Upcoming invoice date. */
			__('The next invoice for %1$s will automatically charge %2$s &bull;&bull;&bull;&bull; %3$s on %4$s.', 'wp-full-stripe-free'),
			$amount_due,
			'<strong>' . ucwords( $payment_method->card->brand ) . '</strong>',
			'<strong>' . $payment_method->card->last4 . '</strong>',
			get_date_from_gmt(
				date( 'Y-m-d H:i:s', $subscription->current_period_end ),
				get_option( 'date_format' )
			)
		),
		[
			'strong' => true,
		]
	);
}
add_filter( 'wpfs_update_payment_method_message', __NAMESPACE__ . '\\update_payment_method_message', 10, 4 );
