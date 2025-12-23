<?php

global $TRP_LANGUAGE;


$settings = new TRP_Settings();

$settings_array = $settings->get_settings();

$trp = TRP_Translate_Press::get_trp_instance();

$trp_lang_switcher = new TRP_Language_Switcher(
	$settings->get_settings(),
	TRP_Translate_Press::get_trp_instance()
);

$trp_languages = $trp->get_component('languages');

if (current_user_can(apply_filters(
	'trp_translating_capability',
	'manage_options'
))) {
	$languages_to_display = $settings_array['translation-languages'];
} else {
	$languages_to_display = $settings_array['publish-languages'];
}

$url_converter = $trp->get_component('url_converter');

$languages = $trp_languages->get_language_names(
	$languages_to_display
);

if (empty($languages)) {
	return;
}

$descriptors = [];

$ls_flag_aspect_ratio = blocksy_akg('ls_flag_aspect_ratio', $atts, '4x3');

foreach ($languages as $code => $lang) {

	$flags_path = TRP_PLUGIN_URL .'assets/images/flags/';
	$flag_file_name = $code .'.png';

	if(file_exists(TRP_PLUGIN_DIR .'assets/flags/' . $ls_flag_aspect_ratio . '/' . $code . '.svg')) {
		$flags_path = TRP_PLUGIN_URL .'assets/flags/' . $ls_flag_aspect_ratio . '/';
		$flag_file_name = $code . '.svg';
	}

	$flags_path = apply_filters('trp_flags_path', $flags_path, $code);
	$flag_file_name = apply_filters('trp_flag_file_name', $flag_file_name, $code);

	if ($code === $TRP_LANGUAGE) {
		$descriptors['current'] = [
			'url' => $url_converter->get_url_for_language($code, false),
			'country_flag_url' => esc_url($flags_path . $flag_file_name),
			'language_code' => str_replace('_', '-', $code),
			'native_name' => $lang,
			'short_name' => strtoupper(
				$url_converter->get_url_slug($code, false)
			),
		];

		continue;
	}

	$descriptors[] = [
		'url' => $url_converter->get_url_for_language($code, false),
		'country_flag_url' => esc_url($flags_path . $flag_file_name),
		'language_code' => str_replace('_', '-', $code),
		'native_name' => $lang,
		'short_name' => strtoupper(
			$url_converter->get_url_slug($code, false)
		),
	];
}
