<?php

$options = [
	'conditions' => [
		'label' => __('Display Conditions', 'blocksy-companion'),
		'type' => 'blocksy-display-condition',
		'filter' => 'product_tabs',
		'sectionAttr' => [ 'class' => 'ct-content-blocks-conditions' ],
		'display' => 'modal',
		'modalTitle' => __('Product Tab Display Conditions', 'blocksy-companion'),
		'modalDescription' => __('Choose where you want this product tab to be displayed.', 'blocksy-companion'),
		'value' => [
			[
				'type' => 'include',
				'rule' => 'singulars',
				'payload' => []
			]
		],

		'value' => [],
		'design' => 'block',
		'divider' => 'bottom:full',
	],

	'custom_tab_order' => [
		'type' => 'ct-number',
			'label' => __('Tab Order', 'blocksy-companion'),
			'design' => 'inline',
			'min' => 0,
			'max' => 999,
			'value' => 40,
			'desc' => __('Default tabs order: Description - 10, Additional Information - 20, Reviews - 30.', 'blocksy-companion'),
	],
];
