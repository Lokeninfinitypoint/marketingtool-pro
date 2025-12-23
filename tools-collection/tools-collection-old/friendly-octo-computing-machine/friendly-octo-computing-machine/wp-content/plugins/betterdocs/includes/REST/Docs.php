<?php

namespace WPDeveloper\BetterDocs\REST;

use Error;
use WP_Query;
use WP_REST_Response;
use WPDeveloper\BetterDocs\Core\BaseAPI;

class Docs extends BaseAPI {
	public function permission_check(): bool {
		return true;
	}

    public function register() {
        $this->get( 'search', [$this, 'search_posts'], [
            'password' => [
                'description' => __( 'The password for password-protected docs.' ),
                'type'        => 'string',
            ],
        ] );
        $this->get( 'search-insert', [$this, 'search_insert'] );
        $this->get( 'get-terms', [$this, 'get_terms_name_and_slug'] );
        $this->get( 'months-with-posts', [$this, 'get_months_with_posts'] );
        $this->get( 'order_docs', [$this, 'render_betterdocs_order_docs'], [
            'password' => [
                'description' => __( 'The password for password-protected docs.' ),
                'type'        => 'string',
            ],
        ] );
        $this->register_field( 'docs', 'year_month', [
            'get_callback' => [$this, 'year_month']
        ] );

		$this->register_field(
			'docs',
			'password',
			[
				'get_callback' => [ $this, 'get_post_password' ]
			]
		);

		add_filter( 'rest_docs_query', [ $this, 'filter_docs_query' ], 10, 2 );
		$this->get( 'docs-faq-count', [ $this, 'get_docs_faq_counts' ] );
	}

    public function render_betterdocs_order_docs($request) {
        $doc_category = $request->get_param('doc_category');
        $order        = $request->get_param('order');
        $orderby      = $request->get_param('orderby');
        $per_page     = $request->get_param('per_page');

        if( empty( $doc_category) ) {
            return [];
        }

        $args         = [
            'term_id' => $doc_category,
            'orderby'  => $orderby,
            'order'    => $order,
            'posts_per_page' => $per_page
        ];

        $args = betterdocs()->query->docs_query_args($args);

        // Exclude password-protected posts unless user has permission or provided password
        if ( ! current_user_can( 'edit_posts' ) ) {
            $args['has_password'] = false;
        }

        $posts = betterdocs()->query->get_posts( $args, true );

        if ( ! $posts->have_posts() ) {
            wp_reset_query();
        }

        $post_datas = [];

        while ( $posts->have_posts() ):
            $posts->the_post();
            $post_obj = get_post( get_the_ID() );

            // Double-check password protection for individual posts
            if ( ! empty( $post_obj->post_password ) ) {
                $can_access = $this->can_access_password_content( $post_obj, $request );
                if ( ! $can_access ) {
                    continue; // Skip this post
                }
            }

            $post_data = $this->get_doc_data( get_the_ID(), $request );
            array_push( $post_datas, $post_data );
        endwhile;

        wp_reset_postdata();
        wp_reset_query();

        return $post_datas;
    }

		public function get_docs_faq_counts() {
		// Initialize the return array
		$counts = [
			'created_docs'    => 0,
			'published_docs'  => 0,
			'created_faq'     => 0,
			'published_faq'   => 0
		];

		// Get all docs (any status)
		$all_docs_query = new WP_Query([
			'post_type'      => 'docs',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		]);
		$counts['created_docs'] = $all_docs_query->post_count;

		// Get published docs only
		$published_docs_query = new WP_Query([
			'post_type'      => 'docs',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		]);
		$counts['published_docs'] = $published_docs_query->post_count;

		// Get all FAQs (any status)
		$all_faq_query = new WP_Query([
			'post_type'      => 'betterdocs_faq',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		]);
		$counts['created_faq'] = $all_faq_query->post_count;

		// Get published FAQs only
		$published_faq_query = new WP_Query([
			'post_type'      => 'betterdocs_faq',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		]);
		$counts['published_faq'] = $published_faq_query->post_count;

		return $counts;
	}

