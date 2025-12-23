<?php

namespace Blocksy\Extensions\WoocommerceExtra;

trait ProductTermsCountTrait {
	private $cached_product_ids = [];

	abstract protected function get_terms_counts_sql($args = []);

	public function get_terms_counts($param, $args = []) {
		global $wpdb;

		$args = wp_parse_args($args, [
			'ignore_current_query' => false,
			'term_ids' => []
		]);

		$product_ids = $this->get_product_ids([
			'ignore_current_query' => $args['ignore_current_query'],
			'param' => $param
		]);

		$sql = $this->get_terms_counts_sql([
			'product_ids' => $product_ids,
			'term_ids' => $args['term_ids']
		]);

		if (! $sql) {
			return [];
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
		return $wpdb->get_results($sql, OBJECT_K);
	}

	protected function get_product_ids($args = []) {
		$args = wp_parse_args($args, [
			'ignore_current_query' => false,
			'param' => ''
		]);

		if (isset($this->cached_product_ids[$args['param']])) {
			return $this->cached_product_ids[$args['param']];
		}

		$product_ids = [];

		if ($args['ignore_current_query']) {
			$product_ids = $this->get_all_product_ids();
		} else {
			$product_ids = $this->get_product_ids_for_current_query($args['param']);
		}

		$this->cached_product_ids[$args['param']] = $product_ids;

		return $product_ids;
	}

	private function get_all_product_ids() {
		$lookup_table = blc_get_ext('woocommerce-extra')
			->filters
			->lookup_table;

		return $lookup_table->get_product_ids();
	}

	private function get_product_ids_for_current_query($param = '') {
		$apply_filters = new ApplyFilters();

		$query_params = FiltersUtils::get_query_params()['params'];

		unset($query_params[$param]);

		$products_query = $apply_filters->get_custom_query_for($query_params);

		return $products_query->posts;
	}
}
