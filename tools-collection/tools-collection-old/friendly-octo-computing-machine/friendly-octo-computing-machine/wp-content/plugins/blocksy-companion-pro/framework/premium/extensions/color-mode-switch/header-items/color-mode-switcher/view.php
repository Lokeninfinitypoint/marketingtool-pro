<?php

if (!isset($device)) {
	$device = 'desktop';
}

$class = 'ct-color-switch ct-toggle';

$item_visibility = blocksy_default_akg('header_color_switch_visibility', $atts, [
	'tablet' => true,
	'mobile' => true,
]);

$class .= ' ' . blocksy_visibility_classes($item_visibility);

$icon = apply_filters('blocksy:header:color-switch:icons', [
	'type-1' => '<svg aria-hidden="true" width="15" height="15" fill="currentColor" class="ct-icon ct-switch-type-expand" viewBox="0 0 32 32"><clipPath id="ct-switch-type-expand__cutout-' . $device . '"><path d="M0-11h25a1 1 0 0017 13v30H0Z" /></clipPath><g clip-path="url(#ct-switch-type-expand__cutout-' . $device . ')"><circle cx="16" cy="16" r="8.4" /><path d="M18.3 3.2c0 1.3-1 2.3-2.3 2.3s-2.3-1-2.3-2.3S14.7.9 16 .9s2.3 1 2.3 2.3zm-4.6 25.6c0-1.3 1-2.3 2.3-2.3s2.3 1 2.3 2.3-1 2.3-2.3 2.3-2.3-1-2.3-2.3zm15.1-10.5c-1.3 0-2.3-1-2.3-2.3s1-2.3 2.3-2.3 2.3 1 2.3 2.3-1 2.3-2.3 2.3zM3.2 13.7c1.3 0 2.3 1 2.3 2.3s-1 2.3-2.3 2.3S.9 17.3.9 16s1-2.3 2.3-2.3zm5.8-7C9 7.9 7.9 9 6.7 9S4.4 8 4.4 6.7s1-2.3 2.3-2.3S9 5.4 9 6.7zm16.3 21c-1.3 0-2.3-1-2.3-2.3s1-2.3 2.3-2.3 2.3 1 2.3 2.3-1 2.3-2.3 2.3zm2.4-21c0 1.3-1 2.3-2.3 2.3S23 7.9 23 6.7s1-2.3 2.3-2.3 2.4 1 2.4 2.3zM6.7 23C8 23 9 24 9 25.3s-1 2.3-2.3 2.3-2.3-1-2.3-2.3 1-2.3 2.3-2.3z" /></g></svg>',

	'type-2' => '<svg aria-hidden="true" class="ct-icon ct-switch-type-within" height="15" width="15" viewBox="0 0 32 32" fill="currentColor"><clipPath id="ct-switch-type-within__clip-' . $device . '"><path d="M0 0h32v32h-32ZM6 16A1 1 0 0026 16 1 1 0 006 16"/></clipPath><g clip-path="url(#ct-switch-type-within__clip-' . $device . ')"><path d="M30.7 21.3 27.1 16l3.7-5.3c.4-.5.1-1.3-.6-1.4l-6.3-1.1-1.1-6.3c-.1-.6-.8-.9-1.4-.6L16 5l-5.4-3.7c-.5-.4-1.3-.1-1.4.6l-1 6.3-6.4 1.1c-.6.1-.9.9-.6 1.3L4.9 16l-3.7 5.3c-.4.5-.1 1.3.6 1.4l6.3 1.1 1.1 6.3c.1.6.8.9 1.4.6l5.3-3.7 5.3 3.7c.5.4 1.3.1 1.4-.6l1.1-6.3 6.3-1.1c.8-.1 1.1-.8.7-1.4zM16 25.1c-5.1 0-9.1-4.1-9.1-9.1 0-5.1 4.1-9.1 9.1-9.1s9.1 4.1 9.1 9.1c0 5.1-4 9.1-9.1 9.1z"/></g><path class="ct-switch-type-within__circle" d="M16 7.7c-4.6 0-8.2 3.7-8.2 8.2s3.6 8.4 8.2 8.4 8.2-3.7 8.2-8.2-3.6-8.4-8.2-8.4zm0 14.4c-3.4 0-6.1-2.9-6.1-6.2s2.7-6.1 6.1-6.1c3.4 0 6.1 2.9 6.1 6.2s-2.7 6.1-6.1 6.1z"/><path class="ct-switch-type-within__inner" d="M16 9.5c-3.6 0-6.4 2.9-6.4 6.4s2.8 6.5 6.4 6.5 6.4-2.9 6.4-6.4-2.8-6.5-6.4-6.5z"/></svg>',

	'type-3' => '<svg aria-hidden="true" width="15" height="15" class="ct-icon ct-switch-type-dark-inner" fill="currentColor" viewBox="0 0 32 32"><path d="M16 9c3.9 0 7 3.1 7 7s-3.1 7-7 7" /><path d="M16 .5C7.4.5.5 7.4.5 16S7.4 31.5 16 31.5 31.5 24.6 31.5 16 24.6.5 16 .5zm0 28.1V23c-3.9 0-7-3.1-7-7s3.1-7 7-7V3.4C23 3.4 28.6 9 28.6 16S23 28.6 16 28.6z" /></svg>',
]);

$icon_type = blocksy_default_akg('color_switch_icon_type', $atts, 'type-1');

if (empty($icon_type)) {
	$icon_type = 'type-1';
}

$icon = $icon[$icon_type];


$label_class = 'ct-label';

$label_class .= ' ' . blocksy_visibility_classes(
	blocksy_akg('color_switch_label_visibility', $atts, [
		'desktop' => false,
		'tablet' => false,
		'mobile' => false,
	])
);


$dark_mode_label = blocksy_expand_responsive_value(
	blocksy_default_akg('dark_mode_label', $atts, __('Dark Mode', 'blocksy-companion'))
)[$device];

$dark_mode_label = blocksy_translate_dynamic(
	$dark_mode_label,
	$panel_type . ':' . $section_id . ':' . $item_id . ':dark_mode_label'
);

$light_mode_label = blocksy_expand_responsive_value(
	blocksy_default_akg('light_mode_label', $atts, __('Light Mode', 'blocksy-companion'))
)[$device];

$light_mode_label = blocksy_translate_dynamic(
	$light_mode_label,
	$panel_type . ':' . $section_id . ':' . $item_id . ':light_mode_label'
);

$color_switch_label_position = blocksy_expand_responsive_value(
	blocksy_akg('color_switch_label_position', $atts, 'left')
);

$color_state = blocksy_akg(
	'color_switch_icon_state',
	$atts,
	'no'
) === 'yes' ? 'reversed' : 'normal';

?>

<button
	class="<?php echo esc_attr($class) ?>"
	data-color-switch="<?php echo esc_attr($color_state) ?>"
	data-label="<?php echo esc_attr($color_switch_label_position[$device]) ?>"
	aria-label="<?php echo esc_attr__('Color mode switch', 'blocksy-companion') ?>"
	<?php blocksy_attr_to_html_e($attr) ?>>

		<span class="<?php echo esc_attr($label_class) ?>" aria-hidden="true">
			<span class="ct-dark-mode-label"><?php echo esc_html($dark_mode_label); ?></span>
			<span class="ct-light-mode-label"><?php echo esc_html($light_mode_label); ?></span>
		</span>

		<?php
			/**
			 * Note to code reviewers: This line doesn't need to be escaped.
			 * The value used here escapes the value properly.
			 * It contains an inline SVG, which is safe.
			 */
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $icon;
		?>



</button>
