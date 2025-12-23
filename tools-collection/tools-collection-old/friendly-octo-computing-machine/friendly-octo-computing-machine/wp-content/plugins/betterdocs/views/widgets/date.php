<?php

if ( betterdocs()->settings->get( 'show_last_update_time' ) ) {
	$doc_date_text = isset( $doc_date_text ) ? $doc_date_text : __( 'Updated on', 'betterdocs' );
	echo wp_sprintf(
		'<div %1$s>%2$s %3$s</div>',
		$wrapper_attr, //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$doc_date_text, //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		get_the_modified_date() //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
}
