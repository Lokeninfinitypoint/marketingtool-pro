<?php

namespace Blocksy\Extensions\PostTypesExtra\DynamicData;

use Blocksy\Extensions\PostTypesExtra\CustomField;

class MetaBoxProvider extends BaseProvider {
	public function get_provider_id() {
		return 'metabox';
	}

	public function get_provider_label() {
		return 'Meta Box';
	}

	public function get_all_fields($args = []) {
		if (! function_exists('rwmb_get_registry')) {
			return [];
		}

		$metabox_fields = [];

		$post_meta_boxes = rwmb_get_registry('meta_box')->get_by(['object_type' => 'post']);
		$taxonomy_meta_boxes = rwmb_get_registry('meta_box')->get_by(['object_type' => 'term']);

		$all_meta_boxes = $post_meta_boxes + $taxonomy_meta_boxes;

		foreach ($all_meta_boxes as $meta_box) {
			if (
				isset($meta_box->meta_box['fields'])
				&&
				is_array($meta_box->meta_box['fields'])
			) {
				foreach ($meta_box->meta_box['fields'] as $field) {
					$metabox_fields[] = $field;
				}
			}
		}

		return $this->metabox_fields_to_result($metabox_fields, $args);
	}

	public function get_post_fields($post_id, $post_type, $args = []) {
		if (! function_exists('rwmb_get_object_fields')) {
			return [];
		}

		$metabox_fields = rwmb_get_object_fields($post_id, 'post');

		return $this->metabox_fields_to_result($metabox_fields, $args);
	}

	public function get_post_type_fields($post_type, $args = []) {
		if (! function_exists('rwmb_get_object_fields')) {
			return [];
		}

		$metabox_fields = rwmb_get_object_fields($post_type, 'post');

		return $this->metabox_fields_to_result($metabox_fields, $args);
	}

	public function get_term_fields($term_id, $args = []) {
		if (! function_exists('rwmb_get_object_fields')) {
			return [];
		}

		$metabox_fields = rwmb_get_object_fields($term_id, 'taxonomy');

		return $this->metabox_fields_to_result($metabox_fields, $args);
	}

	public function render($field_id, $args = []) {
		$args = wp_parse_args($args, [
			'context_object' => null,
			'allow_images' => false
		]);

		if (! function_exists('rwmb_get_value')) {
			return null;
		}


		$object_id = null;
		$meta_box_args = [];

		if (is_numeric($args['context_object'])) {
			$object_id = $args['context_object'];
			$meta_box_args['object_type'] = 'post';
		} elseif ($args['context_object'] instanceof \WP_Post) {
			$object_id = $args['context_object']->ID;
			$meta_box_args['object_type'] = 'post';
		} elseif ($args['context_object'] instanceof \WP_Term) {
			$object_id = $args['context_object']->term_id;
			$meta_box_args['object_type'] = 'term';
		}

		$field_descriptor = rwmb_get_field_settings($field_id, $meta_box_args, $object_id);

		if (! $field_descriptor) {
			return null;
		}

		$field_type = CustomField::$TYPE_TEXT;

		return [
			'name' => $field_id,
			'label' => $field_descriptor['name'],
			'type' => $field_type,
			'value' => rwmb_get_value($field_id, $meta_box_args, $object_id)
		];
	}

	// Implementation details.

	private function metabox_fields_to_result($metabox_fields, $args = []) {
		$result = [];

		foreach ($metabox_fields as $field) {
			$field_data = $field;
			$field_id = null;

			if (is_string(array_keys($metabox_fields)[0])) {
				$field_id = array_search($field, $metabox_fields);
				$field_data = $field;
			} else {
				$field_id = $field_data['id'] ?? null;
			}

			if (! $field_id || ! is_array($field_data)) {
				continue;
			}

			$field_type = CustomField::$TYPE_TEXT;

			if (
				empty($field_id)
				||
				empty($field_data['name'])
			) {
				continue;
			}

			$custom_field = new CustomField([
				'id' => $field_id,
				'label' => $field_data['name'] ?? $field_id,
				'type' => $field_type,
				'provider_id' => $this->get_provider_id()
			]);

			$result[] = $custom_field;
		}

		return $result;
	}
}
