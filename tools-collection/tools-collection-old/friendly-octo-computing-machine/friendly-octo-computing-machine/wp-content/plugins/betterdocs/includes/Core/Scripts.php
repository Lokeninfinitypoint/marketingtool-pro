<?php

namespace WPDeveloper\BetterDocs\Core;

use WPDeveloper\BetterDocs\Utils\Base;
use WPDeveloper\BetterDocs\Utils\Helper;

class Scripts extends Base {
	protected $settings;

	public function __construct( Settings $settings ) {
		$this->settings = $settings;

		add_action( 'init', [ $this, 'init' ], 1 );
	}

	public function init() {
		$assets = betterdocs()->assets;

		// Vendor CSS
		$assets->register( 'simplebar', 'vendor/css/simplebar.css' );

		// Vendor JS
		$assets->register( 'simplebar', 'vendor/js/simplebar.js' );

		if ( ! wp_script_is( 'clipboard', 'registered' ) ) {
			$assets->register( 'clipboard', 'vendor/js/clipboard.min.js' );
		}

		// Shortcodes Styles Registrations
		$assets->register( 'betterdocs-search', 'public/css/search.css' );
		$assets->register( 'betterdocs-search-modal', 'public/css/search-modal.css' );
		$assets->register( 'betterdocs-social-share', 'public/css/social-share.css' );
		$assets->register( 'betterdocs-feedback-form', 'public/css/feedback-form.css' );
		$assets->register( 'betterdocs-reactions', 'public/css/reactions.css' );
		$assets->register( 'betterdocs-toc', 'public/css/toc.css' );
		$assets->register( 'betterdocs-faq', 'public/css/faq.css' );
		$assets->register( 'betterdocs-category-tab-grid', 'public/css/category-tab-grid.css' );
		$assets->register( 'reading-time', 'public/css/reading-time.css' );

		// Template Parts
		$assets->register( 'betterdocs-sidebar', 'public/css/sidebar.css' );
		$assets->register( 'betterdocs-breadcrumb', 'public/css/breadcrumb.css' );
		$assets->register( 'betterdocs-single', 'public/css/single.css' );
		$assets->register( 'betterdocs-docs', 'public/css/docs.css' );
		$assets->register( 'betterdocs-pagination', 'public/css/pagination.css' );
		$assets->register( 'betterdocs-doc_category', 'public/css/tax-doc_category.css', [ 'betterdocs-breadcrumb', 'betterdocs-pagination' ] );
		$assets->register( 'betterdocs-category-archive-header', 'public/css/archive-header.css' );
		$assets->register( 'betterdocs-category-archive-doc-list', 'public/css/archive-doc-list.css' );
		$assets->register( 'betterdocs-article-summary', 'public/css/article-summary.css' );
		$assets->register( 'betterdocs-author', 'public/css/author.css' );

		$assets->register( 'betterdocs-category-grid', 'public/css/category-grid.css', [ 'simplebar' ] );
		$assets->register( 'betterdocs-category-box', 'public/css/category-box.css' );
		$assets->register( 'betterdocs-category-grid-list', 'public/css/category-grid-list.css' );

		// JS
		$assets->register( 'betterdocs', 'public/js/betterdocs.js', [ 'jquery' ] );
		// Shortcode JS
		$assets->register( 'betterdocs-category-toggler', 'public/js/category-toggler.js', [ 'jquery' ] );
		$assets->register(
			'betterdocs-category-grid',
			'public/js/category-grid.js',
			[
				'jquery',
				'masonry',
				'simplebar',
				'betterdocs-category-toggler'
			]
		);
		$assets->register( 'betterdocs-faq', 'shortcodes/js/faq.js', [ 'jquery' ] );
		$assets->register( 'betterdocs-reactions', 'shortcodes/js/reactions.js', [ 'jquery' ] );
		$assets->register( 'betterdocs-search', 'shortcodes/js/search.js', [ 'jquery' ] );
		$assets->register( 'betterdocs-search-modal', 'shortcodes/js/search-modal.js', [ 'jquery' ] );

		$assets->localize(
			'betterdocs',
			'betterdocsConfig',
			[
				'ajax_url'          => admin_url( 'admin-ajax.php' ),
				'copy_text'         => __( 'Copied', 'betterdocs' ),
				'sticky_toc_offset' => $this->settings->get( 'sticky_toc_offset' ),
				'summary_nonce'     => wp_create_nonce( 'betterdocs_article_summary_nonce' ),
				'summary_error'     => __( 'Failed to generate doc summary. Please try again.', 'betterdocs' )
			]
		);

		$assets->localize(
			'betterdocs-search',
			'betterdocsSearchConfig',
			[
				'ajax_url'            => admin_url( 'admin-ajax.php' ),
				'search_letter_limit' => $this->settings->get( 'search_letter_limit' )
			]
		);

		$assets->localize(
			'betterdocs-search-modal',
			'betterdocsSearchModalConfig',
			[
				'ajax_url'               => admin_url( 'admin-ajax.php' ),
				'rest_url' 			     => esc_url_raw(rest_url()),
				'advance_search'         => $this->settings->get( 'advance_search' ),
				'child_category_exclude' => $this->settings->get( 'child_category_exclude' ),
				'popular_keyword_limit'  => $this->settings->get( 'popular_keyword_limit' ),
				'search_letter_limit'    => $this->settings->get( 'search_letter_limit' ),
				'search_placeholder'     => $this->settings->get( 'search_placeholder' ),
				'search_button_text'     => $this->settings->get( 'search_button_text' ),
				'search_not_found_text'  => $this->settings->get( 'search_not_found_text' ),
				'kb_based_search'        => $this->settings->get( 'kb_based_search' )
			]
		);

		/**
		 * Localize This In Order To Know If This Shortcode Is Arriving From Betterdocs Templates Or Not
		 */
		betterdocs()->assets->localize(
			'betterdocs-category-grid',
			'betterdocsCategoryGridConfig',
			[
				'is_betterdocs_templates' => betterdocs()->helper->is_templates() ? true : false
			]
		);

		$this->blocks( $assets );

		return $assets;
	}



	public function blocks( $assets ) {
		$assets->register( 'betterdocs-fontawesome', 'vendor/css/font-awesome5.css' );
		$assets->register( 'betterdocs-blocks-category-box', 'blocks/categorybox/default.css' );
		$assets->register( 'betterdocs-blocks-category-grid', 'blocks/categorygrid/default.css' );
		$assets->register( 'betterdocs-blocks-category-tech-layout', 'blocks/category-tech-layout/default.css' );
		$assets->register( 'betterdocs-blocks-category-slate-layout', 'blocks/category-slate-layout/default.css' );
		$assets->register( 'betterdocs-feedback-form-editor', 'blocks/feedback-form/style-feedback-editor.css' );
		$assets->register( 'betterdocs-doc-archive-list', 'blocks/doc-archive-list/default.css' );
	}
}
