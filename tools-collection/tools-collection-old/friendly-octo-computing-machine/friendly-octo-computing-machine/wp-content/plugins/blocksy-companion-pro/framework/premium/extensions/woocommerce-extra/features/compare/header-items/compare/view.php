<?php

if (!isset($device)) {
	$device = 'desktop';
}

$class = 'ct-header-compare';

$compare_table_placement = blc_theme_functions()->blocksy_get_theme_mod(
	'compare_table_placement',
	'modal'
);

$item_visibility = blocksy_default_akg('header_compare_visibility', $atts, [
	'tablet' => true,
	'mobile' => true,
]);

$class .= ' ' . blocksy_visibility_classes($item_visibility);

if (!function_exists('wc_get_endpoint_url')) {
	return;
}

$icon = apply_filters('blocksy:header:compare:icons', [
	'type-1' =>
		'<svg class="ct-icon" width="15" height="15" viewBox="0 0 15 15"><path d="M7.5 6c-.1.5-.2 1-.3 1.4 0 .6-.1 1.3-.3 2-.2.7-.5 1.4-1 1.9-.5.6-1.3.9-2.2.9H0v-1.4h3.7c.6 0 .9-.2 1.2-.5.3-.3.5-.7.7-1.3.1-.5.2-1 .3-1.6v-.3c0-.5.1-1 .3-1.5.2-.7.5-1.4 1-1.9.5-.6 1.3-.9 2.2-.9h3l-1.6-1.6 1-1L15 3.5l-3.3 3.3-1-1 1.6-1.6h-3c-.6 0-.9.2-1.2.5-.2.3-.5.7-.6 1.3zM4.9 4.7c.2-.4.4-.9.7-1.3-.5-.4-1.1-.6-1.9-.6H0v1.4h3.7c.6 0 1 .2 1.2.5zm5.8 4.5 1.6 1.6h-3c-.6 0-.9-.2-1.2-.5-.2.4-.4.9-.6 1.3.5.4 1.1.6 1.8.6h3l-1.6 1.6 1 1 3.3-3.3-3.3-3.3-1 1z"/></svg>',

	'type-2' =>
		'<svg class="ct-icon" width="15" height="15" viewBox="0 0 15 15"><path d="M6.8 0v5.1h1.5V2.5l4.9 4.9c.1.1.1.2 0 .3L11 9.9l1 1 2.2-2.2c.7-.7.7-1.7 0-2.4L9.3 1.5h2.6V0H6.8zm1.4 15V9.9H6.8v2.6L1.9 7.7c-.1-.1-.1-.2 0-.3l2.2-2.2-1-1L.9 6.3C.2 7 .2 8 .9 8.7l4.9 4.9H3.1V15h5.1z"/></svg>',

	'type-3' =>
		'<svg class="ct-icon" width="15" height="15" viewBox="0 0 15 15"><path d="M6.1 0h1.4v15H6.1v-1.4H2.7c-.8 0-1.4-.6-1.4-1.4V2.7c.1-.7.7-1.3 1.4-1.3h3.4V0zm6.2 1.4H8.9v1.4h3.4v9.5H8.9v1.4h3.4c.8 0 1.4-.6 1.4-1.4V2.7c-.1-.7-.7-1.3-1.4-1.3z"/></svg>',
]);

$icon_type = blocksy_default_akg('compare_item_type', $atts, 'type-1');

if (empty($icon_type)) {
	$icon_type = 'type-1';
}

$count_output = '';

$current_count = count(
	blc_get_ext('woocommerce-extra')
		->get_compare()
		->get_current_compare_list()
);

if (blocksy_akg('has_compare_badge', $atts, 'yes') === 'yes') {
	$count_output = '<span class="ct-dynamic-count-compare" data-count="' . $current_count .'">' . $current_count . '</span>';
}

$icon = $icon[$icon_type];

if (function_exists('blc_get_icon')) {
	$icon_source = blocksy_default_akg('icon_source', $atts, 'default');

	if ($icon_source === 'custom') {
		$icon = blc_get_icon([
			'icon_descriptor' => blocksy_akg('icon', $atts, [
				'icon' => 'blc blc-compare',
			]),
			'icon_container' => false,
		]);
	}
}

$url = '';

if ($compare_table_placement === 'modal') {
	$url = '#ct-compare-modal';
}

if ($compare_table_placement === 'page') {
	$maybe_page_id = blc_theme_functions()->blocksy_get_theme_mod('woocommerce_compare_page');

	if (!empty($maybe_page_id)) {
		$maybe_permalink = get_permalink($maybe_page_id);

		if ($maybe_permalink) {
			$url = $maybe_permalink;
		}
	}
}

$label_class = 'ct-label';

$label_class .=
	' ' .
	blocksy_visibility_classes(
		blocksy_akg('compare_label_visibility', $atts, [
			'desktop' => false,
			'tablet' => false,
			'mobile' => false,
		])
	);

$compare_label = blocksy_expand_responsive_value(
	blocksy_default_akg('compare_label', $atts, __('Compare', 'blocksy-companion'))
)[$device];

$compare_label = blocksy_translate_dynamic(
	$compare_label,
	$panel_type . ':' . $section_id . ':' . $item_id . ':compare_label'
);

$compare_label_position = blocksy_expand_responsive_value(
	blocksy_akg('compare_label_position', $atts, 'left')
);

if ( $compare_table_placement === 'modal' ) {
	$attr = array_merge(
		[
			'data-behaviour' => $compare_table_placement,
		],
		$attr
	);
}

if (function_exists('blocksy_action_button')) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo blocksy_action_button([
		'button_html_attributes' => array_merge(
			[
				'href' => esc_attr($url),
				'class' => esc_attr(trim($class)),
				'data-label' => $compare_label_position[$device],
				'aria-label' => $compare_label,
			],
			$attr
		),
		'icon' => $count_output . $icon,
		'icon_html_attributes' => [
			'class' => blocksy_visibility_classes(
				blocksy_akg('compare_icon_visibility', $atts, [
					'desktop' => true,
					'tablet' => true,
					'mobile' => true,
				])
			),
			'aria-hidden' => 'true',
		],
		'icon_position' => 'end',
		'content' => blocksy_html_tag(
			'span',
			[
				'class' => $label_class,
				'aria-hidden' => 'true',
			],
			$compare_label
		),
	]);
}
