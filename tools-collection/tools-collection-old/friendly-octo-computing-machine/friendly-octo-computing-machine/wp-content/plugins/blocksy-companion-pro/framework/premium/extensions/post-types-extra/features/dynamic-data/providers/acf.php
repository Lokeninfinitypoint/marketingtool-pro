<?php

namespace Blocksy\Extensions\PostTypesExtra\DynamicData;

use Blocksy\Extensions\PostTypesExtra\CustomField;

class ACFProvider extends BaseProvider {
	public function get_provider_id() {
		return 'acf';
	}

	public function get_provider_label() {
		return 'ACF';
	}

	public function get_all_fields($args = []) {
		$args = wp_parse_args($args, [
			'allow_images' => false
		]);

		$acf_fields = [];

		$post_type_acf_groups = acf_get_field_groups();

		foreach ($post_type_acf_groups as $acf_group) {
			$fields = acf_get_fields($acf_group['key']);

			foreach ($fields as $field) {
				$acf_fields[] = $field;
			}
		}

		return $this->acf_fields_to_result($acf_fields, $args);
	}

	public function get_post_fields($post_id, $post_type, $args = []) {
		$acf_fields = [];

		$post_type_acf_groups = acf_get_field_groups([
			'post_id' => $post_id
		]);

		foreach ($post_type_acf_groups as $acf_group) {
			$fields = acf_get_fields($acf_group['key']);

			foreach ($fields as $field) {
				$acf_fields[] = $field;
			}
		}


		return $this->acf_fields_to_result($acf_fields, $args);
	}

	public function get_post_type_fields($post_type, $args = []) {
		$acf_fields = [];

		$post_type_acf_groups = acf_get_field_groups([
			'post_type' => $post_type
		]);

		foreach ($post_type_acf_groups as $acf_group) {
			$fields = acf_get_fields($acf_group['key']);

			foreach ($fields as $field) {
				$acf_fields[] = $field;
			}
		}

		foreach (acf_get_raw_field_groups() as $acf_group) {
			if (! isset($acf_group['location'])) {
				continue;
			}

			$has_matching_location = false;

			foreach ($acf_group['location'] as $single_location) {
				foreach ($single_location as $rule) {
					if (
						$rule['param'] === 'post_type'
						&&
						$rule['operator'] === '=='
						&&
						$rule['value'] === $post_type
					) {
						$has_matching_location = true;
						continue;
					}

					if (
						$rule['param'] !== 'post_taxonomy'
						&&
						$rule['param'] !== 'post_category'
					) {
						continue;
					}

					$tax = explode(':', $rule['value'])[0];

					$all_tax = get_object_taxonomies($post_type);

					if (! in_array($tax, $all_tax)) {
						continue;
					}

					$has_matching_location = true;
				}
			}

			if (! $has_matching_location) {
				continue;
			}

			$fields = acf_get_fields($acf_group['key']);

			foreach ($fields as $field) {
				$acf_fields[] = $field;
			}
		}

		return $this->acf_fields_to_result($acf_fields, $args);
	}

	public function get_term_fields($term_id, $args = []) {
		$acf_fields = [];

		$term = get_term($term_id);

		$post_type_acf_groups = acf_get_field_groups([
			'term_id' => $term_id,
			'taxonomy' => $term->taxonomy
		]);

		foreach ($post_type_acf_groups as $acf_group) {
			$fields = acf_get_fields($acf_group['key']);

			foreach ($fields as $field) {
				$acf_fields[] = $field;
			}
		}

		return $this->acf_fields_to_result($acf_fields, $args);
	}

