<?php

namespace WPDeveloper\BetterDocs\Utils;

use function BetterLinksPro\Dependencies\GuzzleHttp\json_decode;
use function WPML\PHP\Logger\error;

class Helper extends Base {

	public static function get_plugins( $plugin_basename = null ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();
		return $plugin_basename == null ? $plugins : isset( $plugins[ $plugin_basename ] );
	}

	public static function is_plugin_active( $plugin_basename ) {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( $plugin_basename );
	}

	public static function get_tax( $tax = '' ) {
		global $wp_query;

		if ( is_tax( 'knowledge_base' ) ) {
			$_taxes = $wp_query->tax_query->queried_terms;
			if ( array_key_exists( 'doc_category', $_taxes ) ) {
				$tax = 'doc_category';
			} else {
				$tax = 'knowledge_base';
			}
		} elseif ( is_tax( 'doc_category' ) ) {
			$tax = 'doc_category';
		} elseif ( is_tax( 'doc_tag' ) ) {
			$tax = 'doc_tag';
		}

		return $tax;
	}

	public function is_templates() {
		global $wp_query;
		$slug = betterdocs()->settings->get( 'encyclopedia_root_slug', 'encyclopedia' );

		$tax = $this->get_tax();
		if ( is_post_type_archive( 'docs' ) || $tax === 'knowledge_base' || $tax === 'doc_category' || $tax === 'doc_tag' || is_singular( 'docs' ) || is_tax( 'glossaries' ) ) {
			return true;
		}

		if ( isset( $wp_query->query['pagename'] ) && $wp_query->query['pagename'] === $slug ) {
			return true;
		}

		return false;
	}

	public function is_el_templates() {
		$_return_val = betterdocs()->editor->get( 'elementor' )->is_templates();

		if ( $_return_val !== null ) {
			return $_return_val;
		}

		$this->is_templates();
	}

