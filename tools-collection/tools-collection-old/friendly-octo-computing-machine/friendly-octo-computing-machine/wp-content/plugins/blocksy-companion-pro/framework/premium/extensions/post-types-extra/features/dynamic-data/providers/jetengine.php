<?php

namespace Blocksy\Extensions\PostTypesExtra\DynamicData;

use Blocksy\Extensions\PostTypesExtra\CustomField;

class JetEngineProvider extends BaseProvider {
	public function get_provider_id() {
		return 'jetengine';
	}

	public function get_provider_label() {
		return 'JetEngine';
	}

	public function get_all_fields($args = []) {
		if (! function_exists('jet_engine')) {
			return [];
		}

		$jet_fields = [];

		$core_meta = jet_engine()->meta_boxes->meta_fields;

		foreach ($core_meta as $post_type => $fields) {
			foreach ($core_meta[$post_type] as $field) {
				$jet_fields[] = $field;
			}
		}

		if (
			isset($core_meta[$post_type])
			&&
			is_array($core_meta[$post_type])
		) {
			foreach ($core_meta[$post_type] as $field) {
				$jet_fields[] = $field;
			}
		}

		$meta_boxes = jet_engine()->meta_boxes->data->get_items();

		foreach ($meta_boxes as $meta_box) {
			$meta_box_args = $meta_box['args'];

			if (in_array($meta_box_args['object_type'], ['post', 'taxonomy'])) {
				foreach ($meta_box['meta_fields'] as $field) {
					if ($field['object_type'] === 'field') {
						$jet_fields[] = $field;
					}
				}
			}
		}

		return $this->jet_fields_to_result($jet_fields, $args);
	}

	public function get_post_fields($post_id, $post_type, $args = []) {
		if (! function_exists('jet_engine')) {
			return [];
		}

		$jet_fields = [];

		$core_meta = jet_engine()->meta_boxes->meta_fields;

		if (
			isset($core_meta[$post_type])
			&&
			is_array($core_meta[$post_type])
		) {
			foreach ($core_meta[$post_type] as $field) {
				$jet_fields[] = $field;
			}
		}

		$meta_boxes = jet_engine()->meta_boxes->data->get_items();

		foreach ($meta_boxes as $meta_box) {
			$meta_box_args = $meta_box['args'];

			if ($meta_box_args['object_type'] !== 'post') {
				continue;
			}

			$allowed_post_types = [];

			if (isset($meta_box_args['allowed_post_type'])) {
				$allowed_post_types = $meta_box_args['allowed_post_type'];
			}

			if (! in_array($post_type, $allowed_post_types)) {
				continue;
			}

			if (! $this->check_post_conditions($meta_box_args, $post_id)) {
				continue;
			}

			foreach ($meta_box['meta_fields'] as $field) {
				if ($field['object_type'] !== 'field') {
					continue;
				}

				$jet_fields[] = $field;
			}
		}

		return $this->jet_fields_to_result($jet_fields, $args);
	}

	public function get_post_type_fields($post_type, $args = []) {
		if (! function_exists('jet_engine')) {
			return [];
		}

		$jet_fields = [];

		$core_meta = jet_engine()->meta_boxes->meta_fields;

		if (
			isset($core_meta[$post_type])
			&&
			is_array($core_meta[$post_type])
		) {
			foreach ($core_meta[$post_type] as $field) {
				$jet_fields[] = $field;
			}
		}

		$meta_boxes = jet_engine()->meta_boxes->data->get_items();

		foreach ($meta_boxes as $meta_box) {
			$meta_box_args = $meta_box['args'];

			if ($meta_box_args['object_type'] !== 'post') {
				continue;
			}

			$allowed_post_types = [];

			if (isset($meta_box_args['allowed_post_type'])) {
				$allowed_post_types = $meta_box_args['allowed_post_type'];
			}

			if (! in_array($post_type, $allowed_post_types)) {
				continue;
			}

			foreach ($meta_box['meta_fields'] as $field) {
				if ($field['object_type'] !== 'field') {
					continue;
				}

				$jet_fields[] = $field;
			}
		}

		return $this->jet_fields_to_result($jet_fields, $args);
	}

