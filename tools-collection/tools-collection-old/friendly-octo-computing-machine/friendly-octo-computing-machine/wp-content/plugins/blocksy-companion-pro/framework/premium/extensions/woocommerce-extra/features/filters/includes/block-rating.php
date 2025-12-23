<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class RatingBlock {
	public function __construct() {
		add_action('init', [$this, 'blocksy_rating_filter_block']);
    }

	public function blocksy_rating_filter_block() {
		register_block_type('blocksy/woocommerce-rating-filter', [
			'render_callback' => function ($attributes, $content, $block) {
				if (
					! is_woocommerce()
					&&
					! wp_doing_ajax()
					||
					is_singular()
				) {
					return '';
				}

				$ratings = RatingFilter::get_rating_options();
				$rating_values = [];

				foreach ($ratings as $key => $rating) {
					$rating_values[] = [
						'id' => $key,
						'label' => $rating,
						'enabled' => true,
					];
				}

				$attributes = wp_parse_args($attributes, [
                    'exactMatch' => false,
					'showTooltips' => true,
					'showResetButton' => false
				]);

				$filter = Filters::get_filter_instance('rating_filter');

				$presenter = new FilterPresenter($filter);
				return $presenter->render($attributes);
			},
		]);
	}
}
