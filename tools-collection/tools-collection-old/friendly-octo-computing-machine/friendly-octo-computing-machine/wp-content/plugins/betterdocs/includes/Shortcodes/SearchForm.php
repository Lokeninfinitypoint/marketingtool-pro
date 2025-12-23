<?php

namespace WPDeveloper\BetterDocs\Shortcodes;

use WPDeveloper\BetterDocs\Core\Query;
use WPDeveloper\BetterDocs\Utils\Helper;
use WPDeveloper\BetterDocs\Core\Settings;
use WPDeveloper\BetterDocs\Core\Shortcode;
use WPDeveloper\BetterDocs\Admin\Customizer\Defaults;

class SearchForm extends Shortcode {
	public function __construct( Settings $settings, Query $query, Helper $helper, Defaults $defaults ) {
		parent::__construct( $settings, $query, $helper, $defaults );

		add_action( 'wp_ajax_nopriv_betterdocs_get_search_result', [ $this, 'get_search_results' ] );
		add_action( 'wp_ajax_betterdocs_get_search_result', [ $this, 'get_search_results' ] );
	}

	public function get_style_depends() {
		$handlers = [ 'betterdocs-search' ];
		return $handlers;
	}

	public function get_script_depends() {
		$handlers = [ 'betterdocs-search'];

		if ( is_tax() ) {
			$handlers[] = 'betterdocs-glossaries';
		}
		return $handlers;
	}

	public function get_search_results() {
		global $wpdb;
		$search_input = isset( $_POST['search_input'] ) ? sanitize_text_field( $_POST['search_input'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$search_cat   = isset( $_POST['search_cat'] ) ? wp_strip_all_tags( $_POST['search_cat'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$lang         = isset( $_POST['lang'] ) ? wp_strip_all_tags( $_POST['lang'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$search_input = preg_replace( '/[^A-Za-z0-9_\- ][]]/', '', strtolower( $search_input ) );

		$tax_query = [];
		if ( $search_cat ) {
			$tax_query = [
				[
					'taxonomy'         => 'doc_category',
					'field'            => 'slug',
					'terms'            => $search_cat,
					'operator'         => 'AND',
					'include_children' => true
				]
			];
		}

		$term = get_term_by( 'slug', $search_cat );

		$post_status = ['publish'];

		if( current_user_can( 'read_private_docs' ) ) {
			array_push($post_status,  'private');
		}

		$args = [
			'term_id'          => isset( $term->term_id ) ? $term->term_id : 0,
			'post_type'        => 'docs',
			'post_status'      => $post_status,
			'posts_per_page'   => -1,
			'suppress_filters' => true,
			's'                => $search_input,
			'orderby'          => 'relevance',
			'tax_query'        => $tax_query
		];

		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			$args['suppress_filters'] = false;
			$args['lang']             = ICL_LANGUAGE_CODE;
		}

		$search_results = $this->query->get_posts( $args );

		$response = [];

		ob_start();
		betterdocs()->views->get(
			'shortcode-parts/search-results',
			[
				'search_results' => $search_results,
				'search_input'   => $search_input
			]
		);

		$_output = ob_get_clean();

		$_input_not_found = '';
		if ( ! $search_results->have_posts() ) {
			$_input_not_found = $search_input;
		}

		$response['post_lists'] = $_output;

		if ( $_output && strlen( $search_input ) >= 3 ) {
			betterdocs()->query->insert_search_keyword( $search_input, $_input_not_found );
		}

		wp_reset_postdata();

		wp_send_json_success( $response );
	}

	public function get_name() {
		return 'betterdocs_search_form';
	}

	/**
	 * Summary of default_attributes
	 * @return array
	 */
	public function default_attributes() {
		return apply_filters(
			'betterdocs_search_form_attr',
			[
				'placeholder'    => __( 'Search', 'betterdocs' ),
				'heading'        => '',
				'subheading'     => '',
				'heading_tag'    => 'h1',
				'subheading_tag' => 'p'
			]
		);
	}

	public function render( $atts, $content = null ) {
		betterdocs()->assets->localize(
			'betterdocs-search',
			'betterdocsSearchConfigTwo',
			[
				'is_post_type_archive' => is_post_type_archive( 'docs' ),
			]
		);

		$this->views( 'shortcodes/search' );
	}
}
