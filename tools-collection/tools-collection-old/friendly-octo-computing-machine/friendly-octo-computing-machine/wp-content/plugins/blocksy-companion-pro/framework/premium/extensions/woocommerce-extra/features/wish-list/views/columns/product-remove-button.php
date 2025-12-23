<?php

if (! function_exists('blocksy_action_button') || $has_custom_user) {
	return;
}

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo blocksy_action_button([
	'button_html_attributes' => [
		'href' => '#',
		'class' => 'remove',
		'data-product_id' => $single_product_id,
		'title' => __('Remove Product', 'blocksy-companion')
	],
	'icon' => '<svg viewBox="0 0 24 24"><path d="M9.6,0l0,1.2H1.2v2.4h21.6V1.2h-8.4l0-1.2H9.6z M2.8,6l1.8,15.9C4.8,23.1,5.9,24,7.1,24h9.9c1.2,0,2.2-0.9,2.4-2.1L21.2,6H2.8z"></path></svg>'
]);
