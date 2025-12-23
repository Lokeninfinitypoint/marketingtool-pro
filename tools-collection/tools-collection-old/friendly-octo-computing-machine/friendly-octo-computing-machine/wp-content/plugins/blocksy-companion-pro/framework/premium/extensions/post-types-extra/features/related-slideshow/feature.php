<?php

namespace Blocksy\Extensions\PostTypesExtra;

class RelatedSlideshow {
	public function __construct() {
		add_filter(
			'blocksy_customizer_options:single:related:before',
			function ($opts, $prefix, $post_type) {
				$opts = [
					$prefix . 'related_posts_slideshow' => [
						'label' => __('Type', 'blocksy-companion'),
						'type' => 'ct-radio',
						'value' => 'default',
						'view' => 'text',
						'design' => 'block',
						'divider' => 'bottom',
						'choices' => [
							'default' => __('Default', 'blocksy-companion'),
							'slider' => __('Slider', 'blocksy-companion'),
						],
						'sync' => [
							[
								'prefix' => $prefix,
								'selector' => '.ct-related-posts',
								'render' => function () {
									blocksy_related_posts();
								}
							],
						]
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [
							$prefix . 'related_posts_slideshow' => 'slider',
						],
						'options' => [
							blocksy_rand_md5() => [
								'type' => 'ct-group',
								'label' => __('Columns & Posts', 'blocksy-companion'),
								'attr' => ['data-columns' => '2:medium'],
								'responsive' => true,
								'hasGroupRevertButton' => true,
								'options' => [

									$prefix . 'related_posts_slideshow_columns' => [
										'label' => false,
										'desc' => __('Number of columns', 'blocksy-companion'),
										'type' => 'ct-number',
										'value' => [
											'desktop' => 3,
											'tablet' => 2,
											'mobile' => 1,
											'__changed' => ['tablet', 'mobile']
										],
										'min' => 1,
										'max' => 6,
										'design' => 'block',
										'attr' => ['data-width' => 'full'],
										'responsive' => true,
										'skipResponsiveControls' => true,
										'sync' => 'live',
									],

									$prefix . 'related_posts_slideshow_number_of_items' => [
										'label' => false,
										'desc' => __('Number of posts', 'blocksy-companion'),
										'type' => 'ct-number',
										'value' => 6,
										'min' => 1,
										'max' => 50,
										'design' => 'block',
										'attr' => ['data-width' => 'full'],
										'markAsAutoFor' => ['tablet', 'mobile'],
										'sync' => [
											[
												'prefix' => $prefix,
												'selector' => '.ct-related-posts',
												'render' => function () {
													blocksy_related_posts();
												}
											],
										]
									],
								],
							],

							$prefix . 'related_posts_slideshow_autoplay' => [
								'type' => 'ct-switch',
								'label' => __('Autoplay', 'blocksy-companion'),
								'value' => 'no',
								'divider' => 'top',
								'sync' => [
									[
										'prefix' => $prefix,
										'selector' => '.ct-related-posts',
										'render' => function () {
											blocksy_related_posts();
										}
									],
								]
							],

							blocksy_rand_md5() => [
								'type' => 'ct-condition',
								'condition' => [
									$prefix . 'related_posts_slideshow_autoplay' => 'yes',
								],
								'options' => [
									$prefix . 'related_posts_slideshow_autoplay_speed' => [
										'label' => __('Delay (Seconds)', 'blocksy-companion'),
										'desc' => __('Specify the amount of time (in seconds) to delay between automatically cycling an item.', 'blocksy-companion'),
										'type' => 'ct-number',
										'value' => 3,
										'min' => 1,
										'max' => 10,
										'design' => 'inline',
										'sync' => [
											[
												'prefix' => $prefix,
												'selector' => '.ct-related-posts',
												'render' => function () {
													blocksy_related_posts();
												}
											],
										]
									],
								],
							],
						],
					],
				];

				return $opts;
			},
			1,
			3
		);

		add_action('blocksy:global-dynamic-css:enqueue', function ($args) {
			if (! blc_theme_functions()->blocksy_manager()) {
				return;
			}

			blocksy_theme_get_dynamic_styles(array_merge([
				'path' => dirname(__FILE__) . '/global.php',
				'chunk' => 'global',
				'prefixes' => blc_theme_functions()->blocksy_manager()->screen->get_single_prefixes()
			], $args));
		}, 10, 3);

		add_filter('blocksy:related-posts:container-attributes', function($atts) {
			if (! blc_theme_functions()->blocksy_manager()) {
				return $atts;
			}

			$prefix = blc_theme_functions()->blocksy_manager()->screen->get_prefix();

			if (blc_theme_functions()->blocksy_get_theme_mod($prefix . '_related_posts_slideshow', 'default') === 'slider') {
				$atts['class'] .= ' flexy-items';
				unset($atts['data-layout']);
			}

			return $atts;
		});

		add_filter('blocksy:related-posts:item-attributes', function($atts) {
			if (! blc_theme_functions()->blocksy_manager()) {
				return $atts;
			}

			$prefix = blc_theme_functions()->blocksy_manager()->screen->get_prefix();

			if (blc_theme_functions()->blocksy_get_theme_mod($prefix . '_related_posts_slideshow', 'default') === 'slider') {
				$atts['class'] = 'flexy-item';
			}

			return $atts;
		});

		add_action('blocksy:single:related_posts:before_loop', function() {
			if (! blc_theme_functions()->blocksy_manager()) {
				return;
			}

			$prefix = blc_theme_functions()->blocksy_manager()->screen->get_prefix();

			if (blc_theme_functions()->blocksy_get_theme_mod($prefix . '_related_posts_slideshow', 'default') !== 'slider') {
				return;
			}

			$attr = [
				'class' => 'flexy-container',
				'data-flexy' => 'no',
			];

			if (blc_theme_functions()->blocksy_get_theme_mod($prefix . '_related_posts_slideshow_autoplay', 'no') === 'yes') {
				$attr['data-autoplay'] = blc_theme_functions()->blocksy_get_theme_mod(
					$prefix . '_related_posts_slideshow_autoplay_speed',
					3
				);
			}

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<div ' . blocksy_attr_to_html($attr) . '>';
			echo '<div class="flexy">';
			echo '<div class="flexy-view" data-flexy-view="boxed">';
		});

		add_action('blocksy:single:related_posts:after_loop', function() {
			if (! blc_theme_functions()->blocksy_manager()) {
				return;
			}

			$prefix = blc_theme_functions()->blocksy_manager()->screen->get_prefix();

			if (blc_theme_functions()->blocksy_get_theme_mod($prefix . '_related_posts_slideshow', 'default') !== 'slider') {
				return;
			}

			$arrow_icons = apply_filters(
				'blocksy:flexy:arrows',
				[
					'prev' => '<svg width="16" height="10" fill="currentColor" viewBox="0 0 16 10"><path d="M15.3 4.3h-13l2.8-3c.3-.3.3-.7 0-1-.3-.3-.6-.3-.9 0l-4 4.2-.2.2v.6c0 .1.1.2.2.2l4 4.2c.3.4.6.4.9 0 .3-.3.3-.7 0-1l-2.8-3h13c.2 0 .4-.1.5-.2s.2-.3.2-.5-.1-.4-.2-.5c-.1-.1-.3-.2-.5-.2z"></path></svg>',
					'next' => '<svg width="16" height="10" fill="currentColor" viewBox="0 0 16 10"><path d="M.2 4.5c-.1.1-.2.3-.2.5s.1.4.2.5c.1.1.3.2.5.2h13l-2.8 3c-.3.3-.3.7 0 1 .3.3.6.3.9 0l4-4.2.2-.2V5v-.3c0-.1-.1-.2-.2-.2l-4-4.2c-.3-.4-.6-.4-.9 0-.3.3-.3.7 0 1l2.8 3H.7c-.2 0-.4.1-.5.2z"></path></svg>'
				]
			);

			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>
					<span class="' . trim('flexy-arrow-prev' . ' ' . '') . '">' . $arrow_icons['prev'] . '</span>
					<span class="' . trim('flexy-arrow-next' . ' ' . '') . '">' . $arrow_icons['next'] . '</span>
				</div>
			</div>';
			// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
		});
	}
}
