<?php

namespace WPDeveloper\BetterDocs\Core;

use WP_Error;
use WP_Query;
use WPDeveloper\BetterDocs\Utils\Base;
use WPDeveloper\BetterDocs\Utils\Helper;

class FAQBuilder extends Base {
	/**
	 * REST API namespace
	 * @var string
	 */
	private $namespace = 'betterdocs';
	public $post_type  = 'betterdocs_faq';
	public $category   = 'betterdocs_faq_category';

	/**
	 *
	 * Initialize the class and start calling our hooks and filters
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct() {
		// assign default admin capabilities for docs, doc terms, doc tags, knowledge base
		add_action( 'init', [ $this, 'register_post' ] );
		// fires after a new betterdocs_faq_category is created
		add_action( 'created_betterdocs_faq_category', [ $this, 'action_created_betterdocs_faq_category' ], 10, 2 );
		add_action( 'rest_api_init', [ $this, 'register_api_endpoint' ] );
		add_action( 'rest_betterdocs_faq_category_query', [ $this, 'faq_category_orderby_meta' ], 10, 2 );
	}

	public function output() {
		betterdocs()->views->get( 'admin/faq-builder' );
	}

	/**
	 *
	 * Register post type and taxonomies
	 *
	 * @since    1.0.0
	 *
	 */
	public function register_post() {
		/**
		 * Register category taxonomy
		 */
		$category_labels = [
			'name'              => __( 'FAQ Categories', 'betterdocs' ),
			'singular_name'     => __( 'FAQ Category', 'betterdocs' ),
			'all_items'         => __( 'FAQ Categories', 'betterdocs' ),
			'parent_item'       => __( 'Parent FAQ Category', 'betterdocs' ),
			'parent_item_colon' => __( 'Parent FAQ Category:', 'betterdocs' ),
			'edit_item'         => __( 'Edit Category', 'betterdocs' ),
			'update_item'       => __( 'Update Category', 'betterdocs' ),
			'add_new_item'      => __( 'Add New FAQ Category', 'betterdocs' ),
			'new_item_name'     => __( 'New FAQ Category Name', 'betterdocs' ),
			'menu_name'         => __( 'Categories', 'betterdocs' )
		];

		$category_args = [
			'hierarchical'      => true,
			'public'            => false,
			'labels'            => $category_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_rest'      => true,
			'has_archive'       => false,
			'rewrite'           => false,
			'capabilities'      => [
				'manage_terms' => 'manage_doc_terms',
				'edit_terms'   => 'edit_doc_terms',
				'delete_terms' => 'delete_doc_terms',
				'assign_terms' => 'edit_docs'
			]
		];

		register_taxonomy( $this->category, [ $this->post_type ], $category_args );
		register_term_meta( $this->category, 'order', [ 'show_in_rest' => true ] );
		register_term_meta( $this->category, 'status', [ 'show_in_rest' => true ] );
		register_term_meta( $this->category, '_betterdocs_faq_order', [ 'show_in_rest' => true ] );
		register_term_meta( $this->category, 'faq_group_icon', [ 'show_in_rest' => true ]);

		/**
		 * Register post type
		 */
		$labels = [
			'name'               => __( 'BetterDocs FAQ', 'betterdocs' ),
			'singular_name'      => __( 'BetterDocs FAQ', 'betterdocs' ),
			'menu_name'          => __( 'FAQ', 'betterdocs' ),
			'name_admin_bar'     => __( 'FAQ', 'betterdocs' ),
			'add_new'            => __( 'Add New', 'betterdocs' ),
			'add_new_item'       => __( 'Add New FAQ', 'betterdocs' ),
			'new_item'           => __( 'New FAQ', 'betterdocs' ),
			'edit_item'          => __( 'Edit FAQ', 'betterdocs' ),
			'view_item'          => __( 'View FAQ', 'betterdocs' ),
			'all_items'          => __( 'All FAQ', 'betterdocs' ),
			'search_items'       => __( 'Search FAQ', 'betterdocs' ),
			'parent_item_colorn' => null,
			'not_found'          => __( 'No FAQ found', 'betterdocs' ),
			'not_found_in_trash' => __( 'No FAQ found in trash', 'betterdocs' )
		];

		$args = [
			'labels'              => $labels,
			'description'         => __( 'Add new faq from here', 'betterdocs' ),
			'public'              => false,
			'public_queryable'    => true,
			'exclude_from_search' => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'query_var'           => true,
			'capability_type'     => [ 'doc', 'docs' ],
			'hierarchical'        => true,
			'map_meta_cap'        => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'show_in_rest'        => true,
			'menu_icon'           => betterdocs()->assets->icon( 'betterdocs-icon-white.svg' ),
			'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'revisions', 'custom-fields', 'comments' ]
		];

		register_post_type( $this->post_type, $args );
		register_post_meta( $this->post_type, 'faq_open_by_default', [ 'show_in_rest' => true, 'single' => true ] );
	}

