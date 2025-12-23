<?php

//oneclick importer
function ocdi_import_files() {
	return array(
		array(
			'import_file_name'           => 'App Landing',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/app/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/app/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/app/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/app/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/app/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/app',
		),

		array(
			'import_file_name'           => 'Marketing Startup',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/marketing/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/marketing/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/marketing/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/marketing/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/marketing/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/marketing',
		),

		array(
			'import_file_name'           => 'Saas Technology',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/saas/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/saas/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/saas/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/saas/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/saas/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/saas',
		),
		array(
			'import_file_name'           => 'Digital Agency',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/agency/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/agency/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/agency/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/agency/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/agency/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/agency',
		),
		array(
			'import_file_name'           => 'Software Company',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/software/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/software/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/software/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/software/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/software/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/software',
		),
		array(
			'import_file_name'           => 'IT Solutions',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/it/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/it/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/it/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/it/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/it/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/it',
		),
		array(
			'import_file_name'           => 'Shop',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/shop/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/shop/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/shop/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/shop/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/shop/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/shop',
		),
		array(
			'import_file_name'           => 'RTL',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/rtl/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/rtl/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/rtl/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/rtl/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/rtl/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/rtl',
		),
		array(
			'import_file_name'           => 'Creative IT Solution',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/creative/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/creative/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/creative/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/creative/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/creative/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/creative',
		),
		array(
			'import_file_name'           => 'Data Analysis',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/analysis/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/analysis/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/analysis/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/analysis/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/analysis/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/data-analysis/',
		),
		array(
			'import_file_name'           => 'Cloud hosting',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/hosting/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/hosting/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/hosting/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/hosting/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/hosting/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/cloud-hosting',
		),
		array(
			'import_file_name'           => 'Cyber security',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/security/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/security/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/security/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/security/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/security/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/cyber-security',
		),
		array(
			'import_file_name'           => 'Personal portfoilio',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/personal/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/personal/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/personal/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/personal/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/personal/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/freelance-personal/',
		),
		array(
			'import_file_name'           => 'Help Desk',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/help/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/help/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/help/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/help/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/help/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/help-desk/',
		),
		array(
			'import_file_name'           => 'Payment solutions',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/payment/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/payment/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/payment/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/payment/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/payment/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/payment-solutions/',
		),
				array(
			'import_file_name'           => 'NFT marketplace',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/nft/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/nft/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/nft/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/nft/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/nft/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/nft-marketplace/',
		),
		array(
			'import_file_name'           => 'Crypto',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/crypto/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/crypto/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/crypto/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/crypto/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/crypto/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/crypto/',
		),
		array(
			'import_file_name'           => 'AI Saas',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/ai-saas/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/ai-saas/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/ai-saas/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/ai-saas/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/ai-saas/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/ai-saas/',
		),
		array(
			'import_file_name'           => 'AI Creative',
			'categories'                  => array( 'Elementor' ),
			'import_file_url'            => plugins_url( '/demo-data/elementor/ai-creative/content.xml' , __FILE__ ),
			'import_widget_file_url'     => plugins_url( '/demo-data/elementor/ai-creative/widgets.wie' , __FILE__ ),
			'import_customizer_file_url'  => plugins_url( '/demo-data/elementor/ai-creative/customizer.dat' , __FILE__ ),
			'import_redux'           => array(
				array(
					'file_url'   => plugins_url( '/demo-data/elementor/ai-creative/redux.json' , __FILE__ ),
					'option_name' => 'iteck_theme_setting',
				),
			),
			'import_preview_image_url'   => plugins_url( '/demo-data/elementor/ai-creative/preview.jpg' , __FILE__ ),
			'import_notice'                => __( '<p>To prevent any error, please use the clean wordpress site to import the demo data. </p><p>Or you can use this plugin 
			<a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WordPress Database Reset</a> to reset/clear the database first.</p><p>After you import this demo, you will have to setup the elementor page builder .</p>', 'iteck_plg' ),
			'preview_url'                => 'https://iteck.themescamp.com/ai-creative/',
		)
	);
}
add_filter( 'pt-ocdi/import_files', 'ocdi_import_files' );

/*-----------automatically assign "Front page", "Posts page" and menu locations ---------------------------*/



function ocdi_after_import( $selected_import ) {

	// Assign menus to their locations.
	$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
			'primary_menu' => $main_menu->term_id, // replace 'main-menu' here with the menu location identifier from register_nav_menu() function
		)
	);

	if ( 'App Landing' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'App Home');
	}
	elseif ( 'Marketing Startup' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Home Marketing' );
	}
	elseif ( 'Saas Technology' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Home Saas' );
	}
	elseif ( 'Digital Agency' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Home Agency' );
	}
	elseif ( 'IT Solution (Elementor)' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Home' );
	}
	elseif ( 'Marketing (Elementor)' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Home' );
	}
	elseif ( 'Software (Elementor)' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Home Saas' );
	}
	elseif ( 'Digital Ageny' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Home Agency' );
	}
	elseif ( 'Software Company' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Home Software' );
	}
	elseif ( 'IT Solutions' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Home' );
	}
	elseif ( 'Creative IT Solution' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Creative Home' );
	}
	elseif ( 'Data Analysis' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Data Analysis Home' );
	}
	elseif ( 'Cloud hosting' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Cloud Hosting Home' );
	}
	elseif ( 'Cyber security' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Cyber Security Home' );
	}
	elseif ( 'Personal portfoilio' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Freelance Personal Home' );
	}
	elseif ( 'Help Desk' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Help Desk Home' );
	}
	elseif ( 'NFT marketplace' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'NFT Home' );
	}
	elseif ( 'Crypto' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Crypto Home' );
	}
	elseif ( 'Payment solutions' === $selected_import['import_file_name'] ) {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Payment Solutions Home' );
	}
	else {
		// Assign front page.
		$front_page_id = get_page_by_title( 'Home' );
	}
	
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );
	update_option( 'elementor_disable_color_schemes', 'yes' ); 
	update_option( 'elementor_disable_typography_schemes', 'yes' ); 
	update_option( 'elementor_load_fa4_shim', 'yes' ); 
	update_option( 'elementor_container_width', 1200 );
	$cpt_support = [ 'page', 'post','product','portfolio','footer','header','sidepanel' ];
	update_option( 'elementor_cpt_support', $cpt_support ); //update 'Costom post type'
}
add_action( 'pt-ocdi/after_import', 'ocdi_after_import' );

/*------------------disable the ProteusThemes branding notice -----------------------------------*/

add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

/*------------------Adding notes -----------------------------------*/

function ocdi_plugin_intro_text( $default_text ) {
	$default_text .= '<div class="ocdi__intro-text"><strong>Server requirements:</strong></div>';
	$default_text .= '<div class="ocdi__intro-text"><ul>
		<li>max_execution_time 3000</li>
		<li>memory_limit 128M</li>
		<li>post_max_size 64M</li>
		<li>upload_max_filesize 64M</li>
		<li>max_input_time 180</li>
	</ul></div><hr>';

	return $default_text;
}
add_filter( 'pt-ocdi/plugin_intro_text', 'ocdi_plugin_intro_text' );
