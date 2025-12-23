<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class AdvancedReviews {
	public function __construct() {

		add_action(
			'wp_enqueue_scripts',
			function () {
				if (!function_exists('get_plugin_data')) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$data = get_plugin_data(BLOCKSY__FILE__);

				if (is_admin()) {
					return;
				}

				if (! blc_theme_functions()->blocksy_manager()) {
					return;
				}

				if (! blc_theme_functions()->blocksy_manager()->screen->is_product()) {
					return;
				}

				wp_enqueue_style(
					'blocksy-ext-woocommerce-extra-advanced-reviews-styles',
					BLOCKSY_URL .
						'framework/premium/extensions/woocommerce-extra/static/bundle/advanced-reviews.min.css',
					['blocksy-ext-woocommerce-extra-styles'],
					$data['Version']
				);
			},
			50
		);

		add_filter(
			'woocommerce_product_reviews_table_column_comment_content',
			function ($output, $item) {

				$comment_id = $item->comment_ID;
				$comment = get_comment($comment_id);

				if (blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_title', 'no') === 'yes') {
					ob_start();
					$this->display_comment_title($comment);
					$output = ob_get_clean() . $output;
				}

				if (blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_images', 'no') === 'yes') {
					ob_start();
					$this->display_attachments($comment);
					$output .= ob_get_clean();
				}

				return $output;
			},
			10, 2
		);

		add_filter('woocommerce_product_review_list_args', function($args) {
			if (blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_order', 'old') === 'new') {
				$args['reverse_top_level'] = true;
			}

			if (blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_order', 'old') === 'rating') {
				$args['orderby'] = 'rating';
				$args['order'] = 'ASC';
			}

			return $args;
		});

		add_filter('comments_array', function($comments_flat) {

			if (
				! blc_theme_functions()->blocksy_manager()
				||
				! blc_theme_functions()->blocksy_manager()->screen->is_product()
			) {
				return $comments_flat;
			}

			if (
				blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_order', 'old') === 'rating_low'
				||
				blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_order', 'old') === 'rating_high'
			) {
				usort($comments_flat, function($a, $b) {
					$a_rating = get_comment_meta($a->comment_ID, 'rating', true);
					$b_rating = get_comment_meta($b->comment_ID, 'rating', true);

					if ($a_rating === $b_rating) {
						return 0;
					}

					if (blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_order', 'old') === 'rating_low') {
						return ($a_rating < $b_rating) ? -1 : 1;
					}

					return ($a_rating < $b_rating) ? 1 : -1;
				});
			}

			if (blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_order', 'old') === 'most_relevant') {
				usort($comments_flat, function($a, $b) {
					$a_rating = get_comment_meta($a->comment_ID, 'rating', true);
					$b_rating = get_comment_meta($b->comment_ID, 'rating', true);

					$a_votes = get_comment_meta($a->comment_ID, 'blocksy_comment_meta_votes', true);
					$b_votes = get_comment_meta($b->comment_ID, 'blocksy_comment_meta_votes', true);

					$a_thumbs = get_comment_meta($a->comment_ID, 'blocksy_comment_meta_images', true);
					$b_thumbs = get_comment_meta($b->comment_ID, 'blocksy_comment_meta_images', true);

					if (! is_array($a_votes)) {
						$a_votes = [
							'up' => [],
							'down' => []
						];
					}

					if (! is_array($b_votes)) {
						$b_votes = [
							'up' => [],
							'down' => []
						];
					}

					if (! is_array($a_thumbs)) {
						$a_thumbs = [];
					}

					if (! is_array($b_thumbs)) {
						$b_thumbs = [];
					}

					$a_upvotes = count($a_votes['up']);
					$a_downvotes = count($a_votes['down']);

					$b_upvotes = count($b_votes['up']);
					$b_downvotes = count($b_votes['down']);

					$a_total = $a_upvotes + $a_downvotes;
					$b_total = $b_upvotes + $b_downvotes;

					if ($a_total === $b_total) {
						if ($a_rating === $b_rating) {
							return (count($a_thumbs) < count($b_thumbs)) ? 1 : -1;
						}

						return ($a_rating < $b_rating) ? 1 : -1;
					}

					return ($a_total < $b_total) ? 1 : -1;
				});
			}

			return $comments_flat;
		});

		add_action('after_setup_theme', function () {
			if (
				blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_lightbox', 'no') === 'yes'
				||
				is_customize_preview()
			) {
				add_theme_support('wc-product-gallery-lightbox');
			}
		});

		add_action('wp', function() {

			if (blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_summary', 'no') === 'yes') {
				global $blocksy_summary_box_rendered;
				$blocksy_summary_box_rendered = false;

				add_action(
					'woocommerce_before_template_part',
					function ($template_name, $template_path, $located, $args) {
						global $blocksy_summary_box_rendered;

						if (
							$template_name === 'single-product/review.php'
							&&
							! $blocksy_summary_box_rendered
						) {
							$this->blc_reviews_summary();
							$blocksy_summary_box_rendered = true;
						}
					},
					4,
					4
				);
			}
		});

		add_action('init', function() {

			add_filter('woocommerce_product_review_comment_form_args', [$this, 'change_comment_form']);

			if (blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_title', 'no') === 'yes') {
				add_action('woocommerce_review_before_comment_text', [$this, 'display_comment_title']);
				add_action('comment_post', [$this, 'add_review_title_meta'], 1);
			}

			if (blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_images', 'no') === 'yes') {
				add_action('comment_post', [$this, 'save_attachments'], 1);
				add_action('woocommerce_review_after_comment_text', [$this, 'display_attachments'], 10);

				add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
					$chunks[] = [
						'id' => 'blocksy_ext_woo_extra_advanced_reviews',
						'selector' => implode(', ', [
							'#blc-review-images',
						]),
						'url' => blocksy_cdn_url(
							BLOCKSY_URL .
								'framework/premium/extensions/woocommerce-extra/static/bundle/advanced-reviews.js'
						),
						'trigger' => [
							'trigger' => 'jquery-event',
							'events' => [
								'change'
							]
						]
					];

					return $chunks;
				});

				if (blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_lightbox', 'no') === 'yes') {
					add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
						$chunks[] = [
							'id' => 'blocksy_ext_woo_extra_advanced_reviews_lightbox',
							'selector' => implode(', ', [
								'.ct-review-images .ct-media-container',
							]),
							'url' => blocksy_cdn_url(
								BLOCKSY_URL .
									'framework/premium/extensions/woocommerce-extra/static/bundle/advanced-reviews-lightbox.js'
							),
							'trigger' => 'click',
						];

						return $chunks;
					});
				}
			}

			$allowed_roles = blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_votes_allowed_roles', [
				'logged_in' => true,
				'logged_out' => false,
			]);

			$allow_logged_in = $allowed_roles['logged_in'] === true && is_user_logged_in();
			$allow_logged_out = $allowed_roles['logged_out'] === true && ! is_user_logged_in();

			if (
				blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_votes', 'no') === 'yes'
				&&
				(
					$allow_logged_in
					||
					$allow_logged_out
				)
			) {
				add_action('woocommerce_review_after_comment_text', [$this, 'display_votes'], 10);

				if ($allow_logged_in) {
					add_action('wp_ajax_ct_review_vote', [$this, 'blc_vote_for_review']);
				}

				if ($allow_logged_out) {
					add_action('wp_ajax_nopriv_ct_review_vote', [$this, 'blc_vote_for_review']);
				}

				add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
					$chunks[] = [
						'id' => 'blocksy_ext_woo_extra_advanced_reviews_voting',
						'selector' => implode(', ', [
							'.ct-review-vote',
						]),
						'url' => blocksy_cdn_url(
							BLOCKSY_URL .
								'framework/premium/extensions/woocommerce-extra/static/bundle/advanced-reviews-voting.js'
						),
						'trigger' => 'click',
					];

					return $chunks;
				});

				add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
					$cache_manager = new \Blocksy\CacheResetManager();

					if ($cache_manager->is_there_any_page_caching()) {
						$chunks[] = [
							'id' => 'blocksy_ext_woo_extra_advanced_reviews_sync_cache',
							'selector' => implode(', ', [
								'.commentlist'
							]),
							'url' => blocksy_cdn_url(
								BLOCKSY_URL .
											'framework/premium/extensions/woocommerce-extra/static/bundle/advanced-reviews-sync-cache.js'
							),
						];
					}

					return $chunks;
				});

				add_action('wp_ajax_ct_sync_votes', [$this, 'blc_sync_votes']);
				add_action('wp_ajax_nopriv_ct_sync_votes', [$this, 'blc_sync_votes']);
			}
		});

		add_filter(
			'blocksy_customizer_options:woocommerce:general:end',
			function ($opts) {
				$opts['has_product_advanced_reviews_panel'] = blocksy_get_options(
					dirname(__FILE__) . '/options.php',
					[],
					false
				);

				return $opts;
			},
			50
		);
	}

	public function blc_reviews_summary() {
		$reviews = get_comments([
			'post_id' => get_the_ID(),
			'status' => 'approve',
		]);

		if (empty($reviews)) {
			return;
		}

		$ratings = [];

		foreach ($reviews as $review) {
			$rating = get_comment_meta($review->comment_ID, 'rating', true);

			if ($rating === '') {
				$rating = 0;
			}

			if (! isset($ratings[$rating])) {
				$ratings[$rating] = 0;
			}

			$ratings[$rating]++;
		}

		$rating_count = count($reviews) - (isset($ratings[0]) ? $ratings[0] : 0);

		$average_rating = 0;

		if ($rating_count > 0) {
			$average_rating = array_sum(array_map(function($rating, $count) {
				return intval($rating) * intval($count);
			}, array_keys($ratings), $ratings)) / $rating_count;
		}

		$average_rating = round($average_rating, 2);

		$rating_html = [];

		for ($i = 5; $i >= 1; $i--) {

			if (! $rating_count) {
				$rating_count = 1;
			}

			$percent = (isset($ratings[$i]) ? $ratings[$i] : 0) * 100 / $rating_count;

			$rating_html[] = blocksy_html_tag(
				'span',
				[
					'class' => 'ct-review-rating-label'
				],
				$i . ' ' . ($i === 1 ? __('Star', 'blocksy-companion') : __('Stars', 'blocksy-companion')),
			) .
			blocksy_html_tag(
				'span',
				[
					'class' => 'ct-review-rating-percent-bar',
				],
				blocksy_html_tag(
					'span',
					[
						'style' => 'width: ' . number_format($percent, 2) . '%'
					],
					''
				)
			) .
			blocksy_html_tag(
				'span',
				[
					'class' => 'ct-review-rating-percent-label'
				],
				round($percent) . '%'
			);
		}

		ob_start();
		woocommerce_template_single_rating();
		$overall_rating = ob_get_clean();

		$recomendations_percent = round(
			(
				(isset($ratings[4]) ? $ratings[4] : 0) +
				(isset($ratings[5]) ? $ratings[5] : 0)
			) /
			$rating_count * 100
		);

		blocksy_html_tag_e(
			'li',
			[
				'class' => 'ct-reviews-summary'
			],
			blocksy_html_tag(
				'div',
				[
					'class' => 'ct-reviews-average-rating'
				],
				blocksy_html_tag(
					'div',
					[
						'class' => 'ct-reviews-average-rating-info'
					],
					blocksy_html_tag(
						'b',
						[],
						$average_rating
					) .
					$overall_rating
				) .
				(
					$recomendations_percent ?
					blocksy_html_tag(
						'span',
						[
							'class' => 'ct-review-recommendation-count'
						],
						blc_safe_sprintf(
							// translators: %s is the percentage of customers
							__('%s%% of customers recommend this product.', 'blocksy-companion'),
							$recomendations_percent
						)
					) : ''
				)
			) .
			blocksy_html_tag(
				'div',
				[
					'class' => 'ct-reviews-rating'
				],
				join('', $rating_html)
			)
		);
	}

	public function blc_sync_votes() {
		$data = json_decode(
			file_get_contents('php://input'),
			true
		);

		$comments_ids = $data['comments_ids'];

		$votes = [];

		foreach ($comments_ids as $comment_id) {
			$votes[$comment_id] = get_comment_meta($comment_id, 'blocksy_comment_meta_votes', true);
		}

		wp_send_json([
			'votes' => $votes,
			'user' => $this->get_user_identifier()
		]);
	}

	public function blc_vote_for_review() {

		$data = json_decode(
			file_get_contents('php://input'),
			true
		);

		$comment_id = (int) $data['comment_id'];
		$vote = $data['vote'];

		$votes = get_comment_meta($comment_id, 'blocksy_comment_meta_votes', true);

		if (! is_array($votes)) {
			$votes = [
				'up' => [],
				'down' => []
			];
		}

		if (! isset($votes[$vote])) {
			$votes[$vote] = [];
		}

		$current_user_id = $this->get_user_identifier();

		if (in_array($current_user_id, $votes[$vote])) {
			$votes[$vote] = array_diff($votes[$vote], [$current_user_id]);
		} else {
			$votes[$vote][] = $current_user_id;
		}

		if ($vote === 'up') {
			$votes['down'] = array_diff($votes['down'], [$current_user_id]);
		} else {
			$votes['up'] = array_diff($votes['up'], [$current_user_id]);
		}

		update_comment_meta($comment_id, 'blocksy_comment_meta_votes', $votes);

		wp_send_json([
			'votes' => $votes,
			'upvotes' => count($votes['up']),
			'total' => count($votes['up']) + count($votes['down']),
		]);
	}

	public function display_votes($comment) {
		if (blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_votes_only_logged_in', 'no') === 'yes') {
			if (! is_user_logged_in()) {
				return '';
			}
		}

		if (! function_exists('blocksy_action_button')) {
			return '';
		}

		$current_user_id = $this->get_user_identifier();

		$votes = get_comment_meta($comment->comment_ID, 'blocksy_comment_meta_votes', true);

		if (! is_array($votes)) {
			$votes = [];
		}

		$upvotes = isset($votes['up']) ? $votes['up'] : [];
		$downvotes = isset($votes['down']) ? $votes['down'] : [];

		$upvote_count = count($upvotes);
		$downvote_count = count($downvotes);

		$is_upvoted = false;
		$is_downvoted = false;

		if (in_array($current_user_id, $upvotes)) {
			$is_upvoted = true;
		}

		if (in_array($current_user_id, $downvotes)) {
			$is_downvoted = true;
		}

		$upvote = blocksy_action_button(
			[
				'button_html_attributes' => [
					'href' => '#',
					'class' => 'ct-review-vote',
					'data-comment-id' => $comment->comment_ID,
					'data-vote' => 'up',
					'data-button-state' => $is_upvoted ? 'active' : '',
					'aria-label' => __('Upvote', 'blocksy-companion'),
				],
				'icon' => '<svg width="15" height="15" viewBox="0 0 24 24"><path d="M19 24H3.4C1.6 24 .1 22.5.1 20.7v-7.6c0-1.8 1.5-3.3 3.3-3.3H6L10.1.6c.2-.4.6-.6 1-.6 2.4 0 4.4 2 4.4 4.4v3.3h5.6c.9.1 1.6.6 2.1 1.3.5.7.7 1.6.6 2.4l-1.5 9.8C22 22.8 20.7 24 19 24zM7.8 21.8H19c.5 0 1-.4 1.1-.9l1.5-9.8c0-.3 0-.6-.2-.8-.1-.3-.4-.4-.7-.5h-6.4c-.6 0-1.1-.5-1.1-1.1V4.4c0-1-.6-1.8-1.5-2.1l-3.9 8.9v10.6zM3.4 12c-.6 0-1.1.5-1.1 1.1v7.6c0 .6.5 1.1 1.1 1.1h2.2V12H3.4z"/></svg>'
			]
		);

		$downvote = blocksy_action_button(
			[
				'button_html_attributes' => [
					'href' => '#',
					'class' => 'ct-review-vote',
					'data-comment-id' => $comment->comment_ID,
					'data-vote' => 'down',
					'data-button-state' => $is_downvoted ? 'active' : '',
					'aria-label' => __('Downvote', 'blocksy-companion'),
				],
				'icon' => '<svg width="15" height="15" viewBox="0 0 24 24"><path d="M5 0h15.6c1.8 0 3.3 1.5 3.3 3.3v7.6c0 1.8-1.5 3.3-3.3 3.3H18l-4.1 9.2c-.2.4-.6.6-1 .6-2.4 0-4.4-2-4.4-4.4v-3.3H3c-.9-.1-1.6-.6-2.1-1.3-.5-.7-.7-1.6-.6-2.4l1.5-9.8C2 1.2 3.3 0 5 0zm11.2 2.2H5c-.5 0-1 .4-1.1.9l-1.5 9.8c0 .3 0 .6.2.8.1.3.4.4.7.5h6.4c.6 0 1.1.5 1.1 1.1v4.4c0 1 .6 1.8 1.5 2.1l3.9-8.9V2.2zm4.4 9.8c.6 0 1.1-.5 1.1-1.1V3.3c0-.6-.5-1.1-1.1-1.1h-2.2V12h2.2z"/></svg>'
			]
		);

		blocksy_html_tag_e(
			'div',
			[
				'class' => 'ct-review-votes'
			],
			$upvote .
			$downvote .
			blocksy_html_tag(
				'span',
				[
					'class' => 'ct-review-vote-count',
					'data-count' => $upvote_count
				],
				blc_safe_sprintf(
					// translators: %1$s is the number of helpful votes, %2$s is the total votes
					__('%1$s of %2$s found this review helpful', 'blocksy-companion'),
					blocksy_html_tag(
						'span',
						[
							'class' => 'ct-review-upvote-count'
						],
						$upvote_count
					),
					blocksy_html_tag(
						'span',
						[
							'class' => 'ct-review-total-count'
						],
						$upvote_count + $downvote_count
					)
				)
			)
		);
	}

	public function display_comment_title($comment) {
		$review_title = get_comment_meta($comment->comment_ID, 'blocksy_comment_meta_title', true);

		if (! empty($review_title)) {
			blocksy_html_tag_e(
				'h3',
				[
					'class' => 'ct-review-title'
				],
				wp_kses_post($review_title)
			);
		}
	}

	public function add_review_title_meta($comment_id) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (isset($_POST['title'], $_POST['comment_post_ID']) && 'product' === get_post_type(absint($_POST['comment_post_ID']))) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			if (empty($_POST['title'])) {
				return;
			}
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			update_comment_meta($comment_id, 'blocksy_comment_meta_title', sanitize_text_field(wp_unslash($_POST['title'])), true);
		}
	}

	public function change_comment_form($comment_form) {
		if (
			! blc_theme_functions()->blocksy_manager()
			||
			! blc_theme_functions()->blocksy_manager()->screen->is_product()
		) {
			return $comment_form;
		}

		if (blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_title', 'no') === 'yes') {
			$comment_form['comment_field'] = str_replace(
				'<p class="comment-form-field-textarea">',
				blocksy_html_tag(
					'p',
					[
						'class' => 'comment-form-field-input-title'
					],
					blocksy_html_tag(
						'label',
						[
							'for' => 'title'
						],
						esc_html__('Review Title', 'blocksy-companion')
					) .
					blocksy_html_tag(
						'input',
						[
							'type' => 'text',
							'name' => 'title',
							'id' => 'title'
						],
						''
					)
				) .
				'<p class="comment-form-field-textarea">',
				$comment_form['comment_field']
			);
		}

		if (blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_images', 'no') === 'yes') {
			$image_form = blocksy_html_tag(
				'p',
				[
					'class' => 'ct-review-upload-section'
				],
				blocksy_html_tag(
					'label',
					[
						'for' => 'blc-review-images'
					],
					__('Upload Image (Optional)', 'blocksy-companion')
				) .

				blocksy_html_tag(
					'span',
					[
						'class' => 'ct-review-upload-actions ct-review-images'
					],
					blocksy_html_tag(
						'label',
						[
							'class' => 'ct-upload-button',
							'for' => 'blc-review-images'
						],
						'<svg width="12" height="12" viewBox="0 0 15 15" fill="currentColor"><path d="M15 6.2H8.8V0H6.2v6.2H0v2.6h6.2V15h2.6V8.8H15z"/></svg>'
					) .

					blocksy_html_tag(
						'input',
						[
							'type' => 'file',
							'name' => 'blc-review-images[]',
							'id' => 'blc-review-images',
							'accept' => 'image/*',
							'multiple' => 'true'
						]
					)
				)
			);

			$comment_form['comment_field'] .= $image_form;
		}

		return $comment_form;
	}

	public function display_attachments($review) {
		$thumbnail_div = '';

		$thumbs = get_comment_meta($review->comment_ID, 'blocksy_comment_meta_images', true);

		$thumbnail_html = [];

		$is_lightbox = blc_theme_functions()->blocksy_get_theme_mod('woo_advanced_reviews_lightbox', 'no') === 'yes';

		if (! empty($thumbs)) {
			foreach ($thumbs as $thumb_id) {

				if (! wp_attachment_is_image($thumb_id)) {
					continue;
				}

				$thumbnail_html[] = blocksy_media([
					'attachment_id' => $thumb_id,
					'size' => 'medium',
					'ratio' => '1/1',
					'tag_name' => 'figure',
					'html_atts' => $is_lightbox ? [
						'data-src' => wp_get_attachment_image_url($thumb_id, 'full'),
						'data-width' => wp_get_attachment_image_src($thumb_id, 'full')[1],
						'data-height' => wp_get_attachment_image_src($thumb_id, 'full')[2],
					] : []
				]);
			}
		}

		if (! empty($thumbnail_html)) {
			blocksy_html_tag_e(
				'div',
				[
					'class' => 'ct-review-images'
				],
				join('', $thumbnail_html)
			);
		}
	}

	private function insert_attachment($file_handler, $post_id) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (! isset($_FILES[ $file_handler ]['error'])) {
			return false;
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$has_file = UPLOAD_ERR_OK !== (int) $_FILES[ $file_handler ]['error'] ? sanitize_text_field(wp_unslash($_FILES[ $file_handler ]['error'])) : '';
		if (! empty($has_file)) {
			return false;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		return media_handle_upload($file_handler, $post_id);
	}

	public function save_attachments($comment_id) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ($_FILES) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$files = isset($_FILES['blc-review-images']) ? wc_clean($_FILES['blc-review-images']) : [];
			$files_count = isset($files['name']) ? count($files['name']) : 0;

			$attachments_array = [];

			foreach ($files['name'] as $key => $value) {
				if ($files['name'][ $key ]) {
					$file   = [
						'name'     => $files['name'][ $key ],
						'type'     => $files['type'][ $key ],
						'tmp_name' => $files['tmp_name'][ $key ],
						'error'    => (int) $files['error'][ $key ],
						'size'     => (int) $files['size'][ $key ],
					];
					$_FILES = [
						'blc-review-images' => $file
					];

					// phpcs:ignore WordPress.Security.NonceVerification.Missing
					foreach ($_FILES as $file => $array) {
						$attach_id = $this->insert_attachment($file, $comment_id);

						if (! is_wp_error($attach_id) && false !== $attach_id && 0 !== $attach_id) {
							array_push($attachments_array, $attach_id);
						}
					}
				}
			}

			// save review with attachments array.
			if (! empty($attachments_array)) {
				update_comment_meta($comment_id, 'blocksy_comment_meta_images', $attachments_array);
			}
		}
	}

	private function get_user_identifier() {
		return get_current_user_id() ?? (
			isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : ''
		);
	}
}

