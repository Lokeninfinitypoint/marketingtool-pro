<?php

namespace Blocksy;

class ContentBlocks {
	private $post_type = 'ct_content_block';
	private $shortcode = 'blocksy-content-block';

	public $assets_manager = null;

	public function __construct() {
		new ContentBlocksTemplates();
		new ContentBlocksSeoTools();
		new ContentBlocksPopupsLogic();

		$this->assets_manager = new ContentBlocksAssetsManager();

		add_action('init', function () {
			$this->register_block();
			$this->register_post_type();

			$capability = blc_get_capabilities()->get_wp_capability_by(
				'custom_post_type',
				[
					'post_type' => $this->post_type
				]
			);

			if (current_user_can($capability)) {
				new ContentBlocksAdminUi();
			}
		});

		add_action(
			'woocommerce_no_products_found',
			function() {
				$maybe_content_block = blc_get_content_block_that_matches([
					'template_type' => 'nothing_found',
					'match_conditions' => false
				]);

				if (is_search() && $maybe_content_block) {
					remove_action(
						'woocommerce_no_products_found',
						'wc_no_products_found'
					);
				}
			},
			1
		);

		add_shortcode(
			$this->shortcode,
			function ($atts) {
				if (
					! $atts
					||
					! isset($atts['id'])
				) {
					return;
				}

				if (! function_exists('blocksy_get_post_options')) {
					return;
				}

				$id = blocksy_translate_post_id($atts['id']);

				if (
					$id
					&&
					\Blocksy\Plugin::instance()
						->premium
						->content_blocks
						->is_hook_eligible_for_display(intval($id), [
							'match_conditions' => false
						])
				) {
					return $this->output_hook($id, [
						'hook_class' => 'alignfull'
					]);
				}

				return '';
			}
		);

		add_action('blocksy:content-blocks:display-hooks', function () {
			$this->display_hooks();
		});

		add_action('wp', function () {
			do_action('blocksy:content-blocks:display-hooks');
		}, 10000);

		if (class_exists('\Elementor\Plugin')) {
			add_filter(
				'get_post_metadata',
				function ($value, $post_id, $meta_key, $single) {
					if (
						get_post_type($post_id) !== $this->post_type
						||
						$meta_key !== '_wp_page_template'
					) {
						return $value;
					}

					return 'elementor_canvas';
				},
				20,
				4
			);
		}
	}

	public function register_block() {
		register_block_type('blocksy/content-block', [
			'api_version' => 3,
			'render_callback' => function ($attributes, $content) {

				$attributes = wp_parse_args($attributes, [
					'content_block' => '',
					'className' => ''
				]);

				$hook_to_output = blocksy_default_akg(
					'content_block',
					$attributes,
					''
				);

				if (
					$hook_to_output
					&&
					\Blocksy\Plugin::instance()
						->premium
						->content_blocks
						->is_hook_eligible_for_display(intval($hook_to_output), [
							'match_conditions' => false
						])
				) {
					return \Blocksy\Plugin::instance()
						->premium
						->content_blocks
						->output_hook(intval($hook_to_output), [
							'layout' => true,
							'hook_class' => 'alignfull ' . $attributes['className']
						]);
				}

				return '';
			},
		]);
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

		$post_type_options = [
			'labels' => [
				'name' => __('Content Blocks', 'blocksy-companion'),
				'singular_name' => __('Content Block', 'blocksy-companion'),
				'add_new' => __('Add New', 'blocksy-companion'),
				'add_new_item' => __('Add New Content Block', 'blocksy-companion'),
				'edit_item' => __('Edit Content Block', 'blocksy-companion'),
				'new_item' => __('New Content Block', 'blocksy-companion'),
				'all_items' => __('Content Blocks', 'blocksy-companion'),
				'view_item' => __('View Content Block', 'blocksy-companion'),
				'search_items' => __('Search Content Blocks', 'blocksy-companion'),
				'not_found' => __('Nothing found', 'blocksy-companion'),
				'not_found_in_trash' => __('Nothing found in Trash', 'blocksy-companion'),
				'parent_item_colon' => '',
			],

			'show_in_admin_bar' => false,
			'public' => true,
			'show_ui' => true,
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
				// 'thumbnail',
			],

			'capabilities' => $capabilities
		];

		$dashboard_capability = blc_get_capabilities()->get_wp_capability_by('dashboard');

		if (current_user_can($dashboard_capability)) {
			$post_type_options['show_in_menu'] = 'ct-dashboard';
		}

