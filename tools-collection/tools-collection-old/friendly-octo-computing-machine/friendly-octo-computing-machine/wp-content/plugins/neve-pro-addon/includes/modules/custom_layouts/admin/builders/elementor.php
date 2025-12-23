<?php
/**
 * Replace header, footer or hooks for Elementor page builder.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Traits\Core;
use Neve_Pro\Admin\Custom_Layouts_Cpt;

/**
 * Class Elementor
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
class Elementor extends Abstract_Builders {
	use Core;

	/**
	 * Check if class should load or not.
	 *
	 * @return bool
	 */
	public function should_load() {
		return class_exists( '\Elementor\Plugin', false );
	}

	/**
	 * Function that enqueues styles if needed.
	 */
	public function add_styles() {
		return false;
	}

	/**
	 * Builder id.
	 *
	 * @return string
	 */
	function get_builder_id() {
		return 'elementor';
	}

	/**
	 * Load markup for current hook.
	 *
	 * @param int $post_id Layout id.
	 *
	 * @return mixed|void
	 */
	function render( $post_id ) {
		$post_id = Abstract_Builders::maybe_get_translated_layout( $post_id );
		$content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id, true );
		echo apply_filters( 'neve_custom_layout_magic_tags', $content, $post_id ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return true;
	}

	/**
	 * Enqueue necessary hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		parent::register_hooks();

		$custom_layouts = Custom_Layouts_Cpt::get_custom_layouts();
		foreach ( $custom_layouts as $layout => $custom_layout_ids ) {
			$this->try_re_enqueue_wordpress_styles( $custom_layout_ids );
		}
	}

	/**
	 * Re-enqueue WordPress styles if needed. Some plugins might dequeue them, which will break the layout.
	 *
	 * @param int[] $custom_layout_ids The custom layout IDs.
	 * @return void
	 */
	public function try_re_enqueue_wordpress_styles( $custom_layout_ids ) {

		/**
		 * If we have some core blocks in the custom layout, we need to re-enqueue their styles.
		 */
		$has_core_blocks = false;
		foreach ( $custom_layout_ids as $custom_layout_id => $priority ) {
			if ( has_blocks( $custom_layout_id ) ) {
				$has_core_blocks = true;
				break;
			}
		}

		if ( ! $has_core_blocks ) {
			return;
		}

		add_action(
			'wp_enqueue_scripts',
			function() {
				if ( wp_style_is( 'wp-block-library', 'enqueued' ) ) {
					return;
				}

				wp_enqueue_style( 'wp-block-library' );
			},
			1000 // After Elementor de-queues the block library styles.
		);
	}
}
