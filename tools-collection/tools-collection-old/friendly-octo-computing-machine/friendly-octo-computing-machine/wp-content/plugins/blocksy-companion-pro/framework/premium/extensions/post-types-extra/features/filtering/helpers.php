<?php

if (! function_exists('blc_cpt_extra_filtering_output')) {
	function blc_cpt_extra_filtering_output($args = []) {
		$args = wp_parse_args($args, [
			'prefix' => null,
			'post_type' => get_post_type(),

			'term_ids' => [],
			'exclude_term_ids' => [],
			'use_children_tax_ids' => false,

			// default | current_page
			'links_strategy' => 'default'
		]);

		$args = apply_filters(
			'blocksy:ext:post-types-extra:filtering:arguments',
			$args
		);

		if (! $args['prefix'] && blc_theme_functions()->blocksy_manager()) {
			$args['prefix'] = blc_theme_functions()->blocksy_manager()->screen->get_prefix([
				'allowed_prefixes' => [
					'blog'
				],
				'default_prefix' => 'blog'
			]);
		}

		$prefix = $args['prefix'];

		$maybe_tax = blc_theme_functions()->blocksy_get_theme_mod(
			$prefix . '_filter_source',
			blocksy_maybe_get_matching_taxonomy($args['post_type'])
		);

		$taxonomies = get_object_taxonomies($args['post_type']);

		if (! in_array($maybe_tax, $taxonomies)) {
			$maybe_tax = blocksy_maybe_get_matching_taxonomy($args['post_type']);
		}

		if (! $maybe_tax) {
			return;
		}

		if (is_tag() && $maybe_tax !== 'post_tag') {
			return;
		}

		if (is_category() && $maybe_tax !== 'category') {
			return;
		}

		if (is_tax() && ! is_tax($maybe_tax)) {
			return;
		}

		$has_archive_filtering = blc_theme_functions()->blocksy_get_theme_mod(
			$prefix . '_has_archive_filtering',
			'no'
		);

		$class = 'ct-dynamic-filter';

		$class .= ' ' . blocksy_visibility_classes(
			blc_theme_functions()->blocksy_get_theme_mod($prefix . '_filter_visibility', [
				'desktop' => true,
				'tablet' => true,
				'mobile' => false,
			])
		);

		$type = blc_theme_functions()->blocksy_get_theme_mod(
			$prefix . '_filter_type',
			'simple'
		);

		if ($has_archive_filtering === 'no') {
			return;
		}

		$all_terms = get_terms([
			'taxonomy' => $maybe_tax,
			'parent' => 0,
			'hide_empty' => true,
			'include' => $args['term_ids'],
			// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'exclude' => apply_filters(
				'blocksy:ext:post-types-extra:filtering:exclude_terms',
				$args['exclude_term_ids']
			)
		]);

		if (empty($all_terms)) {
			return;
		}

		if ($args['use_children_tax_ids']) {
			$parents = $all_terms;

			$all_terms = [];

			foreach ($parents as $parent) {
				$children = get_terms([
					'taxonomy' => $maybe_tax,
					'parent' => $parent->term_id,
					'hide_empty' => true
				]);

				if (empty($children)) {
					continue;
				}

				foreach ($children as $child) {
					$all_terms[] = $child;
				}
			}
		}

		$post_type_object = get_post_type_object($args['post_type']);

		if (
			! $post_type_object->has_archive
			&&
			$args['post_type'] !== 'post'
		) {
			return;
		}

		echo '<div class="' . esc_attr($class) . '" data-type="' . esc_attr($type) . '">';

		$parent_attr = [
			'href' => get_post_type_archive_link($args['post_type'])
		];

		$maybe_current_tax = null;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_GET['blocksy_term_id'])) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$maybe_current_tax = sanitize_text_field(wp_unslash($_GET['blocksy_term_id']));
		}

		if ($args['links_strategy'] === 'current_page') {
			$parent_attr['href'] = get_permalink();

			if (! $maybe_current_tax) {
				$parent_attr['class'] = 'active';
			}
		}

		if (
			(
				is_post_type_archive($args['post_type'])
				&&
				! is_tax($maybe_tax)
			) || is_home()
		) {
			$parent_attr['class'] = 'active';
		}

		if (
			is_tax()
			||
			is_category()
			||
			is_tag()
		) {
			$current_tax = get_queried_object();

			if (
				$current_tax
				&&
				$current_tax->taxonomy !== $maybe_tax
			) {
				$parent_attr['href'] = get_term_link(
					$current_tax->term_id,
					$current_tax->taxonomy
				);

				if (! $maybe_current_tax) {
					$parent_attr['class'] = 'active';
				}
			}
		}

		$has_counters = blc_theme_functions()->blocksy_get_theme_mod(
			$prefix . '_has_counters',
			'no'
		) === 'yes';

		$all_label = __('All', 'blocksy-companion');

		if ($has_counters) {
			$count_posts = get_posts([
				'post_type' => $args['post_type'],
				'posts_per_page' => -1,
				'status' => 'publish',
				'fields' => 'ids',
				'suppress_filters' => false
			]);

			$all_label .= ' (' . count($count_posts) . ')';
		}

		blocksy_html_tag_e('a', $parent_attr, $all_label);

		foreach ($all_terms as $term) {
			$attr = [
				'href' => get_term_link($term, $maybe_tax)
			];

			if (
				is_tax()
				||
				is_category()
				||
				is_tag()
			) {
				$current_tax = get_queried_object();

				if ($current_tax && $current_tax->taxonomy !== $maybe_tax) {
					$attr['href'] = add_query_arg(
						'blocksy_term_id',
						$term->term_id,
						get_term_link(
							$current_tax->term_id,
							$current_tax->taxonomy
						)
					);
				}
			}

			if ($args['links_strategy'] === 'current_page') {
				$attr['href'] = add_query_arg(
					'blocksy_term_id',
					$term->term_id,
					get_permalink()
				);
			}

			if (isset($term->term_id)) {
				if (
					is_tax($maybe_tax, $term->term_id)
					||
					is_category($term->term_id)
					||
					is_tag($term->term_id)
					||
					(
						$maybe_current_tax
						&&
						intval($maybe_current_tax) === $term->term_id
					)
				) {
					$attr['class'] = 'active';
				}
			}

			if (isset($term->name)) {
				$label = $term->name;

				if ($has_counters) {
					$label .= ' (' . $term->count . ')';
				}

				blocksy_html_tag_e('a', $attr, $label);
			}
		}

		echo '</div>';
	}
}
