<?php

namespace Blocksy\Extensions\WoocommerceExtra;

use Automattic\WooCommerce\Utilities\ArrayUtil;
use Automattic\WooCommerce\Utilities\StringUtil;

class TaxonomiesProductsLookupHooks {
	private $store = null;

	private $is_saving = false;

	public function __construct($store) {
		$this->store = $store;
		$this->init_hooks();
	}

	public function init_hooks() {
		add_action('save_post', [$this, 'save_post']);
		add_action('delete_post', [$this, 'delete_post']);
		add_action('delete_term', [$this, 'delete_term'], 10, 3);
		add_action('edited_term', [$this, 'edit_term'], 10, 4);
		add_action('set_object_terms', [$this, 'set_object_terms']);
		add_filter('wp_insert_post_parent', [$this, 'is_wp_insert_post']);
		add_action(
			'woocommerce_product_import_inserted_product_object',
			[$this, 'index_on_csv_import'],
			20, 2
		);

		add_action('woocommerce_after_set_term_order', [$this, 'woocommerce_after_set_term_order'], 10, 3);
	}

	public function save_post($post_id) {
		if (defined('DOING_AUTOSAVE') && \DOING_AUTOSAVE) {
			return;
		}

		if (false !== wp_is_post_revision($post_id)) {
			return;
		}

		if ('auto-draft' === get_post_status($post_id)) {
			return;
		}

		if (get_post_type($post_id) !== 'product') {
			return;
		}

		$product = wc_get_product($post_id);
		$visibility = $product->get_catalog_visibility();

		if ($visibility === 'hidden') {
			$this->store->delete_data_for([
				'column' => 'parent_id',
				'value' => $post_id
			]);
		} else {
			$this->store->create_data_for_product($product);
		}

		$this->is_saving = false;
	}

	public function delete_post($post_id) {
		if (get_post_type($post_id) !== 'product') {
			return;
		}

		$this->store->delete_data_for([
			'column' => 'product_id',
			'value' => $post_id
		]);
	}

	public function woocommerce_after_set_term_order($term, $index, $taxonomy) {
		$taxonomies = blc_get_ext('woocommerce-extra')
			->utils
			->get_product_taxonomies();

		if (! in_array($taxonomy, $taxonomies)) {
			return;
		}
	}

	public function edit_term($term_id, $tt_id, $taxonomy, $args) {
		$taxonomies = blc_get_ext('woocommerce-extra')
			->utils
			->get_product_taxonomies();

		if (! in_array($taxonomy, $taxonomies)) {
			return;
		}

		blc_get_ext('woocommerce-extra')
			->filters
			->lookup_table
			->initiate_regeneration('on_term_update');
	}

	public function delete_term($term_id, $tt_id, $taxonomy) {
		$taxonomies = blc_get_ext('woocommerce-extra')
			->utils
			->get_product_taxonomies();

		if (! in_array($taxonomy, $taxonomies)) {
			return;
		}

		$this->store->delete_data_for([
			'column' => 'term_id',
			'value' => $term_id
		]);
	}

	public function set_object_terms($object_id) {
		if (! $this->is_saving) {
			$this->store->create_data_for_product($object_id);
		}
	}

	public function is_wp_insert_post($post_parent) {
		$this->is_saving = true;
		return $post_parent;
	}

	public function index_on_csv_import($object, $data) {
		$this->store->create_data_for_product($object->get_id());
	}
}

