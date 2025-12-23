<?php

namespace Blocksy\CustomPostType\Integrations;

class AffiliateBooster extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		// Ignoring this rule becuase it's from 3rd party and we can't add versioning to it.
		// phpcs:disable WordPress.WP.EnqueuedResourceParameters.MissingVersion

		if (has_block('affiliate-booster/ab-advance-button', $this->id)) {
			wp_enqueue_style(
				'affiliate-block-ab-button_blocks-cgb-style-css',
				AFB_URL . 'assets/blocks/ab-btn/style.css'
			);
		}

		if (has_block('affiliate-booster/ab-callto-action', $this->id)) {
			wp_enqueue_style(
				'affiliate-block-ab-cta_blocks-cgb-style-css',
				AFB_URL . 'assets/blocks/ab-cta/style.css'
			);
		}

		if (has_block('affiliate-booster/ab-notice-box', $this->id)) {
			wp_enqueue_style(
				'affiliate-block-ab-notice_blocks-cgb-style-css',
				AFB_URL . 'assets/blocks/ab-notice/style.css'
			);
		}

		if (has_block('affiliate-booster/ab-notification-box', $this->id)) {
			wp_enqueue_style(
				'affiliate-block-ab-notification_blocks-cgb-style-css',

				AFB_URL . 'assets/blocks/ab-notification/style.css'
			);
		}

		if (has_block('affiliate-booster/ab-single-product', $this->id)) {
			wp_enqueue_style(
				'affiliate-block-ab-single-product_blocks-cgb-style-css',
				AFB_URL . 'assets/blocks/ab-single-product/style.css'
			);
		}

		if (has_block('affiliate-booster/ab-star-rating', $this->id)) {
			wp_enqueue_style(
				'affiliate-block-ab-star-rating_blocks-cgb-style-css',
				AFB_URL . 'assets/blocks/ab-star-rating/style.css'
			);
		}

		if (has_block('affiliate-booster/propsandcons', $this->id)) {
			wp_enqueue_style(
				'affiliate-block-propsandcons_blocks-cgb-style-css',
				AFB_URL . 'assets/blocks/propsandcons/style.css'
			);
		}

		if (has_block('affiliate-booster/ab-coupon2', $this->id)) {
			wp_enqueue_style(
				'affiliate-block-ab-coupon2_blocks-cgb-style-css',
				AFB_URL . 'assets/blocks/ab-coupon2/style.css'
			);
		}

		if (has_block('affiliate-booster/ab-icon-list', $this->id)) {
			wp_enqueue_style(
				'affiliate-block-ab-icon-list_blocks-cgb-style-css',
				AFB_URL . 'assets/blocks/ab-icon-list/style.css'
			);
		}

		if (has_block('affiliate-booster/ab-coupon4', $this->id)) {
			wp_enqueue_style(
				'affiliate-block-ab-coupon4_blocks-cgb-style-css',
				AFB_URL . 'assets/blocks/ab-coupon4/style.css'
			);
		}

		// Load the FontAwesome icon library
		wp_enqueue_style(
			'affiliate-block-fontawesome',
			AFB_URL . 'dist/assets/fontawesome/css/all.min.css'
		);

		// phpcs:enable WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}
}

