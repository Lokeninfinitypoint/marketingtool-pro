<?php

namespace WPDeveloper\BetterDocs\Core;

use WP_Query;
use WPDeveloper\BetterDocs\Utils\Base;
use WPDeveloper\BetterDocs\Utils\Database;
use WPDeveloper\BetterDocs\Dependencies\DI\Container;

class Migration extends Base {
	/**
	 * Database
	 * @var Database
	 */
	private $database;
	private $settings;
	private $container;

	public function __construct( Container $container ) {
		$this->container = $container;
		$this->database  = $container->get( Database::class );
		$this->settings  = $container->get( Settings::class );
	}

	public function init( $version ) {
		if ( $version > 250 ) {
			for ( $_version = 250; $_version <= $version; $_version++ ) {
				if ( method_exists( $this, "v$_version" ) ) {
					call_user_func( [ $this, "v$_version" ] );
				}
			}
		}

		$this->search_migration();

		/**
		 * Settings Migration
		 */
		$this->settings->migration( $version );
	}

	public function search_migration() {
		global $wpdb;
		if ( ! $this->database->get( 'betterdocs_search_data_migration', false ) ) {
			$search_data = $this->database->get( 'betterdocs_search_data' );
			if ( ! empty( $search_data ) ) {
				$search_data_arr = unserialize( $search_data );
				foreach ( $search_data_arr as $key => $value ) {
					$args = [
						'post_type'        => 'docs',
						'post_status'      => 'publish',
						'posts_per_page'   => -1,
						'suppress_filters' => true,
						's'                => $key
					];

					$loop = new WP_Query( $args );
					if ( $loop->have_posts() ) {
						$count           = $value;
						$not_found_count = 0;
					} else {
						$count           = 0;
						$not_found_count = $value;
					}

					$keyword = $wpdb->get_var(
						$wpdb->prepare(
							"
                            SELECT keyword
                            FROM {$wpdb->prefix}betterdocs_search_keyword
                            WHERE keyword = %s",
							$key
						)
					);

					if ( $keyword == null ) {
						$insert = $wpdb->query(
							$wpdb->prepare(
								"INSERT INTO {$wpdb->prefix}betterdocs_search_keyword
                                ( keyword )
                                VALUES ( %s )",
								[
									$key
								]
							)
						);

						if ( $insert ) {
							$wpdb->query(
								$wpdb->prepare(
									"INSERT INTO {$wpdb->prefix}betterdocs_search_log
                                    (keyword_id, count, not_found_count, created_at)
                                    VALUES (%d, %d, %d, %s)",
									[
										$wpdb->insert_id,
										$count,
										$not_found_count,
										date( 'Y-m-d' )
									]
								)
							);
						}
					}
				}

				$this->database->save( 'betterdocs_search_data_migration', '1.0' );
			}
		}
	}
}
