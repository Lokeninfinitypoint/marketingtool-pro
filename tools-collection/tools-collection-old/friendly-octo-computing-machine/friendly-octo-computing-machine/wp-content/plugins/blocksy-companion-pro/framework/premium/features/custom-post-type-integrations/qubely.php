<?php

namespace Blocksy\CustomPostType\Integrations;

class Qubely extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
	}

	public function pre_output() {
		if (! class_exists('Blocksy_Qubely')) {
			// phpcs:ignore Squiz.PHP.Eval.Discouraged, Generic.PHP.ForbiddenFunctions.Found
			eval("class Blocksy_Qubely extends QUBELY_MAIN {
				public function __construct() {}
			}");
		}

		add_action('wp_enqueue_scripts', function () {
			global $post;
			$old_post = $post;

			$hook = get_post($this->id);

			$post = $hook;
			setup_postdata($hook);

			$qubely_driver = new \Blocksy_Qubely();
			$qubely_driver->qubely_enqueue_style();
			$qubely_driver->qubely_enqueue_scripts();

			wp_reset_postdata();
			$post = $old_post;
		});

		$option_data = get_option( 'qubely_options' );
		$css_save_as = isset(
			$option_data['css_save_as']
		) ? $option_data['css_save_as'] : 'wp_head';

		if ($css_save_as == 'filesystem') {
			add_action(
				'wp_enqueue_scripts',
				function () {
					global $post;
					$old_post = $post;

					$hook = get_post($this->id);

					$post = $hook;
					setup_postdata($hook);

					$qubely_driver = new \Blocksy_Qubely();
					$qubely_driver->enqueue_block_css_file();

					wp_reset_postdata();
					$post = $old_post;
				}
			);
		} else {
			add_action(
				'wp_head',
				function () {
					global $post;
					$old_post = $post;

					$hook = get_post($this->id);

					$post = $hook;
					setup_postdata($hook);

					$qubely_driver = new \Blocksy_Qubely();
					$qubely_driver->add_block_inline_css();

					wp_reset_postdata();
					$post = $old_post;
				},
				100
			);
		}
	}
}

