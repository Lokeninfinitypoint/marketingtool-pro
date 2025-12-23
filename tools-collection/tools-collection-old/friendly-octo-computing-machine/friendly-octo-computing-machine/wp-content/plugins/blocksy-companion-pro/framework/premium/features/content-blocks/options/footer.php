<?php

$options = blocksy_akg(
	'options',
	blc_theme_functions()->blocksy_get_variables_from_file(
		dirname(__FILE__) . '/header.php',
		['options' => []]
	)
);

