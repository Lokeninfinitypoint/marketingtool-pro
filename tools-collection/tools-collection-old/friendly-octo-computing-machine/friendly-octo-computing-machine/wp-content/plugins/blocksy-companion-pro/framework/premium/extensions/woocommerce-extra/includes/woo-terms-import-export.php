<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class WooTermsImportExport {
	use \Blocksy\Companion\WordPressActionsManager;

	private $filters = [
		[
			'action' => 'woocommerce_product_export_row_data',
			'args' => 3
		],

		[
			'action' => 'woocommerce_product_import_inserted_product_object',
			'args' => 2
		],
	];

	public function __construct() {
		$this->attach_hooks();
	}

	public function woocommerce_product_export_row_data($row, $product, $exporter) {
		$custom_data = blc_theme_functions()->blocksy_manager()
			->woocommerce
			->import_export
			->get_custom_data();

		$custom_data = $this->export_categories($product, $custom_data);
		$custom_data = $this->export_brands($product, $custom_data);
		$custom_data = $this->export_attributes($product, $custom_data);

		blc_theme_functions()->blocksy_manager()
			->woocommerce
			->import_export
			->set_custom_data($custom_data);

		return $row;
	}

	public function woocommerce_product_import_inserted_product_object($product, $data) {
		if (! $product instanceof \WC_Product) {
			return $product;
		}

		$custom_data = \Blocksy\WooImportExport::get_import_file_data();

		$this->import_categories($product, $custom_data);
		$this->import_brands($product, $custom_data);
		$this->import_attributes($product, $custom_data);

		return $product;
	}

	// Import

	private function import_categories($product, $custom_data) {
		$parsed_categories_data = blocksy_akg('blocksy_product_categories', $custom_data, []);

		if (empty($parsed_categories_data)) {
			return;
		}

		foreach ($parsed_categories_data as $category_data) {
			$category = get_term_by('name', $category_data['name'], 'product_cat');

			if (! $category) {
				continue;
			}

			$category_id = $category->term_id;

			if (
				isset($category_data['thumb'])
				&&
				! empty($category_data['thumb'])
			) {
				$image = \Blocksy\WooImportExport::get_attachment_id_from_url(
					$category_data['thumb'],
					$product->get_id()
				);

				if (! is_wp_error($image)) {
					update_term_meta($category_id, 'thumbnail_id', $image);
				}
			}

			if (
				isset($category_data['meta'])
				&&
				! get_term_meta($category_id, 'blocksy_taxonomy_meta_options', true)
			) {
				update_term_meta(
					$category_id,
					'blocksy_taxonomy_meta_options',
					$category_data['meta']
				);
			}
		}
	}

	private function import_brands($product, $custom_data) {
		$parsed_brands_data = blocksy_akg('blocksy_product_brands', $custom_data, []);

		if (empty($parsed_brands_data)) {
			return;
		}

		foreach ($parsed_brands_data as $brand_data) {
			$brand = get_term_by('name', $brand_data['name'], 'product_brand');

			if (! $brand) {
				$brand_id = wp_insert_term($brand_data['name'], 'product_brand');

				if (is_wp_error($brand_id)) {
					continue;
				}

				$brand = get_term($brand_id);
			}

			$brand_id = $brand->term_id;

			if (
				isset($brand_data['thumb'])
				&&
				! empty($brand_data['thumb'])
			) {
				$image = \Blocksy\WooImportExport::get_attachment_id_from_url(
					$brand_data['thumb'],
					$product->get_id()
				);

				if (! is_wp_error($image)) {
					update_term_meta($brand_id, 'thumbnail_id', $image);
				}
			}

			if (
				isset($brand_data['meta'])
				&&
				! get_term_meta($brand_id, 'blocksy_taxonomy_meta_options', true)
			) {
				if (isset($brand_data['meta']['image'])) {
					$image = \Blocksy\WooImportExport::get_attachment_id_from_url(
						$brand_data['meta']['image']['url'],
						$product->get_id()
					);

					if (! is_wp_error($image)) {
						$brand_data['meta']['image'] = [
							'attachment_id' => $image,
							'url' => wp_get_attachment_url($image)
						];
					}
				}

				update_term_meta($brand_id, 'blocksy_taxonomy_meta_options', $brand_data['meta']);
			}
		}
	}

	private function import_attributes($product, $custom_data) {
		$parsed_attributes_data = blocksy_akg('blocksy_product_attributes', $custom_data, []);

		if (empty($parsed_attributes_data)) {
			return;
		}

		// Drop attributes cache so we can update them properly.
		wp_schedule_single_event(time(), 'woocommerce_flush_rewrite_rules');
		delete_transient('wc_attribute_taxonomies');
		\WC_Cache_Helper::invalidate_cache_group('woocommerce-attributes');

		foreach (array_values(wc_get_attribute_taxonomies()) as $tax) {
			$taxonomy = (array) $tax;

			foreach ($parsed_attributes_data as $attribute_data) {
				if ($attribute_data['name'] !== $taxonomy['attribute_label']) {
					continue;
				}

				wc_update_attribute(
					$taxonomy['attribute_id'],
					[
						'type' => $attribute_data['type'] ?? 'select',
					]
				);
			}
		}

		foreach ($parsed_attributes_data as $attribute_data) {
			if (
				! isset($attribute_data['children'])
				||
				empty($attribute_data['children'])
			) {
				continue;
			}

			foreach ($attribute_data['children'] as $term_data) {
				$term = get_term_by(
					'name',
					$term_data['name'],
					wc_attribute_taxonomy_name($attribute_data['slug'])
				);

				if (! $term) {
					continue;
				}

				$term_id = $term->term_id;

				if (
					! isset($term_data['meta'])
					||
					get_term_meta($term_id, 'blocksy_taxonomy_meta_options', true)
				) {
					continue;
				}

				if (isset($term_data['meta']['image'])) {
					$image = \Blocksy\WooImportExport::get_attachment_id_from_url(
						$term_data['meta']['image']['url'],
						$product->get_id()
					);

					if (! is_wp_error($image)) {
						$term_data['meta']['image'] = [
							'attachment_id' => $image,
							'url' => wp_get_attachment_url($image)
						];
					}
				}

				update_term_meta($term_id, 'blocksy_taxonomy_meta_options', $term_data['meta']);
			}
		}
	}

	// Export

	private function export_categories($product, $custom_data) {
		$categories = get_the_terms($product->get_id(), 'product_cat');

		if (
			is_wp_error($categories)
			||
			! is_array($categories)
		) {
			return $custom_data;
		}

		$new_categories = [];

		foreach ($categories as $category) {
			$category_meta = get_term_meta(
				$category->term_id,
				'blocksy_taxonomy_meta_options',
				true
			);

			$thumbnail_id = get_term_meta(
				$category->term_id,
				'thumbnail_id',
				true
			);

			$new_categories[] = [
				'name' => $category->name,
				'thumb' => wp_get_attachment_url($thumbnail_id),
				'meta' => $category_meta
			];
		}

		if (empty($new_categories)) {
			return $custom_data;
		}

		$existing = [];

		if (isset($custom_data['blocksy_product_categories'])) {
			$existing = $custom_data['blocksy_product_categories'];
		}

		$seen = [];

		foreach (array_merge($existing, $new_categories) as $cat) {
			$key = $cat['name'] . '|' . $cat['thumb'];

			if (! isset($seen[$key])) {
				$seen[$key] = $cat;
			}
		}

		$custom_data['blocksy_product_categories'] = array_values($seen);

		return $custom_data;
	}

	private function export_brands($product, $custom_data) {
		$brands = get_the_terms($product->get_id(), 'product_brand');

		if (
			is_wp_error($brands)
			||
			! is_array($brands)
		) {
			return $custom_data;
		}

		$new_brands = [];

		foreach ($brands as $brand) {
			$brand_meta = get_term_meta(
				$brand->term_id,
				'blocksy_taxonomy_meta_options',
				true
			);

			$thumbnail_id = get_term_meta(
				$brand->term_id,
				'thumbnail_id',
				true
			);

			if (
				isset($brand_meta['image']['attachment_id'])
				&&
				! empty($brand_meta['image']['attachment_id'])
			) {
				$brand_meta['image'] = [
					'attachment_id' => $brand_meta['image']['attachment_id'],
					'url' => wp_get_attachment_url($brand_meta['image']['attachment_id'])
				];
			}

			$new_brands[] = [
				'name' => $brand->name,
				'thumb' => wp_get_attachment_url($thumbnail_id),
				'meta' => $brand_meta
			];
		}

		if (empty($new_brands)) {
			return $custom_data;
		}

		$existing = [];

		if (isset($custom_data['blocksy_product_brands'])) {
			$existing = $custom_data['blocksy_product_brands'];
		}

		$seen = [];

		foreach (array_merge($existing, $new_brands) as $brand) {
			$key = $brand['name'] . '|' . $brand['thumb'];

			if (! isset($seen[$key])) {
				$seen[$key] = $brand;
			}
		}

		$custom_data['blocksy_product_brands'] = array_values($seen);

		return $custom_data;
	}

	private function export_attributes($product, $custom_data) {
		$attributes = $product->get_attributes();

		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if (
			is_wp_error($attribute_taxonomies)
			||
			! is_array($attribute_taxonomies)
		) {
			return $custom_data;
		}

		$existing = [];

		if (isset($custom_data['blocksy_product_attributes'])) {
			$existing = $custom_data['blocksy_product_attributes'];
		}

		$result = [];

		foreach ($existing as $attr) {
			$result[$attr['slug']] = $attr;

			$children = [];

			if (isset($attr['children']) && is_array($attr['children'])) {
				foreach ($attr['children'] as $child) {
					$children[$child['name']] = $child;
				}
			}

			$result[$attr['slug']]['children'] = $children;
		}

		foreach ($attribute_taxonomies as $attribute) {
			$is_in_product = in_array(
				wc_attribute_taxonomy_name($attribute->attribute_name),
				array_keys($attributes)
			);

			if (! $is_in_product) {
				continue;
			}

			$attribute_for_product = $attributes[wc_attribute_taxonomy_name($attribute->attribute_name)];

			if (! isset($attribute_for_product['options'])) {
				continue;
			}

			$slug = $attribute->attribute_name;

			if (! isset($result[$slug])) {
				$result[$slug] = [
					'name' => $attribute->attribute_label,
					'slug' => $attribute->attribute_name,
					'type' => $attribute->attribute_type,
					'children' => []
				];
			}

			$terms = get_terms([
				'taxonomy' => wc_attribute_taxonomy_name($attribute->attribute_name),
				'hide_empty' => false,
				'include' => $attribute_for_product['options']
			]);

			foreach ($terms as $term) {
				$term_meta = get_term_meta($term->term_id, 'blocksy_taxonomy_meta_options', true);

				$result[$slug]['children'][$term->name] = [
					'name' => $term->name,
					'meta' => $term_meta
				];
			}
		}

		if (empty($result)) {
			return $custom_data;
		}

		$final_attributes = [];

		foreach ($result as $slug => $attr) {
			$attr['children'] = array_values($attr['children']);
			$final_attributes[] = $attr;
		}

		$custom_data['blocksy_product_attributes'] = $final_attributes;

		return $custom_data;
	}
}