	/**
	 * Which tab to show.
	 *
	 * 1. Drag and Drop UI
	 * 2. Post List UI
	 *
	 * * 1. dnd
	 * * 2. classic
	 *
	 * look into views/admin/docs-ui directory to know more.
	 *
	 * @return string
	 */
	public static function admin_tab() {
		$admin_ui = 'grid';
		if ( isset( $_GET['mode'], $_GET['page'] ) && $_GET['page'] === 'betterdocs-admin' && ! empty( $_GET['mode'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$admin_ui = $_GET['mode'] === 'grid' ? 'grid' : 'list'; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		}

		return $admin_ui;
	}

	public static function is_active( $prev, $current, $class = 'active' ) {
		if ( $current == $prev ) {
			return $class;
		}

		return '';
	}

	public function get_users( $args ) {
		$cache_key = 'betterdocs_cache_admin_user_roles';
		$users     = betterdocs()->database->get_cache( $cache_key );

		if ( false === $users ) {
			$users = get_users( $args );
			betterdocs()->database->set_cache( $cache_key, $users );
		}

		return $users;
	}

	/**
	 * Normalize Menu Array
	 * Menu creator helper
	 *
	 * @since 2.5.0
	 *
	 * @param string $title
	 * @param string $slug
	 * @param string $cap
	 * @param array  $callback
	 *
	 * @return array
	 */
	public static function normalize_menu( $title, $slug, $cap = 'edit_docs', $callback = null, $optional = [] ) {
		$args = [
			'page_title' => $title,
			'menu_title' => $title,
			'capability' => $cap,
			'menu_slug'  => $slug
		];

		if ( $callback != null ) {
			$args['callback'] = $callback;
		}

		return wp_parse_args( $optional, $args );
	}

	/**
	 * Check if the current theme is a block theme.
	 *
	 * @since x.x.x
	 * @return bool
	 */
	public function current_theme_is_fse_theme() {
		if ( function_exists( 'wp_is_block_theme' ) ) {
			return (bool) wp_is_block_theme();
		}
		if ( function_exists( 'gutenberg_is_fse_theme' ) ) {
			return (bool) gutenberg_is_fse_theme();
		}

		return false;
	}

	protected static function is_assoc_array( $array ) {
		return array_keys( $array ) !== range( 0, count( $array ) - 1 );
	}

	public static function merge( &$array1, &$array2 ) {
		$merged = $array1;

		foreach ( $array2 as $key => &$value ) {
			if ( is_array( $value ) && self::is_assoc_array( $value ) && isset( $merged[ $key ] ) && is_array( $merged[ $key ] ) ) {
				$merged[ $key ] = self::merge( $merged[ $key ], $value );
			} elseif ( is_array( $value ) && isset( $merged[ $key ] ) && is_array( $merged[ $key ] ) ) {
				$merged[ $key ] = array_merge( $merged[ $key ], $value );
			} else {
				$merged[ $key ] = $value;
			}
		}

		return $merged;
	}

	public static function get_custom_excerpt( $content, $numOfWords ) {
		$content      = strip_shortcodes( $content );
		$content      = wp_strip_all_tags( $content );
		$words        = explode( ' ', $content );
		$excerptWords = array_slice( $words, 0, $numOfWords );
		$excerpt      = implode( ' ', $excerptWords );
		if ( count( $words ) > $numOfWords ) {
			$excerpt .= '...';
		}
		return $excerpt;
	}

	/**
	 * Get current language from various multilingual plugins
	 *
	 * @return string|null Current language code
	 */
	public static function get_current_language() {
		$current_language = null;

		// WPML Support
		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			global $sitepress;
			if ( $sitepress && $sitepress->is_setup_complete() ) {
				$current_language = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : $sitepress->get_current_language();
			}
		}
		// Polylang Support
		elseif ( function_exists( 'pll_current_language' ) ) {
			$current_language = pll_current_language();
		}
		// qTranslate-X Support
		elseif ( function_exists( 'qtranxf_getLanguage' ) ) {
			$current_language = qtranxf_getLanguage();
		}
		// Weglot Support
		elseif ( function_exists( 'weglot_get_current_language' ) ) {
			$current_language = weglot_get_current_language();
		}
		// TranslatePress Support
		elseif ( class_exists( 'TRP_Translate_Press' ) && function_exists( 'trp_get_current_language' ) ) {
			$current_language = trp_get_current_language();
		}

		return $current_language;
	}

	/**
	 * Check if any multilingual plugin is active
	 *
	 * @return bool
	 */
	public static function is_multilingual_active() {
		return is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ||
			   function_exists( 'pll_current_language' ) ||
			   function_exists( 'qtranxf_getLanguage' ) ||
			   function_exists( 'weglot_get_current_language' ) ||
			   ( class_exists( 'TRP_Translate_Press' ) && function_exists( 'trp_get_current_language' ) );
	}

	/**
	 * Check if we should apply language filtering
	 * Only apply on frontend or when specifically requested
	 *
	 * @return bool
	 */
	public static function should_apply_language_filtering() {
		// Don't apply language filtering in admin context unless it's a frontend request
		if ( is_admin() ) {
			// Allow language filtering for REST API requests that are frontend-facing
			if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
				// Check if this is a frontend REST request (not admin)
				$request_uri = $_SERVER['REQUEST_URI'] ?? '';
				// Don't filter admin REST requests for glossaries management
				if ( strpos( $request_uri, '/wp/v2/glossaries' ) !== false ) {
					return false; // Don't filter admin glossaries management
				}
			}
			return false; // Don't filter other admin requests
		}

		// Apply filtering on frontend
		return true;
	}

