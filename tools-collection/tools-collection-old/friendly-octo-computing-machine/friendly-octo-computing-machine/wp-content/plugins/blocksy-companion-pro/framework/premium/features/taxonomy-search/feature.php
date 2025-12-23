<?php

namespace Blocksy;

class TaxonomySearch {
	public function __construct() {
		add_filter('posts_search', function ($search, \WP_Query $query) {
			if (
				! $query->is_search()
				||
				is_admin()
				||
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading GET parameter for search functionality, not form processing
				! isset($_GET['ct_search_taxonomies'])
				||
				empty($query->get('s'))
			) {
				return $search;
			}

			global $wpdb;

			$tax_query = [
				'relation' => 'OR'
			];

			$post_types = $query->get('post_type');

			if (! is_array($post_types)) {
				$post_types = [$post_types];
			}

			foreach ($post_types as $key => $post_type) {
				$taxonomies = get_object_taxonomies($post_type, 'objects');

				foreach($taxonomies as $taxonomy) {
					$found_terms = get_terms([
						'taxonomy' => $taxonomy->name,
						'fields' => 'slugs',
						'name__like' => strtolower($query->get('s'))
					]);

					if (empty($found_terms)) {
						continue;
					}

					$tax_query[] = [
						'taxonomy' => $taxonomy->name,
						'field' => 'slug',
						'terms' => $found_terms,
						'operator' => 'IN'
					];
				}
			}

			if (count($tax_query) === 1) {
				return $search;
			}

			$posts = new \WP_Query([
				'post_type' => $post_types,
				'posts_per_page' => -1,
				'status' => 'publish',
				'fields' => 'ids',
				'suppress_filters' => false,
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Intentional tax_query for taxonomy search functionality
				'tax_query' => $tax_query
			]);

			if (empty($posts->posts)) {
				return $search;
			}

			$result = str_replace(
				')))',
				")) OR ({$wpdb->posts}.ID IN (" . implode(',', $posts->posts) . ")))",
				$search
			);

			return $result;
		}, 9, 2);
	}
}
