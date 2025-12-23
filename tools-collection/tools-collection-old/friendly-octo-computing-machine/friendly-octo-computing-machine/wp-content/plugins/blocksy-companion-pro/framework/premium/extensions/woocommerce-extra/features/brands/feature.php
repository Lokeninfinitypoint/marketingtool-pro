<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class Brands {
	public function __construct() {
		add_action('woocommerce_after_register_taxonomy', [$this, 'register_brand_meta']);

		add_filter(
			'blocksy_woo_single_options_layers:defaults',
			[$this, 'add_layer_to_default_layout']
		);
		add_filter(
			'blocksy_woo_compare_layers:defaults',
			[$this, 'add_layer_to_default_layout']
		);
		add_filter(
			'blocksy_woo_card_options_layers:defaults',
			[$this, 'add_layer_to_default_layout']
		);
		add_filter(
			'blocksy_woo_single_right_options_layers:defaults',
			[$this, 'add_layer_to_default_layout']
		);

		add_filter(
			'blocksy_woo_single_options_layers:extra',
			[$this, 'add_single_layer_options']
		);
		add_filter(
			'blocksy_woo_compare_layers:extra',
			[$this, 'add_compare_layer_options']
		);
		add_filter(
			'blocksy_woo_card_options_layers:extra',
			[$this, 'add_archive_layer_options']
		);
		add_filter(
			'blocksy_woo_single_right_options_layers:extra',
			[$this, 'add_single_layer_options']
		);

		add_action(
			'blocksy:woocommerce:product:custom:layer',
			[$this, 'product_single_render']
		);

		add_action(
			'blocksy:woocommerce:product-card:custom:layer',
			[$this, 'product_card_render']
		);

		add_action(
			'blocksy:woocommerce:compare:custom:layer',
			[$this, 'product_card_render']
		);

		add_filter(
			'blocksy:options:woo:tabs:general:brands',
			function ($opts) {
				$opts[] = blocksy_get_options(
					dirname(__FILE__) . '/options.php',
					[],
					false
				);

				return $opts;
			},
			50
		);

		add_action(
			'wp',
			function() {
				if (blc_theme_functions()->blocksy_get_theme_mod('has_woo_brands_tab', 'no') === 'yes') {
					add_filter(
						'woocommerce_product_tabs',
						[$this, 'brands_custom_product_tab']
					);
				}
			}
		);

		add_action(
			'woocommerce_product_duplicate',
			function ($duplicate, $product) {
				$terms = get_the_terms($product->get_id(), 'product_brand');

				if (! is_wp_error($terms)) {
					wp_set_object_terms($duplicate->get_id(), wp_list_pluck($terms, 'term_id'), 'product_brand');
				}
			},
			999,
			2
		);

		add_action('woocommerce_coupon_options_usage_restriction', [$this, 'restrict_coupon_by_brand_form'], 10, 2);
		add_action('woocommerce_coupon_options_save', [$this, 'restrict_coupon_by_brand_save'], 10, 2);
		add_filter('woocommerce_coupon_is_valid_for_product', [$this, 'coupon_is_valid_for_product'], 10, 4);
		add_filter('woocommerce_coupon_is_valid_for_cart', [$this, 'coupon_is_valid_for_cart'], 10, 2);

		add_action('rest_api_init', function() {
			register_rest_field(
				'product',
				'ct_brands',
				array(
					'get_callback' => function ($post, $field_name, $request) {
						$product_id = $post['id'];

						$terms = [];
						$brands = get_the_terms($product_id, 'product_brand');

						if (! $brands) {
							$brands = [];
						}

						foreach ($brands as $brand) {
							$terms[] = [
								'id'   => $brand->term_id,
								'name' => $brand->name,
								'slug' => $brand->slug,
							];
						}

						return $terms;
					},
					'update_callback' => function ($value, $object, $field_name) {
						if (! is_array($value)) {
							return;
						}

						if (! current_user_can('edit_posts')) {
							return;
						}

						$terms = [];

						foreach ($value as $brand) {
							$brand = get_term_by('id', $brand['id'], 'product_brand');

							if (! $brand) {
								continue;
							}

							$terms[] = $brand->term_id;
						}

						wp_set_object_terms($object->get_id(), $terms, 'product_brand');

						return $value;
					}
				)
			);
		});
	}

	public function is_invalid($wc_coupon, $to_compare) {
		$conditions = $this->get_exclude_include_conditions($wc_coupon);

		$include = $conditions['include_product_brands'];
		$exclude = $conditions['exclude_product_brands'];

		if (empty($include)) {
			return false;
		}

		$intersect_include = array_intersect($include, $to_compare);
		$intersect_exclude = array_intersect($exclude, $to_compare);

		if (count($intersect_include) === 0) {
			return true;
		}

		if (count($intersect_exclude) > 0) {
			return true;
		}

		return false;
	}

	public function get_exclude_include_conditions($wc_coupon) {
		$include_product_brands = get_post_meta($wc_coupon->get_id(), 'include_product_brands', true);
		$exclude_product_brands = get_post_meta($wc_coupon->get_id(), 'exclude_product_brands', true);

		if (! $include_product_brands) {
			$include_product_brands = [];
		}

		if (! $exclude_product_brands) {
			$exclude_product_brands = [];
		}

		return [
			'include_product_brands' => array_map('intval', $include_product_brands),
			'exclude_product_brands' => array_map('intval', $exclude_product_brands)
		];
	}

	public function coupon_is_valid_for_cart($valid, $wc_coupon) {
		if (is_null(WC()->cart)) {
			return $valid;
		}

		$cart = WC()->cart->get_cart();

		$cart_brands = [];

		foreach ($cart as $cart_item) {
			$product = wc_get_product($cart_item['product_id']);

			if ($product->get_type() === 'variation') {
				$product = wc_get_product($product->get_parent_id());
			}

			$brands = get_the_terms($product->get_id(), 'product_brand');

			if (! $brands) {
				$brands = [];
			}

			$brands = array_map(function($brand) {
				return $brand->term_id;
			}, $brands);

			$cart_brands = array_merge($cart_brands, $brands);
		}

		$is_invalid = $this->is_invalid($wc_coupon, $cart_brands);

		if ($is_invalid) {
			return false;
		}

		return $valid;
	}

	public function coupon_is_valid_for_product($valid, $product, $wc_coupon, $values) {

		if ($product->get_type() === 'variation') {
			$product = wc_get_product($product->get_parent_id());
		}

		$current_product_brands = get_the_terms($product->get_id(), 'product_brand');

		if (! $current_product_brands) {
			$current_product_brands = [];
		}

		$current_product_brands = array_map(function($brand) {
			return $brand->term_id;
		}, $current_product_brands);

		$is_invalid = $this->is_invalid($wc_coupon, $current_product_brands);

		if ($is_invalid) {
			return false;
		}

		return $valid;
	}

	public function restrict_coupon_by_brand_save($post_id, $coupon) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$include_product_brands = isset($_POST['include_product_brands']) ? array_map('intval', $_POST['include_product_brands']) : [];
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$exclude_product_brands = isset($_POST['exclude_product_brands']) ? array_map('intval', $_POST['exclude_product_brands']) : [];

		update_post_meta($post_id, 'include_product_brands', $include_product_brands);
		update_post_meta($post_id, 'exclude_product_brands', $exclude_product_brands);
	}

	public function restrict_coupon_by_brand_form($coupon_id, $coupon) {

		$brands = get_terms([
			'taxonomy' => 'product_brand',
			'orderby' => 'name',
			'hide_empty' => false
		]);

		$include_html = blocksy_html_tag(
			'p',
			[
				'class' => 'form-field',
			],
			blocksy_html_tag(
				'label',
				[],
				__('Include brands', 'blocksy-companion')
			) .
			blocksy_html_tag(
				'select',
				[
					'id' => 'include_product_brands',
					'name' => 'include_product_brands[]',
					'style' => 'width: 50%;',
					'class' => 'wc-enhanced-select',
					'multiple' => 'multiple',
					'data-placeholder' => __('No brands', 'blocksy-companion')
				],
				(function () use ($coupon, $brands) {
					$brand_ids = get_post_meta($coupon->get_id(), 'include_product_brands', true);

					if (! $brand_ids) {
						$brand_ids = [];
					}

					$output = '';

					if ($brands) {
						foreach ($brands as $brand) {
							$output .= blocksy_html_tag(
								'option',
								array_merge(
									[
										'value' => esc_attr($brand->term_id),
									],
									in_array($brand->term_id, $brand_ids) ? ['selected' => 'selected'] : []
								),
								esc_html($brand->name)
							);
						}
					}

					return $output;
				})()
			) .
			wc_help_tip(
				__(
					'Product brands that the coupon will not be applied to, or that cannot be in the cart in order for the "Fixed cart discount" to be applied.',
					'blocksy-companion'
				)
			)
		);

		$exclude_html = blocksy_html_tag(
			'p',
			[
				'class' => 'form-field',
			],
			blocksy_html_tag(
				'label',
				[],
				__('Exclude brands', 'blocksy-companion')
			) .
			blocksy_html_tag(
				'select',
				[
					'id' => 'exclude_product_brands',
					'name' => 'exclude_product_brands[]',
					'style' => 'width: 50%;',
					'class' => 'wc-enhanced-select',
					'multiple' => 'multiple',
					'data-placeholder' => __('No brands', 'blocksy-companion')
				],
				(function () use ($coupon, $brands) {
					$brand_ids = get_post_meta($coupon->get_id(), 'exclude_product_brands', true);

					if (! $brand_ids) {
						$brand_ids = [];
					}

					$output = '';

					if ($brands) {
						foreach ($brands as $brand) {
							$output .= blocksy_html_tag(
								'option',
								array_merge(
									[
										'value' => esc_attr($brand->term_id),
									],
									in_array($brand->term_id, $brand_ids) ? ['selected' => 'selected'] : []
								),
								esc_html($brand->name)
							);
						}
					}

					return $output;
				})()
			) .
			wc_help_tip(
				__(
					'Product brands that the coupon will be applied to, or that must be in the cart in order for the "Fixed cart discount" to be applied.',
					'blocksy-companion'
				)
			)
		);

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $include_html . $exclude_html;
	}

	public function brands_custom_product_tab($tabs) {
		global $product;

		$brands = get_the_terms($product->get_id(), 'product_brand');

		if (! $brands || ! is_array($brands)) {
			return $tabs;
		}

		if (! count($brands)) {
			return $tabs;
		}

		$title = __('About Brands', 'blocksy-companion');

		if (blc_theme_functions()->blocksy_get_theme_mod('use_brand_name_for_tab_title', 'no') === 'no') {
			$title = blc_safe_sprintf(
				// translators: %s is the brand name.
				__('About %s', 'blocksy-companion'),
				$brands[0]->name
			);
		}

		$tabs['specific_product_tab'] = array(
			'title' => $title,
			'priority' => 50,
			'callback' => [$this, 'brands_custom_product_tab_render']
		);

		return $tabs;
	}

	// Add content to a custom product tab
	public function brands_custom_product_tab_render() {
		$brands = get_the_terms(get_the_ID(), 'product_brand');

		if (! $brands || ! is_array($brands)) {
			return;
		}

		if (! count($brands)) {
			return;
		}

		$output = '';

		$tabs_type = blc_theme_functions()->blocksy_get_theme_mod('woo_tabs_type', 'type-1');

		if ($tabs_type === 'type-4') {
			$output .= blocksy_html_tag(
				'h2',
				[],
				blc_theme_functions()->blocksy_get_theme_mod('use_brand_name_for_tab_title', 'no') === 'no'
					? __('About Brands', 'blocksy-companion')
					: blc_safe_sprintf(
						// translators: %s is the brand name.
						__('About %s', 'blocksy-companion'),
						$brands[0]->name
					)
			);
		}

		foreach ($brands as $key => $brand) {
			$output .= blocksy_html_tag(
				'div',
				[
					'class' => 'ct-product-brands-tab'
				],
				do_shortcode(wpautop($brand->description))
			);
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $output;
	}

	public function add_compare_layer_options($opt) {
		$opt = array_merge(
			$opt,
			[
				'product_brands' => [
					'label' => __('Brands', 'blocksy-companion'),
					'options' => [
						'brand_logo_size' => [
							'label' => __('Logo Size', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 60,
							'min' => 30,
							'max' => 200,
							'responsive' => true,
							'sync' => [
								'id' => 'woo_compare_layout_skip'
							]
						],

						'brand_logo_gap' => [
							'label' => __('Logos Gap', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 10,
							'min' => 0,
							'max' => 100,
							'responsive' => true,
							'sync' => [
								'id' => 'woo_compare_layout_skip'
							]
						],

						'compare_row_sticky' => [
							'type'  => 'ct-switch',
							'label' => __( 'Sticky Row', 'blocksy-companion' ),
							'value' => 'no',
						],
					]
				]
			]
		);

		return $opt;
	}

	public function add_single_layer_options($opt) {
		$opt = array_merge(
			$opt,
			[
				'product_brands' => [
					'label' => __('Brands', 'blocksy-companion'),
					'options' => [

						'brand_layer_title' => [
							'label' => __('Title', 'blocksy-companion'),
							'type' => 'text',
							'design' => 'block',
							'value' => '',
							'disableRevertButton' => true,
							'sync' => [
								'id' => 'woo_card_layout_skip'
							],
						],

						'brand_logo_size' => [
							'label' => __('Logo Size', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 60,
							'min' => 30,
							'max' => 200,
							'responsive' => true,
							'sync' => [
								'id' => 'woo_card_layout_skip'
							]
						],

						'brand_logo_gap' => [
							'label' => __('Logos Gap', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 10,
							'min' => 0,
							'max' => 100,
							'responsive' => true,
							'sync' => [
								'id' => 'woo_card_layout_skip'
							]
						],

						'spacing' => [
							'label' => __('Bottom Spacing', 'blocksy-companion'),
							'type' => 'ct-slider',
							'min' => 0,
							'max' => 100,
							'value' => 10,
							'responsive' => true,
							'sync' => [
								'id' => 'woo_card_layout_skip'
							]
						],
					]
				],
			]
		);

		return $opt;
	}

	public function add_archive_layer_options($opt) {
		$opt = array_merge(
			$opt,
			[
				'product_brands' => [
					'label' => __('Brands', 'blocksy-companion'),
					'options' => [

						'brand_logo_size' => [
							'label' => __('Logo Size', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 60,
							'min' => 30,
							'max' => 200,
							'responsive' => true,
							'sync' => [
								'id' => 'woo_card_layout_skip'
							]
						],

						'brand_logo_gap' => [
							'label' => __('Logos Gap', 'blocksy-companion'),
							'type' => 'ct-slider',
							'value' => 10,
							'min' => 0,
							'max' => 100,
							'responsive' => true,
							'sync' => [
								'id' => 'woo_card_layout_skip'
							]
						],

						'spacing' => [
							'label' => __('Bottom Spacing', 'blocksy-companion'),
							'type' => 'ct-slider',
							'min' => 0,
							'max' => 100,
							'value' => 10,
							'responsive' => true,
							'sync' => [
								'id' => 'woo_card_layout_skip'
							]
						],
					]
				],
			]
		);

		return $opt;
	}

	public function add_layer_to_default_layout($opt) {
		$opt = array_merge(
			$opt,
			[
				[
					'id' => 'product_brands',
					'enabled' => false,
				]
			]
		);

		return $opt;
	}

	public function register_brand_meta() {
		$storage = new Storage();
		$settings = $storage->get_settings();

		add_action('product_brand_edit_form', [$this, 'term_options']);
		add_action('product_brand_add_form', [$this, 'term_options']);

		add_action('edited_term', [$this, 'save_term_meta'], 10, 3);
		add_action('create_term', [$this, 'save_term_meta'], 10, 3);
	}

	public function save_term_meta($term_id, $tt_id, $taxonomy) {
		if (
			!(
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				isset($_POST['action'])
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				('editedtag' === $_POST['action'] || 'add-tag' === $_POST['action'])
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				isset($_POST['taxonomy'])
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				($taxonomy = get_taxonomy(sanitize_text_field(wp_unslash($_POST['taxonomy']))))
				&&
				current_user_can($taxonomy->cap->edit_terms)
			)
			||
			$taxonomy->name !== 'product_brand'
		) {
			return;
		}

		$values = [];

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (isset($_POST['blocksy_taxonomy_meta_options'][blocksy_post_name()])) {
			$values = json_decode(
				sanitize_text_field(
					wp_unslash(
						// phpcs:ignore WordPress.Security.NonceVerification.Missing
						$_POST['blocksy_taxonomy_meta_options'][
							blocksy_post_name()
						]
					)
				),
				true
			);
		}

		update_term_meta(
			$term_id,
			'thumbnail_id',
			sanitize_text_field(wp_unslash(
				isset($values['icon_image']['attachment_id']) ? $values['icon_image']['attachment_id'] : ''
			))
		);

		unset($values['icon_image']);

		update_term_meta(
			$term_id,
			'blocksy_taxonomy_meta_options',
			$values
		);

		do_action('blocksy:dynamic-css:refresh-caches');
	}

	public function term_options($term) {
		$values = isset($term->term_id) ? get_term_meta(
			$term->term_id,
			'blocksy_taxonomy_meta_options'
		) : [[]];

		if (empty($values)) {
			$values = [[]];
		}

		if (! $values[0]) {
			$values[0] = [];
		}

		$maybe_image_id = isset($term->term_id) ? get_term_meta($term->term_id, 'thumbnail_id', true) : '';

		if (! empty($maybe_image_id)) {
			$values[0]['icon_image'] = [
				'attachment_id' => $maybe_image_id,
				'url' => wp_get_attachment_image_url($maybe_image_id, 'full')
			];
		}

		$options = [
			'image' => [
				'label' => __('Featured Image', 'blocksy-companion'),
				'type' => 'ct-image-uploader',
				'value' => '',
				'attr' => [
					'data-type' => 'large'
				],
				'emptyLabel' => __('Select Image', 'blocksy-companion'),
			],

			'icon_image' => [
				'label' => __('Featured Icon/Logo', 'blocksy-companion'),
				'type' => 'ct-image-uploader',
				'value' => '',
				'attr' => [
					'data-type' => 'large'
				],
				'emptyLabel' => __('Select Image', 'blocksy-companion'),
			],
		];

		blocksy_html_tag_e(
			'div',
			[],
			blocksy_html_tag(
				'input',
				[
					'type' => 'hidden',
					'value' => htmlspecialchars(wp_json_encode($values[0])),
					'data-options' => htmlspecialchars(
						wp_json_encode($options)
					),
					'name' => 'blocksy_taxonomy_meta_options[' . blocksy_post_name() . ']',
				]
			)
		);
	}

	public function render_brands_grid($brands) {
		$output = '';

		foreach ($brands as $key => $brand) {

			$label = blocksy_html_tag(
				'a',
				[
					'href' => esc_url(get_term_link($brand)),
				],
				$brand->name
			);

			$term_atts = blocksy_get_taxonomy_options($brand->term_id);

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
					'size' => 'medium',
					'ratio' => 'original',
					'tag_name' => 'a',
					'html_atts' => [
						'href' => get_term_link($brand),
						'aria-label' => $brand->name
					]
				]);
			}

			$output .= $label;
		}

		return $output;
	}

	public function product_single_render($layer) {
		if ($layer['id'] !== 'product_brands') {
			return;
		}

		$brands = get_the_terms(get_the_ID(), 'product_brand');

		if (!$brands || !is_array($brands)) {
			return;
		}

		if (!count($brands)) {
			return;
		}

		$section_title = blocksy_akg('brand_layer_title', $layer, '');

		blocksy_html_tag_e(
			'div',
			[
				'class' => 'ct-product-brands-single',
			],
			(
				! empty($section_title) || is_customize_preview() ?
				blocksy_html_tag(
					'span',
					[
						'class' => 'ct-module-title',
					],
					$section_title
				) : ''
			) .
			blocksy_html_tag(
				'div',
				[
					'class' => 'ct-product-brands',
				],
				$this->render_brands_grid($brands)
			)
		);
	}

	public function product_card_render($layer) {
		if ($layer['id'] !== 'product_brands') {
			return;
		}

		$brands = get_the_terms(get_the_ID(), 'product_brand');

		if (!$brands || !is_array($brands)) {
			return;
		}

		if (!count($brands)) {
			return;
		}

		blocksy_html_tag_e(
			'div',
			[
				'class' => 'ct-product-brands',
			],
			$this->render_brands_grid($brands)
		);
	}
}
