<?php

namespace TI\Template_Cloud\Modules;

class Cache_Manager implements Module_Interface {

	/**
	 * Initialize the module.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'post_updated', [ $this, 'maybe_bust_post_cache' ] );
		add_action( 'delete_post', [ $this, 'maybe_bust_post_cache' ] );
	}

	/**
	 * Bust the cache for a post if it's a block pattern.
	 *
	 * @param int $post_id The post ID.
	 *
	 * @return void
	 */
	public function maybe_bust_post_cache( $post_id ) {
		if ( get_post_type( $post_id ) === 'wp_block' ) {
			$this->bust_all_cache();
		}
	}

	/**
	 * Bust all pattern queries transients.
	 *
	 * @return void
	 */
	public function bust_all_cache() {
		$terms = get_terms(
			array(
				'taxonomy'   => [ Admin::COLLECTION_TAXONOMY, Admin::CATEGORY_TAXONOMY ],
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) || ! is_array( $terms ) ) {
			return;
		}

		foreach ( $terms as $term ) {
			delete_transient( self::get_cache_key( $term->term_id ) );
		}
	}

	/**
	 * Get a pattern collection from the cache.
	 *
	 * @param \WP_Term $collection The collection to get from the cache.
	 */
	public static function get_collection_from_cache( $collection ) {
		$pattern_data = get_transient( self::get_cache_key( $collection->term_id ) );

		return ! $pattern_data ? [] : $pattern_data;
	}

	/**
	 * Update the cache for a collection.
	 *
	 * @param \WP_Term $collection The collection to update the cache for.
	 * @param array    $data The data to update the cache with.
	 *
	 * @return void
	 */
	public static function update_collection_cache( $collection, $data ) {
		set_transient( self::get_cache_key( $collection->term_id ), $data, DAY_IN_SECONDS );
	}

	/**
	 * Get the cache key for a collection.
	 *
	 * @param int $collection_id The collection ID.
	 *
	 * @return string
	 */
	public static function get_cache_key( $collection_id ) {
		return 'tc_collection_' . $collection_id;
	}
}
