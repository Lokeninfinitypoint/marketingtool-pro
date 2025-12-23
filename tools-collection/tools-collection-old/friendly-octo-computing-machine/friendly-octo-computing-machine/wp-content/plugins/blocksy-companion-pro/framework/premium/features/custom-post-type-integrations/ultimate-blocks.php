<?php

namespace Blocksy\CustomPostType\Integrations;

class UltimateBlocks extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		add_action( 'enqueue_block_assets', function () {
			ub_load_assets();
		});

		add_action('wp_head', function () {
			if (! function_exists('ub_include_block_attribute_css')) {
				return;
			}

			global $post;
			$post = get_post($this->id);
			setup_postdata($post);
			ub_include_block_attribute_css();
			wp_reset_postdata();
		});

		$blocks_dir = ULTIMATE_BLOCKS_URL . '/src/blocks/';

		if (has_block('ub/advanced-video', $this->id)) {
			wp_enqueue_script(
				'ultimate_blocks-advanced-video-front-script',
				$blocks_dir . 'advanced-video/front.build.js',
				[],
				ULTIMATE_BLOCKS_VERSION,
				true
			);
		}

		if (
			has_block('ub/button', $this->id)
			||
			has_block('ub/button-block', $this->id)
		) {
            wp_enqueue_script(
                'ultimate_blocks-button-front-script',
                $blocks_dir . 'button/front.build.js',
                [],
                ULTIMATE_BLOCKS_VERSION,
                true
            );
        }

		if (
			has_block('ub/content-filter', $this->id)
			||
			has_block('ub/content-filter-block', $this->id)
		) {
            wp_enqueue_script(
                'ultimate_blocks-content-filter-front-script',
                $blocks_dir . 'content-filter/front.build.js',
                [],
                ULTIMATE_BLOCKS_VERSION,
                true
            );
        }

		if (
			has_block('ub/content-toggle', $this->id)
			|| 
			has_block('ub/content-toggle-panel', $this->id)
			|| 
			has_block('ub/content-toggle-block', $this->id)
			|| 
			has_block('ub/content-toggle-panel-block', $this->id)
		) {

			wp_enqueue_script(
				'ultimate_blocks-content-toggle-front-script',
				$blocks_dir . 'content-toggle/front.build.js',
				[],
				ULTIMATE_BLOCKS_VERSION,
				true
			);
		}

		if (has_block('ub/countdown', $this->id)) {
            wp_enqueue_script(
                'ultimate_blocks-countdown-script',
                $blocks_dir . 'countdown/front.build.js',
                [],
                ULTIMATE_BLOCKS_VERSION,
                true
            );
        }

		if (
			has_block('ub/expand', $this->id)
			||
			has_block('ub/expand-portion', $this->id)
		) {
            wp_enqueue_script(
                'ultimate_blocks-expand-block-front-script',
                $blocks_dir . 'expand/front.build.js',
                [],
                ULTIMATE_BLOCKS_VERSION,
                true
            );
        }

		if (has_block('ub/image-slider', $this->id)) {
			wp_enqueue_script(
				'ultimate_blocks-image-slider-init-script',
				$blocks_dir . '/src/front.build.js',
				['ultimate_blocks-swiper'],
				ULTIMATE_BLOCKS_VERSION,
				true
			);
		}

		if (has_block('ub/progress-bar', $this->id)) {
            wp_enqueue_script(
                'ultimate_blocks-progress-bar-front-script',
                $blocks_dir . 'progress-bar/front.build.js',
                [],
				ULTIMATE_BLOCKS_VERSION,
                true
            );
        }

		if (
			has_block('ub/tabbed-content', $this->id)
			|| 
			has_block('ub/tabbed-content-block', $this->id)
		) {
            wp_enqueue_script(
                'ultimate_blocks-tabbed-content-front-script',
				$blocks_dir . 'tabbed-content/front.build.js',
                [],
				ULTIMATE_BLOCKS_VERSION,
                true
            );
        }

		if (
			has_block('ub/table-of-contents', $this->id)
			|| 
			has_block('ub/table-of-contents-block', $this->id) 
		){
            wp_enqueue_script(
                'ultimate_blocks-table-of-contents-front-script',
                $blocks_dir . 'table-of-contents/front.build.js',
                [],
				ULTIMATE_BLOCKS_VERSION,
                true
            );
		}
	}
}


