<?php

namespace Blocksy\CustomPostType\Integrations;

class Brizy extends \Blocksy\CustomPostTypeRenderer {
	public function get_content($args = []) {
		if (
			! in_array(
				get_post_type($this->id),
				\Brizy_Editor::get()->supported_post_types()
			)
		) {
			return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
		}

		$post = \Brizy_Editor_Post::get($this->id);

		if (
			! $post
			||
			! $post->uses_editor()
		) {
			return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
		}

		$project = \Brizy_Editor_Project::get();

		if (! method_exists($post, 'get_compiled_scripts')) {
			return \Blocksy\CustomPostTypeRenderer::NOT_IMPLEMENTED;
		}

		if (version_compare(BRIZY_VERSION, '2.7.1', '>=')) {
			$content = $post->getCompiledHtml();
		} elseif ($post->get_compiled_scripts()) {
			$content = $post->get_compiled_html();
		} else {
			if (! $post->get_compiled_html()) {
				$compiled_html_body = $post->get_compiled_html_body();
				$content = \Brizy_SiteUrlReplacer::restoreSiteUrl($compiled_html_body);
				$post->set_needs_compile(true)->saveStorage();
			} else {
				$compiled_page = $post->get_compiled_page();
				$content = $compiled_page->get_body();
			}
		}

		return apply_filters(
			'brizy_content',
			$content,
			$project,
			$post->getWpPost(),
			'body'
		);
	}

	public function pre_output() {
		if (
			! in_array(
				get_post_type($this->id),
				\Brizy_Editor::get()->supported_post_types()
			)
		) {
			return;
		}

		$post = \Brizy_Editor_Post::get($this->id);

		if (! $post->uses_editor()) {
			return;
		}

		if (
			! class_exists('\Brizy_Public_AssetEnqueueManager')
			||
			! class_exists('\Brizy_Editor_Compiler')
		) {
			return;
		}

		try {
			$compiler = new \Brizy_Editor_Compiler(
				\Brizy_Editor_Project::get(),
				new \Brizy_Admin_Blocks_Manager(\Brizy_Admin_Blocks_Main::CP_GLOBAL),
				new \Brizy_Editor_UrlBuilder(\Brizy_Editor_Project::get(), $post),
				\Brizy_Config::getCompilerUrls(),
				\Brizy_Config::getCompilerDownloadUrl()
			);

			if ($compiler->needsCompile($post)) {
				$editgorConfig = \Brizy_Editor_Editor_Editor::get(
					\Brizy_Editor_Project::get(),
					$post
				)
					->config(\Brizy_Editor_Editor_Editor::COMPILE_CONTEXT);

				$compiler->compilePost($post, $editgorConfig);
			}
		} catch (Exception $e) {
			\Brizy_Logger::instance()->exception($e);
		}

		\Brizy_Public_AssetEnqueueManager::_init()->enqueuePost($post);

		add_filter(
			'body_class',
			function ($classes) {
				$classes[] = 'brz';

				if (function_exists('wp_is_mobile') && wp_is_mobile()) {
					$classes[] = 'brz-is-mobile';
				}

				return array_unique($classes);
			}
		);
	}
}

