<?php

namespace Blocksy\Extensions\PostTypesExtra\DynamicData;

use Blocksy\Extensions\PostTypesExtra\CustomField;

class ACPTProvider extends BaseProvider {
	public function get_provider_id() {
		return 'acpt';
	}

	public function get_provider_label() {
		return 'ACPT';
	}

	public function get_all_fields($args = []) {
		$meta_repository_class = $this->get_acpt_class('Core\Repository\MetaRepository');

		if (! $meta_repository_class) {
			return [];
		}

		$acpt_fields = [];

		$all_groups = $meta_repository_class::get(['lazy' => false]);

		foreach ($all_groups as $group) {
			foreach ($group->getBoxes() as $box) {
				foreach ($box->getFields() as $field) {
					$acpt_fields[] = [
						'group' => $group->getName(),
						'box' => $box->getName(),
						'field' => $field->getName(),
						'label' => $field->getLabel(),
						'type' => $field->getType()
					];
				}
			}
		}

		return $this->acpt_fields_to_result($acpt_fields, $args);
	}

	public function get_post_fields($post_id, $post_type, $args = []) {
		$meta_repository_class = $this->get_acpt_class('Core\Repository\MetaRepository');

		if (! $meta_repository_class) {
			return [];
		}

		$constants_class = $this->get_acpt_class('Constants\MetaTypes');
		$generator_class = $this->get_acpt_class('Core\Generators\CustomPostType\CustomPostTypeMetaBoxFieldGenerator');

		$acpt_fields = [];

		$groups = $meta_repository_class::get([
			'belongsTo' => $constants_class::CUSTOM_POST_TYPE,
			'find' => $post_type,
			'lazy' => false,
		]);

		foreach ($groups as $group) {
			foreach ($group->getBoxes() as $box) {
				foreach ($box->getFields() as $field) {
					$fieldGenerator = $generator_class::generate($field, $post_id);

					if (
						$fieldGenerator
						&&
						$fieldGenerator->isVisible()
					) {
						$acpt_fields[] = [
							'group' => $group->getName(),
							'box' => $box->getName(),
							'field' => $field->getName(),
							'label' => $field->getLabel(),
							'type' => $field->getType()
						];
					}
				}
			}
		}

		return $this->acpt_fields_to_result($acpt_fields, $args);
	}

	public function get_post_type_fields($post_type, $args = []) {
		$meta_repository_class = $this->get_acpt_class('Core\Repository\MetaRepository');

		if (! $meta_repository_class) {
			return [];
		}

		$constants_class = $this->get_acpt_class('Constants\MetaTypes');

		$acpt_fields = [];

		$groups = $meta_repository_class::get([
			'belongsTo' => $constants_class::CUSTOM_POST_TYPE,
			'find' => $post_type,
			'lazy' => false,
		]);

		foreach ($groups as $group) {
			foreach ($group->getBoxes() as $box) {
				foreach ($box->getFields() as $field) {
					$acpt_fields[] = [
						'group' => $group->getName(),
						'box' => $box->getName(),
						'field' => $field->getName(),
						'label' => $field->getLabel(),
						'type' => $field->getType()
					];
				}
			}
		}

		return $this->acpt_fields_to_result($acpt_fields, $args);
	}

	public function get_term_fields($term_id, $args = []) {
		$meta_repository_class = $this->get_acpt_class('Core\Repository\MetaRepository');

		if (! $meta_repository_class) {
			return [];
		}

		$constants_class = $this->get_acpt_class('Constants\MetaTypes');
		$taxonomy_generator_class = $this->get_acpt_class('Core\Generators\Taxonomy\TaxonomyMetaBoxFieldGenerator');

		$acpt_fields = [];
		$term = get_term($term_id);

		if (
			! $term
			||
			is_wp_error($term)
		) {
			return [];
		}

		// Get all meta groups for the taxonomy (fully hydrated)
		$groups = $meta_repository_class::get([
			'belongsTo' => $constants_class::TAXONOMY,
			'find' => $term->taxonomy,
			'lazy' => false,
		]);

		foreach ($groups as $group) {
			foreach ($group->getBoxes() as $box) {
				foreach ($box->getFields() as $field) {
					$fieldGenerator = $taxonomy_generator_class::generate($field, $term_id);

					if (
						$fieldGenerator
						&&
						$fieldGenerator->isVisible()
					) {
						$acpt_fields[] = [
							'group' => $group->getName(),
							'box' => $box->getName(),
							'field' => $field->getName(),
							'label' => $field->getLabel(),
							'type' => $field->getType()
						];
					}
				}
			}
		}

		return $this->acpt_fields_to_result($acpt_fields, $args);
	}