	public function render($field_id, $args = []) {
		$args = wp_parse_args($args, [
			'context_object' => null,

			'allow_images' => false
		]);

		if (! function_exists('get_field_object')) {
			return null;
		}

		$field_descriptor = acf_get_field($field_id);

		// Check if it's a sub-field in a group.
		if (! $field_descriptor) {
			if (strpos($field_id, '_') === false) {
				return null;
			}

			$parts = explode('_', $field_id);

			$group_descriptor = null;
			$sub_field_id = null;

			$group_id = null;
			$sub_field_id = null;

			// This code takes a compound string like "field_test1_test3_test4_test5"
			// and progressively checks shorter prefixes (excluding the full string)
			// with acf_get_field(). The first prefix that exists becomes $group_id,
			// and the remainder of the string becomes $sub_field_id. This allows you
			// to dynamically find the "parent field" and its corresponding "sub-field"
			// based on available ACF fields.
			for ($i = 1; $i <= count($parts); $i++) {
				$prefix = implode('_', array_slice($parts, 0, $i));

				if ($i === count($parts)) {
					continue;
				}

				$maybe_group_descriptor = acf_get_field($prefix);

				if ($maybe_group_descriptor) {
					$group_descriptor = $maybe_group_descriptor;
					$sub_field_id = implode('_', array_slice($parts, $i));
					break;
				}
			}

			if (
				$group_descriptor
				&&
				isset($group_descriptor['sub_fields'])
				&&
				is_array($group_descriptor['sub_fields'])
			) {
				foreach ($group_descriptor['sub_fields'] as $sub_field) {
					if ($sub_field['name'] === $sub_field_id) {
						$field_descriptor = $sub_field;
						break;
					}
				}
			}
		}

		if (! $field_descriptor) {
			return null;
		}

		$field_type = CustomField::$TYPE_TEXT;

		if ($field_descriptor['type'] === 'image') {
			$field_type = CustomField::$TYPE_IMAGE;
		}

		$field = [
			'name' => $field_id,
			'label' => $field_descriptor['label'],
			'type' => $field_type
		];

		$field_descriptor['value'] = get_field(
			$field['name'],
			$args['context_object']
		);

		$field_value = $field_descriptor['value'];

		if (! is_array($field_value)) {
			$field_value = [$field_value];
		}

		if ($field_descriptor['type'] === 'image') {
			if (! $args['allow_images']) {
				$field_value = '';

				if (
					is_string($field_descriptor['value'])
					||
					(
						is_array($field_descriptor['value'])
						&&
						isset($field_descriptor['value']['url'])
						&&
						! empty($field_descriptor['value']['url'])
					)
				) {
					$field_value = $field_descriptor['value'];
				}
			} else {
				$field_value = [];

				if (isset($field_descriptor['value'])) {
					$field_value = $field_descriptor['value'];
				}
			}
		} else {
			$mapped_value = [];

			foreach ($field_value as $single_field) {
				if (is_object($single_field) && get_class($single_field) === 'WP_Term') {
					$mapped_value[] = blocksy_html_tag(
						'a',
						[
							'href' => get_term_link($single_field, $single_field->taxonomy)
						],
						$single_field->name
					);
				} else {
					$mapped_value[] = $single_field;
				}
			}

			$field_value = $mapped_value;

			if (
				$field_descriptor
				&&
				isset($field_descriptor['choices'])
				&&
				! empty($field_descriptor['choices'])
			) {
				$mapped_value = [];

				foreach (array_values($field_value) as $single_field) {
					if (
						isset($field_descriptor['choices'][$single_field])
					) {
						$mapped_value[] = $field_descriptor[
							'choices'
						][$single_field];
					} else {
						$mapped_value[] = $single_field;
					}
				}

				$field_value = $mapped_value;
			}

			$field_value_result = [];

			foreach ($field_value as $index => $single_field_value) {
				if (
					is_string($single_field_value)
					&&
					! empty($single_field_value)
				) {
					$field_value_result[] = $single_field_value;
				}
			}

			$field_value = implode(', ', array_values($field_value_result));
		}

		$field['value'] = $field_value;

		return $field;
	}

	// Implementation details.

	private function acf_fields_to_result($acf_fields, $args = []) {
		$result = [];

		foreach ($acf_fields as $field) {
			if ($field['type'] === 'repeater') {
				continue;
			}

			if (! $args['allow_images'] && $field['type'] === 'image') {
				continue;
			}

			if (
				$field['type'] === 'group'
				&&
				! empty($field['sub_fields'])
			) {
				foreach ($field['sub_fields'] as $sub_field) {
					$field_type = CustomField::$TYPE_TEXT;

					if ($sub_field['type'] === 'image') {
						$field_type = CustomField::$TYPE_IMAGE;
					}

					$custom_field = new CustomField([
						'id' => $field['name'] . '_' . $sub_field['name'],
						'label' => $field['label'] . ' - ' . $sub_field['label'],
						'type' => $field_type,
						'provider_id' => $this->get_provider_id()
					]);

					$result[] = $custom_field;
				}

				continue;
			}

			$field_type = CustomField::$TYPE_TEXT;

			if ($field['type'] === 'image') {
				$field_type = CustomField::$TYPE_IMAGE;
			}

			if (
				empty($field['name'])
				||
				empty($field['label'])
			) {
				continue;
			}

			$custom_field = new CustomField([
				'id' => $field['name'],
				'label' => $field['label'],
				'type' => $field_type,
				'provider_id' => $this->get_provider_id()
			]);

			$result[] = $custom_field;
		}

		return $result;
	}
}

