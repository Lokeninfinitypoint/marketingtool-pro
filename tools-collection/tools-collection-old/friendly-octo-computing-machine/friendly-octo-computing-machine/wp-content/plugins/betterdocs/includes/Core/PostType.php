<?php

namespace WPDeveloper\BetterDocs\Core;

use WP_Post;
use WPDeveloper\BetterDocs\Utils\Base;
use WPDeveloper\BetterDocs\Utils\Database;
use WPDeveloper\BetterDocs\Dependencies\DI\Container;

class PostType extends Base {
	public $post_type  = 'docs';
	public $position   = 5;
	public $category   = 'doc_category';
	public $glossaries = 'glossaries';
	public $tag        = 'doc_tag';

	public $docs_archive;
	public $docs_slug;
	public $cat_slug;
	public $glossaries_slug;
	/**
	 * Database
	 * @var Database
	 */
	private $database = null;

	/**
	 * Summary of Settings
	 * @var Settings
	 */
	private $settings = null;

	/**
	 * Rewrite class
	 * @var Rewrite
	 */
	private $rewrite = null;

	/**
	 * Initially Invoked Functions
	 * @since 2.5.0
	 *
	 * @param Container $container
	 */
	public function __construct( Container $container ) {
		$this->database = $container->get( Database::class );
		$this->settings = $container->get( Settings::class );
		$this->rewrite  = $container->get( Rewrite::class );

		$this->docs_archive    = $this->docs_slug();
		$this->docs_slug       = $this->docs_slug();
		$this->cat_slug        = $this->category_slug();
		$this->glossaries_slug = $this->glossaries_slug();

		add_action( "{$this->glossaries}_add_form_fields", [ $this, 'add_glossary_term_fields' ] );
		add_action( "{$this->glossaries}_edit_form_fields", [ $this, 'edit_glossary_term_fields' ] );
		add_action( "created_{$this->glossaries}", [ $this, 'save_glossary_term_fields' ] );
		add_action( "edited_{$this->glossaries}", [ $this, 'save_glossary_term_fields' ] );
		add_filter( "manage_edit-{$this->glossaries}_columns", [ $this, 'add_glossary_custom_column' ] );
		add_filter( "manage_{$this->glossaries}_custom_column", [ $this, 'manage_glossary_custom_column' ], 10, 3 );

		// Hide default description field for glossaries taxonomy
		add_action( 'admin_head', [ $this, 'hide_glossaries_default_description' ] );
	}

	public static function permalink_structure() {
		return apply_filters( 'betterdocs_doc_permalink_default', ( self::get_instance( betterdocs()->container ) )->docs_slug() );
	}

	public function init() {
		add_filter( 'post_type_link', [ $this, 'post_link' ], 1, 3 );
		add_filter( 'rest_docs_collection_params', [ $this, 'add_rest_orderby_params' ], 10, 1 );
		add_filter( 'rest_doc_category_collection_params', [ $this, 'add_rest_orderby_params_on_doc_category' ], 10, 1 );
		add_filter( 'rest_doc_category_query', [ $this, 'modify_doc_category_rest_query' ], 10, 2 );
		add_action( 'before_delete_post', [ $this, 'delete_analytics_rows_on_post_delete' ], 10, 1 );
		add_filter('rest_prepare_doc_category', [$this, 'modify_term_response'], 10, 3); //modify rest api doc category term count when nested_subcategory is enabled
		if( $this->settings->get( 'enable_category_hierarchy_slugs' ) ) { // reigster hierarchy based slug rewrite rule, for doc category
			add_filter('betterdocs_category_rewrite', [$this, 'enable_nested_hierarchy_doc_category_slug'], 10, 1);
		}
	}

	public function modify_term_response( $response, $item, $request ){
		if( $request->get_param('nested_subcategory') != null && $request->get_param('nested_subcategory') ) {
			$response->data['count'] = betterdocs()->query->get_docs_count(
				$item,
				$request->get_param('nested_subcategory'),
				[
					'multiple_knowledge_base' => false,
					'kb_slug'                 => ''
				]
			);
		}
		return $response;

	}

	public function enable_nested_hierarchy_doc_category_slug($doc_category_rewrite_payload) {
		$doc_category_rewrite_payload['hierarchical'] = true;
		return $doc_category_rewrite_payload;
	}

	/**
	 * Add menu_order param to the list of rest api orderby values
	 */
	public function add_rest_orderby_params( $params ) {
		$params['orderby']['enum'][] = 'menu_order';
		return $params;
	}

	/**
	 * Add doc_category_order param to the list of rest api orderby values on doc_category taxonomy
	 */
	public function add_rest_orderby_params_on_doc_category( $params ) {
		$params['orderby']['enum'][] = 'doc_category_order';
		return $params;
	}

	/**
	 * Modify doc_category rest query for doc_category_order meta key
	 */
	public function modify_doc_category_rest_query( $args, $request ) {
		$order_by = $request->get_param( 'orderby' );
		if ( isset( $order_by ) && 'doc_category_order' === $order_by ) {
			$args['meta_key'] = $order_by;
			$args['orderby']  = 'meta_value_num';
		}
		return $args;
	}

