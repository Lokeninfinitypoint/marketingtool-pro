<?php
/**
 * General ACF functions and helpers.
 *
 * @package ALM_ACF
 */

/**
 * Access ACF fields for Repeater and Flexible Content field types.
 *
 * @param string $type              The type of data to return (query/total).
 * @param string $parent_field_name The ACF parent field name.
 * @param string $field_name        The ACF field name.
 * @param string $id                The post ID.
 * @param mixed  $options           Various config options.
 * @param int    $row_index         The row index to access data.
 * @return mixed                    Total posts count or an array of data containing repeater rows.
 * @since 1.3.0
 */
function alm_acf_loop_repeater_rows( $type = 'query', $parent_field_name = '', $field_name = '', $id = '', $options = '', $row_index = 0 ) {
	if ( ! $field_name || ! $id ) {
		return ''; // Bail early if empty.
	}
	$content = '';
	$total   = 0;

	if ( empty( $parent_field_name ) ) {
		// Standard Field.
		$total = count( get_field( $field_name, $id ) );
		if ( $type === 'query' ) {
			$content = alm_acf_get_repeater_fields( $field_name, $id, $options, $total );
		}
	} elseif ( $parent_field_name && $row_index > 0 ) {
		// Row index: Access sub fields by row index.
		if ( have_rows( $parent_field_name, $id ) ) :
			while ( have_rows( $parent_field_name, $id ) ) :
				the_row();
				if ( get_row_index() === $row_index && get_sub_field( $field_name, $id ) ) {
					$total = count( get_sub_field( $field_name, $id ) );
					if ( $type === 'query' ) {
						$content = alm_acf_get_repeater_fields( $field_name, $id, $options, $total, $parent_field_name, $row_index );
					}
				}
			endwhile;
		endif;
	} else {
		// Parent field: Access sub field data by parent field name.
		$parent       = explode( ':', $parent_field_name ); // Split into array.
		$parent_count = count( $parent );

		// Loop sub fields to get at the field.
		if ( $parent_count == 1 ) { // phpcs:ignore
			while ( have_rows( $parent[0], $id ) ) :
				the_row();
				if ( get_sub_field( $field_name, $id ) ) {
					$total = count( get_sub_field( $field_name, $id ) );
					if ( $type === 'query' ) {
						$content = alm_acf_get_repeater_fields( $field_name, $id, $options, $total );
					}
				}
			endwhile;
		}
		if ( $parent_count == 2 ) { // phpcs:ignore
			while ( have_rows( $parent[0], $id ) ) :
				the_row();
				while ( have_rows( $parent[1], $id ) ) :
					the_row();
					$total = count( get_sub_field( $field_name, $id ) );
					if ( $type === 'query' ) {
						$content = alm_acf_get_repeater_fields( $field_name, $id, $options, $total );
					}
				endwhile;
			endwhile;
		}
		if ( $parent_count == 3 ) { // phpcs:ignore
			while ( have_rows( $parent[0], $id ) ) :
				the_row();
				while ( have_rows( $parent[1], $id ) ) :
					the_row();
					while ( have_rows( $parent[2], $id ) ) :
						the_row();
						$total = count( get_sub_field( $field_name, $id ) );
						if ( $type === 'query' ) {
							$content = alm_acf_get_repeater_fields( $field_name, $id, $options, $total );
						}
					endwhile;
				endwhile;
			endwhile;
		}
	}

	// If count, return the count.
	return $type === 'count' ? $total : $content;
}

/**
 * Get the fields for Repeater and Flexible Content fields
 *
 * @param string $field_name        The ACF field name.
 * @param string $id                The post ID.
 * @param mixed  $options           Optional options to pass.
 * @param number $total             The total posts.
 * @param string $parent_field_name The ACF parent field name.
 * @param int    $row_index         The row index to access data.
 * @since 1.3.0
 */
function alm_acf_get_repeater_fields( $field_name, $id, $options, $total = 0, $parent_field_name = '', $row_index = 0 ) {
	if ( ! $field_name || ! $id ) {
		return ''; // Bail early if empty.
	}

	// Set initial variables.
	$content   = '';
	$data      = '';
	$postcount = 0;
	$row_count = 0;
	$preloaded = isset( $options['is_preloaded'] ) && $options['is_preloaded'];

	if ( ! have_rows( $field_name, $id ) ) {
		return '';
	}

	if ( $preloaded ) {
		ob_start();
		while ( have_rows( $field_name, $id ) ) :
			the_row();
			// Start displaying rows after the offset.
			if ( $row_count >= $options['offset'] ) {
				if ( $row_count >= $options['max_pages'] ) {
					// Exit when rows exceeds max pages.
					break;
				}

				// Set ALM Variables.
				$alm_found_posts = $total;
				$alm_page        = 1;
				$alm_item        = $row_count + 1;
				$alm_current     = $alm_item;

				if ( $options['theme_repeater'] !== 'null' && has_action( 'alm_get_theme_repeater' ) ) {
					// Theme Repeater.
					do_action( 'alm_get_theme_repeater', $options['theme_repeater'], $alm_found_posts, $alm_page, $alm_item, $alm_current );
				} else {
					// Repeater.
					$type = alm_get_repeater_type( $options['repeater'] );
					include alm_get_current_repeater( $options['repeater'], $type );
				}
			}
			++$row_count;
		endwhile;
		return ob_get_clean();
	} else {
		// Standard.
		$per_page    = ( $options['posts_per_page'] * $options['page'] ) + 1;
		$start       = ( $options['posts_per_page'] * $options['page'] ) + $options['offset'];
		$end         = $start + $options['posts_per_page'];
		$page        = isset( $options['page'] ) ? $options['page'] : 1;
		$preloaded   = isset( $options['preloaded'] ) ? $options['preloaded'] : 'false';
		$page        = $preloaded === 'true' ? $page + 1 : $page; // If preloaded, add 1 to $page.
		$count       = 0;
		$row_counter = 0;

		ob_start();
		while ( have_rows( $field_name, $id ) ) :
			the_row();

			// Only display rows between the values.
			if ( $row_counter < $options['posts_per_page'] && $count >= $start ) {
				// Increase row counter.
				++$row_counter;

				// Set ALM Variables.
				$alm_found_posts = $total;
				$alm_page        = $page + 1;
				$alm_item        = $count + 1;
				$alm_current     = $row_counter + 1;
				if ( $options['theme_repeater'] !== 'null' && has_action( 'alm_get_theme_repeater' ) ) {
					do_action( 'alm_get_theme_repeater', $options['theme_repeater'], $alm_found_posts, $alm_page, $alm_item, $alm_current );
				} else {
					$type = alm_get_repeater_type( $options['repeater'] );
					include alm_get_current_repeater( $options['repeater'], $type );
				}
			}
			++$count;

			if ( $count >= $end ) {
				break; // exit loop.
			}
			endwhile;
		$acf_data = ob_get_clean();

		return [
			'content'    => $acf_data,
			'postcount'  => $row_counter,
			'totalposts' => $total,
		];
	}
}

