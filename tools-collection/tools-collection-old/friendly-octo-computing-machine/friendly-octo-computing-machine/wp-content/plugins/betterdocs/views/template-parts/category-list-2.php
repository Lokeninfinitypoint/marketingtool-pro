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
?>

<ul class="betterdocs-articles-list">
	<?php
	
	if ( $query_args['posts_per_page'] === '' ) {
		$query_args['posts_per_page'] = get_option( 'posts_per_page' );
	}

	if ( $query_args['posts_per_page'] == -1 || $query_args['posts_per_page'] > 0 ) {
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
				'<li><a %1$s>%2$s</a></li>',
				$_link_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				betterdocs()->template_helper->kses( get_the_title() ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		endwhile;

		wp_reset_postdata();
	}
	?>
</ul>