	/**
	 * Default the taxonomy's terms' order if it's not set.
	 *
	 * @param string $tax_slug The taxonomy's slug.
	 */
	public function action_created_betterdocs_faq_category( $term_id ) {
		$order = $this->get_max_taxonomy_order( 'betterdocs_faq_category' );
		update_term_meta( $term_id, 'order', $order++ );
		update_term_meta( $term_id, 'status', 1 );
	}

	/**
	 * Default the taxonomy's terms' order if it's not set.
	 *
	 * @param string $tax_slug The taxonomy's slug.
	 */
	public function default_term_order( $tax_slug ) {
		$terms = get_terms(
			[
				'taxonomy'   => $tax_slug,
				'hide_empty' => false,
			]
		);
		$order = $this->get_max_taxonomy_order( $tax_slug );

		foreach ( $terms as $term ) {
			if ( ! get_term_meta( $term->term_id, 'order', true ) ) {
				update_term_meta( $term->term_id, 'order', $order );
				++$order;
			}
		}
	}

	/**
	 * Get the maximum order for this taxonomy. This will be applied to terms that don't have a tax position.
	 */
	private function get_max_taxonomy_order( $tax_slug ) {
		global $wpdb;
        // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.QuotedSimplePlaceholder
		$max_term_order = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT MAX( CAST( tm.meta_value AS UNSIGNED ) )
				FROM $wpdb->terms t
				JOIN $wpdb->term_taxonomy tt ON t.term_id = tt.term_id AND tt.taxonomy = '%s'
				JOIN $wpdb->termmeta tm ON tm.term_id = t.term_id WHERE tm.meta_key = 'order'",
				$tax_slug
			)
		);

		$max_term_order = is_array( $max_term_order ) ? current( $max_term_order ) : 0;

		return (int) $max_term_order === 0 || empty( $max_term_order ) ? 1 : (int) $max_term_order + 1;
	}

	/**
	 * Re-Order the taxonomies based on the order value.
	 *
	 * @param array $pieces     Array of SQL query clauses.
	 * @param array $taxonomies Array of taxonomy names.
	 * @param array $args       Array of term query args.
	 */
	public function set_tax_order( $pieces, $taxonomies, $args ) {
		foreach ( $taxonomies as $taxonomy ) {
			global $wpdb;

			if ( $taxonomy === 'betterdocs_faq_category' ) {
				$join_statement = " LEFT JOIN $wpdb->termmeta AS term_meta ON t.term_id = term_meta.term_id AND term_meta.meta_key = 'order'";

				if ( ! $this->does_substring_exist( $pieces['join'], $join_statement ) ) {
					$pieces['join'] .= $join_statement;
				}

				$pieces['orderby'] = 'ORDER BY CAST( term_meta.meta_value AS UNSIGNED )';
			}
		}

		return $pieces;
	}

	/**
	 * Order the taxonomies on the front end.
	 */
	public function front_end_order_terms() {
		if ( ! is_admin() ) {
			add_filter( 'terms_clauses', [ $this, 'set_tax_order' ], 10, 3 );
		}
	}

	/**
	 * Check if a substring exists inside a string.
	 *
	 * @param string $string    The main string (haystack) we're searching in.
	 * @param string $substring The substring we're searching for.
	 *
	 * @return bool True if substring exists, else false.
	 */
	protected function does_substring_exist( $string, $substring ) {
		return strstr( $string, $substring ) !== false;
	}

	public function register_api_endpoint() {
		register_rest_route(
			$this->namespace,
			'/faq/sample_data',
			[
				'methods'             => [ 'POST' ],
				'callback'            => [ $this, 'create_faq_sample' ],
				'permission_callback' => function () {
					return current_user_can( 'edit_others_posts' );
				}
			]
		);

		register_rest_route(
			$this->namespace,
			'/faq/posts/(?P<type>\S+)',
			[
				'methods'             => [ 'GET' ],
				'callback'            => [ $this, 'fetch_faq_posts' ],
				'permission_callback' => '__return_true'
			]
		);

		register_rest_route(
			$this->namespace,
			'/faq/create_category',
			[
				'methods'             => [ 'POST' ],
				'callback'            => [ $this, 'create_faq_category' ],
				'permission_callback' => function () {
					return current_user_can( 'edit_others_posts' );
				}
			]
		);

		register_rest_route(
			$this->namespace,
			'/faq/update_category',
			[
				'methods'             => [ 'POST' ],
				'callback'            => [ $this, 'update_faq_category' ],
				'permission_callback' => function () {
					return current_user_can( 'edit_others_posts' );
				}
			]
		);

		register_rest_route(
			$this->namespace,
			'/faq/delete_category',
			[
				'methods'             => [ 'POST' ],
				'callback'            => [ $this, 'delete_faq_category' ],
				'permission_callback' => function () {
					return current_user_can( 'edit_others_posts' );
				}
			]
		);

		register_rest_route(
			$this->namespace,
			'/faq/create_post',
			[
				'methods'             => [ 'POST' ],
				'callback'            => [ $this, 'create_betterdocs_faq' ],
				'permission_callback' => function () {
					return current_user_can( 'edit_others_posts' );
				}
			]
		);

		register_rest_route(
			$this->namespace,
			'/faq/update_post',
			[
				'methods'             => [ 'POST' ],
				'callback'            => [ $this, 'update_betterdocs_faq' ],
				'permission_callback' => function () {
					return current_user_can( 'edit_others_posts' );
				}
			]
		);

		register_rest_route(
			$this->namespace,
			'/faq/delete_post',
			[
				'methods'             => [ 'POST' ],
				'callback'            => [ $this, 'delete_betterdocs_faq' ],
				'permission_callback' => function () {
					return current_user_can( 'edit_others_posts' );
				}
			]
		);

		register_rest_route(
			$this->namespace,
			'/faq/category_status',
			[
				'methods'             => [ 'POST' ],
				'callback'            => [ $this, 'update_category_status' ],
				'permission_callback' => function () {
					return current_user_can( 'edit_others_posts' );
				}
			]
		);

		register_rest_route(
			$this->namespace,
			'/faq/category_order',
			[
				'methods'             => [ 'POST' ],
				'callback'            => [ $this, 'update_faq_category_order' ],
				'permission_callback' => function () {
					return current_user_can( 'edit_others_posts' );
				}
			]
		);

		register_rest_route(
			$this->namespace,
			'/faq/update_order_by_category',
			[
				'methods'             => [ 'POST' ],
				'callback'            => [ $this, 'update_faq_order_by_category' ],
				'permission_callback' => function () {
					return current_user_can( 'edit_others_posts' );
				}
			]
		);

		register_rest_route(
			$this->namespace,
			'/faq/uncategorised',
			[
				'methods'             => [ 'GET' ],
				'callback'            => [ $this, 'get_uncategorised_faq' ],
				'permission_callback' => '__return_true'
			]
		);

		register_rest_route(
			$this->namespace,
			'/faq/category_search',
			[
				'methods'             => [ 'GET' ],
				'callback'            => [ $this, 'category_search' ],
				'permission_callback' => '__return_true',
				'args'                => [
					'title' => [
						'type'     => 'string',
						'required' => true
					]
				]
			]
		);
	}

	public function create_faq_sample( $params ) {
		$sample_data = json_decode( $params->get_param( 'sample_data' ), true );
		foreach ( $sample_data as $key => $value ) {
			$insert_term = wp_insert_term(
				$key,
				'betterdocs_faq_category'
			);
			if ( $insert_term ) {
				foreach ( $value['posts'] as $key => $value ) {
					$this->insert_betterdocs_faq( $value['post_title'], $value['post_content'], $insert_term['term_id'] );
				}
			}
		}
		return true;
	}

	public function create_faq_category( $params ) {
		$title       = $params->get_param( 'title' );
		$description = $params->get_param( 'description' );
		$description = ( $description !== 'undefined' ) ? $description : '';
		$slug        = $params->get_param( 'slug' );
		$group_icon_url = $params->get_param('group_icon_url');
		return $this->insert_betterdocs_faq_category( $title, $description, $group_icon_url, $slug );
	}

	public function update_faq_category( $params ) {
		$term_id     = $params->get_param( 'term_id' );
		$title       = $params->get_param( 'title' );
		$description = $params->get_param( 'description' );
		$group_icon_url = $params->get_param('group_icon_url');
		$description = ( $description !== 'undefined' ) ? $description : '';
		$slug        = $params->get_param( 'slug' );
		$update      = wp_update_term(
			$term_id,
			'betterdocs_faq_category',
			[
				'name'        => $title,
				'slug'        => $slug,
				'description' => $description
			]
		);

		if ( is_wp_error( $update ) ) {
			return $update;
		} else {
			$term_id = isset( $update['term_id'] ) ?  $update['term_id']  : 0;
			if( $term_id != 0 ) {
				$previous_icon_url = get_term_meta($term_id, 'faq_group_icon', true);
				update_term_meta($term_id, 'faq_group_icon', $group_icon_url, $previous_icon_url);
			}
			return true;
		}
	}

    public function delete_faq_category( $params ) {
        $term_id = $params->get_param( 'term_id' );
        $delete_all_docs = $params->get_param('with_all_post');

        if ( $delete_all_docs ) {
            Helper::delete_specific_faq_posts_by_faq_category( $term_id );
        }

        $delete  = wp_delete_term( $term_id, 'betterdocs_faq_category' );

		if ( is_wp_error( $delete ) ) {
			return $delete;
		} else {
			return true;
		}
	}

	public function insert_betterdocs_faq_category( $title, $description, $icon_url, $slug = '' ) {
		$insert_term = wp_insert_term(
			$title,
			'betterdocs_faq_category',
			[
				'slug'        => $slug,
				'description' => $description
			]
		);

		if ( is_wp_error( $insert_term ) ) {
			return $insert_term;
		} else {
			$term_id = isset( $insert_term['term_id'] ) ?  $insert_term['term_id']  : 0;
			if( $term_id != 0 ) {
				update_term_meta($term_id, 'faq_group_icon', $icon_url);
			}
			return true;
		}
	}

	public function update_faq_category_order( $params ) {
		$faq_category_order = $params->get_param( 'faq_category_order' );
		$faq_category_order = json_decode( $faq_category_order, true );

		foreach ( $faq_category_order as $order_data ) {
			if ( (int) $order_data['current_position'] != (int) $order_data['updated_position'] ) {
				update_term_meta( $order_data['id'], 'order', ( (int) $order_data['updated_position'] ) );
			}
		}
		return true;
	}

	public function insert_betterdocs_faq( $post_title, $post_content, $term_id ) {
		$post = wp_insert_post(
			[
				'post_type'    => 'betterdocs_faq',
				'post_title'   => wp_strip_all_tags( $post_title ),
				'post_content' => $post_content,
				'post_status'  => 'publish'
			]
		);

		if ( $term_id ) {
			$set_terms = wp_set_object_terms( $post, $term_id, 'betterdocs_faq_category' );
			if ( is_wp_error( $set_terms ) ) {
				return $set_terms;
			} else {
				return $this->update_faq_order_on_insert( $term_id, $post );
			}
		} else {
			return $post;
		}
	}

	public function update_faq_order_on_insert( $term_id, $post ) {
		$term_meta = get_term_meta( $term_id, '_betterdocs_faq_order' );
		if ( ! empty( $term_meta ) ) {
			$term_meta_arr = explode( ',', $term_meta[0] );
			if ( ! in_array( $post, $term_meta_arr ) ) {
				array_unshift( $term_meta_arr, $post );
				$docs_ordering_data = filter_var_array( wp_unslash( $term_meta_arr ), FILTER_SANITIZE_NUMBER_INT );
				return update_term_meta( $term_id, '_betterdocs_faq_order', implode( ',', $docs_ordering_data ) );
			}
		} else {
			return update_term_meta( $term_id, '_betterdocs_faq_order', $post );
		}
	}

	/**
	 * Update _betterdocs_faq_order meta when new post created
	 */

	public function update_faq_order_by_category( $params ) {
		$term_id = $params->get_param( 'term_id' );
		$posts   = $params->get_param( 'posts' );
		return update_term_meta( $term_id, '_betterdocs_faq_order', $posts );
	}

	public function create_betterdocs_faq( $params ) {
		$post_title   = $params->get_param( 'post_title' );
		$post_content = $params->get_param( 'post_content' );
		$term_id      = $params->get_param( 'term_id' );
		return $this->insert_betterdocs_faq( $post_title, $post_content, $term_id );
	}

	public function update_betterdocs_faq( $params ) {
		$post_id      = $params->get_param( 'post_id' );
		$post_title   = $params->get_param( 'post_title' );
		$post_content = $params->get_param( 'post_content' );
		$status       = $params->get_param( 'status' );
		$term_id      = $params->get_param( 'term_id' );
		if ( $status ) {
			$data = [
				'post_type' => 'betterdocs_faq',
				'ID'        => $post_id,
				'status'    => $status
			];
		} else {
			$data = [
				'post_type'    => 'betterdocs_faq',
				'ID'           => $post_id,
				'post_title'   => $post_title,
				'post_content' => $post_content
			];

			if ( $term_id ) {
				$data['tax_input'] = [
					'betterdocs_faq_category' => $term_id
				];

				$term_meta     = get_term_meta( $term_id, '_betterdocs_faq_order' );
				$term_meta_arr = explode( ',', $term_meta[0] );
				if ( ! in_array( $post_id, $term_meta_arr ) ) {
					array_unshift( $term_meta_arr, $post_id );
					$docs_ordering_data = filter_var_array( wp_unslash( $term_meta_arr ), FILTER_SANITIZE_NUMBER_INT );
					update_term_meta( $term_id, '_betterdocs_faq_order', implode( ',', $docs_ordering_data ) );
				}
			}
		}

		return wp_update_post( $data );
	}

	public function delete_betterdocs_faq( $params ) {
		$post_id = $params->get_param( 'post_id' );
		return wp_delete_post( $post_id );
	}

	public function faq_post_loop( $args ) {
		$posts = [];
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) :
				$query->the_post();
				$posts[ get_the_ID() ]['title']   = get_the_title();
				$posts[ get_the_ID() ]['content'] = get_the_content();
			endwhile;
		endif;

		return $posts;
	}

	public function update_category_status( $params ) {
		$term_id = $params->get_param( 'term_id' );
		$status  = $params->get_param( 'status' );
		return update_term_meta( $term_id, 'status', $status );
	}

	public function fetch_faq_posts( $params ) {
		$faq  = [];
		$type = $params->get_param( 'type' );

		if ( $type == 'category' ) {
			$taxonomy_objects = get_terms(
				[
					'taxonomy'   => 'betterdocs_faq_category',
					'hide_empty' => false
				]
			);

			if ( $taxonomy_objects && ! is_wp_error( $taxonomy_objects ) ) :
				foreach ( $taxonomy_objects as $term ) :
					$args = [
						'post_type'     => 'betterdocs_faq',
						'post_status'   => 'publish',
						'post_per_page' => -1,
						'tax_query'     => [
							[
								'taxonomy' => 'betterdocs_faq_category',
								'field'    => 'term_id',
								'terms'    => $term->term_id
							]
						]
					];

					$posts = $this->faq_post_loop( $args );

					$faq[ $term->slug ] = [
						(array) $term,
						'posts' => $posts
					];
				endforeach;
			endif;
		} else {
			$args         = [
				'post_type'     => 'betterdocs_faq',
				'post_status'   => 'publish',
				'post_per_page' => -1
			];
			$posts        = $this->faq_post_loop( $args );
			$faq['posts'] = $posts;
		}

		return $faq;
	}

	public function get_uncategorised_faq() {
		$available_terms = array_map(
			function ( $term ) {
				return $term->term_id;
			},
			get_terms(
				[
					'taxonomy'   => 'betterdocs_faq_category',
					'hide_empty' => false
				]
			)
		);

		return get_posts(
			[
				'post_type'      => 'betterdocs_faq',
				'post_status'    => [ 'publish', 'draft' ],
				'posts_per_page' => -1,
				'tax_query'      => [
					'relation' => 'AND',
					[
						'taxonomy' => 'betterdocs_faq_category',
						'field'    => 'term_id',
						'terms'    => $available_terms,
						'operator' => 'NOT IN'
					]
				]
			]
		);
	}

	public function category_search( $request ) {

		$title = $request['title'];

		// Perform the taxonomy search
		$taxonomy_args = [
			'name__like' => $title,
			'taxonomy'   => 'betterdocs_faq_category',
			'hide_empty' => false
		];

		$taxonomies = get_terms( $taxonomy_args );

		if ( ! empty( $taxonomies ) ) {
			$result = [];
			foreach ( $taxonomies as $taxonomy ) {
				$result[] = [
					'id'          => $taxonomy->term_id,
					'count'       => $taxonomy->count,
					'description' => $taxonomy->description,
					'name'        => $taxonomy->name,
					'slug'        => $taxonomy->slug
					// Add more fields as needed
				];
			}
			// Return the taxonomy data
			return $result;
		} else {
			// Taxonomy not found
			return new WP_Error( 'taxonomy_not_found', 'Taxonomy not found.', [ 'status' => 404 ] );
		}
	}

	public function faq_category_orderby_meta( $args, $request ) {
		if ( $args['taxonomy'] === 'betterdocs_faq_category' ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = 'order';
		}
		return $args;
	}
}
