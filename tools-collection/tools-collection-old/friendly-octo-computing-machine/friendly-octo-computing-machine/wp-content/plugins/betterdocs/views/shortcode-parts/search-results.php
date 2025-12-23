<div class="betterdocs-search-result-wrap">
	<ul class="docs-search-result">
		<?php
		if ( $search_results->have_posts() ) {
			$input_not_found = '';
			while ( $search_results->have_posts() ) :
				$search_results->the_post();
				preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content(), $matches );

				if ( $matches[1] ) {
					$first_img = $matches[1][0];
				} else {
					$first_img = '';
				}

				$terms      = get_the_terms( get_the_ID(), 'doc_category' );
				$terms_name = [];

				if ( $terms && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$terms_name[] = $term->name;
					}
				}

				$all_terms           = join( ', ', $terms_name );
				$icon                = '';
				$search_result_image = betterdocs()->settings->get( 'search_result_image' );

				if ( $search_result_image == 1 && has_post_thumbnail() ) :
					$icon = get_the_post_thumbnail();
					elseif ( $search_result_image == 1 && ! empty( $first_img ) ) :
						$icon = '<img src="' . $first_img . '" alt="">';
					endif;

                    echo '<li>' . $icon . '<a href="' . get_permalink() . '"><span class="betterdocs-search-title">' . betterdocs()->template_helper->kses( get_the_title() ) . '</span><br><span class="betterdocs-search-category">' . $all_terms . '</span></a></li>'; //phpcs:ignore
				endwhile;
		} else {
			$input_not_found = $search_input;
			echo '<li>' . esc_html( betterdocs()->settings->get( 'search_not_found_text' ) ) . '</li>';
		}
		?>
	</ul>
</div>
