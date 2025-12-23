<?php

namespace Blocksy\Extensions\MegaMenu;

class Api {
	public function __construct() {
		add_action(
			'wp_ajax_blc_retrieve_mega_menu_content',
			[$this, 'blc_retrieve_mega_menu_content']
		);

		add_action(
			'wp_ajax_nopriv_blc_retrieve_mega_menu_content',
			[$this, 'blc_retrieve_mega_menu_content']
		);
	}

	private function submenu_get_children_ids($id, $items) {
		$ids = wp_filter_object_list(
			$items,
			['menu_item_parent' => $id],
			'and',
			'ID'
		);

		foreach ($ids as $id) {
			$ids = array_merge(
				$ids,
				$this->submenu_get_children_ids($id, $items)
			);
		}

		return $ids;
	}

	public function blc_retrieve_mega_menu_content() {
		if (
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			! isset($_POST['ids'])
			||
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			! isset($_POST['menu_id'])
		) {
			wp_send_json_error();
		}

		add_filter(
			'wp_nav_menu_objects',
			function ($items, $args) {
				if (empty($args->blocksy_ajax_submenu)) {
					return $items;
				}

				$children = $this->submenu_get_children_ids(
					$args->blocksy_ajax_submenu,
					$items
				);

				foreach ($items as $key => $item) {
					if (! in_array($item->ID, $children)) {
						unset($items[$key]);
					}
				}

				return $items;
			},
			10, 2
		);

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$ids = explode(',', sanitize_text_field(wp_unslash($_POST['ids'])));

		$result = [];

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$menu_id = sanitize_text_field(wp_unslash($_POST['menu_id']));

		foreach ($ids as $id) {
			$result[] = [
				'id' => intval($id),
				'content' => $this->get_content_for(intval($id), $menu_id)
			];
		}

		wp_send_json_success([
			'content' => $result
		]);
	}

	public function get_content_for($id, $menu_id) {
		ob_start();
		wp_nav_menu([
			'container' => false,
			'blocksy_advanced_item' => true,
			'blocksy_mega_menu' => true,
			'menu' => $menu_id,
			'blocksy_ajax_submenu' => $id,
			'items_wrap' => '%3$s'
		]);
		$result = ob_get_clean();

		return '<ul class="sub-menu" role="menu">' . $result . '</ul>';
	}
}