	public static function get_current_letter_docs( $current_letter, $limit = '' ) {
		global $wpdb;

		// Check if the encyclopedia_prefix parameter is set

        $encyclopeia_suorce     = betterdocs()->settings->get( 'encyclopedia_source', 'docs' );
        $enable_glossaries      = betterdocs()->settings->get( 'enable_glossaries', false );
        $encyclopedia_root_slug = betterdocs()->settings->get( 'encyclopedia_root_slug', 'encyclopdia' );

		// if($enable_glossaries && $encyclopeia_suorce === 'glossaries'){
		if ( $enable_glossaries && $encyclopeia_suorce === 'glossaries' ) {
			$lang_join = '';
			$lang_where = '';

			// Add language filtering if multilingual plugin is active and we should apply filtering
			$current_language = self::get_current_language();
			if ( $current_language && self::is_multilingual_active() && self::should_apply_language_filtering() ) {
				// For WPML, use icl_translations table
				if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
					$lang_join = " LEFT JOIN {$wpdb->prefix}icl_translations icl_t ON icl_t.element_id = t.term_id AND icl_t.element_type = 'tax_glossaries'";
					$lang_where = " AND (icl_t.language_code = '$current_language' OR icl_t.language_code IS NULL)";
				}
				// For Polylang, use term_relationships with language taxonomy
				elseif ( function_exists( 'pll_current_language' ) ) {
					$lang_join = " LEFT JOIN {$wpdb->term_relationships} tr ON t.term_id = tr.object_id LEFT JOIN {$wpdb->term_taxonomy} tt_lang ON tr.term_taxonomy_id = tt_lang.term_taxonomy_id AND tt_lang.taxonomy = 'language' LEFT JOIN {$wpdb->terms} t_lang ON tt_lang.term_id = t_lang.term_id";
					$lang_where = " AND (t_lang.slug = '$current_language' OR t_lang.slug IS NULL)";
				}
			}

			$query = "
                SELECT
                    t.term_id,
                    t.name AS post_title,
                    t.slug as slug,
                    '' AS post_excerpt,
                    CONCAT('" . get_home_url() . "/$encyclopedia_root_slug/', t.slug) AS permalink,
                    tt.description AS post_content,
                    JSON_OBJECT(
                        'status', COALESCE(MAX(CASE WHEN m.meta_key = 'status' THEN m.meta_value END), ''),
                        'glossary_term_description', COALESCE(MAX(CASE WHEN m.meta_key = 'glossary_term_description' THEN m.meta_value END), '')
                    ) AS meta_data
                FROM
                    {$wpdb->terms} t
                INNER JOIN
                    {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
                LEFT JOIN
                    {$wpdb->termmeta} m ON t.term_id = m.term_id
                $lang_join
                WHERE
                    tt.taxonomy = 'glossaries'
                AND
                    SUBSTRING(t.name, 1, 1) = %s
                $lang_where
                GROUP BY
                    t.term_id
                ORDER BY
                    t.name ASC
                $limit
            ";
		} else {
			$lang_join = '';
			$lang_where = '';

			// Add language filtering for docs if multilingual plugin is active and we should apply filtering
			$current_language = self::get_current_language();
			if ( $current_language && self::is_multilingual_active() && self::should_apply_language_filtering() ) {
				// For WPML, use icl_translations table
				if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
					$lang_join = " LEFT JOIN {$wpdb->prefix}icl_translations icl_t ON icl_t.element_id = {$wpdb->posts}.ID AND icl_t.element_type = 'post_docs'";
					$lang_where = " AND (icl_t.language_code = '$current_language' OR icl_t.language_code IS NULL)";
				}
				// For Polylang, use term_relationships with language taxonomy
				elseif ( function_exists( 'pll_current_language' ) ) {
					$lang_join = " LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id LEFT JOIN {$wpdb->term_taxonomy} tt_lang ON tr.term_taxonomy_id = tt_lang.term_taxonomy_id AND tt_lang.taxonomy = 'language' LEFT JOIN {$wpdb->terms} t_lang ON tt_lang.term_id = t_lang.term_id";
					$lang_where = " AND (t_lang.slug = '$current_language' OR t_lang.slug IS NULL)";
				}
			}

			$query = "
                SELECT ID, post_title, post_excerpt, guid, post_content
                FROM {$wpdb->posts}
                $lang_join
                WHERE post_type = 'docs'
                AND post_status = 'publish'
                AND SUBSTRING(post_title, 1, 1) = %s
                $lang_where
                ORDER BY post_date DESC
                $limit
            ";
		}