	public function get_term_fields($term_id, $args = []) {
		if (! function_exists('jet_engine')) {
			return [];
		}

		$term = get_term($term_id);

		$jet_fields = [];

		$core_meta = jet_engine()->meta_boxes->meta_fields;

		if (
			isset($core_meta[$term->taxonomy])
			&&
			is_array($core_meta[$term->taxonomy])
		) {
			foreach ($core_meta[$term->taxonomy] as $field) {
				$jet_fields[] = $field;
			}
		}

		$meta_boxes = jet_engine()->meta_boxes->data->get_items();

		foreach ($meta_boxes as $meta_box) {
			$meta_box_args = $meta_box['args'];

			if ($meta_box_args['object_type'] !== 'taxonomy') {
				continue;
			}

			$allowed_taxonomies = [];

			if (isset($meta_box_args['allowed_tax'])) {
				$allowed_taxonomies = $meta_box_args['allowed_tax'];
			}

			if (! in_array($term->taxonomy, $allowed_taxonomies)) {
				continue;
			}

			foreach ($meta_box['meta_fields'] as $field) {
				if ($field['object_type'] !== 'field') {
					continue;
				}

				$jet_fields[] = $field;
			}
		}

		return $this->jet_fields_to_result($jet_fields, $args);
	}

	public function render($field_id, $args = []) {
		$args = wp_parse_args($args, [
			'context_object' => null,

			'allow_images' => false
		]);

		if (! function_exists('jet_engine')) {
			return '';
		}

		$all_fields = $this->get_all_fields(['allow_images' => true]);

		$matching_field = null;

		foreach ($all_fields as $custom_field) {
			if ($custom_field->get_id() === $field_id) {
				$matching_field = $custom_field;
				break;
			}
		}

		if (! $matching_field) {
			return null;
		}

		$value = '';

		if (is_a($args['context_object'], 'WP_Post')) {
			$object_id = $args['context_object']->ID;
			$value = get_post_meta($object_id, $field_id, true);
		} elseif (is_a($args['context_object'], 'WP_Term')) {
			$object_id = $args['context_object']->term_id;
			$value = get_term_meta($object_id, $field_id, true);
		}

		return [
			'name' => $field_id,
			'label' => $matching_field->get_label(),
			'type' => CustomField::$TYPE_TEXT,
			'value' => $value
		];
	}

	private function check_post_conditions($args, $post_id) {
		$active_conditions = [];

		if (isset($args['active_conditions'])) {
			$active_conditions = $args['active_conditions'];
		}

		if (empty($active_conditions)) {
			return true;
		}

		foreach ($active_conditions as $condition) {
			switch ($condition) {
			case 'allowed_posts':
				$allowed_posts = isset($args['allowed_posts']) ? $args['allowed_posts'] : [];
				if (!empty($allowed_posts) && !in_array(strval($post_id), $allowed_posts)) {
					return false;
				}
				break;

			case 'excluded_posts':
				$excluded_posts = isset($args['excluded_posts']) ? $args['excluded_posts'] : [];
				if (!empty($excluded_posts) && in_array(strval($post_id), $excluded_posts)) {
					return false;
				}
				break;

			default:
				break;
			}
		}

		return true;
	}

	private function jet_fields_to_result($jet_fields, $args = []) {
		$result = [];

		foreach ($jet_fields as $field) {
			if (
				empty($field['name'])
				||
				empty($field['title'])
			) {
				continue;
			}

			$result[] = new CustomField([
				'id' => $field['name'],
				'label' => $field['title'],

				'type' => CustomField::$TYPE_TEXT,

				'provider_id' => $this->get_provider_id(),

				'extra_data' => $field
			]);
		}

		return $result;
	}
}
