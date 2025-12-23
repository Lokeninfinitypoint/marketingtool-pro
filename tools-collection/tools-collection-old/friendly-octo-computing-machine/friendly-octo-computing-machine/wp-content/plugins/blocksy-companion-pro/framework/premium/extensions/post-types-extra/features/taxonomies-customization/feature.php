<?php

namespace Blocksy\Extensions\PostTypesExtra;

class TaxonomiesCustomization {
	public function __construct() {
		add_action('init', [$this, 'init_taxonomies'], 999);

		add_filter(
			'blocksy:general:blocks:dynamic-data:data',
			function ($dynamic_data) {
				$dynamic_data['has_taxonomies_customization'] = true;
				return $dynamic_data;
			}
		);

		add_filter(
			'blocksy:options:page-title:archives-have-hero',
			'__return_true'
		);

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (! empty($_POST)) {
			add_action('edited_term', [$this, 'edited_term'], 10, 3);
		}

		// Regenerate dynamic CSS on migration end and customizer save,
		// if it doesn't exist already.
		add_action(
			'blocksy:dynamic-css:refresh-caches',
			function () {
				$file_descriptor = $this->get_file_descriptor();

				if (! $file_descriptor) {
					return;
				}

				if (file_exists($file_descriptor['file'])) {
					return;
				}

				$this->regenerate_dynamic_css();
			}
		);

		add_action(
			'blocksy:demo-install:finish',
			function () {
				$file_descriptor = $this->get_file_descriptor();

				if (! $file_descriptor) {
					return;
				}

				$this->regenerate_dynamic_css();
			}
		);

		add_action('init', [$this, 'enqueue_dynamic_css']);
	}

	public function init_taxonomies() {
		$current_edit_taxonomy = $this->get_current_edit_taxonomy();

		$maybe_taxonomy = get_taxonomy($current_edit_taxonomy['taxonomy']);

		if ($maybe_taxonomy) {
			if (in_array('product', $maybe_taxonomy->object_type)) {
				if (
					$maybe_taxonomy->name === 'product_cat'
					||
					$maybe_taxonomy->name === 'product_tag'
					||
					$maybe_taxonomy->name === 'product_brands'
					||
					$maybe_taxonomy->name === 'product_brand'
					||
					(strpos($maybe_taxonomy->name, 'pa_') === 0)
				) {
					return;
				}
			}
		}

		add_action(
			$current_edit_taxonomy['taxonomy'] . '_edit_form',
			function ($term) {
				$values = get_term_meta(
					$term->term_id,
					'blocksy_taxonomy_meta_options'
				);

				if (empty($values)) {
					$values = [[]];
				}

				if (! $values[0]) {
					$values[0] = [];
				}

				$options = [
					'image' => [
						'label' => __('Featured Image', 'blocksy-companion'),
						'type' => 'ct-image-uploader',
						'value' => '',
						'attr' => ['data-type' => 'large'],
						'emptyLabel' => __('Select Image', 'blocksy-companion'),
					],

					'icon_image' => [
						'label' => __('Featured Icon/Logo', 'blocksy-companion'),
						'type' => 'ct-image-uploader',
						'value' => '',
						'attr' => [
							'data-type' => 'large'
						],
						'emptyLabel' => __('Select Image', 'blocksy-companion'),
					],

					'accent_color' => [
						'label' => __('Accent Color', 'blocksy-companion'),
						'type' => 'ct-color-picker',

						'value' => [
							'default' => [
								'color' => 'CT_CSS_SKIP_RULE'
							],

							'hover' => [
								'color' => 'CT_CSS_SKIP_RULE'
							],

							'background_initial' => [
								'color' => 'CT_CSS_SKIP_RULE'
							],

							'background_hover' => [
								'color' => 'CT_CSS_SKIP_RULE'
							],

						],

						'pickers' => [
							[
								'title' => __('Text Initial', 'blocksy-companion'),
								'id' => 'default'
							],

							[
								'title' => __('Text Hover', 'blocksy-companion'),
								'id' => 'hover'
							],

							[
								'title' => __('Background Initial', 'blocksy-companion'),
								'id' => 'background_initial'
							],

							[
								'title' => __('Background Hover', 'blocksy-companion'),
								'id' => 'background_hover'
							],
						],
					]
				];

				blocksy_html_tag_e(
					'div',
					[],
					blocksy_html_tag(
						'input',
						[
							'type' => 'hidden',
							'value' => htmlspecialchars(
								wp_json_encode($values[0])
							),
							'data-options' => htmlspecialchars(
								wp_json_encode($options)
							),
							'name' => 'blocksy_taxonomy_meta_options[' . blocksy_post_name() . ']',
						]
					)
				);
			}
		);
	}

	private function get_current_edit_taxonomy() {
		static $cache_current_taxonomy_data = null;

		if ($cache_current_taxonomy_data !== null) {
			return $cache_current_taxonomy_data;
		}

		$result = array(
			'taxonomy' => null,
			'term_id'  => 0,
		);

		do {
			if (! is_admin()) {
				break;
			}

			// code from /wp-admin/admin.php line 110
			{
				if (
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					isset($_REQUEST['taxonomy'])
					&&
					taxonomy_exists(
						// phpcs:ignore WordPress.Security.NonceVerification.Recommended
						sanitize_text_field(wp_unslash($_REQUEST['taxonomy']))
					)
				) {
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$taxnow = sanitize_text_field(wp_unslash($_REQUEST['taxonomy']));
				} else {
					$taxnow = '';
				}
			}

			if (empty($taxnow)) {
				break;
			}

			$result['taxonomy'] = $taxnow;

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if (empty($_REQUEST['tag_ID'])) {
				return $result;
			}

			// code from /wp-admin/edit-tags.php
			{
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$tag_ID = (int) $_REQUEST['tag_ID'];
			}

			$result['term_id'] = $tag_ID;
		} while (false);

		$cache_current_taxonomy_data = $result;
		return $cache_current_taxonomy_data;
	}

