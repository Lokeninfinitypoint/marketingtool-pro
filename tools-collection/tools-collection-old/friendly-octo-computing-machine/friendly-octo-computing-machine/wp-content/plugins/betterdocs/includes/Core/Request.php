<?php

namespace WPDeveloper\BetterDocs\Core;

use WPDeveloper\BetterDocs\Utils\Base;

class Request extends Base {
	/**
	 * Flag for already parsed or not
	 *
	 * Specially needed for those who don't update pro yet.
	 * @var boolean
	 */
	protected static $already_parsed = false;

	/**
	 * List of BetterDocs Perma Structure
	 * @var array
	 */
	private $perma_structure = [];

	/**
	 * List of BetterDocs Query Vars Agains Page Structure.
	 * @var array
	 */
	private $query_vars = [];

	/**
	 * List of Query Variables from $wp->query_vars.
	 * @var array
	 */
	private $wp_query_vars = [];

	/**
	 * Rewrite Class Reference of BetterDocs
	 * @var Rewrite
	 */
	protected $rewrite;

	/**
	 * Settings Class Reference of BetterDocs
	 * @var Settings
	 */
	protected $settings;

	public function __construct( Rewrite $rewrite, Settings $settings ) {
		$this->rewrite  = $rewrite;
		$this->settings = $settings;
	}

	public function init() {
		if ( is_admin() ) {
			return;
		}

        $this->perma_structure = [
            'is_docs'          => trim( $this->rewrite->get_base_slug(), '/' ),
            'is_docs_feed'     => trim( $this->rewrite->get_base_slug(), '/' ) . '/%feed%',
            'is_docs_category' => trim( $this->settings->get( 'category_slug', 'docs-category' ), '/' ) . '/%doc_category%',
            'is_docs_tag'      => trim( $this->settings->get( 'tag_slug', 'docs-tag' ), '/' ) . '/%doc_tag%',
            'is_single_docs'   => trim( $this->settings->get( 'permalink_structure', 'docs' ), '/' ) . '/%name%',
            'is_docs_author'   => trim( $this->rewrite->get_base_slug(), '/' ) . '/authors/%author%'
        ];

        $this->query_vars = [
            'is_docs'          => ['post_type'],
            'is_docs_feed'     => ['doc_category'],
            'is_docs_category' => ['doc_category'],
            'is_docs_tag'      => ['doc_tag'],
            'is_single_docs'   => ['name', 'docs', 'post_type'],
            'is_docs_author'   => ['post_type', 'author']
        ];

		add_action( 'parse_request', [ $this, 'parse' ] );

		/**
		 * This is for Backward compatibility if pro not updated.
		 */
		add_action( 'parse_request', [ $this, 'backward_compability' ], 11 );

		/**
		 * Make Compatible With Permalink Manager Plugin
		 */
		add_filter( 'permalink_manager_detected_element_id', [ $this, 'provide_compatibility' ], 10, 3 );
	}

	public function provide_compatibility( $element_id, $uri_parts, $request_url ) {
		if ( $request_url == $this->settings->get( 'docs_slug' ) ) {
			$element_id = '';
		}
		return $element_id;
	}

	protected function is_docs( &$query_vars ) {
		if ( ! $this->settings->get( 'builtin_doc_page', true ) ) {
			$query_vars['post_type'] = 'page';
			$query_vars['name']      = trim( $this->rewrite->get_base_slug(), '/' );
		}

		return $query_vars;
	}

	public function is_docs_feed( $query_vars ) {
		global $wp_rewrite;
		return isset( $query_vars['feed'] ) && in_array( $query_vars['feed'], $wp_rewrite->feeds );
	}

    public function is_docs_author( $query_vars ) {
        return isset( $query_vars['author'] ) ? true : false;
    }

