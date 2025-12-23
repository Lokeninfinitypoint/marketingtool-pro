<?php
if ( ! $show_count ) {
	return;
}

	$prefix = $suffix = $suffix_singular = '';

if ( is_array( $counts ) ) {
	$prefix          = $counts['prefix'];
	$_count          = $counts['counts'];
	$suffix          = $counts['suffix'];
	$suffix_singular = $counts['suffix_singular'];
	$counts          = $_count;
}

	$prefix          = apply_filters( 'betterdocs_category_items_counts_prefix', $prefix, get_defined_vars() );
	$suffix          = apply_filters( 'betterdocs_category_items_counts_suffix', $suffix, get_defined_vars() );
	$suffix_singular = apply_filters( 'betterdocs_category_items_counts_suffix_singular', $suffix_singular, get_defined_vars() );
?>

<div data-count="<?php echo esc_attr( $counts ); ?>" class="betterdocs-category-items-counts">
	<span>
		<?php
			echo esc_html(
				sprintf(
				/* translators: %s: Number of items. */
                // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment, WordPress.WP.I18n.MismatchedPlaceholders
					_n(
						'%1$s %2$s %3$s',
						'%1$s %2$s %4$s',
						$counts,
						'betterdocs'
					),
					esc_html( $prefix ),
					esc_html( $counts ),
					esc_html( $suffix_singular ),
					esc_html( $suffix )
				)
			);
			?>
	</span>
</div>
