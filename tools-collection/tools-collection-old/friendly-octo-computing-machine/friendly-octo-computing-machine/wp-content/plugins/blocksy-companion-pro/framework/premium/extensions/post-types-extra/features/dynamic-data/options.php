<?php

$options = [];

$providers = blc_get_ext('post-types-extra')
	->dynamic_data
	->custom_fields_manager
	->get_providers();

foreach ($providers as $single_provider) {
	$field_id = $single_provider->get_provider_id() . '_field';
	$field_title = $single_provider->get_provider_label();

	$options[$field_id . '_' . $key] = [
		'type' => 'ct-layers-mirror',
		'layers' => $prefix . '_' . $key,
		'field' => $field_id,
		'value' => '',
		'inner-options' => [
			'typography' => [
				'type' => 'ct-typography',
				'label' => blc_safe_sprintf(
					// translators: %1$s is the field title, %2$s is "Field INDEX"
					__('%1$s %2$s Font', 'blocksy-companion'),
					$field_title,
					__('Field', 'blocksy-companion') . ' INDEX'
				),
				'divider' => 'top:full',
				'sync' => 'live',
				'value' => blocksy_typography_default_values([]),
			],

			'color' => [
				'label' => blc_safe_sprintf(
					// translators: %1$s is the field title, %2$s is "Field INDEX"
					__('%1$s %2$s Color', 'blocksy-companion'),
					$field_title,
					__('Field', 'blocksy-companion') . ' INDEX'
				),
				'type'  => 'ct-color-picker',
				'design' => 'inline',
				'noColor' => [ 'background' => 'var(--theme-text-color)'],
				'sync' => 'live',
				'value' => [
					'default' => [
						'color' => \Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
					],

					'hover' => [
						'color' => \Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
					],
				],

				'pickers' => [
					[
						'title' => __( 'Initial', 'blocksy-companion' ),
						'id' => 'default',
						'inherit' => 'var(--theme-text-color)'
					],

					[
						'title' => __( 'Hover', 'blocksy-companion' ),
						'id' => 'hover',
						'inherit' => 'var(--theme-link-hover-color)'
					],
				],
			],
		]
	];
}
