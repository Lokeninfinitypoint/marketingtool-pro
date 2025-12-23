<?php

$options = [

    blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [
			'any' => [
				'woo_has_new_custom_badge/archive' => true,
				'woo_has_new_custom_badge/single' => true,
			]
		],
        'options' => [
            'newBadgeColor' => [
                'label' => __( 'New Badge', 'blocksy-companion' ),
                'type'  => 'ct-color-picker',
                'design' => 'inline',
                'setting' => [ 'transport' => 'postMessage' ],
        
                'value' => [
                    'text' => [
                        'color' => '#ffffff',
                    ],
        
                    'background' => [
                        'color' => '#35a236',
                    ],
                ],
        
                'pickers' => [
                    [
                        'title' => __( 'Text', 'blocksy-companion' ),
                        'id' => 'text',
                    ],
        
                    [
                        'title' => __( 'Background', 'blocksy-companion' ),
                        'id' => 'background',
                    ],
                ],
            ],
        ]
    ],

    blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [
			'any' => [
				'woo_has_featured_custom_badge/archive' => true,
				'woo_has_featured_custom_badge/single' => true,
			]
		],
        'options' => [
            'featuredBadgeColor' => [
                'label' => __( 'Featured Badge', 'blocksy-companion' ),
                'type'  => 'ct-color-picker',
                'design' => 'inline',
                'setting' => [ 'transport' => 'postMessage' ],
        
                'value' => [
                    'text' => [
                        'color' => '#ffffff',
                    ],
        
                    'background' => [
                        'color' => '#de283f',
                    ],
                ],
        
                'pickers' => [
                    [
                        'title' => __( 'Text', 'blocksy-companion' ),
                        'id' => 'text',
                    ],
        
                    [
                        'title' => __( 'Background', 'blocksy-companion' ),
                        'id' => 'background',
                    ],
                ],
            ],
        ]
    ]
];