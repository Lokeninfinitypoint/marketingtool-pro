<div
	<?php echo $wrapper_attr; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="betterdocs-social-share-heading">
		<?php
		if ( $title ) {
			$title_tag = isset( $title_tag ) ? $title_tag : 'h4';
			echo wp_sprintf( '<%1$s class="betterdocs-social-share-title-tag">%2$s</%1$s>', esc_attr( $title_tag ), esc_html( $title ) );
		}
		?>
	</div>

	<ul class="betterdocs-social-share-links">
		<?php
		if ( ! empty( $links ) ) {
			foreach ( $links as $key => $social ) {
				echo wp_sprintf(
					'<li><a href="%s" target="_blank"><img src="%s" alt="%s"></a></li>',
					esc_url( $social['link'] ),
					esc_html( $social['icon'] ),
					esc_attr( $social['alt'] )
				);
			}
		}
		?>
	</ul>
</div>
