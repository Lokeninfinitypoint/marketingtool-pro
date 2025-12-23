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

$prefix                    = apply_filters( 'betterdocs_category_items_counts_prefix', $prefix, get_defined_vars() );
$suffix                    = apply_filters( 'betterdocs_category_items_counts_suffix', $suffix, get_defined_vars() );
$suffix_singular           = apply_filters( 'betterdocs_category_items_counts_suffix_singular', $suffix_singular, get_defined_vars() );
$subcategory_singular_text = isset( $subcategory_text ) ? $subcategory_text : __( 'Sub Category', 'betterdocs' ); // Default singular subcategory text.
$subcategory_plural_text   = isset( $subcategories_text ) ? $subcategories_text : __( 'Sub Categories', 'betterdocs' ); // Default plural subcategory text.
?>

<div data-count="<?php echo esc_attr( $counts ); ?>" class="betterdocs-sub-category-items-counts">
	<?php
	if ( $sub_terms_count > 0 ) {
		echo '<span>';
		if ( $taxonomy === 'knowledge_base' ) {
			/* translators: %s: Number of categories. */
			echo esc_html(
				sprintf(
                // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment, WordPress.WP.I18n.MismatchedPlaceholders
					_n( '%s Category', '%s Categories', $sub_terms_count, 'betterdocs' ),
					number_format_i18n( $sub_terms_count )
				)
			);
		} else {
			/* translators: %1$s: Number of items, %2$s: Singular text, %3$s: Plural text. */
			echo esc_html(
				sprintf(
                // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment, WordPress.WP.I18n.MismatchedPlaceholders
					_n( '%1$s %2$s', '%1$s %3$s', $sub_terms_count, 'betterdocs' ),
					esc_html( $sub_terms_count ),
					esc_html( $subcategory_singular_text ),
					esc_html( $subcategory_plural_text )
				)
			);
		}
		echo '</span> <span>|</span>';
	}
	?>
	<span>
		<?php
		/* translators: %1$s: Number of items, %2$s: Prefix text, %3$s: Singular suffix, %4$s: Plural suffix. */
		echo esc_html(
			sprintf(
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment, WordPress.WP.I18n.MismatchedPlaceholders
				_n( '%2$s %1$s %3$s', '%2$s %1$s %4$s', $counts, 'betterdocs' ),
				esc_html( $counts ),
				esc_html( $prefix ),
				esc_html( $suffix_singular ),
				esc_html( $suffix )
			)
		);
		?>
	</span>
</div>