		$current_letter_docs = $wpdb->get_results( $wpdb->prepare( $query, $current_letter ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		return $current_letter_docs;
	}

    public static function docs_sort_by_letter( $limit = 10 ) {
        global $wpdb;
        $enable_non_latin = betterdocs()->settings->get( 'encyclopedia_enable_non_latin' );
        $script           = betterdocs()->settings->get( 'encyclopedia_non_latin_option' );
        $letters          = Helper::get_character_range( $enable_non_latin, $script );

        $docs_by_letter     = [];
        $encyclopeia_suorce = betterdocs()->settings->get( 'encyclopedia_source', 'docs' );
        $enable_glossaries  = betterdocs()->settings->get( 'enable_glossaries', false );

        foreach ( $letters as $letter ) {
            $posts = self::get_current_letter_docs( $letter, "LIMIT $limit" );

            if ( is_array( $posts ) && ! empty( $posts ) ) {
                foreach ( $posts as $post ) {
                    $description               = isset($post['meta_data']) ? \json_decode( $post['meta_data'], true ) : '';
                    $glossary_term_description = $description['glossary_term_description'] ?? '';

                    // Remove any <p> tags or other unwanted HTML tags
                    $glossary_term_description = strip_tags( $glossary_term_description );
                    $post_excerpt              = strip_tags( $post['post_excerpt'] ?? '' );

                    // Prepare post data
                    if ( $enable_glossaries && $encyclopeia_suorce === 'glossaries' ) {
                        // For glossaries
                        $post_data = [
                            'id'           => $post['term_id'] ?? '',
                            'post_title'   => $post['post_title'] ?? '',
                            'post_excerpt' => ! empty( $post_excerpt )
                            ? $post_excerpt
                            : ( ! empty( $glossary_term_description )
                                ? self::get_custom_excerpt( $glossary_term_description, 15 )
                                : self::get_custom_excerpt( strip_tags( $post['post_content'] ?? '' ), 15 ) ),
                            'permalink'    => isset( $post['slug'] ) ? get_term_link( $post['slug'], 'glossaries' ) : ''
                        ];
                    } else {
                        // For docs
                        $post_data = [
                            'id'           => $post['ID'] ?? '',
                            'post_title'   => $post['post_title'] ?? '',
                            'post_excerpt' => ! empty( $post_excerpt )
                            ? $post_excerpt
                            : self::get_custom_excerpt( strip_tags( $post['post_content'] ?? '' ), 15 ),
                            'permalink'    => isset( $post['ID'] ) ? get_the_permalink( $post['ID'] ) : ''
                        ];
                    }

                    $docs_by_letter[$letter][] = $post_data;
                }
            }
        }

        return $docs_by_letter;
    }

    public static function get_glossaries() {
        global $wpdb;

        $lang_join = '';
        $lang_where = '';

        // Add language filtering if multilingual plugin is active and we should apply filtering
        $current_language = self::get_current_language();
        if ( $current_language && self::is_multilingual_active() && self::should_apply_language_filtering() ) {
            // For WPML, use icl_translations table
            if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
                $lang_join = " LEFT JOIN {$wpdb->prefix}icl_translations icl_t ON icl_t.element_id = t.term_id AND icl_t.element_type = 'tax_glossaries'";
                $lang_where = " AND (icl_t.language_code = '$current_language' OR icl_t.language_code IS NULL)";
            }
            // For Polylang, use term_relationships with language taxonomy
            elseif ( function_exists( 'pll_current_language' ) ) {
                $lang_join = " LEFT JOIN {$wpdb->term_relationships} tr ON t.term_id = tr.object_id LEFT JOIN {$wpdb->term_taxonomy} tt_lang ON tr.term_taxonomy_id = tt_lang.term_taxonomy_id AND tt_lang.taxonomy = 'language' LEFT JOIN {$wpdb->terms} t_lang ON tt_lang.term_id = t_lang.term_id";
                $lang_where = " AND (t_lang.slug = '$current_language' OR t_lang.slug IS NULL)";
            }
        }

        $query = "
            SELECT t.name
            FROM {$wpdb->terms} t
            INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
            $lang_join
            WHERE tt.taxonomy = 'glossaries'
            $lang_where
            ORDER BY t.name ASC
        ";

		$glossaries = $wpdb->get_col( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		return $glossaries;
	}

	/**
	 * Determine live search template layout, when live search is not selected from customizer(this will work when live search template is not selected from customizer)
	 *
	 * @param string $layout
	 * @return string $layout
	 */
	public static function determine_search_layout( $layout ) {
		if ( $layout ) {
			return $layout;
		}

		$search_layout       = betterdocs()->customizer->defaults->get( 'betterdocs_search_layout_select' );
		$docs_layout         = betterdocs()->customizer->defaults->get( 'betterdocs_docs_layout_select' );
		$archive_page_layout = betterdocs()->customizer->defaults->get( 'betterdocs_archive_layout_select' );
		$single_layout       = betterdocs()->customizer->defaults->get( 'betterdocs_single_layout_select' );

        if ( is_post_type_archive( 'docs' ) ) {
            if ( $docs_layout != "layout-7" && ! $search_layout ) {
                $layout = 'layout-1';
            } else if ( $docs_layout == 'layout-7' && ! $search_layout ) {
                $layout = 'layout-2';
            }
        } else if ( is_tax( 'doc_tag' ) && ! $search_layout ) {
            $layout = 'layout-1';
        } else if ( is_tax( 'doc_category' ) ) {
           if ( $archive_page_layout != 'layout-7' && $archive_page_layout != 'layout-8' && ! $search_layout ) {
                $layout = 'layout-1';
            } else if ( ( $archive_page_layout == 'layout-7' && ! $search_layout ) || ( $archive_page_layout == 'layout-8' && ! $search_layout ) ) {
                $layout = 'layout-2';
            }
        } else if ( is_singular( 'docs' ) ) {
            if ( $single_layout != 'layout-8' && $single_layout != 'layout-9' && ! $search_layout ) {
                $layout = 'layout-1';
            } else if ( ( $single_layout == 'layout-8' && ! $search_layout ) || ( $single_layout == 'layout-9' && ! $search_layout ) ) {
                $layout = 'layout-2';
            }
        }

        return $layout;
    }
    public static function mb_ord_fallback( $char ) {
        $code = unpack( 'N', mb_convert_encoding( $char, 'UCS-4BE', 'UTF-8' ) );
        return $code[1];
    }

    public static function mb_chr_fallback( $code ) {
        return mb_convert_encoding( pack( 'N', $code ), 'UTF-8', 'UCS-4BE' );
    }

    public static function unicodeRange( $start, $end ) {
        $range = [];
        for ( $i = self::mb_ord_fallback( $start ); $i <= self::mb_ord_fallback( $end ); $i++ ) {
            $range[] = self::mb_chr_fallback( $i );
        }
        return $range;
    }

    public static function get_character_range( $enable_non_latin, $script ) {
        if ( $enable_non_latin ) {
            switch ( $script ) {
                case 'arabic':
                    return self::unicodeRange( 'ء', 'ي' );
                case 'cyrillic':
                    return self::unicodeRange( 'А', 'Я' );
                case 'hebrew':
                    return self::unicodeRange( 'א', 'ת' );
                case 'greek':
                    return self::unicodeRange( 'Α', 'Ω' );
                default:
                    return range( 'A', 'Z' );
            }
        }

        return range( 'A', 'Z' );
    }

    public static function get_the_top_most_parent( $term_id ) {
        while ( $term_id != 0 ) {
            $parent_id = wp_get_term_taxonomy_parent_id( $term_id, 'doc_category' );

            if ( $parent_id == 0 ) {
                break;
            }

            $term_id = $parent_id;
        }
        return $term_id;
    }

    public static function get_highest_docs_term() {
        $terms = get_terms( [
            'taxonomy'   => 'doc_category', // Change to your desired taxonomy
            'hide_empty' => true, // Only show terms with posts
            'orderby'    => 'count', // Order by post count
            'order'      => 'DESC', // Descending order
            'number'     => 1 // Get only the top term
        ] );
        return isset( $terms[0] ) ? $terms[0] : [];
    }

    public static function delete_specific_faq_posts_by_faq_category( $term_id ) {
        $args = [
            'post_type'      => 'betterdocs_faq',
            'posts_per_page' => -1,
            'tax_query'      => [
                [
                    'taxonomy' => 'betterdocs_faq_category',
                    'field'    => 'id',
                    'terms'    => $term_id,
                    'operator' => 'IN'
                ]
            ],
            'fields'         => 'ids'
        ];

        $query = new \WP_Query( $args );

        if ( $query->have_posts() ) {
            foreach ( $query->posts as $doc_id ) {
                wp_delete_post( $doc_id, true );
            }
        }
    }

	/**
	 * Function To Normalize Repeater Field For Quick Builder
	 *
	 * @param array $fields
	 * @param array $include_field_keys
	 *
	 * @return array
	 */
	public static function normalize_repeater_field( $fields, $include_field_keys = [] ) {
		if( empty( $include_field_keys ) ) {
			return $fields;
		}

		$normalized_fields = [];

		foreach( $fields as $field ) {
			foreach( $include_field_keys as $field_key ) {
				if( ! isset( $normalized_fields[$field_key] ) ) {
					$normalized_fields[$field_key] = isset( $field[$field_key] ) && ! empty( $field[$field_key] ) ? $field[$field_key] : [];
				} else {
					array_push( $normalized_fields[$field_key], ...( isset( $field[$field_key] ) && ! empty( $field[$field_key] ) ? $field[$field_key] : [] ) );
					$normalized_fields[$field_key] = array_unique( $normalized_fields[$field_key] );
				}
			}
		}

		return $normalized_fields;
	}

	public static function get_local_plugin_data( $basename = '' ) {
        if ( empty( $basename ) ) {
            return false;
        }

        if ( !function_exists( 'get_plugins' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugins = get_plugins();

        if ( !isset( $plugins[ $basename ] ) ) {
            return false;
        }

        return $plugins[ $basename ];
    }

    /**
     * Get default file icon based on programming language
     *
     * @param string $language Programming language identifier
     * @return string Emoji icon for the language
     */
    public static function get_file_icon_by_language( $language ) {
        $icons = [
            'javascript' => '📄',
            'typescript' => '📘',
            'jsx' => '⚛️',
            'tsx' => '⚛️',
            'html' => '🌐',
            'css' => '🎨',
            'scss' => '🎨',
            'sass' => '🎨',
            'less' => '🎨',
            'php' => '🐘',
            'python' => '🐍',
            'java' => '☕',
            'csharp' => '🔷',
            'cpp' => '⚙️',
            'c' => '⚙️',
            'ruby' => '💎',
            'go' => '🐹',
            'rust' => '🦀',
            'swift' => '🦉',
            'kotlin' => '🎯',
            'sql' => '🗃️',
            'json' => '📋',
            'yaml' => '📋',
            'xml' => '📄',
            'markdown' => '📝',
            'bash' => '💻',
            'shell' => '💻',
            'powershell' => '💻',
            'dockerfile' => '🐳',
        ];

        return isset( $icons[$language] ) ? $icons[$language] : '📄';
    }

	/**
	 * Check if AI Chatbot is enabled
	 *
	 * @return bool
	 */
	public function is_ai_chatbot_enabled() {
		$chatbot_active = is_plugin_active( 'betterdocs-ai-chatbot/betterdocs-ai-chatbot.php' );
		$chatbot_license_valid = get_option( 'betterdocs_chatbot_software__license_status' ) === 'valid';
		$chatbot_enabled = betterdocs()->settings->get( 'enable_ai_chatbot', false );

		// AI Search Suggestions are enabled if all conditions are met
		return $chatbot_active && $chatbot_license_valid && $chatbot_enabled;
	}

	/**
	 * Check if tags are enabled and post has tags
	 *
	 * @return bool
	 */
	public function is_tag_enabled() {
		global $post;
		$product_terms = wp_get_object_terms( $post->ID, 'doc_tag' );
		$enable_tags = betterdocs()->settings->get( 'enable_tags', false );
		return ! empty( $product_terms ) && $enable_tags;
	}

	/**
	 * Check if AI Search Suggestions are enabled
	 *
	 * @return bool
	 */
	public function is_ai_search_suggestions_enabled() {
		$ai_search_suggestions_active = is_plugin_active( 'betterdocs-ai-search-suggestions/betterdocs-ai-search-suggestions.php' );
		$ai_search_suggestions_license_valid = get_option( 'betterdocs_ai_search_suggestions_software__license_status' ) === 'valid';
		$ai_search_suggestions_enabled = betterdocs()->settings->get( 'enable_ai_powered_search', false );

		return $ai_search_suggestions_active && $ai_search_suggestions_license_valid && $ai_search_suggestions_enabled;
	}

	/**
	 * Get the maximum order value from the 'doc_category_order' term meta
	 *
	 * @return int
	 */
	public static function get_max_doc_category_order_from_term_meta() {
		global $wpdb;
		$sql    = $wpdb->prepare( "SELECT MAX(CAST(meta_value AS UNSIGNED)) AS max FROM {$wpdb->prefix}termmeta WHERE meta_key = %s ", 'doc_category_order');
		$result = $wpdb->get_var($sql);
		return $result;
	}
}
