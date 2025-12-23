<?php


$languages = pll_the_languages([
	'hide_if_empty' => false,
	'hide_if_no_translation' => $hide_missing_language ? 1 : 0,
	'raw' => 1,
]);

if (empty($languages)) {
	return;
}

$descriptors = [];

foreach ($languages as $l) {
	if ($l['current_lang']) {
		$descriptors['current'] = [
			'url' => $l['url'],
			'country_flag_url' => $l['flag'],
			'language_code' => $l['locale'],
			'native_name' => $l['name'],
			'short_name' => strtoupper($l['slug']),
		];

		continue;
	}

	$descriptors[] = [
		'url' => $l['url'],
		'country_flag_url' => $l['flag'],
		'language_code' => $l['locale'],
		'native_name' => $l['name'],
		'short_name' => strtoupper($l['slug']),
	];
}