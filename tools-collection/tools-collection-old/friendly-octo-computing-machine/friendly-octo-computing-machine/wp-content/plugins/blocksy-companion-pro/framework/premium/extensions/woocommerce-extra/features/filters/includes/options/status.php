<?php

namespace Blocksy\Extensions\WoocommerceExtra;

$statuses = StatusFilter::get_status_options();

$status_values = [];
$status_options = [];

foreach ($statuses as $key => $status) {
    $status_values[] = [
        'id' => $key,
        'label' => $status,
        'enabled' => true,
    ];

    $status_options[$key] = [
        'label' => $status,
        'options' => [
            'label' => [
                'type' => 'text',
                'label' => __('Label', 'blocksy-companion'),
                'value' => $status,
            ],
        ]
    ];
}

$options = [
    'statuses' => [
		'label' => __('Statuses', 'blocksy-companion'),
		'type' => 'ct-layers',
		'divider' => 'top:full',
		'manageable' => true,
		'value' => $status_values,
		'settings' => $status_options
	],
];