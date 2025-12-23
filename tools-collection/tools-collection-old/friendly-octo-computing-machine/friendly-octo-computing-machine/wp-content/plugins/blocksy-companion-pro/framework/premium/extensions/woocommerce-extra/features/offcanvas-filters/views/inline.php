<?php

$ariaHidden = blc_theme_functions()->blocksy_get_theme_mod(
	'filter_panel_behaviour',
	'no'
) === 'no' ? 'true' : 'false';

if (! woocommerce_products_will_display()) {
	$ariaHidden = 'true';
}

$content = '';

$filter_source = blc_theme_functions()->blocksy_get_theme_mod(
	'filter_source',
	'sidebar-woocommerce-offcanvas-filters'
);

// phpcs:ignore WordPress.Security.NonceVerification.Recommended
if (isset($_GET['filter_source'])) {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$filter_source = sanitize_text_field(wp_unslash($_GET['filter_source']));
}

if (
	! $has_filter_ajax_reveal
	||
	(
		$has_filter_ajax_reveal
		&&
		blc_is_xhr()
	)
) {
	ob_start();
	dynamic_sidebar($filter_source);
	$content = ob_get_clean();

	ob_start();
	do_action('blocksy:pro:woo-extra:inline-filters:top');
	$content = ob_get_clean() . $content;

	ob_start();
	do_action('blocksy:pro:woo-extra:inline-filters:bottom');
	$content = $content . ob_get_clean();

	$without_container = blocksy_html_tag(
		'div',
		[
			'class' => 'ct-filter-content',
		],
		$content
	);

	$content = $without_container;
}

$visibility_classes = blocksy_visibility_classes(
	blc_theme_functions()->blocksy_get_theme_mod(
		'filter_panel_visibility',
		[
			'desktop' => true,
			'tablet' => true,
			'mobile' => true,
		]
	)
);

$attributes = [
	'id' => 'woo-filters-panel',
	'data-behaviour' => 'drop-down',
	'data-height' => blc_theme_functions()->blocksy_get_theme_mod(
		'filter_panel_height_type',
		'auto'
	),
	'aria-hidden' => $ariaHidden,
];

if (!empty($visibility_classes)) {
	$attributes['class'] = $visibility_classes;
}

blocksy_html_tag_e('div', $attributes, $content);