/**
 * Access ACF fields for the Gallery field type.
 *
 * @param  string $type              The type of data to return (query/total).
 * @param  string $parent_field_name The ACF parent field name.
 * @param  string $field_name        The ACF field name.
 * @param  string $id                The post ID.
 * @param  int    $row_index         The row index to access data.
 * @return mixed                     Total posts count or an content as data.
 * @since 1.3.0
 */
function alm_acf_loop_gallery_rows( $type = 'query', $parent_field_name = '', $field_name = '', $id = '', $row_index = 0 ) {
	if ( ! $field_name || ! $id ) {
		return ''; // Bail early if empty.
	}

	// Set initial variables.
	$content = '';

	if ( empty( $parent_field_name ) ) {
		// Standard Field.
		while ( have_rows( $field_name, $id ) ) :
			the_row();
			$content = get_field( $field_name, $id );
		endwhile;
	} elseif ( $parent_field_name && $row_index > 0 ) {
		// Row index: Access sub fields by row index.
		if ( have_rows( $parent_field_name, $id ) ) :
			while ( have_rows( $parent_field_name, $id ) ) :
				the_row();
				if ( get_row_index() === $row_index ) {
					$content = get_sub_field( $field_name, $id );
				}
			endwhile;
		endif;
	} else {
		// Parent field: Access sub field data by parent field name.
		$parent       = explode( ':', $parent_field_name ); // Split into array.
		$parent_count = count( $parent );

		// Loop sub fields to access the field data.
		if ( $parent_count == 1 ) { // phpcs:ignore
			while ( have_rows( $parent[0], $id ) ) :
				the_row();
				$content = get_sub_field( $field_name, $id );
			endwhile;
		}
		if ( $parent_count == 2 ) { // phpcs:ignore
			while ( have_rows( $parent[0], $id ) ) :
				the_row();
				while ( have_rows( $parent[1], $id ) ) :
					the_row();
					$content = get_sub_field( $field_name, $id );
				endwhile;
			endwhile;
		}
		if ( $parent_count == 3 ) { // phpcs:ignore
			while ( have_rows( $parent[0], $id ) ) :
				the_row();
				while ( have_rows( $parent[1], $id ) ) :
					the_row();
					while ( have_rows( $parent[2], $id ) ) :
						the_row();
						$content = get_sub_field( $field_name, $id );
					endwhile;
				endwhile;
			endwhile;
		}
	}

	// If count, return the count.
	return $type === 'count' ? count( $content ) : $content;
}

/**
 * Access nested ACF fields for the Relationship field type.
 *
 * @param  string $parent_field_name The ACF parent field name.
 * @param  string $field_name        The ACF field name.
 * @param  string $id                The post ID.
 * @return string                    The content as raw HTML.
 * @since 1.3.0
 */
function alm_acf_loop_relationship_rows( $parent_field_name = '', $field_name = '', $id = '' ) {
	if ( ! $field_name || ! $id ) {
		return ''; // Bail early if empty.
	}

	// Initial variables.
	$parent       = explode( ':', $parent_field_name ); // Split into array.
	$parent_count = count( $parent );
	$content      = '';

	// Loop sub fields to get at the field.
	if ( $parent_count == 1 ) { // phpcs:ignore
		while ( have_rows( $parent[0], $id ) ) :
			the_row();
			$content = get_sub_field( $field_name, $id );
		endwhile;
	}
	if ( $parent_count == 2 ) { // phpcs:ignore
		while ( have_rows( $parent[0], $id ) ) :
			the_row();
			while ( have_rows( $parent[1], $id ) ) :
				the_row();
				$content = get_sub_field( $field_name, $id );
			endwhile;
		endwhile;
	}
	if ( $parent_count == 3 ) { // phpcs:ignore
		while ( have_rows( $parent[0], $id ) ) :
			the_row();
			while ( have_rows( $parent[1], $id ) ) :
				the_row();
				while ( have_rows( $parent[2], $id ) ) :
					the_row();
					$content = get_sub_field( $field_name, $id );
				endwhile;
			endwhile;
		endwhile;
	}
	return $content;
}
