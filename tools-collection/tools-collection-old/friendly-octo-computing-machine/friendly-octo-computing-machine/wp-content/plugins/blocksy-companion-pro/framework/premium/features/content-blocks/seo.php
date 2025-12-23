<?php


namespace Blocksy;

class ContentBlocksSeoTools {
	public function __construct() {
		add_filter(
			'rank_math/sitemap/exclude_post_type',
			function($exclude, $post_type) {
				if (in_array($post_type, ['ct_content_block', 'ct_product_tab', 'ct_thank_you_page'], true)) {
					return true;
				}

				return $exclude;
			},
			10, 2
		);

		add_filter(
			'wp_sitemaps_post_types',
			function($post_types) {
				unset($post_types['ct_content_block']);
				unset($post_types['ct_product_tab']);
				unset($post_types['ct_thank_you_page']);
				
				return $post_types;
			}
		);

		add_filter(
			'the_seo_framework_sitemap_supported_post_types',
			function($post_types) {
				return array_diff(
					$post_types,
					[
						'ct_content_block',
						'ct_product_tab',
						'ct_thank_you_page'
					]
				);
			}
		);

		add_filter(
			'wpseo_sitemap_exclude_post_type',
			function ($excluded, $post_type) {
				if (in_array($post_type, ['ct_content_block', 'ct_product_tab', 'ct_thank_you_page'], true)) {
					return true;
				}

				return $excluded;
			},
			10, 2
		);
	}
}

