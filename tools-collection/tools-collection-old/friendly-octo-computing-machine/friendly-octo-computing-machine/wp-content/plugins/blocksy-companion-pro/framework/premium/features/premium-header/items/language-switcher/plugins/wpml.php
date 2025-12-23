<?php

$hide_missing_language = $hide_missing_language ? 1 : 0;

$languages =  apply_filters(
	'wpml_active_languages',
	null,
	"skip_missing={$hide_missing_language}&orderby=custom&order=asc"
);

if (empty($languages)) {
	return;
}

$descriptors = [];

foreach ($languages as $l) {
	if ($l['active']) {
		$descriptors['current'] = [
			'url' => $l['url'],
			'country_flag_url' => $l['country_flag_url'],
			'language_code' => $l['language_code'],
			'native_name' => $l['native_name'],
			'short_name' => strtoupper($l['language_code']),
		];

		continue;
	}

	$descriptors[] = [
		'url' => $l['url'],
		'country_flag_url' => $l['country_flag_url'],
		'language_code' => $l['language_code'],
		'native_name' => $l['native_name'],
		'short_name' => strtoupper($l['language_code']),
	];
}