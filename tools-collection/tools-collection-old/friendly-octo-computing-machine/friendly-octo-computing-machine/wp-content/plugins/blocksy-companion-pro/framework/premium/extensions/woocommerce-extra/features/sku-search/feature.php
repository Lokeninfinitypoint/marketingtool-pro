<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class SkuSearch {
	public function __construct() {
		add_filter('posts_where', function ($where, \WP_Query $query) {
			if (! $query->is_search()) {
				return $where;
			}

			if (! function_exists('wc')) {
				return $where;
			}

			if (is_admin()) {
				return $where;
			}

			global $pagenow, $wpdb, $wp;

			$s = null;

			if (isset($wp->query_vars['s'])) {
				$s = $wp->query_vars['s'];
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if (isset($_GET['search'])) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$s = sanitize_text_field(wp_unslash($_GET['search']));
			}

			if (! $s) {
				return $where;
			}

			if (
				(is_admin() && 'edit.php' != $pagenow) || (
					isset($wp->query_vars['post_type'])
					&&
					'product' != $wp->query_vars['post_type']
				) || (
					isset($wp->query_vars['post_type'])
					&&
					is_array($wp->query_vars['post_type'])
					&&
					! in_array('product', $wp->query_vars['post_type'])
				)
			) {
				return $where;
			}

			$search_ids = [];
			$terms = explode(',', $s);

			foreach ($terms as $term) {
				$term = trim($term);

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$sku_to_parent_id = $wpdb->get_col(
					$wpdb->prepare(
						"SELECT p.post_parent as post_id FROM {$wpdb->posts} as p join {$wpdb->postmeta} pm on p.ID = pm.post_id and pm.meta_key='_sku' and pm.meta_value LIKE %s where p.post_parent <> 0 and p.post_status = 'publish' group by p.post_parent",
						'%' . $wpdb->esc_like(wc_clean($term)) . '%'
					)
				);

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$sku_to_id = $wpdb->get_col(
					$wpdb->prepare(
						"SELECT p.ID as post_id FROM {$wpdb->posts} as p join {$wpdb->postmeta} pm on p.ID = pm.post_id and pm.meta_key='_sku' and pm.meta_value LIKE %s and p.post_status = 'publish'",
						'%' . $wpdb->esc_like(wc_clean($term)) . '%'
					)
				);

				$search_ids = array_merge($search_ids, $sku_to_id, $sku_to_parent_id);
			}

			$search_ids = array_unique(array_filter(array_map('absint', $search_ids)));

			$result = [];

			foreach ($search_ids as $single_id) {
				if (
					get_post_type($single_id) !== 'product'
					||
					! in_array(get_post_status($single_id), ['publish'])
				) {
					continue;
				}

				$result[] = $single_id;
			}

			if (sizeof($result) > 0) {
				$where = str_replace(
					'))',
					") OR ({$wpdb->posts}.ID IN (" . implode(',', $result) . ")))",
					$where
				);
			}

			return $where;
		}, 9, 2);
	}
}
