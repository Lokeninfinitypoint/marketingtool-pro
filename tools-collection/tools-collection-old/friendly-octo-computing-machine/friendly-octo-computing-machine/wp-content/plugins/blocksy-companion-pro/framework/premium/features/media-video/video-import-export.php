<?php

namespace Blocksy;

class VideoImportExport {
	private $blocksy_column_id = 'blocksy_images_metadata';

	public function __construct() {
		add_filter(
			"woocommerce_product_export_product_default_columns",
			[$this, 'export_column_name']
		);

		add_filter(
			'woocommerce_product_export_column_names',
			[$this, 'export_column_name']
		);

		add_filter(
			'woocommerce_csv_product_import_mapping_options',
			[$this, 'export_column_name']
		);

		add_filter(
			'woocommerce_csv_product_import_mapping_default_columns',
			[$this, 'default_import_column_name']
		);

		add_filter(
			"woocommerce_product_export_product_column_{$this->blocksy_column_id}",
			[$this, 'export_custom_data'],
			10, 2
		);

		add_filter(
			'woocommerce_product_import_inserted_product_object',
			[$this, 'attach_images_metadata'],
			10, 2
		);
	}

	public function attach_images_metadata($product, $data) {
		if (empty($data[$this->blocksy_column_id])) {
			return $product;
		}

		$images = \Blocksy\WooImportExport::parse_data($data[$this->blocksy_column_id]);

		if (empty($images)) {
			return $product;
		}

		$images = $images[0];

		$featured_image_metadata = $images[0]['meta'];
		$featured_image_id = $product->get_image_id();

		if (
			$featured_image_metadata['media_video_source'] === 'upload'
			&&
			! empty($featured_image_metadata['media_video_upload'])
		) {
			$video_upload = \Blocksy\WooImportExport::upload_video_from_url($featured_image_metadata['media_video_upload']);

			if (! is_wp_error($video_upload)) {
				$featured_image_metadata['media_video_upload'] = wp_get_attachment_url($video_upload);
			}
		}

		update_post_meta($featured_image_id, 'blocksy_post_meta_options', $featured_image_metadata);

		$gallery_images = array_slice($images, 1);

		if (! empty($gallery_images)) {
			$gallery_images_ids = $product->get_gallery_image_ids();

			foreach ($gallery_images as $gallery_image) {
				$gallery_image_id = $gallery_image['attachment_id'];
				$gallery_image_metadata = $gallery_image['meta'];

				if (! in_array($gallery_image_id, $gallery_images_ids)) {
					$gallery_images_ids[] = $gallery_image_id;
				}

				if (
					$gallery_image_metadata['media_video_source'] === 'upload'
					&&
					! empty($gallery_image_metadata['media_video_upload'])
				) {
					$video_upload = \Blocksy\WooImportExport::upload_video_from_url($gallery_image_metadata['media_video_upload']);

					if (! is_wp_error($video_upload)) {
						$gallery_image_metadata['media_video_upload'] = wp_get_attachment_url($video_upload);
					}
				}

				update_post_meta($gallery_image_id, 'blocksy_post_meta_options', $gallery_image_metadata);
			}

			$product->set_gallery_image_ids($gallery_images_ids);
		}

		return $product;
	}

	public function default_import_column_name($columns) {
		$columns[__('Blocksy Images Metadata', 'blocksy-companion')] = $this->blocksy_column_id;

		return $columns;
	}

	public function export_column_name($columns) {
		$columns[$this->blocksy_column_id] = __('Blocksy Images Metadata', 'blocksy-companion');

		return $columns;
	}

	public function export_custom_data($value, $product) {
		$images = $product->get_gallery_image_ids();
		$featured_image = $product->get_image_id();

		$blocksy_meta = get_post_meta($product->get_id(), 'blocksy_post_meta_options', true);

		$images_ids = [];

		if ($featured_image) {
			$images_ids[] = $featured_image;
		}

		if (! empty($images)) {
			$images_ids = array_merge($images_ids, $images);
		}

		if (
			isset($blocksy_meta['gallery_source'])
			&&
			$blocksy_meta['gallery_source'] !== 'default'
		) {
			$viariation_images = $blocksy_meta['images'];

			foreach ($viariation_images as $variation_image) {
				$images_ids[] = $variation_image['attachment_id'];
			}
		}

		$images_ids = array_unique($images_ids);
		$data = [];

		foreach ($images_ids as $image_id) {
			$image = wp_get_attachment_image_src($image_id, 'full');
			$image_meta = get_post_meta($image_id, 'blocksy_post_meta_options', true);

			if (
				! $image
				||
				empty($image_meta)
			) {
				continue;
			}

			$data[] = [
				'url' => $image[0],
				'attachment_id' => $image_id,
				'meta' => $image_meta,
			];
		}

		if (empty($data)) {
			return '';
		}

		$data = \Blocksy\WooImportExport::implode_values([json_encode($data)]);

		return $data;
	}
}
