<?php

namespace Blocksy\Extensions\WoocommerceExtra;

use \Automattic\WooCommerce\Internal\ProductAttributesLookup\Filterer;
use \Automattic\WooCommerce\Internal\ProductAttributesLookup\DataRegenerator;

class CommonWCFilter extends BaseFilter {
	public function get_filter_id() {
		return 'common_filter';
	}

	public function get_filter_name($param = '') {
        return $param;
    }

	public function render($attributes = []) {
		return '';
	}

	public static function get_query_params() {
		return [];
	}

	public function get_applied_filters() {
		return [];
	}

	/**
	 * @phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_tax_query
	 */
	public function wp_query_arg($query_string, $query_args, $reason) {
		if ($reason !== BaseFilter::$WP_QUERY_ARG_REASON_COUNT) {
			return $query_args;
		}

		add_filter(
			'pre_option_woocommerce_attribute_lookup_enabled',
			[$this, 'force_enable_attribute_lookup']
		);

		$tax_query = WC()->query->get_tax_query(null, true);

		remove_filter(
			'pre_option_woocommerce_attribute_lookup_enabled',
			[$this, 'force_enable_attribute_lookup']
		);

		if (! isset($query_args['tax_query'])) {
			$query_args['tax_query'] = [];
		}

		$query_args['tax_query'] = array_merge(
			$query_args['tax_query'],
			$tax_query
		);

		return $query_args;
	}

	public function force_enable_attribute_lookup($value) {
		return 'yes';
	}

	public function posts_clauses($clauses, $query, $query_string) {
		if (
			! isset($query_string['min_price'])
			&&
			! isset($query_string['max_price'])
		) {
			return $clauses;
		}

		global $wp_the_query;
		$prev_wp_query = $wp_the_query;
		$GLOBALS['wp_the_query'] = $query;

		$clauses = WC()->query->price_filter_post_clauses($clauses, $query);

		$GLOBALS['wp_the_query'] = $prev_wp_query;

		return $clauses;
	}

	public function get_reset_url($attributes = []) {
		return false;
	}
}
