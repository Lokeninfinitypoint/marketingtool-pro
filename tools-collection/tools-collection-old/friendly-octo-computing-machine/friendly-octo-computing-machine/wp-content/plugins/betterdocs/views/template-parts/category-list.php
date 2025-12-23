<?php
use WPDeveloper\BetterDocs\Utils\Helper;

$posts = betterdocs()->query->get_posts( $query_args, true );

if ( ! $posts->have_posts() ) {
	wp_reset_postdata();
}

	$_page_id = null;

if ( is_single() ) {
	$_page_id = get_the_ID();
}

	// if there have list icon url from settings or customizer or shortcodes attribites format it to $list_icon_name
	$settings_list_icon = betterdocs()->settings->get( 'docs_list_icon' );

	// Ensure $list_icon_name is always initialized as an array to prevent TypeError
	if ( ! isset( $list_icon_name ) || ! is_array( $list_icon_name ) ) {
		$list_icon_name = [];
	}

	#for elementor if icon is not selected from settings, and icon attributes are empty, then look for settings, if settings is empty show default icon, else show from settings. If svg from list is selected, then show svg, else show from settings, if settings is empty then show default. Not applicable for sidebar layout-2 elementor
if ( isset( $layout_type ) && $layout_type == 'widget' ) {
	if ( isset( $sidebar_layout ) && $sidebar_layout == 'layout-2' ) { #done to avoid warning for elementor sidebar layout-2
		$list_icon_name = [];
	}

	// Ensure $list_icon_name is an array before using array_key_exists
	if ( ! is_array( $list_icon_name ) ) {
		$list_icon_name = [];
	}

	$list_icon_name = ( array_key_exists( 'value', $list_icon_name ) && array_key_exists( 'library', $list_icon_name ) || array_key_exists( 'value', $list_icon_name ) ) ? ( empty( $list_icon_name['value'] ) && empty( $list_icon_name['library'] ) || empty( $list_icon_name['value'] ) ? ( isset( $settings_list_icon['url'] ) ? [ 'value' => [ 'url' => $settings_list_icon['url'] ] ] : [] ) : $list_icon_name ) : ( isset( $list_icon_name['url'] ) ? [ 'value' => [ 'url' => $list_icon_name['url'] ] ] : ( isset( $settings_list_icon['url'] ) ? $settings_list_icon['url'] : [] ) );
}

	// #for blocks if $list_icon_name is empty, but for category grid block
if ( isset( $layout_type ) && $layout_type == 'block' ) {
	if ( empty( $list_icon_name ) && isset( $widget_type ) && $widget_type == 'category-grid' ) {
		$list_icon_name = [
			'value'
		];
	}
}


	#for blocks if $sidebar_layout is passed, then remove the list icon for layout-4
if ( isset( $layout_type ) && $layout_type == 'block' ) {
	if ( isset( $sidebar_layout ) && $sidebar_layout == 'layout-4' ) {
		$list_icon_name = [
			'value' => [
				'url' => 'list'
			]
		];
	}
}

if ( isset( $layout_type ) && ! empty( $layout_type ) && $layout_type == 'template' && isset( $list_icon_url ) && $list_icon_url ) {
	if ( isset( $list_icon_url ) && $list_icon_url ) {
		$list_icon_name = [
			'value' => [
				'url' => $list_icon_url
			]
		];
	}
}
?>

<ul class="betterdocs-articles-list">
	<?php
	if ( $query_args['posts_per_page'] === '' ) {
		$query_args['posts_per_page'] = get_option( 'posts_per_page' );
	}

	if ( $query_args['posts_per_page'] == -1 || $query_args['posts_per_page'] > 0 ) {
		$pos  = 'left';
		$icon = 'list';
		$show_icon = true;

		// Check if list icon should be shown
		if ( isset( $show_list_icon ) && $show_list_icon === false ) {
			$show_icon = false;
		}

		if ( ! empty( $list_icon_position ) ) {
			if ( $list_icon_position == 'right' ) {
				$pos = 'right';
			}
		}
		if ( ! empty( $list_icon_name ) ) {
			$icon = $list_icon_name;
		}

		while ( $posts->have_posts() ) :
			$posts->the_post();
			$_link_attributes = [
				'href' => esc_url( get_the_permalink() )
			];

			if ( $_page_id === get_the_ID() && Helper::get_tax() != 'doc_category' ) {
				$_link_attributes['class'] = 'active';
			}

			$_link_attributes = betterdocs()->template_helper->get_html_attributes( $_link_attributes );

			echo wp_sprintf(
				'<li>%4$s<a %1$s><span>%2$s</span> %3$s</a></li>',
				$_link_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				betterdocs()->template_helper->kses( get_the_title() ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				( $show_icon && $pos == 'right' ) ? betterdocs()->template_helper->icon( $icon ) : '', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				( $show_icon && $pos == 'left' ) ? betterdocs()->template_helper->icon( $icon ) : '' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		endwhile;

		wp_reset_postdata();
	}
		/**
		 * Nested Sub Categories
		 */
	if ( (bool) $nested_subcategory && $term instanceof \WP_Term ) {
		$_params = get_defined_vars();
		$_params = isset( $_params['params'] ) ? $_params['params'] : [];
		$_params = wp_parse_args(
			[
				'term_id'       => $term->term_id,
				'list_icon_url' => $list_icon_url
			],
			$_params
		);
		if ( $layout_type == 'widget' || $layout_type == 'block' ) {
			$_params['list_icon_name'] = $list_icon_name;
		}
		$view_object->get( 'template-parts/nested-categories', $_params );
	}
	?>
</ul>
