<?php

namespace Blocksy\Extensions\PostTypesExtra;

class CustomFieldsManager {
	private $providers = [];

	public function __construct() {
		if (function_exists('acf_get_field_groups')) {
			$this->register_provider(new DynamicData\ACFProvider());
		}

		// Custom provider uses only Core WP fields so it's ok to register it always.
		$this->register_provider(new DynamicData\CustomWPFieldProvider());

		if (function_exists('get_acpt_field')) {
			$this->register_provider(new DynamicData\ACPTProvider());
		}

		if (class_exists('Jet_Engine')) {
			$this->register_provider(new DynamicData\JetEngineProvider());
		}

		if (defined('PODS_VERSION')) {
			$this->register_provider(new DynamicData\PodsProvider());
		}

		if (
			// Various variations of Pro version
			defined('META_BOX_AIO_DIR')
			||
			// Version from WP repo
			defined('RWMB_INC_DIR')
			||
			// Lite version from site
			defined('META_BOX_LITE_DIR')
		) {
			$this->register_provider(new DynamicData\MetaBoxProvider());
		}

		if (defined('TYPES_VERSION')) {
			$this->register_provider(new DynamicData\ToolsetProvider());
		}
	}

	public function get_providers() {
		return $this->providers;
	}

	public function get_fields($context, $args = []) {
		$args = wp_parse_args($args, [
			'provider' => 'all',
			'allow_images' => false,
		]);

		if ($args['provider'] === 'all') {
			$fields = [];

			foreach ($this->providers as $provider) {
				$provider_fields = $provider->get_fields($context, $args);

				if (empty($provider_fields)) {
					continue;
				}

				$fields[] = [
					'provider' => $provider->get_provider_id(),
					'label' => $provider->get_provider_label(),
					'fields' => $provider_fields
				];
			}

			return $fields;
		}

		if (! isset($this->providers[$args['provider']])) {
			return null;
		}

		$provider = $this->providers[$args['provider']];

		$fields = $provider->get_fields($context, $args);

		if (empty($fields)) {
			return null;
		}

		return [
			'provider' => $provider->get_provider_id(),
			'label' => $provider->get_provider_label(),
			'fields' => $fields
		];
	}

	public function render_field($field_id, $args = []) {
		$args = wp_parse_args($args, [
			'provider' => null,

			'allow_images' => false,

			// if int - post ID
			// if WP_Term - term object
			// if WP_Post - post object
			// if null - will get current post ID
			//
			// TODO: support WP_User?
			'context_object' => '__DEFAULT__',
		]);

		if (
			! $args['provider']
			||
			! isset($this->providers[$args['provider']])
		) {
			return null;
		}

		$provider = $this->providers[$args['provider']];

		unset($args['provider']);

		if ($args['context_object'] === '__DEFAULT__') {
			$args['context_object'] = $this->discover_context_object();
		}

		$result = $provider->render($field_id, $args);

		if (! $result) {
			return null;
		}

		$filter = [
			'blocksy',
			'pro',
			'post-types-extra',
			$provider->get_provider_id(),
			'field-value-render'
		];

		$result['value'] = apply_filters(
			implode(':', $filter),
			$result['value'],
			$result['name'],
			$result
		);

		return $result;
	}

	private function register_provider($provider) {
		if (! $provider instanceof DynamicData\BaseProvider) {
			return;
		}

		$this->providers[$provider->get_provider_id()] = $provider;
	}

	private function discover_context_object() {
		$global_object = get_queried_object();

		global $blocksy_term_obj;

		// We are for sure in the tax-query loop.
		if ($blocksy_term_obj) {
			return $blocksy_term_obj;
		}

		$current_post = get_post(get_the_ID());

		// Return current post if:
		//
		// We have a current post AND
		if ($current_post) {
			if (
				// 1. No global object, highly unlikely
				! $global_object
				||
				// 2. Global object is post and is a different post than current, means we're in a loop.
				(
					isset($global_object->post_type)
					&&
					$global_object->ID !== $current_post->ID
				)
			) {
				return $current_post;
			}

			// 3. Global object is not a post. This means we are in a query that lists posts.
			// We should return the current post only if we are in the loop right now.
			// Sometimes, global object is assigned to first post in the loop even if loop didn't start yet.
			// We still want to refer to the actual global object in that case.
			if (
				! isset($global_object->post_type)
				&&
				// It will detect advanced posts properly, because we replace the global $wp_query.
				// In some specific cases, native WP query loop will not be detected properly.
				in_the_loop()
			) {
				return $current_post;
			}
		}

		return $global_object;
	}
}

