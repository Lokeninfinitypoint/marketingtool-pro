<?php

namespace Blocksy\Extensions\PostTypesExtra\DynamicData;

use Blocksy\Extensions\PostTypesExtra\CustomField;

class CustomWPFieldProvider extends BaseProvider {
	public function get_provider_id() {
		return 'custom';
	}

	public function get_provider_label() {
		return __('Custom', 'blocksy-companion');
	}

	public function get_all_fields($args = []) {
		$all_fields = apply_filters(
			'blocksy:pro:post-types-extra:custom:collect-all-fields',
			[]
		);

		$result = [];

		foreach ($all_fields as $field_id => $field_name) {
			$custom_field = new CustomField([
				'id' => $field_id,
				'label' => $field_name ?: $field_id,
				'type' => CustomField::$TYPE_TEXT,
				'provider_id' => $this->get_provider_id()
			]);

			$result[] = $custom_field;
		}

		return $result;
	}

	public function get_post_fields($post_id, $post_type, $args = []) {
		$all_fields = apply_filters(
			'blocksy:pro:post-types-extra:custom:collect-post-fields',
			[],
			$post_id,
			$post_type
		);

		$result = [];

		foreach ($all_fields as $field_id => $field_name) {
			$custom_field = new CustomField([
				'id' => $field_id,
				'label' => $field_name ?: $field_id,
				'type' => CustomField::$TYPE_TEXT,
				'provider_id' => $this->get_provider_id()
			]);

			$result[] = $custom_field;
		}

		return $result;
	}

	public function get_post_type_fields($post_type, $args = []) {
		$all_fields = apply_filters(
			'blocksy:pro:post-types-extra:custom:collect-fields',
			[],
			$post_type
		);

		$result = [];

		foreach ($all_fields as $field_id => $field_name) {
			$custom_field = new CustomField([
				'id' => $field_id,
				'label' => $field_name ?: $field_id,
				'type' => CustomField::$TYPE_TEXT,
				'provider_id' => $this->get_provider_id()
			]);

			$result[] = $custom_field;
		}

		return $result;
	}

	public function get_term_fields($term_id, $args = []) {
		$all_fields = apply_filters(
			'blocksy:pro:post-types-extra:custom:collect-term-fields',
			[],
			$term_id
		);

		$result = [];

		foreach ($all_fields as $field_id => $field_name) {
			if (
				empty($field_id)
				||
				empty($field_name)
			) {
				continue;
			}

			$custom_field = new CustomField([
				'id' => $field_id,
				'label' => $field_name ?: $field_id,
				'type' => CustomField::$TYPE_TEXT,
				'provider_id' => $this->get_provider_id()
			]);

			$result[] = $custom_field;
		}

		return $result;
	}

	public function render($field_id, $args = []) {
		$args = wp_parse_args($args, [
			'context_object' => null,

			'allow_images' => false
		]);

		if (is_numeric($args['context_object'])) {
			$args['context_object'] = get_post($args['context_object']);
		}

		if (
			! $args['context_object']
			||
			is_wp_error($args['context_object'])
		) {
			return null;
		}

		$field = [
			'name' => $field_id,
			'label' => $field_id,
			'type' => CustomField::$TYPE_TEXT,
			'value' => '__DEFAULT__'
		];

		$fields = [];

		if (isset($args['context_object']->post_type)) {
			$fields = $this->get_post_type_fields(
				$args['context_object']->post_type,
				['allow_images' => $args['allow_images']]
			);

			foreach ($fields as $f) {
				if ($f->get_id() === $field_id) {
					$field['label'] = $f->get_label();
					break;
				}
			}
		}

		if (isset($args['context_object']->post_type)) {
			$field['value'] = get_post_meta(
				$args['context_object']->ID,
				$field_id,
				true
			);
		}

		if (isset($args['context_object']->term_id)) {
			$field['value'] = get_term_meta(
				$args['context_object']->term_id,
				$field_id,
				true
			);
		}

		if ($field['value'] === '__DEFAULT__') {
			return null;
		}

		return $field;
	}
}

