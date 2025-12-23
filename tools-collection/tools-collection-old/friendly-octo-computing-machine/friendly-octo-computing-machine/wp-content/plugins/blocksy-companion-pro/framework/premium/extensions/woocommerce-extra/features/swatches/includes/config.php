<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class SwatchesConfig {
	public function get_attribute_type_term_by_id($id) {
		$term_atts = get_term_meta($id, 'blocksy_taxonomy_meta_options');

		if (empty($term_atts)) {
			$term_atts = [[]];
		}

		$term_atts = $term_atts[0];

		return blocksy_akg('swatch_type', $term_atts, 'button');
	}

	public function get_attributes_meta($taxonomy) {
		$maybe_product_id = get_the_ID();

		// TODO: Migrate _ct-woo-attributes-list to blocksy_post_meta_options
		if ($maybe_product_id) {
			$meta = get_post_meta(
				$maybe_product_id,
				'_ct-woo-attributes-list',
				true
			);

			if (
				$meta
				&&
				json_decode($meta, true)
			) {
				$meta = json_decode($meta, true);

				if (isset($meta[$taxonomy])) {
					return $meta[$taxonomy];
				} else if (isset($meta[wc_attribute_taxonomy_name($taxonomy)])) {
					return $meta[wc_attribute_taxonomy_name($taxonomy)];
				}
			}
		}

		return [];
	}

	public function get_parents($taxonomy) {
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		return array_filter(
			$attribute_taxonomies,
			function ($item) use ($taxonomy) {
				return $item->attribute_name == rtrim(preg_replace('/pa_/', '', $taxonomy));
			}
		);
	}

	public function get_attribute_type($taxonomy, $args = []) {
		$args = wp_parse_args($args, [
			'product' => null
		]);

		global $blocksy_is_quick_view;

		// Automatically compute meta field of the current product, if
		// we are on a product page.
		if (
			(
				(
					blc_theme_functions()->blocksy_manager()
					&&
					blc_theme_functions()->blocksy_manager()->screen->is_product()
				)
				||
				$blocksy_is_quick_view
			)
			&&
			! $args['product']
		) {
			$args['product'] = wc_get_product();
		}

		$type = 'button';

		$parents = $this->get_parents($taxonomy);

		if (! empty($parents)) {
			$parent_taxonomy = array_values($this->get_parents($taxonomy))[0];

			if (isset($parent_taxonomy->attribute_id)) {
				$attribute = wc_get_attribute($parent_taxonomy->attribute_id);

				$type = $attribute->type;
			}
		}

		if ($args['product']) {
			$meta = $this->get_attributes_meta($taxonomy);

			$swatch_type = blocksy_akg('swatch_type', $meta, 'inherit');

			if ($swatch_type !== 'inherit') {
				$type = $swatch_type;
			}
		}

		return $type;
	}

	public function get_swatch_element_descriptor($term, $args = []) {
		$args = wp_parse_args($args, [
			'read_atts' => true
		]);

		$element = [
			'element_slug' => $term->slug,
			'element_label' => $term->name,

			'is_selected' => false,
			'is_out_of_stock' => false,
			'is_invalid' => false,
			'is_limited' => false,

			'element_atts' => [],
			'element_type' => ''
		];

		if ($args['read_atts']) {
			$element['element_type'] = $this->get_attribute_type($term->taxonomy);
			$element['element_atts'] = blocksy_get_taxonomy_options(
				$term->term_id
			);

			if (! isset($element['element_atts']['short_name'])) {
				$element['element_atts']['short_name'] = '';
			}
		}

		return $element;
	}
}
