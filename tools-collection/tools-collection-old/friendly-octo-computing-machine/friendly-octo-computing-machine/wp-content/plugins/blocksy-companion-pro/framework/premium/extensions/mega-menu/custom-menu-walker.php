<?php

if (! class_exists('Blocksy_Walker_Nav_Menu_Edit_Custom')) {
	class Blocksy_Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu_Edit {
 		public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
			$item_output = '';

			parent::start_el($item_output, $item, $depth, $args, $id);

			$position = '<fieldset class="field-move';

			$extra = $this->get_fields($item, $depth, $args, $id);

			$output .= str_replace($position, $extra . $position, $item_output);
		}

		protected function get_fields($item, $depth, $args = [], $id = 0) {
			ob_start();

			global $wp_version;
			$item_id = intval($item->ID);

			if (version_compare(preg_replace('/[^0-9\.]/', '', $wp_version), '5.4', '<')) {
				do_action('wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args, $id);
			}

			return ob_get_clean();
		}

	}
}

