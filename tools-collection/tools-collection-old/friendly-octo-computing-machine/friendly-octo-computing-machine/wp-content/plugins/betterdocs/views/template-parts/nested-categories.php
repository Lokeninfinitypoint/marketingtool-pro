<?php

use WPDeveloper\BetterDocs\Utils\Helper;

if ( ! $nested_subcategory ) {
	return;
}

$_terms_args = [
	'parent'     => $term_id,
	'hide_empty' => true
];

if ( isset( $terms_exclude ) ) {
	$_terms_args['exclude'] = $terms_exclude;
} elseif ( isset( $exclude ) ) {
	$_terms_args['exclude'] = $exclude;
}

$nested_terms_query = isset( $nested_terms_query ) ? array_merge( $_terms_args, $nested_terms_query ) : $_terms_args;

$_nested_categories = get_terms( betterdocs()->query->terms_query( apply_filters( 'betterdocs_nested_terms_args', $nested_terms_query ) ) );

if ( empty( $_nested_categories ) ) {
	return;
}

// if there have list icon url from customizer or shortcodes attribites format it to $list_icon_name
if ( $layout_type == 'template' && isset( $list_icon_url ) && $list_icon_url ) {
	$list_icon_name = array(
		'value' => array(
			'url' => $list_icon_url
		)
	);
}

$_page_id              = null;
$_category_ids         = [];
$_is_single            = false;
$_is_doc_category      = false;
$_current_doc_category = null;

// Check if list icon should be shown
$_show_list_icon = true;
if ( isset( $show_list_icon ) && $show_list_icon === false ) {
	$_show_list_icon = false;
}

$_icon = $_show_list_icon ? betterdocs()->template_helper->icon( isset( $list_icon_name ) ? $list_icon_name : 'list' ) : '';

if ( is_single() ) {
	$_is_single    = true;
	$_page_id      = get_the_ID();
	$_category_ids = wp_get_post_terms( $_page_id, 'doc_category', [ 'fields' => 'ids' ] );
	if ( ! empty( $_category_ids ) && ! is_wp_error( $_category_ids ) ) {
		$ancestors     = get_ancestors( $_category_ids[0], 'doc_category' );
		$_category_ids = array_merge( $_category_ids, $ancestors );
	}
}

if ( is_tax( 'doc_category' ) ) {
	$_is_doc_category       = true;
	$_current_doc_category  = get_queried_object() != null ? get_queried_object()->term_id : '';
	$parent_id              = Helper::get_the_top_most_parent( $_current_doc_category );
	$_category_ids          = get_term_children( $parent_id, 'doc_category' );
	$current_category_index = array_search( $_current_doc_category, $_category_ids );
	$_category_ids          = ! is_bool( $current_category_index ) ? array_slice( $_category_ids, 0, ( $current_category_index + 1 ), true ) : []; // get the range from start to current
}

$_multiple_kb = isset( $multiple_knowledge_base ) ? $multiple_knowledge_base : false;
$_kb_slug     = isset( $kb_slug ) ? $kb_slug : '';

$_default_nested_docs_query_args = [
	'multiple_kb'    => $_multiple_kb,
	'posts_per_page' => -1
];

$nested_docs_query_args = isset( $nested_docs_query_args ) ?
	array_merge( $_default_nested_docs_query_args, $nested_docs_query_args ) :
	$_default_nested_docs_query_args;

$_nested_docs_args = apply_filters( 'betterdocs_nested_docs_args', $nested_docs_query_args );

foreach ( $_nested_categories as $_nested_category ) :
	$classes = $_is_single && in_array( $_nested_category->term_id, $_category_ids ) || ( $_is_doc_category && in_array( $_nested_category->term_id, $_category_ids ) ) ? 'betterdocs-nested-category-list betterdocs-current-category active' : 'betterdocs-nested-category-list';

	$_counts = betterdocs()->query->get_docs_count(
		$_nested_category,
		$nested_subcategory,
		[
			'multiple_knowledge_base' => $_multiple_kb,
			'kb_slug'                 => $_kb_slug,
		]
	);

	if ( $_counts <= 0 ) {
		continue;
	}

	?>
	<li class="betterdocs-nested-category-wrapper">
		<span class="betterdocs-nested-category-title">
			<?php
			if ( isset( $category_icon ) && $category_icon == 'folder' ) {
				betterdocs()->template_helper->icon( 'folder', true );
				betterdocs()->template_helper->icon( 'folder-open', true );
			} else {
				betterdocs()->template_helper->icon( 'arrow-right', true );
				betterdocs()->template_helper->icon( 'arrow-down', true );
			}
				/**
				 * Icons
				 */

			?>
			<a href="#"><?php echo esc_html( $_nested_category->name ); ?></a>
		</span>
		<ul class="<?php echo esc_attr( $classes ); ?>" style="<?php echo $_is_single && in_array( $_nested_category->term_id, $_category_ids ) || ( $_is_doc_category && in_array( $_nested_category->term_id, $_category_ids ) ) ? 'display:block;' : 'display:none;'; ?>">
			<?php
				$_nested_docs_args['term_id']   = $_nested_category->term_id;
				$_nested_docs_args['term_slug'] = $_nested_category->slug;

				$_docs_query = betterdocs()->query->docs_query_args( $_nested_docs_args );

				$_docs_query = new WP_Query( $_docs_query );

			if ( $_docs_query->have_posts() ) {
				while ( $_docs_query->have_posts() ) :
					$_docs_query->the_post();
					$_attributes = [
						'href' => esc_url( get_the_permalink() )
					];
					if ( $_page_id === get_the_ID() && Helper::get_tax() != 'doc_category' ) {
						$_attributes['class'] = 'active';
					}

					$_link_attributes = betterdocs()->template_helper->get_html_attributes( $_attributes );

					echo wp_sprintf(
						'<li>%s<a %s>%s</a></li>',
						$_icon, //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						$_link_attributes, //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						betterdocs()->template_helper->kses( get_the_title() ) //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					);
				endwhile;
			}

				wp_reset_postdata();

				$_params = get_defined_vars();
				$_params = isset( $_params['params'] ) ? $_params['params'] : [];

				$_params = wp_parse_args(
					[
						'term_id' => $_nested_category->term_id
					],
					$_params
				);

				betterdocs()->views->get( 'template-parts/nested-categories', $_params );
			?>
		</ul>
	</li>
	<?php
endforeach;