	public function post_link( $url, $post, $leavename = false ) {
		if ( 'docs' != get_post_type( $post ) ) {
			return $url;
		}

		$cat_terms = wp_get_object_terms( $post->ID, 'doc_category' );

		if( is_array( $cat_terms ) && ! empty( $cat_terms ) && $this->settings->get( 'enable_category_hierarchy_slugs' ) ) { //if nested slug is enabled, render this
			$doccat_terms = [];

			foreach( $cat_terms as $term ) {
				$process_term = $term;
				array_unshift( $doccat_terms, $term->slug );
				while( $process_term->parent != 0 ) {
					$parent_term = get_term($process_term->parent);
					array_unshift($doccat_terms, $parent_term->slug);
					$process_term = $parent_term;
				}
			}

			$doccat_terms = implode('/', $doccat_terms);
		} else if ( is_array( $cat_terms ) && ! empty( $cat_terms ) ) {
			$doccat_terms = $cat_terms[0]->slug;
		} else {
			$doccat_terms = 'uncategorized';
		}

		$url = str_replace( '%doc_category%', $doccat_terms, $url );
		return apply_filters( 'betterdocs_post_type_link', $url, $post, $leavename );
	}

	public function ajax() {
		/**
		 * All kind of ajax related to post type: docs
		 * for admin side.
		 */
		add_action( 'wp_ajax_update_doc_cat_order', [ $this, 'update_category_order' ] );
		add_action( 'wp_ajax_update_doc_order_by_category', [ $this, 'update_docs_order_by_category' ] );
		add_action( 'wp_ajax_update_docs_term', [ $this, 'update_docs_term' ] );
	}

