<?php

namespace Blocksy\Extensions\PostTypesExtra;

class DynamicData {
	public $custom_fields_manager = null;

	public function __construct() {
		add_action('init', [$this, 'init']);

		add_action('after_setup_theme', function () {
			$this->custom_fields_manager = new CustomFieldsManager();
		});
	}

	public function init() {
		// Options
		add_filter(
			'blocksy:options:page-title:design:before_breadcrumbs',
			function ($opts, $prefix) {
				return $this->add_design_options($opts, $prefix, 'hero_elements');
			},
			10, 2
		);

		add_filter(
			'blocksy:options:posts-listing:design:before_card_background',
			function ($opts, $prefix) {
				return $this->add_design_options($opts, $prefix, 'archive_order');
			},
			10, 2
		);

		add_filter(
			'blocksy:options:meta:meta_default_elements',
			function ($layers, $prefix, $computed_cpt) {
				$opt = $this->complement_layers_option(
					[
						'value' => [],
						'settings' => []
					],
					$computed_cpt,
					[
						'has_icon' => true,
						'has_label_option' => false,
						'has_spacing' => false
					]
				);

				foreach ($opt['value'] as $layer) {
					$layers[] = $layer;
				}

				return $layers;
			},
			10, 3
		);

		add_filter(
			'blocksy:options:meta:meta_elements',
			function ($layers, $prefix, $computed_cpt) {
				foreach ($this->complement_layers_option(
					[
						'value' => [],
						'settings' => []
					],
					$computed_cpt,
					[
						'has_icon' => true,
						'has_label_option' => false,
						'has_spacing' => false
					]
				)['settings'] as $id => $layer) {
					$layers[$id] = $layer;
				}

				return $layers;
			},
			10, 3
		);

		add_filter(
			'blocksy:options:page-title:hero-elements',
			function ($option, $prefix) {
				if (
					$prefix !== 'single_blog_post'
					&&
					$prefix !== 'single_page'
					&&
					$prefix !== 'product'
					&&
					strpos($prefix, '_single') === false
				) {
					return $option;
				}

				return $this->complement_layers_option($option, $prefix);
			},
			10, 2
		);

		add_filter(
			'blocksy:options:posts-listing-archive-order',
			function ($option, $prefix) {
				return $this->complement_layers_option(
					$option,
					$prefix,
					[
						'has_spacing' => true
					]
				);
			},
			10, 2
		);

		add_filter(
			'blocksy:options:posts-listing-related-order',
			function ($option, $prefix) {
				return $this->complement_layers_option(
					$option,
					$prefix,
					[
						'has_spacing' => true
					]
				);
			},
			10, 2
		);

		add_filter(
			'blocksy_woo_card_options_layers:defaults',
			[$this, 'get_product_layer_default'],
			10, 1
		);

		add_filter(
			'blocksy_woo_single_options_layers:defaults',
			[$this, 'get_product_layer_default'],
			10, 1
		);

		add_filter(
			'blocksy_woo_single_right_options_layers:defaults',
			[$this, 'get_product_layer_default'],
			10, 1
		);

		add_filter(
			'blocksy_woo_card_options_layers:extra',
			[$this, 'get_product_layer_extra'],
			10, 1
		);

		add_filter(
			'blocksy_woo_single_options_layers:extra',
			[$this, 'get_product_layer_extra'],
			10, 1
		);

		// Rendering

		add_action(
			'blocksy:woocommerce:product:custom:layer',
			function ($atts) {
				$this->render_dynamic_field_layer($atts);
			}
		);

		add_action(
			'blocksy:woocommerce:product-card:custom:layer',
			function ($atts) {
				$this->render_dynamic_field_layer($atts);
			}
		);

		add_action(
			'blocksy:post-meta:render-meta',
			[$this, 'render_meta_layer'],
			10, 3
		);

		add_action(
			'blocksy:hero:element:render',
			[$this, 'render_dynamic_field_layer']
		);

		add_filter(
			'blocksy:archive:render-card-layer',
			function ($output, $atts) {
				$maybe_layer = $this->render_dynamic_field_layer($atts, false);

				if (! empty($maybe_layer)) {
					return $maybe_layer;
				}

				return $output;
			},
			10, 2
		);

		add_filter(
			'blocksy:related:render-card-layer',
			function ($output, $atts) {
				$maybe_layer = $this->render_dynamic_field_layer($atts, false);

				if (! empty($maybe_layer)) {
					return $maybe_layer;
				}

				return $output;
			},
			10,
			2
		);
	}

