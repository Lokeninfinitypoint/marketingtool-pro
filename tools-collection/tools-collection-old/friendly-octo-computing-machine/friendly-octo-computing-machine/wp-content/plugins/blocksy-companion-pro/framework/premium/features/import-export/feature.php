<?php

namespace Blocksy;

class ImportExport {
	public function __construct() {
		add_action(
			'wp_import_insert_post',
			[$this, 'importer_insert_post'],
			10,
			4
		);
	}

	public function importer_insert_post($post_id, $original_post_id, $postdata, $post) {
		if ($post_id !== $original_post_id) {
			update_post_meta(
				$post_id,
				'blocksy_original_post_id',
				$original_post_id
			);
		}
	}
}

