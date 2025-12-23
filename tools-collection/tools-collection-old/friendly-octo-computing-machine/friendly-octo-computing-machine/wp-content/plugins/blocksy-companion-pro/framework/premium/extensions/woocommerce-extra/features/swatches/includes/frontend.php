<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class SwatchesFrontend {
	public function render_variation_swatches($args) {
		$product = $args['product'];
		$layer = $args['layer'];

		$taxonomies_to_show = blocksy_akg('taxonomies_to_show', $layer, []);
		$product_attributes_source = blocksy_akg('product_attributes_source', $layer, 'all');

		$swatches_html = [];
		$attributes = $product->get_variation_attributes();
		$displayed_attributes = [];

		if (
			blc_theme_functions()->blocksy_get_theme_mod('limit_number_of_swatches', 'no') === 'yes'
			&&
			! empty(blc_theme_functions()->blocksy_get_theme_mod('archive_limit_number_of_swatches_number', ''))
		) {
			$args['limit'] = blc_theme_functions()->blocksy_get_theme_mod('archive_limit_number_of_swatches_number', '');
		}

		if ($product_attributes_source === 'custom') {
			$sorted = [
				'global' => [],
				'custom' => []
			];

			foreach ($attributes as $attribute_name => $options) {
				$target = taxonomy_exists($attribute_name) ? 'global' : 'custom';
				$sorted[$target][$attribute_name] = $options;
			}

			foreach ($taxonomies_to_show as $layer_settings) {
				if (empty($layer_settings['enabled'])) {
					continue;
				}

				$id = $layer_settings['id'];
				$is_custom = $id === 'ct_custom_attributes';
				$woo_name = wc_attribute_taxonomy_name($id);

				$has_attribute = isset($attributes[$woo_name]) || isset($attributes[$id]);
				if (!$is_custom && !$has_attribute) {
					continue;
				}

				if ($is_custom) {
					foreach ($sorted['custom'] as $attr_name => $options) {
						$swatches_html[] = $this->render_single_attribute($attr_name, $options, $args);
						$displayed_attributes[] = $attr_name;
					}
				} elseif (isset($sorted['global'][$woo_name])) {
					$swatches_html[] = $this->render_single_attribute($woo_name, $sorted['global'][$woo_name], $args);
					$displayed_attributes[] = $woo_name;
				}
			}
		} else {
			foreach ($attributes as $attr_name => $options) {
				$swatches_html[] = $this->render_single_attribute($attr_name, $options, $args);
				$displayed_attributes[] = $attr_name;
			}
		}

		$not_displayed_attributes = array_diff(
			array_keys($attributes),
			$displayed_attributes
		);

		// If there are attributes that are not displayed and those attributes
		// don't have a default value -- the form is incomplete.
		$not_displayed_without_defaults = [];

		foreach ($not_displayed_attributes as $not_displayed_attribute) {
			$default_value = $product->get_variation_default_attribute($not_displayed_attribute);

			if (! empty($default_value)) {
				continue;
			}

			$not_displayed_without_defaults[] = $not_displayed_attribute;
		}

		$swatches_html = implode('', $swatches_html);
		$attr = [
			'class' => 'ct-card-variation-swatches variations_form',
			'data-out-of-stock-swatch-type' => blc_theme_functions()->blocksy_get_theme_mod(
				'out_of_stock_swatch_type',
				'faded'
			),
		];

		$json = blc_get_ext('woocommerce-extra')
			->utils
			->get_available_variations($product->get_id());

		$get_variations = count($json) <= apply_filters(
			'woocommerce_ajax_variation_threshold',
			30,
			$product
		);

		if (! $get_variations) {
			$hidden_fields_html = [];

			foreach ($not_displayed_attributes as $not_displayed_attribute) {
				$default_value = $product->get_variation_default_attribute($not_displayed_attribute);

				if (empty($default_value)) {
					continue;
				}

				ob_start();

				wc_dropdown_variation_attribute_options([
					'options' => [
						$default_value
					],
					'attribute' => $not_displayed_attribute,
					'product' => $product,
					'selected' => $default_value,
					'id' => strtolower(
						wp_unique_id(
							str_replace(
								'attribute_',
								'',
								$not_displayed_attribute
							) . '--'
						)
					)
				]);

				$select_hidden_result = ob_get_clean();

				$select_hidden_result = str_replace(
					'<select',
					'<select hidden',
					$select_hidden_result
				);

				$hidden_fields_html[] = $select_hidden_result;
			}

			$swatches_html .= implode('', $hidden_fields_html);
		}

		$swatches_html = blocksy_html_tag(
			'div',
			['class' => 'variations'],
			$swatches_html
		);

		$simple = new \WC_Product_Simple($product->get_id());

		$attr['data-product_variations'] = "false";
		$attr['data-product_id'] = $product->get_id();

		if ($get_variations) {
			$attr['data-product_variations'] = wc_esc_json(wp_json_encode($json));
		}

		$attr['data-dynamic-card-data'] = wc_esc_json(wp_json_encode([
			'variable' => [
				'text' => $product->add_to_cart_text(),
				'link' => $product->add_to_cart_url(),
				'price' => '<span class="price">' . $product->get_price_html() . '</span>',
			],

			'simple' => [
				'text' => $simple->add_to_cart_text(),
				'link' => $simple->add_to_cart_url()
			],

			'isCompleteVariationsForm' => count($not_displayed_without_defaults) === 0
		]));

		return blocksy_html_tag('div', $attr, $swatches_html);
	}

	public function get_swatch_html($args) {
		if (
			(
				is_admin()
				&&
				! defined('DOING_AJAX')
			)
			||
			empty($args['options'])
			||
			! $args['product']
		) {
			return '';
		}

		$result = '';
		$elements = $this->get_attribute_elements($args);

		foreach ($elements as $single_element) {
			$swatch_element = new SwatchElementRender($single_element);
			$result .= $swatch_element->get_output();
		}

		if (
			isset($args['limit'])
			&&
			$args['limit'] > 0
			&&
			count($elements) > $args['limit']
			&&
			blc_theme_functions()->blocksy_get_theme_mod('limit_number_of_swatches_more_button', 'no') === 'yes'
		) {
			$result .= blocksy_html_tag(
				'a',
				[
					'class' => 'ct-swatches-more',
					'href' => '#',
					'aria-label' => esc_html__('Show more swatches', 'blocksy-companion'),
					'data-swatches-limit' => $args['limit'],
					'data-state' => 'collapsed',
				],
				blc_safe_sprintf(
					// translators: %s is the number of additional swatches.
					esc_html__('+%s More', 'blocksy-companion'),
					count($elements) - $args['limit']
				)
			);
		}

		return $result;
	}

	private function is_selected($term_slug, $term_attribute, $product) {
		$maybe_current_variation = null;

		if (blc_theme_functions()->blocksy_manager()) {
			$maybe_current_variation = blc_theme_functions()->blocksy_manager()
				->woocommerce
				->retrieve_product_default_variation($product);
		}

		$attributes = $product->get_attributes();

		if ($maybe_current_variation) {
			$attributes = $maybe_current_variation->get_attributes();
		}

		$is_selected = false;
		$clean_term_attribute = sanitize_title(strtolower($term_attribute));

		if (isset($attributes[$clean_term_attribute])) {
			$is_selected = $attributes[$clean_term_attribute] === $term_slug;

			if ($product) {
				$is_selected = $term_slug === $product->get_variation_default_attribute($clean_term_attribute);
			}
		}

		$selected_key = 'attribute_' . sanitize_title($term_attribute);

		if (
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			isset($_REQUEST[$selected_key])
			&&
			get_queried_object_id() === $product->get_id()
		) {
			$is_selected = wc_clean(
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				strtolower(sanitize_text_field(wp_unslash($_REQUEST[$selected_key])))
			) === strtolower($term_slug);
		}

		return $is_selected;
	}

	// element_slug
	// element_label
	//
	// element_type
	//
	// element_atts
	// is_selected
	// is_limited
	//
	//
	// is_out_of_stock - we need to track out of stock status specifically
	//                   because there can be 3 states of a specific swatch:
	//                   - in stock
	//                   - out of stock
	//                   - no existing variation at all (disable click in this case)
	private function get_attribute_elements($args = []) {
		$args = wp_parse_args(
			$args,
			[
				'limit' => 0
			]
		);

		$result = [];

		$is_custom_attribute = ! taxonomy_exists($args['attribute']);

		$conf = new SwatchesConfig();
		$meta = $conf->get_attributes_meta($args['attribute']);

		if (! $is_custom_attribute) {
			$terms = wc_get_product_terms(
				$args['product']->get_id(),
				$args['attribute'],
				[
					'fields' => 'all',
				]
			);

			$limited_index = 0;

			foreach ($terms as $key => $single_term) {
				if (! in_array($single_term->slug, $args['options'])) {
					continue;
				}

				$is_limited = false;

				if ($args['limit'] > 0 && $limited_index >= $args['limit']) {
					$is_limited = true;
				}

				$limited_index++;

				$is_selected = $this->is_selected(
					$single_term->slug,
					$single_term->taxonomy,
					$args['product']
				);

				$swatch_type = blocksy_akg('swatch_type', $meta, 'inherit');

				$base_element = $conf->get_swatch_element_descriptor(
					$single_term,
					[
						'read_atts' => $swatch_type === 'inherit'
					]
				);

				$term_status = $this->get_term_status(
					$single_term->slug,
					$single_term->taxonomy,
					$args['product']
				);

				$base_element['is_selected'] = $is_selected;
				$base_element['is_out_of_stock'] = $term_status === 'out_of_stock';
				$base_element['is_invalid'] = $term_status === 'invalid';
				$base_element['is_limited'] = $is_limited;

				if ($swatch_type !== 'inherit') {
					$base_element['element_type'] = blocksy_akg(
						'swatch_type',
						$meta,
						'inherit'
					);

					$base_element['element_atts'] = blocksy_akg(
						implode('/', [
							'values',
							$single_term->term_id
						]),
						$meta,
						[]
					);
				}

				if ($base_element['element_type'] === 'image') {
					$image_swatch_fallback = blc_theme_functions()->blocksy_get_theme_mod(
						'image_swatch_fallback',
						'no'
					);

					if ($image_swatch_fallback === 'yes') {
						$variation = $this->find_variation_with_attribute($single_term->slug, $single_term->taxonomy, $args['product']);

						if (
							$variation
							&&
							$variation->get_image_id()
							&&
							! isset($base_element['element_atts']['image']['url'])
						) {
							$base_element['element_atts']['image'] = [
								'url' => wp_get_attachment_url($variation->get_image_id()),
								'attachment_id' => $variation->get_image_id()
							];
						}
					}
				}

				$result[] = $base_element;
			}
		}

		if ($is_custom_attribute) {
			$options = $args['options'];

			$custom_attribute_slug = null;

			foreach ($args['product']->get_attributes() as $key => $value) {
				if ($value->get_name() !== $args['attribute']) {
					continue;
				}

				$custom_attribute_slug = $key;
			}

			if (! $custom_attribute_slug) {
				return [];
			}

			foreach ($options as $key => $future_element) {
				$is_limited = false;

				if ($args['limit'] > 0 && $key >= $args['limit']) {
					$is_limited = true;
				}

				$term_status = $this->get_term_status(
					$future_element,
					$custom_attribute_slug,
					$args['product']
				);

				$is_out_of_stock = $term_status === 'out_of_stock';

				$is_selected = $this->is_selected(
					$future_element,
					$custom_attribute_slug,
					$args['product']
				);

				$element = [
					'element_slug' => $future_element,
					'element_label' => $future_element,

					'is_selected' => $is_selected,
					'is_out_of_stock' => $is_out_of_stock,
					'is_invalid' => $term_status === 'invalid',
					'is_limited' => $is_limited,

					'element_atts' => [],
					'element_type' => ''
				];

				$element['element_type'] = blocksy_akg(
					'swatch_type',
					$meta,
					'button'
				);

				$element['element_atts'] = isset($meta['values'][$future_element]) ? $meta['values'][$future_element] : [];

				$result[] = $element;
			}
		}

		return $result;
	}

	private function find_variation_with_attribute($term_slug, $term_attribute, $product) {
		$variations = $product->get_available_variations();

		foreach ($variations as $single_variation) {
			if (
				isset($single_variation['attributes']['attribute_' . sanitize_title($term_attribute)])
				&&
				$single_variation['attributes']['attribute_' . sanitize_title($term_attribute)] === $term_slug
			) {
				return new \WC_Product_Variation($single_variation['variation_id']);
			}
		}

		return null;
	}

	// valid | invalid | out_of_stock
	private function get_term_status($term_slug, $term_attribute, $product) {
		$attribute_terms_stock = blc_get_ext('woocommerce-extra')
			->utils
			->get_attributes_terms_stock($product, $term_attribute);

		if (in_array(null, $attribute_terms_stock['valid'])) {
			return 'valid';
		}

		$is_out_of_stock = in_array($term_slug, $attribute_terms_stock['out_of_stock']);

		if ($is_out_of_stock) {
			return 'out_of_stock';
		}

		if (! in_array($term_slug, $attribute_terms_stock['valid'])) {
			return 'invalid';
		}

		return 'valid';
	}

	public function render_single_attribute($attribute_name, $options, $args) {
		$product = $args['product'];

		$conf = new SwatchesConfig();

		$type = $conf->get_attribute_type($attribute_name, [
			'product' => $product
		]);

		$html_attr = [
			'class' => 'ct-variation-swatches',
			'data-swatches-type' => $type,
			'data-attr' => $attribute_name
		];

		if ($type === 'color') {
			$html_attr['data-swatches-shape'] = blc_theme_functions()->blocksy_get_theme_mod(
				'color_swatch_shape',
				'round'
			);
		}

		if ($type === 'image') {
			$html_attr['data-swatches-shape'] = blc_theme_functions()->blocksy_get_theme_mod(
				'image_swatch_shape',
				'round'
			);
		}

		if ($type === 'button') {
			$html_attr['data-swatches-shape'] = blc_theme_functions()->blocksy_get_theme_mod(
				'button_swatch_shape',
				'round'
			);
		}

		if ($type === 'mixed') {
			$html_attr['data-swatches-shape'] = blc_theme_functions()->blocksy_get_theme_mod(
				'mixed_swatch_shape',
				'round'
			);
		}

		ob_start();
		wc_dropdown_variation_attribute_options([
			'options' => $options,
			'attribute' => $attribute_name,
			'product' => $product,
			'id' => strtolower(
				wp_unique_id(
					str_replace(
						'attribute_',
						'',
						$attribute_name
					) . '--'
				)
			)
		]);
		$content = ob_get_clean();

		if ($type !== 'select') {
			$content .= $this->get_swatch_html(
				array_merge(
					$args,
					[
						'options' => $options,
						'attribute' => $attribute_name,
						'product' => $product,
					]
				)
			);
		}

		return blocksy_html_tag(
			'div',
			$html_attr,
			$content
		);
	}
}

