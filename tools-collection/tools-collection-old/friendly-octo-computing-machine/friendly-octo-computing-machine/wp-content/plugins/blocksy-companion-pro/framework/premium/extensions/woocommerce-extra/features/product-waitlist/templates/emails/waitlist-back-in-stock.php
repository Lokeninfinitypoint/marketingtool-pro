<?php

use Blocksy\Extensions\WoocommerceExtra\WaitlistEmail;

if (! defined('ABSPATH')) {
	exit;
}

$image_src = $product->get_image_id() ? wp_get_attachment_image_src($product->get_image_id(), 'thumbnail')[0] : wc_placeholder_img_src();
$image_size = wc_get_image_size('thumbnail');

$button_link = $product->is_type('simple') ? add_query_arg('add-to-cart', $product->get_id(), $product->get_permalink()) : $product->get_permalink();
?>

<?php do_action('woocommerce_email_header', $email_heading, $email); ?>

<p>
	<?php
		echo wp_kses(
			blocksy_safe_sprintf(
				// translators: %s is the user name
				__('Hi, %s!', 'blocksy-companion'),
				$user_name
			),
			true
		)
	?>
</p>

<p>
	<?php
		echo wp_kses(
			blocksy_safe_sprintf(
				// translators: %s is the product name
				__('Great news! The %s from your waitlist is now back in stock!', 'blocksy-companion'),
				$product->get_name()
			),
			true
		);
	?>
</p>

<p>
	<?php
		echo wp_kses(
			__('Click the link below to secure your purchase before it is gone!', 'blocksy-companion'),
			true
		);
	?>
</p>

<table class="td ct-product-table" cellspacing="0" cellpadding="6" border="1">
	<thead>
		<tr>
			<th class="td" scope="col"></th>
			<th class="td" scope="col"><?php echo esc_html__('Product', 'blocksy-companion'); ?></th>
			<th class="td" scope="col"><?php echo esc_html__('Price', 'blocksy-companion'); ?></th>
			<th class="td ct-align-end" scope="col"><?php echo esc_html__('Add to cart', 'blocksy-companion'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="td ct-image-column">
				<a href="<?php echo esc_url($product->get_permalink()) ?>">
					<img
						src="<?php echo $image_src; // phpcs:ignore. ?>"
						alt="<?php echo esc_html($product->get_name()); ?>"
						width="<?php echo esc_attr( $image_size['width'] ); ?>"
						height="<?php echo esc_attr( $image_size['height'] ); ?>"
					/>
				</a>
			</td>

			<td class="td">
				<a href="<?php echo esc_url($product->get_permalink()) ?>">
					<?php echo esc_html($product->get_name()); ?>
				</a>
			</td>

			<td class="td">
				<?php echo wp_kses_post(wc_price( $product->get_price() )) ?>
			</td>

			<td class="td ct-align-end">
				<a href="<?php echo esc_url($button_link) ?>" class="ct-add-to-cart">
					<?php echo esc_html__('Add to cart', 'blocksy-companion'); ?>
				</a>
			</td>
		</tr>
	</tbody>
</table>

<?php do_action('woocommerce_email_footer', $email); ?>
