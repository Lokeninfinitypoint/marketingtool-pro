<?php

namespace Blocksy\Extensions\WoocommerceExtra;

require_once dirname(__FILE__) . '/helpers.php';

class SizeGuide {
	private $post_type = 'ct_size_guide';

	public function get_dynamic_styles_data($args) {
		return [
			'path' => dirname(__FILE__) . '/dynamic-styles.php'
		];
	}

	public function __construct() {
		add_action('init', [$this, 'register_post_type']);

		add_action(
			'wp',
			function () {
				$maybe_table_id = $this->get_table_id();

				if ($maybe_table_id) {
					$renderer = new \Blocksy\CustomPostTypeRenderer($maybe_table_id);
					$renderer->pre_output();
				}
			}
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

		add_filter(
			'blocksy:woocommerce:single-product:additional-actions',
			function ($actions) {
				$actions[] = [
					'id' => 'has_size_guide',
					'label' => __('Size Guide', 'blocksy-companion'),
					'options' => [
						'label' => [
							'type' => 'text',
							'value' => __('Size Guide', 'blocksy-companion'),
							'design' => 'block',
							'sync' => [
								'shouldSkip' => true,
							],
						],
					],
				];

				return $actions;
			}
		);

		add_filter(
			'blocksy:woocommerce:single-product:additional-actions:content:has_size_guide',
			function ($content, $layer) {
				$content .= blocksy_output_size_guide_trigger($layer, $this->get_table_id());
				return $content;
			},
			10, 2
		);

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			if (! class_exists('WC_AJAX')) {
				return $chunks;
			}

			$chunks[] = [
				'id' => 'blocksy_ext_woo_extra_size_guide',
				'selector' => '.ct-size-guide-button-single',
				'url' => blocksy_cdn_url(
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/size-guide.js'
				),
				'trigger' => 'click',
				'has_loader' => [
					'type' => 'button',
					'id' => 'ct-size-guide-modal',
					'will_open_overlay' => true
				]
			];

			return $chunks;
		});

		add_filter('blocksy:general:ct-scripts-localizations', function ($data) {
			$data['dynamic_styles_selectors'][] = [
				'selector' => '#ct-size-guide-modal',
				'url' => blocksy_cdn_url(
					BLOCKSY_URL . 'framework/premium/extensions/woocommerce-extra/static/bundle/size-guide.min.css'
				)
			];

			return $data;
		});

		add_action('wp_ajax_blocksy_get_woo_size_guide', [
			$this,
			'get_woo_size_guide',
		]);

		add_action('wp_ajax_nopriv_blocksy_get_woo_size_guide', [
			$this,
			'get_woo_size_guide',
		]);

		add_filter(
			'blocksy_customizer_options:woocommerce:general:end',
			function ($opts) {
				$opts['has_size_guide_panel'] = blocksy_get_options(
					dirname(__FILE__) . '/customizer-options.php',
					[],
					false
				);

				return $opts;
			},
			55
		);

		add_filter('manage_ct_size_guide_posts_columns', function ($columns) {
			$columns['conditions'] = __('Conditions', 'blocksy-companion');

			return $columns;
		});

		add_action(
			'manage_ct_size_guide_posts_custom_column',
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

	public function get_woo_size_guide() {
		if (
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			! isset($_GET['table_id'])
			||
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			! isset($_GET['product_id'])
		) {
			wp_send_json_error();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$product_id = sanitize_text_field(wp_unslash($_GET['product_id']));

		global $post, $product;
		$old_product = $product;

		$post = get_post($product_id);
		$product = wc_get_product($product_id);
		setup_postdata($post);

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$renderer = new \Blocksy\CustomPostTypeRenderer(sanitize_text_field(wp_unslash($_GET['table_id'])));

		$table_html = $renderer->get_content();

		$behaviour = blc_theme_functions()->blocksy_get_theme_mod(
			'size_guide_placement',
			'modal'
		);

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_GET['size_guide_placement'])) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$behaviour = sanitize_text_field(wp_unslash($_GET['size_guide_placement']));
		}

		$content = '';

		if ($behaviour === 'modal') {
			$content = blocksy_render_view(
				dirname(__FILE__) . '/modal-view.php',
				[
					'table_html' => $table_html,
				]
			);
		} else {
			$content = blocksy_render_view(
				dirname(__FILE__) . '/side-view.php',
				[
					'table_html' => $table_html,
				]
			);
		}

		wp_reset_postdata();
		$product = $old_product;

		wp_send_json_success([
			'content' => $content
		]);
	}

	private function get_table_id() {
		global $product;

		$all_size_guides = get_posts([
			'numberposts' => -1,
			'post_type' => $this->post_type,
		]);

		$conditions_manager = new \Blocksy\ConditionsManager();

		foreach ($all_size_guides as $size_guide) {
			$values = blocksy_get_post_options($size_guide->ID);

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

			return $size_guide->ID;
		}

		return null;
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
				'name' => __('Size Guides', 'blocksy-companion'),
				'singular_name' => __('Size Guide', 'blocksy-companion'),
				'add_new' => __('Add New', 'blocksy-companion'),
				'add_new_item' => __('Add New Size Guide', 'blocksy-companion'),
				'edit_item' => __('Edit Size Guide', 'blocksy-companion'),
				'new_item' => __('New Size Guide', 'blocksy-companion'),
				'all_items' => __('Size Guides', 'blocksy-companion'),
				'view_item' => __('View Size Guide', 'blocksy-companion'),
				'search_items' => __('Search Size Guides', 'blocksy-companion'),
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
}
