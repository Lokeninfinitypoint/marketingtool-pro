<?php

namespace Blocksy\Extensions\PostTypesExtra\DynamicData;

abstract class BaseProvider {
	abstract public function get_provider_id();
	abstract public function get_provider_label();

	abstract function get_all_fields($args = []);
	abstract function get_post_fields($post_id, $post_type, $args = []);
	abstract function get_post_type_fields($post_type, $args = []);
	abstract function get_term_fields($term_id, $args = []);

	// When rendering, we should:
	//
	// 1. Provide field as string. Provider will find the field internally by
	//    discovering the current context.
	//
	// 2. In $args we provide:
	//     - `allow_images` - if images are allowed to be rendered.
	//     - `context_object` - int, WP_Post or WP_Term object to use as context.
	abstract function render($field_id, $args = []);

	public function get_fields($context, $args = []) {
		$allowed_context_types = [
			'all',

			'post',
			'post_type',

			'term'
		];

		if (
			! isset($context['type'])
			||
			! in_array($context['type'], $allowed_context_types, true)
		) {
			return [];
		}

		if (
			$context['type'] === 'post'
			&&
			isset($context['post_id'])
			&&
			isset($context['post_type'])
		) {
			if (! get_post($context['post_id'])) {
				$context = ['type' => 'all'];
			}
		}

		if ($context['type'] === 'all') {
			return $this->get_all_fields($args);
		}

		if ($context['type'] === 'post') {
			if (
				! isset($context['post_id'])
				||
				! isset($context['post_type'])
			) {
				return [];
			}

			return $this->get_post_fields(
				$context['post_id'],
				$context['post_type'],
				$args
			);
		}

		if ($context['type'] === 'post_type') {
			if (! isset($context['post_type'])) {
				return [];
			}

			return $this->get_post_type_fields(
				$context['post_type'],
				$args
			);
		}

		if ($context['type'] === 'term') {
			if (! isset($context['term_id'])) {
				return [];
			}

			return $this->get_term_fields(
				$context['term_id'],
				$args
			);
		}

		return [];
	}
}

