<?php

namespace Blocksy;

class CodeEditor {
	private $post_types = [
		'ct_content_block',
		'ct_product_tab',
		'ct_size_guide',
		'ct_thank_you_page'
	];

	public function __construct() {
		add_action('admin_body_class', function ($classes) {
			global $pagenow;
			global $post;

			$screen = get_current_screen();

			if ('post-new.php' !== $pagenow && 'post.php' !== $pagenow) {
				return $classes;
			}

			if (! in_array($screen->post_type, $this->post_types)) {
				return $classes;
			}

			$atts = blocksy_get_post_options($post->ID);

			if (blocksy_akg('has_inline_code_editor', $atts, 'no') === 'yes') {
				$classes .= ' blocksy-inline-code-editor';
			}

			return $classes;
		});

		register_block_type('blocksy-companion-pro/code-editor', [
			'api_version' => 3,
			'render_callback' => function ($attributes, $content, $block) {
				if (is_admin()) {
					return '';
				}

				if (! empty($content)) {
					$inline_code = str_replace(
						'<pre class="wp-block-code"><code>',
						'',
						str_replace(
							'</code></pre>',
							'',
							html_entity_decode(htmlspecialchars_decode($content))
						)
					);

					return $this->get_eval_content($inline_code);
				}

				if (empty($attributes['code'])) {
					return '';
				}

				$inline_code = $attributes['code'];

				return $this->get_eval_content($inline_code);
			}
		]);
	}

	public function get_admin_localizations() {
		global $pagenow;
		global $post;

		$screen = get_current_screen();

		$localize = [];

		if ($pagenow === 'post-new.php' || $pagenow === 'post.php') {
			if (
				in_array($screen->post_type, $this->post_types)
				&&
				function_exists('wp_enqueue_code_editor')
			) {
				$localize['editor_settings'] = wp_enqueue_code_editor([
					'type' => 'application/x-httpd-php',
					'codemirror' => [
						'indentUnit' => 2,
						'tabSize' => 2,
					]
				]);
			}
		}

		return $localize;
	}

	private function get_eval_content($inline_code) {
		$ending = '<?php ';

		if (strpos($inline_code, '<?php') !== false) {
			if (strpos($inline_code, '?>') === false) {
				$ending = '';
			}
		}

		$error_message = null;

		ob_start();

		try {
			// phpcs:ignore Generic.PHP.ForbiddenFunctions.Found
			eval('?' . '>' . $inline_code . $ending);
		} catch (\Throwable $e) {
			$error_components = [
				blc_safe_sprintf(
					// translators: %1$s is the error message, %2$s is the line number
					esc_html__('Code execution has been canceled due to error: %1$s on line: %2$s', 'blocksy-companion'),
					'<strong>' . $e->getMessage() . '</strong>',
					$e->getLine()
				)
			];

			$maybe_post_id = CustomPostTypeRenderer::get_current_rendered_post_id();

			if ($maybe_post_id) {
				$post = get_post($maybe_post_id);

				$post_type_object = get_post_type_object($post->post_type);

				if ($post) {
					$error_components[] = blc_safe_sprintf(
						// translators: %1$s is the post type name, %2$s is the post edit link
						esc_html__('%1$s that caused the error: %2$s', 'blocksy-companion'),
						$post_type_object->labels->singular_name,
						blocksy_html_tag(
							'a',
							[
								'href' => get_edit_post_link($maybe_post_id),
								'target' => '_blank'
							],
							$post->post_title
						)
					);
				}
			}

			$error_message = blocksy_html_tag(
				'div',
				[],
				implode('<br>', $error_components)
			);
		}

		$result = ob_get_clean();

		if ($error_message) {
			// TODO: maybe check WP_DEBUG here.
			if (current_user_can('manage_options')) {
				return $error_message;
			}

			return '';
		}

		return $result;
	}
}

