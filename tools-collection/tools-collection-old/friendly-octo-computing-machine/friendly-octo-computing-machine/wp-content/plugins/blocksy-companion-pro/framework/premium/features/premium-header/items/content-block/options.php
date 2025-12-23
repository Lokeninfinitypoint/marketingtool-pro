<?php

$options = [
	blocksy_rand_md5() => [
		'title' => __('General', 'blocksy-companion'),
		'type' => 'tab',
		'options' => [
			empty(blc_get_content_blocks())
				? [

					blocksy_rand_md5() => [
						'type' => 'html',
						'label' => __('Select Content Block', 'blocksy-companion'),
						'value' => '',
						'design' => 'block',
						'html' => '<a href="' . admin_url('/edit.php?post_type=ct_content_block') .'" target="_blank" class="button" style="width: 100%; text-align: center;">' . __('Create a new content Block/Hook', 'blocksy-companion') . '</a>',
					],

				] : [
					'hook_id' => [
						'label' => __('Select Content Block', 'blocksy-companion'),
						'type' => 'ct-select',
						'value' => '',
						'search' => true,
						'defaultToFirstItem' => false,
						'placeholder' => __('None', 'blocksy-companion'),
						'choices' => blocksy_ordered_keys(
							blc_get_content_blocks()
						),
					],
				],
		]
	],

	blocksy_rand_md5() => [
		'title' => __('Design', 'blocksy-companion'),
		'type' => 'tab',
		'options' => [
			'margin' => [
				'label' => __( 'Margin', 'blocksy-companion' ),
				'type' => 'ct-spacing',
				'divider' => 'top',
				'value' => blocksy_spacing_value(),
				'responsive' => true
			],
		],
	],
];
