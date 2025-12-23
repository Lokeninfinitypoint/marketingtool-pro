<?php

blocksy_render_view_e(
	get_template_directory() . '/inc/panel-builder/header/mobile-menu/view.php',
	[
		'atts' => $atts,
		'attr' => $attr,
		'device' => $device,
        'row_id' => $row_id,
		'location' => 'menu_mobile_2'
	]
);

