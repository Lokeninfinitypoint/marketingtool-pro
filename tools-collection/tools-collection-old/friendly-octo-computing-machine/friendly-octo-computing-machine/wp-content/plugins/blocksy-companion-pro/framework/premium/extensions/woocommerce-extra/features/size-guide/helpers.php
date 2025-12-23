<?php

function blocksy_output_size_guide_trigger($attributes, $tab_id) {
	if (! $tab_id) {
		return;
	}

	global $product;
	$id = $product->get_id();

	$class = 'ct-size-guide-button-single';
	$content = '';

	$icon = apply_filters(
		'blocksy:ext:woocommerce-extra:size-guide:icon',
		'<svg class="ct-icon" viewBox="0 0 15 15">
			<path d="M3.9,3.9c0-0.3,0.5-0.6,1.2-0.6s1.2,0.3,1.2,0.6S5.8,4.5,5.1,4.5S3.9,4.2,3.9,3.9z M15,6.3v7.2c0,0.4-0.3,0.7-0.7,0.7h-2.6
	h-1.3H8.6H7.2H5.3H4.3H4v0c-0.9,0-1.6-0.2-2.2-0.6c-0.5-0.3-1-0.8-1.3-1.3c-0.3-0.5-0.4-1-0.5-1.3C0,10.7,0,10.6,0,10.5
	c0,0,0-0.1,0-0.1l0-6.3C0,4,0,4,0,4c0,0,0-0.1,0-0.1C0,3,0.5,2.2,1.5,1.7c1.9-1.1,5.2-1.1,7.1,0c1,0.6,1.5,1.4,1.5,2.2v1.8h4.1
	C14.6,5.6,15,5.9,15,6.3z M1.3,3.9c0,0.3,0.3,0.8,0.9,1.1C3.7,5.8,6.5,5.8,8,5c0.6-0.3,0.9-0.7,0.9-1.1S8.5,3.1,8,2.8
	C7.2,2.4,6.2,2.1,5.1,2.1C4,2.1,2.9,2.4,2.2,2.8C1.6,3.1,1.3,3.5,1.3,3.9z M13.7,7H4.3C3,7,2,6.6,1.3,6.2v4.1c0,0.1,0,0.2,0,0.4
	c0,0.2,0.1,0.6,0.3,0.9c0.2,0.4,0.5,0.6,0.8,0.9c0.4,0.2,0.9,0.4,1.5,0.4V9.7h1.3v3.2h1.9v-2h1.3v2h1.9V9.7h1.3v3.2h1.9V7z"/>
		</svg>'
	);

	$label_class = 'ct-label';
	$label_visibility = blocksy_akg('label_visibility', $attributes, [
		'desktop' => true,
		'tablet' => true,
		'mobile' => true,
	]);

	$label_visibility = blocksy_expand_responsive_value($label_visibility);

	$label_class .= ' ' . blocksy_visibility_classes($label_visibility);

	$tooltip = '';

	$tooltip_visibility_classes = blocksy_visibility_classes(
		[
			'desktop' => ! $label_visibility['desktop'],
			'tablet' => ! $label_visibility['tablet'],
			'mobile' => ! $label_visibility['mobile'],
		]
	);

	$label = blocksy_akg('label', $attributes, __('Size Guide', 'blocksy-companion'));

	$tooltip = blocksy_html_tag(
		'span',
		[
			'class' => 'ct-tooltip ' . $tooltip_visibility_classes,
		],
		$label
	);

	$content .= blocksy_html_tag(
		'span',
		[
			'class' => $label_class,
		],
		$label
	) . $tooltip;

	if (! function_exists('blocksy_action_button')) {
		return '';
	}

	return blocksy_action_button(
		[
			'button_html_attributes' => [
				'class' => $class,
				'aria-label' => $label,
				'data-table_id' => $tab_id
			],
			'html_tag' => 'button',
			'icon' => $icon,
			'content' => $content,
		]
	);
}
