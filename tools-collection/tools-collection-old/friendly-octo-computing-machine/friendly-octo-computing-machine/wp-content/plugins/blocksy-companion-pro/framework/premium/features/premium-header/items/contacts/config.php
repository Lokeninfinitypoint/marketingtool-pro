<?php

$config = [
	'name' => __('Contacts', 'blocksy-companion'),
	'typography_keys' => ['contacts_font'],
	'clone' => 3,

	'selective_refresh' => [
		'contact_items',
		'link_icons'
	],

	'translation_keys' => [
		[
			'key' => 'contact_items',
			'all_layers' => [
				'title',
				'content',
				'link'
			]
		]
	]
];
