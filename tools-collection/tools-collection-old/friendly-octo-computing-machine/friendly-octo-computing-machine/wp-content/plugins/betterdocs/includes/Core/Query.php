<?php

namespace WPDeveloper\BetterDocs\Core;

use WP_Query;
use WPDeveloper\BetterDocs\Utils\Base;
use WPDeveloper\BetterDocs\Utils\Database;
use WPDeveloper\BetterDocs\Dependencies\DI\Container;

class Query extends Base {
	private $container;
	protected $database;
	protected $settings;

	public function __construct( Container $container, Database $database, Settings $settings ) {
		$this->container = $container;
		$this->database  = $database;
		$this->settings  = $settings;

		add_action( 'parse_term_query', [ $this, 'parse_term_query' ] );
		// add_action( 'parse_query', [$this, 'parse_query'], 1 );
		add_action( 'pre_get_posts', [ $this, 'pre_get_posts' ], 1 );
		add_filter( 'betterdocs_base_terms_args', [ $this, 'modify_terms_args_for_private_docs' ], 10, 1 );

		/**
		 * These below filters are hooked for navigation only.
		 *
		 * For old version of this portion.
		 * @see `betterdocs_single_post_nav` filter
		 *
		 * For details:
		 * @see https://developer.wordpress.org/reference/functions/get_next_post/
		 * @see https://developer.wordpress.org/reference/functions/get_previous_post/
		 *
		 * @link https://developer.wordpress.org/reference/hooks/get_adjacent_post_where/
		 */
		add_filter( 'get_next_post_where', [ $this, 'next_post_where' ], 99, 5 );
		add_filter( 'get_previous_post_where', [ $this, 'previous_post_where' ], 99, 5 );

		$this->init();

		/**
		 * Modify Popular Docs Query (For Shortcode & Widget)
		 */
		add_filter( 'posts_clauses', [ $this, 'mod_query_popular_docs' ], 10, 2 );
	}

