<?php

namespace Blocksy\Extensions\PostTypesExtra;

class CustomField {
	static public $TYPE_TEXT = 'text';
	static public $TYPE_IMAGE = 'image';

	private $id;
	private $label;

	private $provider_id;

	private $type;

	private $extra_data;

	public function __construct($args = []) {
		$args = wp_parse_args($args, [
			'id' => '__DEFAULT__',
			'label' => '__DEFAULT_',
			'provider_id' => null,

			'type' => self::$TYPE_TEXT,
			'extra_data' => [],
		]);

		if ($args['id'] === '__DEFAULT__') {
			throw new \Exception('CustomField: id is required');
		}

		if ($args['label'] === '__DEFAULT__') {
			throw new \Exception('CustomField: label is required');
		}

		if (! $args['provider_id']) {
			throw new \Exception('CustomField: provider_id is required');
		}

		$this->id = $args['id'];
		$this->label = $args['label'];
		$this->provider_id = $args['provider_id'];
		$this->type = $args['type'];
		$this->extra_data = $args['extra_data'];
	}

	public function get_type() {
		return $this->type;
	}

	public function get_id() {
		return $this->id;
	}

	public function get_label() {
		return $this->label;
	}

	public function get_provider_id() {
		return $this->provider_id;
	}

	public function get_extra_data() {
		return $this->extra_data;
	}
}