	public function get_product_layer_default($layers) {
		$opt = $this->complement_layers_option(
			[
				'value' => [],
				'settings' => []
			],
			'product',
			[
				'has_icon' => false,
				'has_label_option' => true,
				'has_spacing' => true
			]
		);

		foreach ($opt['value'] as $layer) {
			$layers[] = $layer;
		}

		return $layers;
	}

	public function get_product_layer_extra($layers) {
		foreach ($this->complement_layers_option(
			[
				'value' => [],
				'settings' => []
			],
			'product',
			[
				'has_icon' => false,
				'has_label_option' => true,
				'has_spacing' => true
			]
		)['settings'] as $id => $layer) {
			$layers[$id] = $layer;
		}

		return $layers;
	}

	public function complement_layers_option($option, $prefix, $args = []) {
		$args = wp_parse_args($args, [
			'has_icon' => false,
			'has_label_option' => true,
			'has_spacing' => false
		]);

		$post_type = blc_theme_functions()->blocksy_manager()
			->screen
			->compute_post_type_for_prefix($prefix);

		$fields = $this->custom_fields_manager->get_fields(
			[
				'type' => 'post_type',
				'post_type' => $post_type
			],

			[
				'provider' => 'all'
			]
		);

		foreach ($fields as $fields_descriptor) {
			$args['provider'] = $fields_descriptor['provider'];
			$args['provider_label'] = $fields_descriptor['label'];

			$args['fields'] = [];

			foreach ($fields_descriptor['fields'] as $field) {
				$args['fields'][$field->get_id()] = $field->get_label();
			}

			$option = $this->complement_option_for($option, $args);
		}

		return $option;
	}

	public function complement_option_for($option, $args = []) {
		$args = wp_parse_args($args, [
			'provider' => 'acf',
			'provider_label' => 'ACF',

			'fields' => [],

			'has_icon' => false,
			'has_label_option' => true,
			'has_spacing' => false,

			'prefix' => ''
		]);

		$fields = $args['fields'];

		$option['value'][] = [
			'id' => $args['provider'] . '_field',
			'enabled' => false
		];

		$options = [
			'text' => [
				'label' => ' ',
				'type' => 'html',
				'html' => blc_safe_sprintf(
					// translators: %s is the provider label (ACF, Toolset, etc.)
					__(
						'You have no %s fields declared for this custom post type.',
						'blocksy-companion'
					),
					$args['provider_label']
				)
			]
		];

		if (count($fields) > 0) {
			$options = [
				'field' => [
					'label' => __('Field', 'blocksy-companion'),
					'type' => 'ct-select',
					'view' => 'text',
					'value' => array_keys($fields)[0],
					'design' => 'inline',
					'choices' => $fields,
				]
			];

			if ($args['has_label_option']) {
				$options['label'] = [
					'type' => 'ct-switch',
					'label' => __('Label', 'blocksy-companion'),
					'design' => 'inline',
					'value' => 'no'
				];
			}

			if ($args['has_icon']) {
				$options[blocksy_rand_md5()] = [
					'type' => 'ct-condition',
					'condition' => [ 'meta_type' => 'icons' ],
					'values_source' => 'parent',
					'options' => [
						'icon' => [
							'type' => 'icon-picker',
							'label' => __('Icon', 'blocksy-companion'),
							'design' => 'inline',
							'value' => [
								'icon' => 'blc blc-heart'
							]
						]
					],
				];
			}

			$options['value_before'] = [
				'type' => 'text',
				'label' => __('Before', 'blocksy-companion'),
				'design' => 'inline',
				'value' => '',
				'sync' => [
					'prefix' => $args['prefix'],
					'id' => $args['prefix'] . '_dynamic_data_sync',
				]
			];

			$options['value_after'] = [
				'type' => 'text',
				'label' => __('After', 'blocksy-companion'),
				'design' => 'inline',
				'value' => '',

				'sync' => [
					'prefix' => $args['prefix'],
					'id' => $args['prefix'] . '_dynamic_data_sync',
				]
			];

			$options['value_fallback'] = [
				'type' => 'text',
				'label' => __('Fallback', 'blocksy-companion'),
				'design' => 'inline',
				'value' => '',
				'sync' => [
					'prefix' => $args['prefix'],
					'id' => $args['prefix'] . '_dynamic_data_sync',
				]
			];

			if ($args['has_spacing']) {
				$options['spacing'] = [
					'label' => __( 'Bottom Spacing', 'blocksy-companion' ),
					'type' => 'ct-slider',
					'min' => 0,
					'max' => 100,
					'value' => $args['prefix'] === 'product' ? 10 : 20,
					'responsive' => true,

					'sync' => [
						'id' => 'woo_card_layout_skip'
					]
				];
			}
		}

		$option['settings'][$args['provider'] . '_field'] = [
			'label' => blc_safe_sprintf(
				// translators: %s is the provider label (ACF, Toolset, etc.)
				__('%s Field', 'blocksy-companion'),
				$args['provider_label']
			) . ' INDEX',
			'options' => $options,
			'clone' => 15
		];

		return $option;
	}