	public function mod_query_popular_docs( $clauses, $wp_query ) {
		if ( isset( $wp_query->query['meta_key'] ) ) {
			if ( $wp_query->query['meta_key'] == '_betterdocs_meta_views' ) {
				global $wpdb;
				$order              = isset( $wp_query->query['order'] ) ? $wp_query->query['order'] : '';
				$order_by_query     = ( $order == 'ASC' || $order == 'DESC' ) ? "SUM({$wpdb->prefix}betterdocs_analytics.impressions) {$order}" : ( $order == 'MODIFIED' ? "{$wpdb->prefix}posts.post_modified_gmt DESC" : "{$wpdb->prefix}posts.post_date_gmt DESC" );
				$clauses['join']    = "JOIN {$wpdb->prefix}betterdocs_analytics ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}betterdocs_analytics.post_id";
				$clauses['where']   = ! current_user_can( 'read_private_docs' ) ? "AND ( ( {$wpdb->prefix}posts.post_type = 'docs' ) AND ( {$wpdb->prefix}posts.post_status = 'publish' OR {$wpdb->prefix}posts.post_status = 'future' ) )" : "AND ( ( {$wpdb->prefix}posts.post_type = 'docs' ) AND ( {$wpdb->prefix}posts.post_status = 'publish' OR {$wpdb->prefix}posts.post_status = 'future' OR {$wpdb->prefix}posts.post_status = 'draft' OR {$wpdb->prefix}posts.post_status = 'pending' OR {$wpdb->prefix}posts.post_status = 'private' ) )";
				$clauses['orderby'] = $order_by_query;
				$clauses['groupby'] = "{$wpdb->prefix}betterdocs_analytics.post_id";
				if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
					global $sitepress;
					if ( $sitepress->is_setup_complete() ) {
						$constant_language_code = ICL_LANGUAGE_CODE;
						$clauses['join']       .= " JOIN {$wpdb->prefix}icl_translations ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}icl_translations.element_id";
						$clauses['where']      .= " AND ( {$wpdb->prefix}icl_translations.language_code = '{$constant_language_code}' ) AND ( {$wpdb->prefix}icl_translations.element_type = CONCAT('post_', {$wpdb->prefix}posts.post_type ) )";
					}
				}
			}
		}
		return $clauses;
	}

	public function init() {
	}

	/**
	 * Modify terms args to include terms with only private docs for users with read_private_docs capability
	 *
	 * @param array $args
	 * @return array
	 */
	public function modify_terms_args_for_private_docs( $args ) {
		// Only modify for users with read_private_docs capability and supported taxonomies
		if ( ! current_user_can( 'read_private_docs' ) || ! isset( $args['taxonomy'] ) ) {
			return $args;
		}

		// Only support doc_category taxonomy for private docs filtering
		// knowledge_base terms don't have docs directly assigned to them
		$supported_taxonomies = ['doc_category'];
		if ( ! in_array( $args['taxonomy'], $supported_taxonomies ) ) {
			return $args;
		}

		// If hide_empty is true, we need to modify the logic to include terms with private docs
		if ( isset( $args['hide_empty'] ) && $args['hide_empty'] ) {
			// Set hide_empty to false and we'll filter manually later
			$args['hide_empty'] = false;
			// Add a flag to indicate we need to filter manually
			$args['_betterdocs_filter_private'] = true;
		}

		return $args;
	}

	public function parse_term_query( $term_query ) {
		if ( empty( $term_query->query_vars['taxonomy'] ) ) {
			return;
		}

		if ( ! in_array( 'doc_category', $term_query->query_vars['taxonomy'], true ) ) {
			return;
		}

		global $current_screen;

		if ( $current_screen == null ) {
			return;
		}

		if ( $current_screen->taxonomy !== 'doc_category' || $current_screen->id != 'edit-doc_category' ) {
			return;
		}

		$term_query->query_vars['meta_query'] = [
			[
				'key'  => 'doc_category_order',
				'type' => 'NUMERIC'
			]
		];

		$term_query->query_vars['orderby'] = 'meta_value_num';
	}

	public function parse_query( &$query ) {
		// dump( is_single(), $query->query_vars );
		// if ( is_single() && isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] == 'docs' ) {
		//     $query->is_single = false;
		//     $query->is_archive = true;
		//     $query->set( 'knowledge_base', $query->query_vars['docs'] );
		// }
	}

	public function pre_get_posts( &$query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( is_tax( 'doc_category' ) ) {
			$query->set( 'post_type', 'docs' );
			$query->set( 'posts_per_archive_page', -1 );

			$term = get_term_by( 'slug', $query->get( 'doc_category', '' ), 'doc_category' );

			if ( $term && isset( $term->term_id ) ) {
				$post__in = $this->get_docs_order_by_terms( $term->term_id );
			} else {
				$post__in = [];
			}
			if ( ! empty( $post__in ) ) {
				$query->set( 'orderby', 'post__in' );
				$query->set( 'post__in', $post__in );
			}

			// if ( ! empty( $query->query_vars['knowledge_base'] ) ) {
			//     $query->betterdocs_terms = get_terms( [
			//         'taxonomy'   => 'doc_category',
			//         'parent'     => 0,
			//         'hide_empty' => true,
			//         'meta_query' => [
			//             'relation' => 'OR',
			//             [
			//                 'key'     => 'doc_category_knowledge_base',
			//                 'value'   => $query->query_vars['knowledge_base'],
			//                 'compare' => 'LIKE'
			//             ]
			//         ]
			//     ] );
			// }
		}

		// dump( is_archive(), $query->query_vars );
	}

	/**
	 * Get docs orders by term_id.
	 *
	 * @since 2.5.0
	 * @param int $term_id
	 *
	 * @return array
	 */
	public function get_docs_order_by_terms( $term_id ) {
		global $wpdb;
		$_docs_order = get_term_meta( $term_id, '_docs_order', true );
		$query       = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id = %d", $term_id );
		$query_key   = "docs_order_by_terms_{$term_id}_" . md5( $query );

		if ( ( $results = $this->database->get_cache( $query_key ) ) !== false ) {
			return $results;
		}

		if ( ! empty( $_docs_order ) ) {
			$_docs_order = explode( ',', $_docs_order );
			$new_ids     = [];

			$results = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			if ( is_array( $results ) && ! empty( $results ) ) {
				$object_ids = array_filter(
					$results,
					function ( $value ) use ( $_docs_order ) {
						return ! in_array( $value->object_id, $_docs_order );
					}
				);

				if ( ! empty( $object_ids ) ) {
					array_walk(
						$object_ids,
						function ( $value ) use ( &$new_ids ) {
							$new_ids[] = $value->object_id;
						}
					);
				}
			}

			$_docs_order = array_merge( $new_ids, $_docs_order );
			$this->database->set_cache( $query_key, $_docs_order, 1 );

			return $_docs_order;
		}

		return [];
	}

	/**
	 * For determine the next post ID
	 *
	 * @link https://developer.wordpress.org/reference/hooks/get_adjacent_post_where/
	 *
	 * @param mixed $where
	 * @param mixed $in_same_term
	 * @param mixed $excluded_terms
	 * @param mixed $taxonomy
	 * @param mixed $post
	 * @return mixed
	 */
	public function next_post_where( $where, $in_same_term, $excluded_terms, $taxonomy, $post ) {
		return $this->get_adjacent_post_id( 'next', $where, $post, $taxonomy );
	}

	/**
	 * For determine the previous post ID
	 *
	 * @link https://developer.wordpress.org/reference/hooks/get_adjacent_post_where/
	 *
	 * @param mixed $where
	 * @param mixed $in_same_term
	 * @param mixed $excluded_terms
	 * @param mixed $taxonomy
	 * @param mixed $post
	 * @return mixed
	 */
	public function previous_post_where( $where, $in_same_term, $excluded_terms, $taxonomy, $post ) {
		return $this->get_adjacent_post_id( 'previous', $where, $post, $taxonomy );
	}

	/**
	 * Get where clause for next/previous post ID mainly used in post navigation.
	 *
	 * @see `previous_post_where` and `next_post_where` methods.
	 *
	 * @param mixed $adjacent
	 * @param mixed $where
	 * @param mixed $post
	 * @param mixed $taxonomy
	 * @return mixed
	 */
	private function get_adjacent_post_id( $adjacent, $where, $post, $taxonomy ) {
		if ( $taxonomy !== 'doc_category' ) {
			return $where;
		}

		$_id    = null;
		$_terms = get_the_terms( $post->ID, 'doc_category' );
		if ( empty( $_terms ) ) {
			return $where;
		}

		global $wp_query, $wpdb;

		$_docs_order = $this->get_docs_order_by_terms( $_terms[0]->term_id );

		$_where = explode( 'AND', $where );
		if ( is_array( $_where ) ) {
			// $_where[0] = str_replace( 'WHERE ', '', $_where[0] );
			unset( $_where[0] );
			$_where = implode( 'AND', $_where );
		}

		$_orderby    = $this->settings->get( 'alphabetically_order_post', 'betterdocs_order' );
		$_order      = $this->settings->get( 'docs_order', 'ASC' );
		$_docs_order = $_orderby === 'betterdocs_order' ? $_docs_order : [];

		if ( empty( $_docs_order ) ) {
			$statuses = [ 'publish' ];

			if ( current_user_can( 'read_private_docs' ) ) {
				$statuses[] = 'private';
			}

			$_args = [
				'post_status' => $statuses
			];
			if ( isset( $wp_query->query_vars['doc_category'] ) ) {
				$_args['tax_query'][] = [
					'taxonomy'         => 'doc_category',
					'field'            => 'slug',
					'terms'            => $wp_query->query_vars['doc_category'],
					'operator'         => 'AND',
					'include_children' => false
				];
			} else if( isset( $wp_query->query_vars['name'] ) && isset( $wp_query->query_vars['post_type'] ) && $wp_query->query_vars['post_type'] == 'docs' ) {
				$post      = get_page_by_path($wp_query->query_vars['name'] , OBJECT, 'docs');
				$doc_terms = [];

				if( isset( $post->ID ) ) {
					$terms = get_the_terms($post->ID, 'doc_category');
					if( ! empty( $terms )  ) {
						$doc_terms = wp_list_pluck( $terms, 'term_id' );
					}
				}

				if( ! empty( $doc_terms ) ) {
					$_args['tax_query'][] = [
						'taxonomy'         => 'doc_category',
						'field'            => 'term_id',
						'terms'            => $doc_terms,
						'operator'         => 'AND',
						'include_children' => false
					];
				}
			}

			$_args['orderby'] = $_orderby;
			if ( $_orderby != 'betterdocs_order' ) {
				$_args['order'] = $_order;
			}

			/**
			 * Before Query
			 */
			do_action_ref_array( 'betterdocs_navigation_docs_query', [ &$_args ] );
			$docs = $this->get_posts( $this->docs_query_args( $_args ) );

			$_docs = [];
			if ( $docs->have_posts() ) {
				array_map(
					function ( $post ) use ( &$_docs ) {
						$_docs[] = $post->ID;
					},
					$docs->posts
				);
			}

			$_docs_order = $_docs;
		}

		$_docs_order = apply_filters( 'betterdocs_adjacent_docs_order', $_docs_order, $_terms );

		$_id_index = array_search( $post->ID, $_docs_order );
		$_id_index = $adjacent === 'next' ? $_id_index + 1 : $_id_index - 1;
		$_id       = isset( $_docs_order[ $_id_index ] ) ? (int) $_docs_order[ $_id_index ] : null;

		return $wpdb->prepare( "WHERE p.ID = %s AND $_where", (int) $_id );
	}

	public function parse_terms_args( $args = [] ) {
		$_default_args = [
			'hide_empty' => true,
			'taxonomy'   => 'doc_category'
		];

		// OrderBy & Order
		$_orderby = ! empty( $args['orderby'] ) && $args['orderby'] != 1 ? $args['orderby'] : 'name';
		$_order   = ! empty( $args['order'] ) ? $args['order'] : '';

		if ( $_orderby == 'betterdocs_order' ) {
			$args['meta_key'] = 'doc_category_order';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'ASC';
		} else {
			$args['orderby'] = $_orderby;
			$args['order']   = $_order;
		}

		// Nested Sub Category
		// $args['parent'] = 0;
		// if ( $nested_subcategory == true ) {
		// }

		if ( ! isset( $args['number'] ) ) {
			global $wp_query;
			if ( $wp_query->query === null || ( isset( $wp_query->query['post_type'] ) && $wp_query->query['post_type'] != 'docs' ) ) {
				$args['number'] = 4;
			}
		}

		// Includes
		if ( isset( $args['include'] ) ) {
			$_include        = ! is_array( $args['include'] ) ? explode( ',', $args['include'] ) : $args['include'];
			$args['include'] = $_include;
			$args['orderby'] = 'include';

			unset( $args['parent'] );
		}

		$_meta_query        = ! empty( $args['meta_query'] ) ? $args['meta_query'] : [];
		$args['meta_query'] = apply_filters( 'betterdocs_taxonomy_object_meta_query', $_meta_query, $args );

		return apply_filters( 'betterdocs_category_terms_object', wp_parse_args( $args, $_default_args ), $args );
	}

	public function get_terms( $args ) {
		$parsed_args = $this->parse_terms_args( $args );
		$terms = get_terms( $parsed_args );

		// Filter terms manually if we need to consider private docs for users with read_private_docs capability
		if ( isset( $parsed_args['_betterdocs_filter_private'] ) && $parsed_args['_betterdocs_filter_private'] && current_user_can( 'read_private_docs' ) ) {
			$terms = array_filter( $terms, function( $term ) {
				// Get the actual count including private docs for users with read_private_docs capability
				$actual_count = $this->get_docs_count( $term, false );
				return $actual_count > 0;
			});
		}

		return $terms;
	}

	public function get_child_terms( $args ) {
		if ( ! isset( $args['number'] ) ) {
			global $wp_query;
			if ( $wp_query->query === null || ( isset( $wp_query->query['post_type'] ) && $wp_query->query['post_type'] != 'docs' ) ) {
				$args['number'] = 1;
			}
		}

		return $this->get_terms( $args );
	}

	/**
	 * Get POSTs of Docs type.
	 *
	 * @param mixed $args
	 * @return WP_Query
	 */
	public function get_posts( $args, $ignore = false ) {
		if ( ! $ignore ) {
			$args = $this->docs_query_args( $args );
		}

		return new WP_Query( $args );
	}

	public function get_taxonomy( $tax = '' ) {
		global $wp_query;
		if ( is_tax( 'knowledge_base' ) ) {
			$_tax = $wp_query->tax_query->queried_terms;
			if ( array_key_exists( 'doc_category', $_tax ) ) {
				$tax = 'doc_category';
			} else {
				$tax = 'knowledge_base';
			}
		} elseif ( is_tax( 'doc_category' ) ) {
			$tax = 'doc_category';
		}

		return $tax;
	}

	public function terms_query( $args = [] ) {
		global $wp_query;
		$_origin_args = $args;

		$default_args = [
			'hide_empty' => true,
			'taxonomy'   => 'doc_category',
			'orderby'    => 'name'
		];

		/**
		 * Number Set
		 *
		 * @FIX: If Built-in Docs page off and docs_page in use, then Terms Query Number 4|1 set.
		 */
		// if ( $wp_query->query === NULL || ( isset( $wp_query->query['post_type'] ) && $wp_query->query['post_type'] != 'docs' ) ) {
		//     $default_args['number'] = 1;
		// }

		/**
		 * Nested Sub Category
		 */
		if ( isset( $args['nested_subcategory'] ) && $args['nested_subcategory'] == true ) {
			$default_args['parent'] = 0;
			unset( $args['nested_subcategory'] );
			// if ( $wp_query->query === NULL || ( isset( $wp_query->query['post_type'] ) && $wp_query->query['post_type'] != 'docs' ) ) {
			//     $default_args['number'] = 4;
			// }
		}

		/**
		 * OrderBy and Order
		 */
		if ( ! isset( $args['orderby'] ) ) {
			$_orderby = $this->settings->get( 'terms_orderby', 'name' );
			$_order   = $this->settings->get( 'terms_order', '' );
		} else {
			$_orderby = $args['orderby'];
			$_order   = ! empty( $args['order'] ) ? $args['order'] : '';
		}

		if ( 'betterdocs_order' === $_orderby ) {
			// Use different meta keys for different taxonomies
			if ( isset( $args['taxonomy'] ) && $args['taxonomy'] === 'knowledge_base' ) {
				$default_args['meta_key'] = 'kb_order';
			} else {
				$default_args['meta_key'] = 'doc_category_order';
			}
			$_orderby = 'meta_value_num';
			$_order   = 'ASC';
		} elseif ( $_orderby === true ) {
			$_orderby = 'name';
		}

		$args['orderby'] = $_orderby;
		if ( ! empty( $_order ) ) {
			$args['order'] = $_order;
		}

		/**
		 * @todo old hook
		 * hook: betterdocs_child_taxonomy_meta_query
		 */
		$_multiple_kb = apply_filters(
			'betterdocs_query_args_multiple_kb_enabled',
			isset( $args['multiple_kb'] ) ? (bool) $args['multiple_kb'] : false,
			$_origin_args
		);

		$_kb_slug = isset( $args['kb_slug'] ) ? trim( $args['kb_slug'] ) : '';

		unset( $args['multiple_kb'] );
		unset( $args['kb_slug'] );

		$meta_query = ! empty( $args['meta_query'] ) ? $args['meta_query'] : [];
		$meta_query = apply_filters( 'betterdocs_terms_meta_query_args', $meta_query, $_multiple_kb, $_kb_slug, $_origin_args );

		if ( ! empty( $meta_query ) ) {
			$default_args['meta_query'] = $meta_query;
		}

		if ( ! empty( $args['terms'] ) ) {
			$args['include'] = explode( ',', $args['terms'] );
			$args['orderby'] = 'include';
			$args['order']   = 'ASC';

			unset( $default_args['parent'] );
			unset( $args['parent'] );
			unset( $args['terms'] );
		}

		$_query_args = wp_parse_args( $args, $default_args );
		$_query_args = apply_filters( 'betterdocs_terms_query_args', $_query_args, $_origin_args );

		// Apply private docs logic for logged-in users
		$_query_args = $this->modify_terms_args_for_private_docs( $_query_args );

		return $_query_args;
	}

	public function get_term_parents( $term_id, $taxonomy = 'doc_category', $args = [] ) {
		$term = get_term( $term_id, $taxonomy );
		if ( is_wp_error( $term ) ) {
			return $term;
		}

		if ( ! $term || ! isset( $term->term_id ) ) {
			return [];
		}

		$_lists      = [];
		$origin_term = $term_id;
		$term_id     = $term->term_id;

		$defaults = [
			'format'    => 'name',
			'inclusive' => true
		];

		$args = wp_parse_args( $args, $defaults );

		$args['inclusive'] = wp_validate_boolean( $args['inclusive'] );

		$parents = get_ancestors( $term_id, $taxonomy );

		if ( $args['inclusive'] ) {
			array_unshift( $parents, $term_id );
		}

		foreach ( array_reverse( $parents ) as $term_id ) {
			$parent         = get_term( $term_id, $taxonomy );
			$name           = ( 'slug' === $args['format'] ) ? $parent->slug : $parent->name;
			$term_permalink = get_term_link( $parent->term_id, $taxonomy );
			$term_permalink = apply_filters( 'betterdocs_breadcrumb_term_permalink', $term_permalink, $term_id );

			$_item = [
				'url'  => $term_permalink,
				'text' => $name
			];

			$_lists[] = $_item;
		}

		return apply_filters( 'betterdocs_breadcrumb_archive_lists', $_lists, $origin_term );
	}

	/**
	 * Get all non-empty child term IDs recursively for a given taxonomy and parent term.
	 *
	 * This function retrieves all child terms for a specified taxonomy and parent term,
	 * recursively fetching child terms of child terms, and returns only those with a non-zero post count.
	 *
	 * @param string $taxonomy  The taxonomy name (e.g., 'doc_category').
	 * @param int    $parent_id The ID of the parent term to start retrieving children from.
	 *
	 * @return array An array of non-empty child term IDs.
	 */
	public function get_all_child_term_ids( $taxonomy, $parent_id ) {
		// Set up the arguments for retrieving child terms
		$args = apply_filters(
			'betterdocs_get_child_term_ids_args',
			[
				'taxonomy'   => $taxonomy,
				'parent'     => $parent_id,
				'hide_empty' => true,
				'fields'     => 'ids'
			]
		);

		// Get the terms based on the arguments
		$terms = get_terms( $args );

		// Initialize an empty array to hold non-empty child term IDs
		$non_empty_children = [];

		// Check if terms were retrieved without errors and that the result isn't empty
		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			$term_ids = [];

			// Loop through each term ID
			foreach ( $terms as $term_id ) {
				// Add the current term ID to the term_ids array
				$term_ids[] = $term_id;

				// Recursively get child terms for the current term
				$child_term_ids = $this->get_all_child_term_ids( $taxonomy, $term_id );

				// If there are child terms, merge them into the term_ids array
				if ( ! empty( $child_term_ids ) ) {
					$term_ids = array_merge( $term_ids, $child_term_ids );
				}
			}

			// Loop through all retrieved term IDs to filter out empty ones
			foreach ( $term_ids as $term_id ) {
				// Get the term object for the current term ID
				$child_term = get_term( $term_id, $taxonomy );

				// Only include terms that have a non-zero post count
				if ( $child_term && isset( $child_term->term_id ) && $child_term->count > 0 ) {
					$non_empty_children[] = $child_term->term_id;
				}
			}
		}

		// Return the final array of non-empty child term IDs
		return $non_empty_children;
	}

	/**
	 * Get all nested child term IDs of a specific parent term in a taxonomy and return as a comma-separated string.
	 *
	 * @param string $taxonomy The taxonomy.
	 * @param int $parent_id The parent term ID.
	 * @return string The comma-separated term IDs.
	 */
	public function get_child_term_ids_by_parent_id( $taxonomy, $parent_id ) {
		$term_ids = $this->get_all_child_term_ids( $taxonomy, $parent_id );
		return implode( ',', $term_ids );
	}

	public function get_terms_children( $taxonomy, $parent_id ) {
		$children           = get_term_children( $parent_id, $taxonomy );
		$non_empty_children = [];
		if ( ! is_wp_error( $children ) && ! empty( $children ) ) {
			foreach ( $children as $child_id ) {
				$child_term = get_term( $child_id, $taxonomy );

				// Only include non-empty terms
				if ( $child_term && $child_term->count > 0 ) {
					$non_empty_children[] = $child_term;
				}
			}
		}

		return $non_empty_children;
	}

	public function get_terms_children_ids( $taxonomy, $parent_id ) {
		$children = $this->get_terms_children( $taxonomy, $parent_id );
		$term_ids = wp_list_pluck( $children, 'term_id' );

		return implode( ',', $term_ids );
	}

	public function count_terms_children( $taxonomy, $parent_id ) {
		return count( $this->get_terms_children( $taxonomy, $parent_id ) );
	}

	public function is_inner_templates() {
		if ( is_tax( 'knowledge_base' ) || is_tax( 'doc_category' ) || is_tax( 'doc_tag' ) || is_singular( 'docs' ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Summary of docs_query_args
	 * @param mixed $args
	 * @throws \Exception
	 * @return mixed
	 */
	public function docs_query_args( $args, $filter = [] ) {
		$_origin_args = $args;

		$default_args = [
			'post_type' => 'docs'
		];

		if ( ! empty( $args['post_type'] ) && trim( $args['post_type'] ) === 'docs_any' ) {
			$default_args['post_type']   = 'docs';
			$default_args['post_status'] = 'any';

			unset( $args['post_type'] );
		}

		/**
		 * OrderBy and Order
		 */
		if ( ! isset( $args['orderby'] ) ) {
			$_orderby = $this->settings->get( 'alphabetically_order_post' );
			$_order   = $this->settings->get( 'docs_order', 'ASC' );
		} else {
			$_orderby = $args['orderby'];
			$_order   = ! empty( $args['order'] ) ? $args['order'] : 'ASC';
		}

		if ( 'betterdocs_order' != $_orderby ) {
			if ( $_orderby === true ) {
				$args['orderby'] = 'title';
			} else {
				$args['orderby'] = $_orderby;
			}

			$args['order'] = $_order;
		} elseif ( 'betterdocs_order' == $_orderby ) {
			unset( $args['orderby'] );
		}

		if ( empty( $args['orderby'] ) ) {
			unset( $args['order'] );
		}

		/**
		 * Term ID
		 */
		$_term_id = null;
		if ( ! empty( $args['term_id'] ) ) {
			$_term_id = intval( $args['term_id'] );
			unset( $args['term_id'] );
		}

		// if ( $_term_id == null ) {
		//     throw new \Exception( __( '$args["term_id"] cannot be null.', 'betterdocs' ) );
		// }

		/**
		 * Term Slug for tax_query
		 */
		$_term_slug = '';
		if ( ! empty( $args['term_slug'] ) ) {
			$_term_slug = trim( $args['term_slug'] );
			unset( $args['term_slug'] );
		}

		/**
		 * @todo old hook
		 * hook: betterdocs_cat_template_multikb
		 */

		$_multiple_kb = apply_filters(
			'betterdocs_enable_multiple_knowledge_base',
			isset( $args['multiple_kb'] ) ? (bool) $args['multiple_kb'] : false,
			$_origin_args
		);

		$_kb_slug = isset( $args['kb_slug'] ) ? trim( $args['kb_slug'] ) : '';

		unset( $args['multiple_kb'] );
		unset( $args['kb_slug'] );

		$tax_query = [
			[
				'taxonomy'         => 'doc_category',
				'field'            => 'slug',
				'terms'            => $_term_slug,
				'operator'         => 'AND',
				'include_children' => false
			]
		];

		if ( ! isset( $args['orderby'] ) || $args['orderby'] == 'betterdocs_order' ) {
			$args['orderby']  = 'post__in';
			$args['post__in'] = $this->get_docs_order_by_terms( $_term_id );
		}

		if ( isset( $args['tax_query'] ) ) {
			$tax_query = $args['tax_query'];
		}

		$args['tax_query'] = apply_filters(
			'betterdocs_docs_tax_query_args',
			$tax_query,
			$_multiple_kb,
			$_term_slug,
			$_kb_slug,
			$_origin_args,
			$this->is_inner_templates()
		);
		/**
		 * Final parse args
		 */
		$args = wp_parse_args( $args, $default_args );

		if ( ! empty( $filter ) ) {
			$filter = array_flip( $filter );
			$args   = array_filter(
				$args,
				function ( $item ) use ( $filter ) {
					return ! array_key_exists( $item, $filter );
				},
				ARRAY_FILTER_USE_KEY
			);
		}

		return apply_filters( 'betterdocs_articles_args', $args, $_term_id, $_origin_args );
	}

	public function faq_terms_query_args( $includes = '', $excludes = '', $args = [] ) {
		$_args = [
			'taxonomy'   => 'betterdocs_faq_category',
			'meta_key'   => 'order',
			'orderby'    => 'meta_value_num',
			'order'      => 'ASC',
			'include'    => $includes,
			'exclude'    => $excludes,
			'meta_query' => [
				[
					'key'     => 'status',
					'value'   => 1,
					'compare' => '=='
				]
			]
		];

		if ( $_args['include'] == 'all' ) {
			unset( $_args['include'] );
			unset( $_args['exclude'] );
		}

		if ( empty( $_args['exclude'] ) ) {
			unset( $_args['exclude'] );
		}

		if ( empty( $_args['include'] ) ) {
			unset( $_args['include'] );
		}

		return wp_parse_args( $args, $_args );
	}

	public function get_faq_by_term( $term_id ) {
		global $wpdb;

		$args = [
			'post_type'      => 'betterdocs_faq',
			'post_status'    => 'publish',
			'tax_query'      => [
				[
					'taxonomy' => 'betterdocs_faq_category',
					'field'    => 'term_id',
					'terms'    => $term_id,
					'operator' => 'AND'
				]
			],
			'posts_per_page' => -1
		];

		$args['orderby']  = 'post__in';
		$args['post__in'] = $this->get_faq_orders( $term_id );

		return new WP_Query( $args );
	}

	public function get_faq_orders( $term_id = null ) {
		global $wpdb;
		$faq_order = get_term_meta( $term_id, '_betterdocs_faq_order', true );
		$faq_order = explode( ',', $faq_order );

		$query     = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id = %d", $term_id );
		$query_key = 'betterdocs_faq_order_' . md5( $query );

		if ( ( $results = $this->database->get_cache( $query_key ) ) !== false ) {
			return $results;
		}

		if ( ! empty( $faq_order ) ) {
			$new_ids = [];
			$results = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			if ( ! is_null( $results ) && ! empty( $results ) && is_array( $results ) ) {
				$object_ids = array_filter(
					$results,
					function ( $value ) use ( $faq_order ) {
						return ! in_array( $value->object_id, $faq_order );
					}
				);

				if ( ! empty( $object_ids ) ) {
					array_walk(
						$object_ids,
						function ( $value ) use ( &$new_ids ) {
							$new_ids[] = $value->object_id;
						}
					);
				}
			}

			$faq_order = array_merge( $new_ids, $faq_order );
		}

		$this->database->set_cache( $query_key, $faq_order, 1 );

		return $faq_order;
	}

	public function get_faq_terms( $terms = [] ) {
		$_terms = get_terms(
			[
				'taxonomy'   => 'betterdocs_faq_category',
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
				'meta_query' => [
					[
						'key'     => 'status',
						'value'   => 1,
						'compare' => '=='
					]
				]
			]
		);

		if ( ! is_wp_error( $_terms ) ) {
			foreach ( $_terms as $term ) {
				$terms[ $term->term_id ] = $term->name;
			}
		}

		return $terms;
	}

	public function get_doc_terms( $terms = [] ) {
		$_terms = get_terms(
			[
				'taxonomy'   => 'doc_category',
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
			]
		);

		if ( ! is_wp_error( $_terms ) ) {
			foreach ( $_terms as $term ) {
				$terms[ $term->term_id ] = $term->name;
			}
		}

		return $terms;
	}


	public function get_docs_count( $term, $nested_subcategory = false, $args = [] ) {
		// Validate term object
		if ( ! is_object( $term ) ) {
			return 0;
		}

		$counts = isset( $term->count ) ? $term->count : 0;

		if ( $nested_subcategory == false ) {
			// For non-nested categories, we need to recalculate counts based on user capabilities
			// Only proceed if we have a valid term with required properties
			if ( isset( $term->term_id ) && isset( $term->taxonomy ) && is_numeric( $term->term_id ) ) {
				// Get all post IDs for this term
				$post_ids = get_objects_in_term( $term->term_id, $term->taxonomy );

				if ( ! empty( $post_ids ) ) {
					if ( current_user_can( 'read_private_docs' ) ) {
						// For users with read_private_docs capability, include both private and public posts
						$filtered_post_ids = array_filter( $post_ids, function( $post_id ) {
							$post_status = get_post_status( $post_id );
							return $post_status === 'private' || is_post_publicly_viewable( $post_id );
						});
					} else {
						// For users without read_private_docs capability, only include public posts
						$filtered_post_ids = array_filter( $post_ids, function( $post_id ) {
							return is_post_publicly_viewable( $post_id );
						});
					}

					$counts = count( $filtered_post_ids );
				} else {
					$counts = 0;
				}
			}

			return apply_filters( 'betterdocs_docs_count', $counts, $term, $nested_subcategory, $args );
		}

		$_child_terms_docs_ids = $this->get_doc_ids_by_term( $term, null, $nested_subcategory );
		if ( is_array( $_child_terms_docs_ids ) ) {
			$counts = count( $_child_terms_docs_ids );
		}

		return apply_filters( 'betterdocs_docs_count', $counts, $term, $nested_subcategory, $args );
	}

	public function get_doc_ids_by_term( $term, $optional = null, $nested_subcategory = false ) {
		// Check if term has required properties and is a valid object
		if ( ! is_object( $term ) || ! isset( $term->term_id ) || ! isset( $term->taxonomy ) || ! is_numeric( $term->term_id ) ) {
			return false;
		}

		$args = ['include' => $term->term_id];
        if ( $nested_subcategory ) {
            $args['child_of'] = $term->term_id;
            unset( $args['include'] );
        }
        $_child_terms = get_terms( $term->taxonomy, $args );

        if ( ! is_array( $_child_terms ) ) {
            return false;
        }

        array_unshift( $_child_terms, $term );

        $_child_terms_ids      = array_column( $_child_terms, 'term_id' );
        $_child_terms_taxs     = array_column( $_child_terms, 'taxonomy' );
        $_child_terms_docs_ids = get_objects_in_term( $_child_terms_ids, $_child_terms_taxs );

        if ( $optional !== null ) {
            $_optional_doc_ids     = get_objects_in_term( $optional->term_id, $optional->taxonomy );
            $_child_terms_docs_ids = array_intersect( $_child_terms_docs_ids, $_optional_doc_ids );
        }

        return array_filter( $_child_terms_docs_ids, function ( $doc_id ) {
            if ( ! current_user_can( 'read_private_docs' ) ) {
                return is_post_publicly_viewable( $doc_id );
            }

            $_status = get_post_status( $doc_id );
            return $_status == 'private' || is_post_publicly_viewable( $doc_id );
        } );
	}

	/**
	 * Get the common query arguments for WP_Query.
	 *
	 * @param string $terms The taxonomy.
	 * @param string $term_slug The taxonomy term slug.
	 * @param array $additional_args Additional arguments to merge with the common arguments.
	 * @return array The query arguments.
	 */
	private function tax_query_args( $terms, $term_slug, $additional_args = array() ) {
		$common_args = array(
			'post_type'   => 'docs',
			'post_status' => 'publish',
			'tax_query'   => array(
				array(
					'taxonomy' => $terms,
					'field'    => 'slug',
					'terms'    => $term_slug,
				),
			),
		);
		return array_merge( $common_args, $additional_args );
	}

	/**
	 * Get the latest updated date for a specific taxonomy term.
	 *
	 * @param string $terms The taxonomy.
	 * @param string $term_slug The taxonomy term slug.
	 * @return string|null The latest modified date or null if no posts found.
	 */
	public function latest_updated_date( $terms, $term_slug ) {
		$args = $this->tax_query_args(
			$terms,
			$term_slug,
			array(
				'posts_per_page' => 1,
				'orderby'        => 'modified',
				'order'          => 'DESC',
			)
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$latest_post = get_the_modified_date();
				wp_reset_postdata();
				return $latest_post;
			}
		} else {
			return null;
		}
	}

	/**
	 * Check if there are any new posts within the last 7 days for a specific taxonomy term.
	 *
	 * @param string $terms The taxonomy.
	 * @param string $term_slug The taxonomy term slug.
	 * @return bool True if there are new posts, false otherwise.
	 */
	public function check_new_posts( $terms, $term_slug ) {
		$date_7_days_ago = date( 'Y-m-d H:i:s', strtotime( '-7 days' ) );

		$args = $this->tax_query_args(
			$terms,
			$term_slug,
			array(
				'posts_per_page' => 1,
				'orderby'        => 'modified',
				'order'          => 'DESC',
				'date_query'     => array(
					array(
						'after'     => $date_7_days_ago,
						'inclusive' => true,
					),
				),
			)
		);

		$query = new WP_Query( $args );

		$has_new_posts = $query->have_posts();

		wp_reset_postdata();

		return $has_new_posts;
	}

	public function insert_search_keyword( $search_input, $input_not_found ) {
		if ( empty( $search_input ) ) {
			return false;
		}

		global $wpdb;
		$search = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT *
                FROM {$wpdb->prefix}betterdocs_search_keyword
                WHERE keyword = %s",
				$search_input
			)
		);

		if ( ! empty( $search ) ) {
			$search_log = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT *
                    FROM {$wpdb->prefix}betterdocs_search_log
                    WHERE created_at = %s AND keyword_id = %d",
					date( 'Y-m-d' ),
					$search[0]->id
				)
			);

			if ( ! empty( $search_log ) ) {
				if ( ! empty( $input_not_found ) ) {
					$tbl_field = 'not_found_count';
					$count     = $search_log[0]->not_found_count + 1;
				} else {
					$tbl_field = 'count';
					$count     = $search_log[0]->count + 1;
				}
                // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				$insert = $wpdb->query(
					$wpdb->prepare(
						"UPDATE {$wpdb->prefix}betterdocs_search_log
                        SET " . $tbl_field . ' = ' . $count . '
                        WHERE created_at = %s AND keyword_id = %d',
						$search_log[0]->created_at,
						$search_log[0]->keyword_id
					)
				);
			} else {
				if ( ! empty( $input_not_found ) ) {
					$count           = 0;
					$not_found_count = 1;
				} else {
					$count           = 1;
					$not_found_count = 0;
				}
				$insert = $wpdb->query(
					$wpdb->prepare(
						"INSERT INTO {$wpdb->prefix}betterdocs_search_log
                        ( keyword_id, count, not_found_count, created_at  )
                        VALUES ( %d, %d, %d, %s )",
						[
							$search[0]->id,
							$count,
							$not_found_count,
							date( 'Y-m-d' )
						]
					)
				);
			}
		} else {
			$insert = $wpdb->query(
				$wpdb->prepare(
					"INSERT INTO {$wpdb->prefix}betterdocs_search_keyword
                    ( keyword )
                    VALUES ( %s )",
					[
						$search_input
					]
				)
			);

			if ( $insert ) {
				if ( ! empty( $input_not_found ) ) {
					$count           = 0;
					$not_found_count = 1;
				} else {
					$count           = 1;
					$not_found_count = 0;
				}
				$insert = $wpdb->query(
					$wpdb->prepare(
						"INSERT INTO {$wpdb->prefix}betterdocs_search_log
                        ( keyword_id, count, not_found_count, created_at )
                        VALUES ( %d, %d, %d, %s )",
						[
							$wpdb->insert_id,
							$count,
							$not_found_count,
							date( 'Y-m-d' )
						]
					)
				);
			}
		}
		return $insert;
	}

	/**
	 * Counts the number of 'doc_category' terms assigned to a specific 'knowledge_base' by slug.
	 *
	 * This method retrieves all terms in the 'doc_category' taxonomy and checks the term meta
	 * 'doc_category_knowledge_base' to determine how many 'doc_category' terms are associated
	 * with a given 'knowledge_base' slug.
	 *
	 * @param string $knowledge_base_slug The slug of the 'knowledge_base' to count the assignments for.
	 *
	 * @return int The count of 'doc_category' terms assigned to the specified 'knowledge_base'.
	 */
	public function count_doc_categories_for_knowledge_base( $knowledge_base_slug ) {
		$count = 0;

		$doc_categories = get_terms(
			array(
				'taxonomy'   => 'doc_category',
				'hide_empty' => true,
			)
		);

		if ( ! empty( $doc_categories ) && ! is_wp_error( $doc_categories ) ) {
			foreach ( $doc_categories as $term ) {
				$meta_value = get_term_meta( $term->term_id, 'doc_category_knowledge_base', true );

				if ( $meta_value ) {
					$knowledge_bases = maybe_unserialize( $meta_value );

					// Check if the specific knowledge base slug is present in the meta value array
					if ( is_array( $knowledge_bases ) && in_array( $knowledge_base_slug, $knowledge_bases ) ) {
						++$count;
					}
				}
			}
		}

		return $count;
	}
}
