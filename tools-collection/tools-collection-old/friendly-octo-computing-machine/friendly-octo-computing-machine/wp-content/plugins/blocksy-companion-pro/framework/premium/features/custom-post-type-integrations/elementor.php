<?php

namespace Blocksy\CustomPostType\Integrations;

class Elementor extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		$args = wp_parse_args($args, [
			'switch_global_post' => false
		]);

		if (
			! \Elementor\Plugin::$instance->documents->get($this->id)
			||
			! \Elementor\Plugin::$instance->documents->get($this->id)->is_built_with_elementor()
		) {
			return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
		}

		$switch = function () {
			global $post;

			if (class_exists('\ElementorPro\Plugin')) {
				\ElementorPro\Plugin::elementor()->documents->switch_to_document(
					\Elementor\Plugin::$instance->documents->get($post->ID)
				);
			}
		};

		if ($args['switch_global_post']) {
			add_action(
				'elementor/frontend/before_get_builder_content',
				$switch
			);
		}

		global $blocksy_elementor_hook_was_rendered;
		$blocksy_elementor_hook_was_rendered = true;

		$result = \Elementor\Plugin::$instance
			->frontend
			->get_builder_content_for_display($this->id);

		if ($args['switch_global_post']) {
			remove_action(
				'elementor/frontend/before_get_builder_content',
				$switch
			);
		}

		return $result;
	}

	public function pre_output() {
		if (
			\Elementor\Plugin::$instance->documents->get($this->id)
			&&
			\Elementor\Plugin::$instance->documents->get($this->id)->is_built_with_elementor()
			&&
			! (
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				isset($_POST['wp_customize_render_partials'])
				||
				wp_doing_ajax()
			)
		) {
			$document = \Elementor\Plugin::$instance->documents->get_doc_for_frontend(
				$this->id
			);

			// Change the current post, so widgets can use `documents->get_current`.
			\Elementor\Plugin::$instance->documents->switch_to_document($document);
			$data = $document->get_elements_data();
			$data = apply_filters('elementor/frontend/builder_content_data', $data, $this->id);

			add_action('wp_enqueue_scripts', function () {
				$f = \Elementor\Plugin::$instance
					->frontend;

				if ($f->has_elementor_in_page()) {
					return;
				}

				$f->enqueue_styles();

			});

            /*
			if (! empty($data)) {
				if ($document->is_autosave()) {
					$css_file = new \Elementor\Core\Files\CSS\Post_Preview(
						$document->get_post()->ID
					);
				} else {
					$css_file = new \Elementor\Core\Files\CSS\Post($id);
				}

				$css_file->enqueue();
			}
             */

			add_action(
				'wp_footer',
				function () {
					// Preload fonts & icons links if the hook will be
					// generated dynamically via AJAX.
					global $blocksy_elementor_hook_was_rendered;

					if (! $blocksy_elementor_hook_was_rendered) {
						\Elementor\Plugin::$instance
							->frontend
							->get_builder_content_for_display($this->id);
					}

					$document = \Elementor\Plugin::$instance->documents->get_doc_for_frontend(
						$this->id
					);

					if ($document->is_autosave()) {
						$css_file = new \Elementor\Core\Files\CSS\Post_Preview(
							$document->get_post()->ID
						);
					} else {
						$css_file = new \Elementor\Core\Files\CSS\Post($this->id);
					}

					$css_file->print_css();

					$f = \Elementor\Plugin::$instance
						->frontend;

					if ($f->has_elementor_in_page()) {
						return;
					}

					$f->enqueue_styles();
					$f->enqueue_scripts();

					$f->print_fonts_links();
				}
			);
		}
	}
}
