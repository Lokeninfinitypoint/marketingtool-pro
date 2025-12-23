<?php

namespace WPDeveloper\BetterDocs\Admin\Importer\Parsers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WordPress extended RSS file parser implementations
 * Originally made by WordPress part of WordPress/Importer.
 * https://plugins.trac.wordpress.org/browser/wordpress-importer/trunk/parsers/class-wxr-parser-regex.php
 *
 * What was done (by Elementor):
 * Reformat of the code.
 * Changed text domain.
 * Changed methods visibility.
 */

/**
 * WXR Parser that uses regular expressions. Fallback for installs without an XML parser.
 */
class CSV_Parser {

	/**
	 * Sort function for sorting CSV file data to keep Term and Author on top.
	 *
	 * @param array $a The first element to compare.
	 * @param array $b The second element to compare.
	 *
	 * @return int Returns an integer less than, equal to, or greater than zero if the first
	 *             argument is considered to be respectively less than, equal to, or greater
	 *             than the second.
	 */
	public function csvSort( $a, $b ) {
		$order = [ 'Term', 'Author', 'Docs' ];

		$keyA = array_search( $a[0], $order );
		$keyB = array_search( $b[0], $order );

		return $keyA - $keyB;
	}

	public function parse( $file ) {
		$data = [
			'terms'   => [],
			'posts'   => [],
			'authors' => []
		];

		$csv_data = [];

		// Read and normalize file content
		$fileContent = file_get_contents( $file );
		if ( $fileContent === false ) {
			return $data; // Return empty data if file reading fails
		}

		$fileContent = str_replace( [ "\r\n", "\r" ], "\n", $fileContent );

		$handle = fopen( 'php://temp', 'r+' );
		fwrite( $handle, $fileContent );
		rewind( $handle );

		if ( $handle !== false ) {
			while ( ( $row = fgetcsv( $handle, 1000, ',' ) ) !== false ) {
				$csv_data[] = $row;
			}
			fclose( $handle );
		}

		$headers = array_shift( $csv_data );

		// Process specific headers for 'Docs Title'
		if ( $headers[0] == 'Docs Title' ) {
			$replacementMap = [ 'Docs Slug' => 'post_name' ];

			$headers = array_map(
				function ( $item ) use ( $replacementMap ) {
					return $replacementMap[ $item ] ?? $item;
				},
				$headers
			);

			$data['type'] = 'sample/csv';

			foreach ( $csv_data as $row ) {
				$row             = array_pad( $row, count( $headers ), '' );
				$data['posts'][] = array_combine( $headers, $row );
			}

			return $data;
		}

		usort( $csv_data, [ $this, 'csvSort' ] );

		foreach ( $csv_data as $row ) {
			$type = $row[0];

			if ( $type === 'glossaries' ) {
				$term_headers = array_slice( $headers, 0, 7 );
				$term_row     = array_slice( $row, 0, 7 );
				$term_row     = array_pad( $term_row, count( $term_headers ), '' );

				if ( count( $term_headers ) !== count( $term_row ) ) {
					return $data;
				}

				$term_data = array_combine( $term_headers, $term_row );

				$term_args = [
					'term_taxonomy' => $term_data['Taxonomy'],
					'term_id'       => $term_data['Term ID'],
					'term_name'     => $term_data['Term name'],
					'slug'          => $term_data['Term slug'],
					'term_group'    => $term_data['Term group']
				];

				$term_args['termmeta'][] = [
					'key'   => 'glossary_term_description',
					'value' => $term_data['Term description']
				];

				$data['terms'][] = $term_args;
			} elseif ( $type === 'Term' ) {
				$term_headers = array_slice( $headers, 28, 11 );
				$term_row     = array_slice( $row, 28, 11 );
				$term_row     = array_pad( $term_row, count( $term_headers ), '' );

				$term_data = array_combine( $term_headers, $term_row );

                $taxonomy  = $term_data['Taxonomy'];
                $term_args = [
                    'term_id'       => sanitize_text_field( $term_data['Term ID'] ),
                    'term_taxonomy' => $taxonomy,
                    'slug'          => sanitize_text_field( $term_data['Term slug'] ),
                    'term_parent'   => sanitize_text_field( $term_data['Term parent'] ),
                    'term_name'     => sanitize_text_field( $term_data['Term name'] ),
                    'description'   => sanitize_text_field( $term_data['Term description'] ),
                    'term_group'    => sanitize_text_field( $term_data['Term group'] ),
                    'termmeta'      => []
                ];

                if ( $taxonomy === 'doc_category' ) {
                    if ( ! empty( $term_data['Assigned Docs'] ) ) {
                        $term_args['termmeta'][] = [
                            'key'   => '_docs_order',
                            'value' => sanitize_text_field( $term_data['Assigned Docs'] )
                        ];
                    }

                    if ( ! empty( $term_data['Assigned KBs'] ) ) {
                        $doc_category_knowledge_base = explode( ",", sanitize_text_field( $term_data['Assigned KBs'] ) );
                        $term_args['termmeta'][]     = [
                            'key'   => 'doc_category_knowledge_base',
                            'value' => rest_sanitize_array( $doc_category_knowledge_base )
                        ];
                    }

                    if ( ! empty( $term_data['Doc Category order'] ) ) {
                        $term_args['termmeta'][] = [
                            'key'   => 'doc_category_order',
                            'value' => sanitize_text_field( $term_data['Doc Category order'] )
                        ];
                    }
                } else if ( $taxonomy === 'knowledge_base' && ! empty( $term_data['KB order'] ) ) {
                    $term_args['termmeta'][] = [
                        'key'   => 'kb_order',
                        'value' => $term_data['KB order']
                    ];
                }

				$data['terms'][] = $term_args;
			} elseif ( $type === 'Author' ) {
				$author_headers = array_slice( $headers, 22, 6 );
				$author_row     = array_slice( $row, 22, 6 );
				$author_row     = array_pad( $author_row, count( $author_headers ), '' );

				$author_data = array_combine( $author_headers, $author_row );

                $data['authors'][$author_data['Author login']] = [
                    'author_id'           => sanitize_text_field( $author_data['Author id'] ),
                    'author_login'        => sanitize_text_field( $author_data['Author login'] ),
                    'author_email'        => sanitize_text_field( $author_data['Author email'] ),
                    'author_display_name' => sanitize_text_field( $author_data['Author display name'] ),
                    'author_first_name'   => sanitize_text_field( $author_data['Author first name'] ),
                    'author_last_name'    => sanitize_text_field( $author_data['Author last name'] )
                ];
            } else if ( $type === 'Docs' ) {
                $post_headers = array_slice( $headers, 1, 21 );
                $post_row     = array_slice( $row, 1, 21 );
                $post_row     = array_pad( $post_row, count( $post_headers ), '' );

				$post_data = array_combine( $post_headers, $post_row );

                $post_args = [
                    'post_id'           => sanitize_text_field( $post_data['Docs ID'] ) ?? '',
                    'post_type'         => 'docs',
                    'post_author'       => sanitize_text_field( $post_data['Docs author'] ) ?? '',
                    'post_content'      => sanitize_text_field( $post_data['Docs content'] ) ?? '',
                    'post_title'        => sanitize_text_field( $post_data['Docs title'] ) ?? '',
                    'post_name'         => sanitize_text_field( $post_data['Docs slug'] ) ?? '',
                    'post_excerpt'      => sanitize_text_field( $post_data['Docs excerpt'] ) ?? '',
                    'status'            => sanitize_text_field( $post_data['Docs status'] ) ?? 'publish',
                    'post_password'     => sanitize_text_field( $post_data['Docs password'] ) ?? '',
                    'post_parent'       => sanitize_text_field( $post_data['Docs parent'] ) ?? '',
                    'menu_order'        => sanitize_text_field( $post_data['Docs menu order'] ) ?? '',
                    'post_date'         => sanitize_text_field( $post_data['Docs date'] ) ?? '',
                    'post_date_gmt'     => sanitize_text_field( $post_data['Docs date gmt'] ) ?? '',
                    'post_modified'     => sanitize_text_field( $post_data['Docs modified date'] ) ?? '',
                    'post_modified_gmt' => sanitize_text_field( $post_data['Docs modified date gmt'] ) ?? '',
                    'terms'             => [],
                    'postmeta'          => []
                ];

                if ( isset( $post_data['Doc Categories'] ) && $data['terms'] ) {
                    $post_args['terms'] = array_merge(
                        $this->searchTermsByIds( $data['terms'], sanitize_text_field( $post_data['Doc Categories'] ) ),
                        $this->searchTermsByIds( $data['terms'], sanitize_text_field( $post_data['Doc Tags'] ) ),
                        $this->searchTermsByIds( $data['terms'], sanitize_text_field( $post_data['Knowledge Bases'] ) )
                    );
                }

				$data['posts'][] = $post_args;

                if ( ! empty( $post_data['Docs attachement url'] ) ) {
                    $attachment_args = [
                        'post_type'      => 'attachment',
                        'post_author'    => sanitize_text_field( $post_data['Docs author'] ) ?? '',
                        'post_id'        => sanitize_text_field( $post_data['Docs attachement ID'] ) ?? '',
                        'status'         => 'inherit',
                        'post_content'   => '',
                        'post_excerpt'   => '',
                        'guid'           => '',
                        'post_title'     => pathinfo( sanitize_text_field( $post_data['Docs attachement url'] ), PATHINFO_FILENAME ),
                        'post_name'      => pathinfo( sanitize_text_field( $post_data['Docs attachement url'] ), PATHINFO_FILENAME ),
                        'post_parent'    => sanitize_text_field( $post_data['Docs ID'] ) ?? '',
                        'attachment_url' => sanitize_text_field( $post_data['Docs attachement url'] )
                    ];

					$data['posts'][] = $attachment_args;
				}
			}
		}

		return $data;
	}

	public function searchTermsByIds( $terms, $termIds ) {
		// Convert the comma-separated term IDs to an array
		$termIdsArray = explode( ',', $termIds );

		// Initialize the result array
		$result = [];

		// Iterate through each term_id in the array
		foreach ( $termIdsArray as $termId ) {
			// Find the corresponding term in the terms array
			$foundTerm = array_filter(
				$terms,
				function ( $term ) use ( $termId ) {
					return $term['term_id'] == $termId;
				}
			);

			// If the term is found, add it to the result array
			if ( ! empty( $foundTerm ) ) {
				$foundTerm = reset( $foundTerm );
				$result[]  = [
					'name'   => $foundTerm['term_name'],
					'slug'   => $foundTerm['slug'],
					'domain' => $foundTerm['term_taxonomy']
				];
			}
		}

		return $result;
	}
}
