<?php

namespace Blocksy;

class CloneCPT {
	private $post_types_to_clone = [
		'ct_content_block',
		'ct_size_guide'
	];

	public function __construct($url = '') {
		add_filter('post_row_actions', [$this, 'blc_duplicate_post_link'], 10, 2);
		add_action('admin_notices', [$this, 'blc_duplication_admin_notice']);
		add_action('admin_action_blc_duplicate_post_as_draft', [$this, 'blc_duplicate_post_as_draft']);
	}

	public function blc_duplicate_post_link($actions, $post) {
		if (
			! current_user_can('edit_posts')
			||
			in_array($post->post_type, $this->post_types_to_clone) === false
		) {
			return $actions;
		}

		$url = wp_nonce_url(
			add_query_arg(
				[
					'action' => 'blc_duplicate_post_as_draft',
					'post' => $post->ID,
				],
				'admin.php'
			),
			basename(__FILE__),
			'duplicate_nonce'
		);

		$actions['duplicate'] = blocksy_html_tag(
			'a',
			[
				'href' => $url,
				'title' => __('Duplicate', 'blocksy-companion'),
				'rel' => 'permalink'
			],
			__('Duplicate', 'blocksy-companion')
		);

		return $actions;
	}

	public function blc_duplicate_post_as_draft(){

		if (empty($_GET['post'])) {
			wp_die(
				esc_html__('No post to duplicate', 'blocksy-companion')
			);
		}

		if (
			! isset($_GET['duplicate_nonce'])
			||
			! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['duplicate_nonce'])), basename(__FILE__))
		) {
			return;
		}

		$post_id = absint($_GET['post']);
		$post = get_post($post_id);

		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;

		if ($post) {
			$args = [
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => wp_slash($post->post_content),
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'draft',
				'post_title'     => $post->post_title,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order
			];

			$new_post_id = wp_insert_post($args);

			$taxonomies = get_object_taxonomies(get_post_type($post));

			$exlude_taxonomies_keys = [
				'post_translations',
			];

			$taxonomies = array_diff($taxonomies, $exlude_taxonomies_keys);

			if($taxonomies) {
				foreach ($taxonomies as $taxonomy) {
					$post_terms = wp_get_object_terms(
						$post_id,
						$taxonomy,
						[
							'fields' => 'slugs'
						]
					);
					wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
				}
			}

			$this->blc_duplicate_post_meta($new_post_id, $post);

			wp_safe_redirect(
				add_query_arg(
					[
						'post_type' => ('post' !== get_post_type($post) ? get_post_type($post) : false),
						'saved' => 'post_duplication_created'
					],
					admin_url('edit.php')
				)
			);
			exit;
		} else {
			wp_die(
				esc_html__('Post creation failed, could not find original post: ', 'blocksy-companion') . esc_html($post_id)
			);
		}

	}

	private function blc_duplicate_post_meta($new_post_id, $original) {
		$post_meta_keys = get_post_custom_keys($original->ID);

		if (empty($post_meta_keys)) {
			return;
		}

		$meta_blacklist = [];

		$meta_blacklist[] = '_edit_lock';
		$meta_blacklist[] = '_edit_last';
		$meta_blacklist[] = '_dp_is_rewrite_republish_copy';
		$meta_blacklist[] = '_dp_has_rewrite_republish_copy';

		$meta_blacklist = apply_filters('duplicate_post_excludelist_filter', $meta_blacklist);

		$meta_keys = array_diff($post_meta_keys, $meta_blacklist);
		$meta_keys = apply_filters('duplicate_post_meta_keys_filter', $meta_keys);

		foreach ($meta_keys as $meta_key) {
			$meta_values = get_post_custom_values($meta_key, $original->ID);
			foreach ($meta_values as $meta_value) {
				if (unserialize($meta_value)) {
					add_post_meta($new_post_id, $meta_key, unserialize($meta_value));
				} else {
					add_post_meta($new_post_id, $meta_key, $meta_value);
				}
			}
		}
	}

	public function blc_duplication_admin_notice() {
		$screen = get_current_screen();

		if ('edit' !== $screen->base) {
			return;
		}

		if (
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			isset($_GET['saved'])
			&&
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			'post_duplication_created' == $_GET['saved']
		) {
			blocksy_html_tag_e(
				'div',
				[
					'class' => 'notice notice-success is-dismissible'
				],
				blocksy_html_tag(
					'p',
					[],
					__('Post copy created.', 'blocksy-companion')
				)
			);
		}
	}
}