     /**
     * Get Doc Data Based On Doc ID
     *
     * @param int $id Post ID
     * @param WP_REST_Request $request REST request object
     * @return array
     */
    public function get_doc_data( $id, $request = null ) {
        $post_data = get_post( $id );

        // Check if user can access password-protected content
        $can_access_password_content = $this->can_access_password_content( $post_data, $request );

        $data      = [
            'author'         => (int) $post_data->post_author,
            'author_info'    => [
                'name'            => get_the_author_meta( 'display_name', $post_data->post_author ),
                'author_nicename' => get_the_author_meta( 'nicename', $post_data->post_author ),
                'author_url'      => get_author_posts_url( $post_data->post_author )
            ],
            'unique_id'      => uniqid( 'doc' ),
            'id'             => $post_data->ID,
            'title'          => [
                'rendered' => $post_data->post_title
            ],
            'slug'           => get_post_field( 'post_name', $id ),
            'link'           => get_permalink( $id ),
            'status'         => get_post_status(),
            'date'           => $post_data->post_date,
            'date_gmt'       => $post_data->post_date_gmt,
            'doc_category'   => wp_get_post_terms( $id, 'doc_category', ["fields" => "ids"] ),
            'doc_tag'        => wp_get_post_terms( $id, 'doc_tag', ["fields" => "ids"] ),
            'comment_status' => $post_data->comment_status
        ];

        // Only include password field if user has edit permissions
        if ( current_user_can( 'edit_post', $id ) ) {
            $data['password'] = $post_data->post_password;
        }

        // Add password protection indicator
        if ( ! empty( $post_data->post_password ) ) {
            $data['password_protected'] = true;

            // If user cannot access password-protected content, hide sensitive data
            if ( ! $can_access_password_content ) {
                // Keep basic info but indicate it's protected
                $data['title']['rendered'] = $post_data->post_title; // WordPress doesn't prefix in REST API
                $data['excerpt'] = ''; // Hide excerpt for password-protected posts
            }
        } else {
            $data['password_protected'] = false;
        }

        if ( taxonomy_exists( 'knowledge_base' ) ) {
            $data['knowledge_base'] = wp_get_post_terms( $id, 'knowledge_base', ["fields" => "ids"] );
        }

        return $data;
    }

    /**
     * Checks if the user can access password-protected content.
     *
     * This method determines whether we need to override the regular password
     * check in core with a filter.
     *
     * @param WP_Post         $post    Post to check against.
     * @param WP_REST_Request $request Request data to check.
     * @return bool True if the user can access password-protected content, otherwise false.
     */
    public function can_access_password_content( $post, $request ) {
        if ( empty( $post->post_password ) ) {
            // No filter required.
            return true;
        }

        /*
         * Users always get access to password protected content if they have
         * the `edit_post` meta capability.
         */
        if ( current_user_can( 'edit_post', $post->ID ) ) {
            return true;
        }

        // No password provided in request, no auth.
        if ( empty( $request ) || empty( $request['password'] ) ) {
            return false;
        }

        // Double-check the request password.
        return hash_equals( $post->post_password, $request['password'] );
    }

    /**
     * Retrieves the months and years that have posts of the type 'docs' and formats them.
     *
     * This function queries the WordPress database for all unique months and years
     * in which 'docs' post type posts have been published. The results are then
     * formatted into an array of associative arrays, where each entry contains an
     * 'id' and a 'name'.
     *
     * The 'id' is a string formatted as 'month-year' (e.g., 'may-2024') to provide
     * a unique identifier that is easy to work with in JavaScript and HTML. The 'name'
     * is a more human-readable string formatted as 'Month Year' (e.g., 'May 2024') to
     * display to users.
     *
     * @return WP_REST_Response A response containing the formatted months and years.
     */
    public function get_months_with_posts() {
        global $wpdb;

		// Query to get distinct year and month from posts of type 'docs'
		$results = $wpdb->get_results(
			"SELECT DISTINCT YEAR(post_date) AS year, MONTH(post_date) AS month
            FROM $wpdb->posts
            WHERE post_type = 'docs'
            ORDER BY post_date DESC"
		);

		$formatted_months = [];

		foreach ( $results as $result ) {
			$year  = $result->year;
			$month = $result->month;

			// Create a DateTime object to format the month
			$date         = \DateTime::createFromFormat( '!m', $month );
			$month_name   = $date->format( 'F' ); // Full month name
			$month_number = $date->format( 'm' ); // Month number with leading zero

			// Format the months and years into wp rest api structure like wp-json/wp/v2/doc_category
			$formatted_months[] = [
				'id'   => "$year-$month_number", // e.g., '2024-05'
				'name' => "$month_name $year" // e.g., 'May 2024'
			];
		}

		return rest_ensure_response( $formatted_months );
	}

