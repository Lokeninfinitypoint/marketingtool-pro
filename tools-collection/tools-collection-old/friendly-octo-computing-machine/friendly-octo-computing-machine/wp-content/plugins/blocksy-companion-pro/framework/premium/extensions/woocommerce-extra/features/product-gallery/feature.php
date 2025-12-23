<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class ProductGallery {
	public function __construct() {
		add_filter(
			'blocksy:options:conditions:overrides',
			function ($overrides) {
				unset($overrides['product_view_type']);
				return $overrides;
			}
		);

		add_action(
			'wp_enqueue_scripts',
			function () {
				if (! function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				if (
					is_admin()
					||
					! function_exists('is_product')
					||
					! blc_theme_functions()->blocksy_manager()
					||
					! blc_theme_functions()->blocksy_manager()->screen->is_product()
				) {
					return;
				}

				wp_enqueue_style(
					'blocksy-ext-woocommerce-extra-product-gallery-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/gallery-types.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);
			},
			50
		);

		add_filter(
			'blocksy:woocommerce:single-product:post-class',
			function($classes) {
				if (
					! blc_theme_functions()->blocksy_manager()
					||
					! blc_theme_functions()->blocksy_manager()->screen->is_product()
				) {
					return $classes;
				}

				if (function_exists('blocksy_woocommerce_has_flexy_view')) {
					if (! blocksy_woocommerce_has_flexy_view()) {
						return $classes;
					}
				}

				if (! in_array('ct-default-gallery', $classes)) {
					return $classes;
				}

				global $blocksy_is_quick_view;

				$classes = array_diff($classes, [
					'ct-default-gallery',
					// 'thumbs-left',
					// 'thumbs-bottom'
				]);

				if (! $blocksy_is_quick_view) {
					$product_view_type = blc_theme_functions()->blocksy_get_theme_mod(
						'product_view_type',
						'default-gallery'
					);

					if ($product_view_type !== 'default-gallery') {
						$classes = array_diff($classes, [
							'thumbs-left',
							'thumbs-bottom'
						]);
					}

					$classes[] = 'ct-' . $product_view_type;
				}

				return $classes;
			},
			1500
		);


		add_filter(
			'blocksy:woocommerce:single-product:gallery:columns',
			function ($columns) {
				$product_view_type = blc_theme_functions()->blocksy_get_theme_mod(
					'product_view_type',
					'default-gallery'
				);

				if ($product_view_type === 'columns-top-gallery') {
					return blc_theme_functions()->blocksy_get_theme_mod('product_view_columns_top', 3);
				}

				return $columns;
			}
		);

		add_filter(
			'blocksy:options:single_product:product-general-tab:start',
			function ($options) {
				$options['product_view_type'] = [
					'label' => false,
					'type' => 'ct-image-picker',
					'value' => 'default-gallery',
					// 'divider' => 'bottom',
					'choices' => [
						'default-gallery' => [
							'src' => blocksy_image_picker_url('woo-gallery-type-1.svg'),
							'title' => __('Type 1', 'blocksy-companion'),
						],

						'top-gallery' => [
							'src' => blocksy_image_picker_url('woo-gallery-type-2.svg'),
							'title' => __('Type 2', 'blocksy-companion'),
						],

						'stacked-gallery' => [
							'src' => blocksy_image_picker_url('woo-gallery-type-3.svg'),
							'title' => __('Type 3', 'blocksy-companion'),
						],

						'columns-top-gallery' => [
							'src' => blocksy_image_picker_url('woo-gallery-type-4.svg'),
							'title' => __('Type 4', 'blocksy-companion'),
						],
					],

					'sync' => blocksy_sync_whole_page([
						'loader_selector' => '.woocommerce-product-gallery',
						'prefix' => 'product'
					])
				];

				return $options;
			}
		);

		add_filter(
			'blocksy:options:single_product:product-general-tab:sticky:after',
			function($options) {
				$options[blocksy_rand_md5()] = [
					'type' => 'ct-condition',
					'condition' => [
						'product_view_type' => '!stacked-gallery'
					],
					'options' => [
						'has_product_autoplay_gallery' => [
							'label' => __('Autoplay Gallery', 'blocksy-companion'),
							'type' => 'ct-switch',
							'value' => 'no'
						],

						blocksy_rand_md5() => [
							'type' => 'ct-condition',
							'condition' => [
								'has_product_autoplay_gallery' => 'yes'
							],
							'options' => [
								'has_product_autoplay_gallery_delay' => [
									'label' => __( 'Delay (in seconds)', 'blocksy-companion' ),
									'type' => 'ct-number',
									'design' => 'inline',
									'min' => 1,
									'max' => 50,
									'value' => 5,
									'responsive' => false,
								],
							]
						]
					],
				];

				return $options;
			}
		);

		add_filter(
			'blocksy:options:single_product:gallery-options',
			function ($options) {
				$options[blocksy_rand_md5()] = [
					'type' => 'ct-condition',
					'condition' => ['product_view_type' => 'stacked-gallery'],
					'options' => [

						'product_view_stacked_columns' => [
							'label' => __('Columns', 'blocksy-companion'),
							'type' => 'ct-number',
							'value' => 2,
							'min' => 1,
							'max' => 4,
							'divider' => 'top:full',
							'responsive' => true,
							'sync' => 'live',
							'attr' => ['data-position' => 'right']
						],
					]
				];

				$options[blocksy_rand_md5()] = [
					'type' => 'ct-condition',
					'condition' => ['product_view_type' => 'columns-top-gallery'],
					'options' => [

						'product_view_columns_top' => [
							'label' => __('Columns', 'blocksy-companion'),
							'type' => 'ct-number',
							'value' => 3,
							'min' => 1,
							'max' => 6,
							'divider' => 'top:full',
							'responsive' => true,
							'sync' => blocksy_sync_whole_page([
								'loader_selector' => '.woocommerce-product-gallery',
								'prefix' => 'product'
							]),
							'attr' => ['data-position' => 'right']
						],
					]
				];

				$options[blocksy_rand_md5()] = [
					'type' => 'ct-condition',
					'condition' => ['product_view_type' => 'columns-top-gallery|stacked-gallery'],
					'options' => [

						'product_thumbs_spacing' => [
							'label' => __( 'Columns Spacing', 'blocksy-companion' ),
							'type' => 'ct-slider',
							'value' => '15px',
							'units' => blocksy_units_config([
								[ 'unit' => 'px', 'min' => 0, 'max' => 100 ],
							]),
							'responsive' => true,
							'divider' => 'top',
							'setting' => [ 'transport' => 'postMessage' ],
						],

					],
				];

				return $options;
			}
		);

		add_filter(
			'blocksy:options:single_product:gallery:arrows',
			function ($options) {

				$options[blocksy_rand_md5()] = [
					'type' => 'ct-condition',
					'condition' => ['product_view_type' => '!stacked-gallery'],
					'options' => [

						'has_product_slider_arrows' => [
							'label' => __('Arrows Visibility', 'blocksy-companion'),
							'type' => 'ct-visibility',
							'design' => 'block',
							'divider' => 'top',
							'value' => blocksy_default_responsive_value([
								'desktop' => true,
								'tablet' => true,
								'mobile' => false,
							]),
							'allow_empty' => true,
							'choices' => blocksy_ordered_keys([
								'desktop' => __('Desktop', 'blocksy-companion'),
								'tablet' => __('Tablet', 'blocksy-companion'),
								'mobile' => __('Mobile', 'blocksy-companion'),
							]),
						],

					],
				];

				return $options;
			}
		);

		add_filter(
			'blocksy:options:single_product:gallery-thumbs:arrows',
			function ($options) {
				$options['has_product_pills_arrows'] = [
					'label' => __('Arrows Visibility', 'blocksy-companion'),
					'type' => 'ct-visibility',
					'design' => 'block',
					'divider' => 'top',
					'value' => blocksy_default_responsive_value([
						'desktop' => true,
						'tablet' => true,
						'mobile' => false,
					]),
					'allow_empty' => true,
					'choices' => blocksy_ordered_keys([
						'desktop' => __('Desktop', 'blocksy-companion'),
						'tablet' => __('Tablet', 'blocksy-companion'),
						'mobile' => __('Mobile', 'blocksy-companion'),
					]),
				];

				return $options;
			}
		);

		add_filter('blocksy:woocommerce:single_product:flexy-args', function ($args) {
			global $blocksy_is_quick_view;

			$args['arrows_class'] = blocksy_visibility_classes(
				blc_theme_functions()->blocksy_get_theme_mod('has_product_slider_arrows', [
					'desktop' => true,
					'tablet' => true,
					'mobile' => false
				])
			);

			$args['pills_arrows_class'] = blocksy_visibility_classes(
				blc_theme_functions()->blocksy_get_theme_mod('has_product_pills_arrows', [
					'desktop' => true,
					'tablet' => true,
					'mobile' => false
				])
			);

			$product_view_type = blc_theme_functions()->blocksy_get_theme_mod(
				'product_view_type',
				'default-gallery'
			);

			$has_product_autoplay_gallery = 0;

			if (blc_theme_functions()->blocksy_get_theme_mod('has_product_autoplay_gallery','no') === 'yes') {
				$has_product_autoplay_gallery = blc_theme_functions()->blocksy_get_theme_mod(
					'has_product_autoplay_gallery_delay',
					5
				);

				$args['autoplay'] = $has_product_autoplay_gallery;
			}

			if (
				! $blocksy_is_quick_view
				&&
				(
					$product_view_type === 'default-gallery'
					||
					$product_view_type === 'top-gallery'
				)
				&&
				apply_filters(
					'blocksy:woocommerce:gallery-pills-slider:enabled',
					true
				)
			) {
				$args['pills_container_attr'] = [
					'data-flexy' => 'no'
				];

				if (count($args['images']) <= 4) {
					$args['pills_container_attr']['data-flexy'] .= ':paused';
				}

				$args['pills_have_arrows'] = true;
			}

			if (
				$blocksy_is_quick_view
				||
				$product_view_type === 'columns-top-gallery'
			) {
				$args['pills_container_attr'] = [
					'data-flexy' => 'no'
				];

				if (count($args['images']) <= 4) {
					$args['pills_container_attr']['data-flexy'] .= ':paused';
				}


			}

			if (
				! $blocksy_is_quick_view
				&&
				$product_view_type === 'columns-top-gallery'
			) {
				unset($args['pills_images']);

				$maybe_zoom_icon = '';

				if (blc_theme_functions()->blocksy_get_theme_mod('has_product_single_lightbox', 'no') === 'yes') {
					$maybe_zoom_icon = '<span class="woocommerce-product-gallery__trigger">🔍</span>';
				}

				$args['slide_inner_content'] = $maybe_zoom_icon;

				$columns = blocksy_expand_responsive_value(
					blc_theme_functions()->blocksy_get_theme_mod('product_view_columns_top', 3)
				);

				$args['pills_class'] = blocksy_visibility_classes([
					'desktop' => count(
						$args['images']
					) > $columns['desktop'],
					'tablet' => count(
						$args['images']
					) > $columns['tablet'],
					'mobile' => count(
						$args['images']
					) > $columns['mobile']
				]);
			}

			return $args;
		});

		add_filter('blocksy:woocommerce:product-review:has-gallery-zoom-trigger', function ($value) {
			$product_view_type = blc_theme_functions()->blocksy_get_theme_mod(
				'product_view_type',
				'default-gallery'
			);

			if ($product_view_type === 'columns-top-gallery') {
				return false;
			}

			return $value;
		});

		add_filter(
			'blocksy:woocommerce:product-view:content',
			function ($content, $product, $gallery_images, $is_single) {
				$product_view_type = blc_theme_functions()->blocksy_get_theme_mod('product_view_type', 'default-gallery');

				if (
					$product_view_type === 'default-gallery'
					||
					$product_view_type === 'top-gallery'
					||
					$product_view_type === 'columns-top-gallery'
				) {
					return null;
				}

				if (!$product) {
					global $product;
				}

				$content = '';

				$single_ratio = blc_theme_functions()->blocksy_get_theme_mod('product_gallery_ratio', '3/4');
				$default_ratio = apply_filters(
					'blocksy:woocommerce:default_product_ratio',
					'3/4'
				);

				$maybe_zoom_icon = '';

				if (blc_theme_functions()->blocksy_get_theme_mod('has_product_single_lightbox', 'no') === 'yes') {
					$maybe_zoom_icon = '<span class="woocommerce-product-gallery__trigger">🔍</span>';
				}

				foreach ($gallery_images as $image) {
					$attachment_id = $image;

					$image_href = wp_get_attachment_image_src(
						$attachment_id,
						'full'
					);

					$width = null;
					$height = null;

					if ($image_href) {
						$width = $image_href[1];
						$height = $image_href[2];

						$image_href = $image_href[0];
					}

					$content .= blocksy_media([
							'display_video' => true,
							'no_image_type' => 'woo',
							'attachment_id' => $image,
							'post_id' => $product->get_id(),
							'size' => 'woocommerce_single',
							'ratio' => $is_single ? $single_ratio : $default_ratio,
							'tag_name' => 'figure',
							'size' => 'woocommerce_single',
							'html_atts' => array_merge([
								'data-src' => $image_href
							], $width ? [
								'data-width' => $width,
								'data-height' => $height
							] : []),
							'inner_content' => $maybe_zoom_icon,
							'lazyload' => blc_theme_functions()->blocksy_get_theme_mod(
								'has_lazy_load_single_product_image',
								'yes'
							) === 'yes'
						]);
				}

				return blocksy_html_tag(
					'div',
					[
						'class' => 'ct-stacked-gallery-container',
					],
					$content
				);
			},
			10,
			4
		);

		add_action('woocommerce_single_product_summary', function () {
			$product_view_type = blc_theme_functions()->blocksy_get_theme_mod('product_view_type', 'default-gallery');

			if (
				$product_view_type !== 'top-gallery'
				&&
				$product_view_type !== 'columns-top-gallery'
			) {
				return;
			}

			echo '<section class="entry-summary-items">';
		}, 1);

		add_action('woocommerce_single_product_summary', function () {
			$product_view_type = blc_theme_functions()->blocksy_get_theme_mod('product_view_type', 'default-gallery');

			if (
				$product_view_type !== 'top-gallery'
				&&
				$product_view_type !== 'columns-top-gallery'
			) {
				return;
			}

			$woo_single_split_layout_defults = [
				'left' => [],
				'right' => []
			];

			if (function_exists('blocksy_get_woo_single_layout_defaults')) {
				$woo_single_split_layout_defults = [
					'left' => blocksy_get_woo_single_layout_defaults('left'),
					'right' => blocksy_get_woo_single_layout_defaults('right')
				];
			}

			$woo_single_split_layout = blc_theme_functions()->blocksy_get_theme_mod(
				'woo_single_split_layout',
				$woo_single_split_layout_defults
			);

			$render_ids = array_column(array_merge(
				$woo_single_split_layout['left'],
				$woo_single_split_layout['right']
			), 'id');

			foreach ($woo_single_split_layout_defults['left'] as $item) {
				if (! in_array($item['id'], $render_ids)) {
					$woo_single_split_layout['left'][] = $item;
				}
			}

			if (blc_theme_functions()->blocksy_manager()) {
				blc_theme_functions()->blocksy_manager()->woocommerce->single->render_layout([
					'layout' => $woo_single_split_layout['left']
				]);
			}

			echo '</section>';
			echo '<section class="entry-summary-items">';
		}, 1);

		add_action('woocommerce_single_product_summary', function () {
			$product_view_type = blc_theme_functions()->blocksy_get_theme_mod('product_view_type', 'default-gallery');

			if (
				$product_view_type !== 'top-gallery'
				&&
				$product_view_type !== 'columns-top-gallery'
			) {
				return;
			}

			$woo_single_split_layout_defults = [
				'left' => [],
				'right' => []
			];

			if (function_exists('blocksy_get_woo_single_layout_defaults')) {
				$woo_single_split_layout_defults = [
					'left' => blocksy_get_woo_single_layout_defaults('left'),
					'right' => blocksy_get_woo_single_layout_defaults('right')
				];
			}

			$woo_single_split_layout = blc_theme_functions()->blocksy_get_theme_mod(
				'woo_single_split_layout',
				$woo_single_split_layout_defults
			);

			$render_ids = array_column(array_merge(
				$woo_single_split_layout['left'],
				$woo_single_split_layout['right']
			), 'id');

			foreach ($woo_single_split_layout_defults['right'] as $item) {
				if (! in_array($item['id'], $render_ids)) {
					$woo_single_split_layout['right'][] = $item;
				}
			}

			if (blc_theme_functions()->blocksy_manager()) {
				blc_theme_functions()->blocksy_manager()->woocommerce->single->render_layout([
					'layout' => $woo_single_split_layout['right']
				]);
			}

			echo '</section>';
		}, 9999999);

		add_filter(
			'blocksy:woocommerce:product-single:view-type',
			function ($view_type) {
				return blc_theme_functions()->blocksy_get_theme_mod(
					'product_view_type',
					'default-gallery'
				);
			}
		);
	}
}