	public function render_dynamic_field_layer($atts, $echo = true) {
		$field = $this->get_field_to_render($atts);

		if (! $field) {
			return '';
		}

		$output = $field['value'];

		$value_fallback = blocksy_akg('value_fallback', $atts, '');

		$has_fallback = false;

		if (empty($output) && ! empty($value_fallback)) {
			$has_fallback = true;
			$output = do_shortcode($value_fallback);
		}

		if (empty($output)) {
			return '';
		}

		$value_after = blocksy_akg('value_after', $atts, '');
		$value_before = blocksy_akg('value_before', $atts, '');

		if (! empty($value_after) && ! $has_fallback) {
			$output .= $value_after;
		}

		if (! empty($value_before) && ! $has_fallback) {
			$output = $value_before . $output;
		}

		if (blocksy_akg('label', $atts, 'no') === 'yes') {
			$output = '<span>' . $field['label'] . '</span>' . $output;
		}

		$attr = [
			'class' => 'ct-dynamic-data-layer'
		];

		$attr['data-field'] = $field['name'];

		if (isset($atts['__id'])) {
			$attr['data-field'] .= ':' . substr($atts['__id'], 0, 6);
		}

		$layer = blocksy_html_tag('div', $attr, $output);

		if ($echo) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $layer;
		}

		return $layer;
	}

	public function render_meta_layer($id, $meta, $args) {
		$field = $this->get_field_to_render($meta);

		if (! $field) {
			return;
		}

		$value_fallback = blocksy_akg('value_fallback', $meta, '');

		$value = $field['value'];

		$has_fallback = false;

		if (empty($value) && ! empty($value_fallback)) {
			$has_fallback = true;
			$value = do_shortcode($value_fallback);
		}

		if (! is_string($value)) {
			return;
		}

		if (empty(trim($value))) {
			return;
		}

		$value_after = blocksy_akg('value_after', $meta, '');
		$value_before = blocksy_akg('value_before', $meta, '');

		if (! empty($value_after) && ! $has_fallback) {
			$value .= $value_after;
		}

		if (! empty($value_before) && ! $has_fallback) {
			$value = $value_before . $value;
		}

		if ($args['meta_type'] === 'label') {
			$value = '<span>' . $field['label'] . '</span>' . $value;
		}

		if (
			$args['meta_type'] === 'icons'
			||
			$args['force_icons']
		) {
			$value = blc_get_icon([
				'icon_descriptor' => blocksy_akg('icon', $meta, [
					'icon' => 'blc blc-heart'
				]),
				'icon_container' => false
			]) . $value;
		}

		$value = apply_filters(
			'blocksy:pro:post-types-extra:post-meta:rendered-value',
			$value,
			$field,
			$meta
		);

		blocksy_html_tag_e(
			'li',
			[
				'class' => 'meta-custom-field',
				'data-field' => $field['name']
			],
			$value
		);
	}

	private function get_field_to_render($atts) {
		if (
			! isset($atts['id'])
			||
			! str_ends_with($atts['id'], '_field')
		) {
			return null;
		}

		$provider = str_replace('_field', '', $atts['id']);

		// Just for default field
		$maybe_fields_descriptor = $this->custom_fields_manager->get_fields(
			[
				'type' => 'post_type',

				// It's safe to get current global post type because we are in
				// the theme's main layout context. There cannot be any other
				// post type context here.
				'post_type' => get_post_type()
			],
			[
				'provider' => $provider
			]
		);

		if (! $maybe_fields_descriptor) {
			return null;
		}

		$first_field = $maybe_fields_descriptor['fields'][0];

		$field_id = blocksy_akg('field', $atts, $first_field->get_id());

		return $this->custom_fields_manager->render_field($field_id, [
			'provider' => $provider
		]);
	}

	public function add_design_options($opts, $prefix, $key) {
		$options = blocksy_get_options(
			dirname(__FILE__) . '/options.php',
			[
				'prefix' => $prefix,
				'key' => $key,
			],
			false
		);

		return array_merge($opts, $options);
	}
}