	public function get_terms_dynamic_styles() {
		$css = new \Blocksy_Css_Injector();
		$tablet_css = new \Blocksy_Css_Injector();
		$mobile_css = new \Blocksy_Css_Injector();

		if (! blc_theme_functions()->blocksy_manager()) {
			return '';
		}

		$custom_post_types = blc_theme_functions()->blocksy_manager()
			->post_types
			->get_supported_post_types();

		$custom_post_types[] = 'post';

		foreach ($custom_post_types as $post_type) {
			$taxonomies = array_values(array_diff(
				get_object_taxonomies($post_type),
				['post_format']
			));

			foreach ($taxonomies as $taxonomy) {
				$all_terms = blc_theme_functions()->blocksy_get_terms(
					[
						'taxonomy' => $taxonomy,
						'update_term_meta_cache' => false,
						// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						'meta_query' => [
							[
								'key' => 'blocksy_taxonomy_meta_options',
								'value' => "accent_color",
								'compare' => 'LIKE'
							]
						]
					],
					[
						'all_languages' => true
					]
				);

				if ($all_terms === \Blocksy\ThemeFunctions::$NON_EXISTING_FUNCTION) {
					$all_terms = [];
				}

				foreach ($all_terms as $term) {
					$values = get_term_meta(
						$term->term_id,
						'blocksy_taxonomy_meta_options'
					);

					if (empty($values)) {
						$values = [[]];
					}

					blocksy_theme_get_dynamic_styles([
						'path' => dirname(__FILE__) . '/global.php',
						'css' => $css,
						'tablet_css' => $tablet_css,
						'mobile_css' => $mobile_css,
						'context' => 'inline',
						'chunk' => 'inline',
						'forced_call' => true,
						'atts' => $values[0],
						'root_selector' => ['.ct-term-' . $term->term_id]
					]);
				}
			}
		}

		return $css->build_css_structure();
	}

	public function edited_term($term_id, $tt_id, $taxonomy) {
		if (
			!(
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				isset($_POST['action'])
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				'editedtag' === $_POST['action']
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				isset($_POST['taxonomy'])
				&&
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				($taxonomy = get_taxonomy(sanitize_text_field(wp_unslash($_POST['taxonomy']))))
				&&
				current_user_can($taxonomy->cap->edit_terms)
			)
		) {
			return;
		}

		if (
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			isset($_POST['tag_ID'])
			&&
			intval(
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				sanitize_text_field(wp_unslash($_POST['tag_ID']))
			) !== $term_id
		) {
			return;
		}

		$values = [];

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if (isset($_POST['blocksy_taxonomy_meta_options'][blocksy_post_name()])) {
			$values = json_decode(
				sanitize_text_field(
					wp_unslash(
						// phpcs:ignore WordPress.Security.NonceVerification.Missing
						$_POST['blocksy_taxonomy_meta_options'][blocksy_post_name()]
					)
				),
				true
			);
		}

		update_term_meta(
			$term_id,
			'blocksy_taxonomy_meta_options',
			$values
		);

		$this->regenerate_dynamic_css();
	}

	public function regenerate_dynamic_css() {
		$file_descriptor = $this->get_file_descriptor();

		if (! $file_descriptor) {
			return;
		}

		$wp_filesystem = \Blocksy\Plugin::instance()->dynamic_css->get_wp_filesystem();

		if ($wp_filesystem) {
			$wp_filesystem->put_contents(
				$file_descriptor['file'],
				$this->get_terms_dynamic_styles()
			);
		}
	}

	public function enqueue_dynamic_css() {
		if (! blc_theme_functions()->blocksy_has_dynamic_css_in_frontend()) {
			return;
		}

		$file_descriptor = $this->get_file_descriptor();

		if (! $file_descriptor) {
			return;
		}

		if (
			! file_exists($file_descriptor['file'])
			||
			filesize($file_descriptor['file']) === 0
		) {
			return;
		}

		// add_editor_style(set_url_scheme($file_descriptor['url']));

		if (is_admin()) {
			return;
		}

		wp_enqueue_style(
			'blocksy-dynamic-' . pathinfo($file_descriptor['filename'], PATHINFO_FILENAME),
			set_url_scheme($file_descriptor['url']),
			[],
			substr((string) filemtime($file_descriptor['file']), -5, 5)
		);
	}

	private function get_file_descriptor() {
		$theme_paths = \Blocksy\Plugin::instance()
			->dynamic_css
			->maybe_prepare_theme_uploads_path();

		if (! $theme_paths) {
			return null;
		}

		$filename = 'taxonomies.css';

		$file = $theme_paths['css_path'] . '/' . $filename;
		$url = $theme_paths['css_url'] . '/' . $filename;

		return [
			'file' => $file,
			'url' => $url,
			'filename' => $filename
		];
	}
}

