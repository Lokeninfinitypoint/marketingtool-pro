<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class SwatchesPersistAttributes {
	private $conf = null;
	private $options = [
		'tooltip_options' => [],
		'mixed_options' => [],
		'color_options' => [],
		'image_options' => [],
		'button_options' => [],
		'inherit_options' => []
	];

	public function __construct() {
		$this->conf = new SwatchesConfig();

		add_action('admin_init', function () {
			$this->attributes_value_meta_init();

			$this->options = blc_theme_functions()->blocksy_get_variables_from_file(
				dirname(dirname(__FILE__)) . '/woo-tab-options.php',
				$this->options
			);
		});
	}

	private function attributes_value_meta_init() {
		add_action(
			'edited_term',
			[$this, 'persist_attributes_values_option'],
			10, 3
		);

		add_action(
			'create_term',
			[$this, 'persist_attributes_values_option'],
			10, 3
		);

		if (! function_exists('wc_get_attribute_taxonomies')) {
			return;
		}

		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if (! $attribute_taxonomies) {
			return;
		}

		foreach ($attribute_taxonomies as $tax) {
			add_action(
				'pa_' . $tax->attribute_name . '_edit_form_fields',
				[$this, 'output_attributes_values_options'],
				10, 2
			);

			add_action(
				'pa_' . $tax->attribute_name . '_add_form_fields',
				[$this, 'output_add_attributes_values_options'],
				10, 1
			);

			$attribute = wc_get_attribute($tax->attribute_id);
			$type = $attribute->type;

			if ($type === 'color' || $type ===  'image' || $type === 'mixed') {
				add_filter(
					'manage_edit-pa_' . $tax->attribute_name . '_columns',
					function ($columns) {
						$new_columns = [];

						if (isset($columns['cb'])) {
							$new_columns['cb'] = $columns['cb'];
							unset($columns['cb']);
						}

						$new_columns['blc_swatch_preview'] = '';
						$columns = array_merge($new_columns, $columns);

						return $columns;
					}
				);

				add_filter(
					'manage_pa_' . $tax->attribute_name . '_custom_column',
					function ($columns, $column, $id) {
						if ($column !== 'blc_swatch_preview') {
							return $columns;
						}

						$conf = new SwatchesConfig();

						$swatch_term = new SwatchElementRender(
							$conf->get_swatch_element_descriptor(get_term($id))
						);

						$iamge = '';

						$maybe_image = $swatch_term->get_output(true);

						if ($maybe_image) {
							$iamge = $maybe_image;
						}

						return $columns . $iamge;
					},
					10, 3
				);
			}
		}
	}

	public function persist_attributes_values_option($term_id, $tt_id, $taxonomy) {
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
			'blocksy_taxonomy_meta_options',
			$values
		);

		do_action('blocksy:dynamic-css:refresh-caches');
	}

	public function output_add_attributes_values_options($taxonomy) {
		$selected_type = $this->conf->get_attribute_type($taxonomy);

		$values = [[]];
		$options = [];

		if ($selected_type === 'mixed') {
			$options = $this->options['mixed_options'];
		}

		if ($selected_type === 'image') {
			$options = $this->options['image_options'];
		}

		if ($selected_type === 'color') {
			$options = $this->options['color_options'];
		}

		if ($selected_type === 'button') {
			$options = $this->options['button_options'];
		}

		$options = array_merge($options, $this->options['tooltip_options']);

		if (empty($options)) {
			return;
		}

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
					'name' => 'blocksy_taxonomy_meta_options[' . blocksy_post_name() . ']'
				]
			)
		);
	}

	public function output_attributes_values_options($term, $taxonomy) {
		$selected_type = $this->conf->get_attribute_type($term->taxonomy);

		$values = get_term_meta(
			$term->term_id,
			'blocksy_taxonomy_meta_options'
		);

		if (empty($values)) {
			$values = [[]];
		}

		if (! $values[0]) {
			$values[0] = [];
		}

		$options = [];

		if ($selected_type === 'mixed') {
			$options = $this->options['mixed_options'];
		}

		if ($selected_type === 'image') {
			$options = $this->options['image_options'];
		}

		if ($selected_type === 'color') {
			$options = $this->options['color_options'];
		}

		if ($selected_type === 'button') {
			$options = $this->options['button_options'];
		}

		$options = array_merge($options, $this->options['tooltip_options']);

		if (empty($options)) {
			return;
		}

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
					'name' => 'blocksy_taxonomy_meta_options[' . blocksy_post_name() . ']'
				]
			)
		);
	}
}