    protected function is_single_docs( $query_vars ) {
        if ( ! isset( $query_vars['name'] ) ) {
            return false;
        }

		global $wpdb;
		$name = $query_vars['name'];

		$_post_id = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type = %s LIMIT 1",
				esc_sql( $name ),
				'docs'
			)
		);

		return $_post_id > 0;
	}

	protected function is_docs_category( $query_vars ) {
		return $this->term_exists( $query_vars, 'doc_category' );
	}

	protected function is_docs_tag( $query_vars ) {
		return $this->term_exists( $query_vars, 'doc_tag' );
	}

	protected function term_exists( $query_vars, $taxonomy ) {
		if ( ! isset( $query_vars[ $taxonomy ] ) ) {
			return false;
		}

		return term_exists( $query_vars[ $taxonomy ], $taxonomy );
	}

	public function set_perma_structure( $structures = [] ) {
		$this->perma_structure = array_merge( $this->perma_structure, $structures );
	}

	public function set_query_vars( $query_vars = [] ) {
		$this->query_vars = array_merge( $this->query_vars, $query_vars );
	}

	public function backward_compability( $wp ) {
		if ( static::$already_parsed ) {
			return;
		}

		$this->permalink_magic( $wp );
	}

	public function parse( $wp ) {
		static::$already_parsed = true;

        $this->perma_structure = apply_filters('docs_rewrite_rules', $this->perma_structure);

        $this->permalink_magic( $wp );
    }

	protected function permalink_magic( $wp ) {
		$this->wp_query_vars = $wp->query_vars;

		if ( ! empty( $this->perma_structure ) ) {
			$_valid = [];

			foreach ( $this->perma_structure as $_type => $structure ) {
				$_perma_vars = $this->is_perma_valid_for( $structure, $wp->request );

                // $_valid = empty( $_valid ) && $_perma_vars ? [ 'type' => $_type, 'query_vars' => $_perma_vars ] : $_valid;
                if ( ( $_perma_vars && method_exists( $this, $_type ) && call_user_func_array( [$this, $_type], [ & $_perma_vars] ) ) ) {
                    // dump( $_type, $_perma_vars );
                    if ( $_type === 'is_single_docs' || $_type == 'is_docs_feed' || $_type == 'is_docs_author' ) {
                        $_perma_vars['post_type'] = 'docs';
                    }
                    $_valid = ['type' => $_type, 'query_vars' => $_perma_vars];
                }
            }

			$type       = isset( $_valid['type'] ) ? $_valid['type'] : '';
			$query_vars = isset( $_valid['query_vars'] ) ? $_valid['query_vars'] : [];

			if ( ! empty( $type ) ) {
				unset( $this->query_vars[ $type ] );
				array_map(
					function ( $_vars ) use ( &$wp ) {
						array_map(
							function ( $_var ) use ( &$wp ) {
								unset( $wp->query_vars[ $_var ] );
							},
							$_vars
						);
					},
					$this->query_vars
				);
			}

            $wp->query_vars = is_array( $query_vars ) ? array_merge( $wp->query_vars, $query_vars ) : $wp->query_vars;
            // Fallback
            if ( ! empty( $_valid ) ) {
                unset( $wp->query_vars['attachment'] );
            }
        }
    }

	/**
	 * This method is responsible for checking a structure is valid again a request.
	 *
	 * @param string $structure
	 * @param string $request
	 * @return array|bool
	 */
	private function is_perma_valid_for( $structure, $request ) {
		if ( empty( $structure ) ) {
			return false;
		}

		$_tags                 = explode( '/', trim( $structure, '/' ) );
		$_replace_matched_tags = [];

		$_replace_tags = array_filter(
			$_tags,
			function ( $item ) use ( &$_replace_matched_tags ) {
				$_is_valid = strpos( $item, '%' ) !== false;
				if ( $_is_valid ) {
					$_replace_matched_tags[] = trim( $item, '%' );
				}
				return $_is_valid;
			}
		);

		$_perma_structure = preg_quote( $structure, '/' );
		$_perma_structure = str_replace( $_replace_tags, '([^\/]+)', $_perma_structure );

		preg_match( "/^$_perma_structure$/", $request, $matches );

		if ( empty( $matches ) || ! is_array( $matches ) ) {
			return false;
		}

		if ( count( $matches ) === 1 ) {
			return [ 'post_type' => 'docs' ];
		}

		unset( $matches[0] );

		return array_combine( $_replace_matched_tags, $matches );
	}
}
