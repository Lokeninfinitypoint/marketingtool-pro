<?php

namespace Blocksy\Extensions\PostTypesExtra\DynamicData;

use Blocksy\Extensions\PostTypesExtra\CustomField;

class PodsProvider extends BaseProvider {
	public function get_provider_id() {
		return 'pods';
	}

	public function get_provider_label() {
		return 'Pods';
	}

	public function get_all_fields($args = []) {
		if (! function_exists('pods_api')) {
			return [];
		}

		$pods_fields = [];

		$api = pods_api();
		$all_pods = $api->load_pods();

		foreach ($all_pods as $pod) {
			if (! isset($pod['fields']) || empty($pod['fields'])) {
				continue;
			}

			foreach ($pod['fields'] as $field_name => $field_data) {
				$pods_fields[] = $field_data;
			}
		}

		return $this->pods_fields_to_result($pods_fields, $args);
	}

	public function get_post_fields($post_id, $post_type, $args = []) {
		// For Pods, post fields are the same as post type fields.
		return $this->get_post_type_fields($post_type, $args);
	}

	public function get_post_type_fields($post_type, $args = []) {
		if (! function_exists('pods_api')) {
			return [];
		}

		$pods_fields = [];

		$pod_data = pods_api()->load_pod(['name' => $post_type]);

		if (
			$pod_data
			&&
			isset($pod_data['fields'])
		) {
			$pods_fields = array_values($pod_data['fields']);
		}

		return $this->pods_fields_to_result($pods_fields, $args);
	}

	public function get_term_fields($term_id, $args = []) {
		if (! function_exists('pods_api')) {
			return [];
		}

		$term = get_term($term_id);

		if (
			! $term
			||
			is_wp_error($term)
		) {
			return [];
		}

		$pod_data = pods_api()->load_pod(['name' => $term->taxonomy]);

		if (
			$pod_data
			&&
			isset($pod_data['fields'])
		) {
			$pod_fields = array_values($pod_data['fields']);
		}

		return $this->pods_fields_to_result($pods_fields, $args);
	}

	public function render($field_id, $args = []) {
		$args = wp_parse_args($args, [
			'context_object' => null,
			'allow_images' => false
		]);

		if (! function_exists('pods_api')) {
			return null;
		}

		$pod_name = null;
		$item_id = null;

		if ($args['context_object']) {
			if (is_a($args['context_object'], 'WP_Post')) {
				$pod_name = $args['context_object']->post_type;
				$item_id = $args['context_object']->ID;
			} elseif (is_a($args['context_object'], 'WP_Term')) {
				$pod_name = $args['context_object']->taxonomy;
				$item_id = $args['context_object']->term_id;
			} elseif (is_numeric($args['context_object'])) {
				$post = get_post($args['context_object']);

				if ($post) {
					$pod_name = $post->post_type;
					$item_id = $args['context_object'];
				}
			}
		}

		if (
			! $pod_name
			||
			! $item_id
		) {
			return null;
		}

		$pod = pods($pod_name, $item_id);

		if (
			! $pod
			||
			! $pod->is_valid()
		) {
			return null;
		}

		$pod_data = pods_api()->load_pod(['name' => $pod_name]);

		if (
			! $pod_data
			||
			! isset($pod_data['fields'][$field_id])
		) {
			return null;
		}

		$field_data = $pod_data['fields'][$field_id];

		$field_type = CustomField::$TYPE_TEXT;

		$field = [
			'name' => $field_id,
			'label' => $field_data['label'],
			'type' => $field_type,
			'value' => $pod->display($field_id)
		];

		return $field;
	}

	// Implementation details.

	private function pods_fields_to_result($pods_fields, $args = []) {
		$result = [];

		foreach ($pods_fields as $field) {
			if ($field['type'] === 'repeater') {
				continue;
			}

			$field_type = CustomField::$TYPE_TEXT;

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
