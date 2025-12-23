<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class CompareTable {
	public static function blc_render_compare_column(
		$content = '~',
		$attrs = [
			'class' => 'ct-compare-column'
		]
	) {
		return blocksy_html_tag(
			'div',
			$attrs,
			$content
		);
	}

	public static function blc_render_compare_table_actions(
		$product,
		$maybeVariations,
		$is_mobile = false
	) {
		global $has_compare_list;
		$has_compare_list = true;

		$maybeVariationsAttrs = $product->get_attributes();

		if (
			isset($maybeVariations['attributes']) &&
			!empty($maybeVariations['attributes'])
		) {
			$maybeVariationsAttrs = array_merge(
				$maybeVariationsAttrs,
				$maybeVariations['attributes']
			);
		}

		$product->set_attributes($maybeVariationsAttrs);

		$is_simple_product = blc_get_ext(
			'woocommerce-extra'
		)->utils->is_simple_product($product);

		if ($is_simple_product['value'] && !$is_mobile) {
			do_action('woocommerce_simple_add_to_cart');
		} else {
			woocommerce_template_loop_add_to_cart();
		}

		$has_compare_list = false;
	}

	public static function blc_row_classes($layout) {
		$classes = ['ct-compare-row'];

		if (
			blocksy_akg('compare_row_sticky', $layout, 'no') === 'yes'
			&&
			blc_theme_functions()->blocksy_get_theme_mod('compare_table_placement', 'modal') === 'modal'
		) {
			$classes[] = 'ct-compare-row-is-sticky';
		}

		return trim(implode(' ', $classes));
	}

	public static function render() {
		$compare_list = blc_get_ext('woocommerce-extra')
			->get_compare()
			->get_current_compare_list();


		add_filter('wsa_sample_should_add_button', '__return_false');

		if (class_exists('EPOFW_Front')) {
			$instance = EPOFW_Front::instance();

			remove_action(
				'woocommerce_before_add_to_cart_button',
				[$instance, 'epofw_before_add_to_cart_button'],
				10
			);

			remove_action(
				'woocommerce_after_add_to_cart_button',
				[$instance, 'epofw_after_add_to_cart_button'],
				10
			);
		}

		$render_layout_config = blc_theme_functions()->blocksy_get_theme_mod('product_compare_layout', [
			[
				'id' => 'product_main',
				'enabled' => true,
			],
			[
				'id' => 'product_title',
				'enabled' => true,
			],
			[
				'id' => 'product_price',
				'enabled' => true,
			],
			[
				'id' => 'product_description',
				'enabled' => true,
			],
			[
				'id' => 'product_attributes',
				'enabled' => true,
				'product_attributes_source' => 'all',
			],
			[
				'id' => 'product_availability',
				'enabled' => true,
			],
			[
				'id' => 'product_add_to_cart',
				'enabled' => true,
			],
		]);

		if (empty($compare_list)) {
			return blocksy_render_view(
				dirname(__FILE__) . '/table-no-results.php'
			);
		}

		$products = [];

		foreach ($compare_list as $single_product) {
			$products[] = wc_get_product($single_product['id']);
		}

		$rows_html = [];

		foreach ($render_layout_config as $layout) {
			if (!$layout['enabled']) {
				continue;
			}

			if (method_exists(self::class, $layout['id'])) {
				$rows_html[] = self::{$layout['id']}($products, $layout);
			}
		}

		return blocksy_html_tag(
			'div',
			[
				'class' => 'ct-compare-table',
				'style' => '--compare-products:' . count($products)
			],
			self::render_head($products) .
			implode('', $rows_html)
		);
	}

	public static function render_head($products = []) {
		$products_html = [];

		foreach ($products as $product) {
			$products_html[] = self::blc_render_compare_column(
				blocksy_action_button(
					[
						'button_html_attributes' => [
							'href' => '#',
							'class' => 'ct-compare-remove',
							'data-product_id' => $product->get_id(),
							'title' => __('Remove Product', 'blocksy-companion')
						],
						'icon' => '<svg viewBox="0 0 15 15"><path d="M8.5,7.5l4.5,4.5l-1,1L7.5,8.5L3,13l-1-1l4.5-4.5L2,3l1-1l4.5,4.5L12,2l1,1L8.5,7.5z"></path></svg>',
						'content' => __('Remove Product', 'blocksy-companion')
					]
				)
			);
		}

		return blocksy_html_tag(
			'div',
			['class' => 'ct-compare-row'],
			self::blc_render_compare_column(
				'&nbsp;',
				['class' => 'ct-compare-column ct-compare-item-label']
			) .
			implode('', $products_html)
		);
	}

	public static function product_main($products, $layout) {
		$products_html = [];

		foreach ($products as $product) {
			$GLOBALS['product'] = $product;

			$output = '';

			if (function_exists('blocksy_output_add_to_wish_list')) {
				$output = blocksy_html_tag(
					'div',
					[
						'class' => 'ct-woo-card-extra',
						'data-type' => 'type-1'
					],
					blocksy_output_add_to_wish_list('archive')
				);
			}

			$products_html[] = self::blc_render_compare_column(
				blocksy_html_tag(
					'figure',
					[],
					$output .
					blocksy_media(
						[
							'attachment_id' => get_post_thumbnail_id($product->get_id()),
							'post_id' => $product->get_id(),
							'ratio' => blocksy_get_compare_ratio($layout),
							'size' => blocksy_akg('compare_image_size', $layout, 'medium_large'),
							'class' => 'ct-media-container',
							'tag_name' => 'a',
							'html_atts' => [
								'href' => $product->is_visible() ? $product->get_permalink() : '',
							]
						]
					)
				)
			);
		}

		return blocksy_html_tag(
			'div',
			[
				'class' => self::blc_row_classes($layout)
			],
			self::blc_render_compare_column(
				__('Image', 'blocksy-companion'),
				[
					'class' => 'ct-compare-column ct-compare-item-label',
				]
			) .
			implode('', $products_html)
		);
	}

	public static function product_title($products, $layout) {
		$products_html = [];

		foreach ($products as $product) {
			$GLOBALS['product'] = $product;

			$products_html[] = self::blc_render_compare_column(
				blocksy_html_tag(
					'h2',
					[
						'class' => esc_attr(
							apply_filters(
								'woocommerce_product_loop_title_classes',
								'woocommerce-loop-product__title'
							)
						),
					],
					blocksy_html_tag(
						'a',
						array_merge(
							[
								'class' => 'woocommerce-LoopProduct-link woocommerce-loop-product__link',
								'href' => $product->is_visible() ? $product->get_permalink() : '',
							],
						),
						blc_get_ext(
							'woocommerce-extra'
						)->utils->get_formatted_title($product->get_id())
					)
				)
			);
		}

		return blocksy_html_tag(
			'div',
			[
				'class' => self::blc_row_classes($layout)
			],
			self::blc_render_compare_column(
				__('Title', 'blocksy-companion'),
				[
					'class' => 'ct-compare-column ct-compare-item-label',
				]
			) .
			implode('', $products_html)
		);
	}

	public static function product_price($products, $layout) {
		$products_html = [];

		foreach ($products as $product) {
			$GLOBALS['product'] = $product;

			ob_start();
			woocommerce_template_loop_price();
			$price = ob_get_clean();

			$products_html[] = self::blc_render_compare_column($price);
		}

		return blocksy_html_tag(
			'div',
			[
				'class' => self::blc_row_classes($layout)
			],
			self::blc_render_compare_column(
				__('Price', 'blocksy-companion'),
				[
					'class' => 'ct-compare-column ct-compare-item-label',
				]
			) .
			implode('', $products_html)
		);
	}

	public static function product_add_to_cart($products, $layout) {
		$products_html = [];

		foreach ($products as $product) {
			$GLOBALS['product'] = $product;

			ob_start();
			woocommerce_template_loop_add_to_cart();
			$add_to_cart = ob_get_clean();

			$products_html[] = self::blc_render_compare_column($add_to_cart);
		}

		return blocksy_html_tag(
			'div',
			[
				'class' => self::blc_row_classes($layout)
			],
			self::blc_render_compare_column(
				__('Add to Cart', 'blocksy-companion'),
				[
					'class' => 'ct-compare-column ct-compare-item-label',
				]
			) .
			implode('', $products_html)
		);
	}

	public static function product_description($products, $layout) {
		$products_html = [];

		foreach ($products as $product) {
			$GLOBALS['product'] = $product;

			ob_start();
			blocksy_trim_excerpt($product->get_short_description(),  blocksy_akg('excerpt_length', $layout, '40'));
			$excerpt = ob_get_clean();

			$products_html[] = self::blc_render_compare_column($excerpt);
		}

		return blocksy_html_tag(
			'div',
			[
				'class' => self::blc_row_classes($layout)
			],
			self::blc_render_compare_column(
				__('Description', 'blocksy-companion'),
				[
					'class' => 'ct-compare-column ct-compare-item-label',
				]
			) .
			implode('', $products_html)
		);
	}

	public static function product_sku($products, $layout) {
		$products_html = [];
		$has_content = false;

		foreach ($products as $product) {
			$GLOBALS['product'] = $product;

			if ( empty($product->get_sku()) ) {
				$products_html[] = self::blc_render_compare_column();
				continue;
			}

			$has_content = true;
			$products_html[] = self::blc_render_compare_column(
				$product->get_sku()
			);
		}

		if (!$has_content) {
			return '';
		}

		return blocksy_html_tag(
			'div',
			[
				'class' => self::blc_row_classes($layout)
			],
			self::blc_render_compare_column(
				__('SKU', 'blocksy-companion'),
				[
					'class' => 'ct-compare-column ct-compare-item-label',
				]
			) .
			implode('', $products_html)
		);
	}

	public static function product_availability($products, $layout) {
		$products_html = [];

		foreach ($products as $product) {
			$GLOBALS['product'] = $product;

			$availability = $product->is_in_stock();

			$products_html[] = self::blc_render_compare_column(
				$availability ? __('In Stock', 'blocksy-companion') : __('Out of Stock', 'blocksy-companion')
			);
		}

		return blocksy_html_tag(
			'div',
			[
				'class' => self::blc_row_classes($layout)
			],
			self::blc_render_compare_column(
				__('Availability', 'blocksy-companion'),
				[
					'class' => 'ct-compare-column ct-compare-item-label',
				]
			) .
			implode('', $products_html)
		);
	}

	public static function product_rating($products, $layout) {
		$products_html = [];
		$has_content = false;

		foreach ($products as $product) {
			$GLOBALS['product'] = $product;

			ob_start();
			woocommerce_template_loop_rating();
			$rating = ob_get_clean();

			if ( ! empty($rating) ) {
				$has_content = true;

				$products_html[] = self::blc_render_compare_column($rating);
				continue;
			}

			$products_html[] = self::blc_render_compare_column();
		}


		return blocksy_html_tag(
			'div',
			[
				'class' => self::blc_row_classes($layout)
			],
			self::blc_render_compare_column(
				__('Rating', 'blocksy-companion'),
				[
					'class' => 'ct-compare-column ct-compare-item-label',
				]
			) .
			implode('', $products_html)
		);
	}

	public static function product_attributes($products, $layout) {
		$rows_html = [];
		$taxonomies = [];

		if (blocksy_akg('product_attributes_source', $layout, 'all') === 'all') {
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			foreach ($attribute_taxonomies as $tax) {
				$taxonomies[] = $tax->attribute_name;
			}
		} else {
			if (
				! isset( $layout['taxonomies_to_compare'])
				||
				empty($layout['taxonomies_to_compare'])
			) {
				return '';
			}

			$taxonomies = array_column($layout['taxonomies_to_compare'], 'id');
		}

		if (empty($taxonomies)) {
			return '';
		}

		foreach ($taxonomies as $taxonomy_to_compare) {
			if (
				! $taxonomy_to_compare
				||
				! taxonomy_exists(wc_attribute_taxonomy_name($taxonomy_to_compare))
			) {
				continue;
			}

			$taxonomy_name = wc_attribute_taxonomy_name($taxonomy_to_compare);
			$taxonomy_hr_name = $taxonomy_to_compare;

			if (taxonomy_exists($taxonomy_name)) {
				$labels = get_taxonomy_labels(get_taxonomy($taxonomy_name));

				if (isset($labels->singular_name)) {
					$taxonomy_hr_name = $labels->singular_name;
				}
			}

			$columns = [];
			$columns[] = self::blc_render_compare_column(
				$taxonomy_hr_name,
				[
					'class' => 'ct-compare-column ct-compare-item-label',
				]
			);

			$has_content = false;

			foreach ($products as $product) {
				$GLOBALS['product'] = $product;

				$attributes = $product->get_attributes();

				if ( ! isset($attributes[sanitize_title($taxonomy_name)]) ) {
					$columns[] = self::blc_render_compare_column();
					continue;
				}

				$attribute = $attributes[sanitize_title($taxonomy_name)];

				if ($attribute === false) {
					$columns[] = self::blc_render_compare_column();
					continue;
				} else {
					$values = [];

					if ( $attribute->is_taxonomy() ) {
						$attribute_taxonomy = $attribute->get_taxonomy_object();
						$attribute_values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

						foreach ( $attribute_values as $attribute_value ) {

							$value_name = esc_html( $attribute_value->name );

							if ( $attribute_taxonomy->attribute_public ) {
								$values[] = $value_name;
							} else {
								$values[] = $value_name;
							}
						}
					} else {
						$values = $attribute->get_options();

						foreach ( $values as &$value ) {
							$value = make_clickable( esc_html( $value ) );
						}
					}

					$has_content = true;

					$columns[] = self::blc_render_compare_column(
						apply_filters(
							'woocommerce_attribute',
							wpautop( wptexturize( implode( ', ', $values ) ) ),
							$attribute,
							$values
						)
					);
				}
			}
			if ( $has_content ) {
				$rows_html[] = blocksy_html_tag(
					'div',
					[
						'class' => self::blc_row_classes($layout)
					],
					join('', $columns)
				);
			}
		}

		return implode('', $rows_html);
	}

	public static function product_brands($products, $layout) {
		$products_html = [];

		foreach ($products as $product) {
			$GLOBALS['product'] = $product;

			$brands = get_the_terms($product->get_id(), 'product_brand');

			if (!$brands || !is_array($brands) || empty($brands)) {
				$products_html[] = self::blc_render_compare_column();
				continue;
			}

			$brands_html = '';

			foreach ($brands as $key => $brand) {
				$label = blocksy_html_tag(
					'a',
					[
						'href' => esc_url(get_term_link($brand))
					],
					$brand->name
				);

				$term_atts = get_term_meta(
					$brand->term_id,
					'blocksy_taxonomy_meta_options'
				);

				if (empty($term_atts)) {
					$term_atts = [[]];
				}

				$term_atts = $term_atts[0];

				$maybe_image_id = isset($brand->term_id) ? get_term_meta($brand->term_id, 'thumbnail_id', true) : '';

				if (! empty($maybe_image_id)) {
					$term_atts['icon_image'] = [
						'attachment_id' => $maybe_image_id,
						'url' => wp_get_attachment_image_url($maybe_image_id, 'full')
					];
				}

				$maybe_image = blocksy_akg('icon_image', $term_atts, '');

				if (
					$maybe_image
					&&
					is_array($maybe_image)
					&&
					isset($maybe_image['attachment_id'])
				) {
					$attachment_id = $maybe_image['attachment_id'];

					$label = blocksy_media([
						'attachment_id' => $maybe_image['attachment_id'],
						'size' => 'full',
						'ratio' => 'original',
						'tag_name' => 'a',
						'html_atts' => [
							'href' => get_term_link($brand),
						]
					]);
				}

				$brands_html .= $label;
			}

			$products_html[] = self::blc_render_compare_column(
				blocksy_html_tag(
					'div',
					[
						'class' => 'ct-product-brands'
					],
					$brands_html
				)
			);
		}

		return blocksy_html_tag(
			'div',
			[
				'class' => self::blc_row_classes($layout)
			],
			self::blc_render_compare_column(
				__('Brands', 'blocksy-companion'),
				[
					'class' => 'ct-compare-column ct-compare-item-label',
				]
			) .
			implode('', $products_html)
		);
	}
}
