<?php

$config = [
	'enabled' => (
		function_exists('icl_object_id')
		||
		function_exists('pll_the_languages')
		||
		class_exists('TRP_Translate_Press')
		||
		function_exists('weglot_get_current_language')
	),
	'typography_keys' => ['ls_font'],
	'name' => __('Languages', 'blocksy-companion'),

	'selective_refresh' => [
		'ls_type',
		'ls_flag_aspect_ratio',
		'language_type',
		'language_label',
		'hide_current_language',

		// 'top_level_language_label',
	],
];