	public function render($field_id, $args = []) {
		$args = wp_parse_args($args, [
			'context_object' => null,
			'allow_images' => false
		]);

		$meta_repository_class = $this->get_acpt_class('Core\Repository\MetaRepository');

		if (! $meta_repository_class) {
			return null;
		}

		$all_fields = $this->get_all_fields(['allow_images' => true]);

		$matching_field = null;
		$box_name = null;
		$field_name = $field_id;

		foreach ($all_fields as $custom_field) {
			if ($custom_field->get_id() === $field_id) {
				$matching_field = $custom_field;
				$extra_data = $custom_field->get_extra_data();
				$box_name = $extra_data['box_name'] ?? null;
				break;
			}
		}

		if (
			! $matching_field
			||
			! $box_name
		) {
			return null;
		}

		$field_type = $matching_field->get_type();
		$field_label = $matching_field->get_label();
		$field_descriptor = null;

		try {
			$field_descriptor = $meta_repository_class::getMetaFieldByName([
				'boxName' => $box_name,
				'fieldName' => $field_name
			]);
		} catch (\Exception $e) {
		}

		$field_value = null;

		$get_acpt_field_args = [
			'box_name' => $box_name,
			'field_name' => $field_name
		];

		$context = $args['context_object'];

		if (is_numeric($context)) {
			$get_acpt_field_args['post_id'] = $context;
		} elseif ($context instanceof \WP_Post) {
			$get_acpt_field_args['post_id'] = $context->ID;
		} elseif ($context instanceof \WP_Term) {
			$get_acpt_field_args['term_id'] = $context->term_id;
		} elseif (is_object($context)) {
			if (isset($context->ID)) {
				$get_acpt_field_args['post_id'] = $context->ID;
			} elseif (isset($context->term_id)) {
				$get_acpt_field_args['term_id'] = $context->term_id;
			} elseif (isset($context->user_id)) {
				$get_acpt_field_args['user_id'] = $context->user_id;
			}
		}

		if (function_exists('get_acpt_field')) {
			$field_value = get_acpt_field($get_acpt_field_args);
		}

		if (
			$field_descriptor
			&&
			$field_value !== null
		) {
			$field_type_name = $field_descriptor->getType();

			$wp_attachment_class = $this->get_acpt_class('Utils\Wordpress\WPAttachment');

			if (
				$wp_attachment_class
				&&
				$field_value instanceof $wp_attachment_class
				&&
				$field_type_name === 'Image'
				&&
				$field_value->getId()
			) {
				if ($args['allow_images']) {
					$image_data = wp_get_attachment_metadata($field_value->getId());

					$field_value = array_merge($image_data, [
						'id' => $field_value->getId(),
						'ID' => $field_value->getId(),
						'url' => $field_value->getSrc()
					]);
				} else {
					$field_value = $field_value->getSrc();
				}
			}

			if (
				$field_type_name === 'Country'
				&&
				isset($field_value['value'])
			) {
				$field_value = $field_value['value'];
			}

			if (
				$field_type_name === 'Url'
				&&
				isset($field_value['url'])
				&&
				isset($field_value['label'])
			) {
				$field_value = sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url($field_value['url']),
					esc_html($field_value['label'])
				);
			}

			if (
				$field_type_name === 'Address'
				&&
				isset($field_value['address'])
			) {
				$field_value = $field_value['address'];
			}

			if (
				$field_type_name === 'Currency'
				&&
				isset($field_value['unit'])
				&&
				isset($field_value['amount'])
			) {
				$field_value = sprintf(
					'%s %s',
					esc_html($field_value['amount']),
					esc_html($field_value['unit'])
				);
			}

			if (
				$field_type_name === 'Weight'
				&&
				isset($field_value['unit'])
				&&
				isset($field_value['weight'])
			) {
				$field_value = sprintf(
					'%s %s',
					esc_html($field_value['weight']),
					esc_html($field_value['unit'])
				);
			}

			if (
				$field_type_name === 'Length'
				&&
				isset($field_value['unit'])
				&&
				isset($field_value['length'])
			) {
				$field_value = sprintf(
					'%s %s',
					esc_html($field_value['length']),
					esc_html($field_value['unit'])
				);
			}

			if ($field_type_name === 'List' && is_array($field_value)) {
				$field_value = implode(
					', ',
					array_map('esc_html', $field_value)
				);
			}
		}

		$field = [
			'name' => $field_id,
			'label' => $field_label,
			'type' => $field_type,
			'value' => $field_value
		];

		return $field;
	}

	// Implementation details.

	private function get_acpt_class($class_suffix) {
		$full_acpt_class = '\ACPT\\' . $class_suffix;

		if (class_exists($full_acpt_class)) {
			return $full_acpt_class;
		}

		$lite_acpt_class = '\ACPT_Lite\\' . $class_suffix;

		if (class_exists($lite_acpt_class)) {
			return $lite_acpt_class;
		}

		return null;
	}

	private function acpt_fields_to_result($acpt_fields, $args = []) {
		$result = [];

		foreach ($acpt_fields as $field) {
			// Skip unsupported field types
			if (in_array($field['type'], [
				'Video',
				'Gallery',
				'Repeater',
				'Flexible',
				'PostObject',
				'PostObjectMulti',
				'TermObject',
				'TermObjectMulti',
				'User',
				'UserMulti'
			])) {
				continue;
			}

			$field_type = CustomField::$TYPE_TEXT;

			if ($field['type'] === 'Image') {
				if (! $args['allow_images']) {
					continue;
				}

				$field_type = CustomField::$TYPE_IMAGE;
			}

			if (
				empty($field['field'])
				||
				empty($field['label'])
			) {
				continue;
			}

			$custom_field = new CustomField([
				'id' => $field['field'],
				'label' => $field['label'],
				'type' => $field_type,
				'provider_id' => $this->get_provider_id(),
				'extra_data' => [
					'box_name' => $field['box']
				]
			]);

			$result[] = $custom_field;
		}

		return $result;
	}
}
