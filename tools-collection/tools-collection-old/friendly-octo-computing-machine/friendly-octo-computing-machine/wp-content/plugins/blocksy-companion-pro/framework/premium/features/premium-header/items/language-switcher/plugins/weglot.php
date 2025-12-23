<?php

$languages_available = array_values((array)weglot_get_languages_available())[0];

$current_language = weglot_get_current_language();
$original_language = weglot_get_original_language();

$destination_languages = array_map(function ($object) {
	return $object['language_to'];
}, weglot_get_destination_languages());

$languages = array_merge(array($original_language), $destination_languages);

if (empty($languages)) {
	return;
}

$weglot_url = weglot_get_request_url_service()->get_weglot_url();

$descriptors = [];

foreach ($languages as $code) {
	$language = $languages_available[$code];

	$flag_code = $code;

	// TODO: need to solve this
	if ($flag_code === 'en') {
		$flag_code = 'gb';
	}

	$flag_url = "https://cdn.weglot.com/flags/rectangle_mat/{$flag_code}.svg";
	
	if ($code === $current_language) {
		$descriptors['current'] = [
			'url' => $weglot_url->getForLanguage($language, true),	
			'country_flag_url' => $flag_url,
			'language_code' => $code,
			'native_name' => $language->getLocalName(),
			'short_name' => $language->getExternalCode(),
		];

		continue;
	}

	$descriptors[] = [
		'url' => $weglot_url->getForLanguage($language, false),
		'country_flag_url' => $flag_url,
		'language_code' => $code,
		'native_name' => $language->getLocalName(),
		'short_name' => $language->getExternalCode(),
	];
}