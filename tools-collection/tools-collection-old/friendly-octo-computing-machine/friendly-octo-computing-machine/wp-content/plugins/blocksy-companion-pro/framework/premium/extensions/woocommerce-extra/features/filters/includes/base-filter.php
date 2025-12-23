<?php

namespace Blocksy\Extensions\WoocommerceExtra;

abstract class BaseFilter {
	public $attributes = [];

	abstract public function get_filter_id();
	abstract public function render($attributes = []);

	static public $WP_QUERY_ARG_REASON_MAIN = 'main';
	static public $WP_QUERY_ARG_REASON_COUNT = 'count';

	// Optional methods:
	//
	// 1. wp_query_arg -- for modifying the query args during WP_Query cosntruction.
	//                    Either for main filtering logic or just for counts.
	//
	// Reason: main | count
	//
	// 2. posts_clauses -- for modifying the SQL query during WP_Query construction.

	// TODO: maybe document format of applied filters
	abstract public function get_applied_filters();
	abstract public function get_reset_url($attributes = []);

	public static function get_query_params() {
		return [];
	}
}
