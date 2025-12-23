<?php

function blocksy_quick_view_attr() {
	if (blc_theme_functions()->blocksy_get_theme_mod('woocommerce_quick_view_trigger', 'button') === 'button') {
		return [];
	}

	return [
		'data-quick-view' => blc_theme_functions()->blocksy_get_theme_mod('woocommerce_quick_view_trigger', 'button')
	];
}

function blocksy_output_quick_view_link() {
	global $product;

	if (
		! $product
		||
		blc_theme_functions()->blocksy_get_theme_mod('has_archive_quick_view', 'yes') === 'no'
		||
		blc_theme_functions()->blocksy_get_theme_mod('woocommerce_quick_view_trigger', 'button') !== 'button'
	) {
		return '';
	}

	$id = $product->get_id();

	$shop_cards_type = blc_theme_functions()->blocksy_get_theme_mod('shop_cards_type', 'type-1');

	$icon = apply_filters(
		'blocksy:ext:woocommerce-extra:quick-view:trigger:icon',
		'<svg width="14" height="14" viewBox="0 0 15 15"><title>'. __('Quick view icon', 'blocksy-companion') . '</title><path d="M7.5,5.5c-1.1,0-1.9,0.9-1.9,2s0.9,2,1.9,2s1.9-0.9,1.9-2S8.6,5.5,7.5,5.5z M14.7,6.9c-0.9-1.6-2.9-5.2-7.1-5.2S1.3,5.3,0.4,6.9L0,7.5l0.4,0.6c0.9,1.6,2.9,5.2,7.1,5.2s6.3-3.7,7.1-5.2L15,7.5L14.7,6.9zM7.5,11.8c-3.2,0-4.9-2.8-5.7-4.3C2.6,6,4.3,3.2,7.5,3.2s4.9,2.8,5.7,4.3C12.4,9,10.8,11.8,7.5,11.8z"/></svg>'
	);

	if ( $shop_cards_type === 'type-3' ) {
		$icon .= '<span class="ct-tooltip ct-hidden-sm">' .  __('Quick view', 'blocksy-companion') . '</span>';
	}

	if (function_exists('blocksy_action_button')) {
		return blocksy_action_button(
			[
				'button_html_attributes' => [
					'class' => 'ct-open-quick-view ct-button',
					'aria-label' => __('Quick view toggle', 'blocksy-companion'),
				],
				'html_tag' => 'button',
				'icon' => $icon,
			]
		);
	}

	return '';
}


