<?php

$common_options = blocksy_get_variables_from_file(
	dirname(__FILE__) . '/common.php',
	[
		'common_options' => [],
		'design_options' => []
	],
	[
		'sync_id' => $sync_id
	]
);

$design_options = $common_options['design_options'];

$general_options = [
	[
		$common_options['common_options']
	],

	[
		'ls_items_spacing' => [
			'label' => __( 'Items Spacing', 'blocksy-companion' ),
			'type' => 'ct-slider',
			'min' => 5,
			'max' => 50,
			'value' => 20,
			'responsive' => true,
			'divider' => 'top',
		],

		'hide_current_language' => [
			'label' => __( 'Hide Current Language', 'blocksy-companion' ),
			'type' => 'ct-switch',
			'design' => 'inline',
			'divider' => 'top',
			'disableRevertButton' => true,
			'value' => 'no',
			'sync' => [
				'id' => $sync_id
			]
		],
	]
];
