<?php

$search_through = blocksy_akg('search_through', $atts, [
	'post' => true,
	'page' => true,
	'product' => true
]);

$post_type = blc_theme_functions()->blocksy_get_search_post_type($search_through);

$class = 'ct-search-box';

if ($panel_type === 'header') {
	$visibility = blocksy_default_akg('visibility', $atts, [
		'tablet' => true,
		'mobile' => true,
	]);
} else {
	$visibility = blocksy_default_akg('footer_visibility', $atts, [
		'desktop' => true,
		'tablet' => true,
		'mobile' => true,
	]);
}

$class .= ' ' . blocksy_visibility_classes($visibility);

$icon = '<svg class="ct-icon ct-search-button-content" aria-hidden="true" width="15" height="15" viewBox="0 0 15 15"><path d="M14.8,13.7L12,11c0.9-1.2,1.5-2.6,1.5-4.2c0-3.7-3-6.8-6.8-6.8S0,3,0,6.8s3,6.8,6.8,6.8c1.6,0,3.1-0.6,4.2-1.5l2.8,2.8c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2C15.1,14.5,15.1,14,14.8,13.7z M1.5,6.8c0-2.9,2.4-5.2,5.2-5.2S12,3.9,12,6.8S9.6,12,6.8,12S1.5,9.6,1.5,6.8z"/></svg>';

if (function_exists('blc_get_icon') && isset($atts['icon'])) {
	$icon = blc_get_icon([
		'icon_descriptor' => blocksy_akg('icon', $atts, [
			'icon' => 'blc blc-search'
		]),
		'icon_container' => false,
		'icon_html_atts' => [
			'class' => 'ct-icon ct-search-button-content',
		]
	]);
}

$taxonomy_filter_visibility = blocksy_visibility_classes(
	blocksy_akg(
		'taxonomy_filter_visibility',
		$atts,
		[
			'desktop' => true,
			'tablet' => true,
			'mobile' => false,
		]
	)
);

?>

<div <?php
	blocksy_attr_to_html_e(array_merge([
		'class' => $class
	], $attr));
?>>

	<?php
		if (function_exists('blocksy_isolated_get_search_form')) {
			blocksy_isolated_get_search_form([
				'ct_post_type' => $post_type,
				'search_live_results' => blocksy_akg('enable_live_results', $atts, 'no'),
				'live_results_attr' => blocksy_akg(
					'live_results_images',
					$atts,
					'yes'
				) === 'yes' ? 'thumbs' : '',
				'ct_product_price' => blocksy_akg(
					'searchHeaderProductPrice',
					$atts,
					'no'
				) === 'yes',
				'ct_product_status' => blocksy_akg(
					'searchHeaderProductStatus',
					$atts,
					'no'
				) === 'yes',
				'search_placeholder' => blocksy_translate_dynamic(
					blocksy_default_akg(
						'search_box_placeholder',
						$atts,
						__('Search', 'blocksy-companion')
					),
					$panel_type . ':' . $section_id . ':search-input:search_box_placeholder'
				),
				'has_taxonomy_filter' => blocksy_akg('has_taxonomy_filter', $atts, 'no') === 'yes',
				'has_taxonomy_children' => blocksy_akg('has_taxonomy_children', $atts, 'no') === 'yes',
				'search_through_taxonomy' => blocksy_akg('search_through_taxonomy', $atts, 'no'),
				'taxonomy_filter_visibility' => blocksy_akg('taxonomy_filter_visibility', $atts, [
					'desktop' => true,
					'tablet' => true,
					'mobile' => false,
				]),
				'icon' => $icon,
				'html_atts' => [
					'data-form-controls' => 'inside',
					'data-taxonomy-filter' => blocksy_akg('has_taxonomy_filter', $atts, 'no') === 'yes' ? 'true' : 'false',
					'data-submit-button' => 'icon',
				]
			]);
		}
	?>
</div>
