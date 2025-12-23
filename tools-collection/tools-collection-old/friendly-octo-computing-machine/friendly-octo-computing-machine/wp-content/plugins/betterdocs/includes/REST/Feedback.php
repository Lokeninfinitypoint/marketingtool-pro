<?php

namespace WPDeveloper\BetterDocs\REST;

use WP_REST_Request;
use WPDeveloper\BetterDocs\Core\BaseAPI;

class Feedback extends BaseAPI {
	/**
	 * @return mixed
	 */
	public function register() {
		$this->post(
			'/feedback/(?P<id>\d+)',
			[ $this, 'save' ],
			[
				'id'       => [
					'type'              => 'integer',
					'validate_callback' => function ( $param, $request, $key ) {
						return ! empty( $param ) && is_numeric( $param ) && get_post( $param ) !== null;
					},
					'required'          => false,
					'default'           => null
				],
				'feelings' => [
					'type'              => 'string',
					'validate_callback' => function ( $param, $request, $key ) {
						$allowed_feelings = [ 'happy', 'sad', 'normal' ];
						return in_array( $param, $allowed_feelings );
					},
					'required'          => true
				]
			]
		);

		$this->register_field(
			'docs',
			'word_count',
			[
				'get_callback' => [ $this, 'get_word_count' ]
			]
		);

		$this->register_field(
			'docs',
			'total_views',
			[
				'get_callback' => [ $this, 'get_total_views' ]
			]
		);

		$this->register_field(
			'docs',
			'reactions',
			[
				'get_callback' => [ $this, 'get_reaction_count' ]
			]
		);

		$this->register_field(
			'docs',
			'author_info',
			[
				'get_callback' => [ $this, 'get_author_info' ]
			]
		);

		$this->register_field(
			'docs',
			'doc_category_info',
			[
				'get_callback' => [ $this, 'get_doc_category_info' ]
			]
		);

		$this->register_field(
			'docs',
			'doc_tag_info',
			[
				'get_callback' => [ $this, 'get_doc_tag_info' ]
			]
		);

		// $this->register_field( 'docs', 'author_list', [
		//     'get_callback' => [$this, 'get_author_list']
		// ] );
	}

	public function get_author_list( $object, $field_name, $request ) {
		$args  = [
			'fields' => [
				'ID',
				'user_login',
				'display_name'
			]
		];
		$users = get_users( $args );
		return $users;
	}

	public function analytics_by_post_id( $post_id ) {
		global $wpdb;

		$where = "WHERE post_id='" . esc_sql( $post_id ) . "'";
		return $wpdb->get_results(
			"SELECT
                sum(impressions) as totalViews,
                sum(unique_visit) as totalUniqueViews,
                sum(happy + sad + normal) as totalReactions,
                sum(happy) as totalHappy,
                sum(normal) as totalNormal,
                sum(sad) as totalSad
            FROM {$wpdb->prefix}betterdocs_analytics
            $where"
		);
	}

	public function get_word_count( $object, $field_name, $request ) {
		return str_word_count( trim( strip_tags( get_post_field( 'post_content', $object['id'] ) ) ) );
	}

	public function get_total_views( $object, $field_name, $request ) {
		$analytics = $this->analytics_by_post_id( $object['id'] );

		if ( ! empty( $analytics ) ) {
			return isset( $analytics[0]->totalViews ) ? $analytics[0]->totalViews : 0;
		} else {
			return 0;
		}
	}

	public function get_reaction_count( $object, $field_name, $request ) {
		$analytics = $this->analytics_by_post_id( $object['id'] );

		if ( ! empty( $analytics ) ) {
			return [
				'happy'  => isset( $analytics[0]->totalHappy ) ? $analytics[0]->totalHappy : 0,
				'normal' => isset( $analytics[0]->totalNormal ) ? $analytics[0]->totalNormal : 0,
				'sad'    => isset( $analytics[0]->totalSad ) ? $analytics[0]->totalSad : 0
			];
		} else {
			return [
				'happy'  => 0,
				'normal' => 0,
				'sad'    => 0
			];
		}
	}

	public function save( WP_REST_Request $request ) {
		global $wpdb;
		$docs_id  = isset( $request['id'] ) ? esc_sql( intval( $request['id'] ) ) : null;
		$feelings = isset( $request['feelings'] ) ? esc_sql( $request['feelings'] ) : 'happy';
		if ( $docs_id !== null && get_post( $docs_id ) && get_option( 'betterdocs_db_version' ) == true ) {
			$post_id = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT *
                    FROM {$wpdb->prefix}betterdocs_analytics
                    WHERE created_at = %s AND post_id = %d",
					date( 'Y-m-d' ),
					$docs_id
				)
			);

			if ( ! empty( $post_id ) ) {
				$feelings_increment = $post_id[0]->{$feelings} + 1;
                // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				$insert = $wpdb->query(
					$wpdb->prepare(
						"UPDATE {$wpdb->prefix}betterdocs_analytics
                    SET " . $feelings . ' = ' . $feelings_increment . '
                    WHERE created_at = %s AND post_id = %d',
						[
							date( 'Y-m-d' ),
							$docs_id
						]
					)
				);
			} else {
                // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				$insert = $wpdb->query(
					$wpdb->prepare(
						"INSERT INTO {$wpdb->prefix}betterdocs_analytics
                        ( post_id, " . $request['feelings'] . ', created_at )
                        VALUES ( %d, %d, %s )',
						[
							$docs_id,
							1,
							date( 'Y-m-d' )
						]
					)
				);
			}

			if ( $insert == true ) {
				return true;
			}
		}
		return false;
	}

	public function get_author_info( $object, $field_name, $request ) {
		$author_id = isset( $object['author'] ) ? $object['author'] : '';
		if ( ! empty( $author_id ) ) {
			return [
				'name'            => get_the_author_meta( 'display_name', $author_id ),
				'author_nicename' => get_the_author_meta( 'nicename', $author_id ),
				'author_url'      => get_author_posts_url( $author_id )
			];
		}
		return [];
	}

	public function get_doc_category_info( $object, $field_name, $request ) {
		$category_term_names = [];
		$doc_categories      = ! empty( $object['doc_category'] ) ? $object['doc_category'] : [];
		foreach ( $doc_categories as $doc_category_id ) {
			array_push(
				$category_term_names,
				[
					'term_name' => get_term( $doc_category_id )->name,
					'term_url'  => get_term_link( $doc_category_id )
				]
			);
		}
		return $category_term_names;
	}

	public function get_doc_tag_info( $object, $field_name, $request ) {
		$doc_tag_term_names = [];
		$doc_tags           = ! empty( $object['doc_tag'] ) ? $object['doc_tag'] : [];
		foreach ( $doc_tags as $tag_id ) {
			array_push(
				$doc_tag_term_names,
				[
					'term_name' => get_term( $tag_id )->name,
					'term_url'  => get_term_link( $tag_id )
				]
			);
		}
		return $doc_tag_term_names;
	}
}
