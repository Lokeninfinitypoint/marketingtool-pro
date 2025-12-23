<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class CustomTabs {
	private $post_type = 'ct_product_tab';

	public function __construct() {
		add_action('init', [$this, 'register_post_type']);

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (!class_exists('WC_AJAX')) {
				return $chunks;
			}

			$matching_tabs = $this->get_matching_tabs();

			if (! $matching_tabs) {
				return $chunks;
			}

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_scroll_to_ct',
				'selector' => '.single-product li[id*="tab-title"][class*="_tab"]',
				'url' => blocksy_cdn_url(
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/scroll-to-custom-tab.js'
				),
			];

			return $chunks;
		});

		add_action(
			'wp',
			function () {
				$matching_tabs = $this->get_matching_tabs();

				foreach ($matching_tabs as $tab) {
					$renderer = new \Blocksy\CustomPostTypeRenderer($tab->ID);
					$renderer->pre_output();
				}
			}
		);

		add_filter(
			'woocommerce_product_tabs',
			[$this, 'custom_product_tab']
		);

		add_filter(
			'blocksy:editor:post_types_for_rest_field',
			function ($post_types) {
				$post_types[] = $this->post_type;
				return $post_types;
			}
		);

		add_filter('blocksy:editor:post_meta_options', function ($options, $post_type) {
			if ($post_type !== $this->post_type) {
				return $options;
			}

			global $post;

			$post_id = $post->ID;

			return blocksy_akg(
				'options',
				blc_theme_functions()->blocksy_get_variables_from_file(
					dirname(
						__FILE__
					) . '/options.php',
					['options' => []]
				)
			);
		}, 10, 2);

		add_filter('manage_ct_product_tab_posts_columns', function ($columns) {
			$columns['conditions'] = __('Conditions', 'blocksy-companion');

			return $columns;
		});

		add_action(
			'manage_ct_product_tab_posts_custom_column',
			function ($column, $post_id) {
				$atts = blocksy_get_post_options($post_id);

				if ($column === 'conditions') {
					$conditions = blocksy_default_akg('conditions', $atts, []);

					$conditions_manager = new \Blocksy\ConditionsManager();

					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo implode('<br>', $conditions_manager->humanize_conditions($conditions));
				}
			}, 10, 2
		);
	}

	public function register_post_type() {
		$actions = [
			'edit_post',
			'read_post',
			'delete_post',
			'edit_posts',
			'edit_others_posts',
			'publish_posts',
			'read_private_posts',
			'read',
			'delete_posts',
			'delete_private_posts',
			'delete_published_posts',
			'delete_others_posts',
			'edit_private_posts',
			'edit_published_posts'
		];

		$capabilities = [];

		foreach ($actions as $action) {
			$capabilities[$action] = blc_get_capabilities()->get_wp_capability_by(
				'custom_post_type',
				[
					'post_type' => $this->post_type,
					'action' => $action
				]
			);
		}

		register_post_type($this->post_type, [
			'labels' => [
				'name' => __('Product Tabs', 'blocksy-companion'),
				'singular_name' => __('Product Tab', 'blocksy-companion'),
				'add_new' => __('Add New', 'blocksy-companion'),
				'add_new_item' => __('Add New Product Tab', 'blocksy-companion'),
				'edit_item' => __('Edit Product Tab', 'blocksy-companion'),
				'new_item' => __('New Product Tab', 'blocksy-companion'),
				'all_items' => __('Product Tabs', 'blocksy-companion'),
				'view_item' => __('View Product Tab', 'blocksy-companion'),
				'search_items' => __('Search Product Tabs', 'blocksy-companion'),
				'not_found' => __('Nothing found', 'blocksy-companion'),
				'not_found_in_trash' => __('Nothing found in Trash', 'blocksy-companion'),
				'parent_item_colon' => '',
			],

			'show_in_admin_bar' => false,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => 'edit.php?post_type=product',
			'publicly_queryable' => true,
			'can_export' => true,
			'query_var' => true,
			'has_archive' => false,
			'hierarchical' => false,
			'show_in_rest' => true,
			'exclude_from_search' => true,

			'supports' => [
				'title',
				'editor',
				'revisions',
				'custom-fields'
			],

			'capabilities' => $capabilities
		]);
	}

	private function get_matching_tabs() {
		$all_products_tabs = get_posts([
			'numberposts' => -1,
			'post_type' => $this->post_type,
			'suppress_filters' => false,
		]);

		$conditions_manager = new \Blocksy\ConditionsManager();

		$matching_tabs = [];

		foreach ($all_products_tabs as $tab) {
			$values = blocksy_get_post_options($tab->ID);

			$conditions = blocksy_default_akg(
				'conditions',
				$values,
				[]
			);

			if (
				! $conditions_manager->condition_matches(
					$conditions,
					['relation' => 'OR']
				)
			) {
				continue;
			}

			$matching_tabs[] = $tab;
		}

		return $matching_tabs;
	}

	public function custom_product_tab($tabs) {
		global $product;

		foreach ($this->get_matching_tabs() as $tab) {
			$values = blocksy_get_post_options($tab->ID);
			$order = blocksy_default_akg('custom_tab_order', $values, 40);

			$tabs[$tab->ID] = array(
				'title' => get_the_title($tab->ID),
				'priority' => $order,
				'callback' => function() use ($tab) {
					$this->custom_product_tab_render($tab->ID);
				}
			);
		}

		return $tabs;
	}

	public function custom_product_tab_render($tab_id) {
		$output = '';

		$tabs_type = blc_theme_functions()->blocksy_get_theme_mod('woo_tabs_type', 'type-1');

		if ($tabs_type === 'type-4') {
			$output .= blocksy_html_tag(
				'h2',
				[],
				get_the_title($tab_id)
			);
		}

		$renderer = new \Blocksy\CustomPostTypeRenderer($tab_id);
		$output .= $renderer->get_content();

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $output;
	}
}
