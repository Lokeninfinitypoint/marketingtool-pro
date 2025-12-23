<?php

class BlocksyExtensionCodeSnippets {
	public function __construct() {
		add_action('wp_head', function () {
			$this->output_scripts('header_scripts');
		}, 50);

		add_action('wp_body_open', function () {
			$this->output_scripts('header_after_body_scripts');
		}, 50);

		add_action('wp_footer', function () {
			$this->output_scripts('footer_scripts');
		}, 5);

		add_filter('blocksy_extensions_metabox_post_bottom', function ($opts) {
			if (! current_user_can(
				blc_get_capabilities()->get_wp_capability_by('ext_code_snippets_fields')
			)) {
				return $opts;
			}

			$opts[blocksy_rand_md5()] = [
				'type' => 'ct-divider',
			];

			$opts['header_footer_scripts_panel'] = [
				//  translators: This is a brand name. Preferably to not be translated
				'label' => _x('Custom Code Snippets', 'Extension Brand Name', 'blocksy-companion'),
				'type' => 'ct-panel',
				'wrapperAttr' => [ 'data-label' => 'heading-label' ],
				'setting' => [ 'transport' => 'postMessage' ],
				'inner-options' => [

					'header_scripts' => [
						'label' => __( 'Header scripts', 'blocksy-companion' ),
						'type' => 'textarea',
						'value' => '',
						'attr' => [ 'data-resize' => 'resize-y' ],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],

					'header_after_body_scripts' => [
						'label' => __( 'After body open scripts', 'blocksy-companion' ),
						'type' => 'textarea',
						'value' => '',
						'attr' => [ 'data-resize' => 'resize-y' ],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],

					'footer_scripts' => [
						'label' => __( 'Footer scripts', 'blocksy-companion' ),
						'type' => 'textarea',
						'value' => '',
						'attr' => [ 'data-resize' => 'resize-y' ],
					],
				]
			];

			return $opts;
		});

		add_filter('blocksy:posts:meta:blog-special-keys', function ($keys) {
			$keys[] = 'header_scripts';
			$keys[] = 'header_after_body_scripts';
			$keys[] = 'footer_scripts';

			return $keys;
		});

		add_filter('blocksy_extensions_metabox_page_bottom', function ($opts) {
			if (! current_user_can(
				blc_get_capabilities()->get_wp_capability_by('ext_code_snippets_fields')
			)) {
				return $opts;
			}

			$opts[blocksy_rand_md5()] = [
				'type' => 'ct-divider',
			];

			$opts['header_footer_scripts_panel'] = [
				//  translators: This is a brand name. Preferably to not be translated
				'label' => _x('Custom Code Snippets', 'Extension Brand Name', 'blocksy-companion'),
				'type' => 'ct-panel',
				'wrapperAttr' => [ 'data-label' => 'heading-label' ],
				'setting' => [ 'transport' => 'postMessage' ],
				'inner-options' => [

					'header_scripts' => [
						'label' => __( 'Header scripts', 'blocksy-companion' ),
						'type' => 'textarea',
						'value' => '',
						'attr' => [ 'data-resize' => 'resize-y' ],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],

					'header_after_body_scripts' => [
						'label' => __( 'After body open scripts', 'blocksy-companion' ),
						'type' => 'textarea',
						'value' => '',
						'attr' => [ 'data-resize' => 'resize-y' ],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],

					'footer_scripts' => [
						'label' => __( 'Footer scripts', 'blocksy-companion' ),
						'type' => 'textarea',
						'value' => '',
						'attr' => [ 'data-resize' => 'resize-y' ],
					],
				]
			];

			return $opts;
		});

		add_filter('blocksy_extensions_customizer_options', function ($opts) {
			$opts['header_footer_scripts'] = [
				//  translators: This is a brand name. Preferably to not be translated
				'title' => _x('Custom Code Snippets', 'Extension Brand Name', 'blocksy-companion'),
				'container' => [ 'priority' => 8 ],
				'options' => [
					'header_footer_scripts_section_options' => [
						'type' => 'ct-options',
						'setting' => [ 'transport' => 'postMessage' ],
						'inner-options' => [

							'header_scripts' => [
								'label' => __( 'Header scripts', 'blocksy-companion' ),
								'type' => 'textarea',
								'value' => '',
								'attr' => [ 'data-resize' => 'resize-y' ],
							],

							blocksy_rand_md5() => [
								'type' => 'ct-divider',
							],

							'header_after_body_scripts' => [
								'label' => __( 'After body open scripts', 'blocksy-companion' ),
								'type' => 'textarea',
								'value' => '',
								'attr' => [ 'data-resize' => 'resize-y' ],
							],

							blocksy_rand_md5() => [
								'type' => 'ct-divider',
							],

							'footer_scripts' => [
								'label' => __( 'Footer scripts', 'blocksy-companion' ),
								'type' => 'textarea',
								'value' => '',
								'attr' => [ 'data-resize' => 'resize-y' ],
							],
						],
					],
				]
			];

			return $opts;
		});
	}

	private function output_scripts($id) {
		$scripts = blc_theme_functions()->blocksy_get_theme_mod($id, '');

		if (! empty($scripts)) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $scripts;
		}

		global $post;

		if ($post && function_exists('blocksy_get_post_options')) {
			$atts = blocksy_get_post_options($post->ID);

			if (is_singular() && ! empty(blocksy_akg($id, $atts, ''))) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo blocksy_akg($id, $atts, '');
			}
		}
	}
}