	/**
	 * Callback function to retrieve 'year_month' field value.
	 *
	 * @param object $post The REST API response object.
	 * @return string The formatted date (e.g., '2024-05').
	 */
	public function year_month( $post ) {
		$date_string = isset( $post->post_date ) ? $post->post_date : '';

		$date = new \DateTime( $date_string );

		// Format the date to 'Y-m' (e.g., '2024-05')
		$formatted_date = $date->format( 'Y-m' );

		return $formatted_date;
	}

	/**
	 * Filter the docs query by year_month parameters.
	 *
	 * @param array $args The query arguments.
	 * @param WP_REST_Request $request The current REST API request.
	 * @return array Modified query arguments.
	 */
	public function filter_docs_query( $args, $request ) {
		// Filter by year_month
		if ( isset( $request['year_month'] ) ) {
			$formatted_date = $request['year_month'];

			// Parse the formatted_date to year and month
			$year  = substr( $formatted_date, 0, 4 );
			$month = substr( $formatted_date, 5, 2 );

			// Add date query arguments
			$args['date_query'] = [
				[
					'year'  => $year,
					'month' => $month
				]
			];
		}

		return $args;
	}


	public function get_post_password( $object, $field_name, $request ) {
		// Suppress unused parameter warnings
		unset( $field_name, $request );

		if ( current_user_can( 'edit_docs' ) ) {
			return isset( $object['password'] ) ? $object['password'] : '';
		} else {
			return '';
		}
	}

	public function search_posts( $request ) {
		$search_query = sanitize_text_field( $request->get_param( 's' ) );
		$doc_category = sanitize_text_field( $request->get_param( 'doc_category' ) );
		$number       = (int) $request->get_param( 'per_page' ) ? (int) $request->get_param( 'per_page' ) : 5;
		$docs_ids     = ! empty( $request->get_param( 'doc_ids' ) ) ? explode( ',', $request->get_param( 'doc_ids' ) ) : [];
		$doc_term_ids = ! empty( $request->get_param( 'doc_categories_ids' ) ) ? explode( ',', $request->get_param( 'doc_categories_ids' ) ) : [];
		$faq_term_ids = ! empty( $request->get_param( 'faq_categories_ids' ) ) ? explode( ',', $request->get_param( 'faq_categories_ids' ) ) : [];
		$posts        = array();
		$post_status  = ['publish'];

		if( current_user_can( 'read_private_docs' ) ) {
			array_push($post_status,  'private');
		}

		// Common query args
		$common_args = [
			'post_status'      => $post_status,
			'suppress_filters' => true,
			'orderby'          => 'relevance',
		];

		// Exclude password-protected posts unless user has permission
		if ( ! current_user_can( 'edit_posts' ) ) {
			$common_args['has_password'] = false;
		}

		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			$common_args['suppress_filters'] = false;
			$common_args['lang']             = ICL_LANGUAGE_CODE;
		}

		if ( $search_query ) {
			$common_args['s']              = $search_query;
			$common_args['posts_per_page'] = -1;
		} else {
			$common_args['posts_per_page'] = $number;
		}

		// Docs-specific query
		$docs_args = array_merge(
			$common_args,
			[
				'post_type' => 'docs'
			]
		);

		if ( ! $search_query ) {
			$docs_args['meta_key'] = '_betterdocs_meta_views';
			$docs_args['orderby']  = 'meta_value_num';
			$docs_args['order']    = 'DESC';
		}

		if ( ! empty( $docs_ids ) ) {
			unset( $docs_args['meta_key'] );
			$docs_args['posts_per_page'] = -1;
			$docs_args['post__in']       = $docs_ids;
		}

