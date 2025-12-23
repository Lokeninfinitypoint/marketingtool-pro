<?php

defined( 'ABSPATH' ) || exit;

if (! function_exists('blc_order_details_customer_before')) {
	function blc_order_details_customer_before($template_name, $template_path, $located, $args) {
		if ($template_name !== 'order/order-details-customer.php') {
			return;
		}

		ob_start();
	}
}

if (! function_exists('blc_order_details_customer_after')) {
	function blc_order_details_customer_after($template_name, $template_path, $located, $args) {
		if ($template_name !== 'order/order-details-customer.php') {
			return;
		}

		ob_get_clean();
	}
}

$wp_styles = wp_style_engine_get_styles(
	$atts['style']
);

$wp_styles_css = isset($wp_styles['css']) ? $wp_styles['css'] : '';

$classes = 'ct-order-review-block';

if (! empty($atts['className'])) {
	$classes .= ' ' . $atts['className'];
}

$wrapper_attr = [
	'class' => $classes,
];

if (! empty($wp_styles_css)) {
	$wrapper_attr['style'] = $wp_styles_css;
}

?>

<div <?php blocksy_attr_to_html_e($wrapper_attr); ?>>

	<?php
	if ( $order ) {
		if ($atts['showOrderOverview']) {
			?>
				<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

			<li class="woocommerce-order-overview__order order">
				<?php esc_html_e( 'Order number:', 'blocksy-companion' ); ?>
				<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
			</li>

			<li class="woocommerce-order-overview__date date">
				<?php esc_html_e( 'Date:', 'blocksy-companion' ); ?>
				<strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
			</li>

			<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
				<li class="woocommerce-order-overview__email email">
					<?php esc_html_e( 'Email:', 'blocksy-companion' ); ?>
					<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>
			<?php endif; ?>

			<li class="woocommerce-order-overview__total total">
				<?php esc_html_e( 'Total:', 'blocksy-companion' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
			</li>

			<?php if ( $order->get_payment_method_title() ) : ?>
				<li class="woocommerce-order-overview__payment-method method">
					<?php esc_html_e( 'Payment method:', 'blocksy-companion' ); ?>
					<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
				</li>
			<?php endif; ?>

		</ul>
			<?php

			do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
		}
	}

	if ($atts['showOrderDetails']) {
		if (!$atts['showCustomerDetails']) {
			add_action('woocommerce_before_template_part', 'blc_order_details_customer_before', 1, 4);
			add_action('woocommerce_after_template_part', 'blc_order_details_customer_after', 1, 4);
		}

		woocommerce_order_details_table($order->get_id());

		if (!$atts['showCustomerDetails']) {
			remove_action('woocommerce_before_template_part', 'blc_order_details_customer_before', 1, 4);
			remove_action('woocommerce_after_template_part', 'blc_order_details_customer_after', 1, 4);
		}
	}

	if (
		!$atts['showOrderDetails']
		&&
		$atts['showCustomerDetails']
	) {
		wc_get_template( 'order/order-details-customer.php', ['order' => $order]);
	}
?>
</div>
