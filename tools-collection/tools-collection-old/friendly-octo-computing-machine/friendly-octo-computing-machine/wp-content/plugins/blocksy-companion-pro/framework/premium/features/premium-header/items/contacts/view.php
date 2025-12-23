<?php

$class = 'ct-contact-info';

$visibility = blocksy_default_akg(
	'visibility',
	$atts,
	[
		'tablet' => true,
		'mobile' => true,
	]
);

if ($panel_type === 'footer') {
	$visibility = blocksy_default_akg(
		'footer_contacts_visibility',
		$atts,
		[
			'desktop' => true,
			'tablet' => true,
			'mobile' => true
		]
	);
}

$class = trim($class . ' ' . blocksy_visibility_classes($visibility));

$contact_items = blocksy_default_akg(
	'contact_items',
	$atts,
	[
		[
			'id' => 'address',
			'enabled' => true,
			'title' => __('Address:', 'blocksy-companion'),
			'content' => 'Street Name, NY 38954',
			'link' => '',
		],

		[
			'id' => 'phone',
			'enabled' => true,
			'title' => __('Phone:', 'blocksy-companion'),
			'content' => '578-393-4937',
			'link' => 'tel:578-393-4937',
		],

		[
			'id' => 'mobile',
			'enabled' => true,
			'title' => __('Mobile:', 'blocksy-companion'),
			'content' => '578-393-4937',
			'link' => 'tel:578-393-4937',
		],
	]
);

$text = blocksy_translate_dynamic(
	blocksy_default_akg('header_button_text', $atts, __('Download', 'blocksy-companion')),
	$panel_type . ':' . $section_id . ':button:header_button_text'
);

foreach ($contact_items as $item_index => $single_item) {
	if (isset($single_item['title'])) {
		$contact_items[$item_index]['title'] = blocksy_translate_dynamic(
			$single_item['title'],
			$panel_type . ':' . $section_id . ':contacts:contact_items:' . $single_item['id'] . ':title'
		);
	}

	if (isset($single_item['content'])) {
		$contact_items[$item_index]['content'] = blocksy_translate_dynamic(
			$single_item['content'],
			$panel_type . ':' . $section_id . ':contacts:contact_items:' . $single_item['id'] . ':content'
		);
	}

	if (isset($single_item['link'])) {
		$contact_items[$item_index]['link'] = blocksy_translate_dynamic(
			$single_item['link'],
			$panel_type . ':' . $section_id . ':contacts:contact_items:' . $single_item['id'] . ':link'
		);
	}
}

blocksy_html_tag_e(
	'div',
	array_merge([
		'class' => $class
	], $attr),
	blc_get_contacts_output([
		'data' => $contact_items,
		'link_target' => blocksy_default_akg('link_target', $atts, 'no'),
		'link_nofollow' => blocksy_default_akg('link_nofollow', $atts, 'no'),
		'link_icons' => blocksy_default_akg('link_icons', $atts, 'no'),
		'type' => blocksy_akg('contacts_icon_shape', $atts, 'rounded'),
		'fill' => blocksy_akg('contacts_icon_fill_type', $atts, 'outline')
	])
);