		register_post_type($this->post_type, $post_type_options);
	}

	public function display_hooks() {
		$all_hooks = array_keys(blc_get_content_blocks());

		foreach ($all_hooks as $hook_id) {
			if (! $this->is_hook_eligible_for_display($hook_id)) {
				continue;
			}

			$values = blocksy_get_post_options($hook_id);

			$locations = array_merge([
				[
					'location' => blocksy_default_akg('location', $values, ''),
					'priority' => blocksy_default_akg('priority', $values, '10'),
					'custom_location' => blocksy_default_akg('custom_location', $values, ''),
					'paragraphs_count' => blocksy_default_akg('paragraphs_count', $values, '5'),
					'headings_count' => blocksy_default_akg('headings_count', $values, '5'),
					'cards_count' => blocksy_default_akg('cards_count', $values, '3'),
					'repeat_for_every_card' => blocksy_default_akg('repeat_for_every_card', $values, 'no'),
				]
			], blocksy_default_akg('additional_locations', $values, []));

			$this->assets_manager->enqueue_hook($hook_id);

			foreach ($locations as $location) {
				if (
					$location['location'] === 'custom_hook'
					&&
					! empty($location['custom_location'])
				) {
					$location['location'] = $location['custom_location'];
				}

				if (empty($location['location'])) {
					continue;
				}

				if ($location['location'] === 'blocksy:single:content:paragraphs-number') {
					add_filter('the_content', function ($content) {
						global $blocksy_should_parse_blocks;
						$blocksy_should_parse_blocks = true;
						return $content;
					}, 1);

					add_filter(
						'render_block',
						function ($content, $parsed_block) use ($location, $hook_id) {
							if (isset($parsed_block['ct_hook_block'])) {
								return $content;
							}

							global $blocksy_should_parse_blocks;

							if (
								! isset($blocksy_should_parse_blocks)
								||
								! $blocksy_should_parse_blocks
							) {
								return $content;
							}

							if (
								isset($parsed_block['firstLevelBlock'])
								&&
								$parsed_block['firstLevelBlock']
								&&
								isset($parsed_block['firstLevelBlockIndex'])
								&&
								intval(
									$parsed_block['firstLevelBlockIndex']
								) + 1 === intval($location['paragraphs_count'])
							) {
								$content .= $this->output_hook($hook_id, [
									'hook_class' => 'alignfull'
								]);
							}

							return $content;
						},
						intval($location['priority']), 2
					);

					add_filter('the_content', function ($content) {
						global $blocksy_should_parse_blocks;
						$blocksy_should_parse_blocks = false;
						return $content;
					}, 50);

					continue;
				}

				if ($location['location'] === 'blocksy:single:content:headings-number') {
					add_filter(
						'render_block',
						function ($content, $parsed_block) use ($location, $hook_id) {
							if (isset($parsed_block['ct_hook_block'])) {
								return $content;
							}

							if (
								isset($parsed_block['firstLevelBlock'])
								&&
								$parsed_block['firstLevelBlock']
								&&
								isset($parsed_block['firstLevelHeadingIndex'])
								&&
								intval(
									$parsed_block['firstLevelHeadingIndex']
								) + 1 === intval($location['headings_count'])
							) {
								$content = $this->output_hook($hook_id, [
									'hook_class' => 'alignfull'
								]) . $content;
							}

							return $content;
						},
						intval($location['priority']),
						2
					);

					continue;
				}

				if ($location['location'] === 'blocksy:loop:card:cards-number') {
					$handle_card_after = function () use ($location, $hook_id) {
						global $wp_query;

						$current_post = $wp_query->current_post;

						$cards_count = intval($location['cards_count']);

						$should_output = $current_post === $cards_count - 1;

						$should_output_repeated = false;

						if ($location['repeat_for_every_card'] === 'yes') {
							$should_output_repeated = ($current_post + 1) % $cards_count === 0;
						}

						$should_output = $should_output || $should_output_repeated;

						if (
							$should_output
							&&
							$wp_query->is_main_query()
						) {
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo $this->output_hook($hook_id, [
								'hook_class' => 'alignfull'
							]);
						}
					};

					add_action(
						'blocksy:loop:card:after',
						$handle_card_after,
						intval($location['priority'])
					);

					add_action(
						'blocksy:woocommerce:product-card:after',
						$handle_card_after,
						intval($location['priority'])
					);

					continue;
				}

				add_action(
					$location['location'],
					function () use ($hook_id) {
						$atts = blocksy_get_post_options($hook_id);

						$conditions = blocksy_default_akg(
							'conditions',
							$atts,
							[]
						);

						$conditions_manager = new ConditionsManager();

						$matches = $conditions_manager->condition_matches(
							$conditions,
							[
								'conditions_purpose' => 'archive-loop'
							]
						);

						if (! $matches) {
							return;
						}

						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo $this->output_hook($hook_id);
					},
					intval($location['priority'])
				);
			}
		}
	}

	public function output_hook($id, $args = []) {
		$args = wp_parse_args($args, [
			'layout' => true,
			'article_wrapper' => false,
			'hook_class' => '',
			'hook_attr' => [],
			'article_inside' => '',

			'raw_content' => false,
			'skip_content' => false,

			'entry_content_attr' => []
		]);

		do_action('blocksy:pro:content-blocks:output-hook:start', $id);
		$content_block_renderer = new ContentBlocksRenderer($id);

		$content = '';

		if (! $args['skip_content']) {
			$content = apply_filters(
				'blocksy:pro:content-blocks:output-content',
				$content_block_renderer->get_content(),
				$id
			);

			if (empty($content)) {
				return '';
			}
		}

		$atts = blocksy_get_post_options($id);
		$post_status = get_post_status($id);

		$template_type = get_post_meta($id, 'template_type', true);

		$default_template_subtype = 'card';

		if ($template_type === 'single') {
			$default_template_subtype = 'canvas';
		}

		$template_subtype = blocksy_akg(
			'template_subtype',
			$atts,
			$default_template_subtype
		);

		if (
			$template_type === 'archive'
			&&
			$template_subtype === 'card'
		) {
			$args['layout'] = false;
		}

		if (
			$template_type === 'single'
			&&
			$template_subtype === 'content'
		) {
			$args['layout'] = false;
		}

		if (
			blocksy_akg('has_inline_code_editor', $atts, 'no') === 'yes'
			&&
			$template_type !== 'popup'
		) {
			return $content;
		}

		if (! isset($args['entry_content_attr']['class'])) {
			$args['entry_content_attr']['class'] = '';
		}

		$args['entry_content_attr']['class'] = trim(
			'entry-content is-layout-constrained ' . $args['entry_content_attr']['class']
		);

		$container_class = '';

		$attr = array_merge([
			'data-block' => get_post_meta($id, 'template_type', true) . ':' . $id,
			'class' => $args['hook_class']
		], $args['hook_attr']);

		$container_attr = [
			'class' => ''
		];

		$default_content_block_structure = 'yes';

		if ($template_type === 'hook' || $template_type === 'popup') {
			$default_content_block_structure = 'no';
		}

		$has_content_block_structure = blocksy_akg(
			'has_content_block_structure',
			$atts,
			$default_content_block_structure
		);

		if ($has_content_block_structure === 'yes') {
			$container_attr['class'] = 'ct-container-full';
			$container_attr['data-content'] = 'normal';

			$attr['data-block-structure'] = 'custom';

			if (blocksy_akg('content_block_structure', $atts, 'type-4') === 'type-3') {
				$container_attr['data-content'] = 'narrow';
			}

			$content_spacing_defaults = 'both';

			if ($template_type === 'maintenance') {
				$content_spacing_defaults = 'none';
			}

			$content_block_spacing = blocksy_akg(
				'content_block_spacing',
				$atts,
				$content_spacing_defaults
			);

			$data_v_spacing_components = [];

			if (
				$content_block_spacing === 'both'
				||
				$content_block_spacing === 'top'
			) {
				$data_v_spacing_components[] = 'top';
			}

			if (
				$content_block_spacing === 'both'
				||
				$content_block_spacing === 'bottom'
			) {
				$data_v_spacing_components[] = 'bottom';
			}

			if (! empty($data_v_spacing_components)) {
				$attr['data-vertical-spacing'] = implode(
					':',
					$data_v_spacing_components
				);
			}
		}

		if (
			$template_type === 'archive' && $template_subtype === 'card'
			||
			$template_type === 'single' && $template_subtype === 'content'
			||
			$has_content_block_structure === 'plain'
			||
			$args['raw_content']
		) {
			$processor = new \WP_HTML_Tag_Processor($content);

			if ($processor->next_tag()) {
				$processor->set_attribute('data-block', get_post_meta($id, 'template_type', true) . ':' . $id);

				$content = $processor->get_updated_html();
			}

			return $content;
		}

		$article_atts = [
			'id' => 'post-' . $id,
			'class' => 'post-' . $id
		];

		if ($template_type === 'single' || $template_type === 'archive') {
			$article_atts = [
				'id' => 'post-' . $id,
				'class' => 'post-' . $id
			];

			$maybe_post_class = implode(' ', get_post_class());

			if (! empty($maybe_post_class)) {
				$article_atts['class'] = $maybe_post_class;
			}

			unset($container_attr['data-content']);

			$page_structure = blocksy_get_page_structure();

			$container_attr['class'] = 'ct-container-full';

			if ($page_structure === 'none') {
				$container_attr['class'] = 'ct-container';
			} else {
				$container_attr['data-content'] = $page_structure;
			}
		}

		$visibility_classes = blocksy_visibility_classes(
			blocksy_default_akg('visibility', $atts, [
				'desktop' => true,
				'tablet' => true,
				'mobile' => true,
			])
		);

		$custom_class = blocksy_default_akg('custom_class', $atts, '');
		$custom_attributes = blocksy_default_akg('custom_attributes', $atts, '');


		$attr['class'] = trim($attr['class'] . ' ' . $visibility_classes);

		if (! empty($custom_class)) {
			$attr['class'] = trim($attr['class'] . ' ' . $custom_class);
		}

		if (empty($attr['class'])) {
			unset($attr['class']);
		}

		$attr = array_merge(
			$attr,
			blc_parse_attributes_string($custom_attributes)
		);

		if (! $args['layout']) {
			return blocksy_html_tag(
				'div',
				$args['entry_content_attr'],
				$content
			);
		}

		$article_wrapper = blocksy_html_tag(
			'article',
			$article_atts,
			$args['article_inside'] . (empty($content) ? '' : blocksy_html_tag(
				'div',
				$args['entry_content_attr'],
				$content
			))
		);

		$sidebar_position_attr = [];

		if ($template_type === 'single' || $template_type === 'archive') {
			ob_start();
			get_sidebar();
			$article_wrapper .= ob_get_clean();

			if (function_exists('blocksy_sidebar_position_attr')) {
				$sidebar_position_attr = blocksy_sidebar_position_attr([
					'array' => true
				]);

				if (! is_array($sidebar_position_attr)) {
					$sidebar_position_attr = [];
				}
			}
		}

		if ($args['article_wrapper']) {
			$article_wrapper = blocksy_html_tag(
				'div',
				$args['article_wrapper'],
				$article_wrapper
			);
		}

		return blocksy_html_tag(
			'div',
			$attr,
			$container_attr['class'] ? blocksy_html_tag(
				'div',
				array_merge(
					$container_attr,
					$sidebar_position_attr
				),
				$article_wrapper
			) : $article_wrapper
		);
	}

	public function is_hook_eligible_for_display($id, $args = []) {
		$args = wp_parse_args($args, [
			'match_conditions' => true,
			'match_conditions_strategy' => 'current-screen',
		]);

		if (class_exists('\Elementor\Plugin')) {
			if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
				return false;
			}
		}

		if (
			class_exists('GFForms')
			&&
			method_exists(
				'\GFForms',
				'get_service_container'
			)
		) {
			$maybe_full_screen = \GFForms::get_service_container()
				->get('full_screen_handler');

			if (
				$maybe_full_screen
				&&
				$maybe_full_screen->get_form_for_display()
			) {
				return false;
			}
		}

		$values = blocksy_get_post_options($id);
		$template_type = get_post_meta($id, 'template_type', true);

		$defaults = [];

		if (
			$template_type === 'maintenance'
			||
			$template_type === 'nothing_found'
		) {
			$defaults = [
				[
					'type' => 'include',
					'rule' => 'everywhere',
				]
			];
		}

		$conditions = blocksy_default_akg(
			'conditions',
			$values,
			$defaults
		);

		if (blocksy_default_akg('is_hook_enabled', $values, 'yes') !== 'yes') {
			return false;
		}

		$conditions_manager = new ConditionsManager();

		if (
			get_post_status($id) === 'trash'
			||
			get_post_status($id) === 'draft'
			||
			! empty(get_post($id)->post_password)
			||
			(
				get_post_status($id) === 'private'
				&&
				! current_user_can('read_private_pages', $id)
			)
		) {
			return false;
		}

		if ($args['match_conditions']) {
			if (! $conditions_manager->condition_matches(
				$conditions,
				apply_filters(
					'blocksy:pro:content-blocks:condition-match-args',
					[
						'relation' => 'OR',
						'strategy' => $args['match_conditions_strategy'],
					],
					$id
				)
			)) {
				return false;
			}
		}

		return apply_filters(
			'blocksy:pro:content-blocks:condition-match',
			true,
			$id
		);
	}
}

