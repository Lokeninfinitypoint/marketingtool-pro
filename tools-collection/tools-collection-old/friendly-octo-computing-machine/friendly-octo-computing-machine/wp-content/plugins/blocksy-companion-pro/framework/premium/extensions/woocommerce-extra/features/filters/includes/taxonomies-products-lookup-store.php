<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class FiltersTaxonomiesProductsLookupStore {
	private $lookup_table_name = '';

	private $hierarchy_children_first_cache = [];

	public function __construct($table_name) {
		$this->lookup_table_name = $table_name;

		new TaxonomiesProductsLookupHooks($this);
	}

	public function create_data_for_product($product) {
		if (! is_a($product, \WC_Product::class)) {
			$product = WC()->call_function('wc_get_product', $product);
		}

		if (! $product) {
			return;
		}

		$this->delete_data_for([
			'column' => 'product_id',
			'value' => $product->get_id()
		]);

		$this->create_data_for($product);
	}

	public function delete_data_for($args = []) {
		$args = wp_parse_args($args, [
			'column' => 'product_id',
			'value' => 0
		]);

		global $wpdb;

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$sql = $wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			'DELETE FROM ' . $this->lookup_table_name . ' WHERE %i = %d',
			$args['column'],
			$args['value']
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
		$wpdb->query($sql);
	}

	private function create_data_for(\WC_Product $product) {
		if ($this->is_variation($product)) {
			return;
		}

		if ($product->get_catalog_visibility() === 'hidden') {
			return;
		}

		$this->create_data_for_simple_product($product);
	}

	private function is_variation(\WC_Product $product) {
		return is_a($product, \WC_Product_Variation::class);
	}

	private function create_data_for_simple_product(\WC_Product $product) {
		$all_taxonomies = blc_get_ext('woocommerce-extra')
			->utils
			->get_product_taxonomies();

		global $sitepress;

		$filter_name = [
			[
				'name' => 'get_terms_args',
				'callback' => [$sitepress, 'get_terms_args_filter'],
				'priority' => 10,
				'args' => 2,
				'should_add_back' => false
			],

			[
				'name' => 'get_term',
				'callback' => [$sitepress, 'get_term_adjust_id'],
				'priority' => 1,
				'args' => 1,
				'should_add_back' => false
			],

			[
				'name' => 'terms_clauses',
				'callback' => [$sitepress, 'terms_clauses'],
				'priority' => 10,
				'args' => 3,
				'should_add_back' => false
			]
		];

		if ($sitepress) {
			foreach ($filter_name as $filter_index => $filter) {
				if (! has_filter($filter['name'], $filter['callback'])) {
					continue;
				}

				$filter_name[$filter_index]['should_add_back'] = true;

				remove_filter(
					$filter['name'],
					$filter['callback'],
					$filter['priority'],
					$filter['args']
				);
			}
		}

		foreach ($all_taxonomies as $taxonomy) {
			$term_ids = wp_get_object_terms(
				$product->get_id(),
				$taxonomy,
				['fields' => 'ids']
			);

			if (empty($term_ids)) {
				continue;
			}

			if (is_taxonomy_hierarchical($taxonomy)) {
				$full_term_ids = [];

				foreach ($term_ids as $term_id) {
					$full_term_ids[] = $term_id;

					$full_term_ids = array_merge(
						$full_term_ids,
						$this->get_parents_for_term($taxonomy, $term_id)
					);
				}

				$term_ids = array_unique($full_term_ids);
			}

			$term_ids = $this->maybe_expand_term_ids_for_wpml_fallback_translations([
				'product_id' => $product->get_id(),
				'taxonomy' => $taxonomy,
				'term_ids' => $term_ids
			]);

			foreach ($term_ids as $term_id) {
				$this->insert_lookup_table_data(
					$product->get_id(),
					$taxonomy,
					$term_id
				);
			}
		}

		if ($sitepress) {
			foreach ($filter_name as $filter) {
				if ($filter['should_add_back']) {
					add_filter(
						$filter['name'],
						$filter['callback'],
						$filter['priority'],
						$filter['args']
					);
				}
			}
		}
	}

	private function insert_lookup_table_data(int $product_id, string $taxonomy, int $term_id) {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				'INSERT INTO ' . $this->lookup_table_name . ' (
					product_id,
					taxonomy,
					term_id)
					VALUES
					(%d, %s, %d)',
				$product_id,
				$taxonomy,
				$term_id
			)
		);
	}

	private function get_parents_for_term($taxonomy, $term_id) {
		$parents = [];

		while (isset($this->get_hierarchy_children_first($taxonomy)[$term_id])) {
			$term_id = $this->get_hierarchy_children_first($taxonomy)[$term_id];
			$parents[] = $term_id;
		}

		return $parents;
	}

	private function get_hierarchy_children_first($taxonomy) {
		if (isset($this->hierarchy_children_first_cache[$taxonomy])) {
			return $this->hierarchy_children_first_cache[$taxonomy];
		}

		$term_hierarchy = _get_term_hierarchy($taxonomy);

		$hierarchy_children_first = [];

		foreach ($term_hierarchy as $parent_id => $children) {
			foreach ($children as $child) {
				$hierarchy_children_first[$child] = $parent_id;
			}
		}

		$this->hierarchy_children_first_cache[
			$taxonomy
		] = $hierarchy_children_first;

		return $hierarchy_children_first;
	}

	private function maybe_expand_term_ids_for_wpml_fallback_translations($args = []) {
		$args = wp_parse_args($args, [
			'product_id' => 0,
			'taxonomy' => '',
			'term_ids' => []
		]);

		global $sitepress;

		// Do nothing if WPML is not active
		if (! $sitepress) {
			return $args['term_ids'];
		}

		// We should not do anything if the fallback translation is not
		// enabled for the product post type.
		if (! $sitepress->is_display_as_translated_post_type('product')) {
			return $args['term_ids'];
		}

		$default_language = $sitepress->get_default_language();

		$product_language = $sitepress->get_language_for_element(
			$args['product_id'],
			'post_product'
		);

		// We are only interested in the product with the default language.
		if ($product_language !== $default_language) {
			return $args['term_ids'];
		}

		$product_trid = $sitepress->get_element_trid(
			$args['product_id'],
			'post_product'
		);

		$translations = $sitepress->get_element_translations(
			$product_trid,
			'post_product'
		);

		$languages = apply_filters(
			'wpml_active_languages',
			null,
			"skip_missing=0&orderby=custom&order=asc"
		);

		$non_default_languages = array_filter(
			array_keys($languages),
			function ($language) use ($default_language) {
				return $language !== $default_language;
			}
		);

		$translated_term_ids = [];

		foreach ($non_default_languages as $language) {
			// If post is translated in that language, we should not do anything.
			if (isset($translations[$language])) {
				continue;
			}

			foreach ($args['term_ids'] as $term_id) {
				$term_trid = $sitepress->get_element_trid(
					$term_id,
					'tax_' . $args['taxonomy']
				);

				$term_translations = $sitepress->get_element_translations(
					$term_trid,
					'tax_' . $args['taxonomy']
				);

				if (isset($term_translations[$language])) {
					$translated_term_ids[] = $term_translations[$language]->element_id;
				}
			}
		}

		return array_merge($args['term_ids'], $translated_term_ids);
	}
}