	public function admin_init() {
		$this->ajax();

		add_action( 'new_to_auto-draft', [ $this, 'auto_add_category' ] );
		add_action( 'save_post_docs', [ $this, 'save_docs' ] );
		add_action( 'rest_after_insert_docs', [ $this, 'save_docs' ] );

		// Doc Category Taxonomy EXTRA Fields
		add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ] );

		if ( ! is_admin() ) {
			return;
		}

		add_action( 'transition_post_status', [ $this, 'clear_docs_object_cache' ], 10, 3 );
		add_action( 'doc_category_add_form_fields', [ $this, 'add_form_fields' ], 10, 2 );
		add_action( 'doc_category_edit_form_fields', [ $this, 'edit_form_fields' ], 10, 2 );
		add_action( 'created_doc_category', [ $this, 'save_category_meta' ], 11, 2 );
		add_action( 'edited_doc_category', [ $this, 'updated_category_meta' ], 11, 2 );

		// Order the terms on the admin side.
		add_action( 'admin_head', [ $this, 'order_terms' ] );
	}

	public function scripts( $hook ) {
		$current_screen = get_current_screen();
		if ( isset( $current_screen->id ) && $current_screen->id !== 'edit-doc_category' ) {
			return;
		}

		wp_enqueue_media();

		betterdocs()->assets->enqueue( 'betterdocs-category-edit', 'admin/js/category-edit.js' );

		betterdocs()->assets->localize(
			'betterdocs-category-edit',
			'betterdocsCategorySorting',
			[
				'action'      => 'update_doc_cat_order',
				'selector'    => '.taxonomy-doc_category',
				'ajaxurl'     => admin_url( 'admin-ajax.php' ),
				'nonce'       => wp_create_nonce( 'doc_cat_order_nonce' ),
				'paged'       => isset( $_GET['paged'] ) &&
								isset( $_GET['nonce'] ) &&
								wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'doc_cat_order_nonce' )
								? absint( wp_unslash( $_GET['paged'] ) ) : 0,
				'per_page_id' => "edit_{$current_screen->taxonomy}_per_page"
			]
		);
	}

	/**
	 * Function to clear object cache when a new 'docs' post is published.
	 */
	public function clear_docs_object_cache( $new_status, $old_status, $post ) {
		if ( $post->post_type === 'docs' && $new_status === 'publish' ) {
			wp_cache_flush();
		}
	}

	public function add_form_fields( $taxonomy ) {
		betterdocs()->views->get( 'admin/taxonomy/add' );
	}

	public function edit_form_fields( $term, $taxonomy ) {
		$term_meta   = get_option( "doc_category_$term->term_id" );
		$cat_order   = get_term_meta( $term->term_id, 'doc_category_order', true );
		$cat_icon_id = get_term_meta( $term->term_id, 'doc_category_image-id', true );

		betterdocs()->views->get(
			'admin/taxonomy/edit',
			[
				'term'    => $term,
				'meta'    => $term_meta,
				'order'   => $cat_order,
				'icon_id' => $cat_icon_id
			]
		);
	}

	/**
	 * Save custom meta data for the category when a term is added.
	 * Meta data is saved from $_POST['term_meta'] array.
	 * If 'doc_category_kb' is set in $_POST, it updates 'doc_category_knowledge_base' meta data.
	 * It also sets the term order using set_term_order function.
	 * @param int $term_id The ID of the term being saved.
	 * @param int $tt_id The term taxonomy ID.
	 */
	public function save_category_meta( $term_id, $tt_id ) {
		// Ensure the current user has capabilities to edit terms
		if ( ! current_user_can( 'manage_doc_terms' ) ) {
			return;
		}

        // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['term_meta'] ) && is_array( $_POST['term_meta'] ) ) {
			// Sanitize the input array
			$term_meta = array_map( 'sanitize_text_field', wp_unslash( $_POST['term_meta'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			$cat_keys = array_keys( $term_meta );
			foreach ( $cat_keys as $key ) {
				if ( isset( $term_meta[ $key ] ) ) {
					add_term_meta( $term_id, "doc_category_$key", $term_meta[ $key ] );
				}
			}
		}

		// @todo PRO
		if ( isset( $_POST['doc_category_kb'] ) && is_array( $_POST['doc_category_kb'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$doc_category_kb = array_map( 'sanitize_text_field', wp_unslash( $_POST['doc_category_kb'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			// Update the term meta with the sanitized array
			update_term_meta( $term_id, 'doc_category_knowledge_base', $doc_category_kb );
		}

		// Default the taxonomy's terms' order if it's not set.
		$this->set_term_order( $term_id, $tt_id );
	}

	/**
	 * Set the term order when a new term is created.
	 * If the term has a parent, updates the doc_category_order based on the parent's order.
	 * Otherwise, assigns the maximum doc_category_order among all terms plus one.
	 */
	public function set_term_order( $term_id, $tt_id ) {
		// Verify term exists and is of the correct taxonomy
		$term = get_term( $term_id, 'doc_category' );

		// Bail if term is invalid
		if ( is_wp_error( $term ) || ! $term ) {
			return;
		}

		if ( $term->parent !== 0 ) {
			$this->update_doc_category_order_by_parent( $term_id, $term->parent );
		} else {
			$max_order = $this->get_max_taxonomy_order( 'doc_category' );
			update_term_meta( $term_id, 'doc_category_order', $max_order );
		}
	}

	/**
	 * Update category meta data when a term is updated.
	 * Updates custom term meta data such as 'doc_category_kb'.
	 * If the parent term is changed, updates the doc_category_order based on the new parent's order.
	 * @param int $term_id The ID of the term being updated.
	 */
	public function updated_category_meta( $term_id ) {
		$term              = get_term( $term_id, 'doc_category' );
		$current_parent_id = $term->parent;
		// Update custom meta data from $_POST['term_meta'] array
		if ( isset( $_POST['term_meta'] ) && is_array( $_POST['term_meta'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$term_meta = array_map( 'sanitize_text_field', wp_unslash( $_POST['term_meta'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$cat_keys  = array_keys( $term_meta );
			foreach ( $cat_keys as $key ) {
				if ( isset( $term_meta[ $key ] ) ) {
					update_term_meta( $term_id, "doc_category_$key", $term_meta[ $key ] );
				}
			}
		}

		// Update 'doc_category_knowledge_base' meta data if 'doc_category_kb' is set in $_POST
		if ( isset( $_POST['doc_category_kb'] ) && is_array( $_POST['doc_category_kb'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$doc_category_kb = array_map( 'sanitize_text_field', wp_unslash( $_POST['doc_category_kb'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			update_term_meta( $term_id, 'doc_category_knowledge_base', $doc_category_kb );
		}
	}

	/**
	 * Update the doc_category_order for a term based on its parent's order.
	 */
	public function update_doc_category_order_by_parent( $term_id, $term_parent_id ) {
		// Verify user capabilities
		if ( ! current_user_can( 'manage_doc_terms' ) ) {
			return;
		}

		// Get the parent's order or default to 1
		$parent_order = (int) get_term_meta( $term_parent_id, 'doc_category_order', true );

		if ( $parent_order === 0 ) {
			$parent_order = $this->get_max_taxonomy_order( 'doc_category', $term_parent_id );
		}

		$order = $parent_order + 1;
		update_term_meta( $term_id, 'doc_category_order', (int) $order );
	}

	/**
	 * Get the maximum doc_category_order for this taxonomy.
	 * If $parent_term_id is provided, it retrieves the maximum order under that parent.
	 * Otherwise, it retrieves the maximum order among all terms.
	 * @param string $tax_slug The taxonomy slug.
	 * @param int|null $parent_term_id The parent term ID (optional).
	 * @return int The maximum doc_category_order.
	 */
	private function get_max_taxonomy_order( $tax_slug, $parent_term_id = null ) {
		global $wpdb;

		// Prepare the table names safely
		$terms_table         = $wpdb->terms;
		$term_taxonomy_table = $wpdb->term_taxonomy;
		$termmeta_table      = $wpdb->termmeta;

		if ( $parent_term_id !== null ) {
			// Query with parent_term_id
			$query          = "
                SELECT MAX(CAST(tm.meta_value AS UNSIGNED))
                FROM {$terms_table} AS t
                INNER JOIN {$term_taxonomy_table} AS tt ON t.term_id = tt.term_id
                INNER JOIN {$termmeta_table} AS tm ON tm.term_id = t.term_id
                WHERE tt.taxonomy = %s
                AND tm.meta_key = 'doc_category_order'
                AND tt.parent = %d
            ";
            $max_term_order = $wpdb->get_var( $wpdb->prepare( $query, $tax_slug, $parent_term_id ) ); // phpcs:ignore
		} else {
			// Query without parent_term_id
			$query          = "
                SELECT MAX(CAST(tm.meta_value AS UNSIGNED))
                FROM {$terms_table} AS t
                INNER JOIN {$term_taxonomy_table} AS tt ON t.term_id = tt.term_id
                INNER JOIN {$termmeta_table} AS tm ON tm.term_id = t.term_id
                WHERE tt.taxonomy = %s
                AND tm.meta_key = 'doc_category_order'
            ";
            $max_term_order = $wpdb->get_var( $wpdb->prepare( $query, $tax_slug ) ); // phpcs:ignore
		}

		// Return the result as an integer, defaulting to 1 if no results found
		return (int) $max_term_order === 0 || empty( $max_term_order ) ? 1 : (int) $max_term_order + 1;
	}

	/**
	 * Summary of order_terms
	 * @return void
	 */
	public function order_terms() {
		global $current_screen;
		$screen_id = isset( $current_screen->id ) ? $current_screen->id : '';

		if ( in_array( $screen_id, [ 'betterdocs_page_betterdocs-admin', 'betterdocs_page_betterdocs-settings', 'admin_page_betterdocs-admin' ] ) ) {
			$this->default_term_order( 'doc_category' );
		}

        if ( ! isset( $_GET['orderby'] ) && ! empty( $current_screen->base ) && $current_screen->base === 'edit-tags' && $current_screen->taxonomy === 'doc_category' ) { // phpcs:ignore
			$this->default_term_order( $current_screen->taxonomy );
			add_filter( 'terms_clauses', [ $this, 'set_tax_order' ], 10, 3 );
		}
	}

	/**
	 * Default the taxonomy's terms' order if it's not set.
	 *
	 * @param string $tax_slug The taxonomy's slug.
	 */
	private function default_term_order( $tax_slug ) {
		$terms = get_terms(
			[
				'taxonomy'   => $tax_slug,
				'hide_empty' => false,
			]
		);

		$order = $this->get_max_taxonomy_order( $tax_slug );

		if ( ! is_array( $terms ) ) {
			return;
		}

		foreach ( $terms as $term ) {
			if ( ! get_term_meta( $term->term_id, 'doc_category_order', true ) ) {
				update_term_meta( $term->term_id, 'doc_category_order', $order );
				++$order;
			}
		}
	}

	/**
	 * Re-Order the taxonomies based on the doc_category_order value.
	 *
	 * @param array $pieces     Array of SQL query clauses.
	 * @param array $taxonomies Array of taxonomy names.
	 * @param array $args       Array of term query args.
	 */
	public function set_tax_order( $pieces, $taxonomies, $args ) {
		global $wpdb;

		foreach ( $taxonomies as $taxonomy ) {
			if ( $taxonomy === 'doc_category' ) {
				$join_statement = " LEFT JOIN $wpdb->termmeta AS term_meta ON t.term_id = term_meta.term_id AND term_meta.meta_key = 'doc_category_order'";

				if ( ! $this->does_substring_exist( $pieces['join'], $join_statement ) ) {
					$pieces['join'] .= $join_statement;
				}

				$pieces['orderby'] = 'ORDER BY CAST( term_meta.meta_value AS UNSIGNED )';
			}
		}

		return $pieces;
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

	/**
	 * Auto Add in Category, Adding from Sorting
	 *
	 * @param \WP_Post $post
	 * @return void
	 */
	public function auto_add_category( $post ) {
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			// Unslash and sanitize the REQUEST_URI
			$request_uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );

			if ( strpos( $request_uri, 'wp-admin/post-new.php' ) === false ) {
				return;
			}
		} else {
			return;
		}

        if ( empty( $_GET['cat'] ) ) { // phpcs:ignore
			return;
		}

		// Sanitize and unslash the 'cat' parameter
        $cat = sanitize_text_field( wp_unslash( $_GET['cat'] ) ); // phpcs:ignore
		if ( false === ( $cat = get_term_by( 'term_id', $cat, 'doc_category' ) ) ) {
			return;
		}

		wp_set_post_terms( $post->ID, [ $cat->term_id ], 'doc_category', false );
	}

	public function update_category_order() {
		if ( ! check_ajax_referer( 'doc_cat_order_nonce', 'nonce', false ) ) {
			wp_send_json_error( __( 'Nonce Failed', 'betterdocs' ) );
		}

		if ( ! current_user_can( 'manage_doc_terms' ) ) {
			wp_send_json_error( __( 'You don\'t have permission to manage docs term.', 'betterdocs' ) );
		}

		wp_cache_flush();

		if ( isset( $_POST['data'] ) && is_array( $_POST['data'] ) ) {
			// Sanitize and validate each element in the array
			$taxonomy_ordering_data = array_map(
				function ( $item ) {
					if ( is_array( $item ) && isset( $item['term_id'], $item['order'] ) ) {
							return [
								'term_id' => intval( $item['term_id'] ),
								'order'   => intval( $item['order'] ),
							];
					}
					return null; // Discard invalid items
        }, wp_unslash( $_POST['data'] ) ); // phpcs:ignore

			// Remove any null entries
			$taxonomy_ordering_data = array_filter( $taxonomy_ordering_data );
		} else {
			$taxonomy_ordering_data = []; // Default to an empty array if not set
		}

		if ( isset( $_POST['base_index'] ) ) {
            $base_index = intval( $_POST['base_index'] ); //phpcs:ignore
		} else {
			$base_index = 0; // Default to 0 if not set
		}

		foreach ( $taxonomy_ordering_data as $order_data ) {
			// Ensure $order_data is an array with required keys
			if ( is_array( $order_data ) && isset( $order_data['term_id'], $order_data['order'] ) ) {
				if ( $base_index > 0 ) {
					$current_position = get_term_meta( $order_data['term_id'], 'doc_category_order', true );

					if ( (int) $current_position < (int) $base_index ) {
						continue;
					}
				}

				// Update term meta with sanitized and validated values
				update_term_meta( $order_data['term_id'], 'doc_category_order', ( (int) $order_data['order'] + (int) $base_index ) );
			}
		}

		wp_send_json_success( __( 'Successfully updated.', 'betterdocs' ) );
	}

	/**
	 * AJAX Handler to update docs position.
	 */
	public function update_docs_order_by_category() {
		if ( ! check_ajax_referer( 'doc_cat_order_nonce', 'doc_cat_order_nonce', false ) ) {
			wp_send_json_error( __( 'Nonce Failed', 'betterdocs' ) );
		}

		if ( ! current_user_can( 'edit_docs' ) ) {
			wp_send_json_error( __( 'You don\'t have permission to update docs term.', 'betterdocs' ) );
		}

		wp_cache_flush();

		if ( isset( $_POST['docs_ordering_data'] ) && is_array( $_POST['docs_ordering_data'] ) ) {
			// Unslash and sanitize each element in the array
			$docs_ordering_data = implode( ',', array_map( 'intval', array_map( 'sanitize_text_field', wp_unslash( $_POST['docs_ordering_data'] ) ) ) );
		} else {
			$docs_ordering_data = ''; // Default to an empty string if not set
		}

		$term_id = isset( $_POST['list_term_id'] ) ? intval( wp_unslash( $_POST['list_term_id'] ) ) : 0;

		if ( ! $term_id ) {
			wp_send_json_error( __( 'Invalid term ID.', 'betterdocs' ) );
		}

		if ( update_term_meta( $term_id, '_docs_order', $docs_ordering_data ) ) {
			wp_send_json_success( __( 'Successfully updated.', 'betterdocs' ) );
		}

		wp_send_json_error( __( 'Something went wrong.', 'betterdocs' ) );
	}

	/**
	 * AJAX Handler to update docs position.
	 */
	public function update_docs_term() {
		if ( ! check_ajax_referer( 'doc_cat_order_nonce', 'doc_cat_order_nonce', false ) ) {
			wp_send_json_error( __( 'Nonce Failed', 'betterdocs' ) );
		}

		if ( ! current_user_can( 'edit_docs' ) ) {
			wp_send_json_error( __( 'You don\'t have permission to update docs term.', 'betterdocs' ) );
		}

		$object_id    = isset( $_POST['object_id'] ) ? intval( wp_unslash( $_POST['object_id'] ) ) : 0;
		$term_id      = isset( $_POST['list_term_id'] ) ? intval( wp_unslash( $_POST['list_term_id'] ) ) : 0;
		$prev_term_id = isset( $_POST['prev_term_id'] ) ? intval( wp_unslash( $_POST['prev_term_id'] ) ) : 0;

		if ( ! $term_id || ! $object_id ) {
			wp_send_json_error( __( 'Invalid object or term ID.', 'betterdocs' ) );
		}

		global $wpdb;

		if ( $prev_term_id ) {
			wp_remove_object_terms( $object_id, $prev_term_id, 'doc_category' );
		}

		// Check if the doc has more than 0 terms assigned to it after unassignment above.
		// This ensures other terms except the current term do not lose the current doc assignment.
		$extra_terms_of_doc = wp_get_post_terms( $object_id, 'doc_category', [ 'fields' => 'ids' ] );

		if ( count( $extra_terms_of_doc ) > 0 ) {
			$term_id .= ',' . implode( ',', $extra_terms_of_doc );
		}

		$terms_added = wp_set_post_terms( $object_id, $term_id, 'doc_category' );

		if ( ! is_wp_error( $terms_added ) ) {
			wp_send_json_success( __( 'Successfully updated.', 'betterdocs' ) );
		}

		wp_send_json_error( __( 'Something went wrong.', 'betterdocs' ) );
	}

	/**
	 * Update docs_term meta when new post created
	 */
	public function save_docs( $post_id ) {
		// bail out if this is an autosave
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( $post_id instanceof WP_Post ) {
			$post_id = $post_id->ID;
		}

		$term_list = wp_get_post_terms( $post_id, 'doc_category', [ 'fields' => 'ids' ] );

		//save estimated reading text in post
		$est_reading_text = isset( $_POST['estimated_reading_text'] ) ? sanitize_text_field( wp_unslash( $_POST['estimated_reading_text'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		update_post_meta( $post_id, '_betterdocs_est_reading_text', $est_reading_text );

		if ( ! empty( $term_list ) ) {
			foreach ( $term_list as $term_id ) {
				$term_meta = get_term_meta( $term_id, '_docs_order', true );
				if ( ! empty( $term_meta ) ) {
					$_term_meta_array = explode( ',', $term_meta );

					if ( ! in_array( $post_id, $_term_meta_array ) ) {
						array_unshift( $_term_meta_array, $post_id );
						$_docs_order_data = filter_var_array( wp_unslash( $_term_meta_array ), FILTER_SANITIZE_NUMBER_INT );
						update_term_meta( $term_id, '_docs_order', implode( ',', $_docs_order_data ) );
					}
				} else {
					update_term_meta( $term_id, '_docs_order', implode( ',', [ $post_id ] ) );
				}
			}
		}
	}

	public function register() {
		/**
		 * Flush Rewrite Rules
		 */
		if ( $this->database->get_transient( 'betterdocs_flush_rewrite_rules' ) ) {
			betterdocs()->rewrite->rules();

			flush_rewrite_rules();
			$this->database->delete_transient( 'betterdocs_flush_rewrite_rules' );
		}

		$this->register_post_type();
		$this->register_category_taxonomy();

		$is_enable_glossary = betterdocs()->settings->get( 'enable_glossaries', false );
		if ( $is_enable_glossary && betterdocs()->is_pro_active() ) {
			$this->register_glossaries_taxonomy();
		}

		$this->register_tag_taxonomy();
	}

	/**
	 * Register the post type: docs
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_post_type() {
		$singular_name = $this->settings->get( 'breadcrumb_doc_title' );

		$labels = [
			'name'               => ( $singular_name ) ? $singular_name : 'Docs',
			'singular_name'      => ( $singular_name ) ? $singular_name : 'Docs',
			'menu_name'          => __( 'BetterDocs', 'betterdocs' ),
			'name_admin_bar'     => __( 'Docs', 'betterdocs' ),
			'add_new'            => __( 'Add New', 'betterdocs' ),
			'add_new_item'       => __( 'Add New Docs', 'betterdocs' ),
			'new_item'           => __( 'New Docs', 'betterdocs' ),
			'edit_item'          => __( 'Edit Docs', 'betterdocs' ),
			'view_item'          => __( 'View Docs', 'betterdocs' ),
			'all_items'          => __( 'All Docs', 'betterdocs' ),
			'search_items'       => __( 'Search Docs', 'betterdocs' ),
			'parent_item_colorn' => null,
			'not_found'          => __( 'No docs found', 'betterdocs' ),
			'not_found_in_trash' => __( 'No docs found in trash', 'betterdocs' )
		];

		$betterdocs_articles_caps = apply_filters( 'betterdocs_articles_caps', 'edit_posts', 'article_roles' );

		$args = [
			'labels'              => $labels,
			'description'         => __( 'Add new doc from here', 'betterdocs' ),
			'public'              => true,
			'public_queryable'    => true,
			'exclude_from_search' => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_admin_bar'   => $betterdocs_articles_caps,
			'query_var'           => true,
			'capability_type'     => [ 'doc', 'docs' ],
			'hierarchical'        => false,
			'map_meta_cap'        => true,
			'menu_position'       => $this->position,
			'show_in_rest'        => true,
			'menu_icon'           => betterdocs()->assets->icon( 'betterdocs-icon-white.svg' ),
			'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'revisions', 'custom-fields', 'comments' ]
		];

		$builtin_doc_page = $this->settings->get( 'builtin_doc_page', false );
		$docs_page        = $this->settings->get( 'docs_page' );

		$args['has_archive'] = ! $builtin_doc_page && $docs_page ? false : $this->docs_archive;

		$args['rewrite'] = betterdocs()->rewrite->docs_type_rewrite(
			[
				'slug'       => $this->docs_archive,
				'with_front' => false
			],
			$this->docs_slug
		);

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Register the taxonomy for Category
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_category_taxonomy() {
		$category_labels = [
			'name'              => __( 'Docs Categories', 'betterdocs' ),
			'singular_name'     => __( 'Docs Category', 'betterdocs' ),
			'all_items'         => __( 'Docs Categories', 'betterdocs' ),
			'parent_item'       => __( 'Parent Docs Category', 'betterdocs' ),
			'parent_item_colon' => __( 'Parent Docs Category:', 'betterdocs' ),
			'edit_item'         => __( 'Edit Category', 'betterdocs' ),
			'update_item'       => __( 'Update Category', 'betterdocs' ),
			'add_new_item'      => __( 'Add New Docs Category', 'betterdocs' ),
			'new_item_name'     => __( 'New Docs Category Name', 'betterdocs' ),
			'menu_name'         => __( 'Categories', 'betterdocs' )
		];

		$category_args = [
			'hierarchical'      => true,
			'public'            => true,
			'labels'            => $category_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_rest'      => true,
			'has_archive'       => true,
			'capabilities'      => [
				'manage_terms' => 'manage_doc_terms',
				'edit_terms'   => 'edit_doc_terms',
				'delete_terms' => 'delete_doc_terms',
				'assign_terms' => 'edit_docs'
			]
		];

		$category_args['rewrite'] = apply_filters(
			'betterdocs_category_rewrite',
			[
				'slug'       => $this->cat_slug,
				'with_front' => false
			],
			$this->cat_slug
		);

		register_taxonomy( $this->category, [ $this->post_type ], $category_args );
	}

	public function register_glossaries_taxonomy() {
		$encyclopedia_root_slug = betterdocs()->settings->get( 'encyclopedia_root_slug', 'encyclopedia' );

		$labels = [
			'name'              => __( 'Glossaries Terms', 'betterdocs' ),
			'singular_name'     => __( 'Glossaries Term', 'betterdocs' ),
			'all_items'         => __( 'Glossaries Terms', 'betterdocs' ),
			'parent_item'       => __( 'Parent Glossaries Term', 'betterdocs' ),
			'parent_item_colon' => __( 'Parent Glossaries Term:', 'betterdocs' ),
			'edit_item'         => __( 'Edit Term', 'betterdocs' ),
			'update_item'       => __( 'Update Glossary', 'betterdocs' ),
			'add_new_item'      => __( 'Add New Glossaries Term', 'betterdocs' ),
			'new_item_name'     => __( 'New Glossaries Term Name', 'betterdocs' ),
			'menu_name'         => __( 'Glossaries', 'betterdocs' )
		];

		$args = [
			'hierarchical'      => true,
			'public'            => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_rest'      => true,
			'has_archive'       => true,
			'rewrite'           => [
				'slug'       => $encyclopedia_root_slug,
				'with_front' => false,
			],
			'capabilities'      => [
				'manage_terms' => 'manage_doc_terms',
				'edit_terms'   => 'edit_doc_terms',
				'delete_terms' => 'delete_doc_terms',
				'assign_terms' => 'edit_docs'
			]
		];

		// Register the custom taxonomy
		register_taxonomy( $this->glossaries, [ $this->post_type ], $args );

		// Customize rewrite rules for the custom taxonomy
		global $wp_rewrite;
		$wp_rewrite->extra_permastructs[ $this->glossaries ]['struct'] = '/' . $encyclopedia_root_slug . '/%' . $this->glossaries . '%';

		// Flush rewrite rules to ensure the new structure takes effect
		add_action( 'init', 'flush_rewrite_rules', 999 );
	}

	public function add_glossary_term_fields( $taxonomy ) {
		?>
		<div class="form-field term-custom-field-wrap">
			<label for="glossary_term_description"><?php esc_html_e( 'Glossary Term Description', 'betterdocs' ); ?></label>
			<textarea
				name="glossary_term_description"
				id="glossary_term_description"
				rows="5"
				cols="50"
				class="large-text"
				placeholder="<?php esc_attr_e( 'Enter a description for the glossary term', 'betterdocs' ); ?>"
			></textarea>
			<p class="description"><?php echo esc_html_e( 'Enter a description for the glossary term', 'betterdocs' ); ?></p>
		</div>
		<?php wp_nonce_field( 'save_glossary_term_description', 'glossary_term_description_nonce' ); ?>
		<?php
	}

	public function edit_glossary_term_fields( $term ) {
		$glossary_term_description = get_term_meta( $term->term_id, 'glossary_term_description', true );
		?>
		<tr class="form-field term-custom-field-wrap">
			<th scope="row"><label for="glossary_term_description"><?php esc_html_e( 'Glossary Term Description', 'betterdocs' ); ?></label></th>
			<td>
				<?php
				wp_editor(
					$glossary_term_description,
					'glossary_term_description',
					[
						'textarea_name' => 'glossary_term_description',
						'textarea_rows' => 5,
						'media_buttons' => false,
						'tinymce'       => true,
						'quicktags'     => true,
					]
				);
				wp_nonce_field( 'save_glossary_term_description', 'glossary_term_description_nonce' );
				?>
				<p class="description"><?php esc_html_e( 'Enter a description for the glossary term', 'betterdocs' ); ?></p>
			</td>
		</tr>
		<?php
	}

	public function save_glossary_term_fields($term_id) {
		// Check if we're in admin and this is a glossaries taxonomy operation
		if (!is_admin()) {
			return;
		}

		// Verify this is for glossaries taxonomy
		if (!isset($_POST['taxonomy']) || $_POST['taxonomy'] !== 'glossaries') {
			return;
		}

		// Verify nonce for security
		if (!isset($_POST['glossary_term_description_nonce']) ||
			!wp_verify_nonce($_POST['glossary_term_description_nonce'], 'save_glossary_term_description')) {
			return;
		}

		// Check if 'glossary_term_description' is set in $_POST
		if (isset($_POST['glossary_term_description'])) {
			// Sanitize the content using wp_kses_post
			$description = wp_kses_post(wp_unslash($_POST['glossary_term_description']));

			// Save the sanitized value to term meta
			update_term_meta($term_id, 'glossary_term_description', $description);
		}
	}


	public function add_glossary_custom_column( $columns ) {
		$columns['glossary_term_description'] = __( 'Glossary Term Description', 'betterdocs' );
		return $columns;
	}

	public function manage_glossary_custom_column( $content, $column_name, $term_id ) {
		if ( $column_name === 'glossary_term_description' ) {
			$content = get_term_meta( $term_id, 'glossary_term_description', true );
		}
		return $content;
	}

	/**
	 * Hide default description field for glossaries taxonomy
	 */
	public function hide_glossaries_default_description() {
		$screen = get_current_screen();
		if ( $screen && $screen->taxonomy === 'glossaries' ) {
			?>
			<style type="text/css">
				.term-description-wrap,
				.form-field.term-description-wrap {
					display: none !important;
				}
			</style>
			<?php
		}
	}


	/**
	 * Register the taxonomy for Tags.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_tag_taxonomy() {
		$tags_labels = [
			'name'                       => __( 'Docs Tags', 'betterdocs' ),
			'singular_name'              => __( 'Tag', 'betterdocs' ),
			'search_items'               => __( 'Search Tags', 'betterdocs' ),
			'popular_items'              => __( 'Popular Tags', 'betterdocs' ),
			'all_items'                  => __( 'All Tags', 'betterdocs' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Tag', 'betterdocs' ),
			'update_item'                => __( 'Update Tag', 'betterdocs' ),
			'add_new_item'               => __( 'Add New Tag', 'betterdocs' ),
			'new_item_name'              => __( 'New Tag Name', 'betterdocs' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'betterdocs' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'betterdocs' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags', 'betterdocs' ),
			'menu_name'                  => __( 'Tags', 'betterdocs' )
		];

		$tag_args = [
			'hierarchical'          => true,
			'labels'                => $tags_labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'show_admin_column'     => true,
			'query_var'             => true,
			'show_in_rest'          => true,
			'capabilities'          => [
				'manage_terms' => 'manage_doc_terms',
				'edit_terms'   => 'edit_doc_terms',
				'delete_terms' => 'delete_doc_terms',
				'assign_terms' => 'edit_docs'
			]
		];

		$tag_slug = $this->settings->get( 'tag_slug' );

		$tag_args['rewrite'] = apply_filters(
			'betterdocs_tags_rewrite',
			[
				'slug'       => ! empty( $tag_slug ) ? $tag_slug : 'docs-tag',
				'with_front' => false
			]
		);

		register_taxonomy( $this->tag, [ $this->post_type ], $tag_args );
	}

	/**
	 * Get Docs Slug
	 *
	 * @since 1.0.0
	 * @return string
	 */
	private function docs_slug() {
		return $this->rewrite->get_base_slug();
	}

	/**
	 * Get Category Taxonomy Slug
	 *
	 * @since 1.0.0
	 * @return string
	 */
	private function category_slug() {
		return $this->settings->get( 'category_slug', 'docs-category' );
	}
	private function glossaries_slug() {
		return 'glossaries';
	}

	public function highlight_admin_menu( $parent_file ) {
		global $current_screen;
		if ( $current_screen->id === 'edit-docs' || $current_screen->id === 'admin_page_betterdocs-admin' || in_array( $current_screen->id, [ 'edit-doc_tag', 'edit-doc_category' ] ) ) {
			$parent_file = 'betterdocs-dashboard';
		} elseif ( in_array( $current_screen->id, [ 'edit-doc_tag', 'edit-doc_category' ] ) ) {
			$parent_file = 'edit.php?post_type=docs';
		}

		return apply_filters( 'betterdocs_highlight_admin_menu', $parent_file, $current_screen );
	}

	public function highlight_admin_submenu( $submenu_file ) {
		global $current_screen, $pagenow;

		if ( $current_screen->post_type == 'docs' ) {
			if ( $pagenow == 'edit.php' ) {
				$submenu_file = 'betterdocs-admin';
			}
			if ( $pagenow == 'post.php' ) {
				$submenu_file = 'edit.php?post_type=docs';
			}
			if ( $pagenow == 'post-new.php' ) {
				$submenu_file = 'post-new.php?post_type=docs';
			}
			if ( $current_screen->id === 'edit-doc_category' ) {
				$submenu_file = 'edit-tags.php?taxonomy=doc_category&post_type=docs';
			}
			if ( $current_screen->id === 'edit-doc_tag' ) {
				$submenu_file = 'edit-tags.php?taxonomy=doc_tag&post_type=docs';
			}
		}

		if ( 'betterdocs_page_betterdocs-settings' == $current_screen->id ) {
			$submenu_file = 'betterdocs-settings';
		}

		if ( 'betterdocs_page_betterdocs-analytics' == $current_screen->id ) {
			$submenu_file = 'betterdocs-analytics';
		}
		if ( 'betterdocs_page_betterdocs-ai-chatbot' == $current_screen->id ) {
            $submenu_file = 'betterdocs-ai-chatbot';
        }

		if ( 'betterdocs_page_betterdocs-setup' == $current_screen->id ) {
			$submenu_file = 'betterdocs-setup';
		}

		return apply_filters( 'betterdocs_highlight_admin_submenu', $submenu_file, $current_screen, $pagenow );
	}

	/**
	 * Deletes rows from the analytics table when a 'docs' post is deleted from trash.
	 *
	 * This function is hooked into the 'wp_trash_post' action in WordPress. It checks
	 * if the deleted post is of type 'docs'. If so, it deletes the corresponding rows
	 * from the analytics table where the post_id matches the ID of the deleted post.
	 *
	 * @param int $post_id The ID of the deleted post.
	 * @return void
	 */
	public function delete_analytics_rows_on_post_delete( $post_id ) {
		// Check if the deleted post is of type 'docs'
		if ( get_post_type( $post_id ) === 'docs' ) {
			global $wpdb;
			$analytics_table = $wpdb->prefix . 'betterdocs_analytics';

			// Delete the entire row from the analytics table where post_id matches the deleted post
            $wpdb->query($wpdb->prepare("DELETE FROM $analytics_table WHERE post_id = %d", $post_id)); // phpcs:ignore
		}
	}
}
