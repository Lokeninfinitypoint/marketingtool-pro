<?php

$class = 'ct-language-switcher';

if (!isset($device)) {
	$device = 'desktop';
}

if ($panel_type === 'header') {
	$visibility = blocksy_default_akg('visibility', $atts, [
		'tablet' => true,
		'mobile' => true,
	]);
} else {
	$visibility = blocksy_default_akg('footer_visibility', $atts, [
		'desktop' => true,
		'tablet' => true,
		'mobile' => true,
	]);
}

$class .= ' ' . blocksy_visibility_classes($visibility);

$language_type = blocksy_default_akg(
	'language_type',
	$atts,
	[
		'icon' => true,
		'label' => true,
	]
);

$dropdown_language_type = blocksy_default_akg(
	'dropdown_language_type',
	$atts,
	[
		'icon' => true,
		'label' => true,
	]
);

$language_label = blocksy_default_akg('language_label', $atts, 'long');
$dropdown_language_label = blocksy_default_akg('dropdown_language_label', $atts, 'long');

$language_label_position = blocksy_expand_responsive_value(
	blocksy_default_akg('language_label_position', $atts, 'right')
);

$dropdown_language_label_position = blocksy_expand_responsive_value(
	blocksy_default_akg('dropdown_language_label_position', $atts, 'right')
);

$ls_type = 'inline';

if ($panel_type === 'header') {
	$ls_type = blocksy_default_akg('ls_type', $atts, 'inline');

	if (isset($row_id) && $row_id === 'offcanvas') {
		$ls_type = 'inline';
	}
}

$items_language_type = $language_type;
$items_language_label = $language_label;
$items_language_label_position = $language_label_position[$device];

if ($ls_type === 'dropdown') {
	$items_language_type = $dropdown_language_type;
	$items_language_label_position = $dropdown_language_label_position[$device];
	$items_language_label = $dropdown_language_label;
}

$hide_current_language = blocksy_default_akg('hide_current_language', $atts, 'no') === 'yes';

$has_arrow = blocksy_akg('ls_dropdown_arrow', $atts, 'no') === 'yes';
$hide_missing_language = blocksy_akg('hide_missing_language', $atts, 'no') === 'yes';

$current_plugin = null;

if (function_exists('icl_object_id') && function_exists('icl_disp_language')) {
	$current_plugin = 'wpml';
}

if (function_exists('pll_the_languages')) {
	$current_plugin = 'polylang';
}

if (class_exists('TRP_Translate_Press')) {
	$current_plugin = 'translate-press';
}

if (function_exists('weglot_get_current_language')) {
	$current_plugin = 'weglot';
}

$output = '';

$icon = '<svg class="ct-icon ct-dropdown-icon" width="8" height="8" viewBox="0 0 15 15" aria-hidden="true"><path d="M2.1,3.2l5.4,5.4l5.4-5.4L15,4.3l-7.5,7.5L0,4.3L2.1,3.2z"></path></svg>';

if ($current_plugin) {
	$descriptors = blc_theme_functions()->blocksy_get_variables_from_file(
		dirname(__FILE__) . '/plugins/' . $current_plugin . '.php',
		['descriptors' => []],
		[
			'hide_missing_language' => $hide_missing_language,
			'atts' => $atts,
		]
	);

	$items_html = [];

	foreach ($descriptors['descriptors'] as $key => $descriptor) {
		$content = '';
		$flag_image = '';

		if (
			$ls_type === 'dropdown'
			&&
			$key === 'current'
		) {
			if (
				$descriptor['country_flag_url']
				&&
				$language_type['icon']
			) {
				$flag_image = blocksy_html_tag(
					'img',
					[
						'src' => $descriptor['country_flag_url'],
						'width' => '18',
						'height' => '12',
						// 'alt' => $descriptor['language_code'],
						'alt' => '',
						'aria-hidden' => 'true',
						// 'title' => $descriptor['native_name'],
					]
				);
			}

			if ($language_type['label']) {
				$content .= blocksy_html_tag(
					'span',
					[
						'class' => 'ct-label',
						'aria-hidden' => 'true',
					],
					$language_label === 'long'
						? $descriptor['native_name']
						: $descriptor['short_name']
				);
			}

			$content .= $flag_image;

			if ($has_arrow) {
				$content .= $icon;
			}

			$output .= blocksy_html_tag(
				'div',
				[
					'class' => 'ct-language ct-active-language',
					'data-label' => $language_label_position[$device],
					'aria-label' => $descriptor['native_name'],
					'lang' => $descriptor['language_code'],
					'tabindex' => '0',
				],
				$content
			);

			continue;
		}

		if (
			$hide_current_language
			&&
			$key === 'current'
		) {
			continue;
		}

		if (
			$descriptor['country_flag_url']
			&&
			$items_language_type['icon']
		) {
			$flag_image = blocksy_html_tag(
				'img',
				[
					'src' => $descriptor['country_flag_url'],
					'width' => '18',
					'height' => '12',
					// 'alt' => $descriptor['language_code'],
					'alt' => '',
					'aria-hidden' => 'true',
					// 'title' => $descriptor['native_name'],
				]
			);
		}

		if ($items_language_type['label']) {
			$content .= blocksy_html_tag(
				'span',
				[
					'class' => 'ct-label',
					'aria-hidden' => 'true',
				],
				$items_language_label === 'long'
					? $descriptor['native_name']
					: $descriptor['short_name']
			);
		}

		$content .= $flag_image;

		$items_html[] = blocksy_html_tag(
			'li',
			$key === 'current' ? [
				'class' => 'current-lang',
			] : [],
			blocksy_html_tag(
				'a',
				array_merge(
					[
						'href' => $descriptor['url'],
						'data-label' => $items_language_label_position,
						'aria-label' => $descriptor['native_name'],
						'lang' => $descriptor['language_code'],
					],
					($ls_type === 'dropdown' ? [] : [
						'data-label' => $items_language_label_position,
					]),
					($current_plugin === 'weglot' ? [
						'data-wg-notranslate' => "",
					] : [])
				),
				$content
			)
		);
	}

	$output .= blocksy_html_tag(
		'ul',
		(
			$ls_type !== "dropdown" ? [
				'class' => 'ct-language',
			] : []
		),
		implode('', $items_html)
	);
}

$attr['class'] = trim($class);
$attr['data-type'] = $ls_type;

?>

<div <?php blocksy_attr_to_html_e($attr) ?>>

	<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $output
	?>

</div>
