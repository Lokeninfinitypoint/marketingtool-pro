<?php

namespace Blocksy\Extensions\WoocommerceExtra;

trait QueryManager {
	public function is_main_query($query) {
		$is_main_query = (
			$query->is_main_query()
			||
			$query->is_archive
			||
			($query->is_tax && $this->is_taxonomy_page())
		);

		$is_main_query = (
			$query->is_singular
			||
			$query->is_feed
		) ? false : $is_main_query;

		$is_main_query = (
			$query->get('suppress_filters', false)
		) ? false : $is_main_query;

		if ($query->get('blocksy-woocommerce-extra-filters') !== '') {
			$is_main_query = (bool) $query->get(
				'blocksy-woocommerce-extra-filters'
			);
		}

		if ('product_query' !== $query->get('wc_query')) {
			return false;
		}

		return $is_main_query;
	}

	public function is_taxonomy_page() {
		$queried_object = get_queried_object();

		return (
			is_product_category()
			||
			is_product_tag()
			||
			(
				is_tax()
				&&
				array_key_exists(
					$queried_object->taxonomy,
					$this->get_registered_taxonomies([
						'with_attributes' => true
					])
				)
			)
		);
	}

	public static function get_registered_taxonomies($args = []) {
		$args = wp_parse_args($args, [
			'exclude_default' => false,
			'with_attributes' => false
		]);

		$list = [];

		$not_allowed = [
			'product_visibility',
			'product_shipping_class',
			'product_type'
		];

		if ($args['exclude_default']) {
			$not_allowed[] = 'product_cat';
			$not_allowed[] = 'product_tag';
		}

		$registered = get_object_taxonomies('product', 'objects');

		foreach ($registered as $taxonomy) {
			if (in_array($taxonomy->name, $not_allowed, true)) {
				continue;
			}

			if (
				strpos($taxonomy->name, 'pa_') === 0
				&&
				! $args['with_attributes']
			) {
				continue;
			}

			$list[$taxonomy->name] = $taxonomy->label;
		}

		return $list;
	}
}
