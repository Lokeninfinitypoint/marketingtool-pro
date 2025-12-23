<?php

$compare_list = blc_get_ext('woocommerce-extra')
	->get_compare()
	->get_current_compare_list();

$content = '';

$visiblity_class = blocksy_visibility_classes(
	blc_theme_functions()->blocksy_get_theme_mod(
		'product_compare_bar_visibility',
		[
			'desktop' => true,
			'tablet' => true,
			'mobile' => true,
		]
	)
);

if (count($compare_list) > 0) {
	$products = [];

	$class = 'ct-button';

	if (!function_exists('wc_get_endpoint_url')) {
		return;
	}

	$icon = blc_get_icon([
		'icon_descriptor' => blc_theme_functions()->blocksy_get_theme_mod(
			'product_compare_bar_button_icon',
			[
				'icon' => 'blc blc-compare',
			]
		),
		'icon_container' => false,
	]);

	$url = '#ct-compare-modal';

	$maybe_page_id = blc_theme_functions()->blocksy_get_theme_mod('woocommerce_compare_page');

	if (!empty($maybe_page_id)) {
		$maybe_permalink = get_permalink($maybe_page_id);

		if ($maybe_permalink) {
			$url = $maybe_permalink;
		}
	}

	$label_class = 'ct-label';

	$compare_label = blc_theme_functions()->blocksy_get_theme_mod(
		'product_compare_bar_button_label',
		__('Compare', 'blocksy-companion')
	);

	$button = '';

	if (function_exists('blocksy_action_button')) {
		$button = blocksy_action_button(
			[
				'button_html_attributes' => [
					'href' => $url,
					'class' => $class,
					'aria-label' => $compare_label,
					'data-behaviour' => blc_theme_functions()->blocksy_get_theme_mod('compare_table_placement', 'modal'),
				],
				'icon' => $icon,
				'content' => blocksy_html_tag(
					'span',
					[
						'class' => 'ct-hidden-md ct-hidden-sm',
					],
					$compare_label
				)
			]
		);
	}

	foreach ($compare_list as $single_product) {
		$product = wc_get_product($single_product['id']);

		if (! $product) {
			continue;
		}

		$thumbnail = $product->get_image();
		$thumbnail = blocksy_media(
			[
				'attachment_id' => get_post_thumbnail_id($product->get_id()),
				'post_id' => $product->get_id(),
				'ratio' => '1',
				'size' => 'thumbnail',
				'tag_name' => 'figure',
			]
		);

		$remove = blocksy_html_tag(
			'a',
			[
				'href' => '#',
				'class' => 'ct-compare-remove',
				'data-product_id' => $product->get_id(),
				'title' => __('Remove Product', 'blocksy-companion')
			],
			'<svg width="10" height="10" viewBox="0 0 15 15" fill="currentColor"><path d="M8.5,7.5l4.5,4.5l-1,1L7.5,8.5L3,13l-1-1l4.5-4.5L2,3l1-1l4.5,4.5L12,2l1,1L8.5,7.5z"></path></svg>'
		);

		$products[] = blocksy_html_tag(
			'li',
			[],
			blocksy_html_tag(
				'span',
				[
					'class' => 'ct-tooltip'
				],
				blc_get_ext(
					'woocommerce-extra'
				)->utils->get_formatted_title($product->get_id())
			) .
			$remove .
			$thumbnail
		);
	}

	$content = blocksy_html_tag(
		'div',
		[
			'class' => 'ct-container',
		],
		blocksy_html_tag(
			'ul',
			[],
			implode('', $products)
		) . $button
	);
}

$attr = [
	'class' => trim('ct-compare-bar' . ' ' . $visiblity_class)
];

if (is_customize_preview()) {
	$attr['data-shortcut'] = 'border:outside';
	$attr['data-shortcut-location'] = 'woocommerce_general:has_compare_panel';
}

blocksy_html_tag_e('div', $attr, $content);
