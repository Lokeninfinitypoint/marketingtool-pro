<?php

if (! function_exists('blocksy_get_compare_ratio')) {
	function blocksy_get_compare_ratio($layout) {
		$cropping = 'predefined';

		if (! $layout['enabled']) {
			return '3/4';
		}

		$cropping = blocksy_akg(
			'compare_image_ratio',
			$layout,
			'3/4'
		);

		if ($cropping === 'uncropped') {
			return 'original';
		}

		if ($cropping === '1:1') {
			return '1/1';
		}

		if ($cropping === 'custom' || $cropping === 'predefined') {
			$width = get_option('woocommerce_thumbnail_cropping_custom_width', 4);
			$height = get_option('woocommerce_thumbnail_cropping_custom_height', 3);

			return $width . '/' . $height;
		}

		return $cropping;
	}
}

function blocksy_output_add_to_compare($place, $attributes = []) {
	$option_ids = [
		'archive' => 'has_archive_compare',
		'quick-view' => 'has_quick_view_compare',
	];

	if ($place !== 'single') {
		if ($place && isset($option_ids[$place])) {
			if (blc_theme_functions()->blocksy_get_theme_mod($option_ids[$place], 'yes') === 'no') {
				return '';
			}
		} else {
			return '';
		}
	}


	global $product;
	$id = $product->get_id();

	$class = 'ct-compare-button-archive ct-button';

	if ($place !== 'archive') {
		$class = 'ct-compare-button-single';
	}

	$default_attributes = $product->get_default_attributes();
	$is_active = false;

	if (
		in_array(
			$id,
			array_column(
				blc_get_ext('woocommerce-extra')
					->get_compare()
					->get_current_compare_list(),
				'id'
			)
		)
	) {
		$is_active = true;
	}

	$content = '';

	$icon = apply_filters(
		'blocksy:ext:woocommerce-extra:compare:icon',
		'<svg class="ct-icon" viewBox="0 0 15 15">
			<path d="M7.5 6c-.1.5-.2 1-.3 1.4 0 .6-.1 1.3-.3 2-.2.7-.5 1.4-1 1.9-.5.6-1.3.9-2.2.9H0v-1.4h3.7c.6 0 .9-.2 1.2-.5.3-.3.5-.7.7-1.3.1-.5.2-1 .3-1.6v-.3c0-.5.1-1 .3-1.5.2-.7.5-1.4 1-1.9.5-.6 1.3-.9 2.2-.9h3l-1.6-1.6 1-1L15 3.5l-3.3 3.3-1-1 1.6-1.6h-3c-.6 0-.9.2-1.2.5-.2.3-.5.7-.6 1.3zM4.9 4.7c.2-.4.4-.9.7-1.3-.5-.4-1.1-.6-1.9-.6H0v1.4h3.7c.6 0 1 .2 1.2.5zm5.8 4.5 1.6 1.6h-3c-.6 0-.9-.2-1.2-.5-.2.4-.4.9-.6 1.3.5.4 1.1.6 1.8.6h3l-1.6 1.6 1 1 3.3-3.3-3.3-3.3-1 1z"/>
		</svg>'
	);

	$shop_cards_type = blc_theme_functions()->blocksy_get_theme_mod('shop_cards_type', 'type-1');

	if ($place === 'archive' && $shop_cards_type === 'type-3') {
		$content .=
			'<span class="ct-tooltip ct-hidden-sm">' .
			__('Add to compare', 'blocksy-companion') .
			'</span>';
	}


	$label_class = 'ct-label';
	$label_visibility = blocksy_akg('label_visibility', $attributes, [
		'desktop' => true,
		'tablet' => true,
		'mobile' => true,
	]);

	$label_visibility = blocksy_expand_responsive_value($label_visibility);

	$label_class .=
	' ' .
	blocksy_visibility_classes($label_visibility);
	$label = blocksy_akg('label', $attributes, __('Compare', 'blocksy-companion'));

	$tooltip = '';

	$tooltip_visibility_classes = blocksy_visibility_classes([
		'desktop' => ! $label_visibility['desktop'],
		'tablet' => ! $label_visibility['tablet'],
		'mobile' => ! $label_visibility['mobile'],
	]);

	$tooltip = blocksy_html_tag(
		'span',
		[
			'class' => 'ct-tooltip ' . $tooltip_visibility_classes,
		],
		$label
	);

	if (
		$place === 'single'
		||
		$place === 'quick-view'
	) {
		$content .= blocksy_html_tag(
			'span',
			[
				'class' => $label_class,
			],
			$label
		) .
		$tooltip;
	}

	if (! function_exists('blocksy_action_button')) {
		return '';
	}

	return blocksy_action_button(
		[
			'button_html_attributes' => [
				'class' => $class,
				'aria-label' => __('Add to compare', 'blocksy-companion'),
				'data-button-state' => $is_active ? 'active' : '',
			],
			'html_tag' => 'button',
			'icon' => $icon,
			'content' => $content,
		]
	);
}
