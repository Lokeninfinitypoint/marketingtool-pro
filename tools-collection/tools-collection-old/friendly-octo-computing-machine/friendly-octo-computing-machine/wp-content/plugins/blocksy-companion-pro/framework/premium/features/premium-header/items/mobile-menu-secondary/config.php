<?php

$config = [
	'name' => __('Mobile Menu 2', 'blocksy-companion'),
	'typography_keys' => ['mobileMenuFont'],
	'devices' => ['mobile'],
	'allowed_in' => [
		'desktop' => ['offcanvas'],
	],
	'selective_refresh' => [
		'menu',
		'mobile_menu_type',
		'mobile_menu_interactive'
	]
];

