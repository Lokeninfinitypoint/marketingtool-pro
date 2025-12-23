<?php

if (! isset($device)) {
	$device = 'desktop';
}

$class = 'ct-header-wishlist';

$item_visibility = blocksy_default_akg('header_wishlist_visibility', $atts, [
	'tablet' => true,
	'mobile' => true,
]);

$class .= ' ' . blocksy_visibility_classes($item_visibility);

if (! function_exists('wc_get_endpoint_url')) {
	return;
}

$icon = apply_filters('blocksy:header:wish-list:icons', [
	'type-1' => '<svg class="ct-icon" width="15" height="15" viewBox="0 0 15 15"><path d="M13.5,1.8c-1.6-1.4-4.7-1.1-6,1.4c-1.3-2.5-4.4-2.8-6-1.4c-1.8,1.5-2,4.4-0.2,6.6C2.5,10,7.5,14,7.5,14s5-4,6.3-5.6C15.6,6.2,15.3,3.4,13.5,1.8z"/></svg>',

	'type-2' => '<svg class="ct-icon" width="15" height="15" viewBox="0 0 15 15"><path d="M7.5,13.9l-0.4-0.3c-0.2-0.2-4.6-3.5-5.8-4.8C0.4,7.7-0.1,6.4,0,5.1c0.1-1.2,0.7-2.2,1.6-3c0.9-0.8,2.3-1,3.6-0.8C6.1,1.5,6.9,2,7.5,2.6c0.6-0.6,1.4-1.1,2.4-1.3c1.3-0.2,2.6,0,3.5,0.8l0,0c0.9,0.7,1.5,1.8,1.6,3c0.1,1.3-0.3,2.6-1.3,3.7c-1.2,1.4-5.6,4.7-5.7,4.8L7.5,13.9z M4.2,2.7C3.6,2.7,3,2.9,2.5,3.3c-0.6,0.5-0.9,1.2-1,1.9C1.4,6.1,1.8,7,2.4,7.8c0.9,1,3.9,3.4,5.1,4.3c1.2-0.9,4.2-3.3,5.1-4.3c0.7-0.8,1-1.7,0.9-2.6c-0.1-0.8-0.4-1.4-1-1.9l0,0c-0.6-0.5-1.5-0.7-2.3-0.5C9.3,3,8.6,3.5,8.2,4.2L7.5,5.4L6.8,4.2C6.4,3.5,5.7,3,4.9,2.8C4.7,2.8,4.4,2.7,4.2,2.7z"/></svg>',

	'type-3' => '<svg class="ct-icon" width="15" height="15" viewBox="0 0 15 15"><path d="M7.5,6.3C7,5.3,5.8,5.2,5.1,5.7C4.4,6.3,4.3,7.4,5,8.3c0.5,0.6,2.5,2.1,2.5,2.1s2-1.5,2.5-2.1c0.7-0.8,0.6-1.9-0.1-2.5C9.3,5.2,8.1,5.3,7.5,6.3zM7.5,0C3.4,0,0,3.3,0,7.5S3.4,15,7.5,15S15,11.6,15,7.5S11.7,0,7.5,0z M7.5,13.5c-3.3,0-6-2.7-6-6s2.7-6,6-6s6,2.7,6,6S10.8,13.5,7.5,13.5z"/></svg>',
]);

$icon_type = blocksy_default_akg('wishlist_item_type', $atts, 'type-1');

if (empty($icon_type)) {
	$icon_type = 'type-1';
}

$count_output = '';

$current_count = count(
	blc_get_ext('woocommerce-extra')->get_wish_list()->get_current_wish_list()
);

if (blocksy_akg('has_wishlist_badge', $atts, 'yes') === 'yes') {
	$count_output = '<span class="ct-dynamic-count-wishlist" data-count="' . $current_count . '">' . $current_count . '</span>';
}

$icon = $icon[$icon_type];

if (function_exists('blc_get_icon')) {
	$icon_source = blocksy_default_akg('icon_source', $atts, 'default');

	if ( $icon_source === 'custom' ) {
		$icon = blc_get_icon([
			'icon_descriptor' => blocksy_akg(
				'icon',
				$atts,
				['icon' => 'blc blc-heart']
			),
			'icon_container' => false,
		]);
	}

}

$url = wc_get_endpoint_url(
	apply_filters(
		'blocksy:pro:woocommerce-extra:wish-list:slug',
		'woo-wish-list'
	),
	'',
	get_permalink(get_option('woocommerce_myaccount_page_id'))
);

if (
	! is_user_logged_in()
	&&
	blc_theme_functions()->blocksy_get_theme_mod('product_wishlist_display_for', 'logged_users') === 'all_users'
) {
	$maybe_page_id = blc_theme_functions()->blocksy_get_theme_mod('woocommerce_wish_list_page');

	if (! empty($maybe_page_id)) {
		$maybe_permalink = get_permalink($maybe_page_id);

		if ($maybe_permalink) {
			$url = $maybe_permalink;
		}
	}
}



$label_class = 'ct-label';

$label_class .= ' ' . blocksy_visibility_classes(blocksy_akg('wishlist_label_visibility', $atts,
	[
		'desktop' => false,
		'tablet' => false,
		'mobile' => false,
	]
));

$wishlist_label = blocksy_expand_responsive_value(
	blocksy_default_akg('wishlist_label', $atts, __('Wishlist', 'blocksy-companion'))
)[$device];

$wishlist_label = blocksy_translate_dynamic(
	$wishlist_label,
	$panel_type . ':' . $section_id . ':' . $item_id . ':wishlist_label'
);

$wishlist_label_position = blocksy_expand_responsive_value(
	blocksy_akg('wishlist_label_position', $atts, 'left')
);

$icon_classes = [
	'ct-icon-container',
	blocksy_visibility_classes(
		blocksy_akg('wishlist_icon_visibility', $atts, [
			'desktop' => true,
			'tablet' => true,
			'mobile' => true,
		])
	)
];

?>

<a
	href="<?php echo esc_attr($url) ?>"
	class="<?php echo esc_attr(trim($class)) ?>"
	data-label="<?php echo esc_attr($wishlist_label_position[$device]); ?>"
	aria-label="<?php echo esc_attr($wishlist_label); ?>"
	<?php blocksy_attr_to_html_e($attr); ?>>

	<span class="<?php echo esc_attr($label_class); ?>" aria-hidden="true"><?php echo esc_html($wishlist_label); ?></span>

	<span class="<?php echo esc_attr(implode(' ', $icon_classes)) ?>" aria-hidden="true">
		<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $count_output;

			/**
			 * Note to code reviewers: This line doesn't need to be escaped.
			 * The value used here escapes the value properly.
			 * It contains an inline SVG, which is safe.
			 */
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $icon;
		?>
	</span>
</a>
