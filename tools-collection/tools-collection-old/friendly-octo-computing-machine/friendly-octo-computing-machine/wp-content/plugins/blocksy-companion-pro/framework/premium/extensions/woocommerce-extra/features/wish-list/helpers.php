<?php

function blocksy_output_add_to_wish_list($place, $attributes = []) {

	$option_ids = [
		'archive' => 'has_archive_wishlist',
		'quick-view' => 'has_quick_view_wishlist'
	];

	$has_wishlist = true;

	if ($place !== 'single') {
		$has_wishlist = (
			$place
			&&
			isset($option_ids[$place])
			&&
			blc_theme_functions()->blocksy_get_theme_mod($option_ids[$place], 'yes') === 'yes'
		);
	}

	$has_wishlist = apply_filters(
		'blocksy:ext:woocommerce-extra:wish-list:enabled',
		$has_wishlist,
		$place
	);

	if (! $has_wishlist) {
		return '';
	}

	global $product;
	$id = $product->get_id();

	$class = 'ct-wishlist-button-archive ct-button';

	if ($place !== 'archive') {
		$class = 'ct-wishlist-button-single';
	}

	$is_disabled = false;

	if (
		$product->is_type('variable')
		&&
		blc_theme_functions()->blocksy_get_theme_mod('has_variations_wishlist', 'no') === 'yes'
		&&
		blc_theme_functions()->blocksy_manager()
		&&
		function_exists('blocksy_has_product_card_specific_layer')
		&&
		blocksy_has_product_card_specific_layer('product_swatches')
	) {
		$maybe_current_variation = null;

		$maybe_current_variation = blc_theme_functions()->blocksy_manager()
			->woocommerce
			->retrieve_product_default_variation($product);

		if ($maybe_current_variation) {
			$id = $maybe_current_variation->get_id();
		} else {
			$default_attributes = $product->get_default_attributes();

			if ( ! empty($default_attributes) ) {
				$is_disabled = true;
			}
		}
	}

	$is_in_wishlist = in_array(
		$id,
		array_column(
			blc_get_ext('woocommerce-extra')->get_wish_list()->get_current_wish_list(),
			'id'
		)
	);

	$is_liked = false;

	if ($is_in_wishlist && ! $product->is_type('variable')) {
		$is_liked = true;
	}

	if ($is_in_wishlist && $product->is_type('variable')) {
		$possible_variations = array_filter(
			blc_get_ext('woocommerce-extra')->get_wish_list()->get_current_wish_list(),
			function ($item) use ($id) {
				return $item['id'] === $id;
			}
		);

		if (count($possible_variations)) {
			foreach ($possible_variations as $item) {
				if ($item['id'] !== $id) {
					continue;
				}

				if (blc_theme_functions()->blocksy_get_theme_mod('has_variations_wishlist', 'no') === 'no') {
					if (! isset($item['attributes'])) {
						$is_liked = true;
					}

					continue;
				}

				$is_liked = true;

				if (
					isset($item['attributes'])
					&&
					blc_theme_functions()->blocksy_manager()
				) {
					$maybeAttrs = [];

					$maybe_current_variation = blc_theme_functions()->blocksy_manager()
						->woocommerce
						->retrieve_product_default_variation($product);

					if ($maybe_current_variation) {
						$maybeAttrs = $maybe_current_variation->get_variation_attributes();
					}

					foreach ($item['attributes'] as $key => $value) {
						if (
							isset($maybeAttrs[$key])
							&&
							$value !== $maybeAttrs[$key]
						) {
							$is_liked = false;
							break;
						}
					}
				}
			}
		}
	}

	$content = '';

	$icon = apply_filters(
		'blocksy:ext:woocommerce-extra:wish-list:heart-icon',
		'<svg viewBox="0 0 15 15">
		<path class="ct-heart-fill" d="M12.9,3.8c-0.6-0.5-1.6-0.7-2.5-0.5C9.4,3.5,8.7,4,8.2,4.8L7.5,6.1L6.8,4.8C6.3,4,5.6,3.5,4.6,3.3C4.4,3.2,4.2,3.2,4,3.2c-0.7,0-1.4,0.2-1.9,0.6C1.5,4.3,1.1,5.1,1,5.9c-0.1,1,0.3,1.9,1,2.8c1,1.1,4.2,3.7,5.5,4.6c1.3-1,4.5-3.5,5.5-4.6c0.7-0.8,1.1-1.8,1-2.8C13.9,5.1,13.5,4.3,12.9,3.8z"/>
		<path d="M13.4,3.2c-0.9-0.8-2.3-1-3.5-0.8C8.9,2.6,8.1,3,7.5,3.7C6.9,3,6.1,2.6,5.2,2.4c-1.3-0.2-2.6,0-3.6,0.8C0.7,3.9,0.1,5,0,6.1c-0.1,1.3,0.3,2.6,1.3,3.7c1.2,1.4,5.6,4.7,5.8,4.8L7.5,15L8,14.6c0.2-0.1,4.5-3.5,5.7-4.8c1-1.1,1.4-2.4,1.3-3.7C14.9,5,14.3,3.9,13.4,3.2z M12.6,8.8c-0.9,1-3.9,3.4-5.1,4.3c-1.2-0.9-4.2-3.3-5.1-4.3c-0.7-0.8-1-1.7-0.9-2.6c0.1-0.8,0.4-1.4,1-1.9C3,4,3.6,3.8,4.2,3.8c0.2,0,0.4,0,0.6,0.1c0.9,0.2,1.6,0.7,2,1.4l0.7,1.2l0.7-1.2c0.4-0.8,1.1-1.3,2-1.4c0.8-0.2,1.7,0,2.3,0.5c0.6,0.5,1,1.2,1,1.9C13.6,7.2,13.2,8.1,12.6,8.8z"/>
		</svg>'
	);

	$shop_cards_type = blc_theme_functions()->blocksy_get_theme_mod('shop_cards_type', 'type-1');

	if (
		$place === 'archive'
		&&
		$shop_cards_type === 'type-3'
	) {
		$content .= '<span class="ct-tooltip">' . __('Add to wishlist', 'blocksy-companion') . '</span>';
	}

	if (
		$place === 'single'
		||
		$place === 'quick-view'
	) {
		$label_class = 'ct-label';

		$label_visibility = blocksy_akg('label_visibility', $attributes, [
			'desktop' => true,
			'tablet' => true,
			'mobile' => true,
		]);

		$label_visibility = blocksy_expand_responsive_value($label_visibility);

		$label = blocksy_akg('label', $attributes, __('Wishlist', 'blocksy-companion'));

		$label_class .= ' ' . blocksy_visibility_classes($label_visibility);

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

	$additional_params = [];

	if (
		blc_theme_functions()->blocksy_get_theme_mod('has_variations_wishlist', 'no') === 'yes'
		&&
		$product->is_type('variable')
		&&
		(
			(
				$place === 'archive'
				&&
				function_exists('blocksy_has_product_card_specific_layer')
				&&
				blocksy_has_product_card_specific_layer('product_swatches')
			)
			||
			$place === 'single'
			||
			$place === 'quick-view'
		)
	) {
		$additional_params = [
			'data-variable' => '',
		];
	}

	return blocksy_action_button(
		[
			'button_html_attributes' => array_merge(
				[
					'class' => $class,
					'aria-label' => __('Add to wishlist', 'blocksy-companion'),
					'data-button-state' => $is_disabled ? 'disabled' : ($is_liked ? 'active' : ''),
				],
				$additional_params
			),
			'html_tag' => 'button',
			'icon' => $icon,
			'content' => $content,
		]
	);
}


