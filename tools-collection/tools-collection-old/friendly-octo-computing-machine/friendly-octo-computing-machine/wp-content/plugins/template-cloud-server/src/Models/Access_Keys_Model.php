<?php

namespace TI\Template_Cloud\Models;

/**
 * Class Access_Key_Model
 *
 * Handles access key management for template cloud.
 */
class Access_Keys_Model {
	public const SETTINGS_KEY   = 'ti_template_cloud_keys';
	private const ALLOWED_MODES = [ 'all', 'exclude', 'include' ];
	private const DEFAULT_MODE  = 'all';
	private const KEY_LENGTH    = 32;

	/**
	 * Access key name.
	 *
	 * @var string
	 */
	private $name;
	/**
	 * Access key mode. One of 'all', 'exclude', or 'include'.
	 *
	 * @var string
	 */
	private $mode;
	/**
	 * Collections to include or exclude.
	 *
	 * @var array
	 */
	private $collections;
	/**
	 * Categories to include or exclude.
	 *
	 * @var array
	 */
	private $categories;
	/**
	 * Access key.
	 *
	 * @var string
	 */
	private $key;
	/**
	 * Access key creation date.
	 *
	 * @var string
	 */
	private $created_at;

	/**
	 * Access_Key_Model constructor.
	 *
	 * @param string|null $key The access key to load.
	 */
	public function __construct( $key = null ) {
		$this->name        = __( 'Access Key', 'template-cloud-server' );
		$this->mode        = self::DEFAULT_MODE;
		$this->collections = [];
		$this->categories  = [];
		$this->key         = '';
		$this->created_at  = current_time( 'mysql' );

		if ( $key !== null ) {
			$this->hydrate_from_array( $key );
		}
	}

	/**
	 * Hydrate the model from an array.
	 *
	 * @param string $key Access key.
	 */
	public function hydrate_from_array( $key ) {
		$data = $this->fetch_key_data( $key );

		if ( ! is_array( $data ) ) {
			return $this;
		}

		$this->set_key( $key );
		$this->set_name( isset( $data['name'] ) ? $data['name'] : $this->name );
		$this->set_mode( isset( $data['mode'] ) ? $data['mode'] : $this->mode );
		$this->set_collections( isset( $data['collections'] ) ? $data['collections'] : $this->collections );
		$this->set_categories( isset( $data['categories'] ) ? $data['categories'] : $this->categories );
		$this->set_created_at( isset( $data['created_at'] ) ? $data['created_at'] : $this->created_at );

		return $this;
	}

	/**
	 * Set the access key name.
	 *
	 * @param string $name Access key name.
	 *
	 * @return $this
	 */
	public function set_name( $name ) {
		$this->name = sanitize_text_field( $name );

		return $this;
	}

	/**
	 * Set the access key mode.
	 *
	 * @param string $mode Access key mode. Can be 'all', 'exclude', or 'include'.
	 *
	 * @return $this
	 */
	public function set_mode( $mode ) {
		$this->mode = self::sanitize_key_mode( $mode );

		return $this;
	}

	/**
	 * Set the collections to include or exclude.
	 *
	 * @param array $collections Collections to include or exclude.
	 *
	 * @return $this
	 */
	public function set_collections( $collections ) {
		$this->collections = self::sanitize_term_ids_array( $collections );

		return $this;
	}
	/**
	 * Set the categories to include or exclude.
	 *
	 * @param array $categories Categories to include or exclude.
	 *
	 * @return $this
	 */
	public function set_categories( $categories ) {
		$this->categories = self::sanitize_term_ids_array( $categories );

		return $this;
	}

	/**
	 * Set the access key.
	 *
	 * @param string $key Access key.
	 *
	 * @return $this
	 */
	public function set_key( $key ) {
		$this->key = sanitize_text_field( $key );

		return $this;
	}

	/**
	 * Set the access key creation date.
	 *
	 * @param string $date Access key creation date.
	 *
	 * @return $this
	 */
	public function set_created_at( $date ) {
		$this->created_at = $date;

		return $this;
	}

	/**
	 * Check if the access key is valid.
	 * An access key is valid if it exists in the database.
	 *
	 * @return bool
	 */
	public function exists() {
		if ( empty( $this->key ) ) {
			return false;
		}

		$all_keys = $this->get_saved_data();

		if ( ! is_array( $all_keys ) || empty( $all_keys ) || ! array_key_exists( $this->key, $all_keys ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Save the access key to the database.
	 *
	 * @return bool
	 */
	public function save() {
		$keys               = $this->get_saved_data();
		$keys[ $this->key ] = $this->to_array();

		return $this->save_data( $keys );
	}

	/**
	 * Delete the access key from the database.
	 *
	 * @return bool
	 */
	public function delete() {
		$keys = $this->get_saved_data();

		unset( $keys[ $this->key ] );

		return $this->save_data( $keys );
	}

	/**
	 * Transform the model to an array.
	 *
	 * @return array
	 */
	public function to_array() {
		return [
			'name'        => $this->name,
			'mode'        => $this->mode,
			'collections' => $this->collections,
			'categories'  => $this->categories,
			'created_at'  => $this->created_at,
		];
	}

	/**
	 * Fetch the key data from the database.
	 *
	 * @param string $key Access key.
	 *
	 * @return array|null
	 */
	private function fetch_key_data( $key ) {
		$keys = $this->get_saved_data();

		return isset( $keys[ $key ] ) ? $keys[ $key ] : null;
	}

	/**
	 * Get the saved data from the database.
	 *
	 * @return array
	 */
	public function get_saved_data() {
		$data    = get_option( self::SETTINGS_KEY, '[]' );
		$decoded = json_decode( $data, true );

		return is_array( $decoded ) ? $decoded : [];
	}

	/**
	 * Save the data to the database.
	 *
	 * @param array $data Array of access keys.
	 *
	 * @return bool
	 */
	private function save_data( $data ) {
		return update_option( self::SETTINGS_KEY, wp_json_encode( $data ) );
	}

	/**
	 * Generate a new access key.
	 *
	 * @return string
	 */
	public static function generate_key() {
		return wp_generate_password( self::KEY_LENGTH, false );
	}

	/**
	 * Sanitize the access key mode.
	 *
	 * @param string $mode Access key mode. Can be 'all', 'exclude', or 'include'.
	 *
	 * @return string
	 */
	public static function sanitize_key_mode( $mode ) {
		return in_array( $mode, self::ALLOWED_MODES, true ) ? $mode : self::DEFAULT_MODE;
	}

	/**
	 * Sanitize the term IDs array.
	 *
	 * @param array $term_ids Taxonomy term IDs.
	 *
	 * @return array
	 */
	public static function sanitize_term_ids_array( $term_ids ) {
		if ( ! is_array( $term_ids ) ) {
			return [];
		}

		return array_values(
			array_map(
				'absint',
				array_filter( $term_ids, 'is_numeric' )
			)
		);
	}
}