		if ( ! empty( $doc_term_ids ) ) {
			unset( $docs_args['meta_key'] );
			$docs_args['posts_per_page'] = -1;
			$docs_args['tax_query']      = [
				[
					'taxonomy' => 'doc_category',
					'field'    => 'term_id',
					'terms'    => $doc_term_ids,
					'operator' => 'IN',
				]
			];
		}

		// Taxonomy filter for docs
		if ( $doc_category ) {
			$docs_args['tax_query'] = [
				[
					'taxonomy'         => 'doc_category',
					'field'            => 'slug',
					'terms'            => $doc_category,
					'operator'         => 'AND',
					'include_children' => true,
				],
			];
		}

		// FAQ-specific query
		$faq_args = array_merge(
			$common_args,
			[
				'post_type' => 'betterdocs_faq',
				'orderby'   => 'date',
				'order'     => 'DESC',
			]
		);

		if ( ! empty( $faq_term_ids ) ) {
			$faq_args['posts_per_page'] = -1;
			$faq_args['tax_query']      = [
				[
					'taxonomy' => 'betterdocs_faq_category',
					'field'    => 'term_id',
					'terms'    => $faq_term_ids,
					'operator' => 'IN',
				]
			];
		}

		// Run individual queries
		$docs_query = betterdocs()->query->get_posts( $docs_args );
		$faq_query  = new WP_Query( $faq_args );

		// Process docs posts
		if ( $docs_query->have_posts() ) {
			while ( $docs_query->have_posts() ) {
				$docs_query->the_post();

				$post_obj = get_post( get_the_ID() );

				// Check if user can access password-protected content
				$can_access = $this->can_access_password_content( $post_obj, $request );

				// Skip password-protected posts if user cannot access them
				if ( ! empty( $post_obj->post_password ) && ! $can_access ) {
					continue;
				}

				$taxonomies = array();
				$terms      = get_the_terms( get_the_ID(), 'doc_category' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					$taxonomies = wp_list_pluck( $terms, 'name' );
				}

				$posts[] = array(
					'title'      => get_the_title(),
					'post_type'  => get_post_type(),
					'permalink'  => get_the_permalink(),
					'taxonomies' => implode( ', ', $taxonomies ),
				);
			}
			wp_reset_postdata();
		}

		// Process FAQ posts with content
		if ( $faq_query->have_posts() ) {
			while ( $faq_query->have_posts() ) {
				$faq_query->the_post();

				$terms      = get_the_terms( get_the_ID(), 'betterdocs_faq_category' );
				$taxonomies = array();
				if ( $terms && ! is_wp_error( $terms ) ) {
					$taxonomies = wp_list_pluck( $terms, 'name' );
				}

				$posts[] = array(
					'title'      => get_the_title(),
					'content'    => get_the_content(),  // Include post content for FAQ posts
					'post_type'  => get_post_type(),
					'permalink'  => get_the_permalink(),
					'taxonomies' => implode( ', ', $taxonomies ),
				);
			}
			wp_reset_postdata();
		}

		return $posts;
	}



	public function search_insert( $request ) {
		$search_input = sanitize_text_field( $request->get_param( 's' ) );
		$no_result    = sanitize_text_field( $request->get_param( 'no_result' ) );

		return betterdocs()->query->insert_search_keyword( $search_input, $no_result );
	}


	public function get_terms_name_and_slug( $request ) {
		$default_params = [
			'taxonomy'   => $request->get_param( 'taxonomy' ),
			'hide_empty' => false,
			'fields'     => 'all',
		];

		if ( betterdocs()->settings->get( 'child_category_exclude' ) ) { //disable child terms if this is enabled
			$default_params['parent'] = 0;
		}

		// Retrieve all terms for the specified taxonomy, including empty ones
		$terms = get_terms($default_params);

		// Initialize an empty array to hold the term data
		$term_data = [];

		// Loop through each term and extract the name and slug
		$term_data = array_map(
			function ( $term ) {
				return [
					'name'   => $term->name,
					'slug'   => $term->slug,
					'parent' => $term->parent,
				];
			},
			$terms
		);

		// Return the array of term data
		return $term_data;
	}

	public function get_faq_categories( $request ) {
		// Suppress unused parameter warning
		unset( $request );
	}
}
