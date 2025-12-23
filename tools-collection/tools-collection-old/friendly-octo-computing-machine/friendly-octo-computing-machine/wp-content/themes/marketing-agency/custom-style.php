<?php

	/*-----------------------First highlight color-------------------*/

	$marketing_agency_first_color = get_theme_mod('marketing_agency_first_color');

	$marketing_agency_custom_css= "";

	if($marketing_agency_first_color != false){
		$marketing_agency_custom_css .='a, #footer .textwidget a,.post-main-box:hover h3 a,#sidebar ul li a:hover,.post-navigation a:hover .post-title, .post-navigation a:focus .post-title,.post-navigation a:hover,.post-navigation a:focus, .woocommerce ul.products li.product .price,.woocommerce div.product p.price, .woocommerce div.product span.price, .toggle-nav i, .wp-block-button .wp-block-button__link:hover, #footer .textwidget a, #sidebar .textwidget a, .woocommerce-product-details__short-description p a, .textwidget p a, .entry-content a, #sidebar p a, #comments p a, .comment-meta.commentmetadata a, #content-vw a{';
			$marketing_agency_custom_css .='color: '.esc_html($marketing_agency_first_color).';';
		$marketing_agency_custom_css .='}';
	}

	if($marketing_agency_first_color != false){
		$marketing_agency_custom_css .='.main-navigation a:hover, .wp-block-button .wp-block-button__link:hover,#footer .textwidget a, #sidebar .textwidget a, .woocommerce-product-details__short-description p a, .textwidget p a, .entry-content a, #comments p a, .comment-meta.commentmetadata a,a.wc-block-components-product-name, .wc-block-components-product-name{';
			$marketing_agency_custom_css .='color: '.esc_html($marketing_agency_first_color).'!important;';
		$marketing_agency_custom_css .='}';
	}

	/*------------------------Second highlight color-------------------*/

	$marketing_agency_second_color = get_theme_mod('marketing_agency_second_color');

	if($marketing_agency_second_color != false){
		$marketing_agency_custom_css .='.woocommerce span.onsale, #preloader{';
			$marketing_agency_custom_css .='background-color: '.esc_html($marketing_agency_second_color).';';
		$marketing_agency_custom_css .='}';
	}

	if($marketing_agency_second_color != false){
		$marketing_agency_custom_css .='.post-main-box:hover h2 a, .post-main-box:hover .post-info a, .post-info:hover a, .top-bar:hover p a, .logo .site-title a:hover, #footer li a:hover{';
			$marketing_agency_custom_css .='color: '.esc_html($marketing_agency_second_color).';';
		$marketing_agency_custom_css .='}';
	}

	if($marketing_agency_first_color != false || $marketing_agency_second_color != false){
		$marketing_agency_custom_css .='input[type="submit"], a.getstarted-btn, #header, .more-btn a, .inner-box:hover, #footer-2, .scrollup i, #comments input[type="submit"], #comments a.comment-reply-link, #sidebar h3, .pagination span, .pagination a, .widget_product_search button, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button,.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, nav.woocommerce-MyAccount-navigation ul li, .wp-block-button__link, #footer .wp-block-search .wp-block-search__button, #sidebar .wp-block-search .wp-block-search__button, #sidebar .wp-block-search .wp-block-search__label, .bradcrumbs a:hover, .bradcrumbs span, .post-categories li a:hover, nav.navigation.posts-navigation .nav-previous a, nav.navigation.posts-navigation .nav-next a, #sidebar .wp-block-heading, .wp-block-tag-cloud a:hover,.wp-block-woocommerce-cart .wc-block-cart__submit-button, .wc-block-components-checkout-place-order-button, .wc-block-components-totals-coupon__button,.wc-block-components-order-summary-item__quantity,#footer .tagcloud a, a.wc-block-components-checkout-return-to-cart-button{
			background: linear-gradient(to right, '.esc_html($marketing_agency_first_color).', '.esc_html($marketing_agency_second_color).')!important;
		}';
	}

	if($marketing_agency_first_color != false || $marketing_agency_second_color != false){
		$marketing_agency_custom_css .='.more-btn a:hover, nav.navigation.posts-navigation .nav-previous a:hover, nav.navigation.posts-navigation .nav-next a:hover{
			background: linear-gradient(to right, '.esc_html($marketing_agency_second_color).', '.esc_html($marketing_agency_first_color).');
		}';
	}

	if($marketing_agency_first_color != false || $marketing_agency_second_color != false){
		$marketing_agency_custom_css .='.page-template-custom-home-page .main-navigation .current_page_item > a,.page-template-custom-home-page .main-navigation .current-menu-item > a, .page-template-custom-home-page .main-navigation .current_page_ancestor > a,.page-template-custom-home-page .main-navigation a:hover, .custom-social-icons i:hover,.top-bar i.far.fa-envelope,.top-bar i.fas.fa-phone, .main-navigation ul.sub-menu a:hover{
			background: linear-gradient('.esc_html($marketing_agency_first_color).', '.esc_html($marketing_agency_second_color).');
			-webkit-text-fill-color: transparent;
			-webkit-background-clip: text;
		}';
	}

	if($marketing_agency_second_color != false){
		$marketing_agency_custom_css .='.more-btn a:hover, nav.navigation.posts-navigation .nav-previous a:hover, nav.navigation.posts-navigation .nav-next a:hover,.wp-block-woocommerce-cart .wc-block-cart__submit-button:hover, .wc-block-components-checkout-place-order-button:hover{';
			$marketing_agency_custom_css .='box-shadow: 0 0.4rem 1rem '.esc_html($marketing_agency_second_color).';';
		$marketing_agency_custom_css .='}';
	}

	/*------------------------Third highlight color-------------------*/

	$marketing_agency_third_color = get_theme_mod('marketing_agency_third_color');

	if($marketing_agency_third_color != false){
		$marketing_agency_custom_css .='#slider .carousel-control-prev-icon{
			border-color: transparent '.esc_html($marketing_agency_third_color).' transparent transparent;
		}';
	}

	if($marketing_agency_third_color != false){
		$marketing_agency_custom_css .='#slider .carousel-control-next-icon{
			border-color: transparent transparent transparent '.esc_html($marketing_agency_third_color).';
		}';
	}

	/*--------------------------- Slider -------------------*/

	$marketing_agency_slider = get_theme_mod('marketing_agency_slider_arrows');
	if($marketing_agency_slider == false){
		$marketing_agency_custom_css .='.page-template-custom-home-page .main-navigation a, .page-template-custom-home-page p.site-title a, .page-template-custom-home-page .logo h1 a, .page-template-custom-home-page p.site-description{';
			$marketing_agency_custom_css .='color: #333333;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='#services-sec{';
			$marketing_agency_custom_css .='margin-top: 2em;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='.page-template-custom-home-page .header-box{';
			$marketing_agency_custom_css .='border-bottom: solid 1px #333333;';
		$marketing_agency_custom_css .='}';

		$marketing_agency_custom_css .='@media screen and (max-width:720px) {';
		$marketing_agency_custom_css .='.page-template-custom-home-page p.site-title a, .page-template-custom-home-page .logo h1 a, .page-template-custom-home-page p.site-description{';
			$marketing_agency_custom_css .='color: #ffffff;';
		$marketing_agency_custom_css .='} }';

		$marketing_agency_custom_css .='@media screen and (max-width:720px) {';
		$marketing_agency_custom_css .='.page-template-custom-home-page .header-box{';
			$marketing_agency_custom_css .='border-bottom: solid 1px #ffffff;';
		$marketing_agency_custom_css .='} }';

		$marketing_agency_custom_css .='@media screen and (max-width:720px) {';
		$marketing_agency_custom_css .='#services-sec{';
			$marketing_agency_custom_css .='margin-top: 5em;';
		$marketing_agency_custom_css .='} }';
	}

	/*---------------------------Width Layout -------------------*/

	$marketing_agency_theme_lay = get_theme_mod( 'marketing_agency_width_option','Full Width');
    if($marketing_agency_theme_lay == 'Boxed'){
		$marketing_agency_custom_css .='body{';
			$marketing_agency_custom_css .='max-width: 1140px; width: 100%; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='#slider .carousel-control-prev-icon{';
			$marketing_agency_custom_css .='border-width: 25px 163px 25px 0; top: 42px;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='#slider .carousel-control-next-icon{';
			$marketing_agency_custom_css .='border-width: 25px 0 25px 170px; top: 42px;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='.scrollup i{';
			$marketing_agency_custom_css .='right: 100px;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='.scrollup.left i{';
			$marketing_agency_custom_css .='left: 100px;';
		$marketing_agency_custom_css .='}';
	}else if($marketing_agency_theme_lay == 'Wide Width'){
		$marketing_agency_custom_css .='body{';
			$marketing_agency_custom_css .='width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='.scrollup i{';
			$marketing_agency_custom_css .='right: 30px;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='.scrollup.left i{';
			$marketing_agency_custom_css .='left: 30px;';
		$marketing_agency_custom_css .='}';
	}else if($marketing_agency_theme_lay == 'Full Width'){
		$marketing_agency_custom_css .='body{';
			$marketing_agency_custom_css .='max-width: 100%;';
		$marketing_agency_custom_css .='}';
	}

	/*----------------- Slider Content Layout -------------------*/

	$marketing_agency_slider_image = get_theme_mod('marketing_agency_slider_image');
	if($marketing_agency_slider_image != false){
		$marketing_agency_custom_css .='#slider{';
			$marketing_agency_custom_css .='background: url('.esc_url($marketing_agency_slider_image).');';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_theme_lay = get_theme_mod( 'marketing_agency_slider_content_option','Left');
    if($marketing_agency_theme_lay == 'Left'){
		$marketing_agency_custom_css .='#slider .carousel-caption{';
			$marketing_agency_custom_css .='text-align:left; right: 22%; left: 2%;';
		$marketing_agency_custom_css .='}';
	}else if($marketing_agency_theme_lay == 'Center'){
		$marketing_agency_custom_css .='#slider .carousel-caption{';
			$marketing_agency_custom_css .='text-align:center; right: 10%; left: 10%;';
		$marketing_agency_custom_css .='}';
	}else if($marketing_agency_theme_lay == 'Right'){
		$marketing_agency_custom_css .='#slider .carousel-caption{';
			$marketing_agency_custom_css .='text-align:right; right: 10%; left: 15%;';
		$marketing_agency_custom_css .='}';
	}

	/*------------- Slider Content Padding Settings ------------------*/

	$marketing_agency_slider_content_padding_top_bottom = get_theme_mod('marketing_agency_slider_content_padding_top_bottom');
	$marketing_agency_slider_content_padding_left_right = get_theme_mod('marketing_agency_slider_content_padding_left_right');
	if($marketing_agency_slider_content_padding_top_bottom != false || $marketing_agency_slider_content_padding_left_right != false){
		$marketing_agency_custom_css .='#slider .carousel-caption{';
			$marketing_agency_custom_css .='top: '.esc_attr($marketing_agency_slider_content_padding_top_bottom).'; bottom: '.esc_attr($marketing_agency_slider_content_padding_top_bottom).';left: '.esc_attr($marketing_agency_slider_content_padding_left_right).';right: '.esc_attr($marketing_agency_slider_content_padding_left_right).';';
		$marketing_agency_custom_css .='}';
	}

	/*---------------------------Blog Layout -------------------*/

	$marketing_agency_theme_lay = get_theme_mod( 'marketing_agency_blog_layout_option','Default');
    if($marketing_agency_theme_lay == 'Default'){
		$marketing_agency_custom_css .='.post-main-box{';
			$marketing_agency_custom_css .='';
		$marketing_agency_custom_css .='}';
	}else if($marketing_agency_theme_lay == 'Center'){
		$marketing_agency_custom_css .='.post-main-box, .post-main-box h2, .post-info, .new-text p{';
			$marketing_agency_custom_css .='text-align:center;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='.post-info{';
			$marketing_agency_custom_css .='margin-top:10px;';
		$marketing_agency_custom_css .='}';
	}else if($marketing_agency_theme_lay == 'Left'){
		$marketing_agency_custom_css .='.post-main-box, .post-main-box h2, .post-info, .new-text p, #our-services p{';
			$marketing_agency_custom_css .='text-align:Left;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='.post-main-box h2{';
			$marketing_agency_custom_css .='margin-top:10px;';
		$marketing_agency_custom_css .='}';
	}

	// featured image dimention
	$marketing_agency_blog_post_featured_image_dimension = get_theme_mod('marketing_agency_blog_post_featured_image_dimension', 'default');
	$marketing_agency_blog_post_featured_image_custom_width = get_theme_mod('marketing_agency_blog_post_featured_image_custom_width',250);
	$marketing_agency_blog_post_featured_image_custom_height = get_theme_mod('marketing_agency_blog_post_featured_image_custom_height',250);
	if($marketing_agency_blog_post_featured_image_dimension == 'custom'){
		$marketing_agency_custom_css .='.post-main-box img{';
			$marketing_agency_custom_css .='width: '.esc_attr($marketing_agency_blog_post_featured_image_custom_width).'; height: '.esc_attr($marketing_agency_blog_post_featured_image_custom_height).';';
		$marketing_agency_custom_css .='}';
	}

	/*--------------------- Blog Page Posts -------------------*/

	$marketing_agency_blog_page_posts_settings = get_theme_mod( 'marketing_agency_blog_page_posts_settings','Into Blocks');
    if($marketing_agency_blog_page_posts_settings == 'Without Blocks'){
		$marketing_agency_custom_css .='.post-main-box{';
			$marketing_agency_custom_css .='box-shadow: none; border: none; margin:30px 0;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_related_image_box_shadow = get_theme_mod('marketing_agency_related_image_box_shadow',0);
    if($marketing_agency_related_image_box_shadow != false){
        $marketing_agency_custom_css .='.related-post .box-image img{';
            $marketing_agency_custom_css .='box-shadow: '.esc_attr($marketing_agency_related_image_box_shadow).'px '.esc_attr($marketing_agency_related_image_box_shadow).'px '.esc_attr($marketing_agency_related_image_box_shadow).'px #cccccc;';
        $marketing_agency_custom_css .='}';
    }

	/*--------------------- Grid Posts -------------------*/

	$marketing_agency_display_grid_posts_settings = get_theme_mod( 'marketing_agency_display_grid_posts_settings','Into Blocks');
    if($marketing_agency_display_grid_posts_settings == 'Without Blocks'){
		$marketing_agency_custom_css .='.grid-post-main-box{';
			$marketing_agency_custom_css .='box-shadow: none; border: none; margin:30px 0; background: none;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_grid_featured_image_box_shadow = get_theme_mod('marketing_agency_grid_featured_image_box_shadow',0);
	if($marketing_agency_grid_featured_image_box_shadow != false){
		$marketing_agency_custom_css .='.grid-post-main-box .box-image img, .grid-post-main-box .feature-box img, #content-vw img{';
			$marketing_agency_custom_css .='box-shadow: '.esc_attr($marketing_agency_grid_featured_image_box_shadow).'px '.esc_attr($marketing_agency_grid_featured_image_box_shadow).'px '.esc_attr($marketing_agency_grid_featured_image_box_shadow).'px #cccccc;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_grid_featured_image_border_radius = get_theme_mod('marketing_agency_grid_featured_image_border_radius', 0);
	if($marketing_agency_grid_featured_image_border_radius != false){
		$marketing_agency_custom_css .='.grid-post-main-box .box-image img, .grid-post-main-box .feature-box img, #content-vw img{';
			$marketing_agency_custom_css .='border-radius: '.esc_attr($marketing_agency_grid_featured_image_border_radius).'px;';
		$marketing_agency_custom_css .='}';
	}

	/*----------------Responsive Media -----------------------*/

	$marketing_agency_resp_stickyheader = get_theme_mod( 'marketing_agency_stickyheader_hide_show',false);
	if($marketing_agency_resp_stickyheader == true && get_theme_mod( 'marketing_agency_sticky_header',false) != true){
    	$marketing_agency_custom_css .='.header-fixed #header{';
			$marketing_agency_custom_css .='position:static;z-index: 999;';
		$marketing_agency_custom_css .='} ';
	}
    if($marketing_agency_resp_stickyheader == true){
    	$marketing_agency_custom_css .='@media screen and (max-width:575px) {';
		$marketing_agency_custom_css .='.header-fixed #header{';
			$marketing_agency_custom_css .='position:fixed;z-index: 999;';
		$marketing_agency_custom_css .='} }';
	}else if($marketing_agency_resp_stickyheader == false){
		$marketing_agency_custom_css .='@media screen and (max-width:575px){';
		$marketing_agency_custom_css .='.header-fixed #header{';
			$marketing_agency_custom_css .='position:static;z-index: 999;';
		$marketing_agency_custom_css .='} }';
	}

	$marketing_agency_resp_topbar = get_theme_mod( 'marketing_agency_resp_topbar_hide_show',true);
	if($marketing_agency_resp_topbar == true && get_theme_mod( 'marketing_agency_hide_show_topbar_section', true) == false){
    	$marketing_agency_custom_css .='.top-bar{';
			$marketing_agency_custom_css .='display:none;';
		$marketing_agency_custom_css .='} ';
	}
    if($marketing_agency_resp_topbar == true){
    	$marketing_agency_custom_css .='@media screen and (max-width:575px) {';
		$marketing_agency_custom_css .='.top-bar{';
			$marketing_agency_custom_css .='display:block;';
		$marketing_agency_custom_css .='} }';
	}else if($marketing_agency_resp_topbar == false){
		$marketing_agency_custom_css .='@media screen and (max-width:575px) {';
		$marketing_agency_custom_css .='.top-bar{';
			$marketing_agency_custom_css .='display:none;';
		$marketing_agency_custom_css .='} }';
	}

	$marketing_agency_resp_slider = get_theme_mod( 'marketing_agency_resp_slider_hide_show',true);
	if($marketing_agency_resp_slider == true && get_theme_mod( 'marketing_agency_slider_arrows', true) == false){
    	$marketing_agency_custom_css .='#slider{';
			$marketing_agency_custom_css .='display:none;';
		$marketing_agency_custom_css .='} ';
	}
    if($marketing_agency_resp_slider == true){
    	$marketing_agency_custom_css .='@media screen and (max-width:575px) {';
		$marketing_agency_custom_css .='#slider{';
			$marketing_agency_custom_css .='display:block;';
		$marketing_agency_custom_css .='} }';
	}else if($marketing_agency_resp_slider == false){
		$marketing_agency_custom_css .='@media screen and (max-width:575px) {';
		$marketing_agency_custom_css .='#slider{';
			$marketing_agency_custom_css .='display:none;';
		$marketing_agency_custom_css .='} }';
	}

	$marketing_agency_resp_sidebar = get_theme_mod( 'marketing_agency_sidebar_hide_show',true);
    if($marketing_agency_resp_sidebar == true){
    	$marketing_agency_custom_css .='@media screen and (max-width:575px) {';
		$marketing_agency_custom_css .='#sidebar{';
			$marketing_agency_custom_css .='display:block;';
		$marketing_agency_custom_css .='} }';
	}else if($marketing_agency_resp_sidebar == false){
		$marketing_agency_custom_css .='@media screen and (max-width:575px) {';
		$marketing_agency_custom_css .='#sidebar{';
			$marketing_agency_custom_css .='display:none;';
		$marketing_agency_custom_css .='} }';
	}

	$marketing_agency_resp_scroll_top = get_theme_mod( 'marketing_agency_resp_scroll_top_hide_show',true);
	if($marketing_agency_resp_scroll_top == true && get_theme_mod( 'marketing_agency_footer_scroll',true) != true){
    	$marketing_agency_custom_css .='.scrollup i{';
			$marketing_agency_custom_css .='visibility:hidden !important;';
		$marketing_agency_custom_css .='} ';
	}
    if($marketing_agency_resp_scroll_top == true){
    	$marketing_agency_custom_css .='@media screen and (max-width:575px) {';
		$marketing_agency_custom_css .='.scrollup i{';
			$marketing_agency_custom_css .='visibility:visible !important;';
		$marketing_agency_custom_css .='} }';
	}else if($marketing_agency_resp_scroll_top == false){
		$marketing_agency_custom_css .='@media screen and (max-width:575px){';
		$marketing_agency_custom_css .='.scrollup i{';
			$marketing_agency_custom_css .='visibility:hidden !important;';
		$marketing_agency_custom_css .='} }';
	}

	$marketing_agency_resp_menu_toggle_btn_bg_color = get_theme_mod('marketing_agency_resp_menu_toggle_btn_bg_color');
	if($marketing_agency_resp_menu_toggle_btn_bg_color != false){
		$marketing_agency_custom_css .='.toggle-nav i{';
			$marketing_agency_custom_css .='background-color: '.esc_attr($marketing_agency_resp_menu_toggle_btn_bg_color).';';
		$marketing_agency_custom_css .='}';
	}

	/*---------------- Menus Settings ------------------*/

	$marketing_agency_navigation_menu_font_size = get_theme_mod('marketing_agency_navigation_menu_font_size');
	if($marketing_agency_navigation_menu_font_size != false){
		$marketing_agency_custom_css .='.main-navigation a{';
			$marketing_agency_custom_css .='font-size: '.esc_attr($marketing_agency_navigation_menu_font_size).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_header_menus_color = get_theme_mod('marketing_agency_header_menus_color');
	if($marketing_agency_header_menus_color != false){
		$marketing_agency_custom_css .='.main-navigation a, .page-template-custom-home-page .main-navigation a{';
			$marketing_agency_custom_css .='color: '.esc_attr($marketing_agency_header_menus_color).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_header_menus_hover_color = get_theme_mod('marketing_agency_header_menus_hover_color');
	if($marketing_agency_header_menus_hover_color != false){
		$marketing_agency_custom_css .='.main-navigation a:hover, .page-template-custom-home-page .main-navigation a:hover, .page-template-custom-home-page .main-navigation .current_page_item > a, .page-template-custom-home-page .main-navigation .current-menu-item > a, .page-template-custom-home-page .main-navigation .current_page_ancestor > a{';
			$marketing_agency_custom_css .='color: '.esc_attr($marketing_agency_header_menus_hover_color).'!important;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_header_submenus_color = get_theme_mod('marketing_agency_header_submenus_color');
	if($marketing_agency_header_submenus_color != false){
		$marketing_agency_custom_css .='.main-navigation ul ul a, .page-template-custom-home-page .main-navigation ul ul a{';
			$marketing_agency_custom_css .='color: '.esc_attr($marketing_agency_header_submenus_color).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_header_submenus_hover_color = get_theme_mod('marketing_agency_header_submenus_hover_color');
	if($marketing_agency_header_submenus_hover_color != false){
		$marketing_agency_custom_css .='.main-navigation ul.sub-menu a:hover, .main-navigation ul ul a:hover, .page-template-custom-home-page .main-navigation ul.sub-menu a:hover, .page-template-custom-home-page .main-navigation ul ul a:hover{';
			$marketing_agency_custom_css .='color: '.esc_attr($marketing_agency_header_submenus_hover_color).'!important; -webkit-text-fill-color: '.esc_attr($marketing_agency_header_submenus_hover_color).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_menus_item = get_theme_mod( 'marketing_agency_menus_item_style','None');
    if($marketing_agency_menus_item == 'None'){
		$marketing_agency_custom_css .='.main-navigation a{';
			$marketing_agency_custom_css .='';
		$marketing_agency_custom_css .='}';
	}else if($marketing_agency_menus_item == 'Zoom In'){
		$marketing_agency_custom_css .='.main-navigation a:hover{';
			$marketing_agency_custom_css .='transition: all 0.3s ease-in-out !important; transform: scale(1.2) !important; color:#2ecc71;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_navigation_menu_font_weight = get_theme_mod('marketing_agency_navigation_menu_font_weight','600');
	if($marketing_agency_navigation_menu_font_weight != false){
		$marketing_agency_custom_css .='.main-navigation a{';
			$marketing_agency_custom_css .='font-weight: '.esc_attr($marketing_agency_navigation_menu_font_weight).';';
		$marketing_agency_custom_css .='}';
	}
	
	$marketing_agency_theme_lay = get_theme_mod( 'marketing_agency_menu_text_transform','Capitalize');
	if($marketing_agency_theme_lay == 'Capitalize'){
		$marketing_agency_custom_css .='.main-navigation a{';
			$marketing_agency_custom_css .='text-transform:Capitalize;';
		$marketing_agency_custom_css .='}';
	}
	if($marketing_agency_theme_lay == 'Lowercase'){
		$marketing_agency_custom_css .='.main-navigation a{';
			$marketing_agency_custom_css .='text-transform:Lowercase;';
		$marketing_agency_custom_css .='}';
	}
	if($marketing_agency_theme_lay == 'Uppercase'){
		$marketing_agency_custom_css .='.main-navigation a{';
			$marketing_agency_custom_css .='text-transform:Uppercase;';
		$marketing_agency_custom_css .='}';
	}

	/*---------------- Post Settings ------------------*/

	$marketing_agency_featured_image_border_radius = get_theme_mod('marketing_agency_featured_image_border_radius', 0);
	if($marketing_agency_featured_image_border_radius != false){
		$marketing_agency_custom_css .='.box-image img, .feature-box img{';
			$marketing_agency_custom_css .='border-radius: '.esc_attr($marketing_agency_featured_image_border_radius).'px;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_featured_image_box_shadow = get_theme_mod('marketing_agency_featured_image_box_shadow',0);
	if($marketing_agency_featured_image_box_shadow != false){
		$marketing_agency_custom_css .='.box-image img, #content-vw img{';
			$marketing_agency_custom_css .='box-shadow: '.esc_attr($marketing_agency_featured_image_box_shadow).'px '.esc_attr($marketing_agency_featured_image_box_shadow).'px '.esc_attr($marketing_agency_featured_image_box_shadow).'px #cccccc;';
		$marketing_agency_custom_css .='}';
	}

	/*---------------- Single Post Settings ------------------*/

	$marketing_agency_single_blog_comment_title = get_theme_mod('marketing_agency_single_blog_comment_title', 'Leave a Reply');
	if($marketing_agency_single_blog_comment_title == ''){
		$marketing_agency_custom_css .='#comments h2#reply-title {';
			$marketing_agency_custom_css .='display: none;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_single_blog_comment_button_text = get_theme_mod('marketing_agency_single_blog_comment_button_text', 'Post Comment');
	if($marketing_agency_single_blog_comment_button_text == ''){
		$marketing_agency_custom_css .='#comments p.form-submit {';
			$marketing_agency_custom_css .='display: none;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_comment_width = get_theme_mod('marketing_agency_single_blog_comment_width');
	if($marketing_agency_comment_width != false){
		$marketing_agency_custom_css .='#comments textarea{';
			$marketing_agency_custom_css .='width: '.esc_attr($marketing_agency_comment_width).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_singlepost_image_box_shadow = get_theme_mod('marketing_agency_singlepost_image_box_shadow',0);
	if($marketing_agency_singlepost_image_box_shadow != false){
		$marketing_agency_custom_css .='.feature-box img{';
			$marketing_agency_custom_css .='box-shadow: '.esc_attr($marketing_agency_singlepost_image_box_shadow).'px '.esc_attr($marketing_agency_singlepost_image_box_shadow).'px '.esc_attr($marketing_agency_singlepost_image_box_shadow).'px #cccccc;';
		$marketing_agency_custom_css .='}';
	}

	/*---------------- Button Settings ------------------*/

	$marketing_agency_button_border_radius = get_theme_mod('marketing_agency_button_border_radius');
	if($marketing_agency_button_border_radius != false){
		$marketing_agency_custom_css .='.post-main-box .more-btn a{';
			$marketing_agency_custom_css .='border-radius: '.esc_attr($marketing_agency_button_border_radius).'px;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_button_font_size = get_theme_mod('marketing_agency_button_font_size',14);
	$marketing_agency_custom_css .='.post-main-box .more-btn a{';
		$marketing_agency_custom_css .='font-size: '.esc_attr($marketing_agency_button_font_size).';';
	$marketing_agency_custom_css .='}';

	$marketing_agency_theme_lay = get_theme_mod( 'marketing_agency_button_text_transform','Uppercase');
	if($marketing_agency_theme_lay == 'Capitalize'){
		$marketing_agency_custom_css .='.post-main-box .more-btn a{';
			$marketing_agency_custom_css .='text-transform:Capitalize;';
		$marketing_agency_custom_css .='}';
	}
	if($marketing_agency_theme_lay == 'Lowercase'){
		$marketing_agency_custom_css .='.post-main-box .more-btn a{';
			$marketing_agency_custom_css .='text-transform:Lowercase;';
		$marketing_agency_custom_css .='}';
	}
	if($marketing_agency_theme_lay == 'Uppercase'){ 
		$marketing_agency_custom_css .='.post-main-box .more-btn a{';
			$marketing_agency_custom_css .='text-transform:Uppercase;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_button_padding_top_bottom = get_theme_mod('marketing_agency_button_padding_top_bottom');
	$marketing_agency_button_padding_left_right = get_theme_mod('marketing_agency_button_padding_left_right');
	if($marketing_agency_button_padding_top_bottom != false || $marketing_agency_button_padding_left_right != false){
		$marketing_agency_custom_css .='.post-main-box .more-btn a{';
			$marketing_agency_custom_css .='padding-top: '.esc_attr($marketing_agency_button_padding_top_bottom).'!important; 
			padding-bottom: '.esc_attr($marketing_agency_button_padding_top_bottom).'!important;
			padding-left: '.esc_attr($marketing_agency_button_padding_left_right).'!important;
			padding-right: '.esc_attr($marketing_agency_button_padding_left_right).'!important;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_button_letter_spacing = get_theme_mod('marketing_agency_button_letter_spacing');
	$marketing_agency_custom_css .='.post-main-box .more-btn a{';
		$marketing_agency_custom_css .='letter-spacing: '.esc_attr($marketing_agency_button_letter_spacing).';';
	$marketing_agency_custom_css .='}';

	/*-------------- Copyright Alignment ----------------*/
	$marketing_agency_resp_stickycopyright = get_theme_mod( 'marketing_agency_stickycopyright_hide_show',false);
	if($marketing_agency_resp_stickycopyright == true && get_theme_mod( 'marketing_agency_copyright_sticky',false) != true){
    	$marketing_agency_custom_css .='.copyright-sticky{';
			$marketing_agency_custom_css .='position:static;';
		$marketing_agency_custom_css .='} ';
	}

	$marketing_agency_footer_social_icons_font_size = get_theme_mod('marketing_agency_footer_social_icons_font_size','16');
	$marketing_agency_custom_css .='.copyright .widget i{';
		$marketing_agency_custom_css .='font-size: '.esc_attr($marketing_agency_footer_social_icons_font_size).'px;';
	$marketing_agency_custom_css .='}';
	
	$marketing_agency_copyright_first_color = get_theme_mod('marketing_agency_copyright_first_color');

	$marketing_agency_copyright_second_color = get_theme_mod('marketing_agency_copyright_second_color');

	if($marketing_agency_copyright_first_color != false || $marketing_agency_copyright_second_color != false){
		$marketing_agency_custom_css .='#footer-2{
		background: linear-gradient(to right, '.esc_attr($marketing_agency_copyright_first_color).', '.esc_attr($marketing_agency_copyright_second_color).');
		}';
	}

	$marketing_agency_footer_widgets_heading = get_theme_mod( 'marketing_agency_footer_widgets_heading','Left');
    if($marketing_agency_footer_widgets_heading == 'Left'){
		$marketing_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label{';
		$marketing_agency_custom_css .='text-align: left;';
		$marketing_agency_custom_css .='}';
	}else if($marketing_agency_footer_widgets_heading == 'Center'){
		$marketing_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label{';
			$marketing_agency_custom_css .='text-align: center;';
		$marketing_agency_custom_css .='}';
	}else if($marketing_agency_footer_widgets_heading == 'Right'){
		$marketing_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label{';
			$marketing_agency_custom_css .='text-align: right;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_footer_widgets_content = get_theme_mod( 'marketing_agency_footer_widgets_content','Left');
    if($marketing_agency_footer_widgets_content == 'Left'){
		$marketing_agency_custom_css .='#footer .widget{';
		$marketing_agency_custom_css .='text-align: left;';
		$marketing_agency_custom_css .='}';
	}else if($marketing_agency_footer_widgets_content == 'Center'){
		$marketing_agency_custom_css .='#footer .widget{';
			$marketing_agency_custom_css .='text-align: center;';
		$marketing_agency_custom_css .='}';
	}else if($marketing_agency_footer_widgets_content == 'Right'){
		$marketing_agency_custom_css .='#footer .widget{';
			$marketing_agency_custom_css .='text-align: right;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_footer_background_color = get_theme_mod('marketing_agency_footer_background_color');
	if($marketing_agency_footer_background_color != false){
		$marketing_agency_custom_css .='#footer{';
			$marketing_agency_custom_css .='background-color: '.esc_attr($marketing_agency_footer_background_color).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_copyright_font_size = get_theme_mod('marketing_agency_copyright_font_size');
	if($marketing_agency_copyright_font_size != false){
		$marketing_agency_custom_css .='.copyright p{';
			$marketing_agency_custom_css .='font-size: '.esc_attr($marketing_agency_copyright_font_size).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_align_footer_social_icon = get_theme_mod('marketing_agency_align_footer_social_icon');
	if($marketing_agency_align_footer_social_icon != false){
		$marketing_agency_custom_css .='.copyright .widget{';
			$marketing_agency_custom_css .='text-align: '.esc_attr($marketing_agency_align_footer_social_icon).';';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='
		@media screen and (max-width:720px) {
			.copyright .widget{';
			$marketing_agency_custom_css .='text-align: center;} }';
	}

	$marketing_agency_copyright_alignment = get_theme_mod('marketing_agency_copyright_alignment');
	if($marketing_agency_copyright_alignment != false){
		$marketing_agency_custom_css .='.copyright p,#footer-2 p{';
			$marketing_agency_custom_css .='text-align: '.esc_attr($marketing_agency_copyright_alignment).';';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='
		@media screen and (max-width:720px) {
			.copyright p,#footer-2 p{';
			$marketing_agency_custom_css .='text-align: center;} }';
	}

	$marketing_agency_footer_padding = get_theme_mod('marketing_agency_footer_padding');
	if($marketing_agency_footer_padding != false){
		$marketing_agency_custom_css .='#footer{';
			$marketing_agency_custom_css .='padding: '.esc_attr($marketing_agency_footer_padding).' 0;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_footer_icon = get_theme_mod('marketing_agency_footer_icon');
	if($marketing_agency_footer_icon == false){
		$marketing_agency_custom_css .='.copyright p{';
			$marketing_agency_custom_css .='width:100%; text-align:center; float:none;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_footer_background_image = get_theme_mod('marketing_agency_footer_background_image');
	if($marketing_agency_footer_background_image != false){
		$marketing_agency_custom_css .='#footer{';
			$marketing_agency_custom_css .='background: url('.esc_attr($marketing_agency_footer_background_image).'); background-size: cover;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_theme_lay = get_theme_mod( 'marketing_agency_img_footer','scroll');
	if($marketing_agency_theme_lay == 'fixed'){
		$marketing_agency_custom_css .='#footer{';
			$marketing_agency_custom_css .='background-attachment: fixed !important;';
		$marketing_agency_custom_css .='}';
	}elseif ($marketing_agency_theme_lay == 'scroll'){
		$marketing_agency_custom_css .='#footer{';
			$marketing_agency_custom_css .='background-attachment: scroll !important;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_footer_img_position = get_theme_mod('marketing_agency_footer_img_position','center center');
	if($marketing_agency_footer_img_position != false){
		$marketing_agency_custom_css .='#footer{';
			$marketing_agency_custom_css .='background-position: '.esc_attr($marketing_agency_footer_img_position).'!important;';
		$marketing_agency_custom_css .='}';
	} 

	/*-------------- Wocommerce Settings ----------------*/

	$marketing_agency_related_product_show_hide = get_theme_mod('marketing_agency_related_product_show_hide',true);
	if($marketing_agency_related_product_show_hide != true){
		$marketing_agency_custom_css .='.related.products{';
			$marketing_agency_custom_css .='display: none;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_products_btn_padding_top_bottom = get_theme_mod('marketing_agency_products_btn_padding_top_bottom');
	if($marketing_agency_products_btn_padding_top_bottom != false){
		$marketing_agency_custom_css .='.woocommerce a.button{';
			$marketing_agency_custom_css .='padding-top: '.esc_attr($marketing_agency_products_btn_padding_top_bottom).' !important; padding-bottom: '.esc_attr($marketing_agency_products_btn_padding_top_bottom).' !important;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_products_btn_padding_left_right = get_theme_mod('marketing_agency_products_btn_padding_left_right');
	if($marketing_agency_products_btn_padding_left_right != false){
		$marketing_agency_custom_css .='.woocommerce a.button{';
			$marketing_agency_custom_css .='padding-left: '.esc_attr($marketing_agency_products_btn_padding_left_right).' !important; padding-right: '.esc_attr($marketing_agency_products_btn_padding_left_right).' !important;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_woocommerce_sale_position = get_theme_mod( 'marketing_agency_woocommerce_sale_position','right');
    if($marketing_agency_woocommerce_sale_position == 'left'){
		$marketing_agency_custom_css .='.woocommerce ul.products li.product .onsale{';
			$marketing_agency_custom_css .='left: 10px; right: auto !important;';
		$marketing_agency_custom_css .='}';
	}else if($marketing_agency_woocommerce_sale_position == 'right'){
		$marketing_agency_custom_css .='.woocommerce ul.products li.product .onsale{';
			$marketing_agency_custom_css .='left: auto; right: 0;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_woocommerce_sale_font_size = get_theme_mod('marketing_agency_woocommerce_sale_font_size');
	if($marketing_agency_woocommerce_sale_font_size != false){
		$marketing_agency_custom_css .='.woocommerce span.onsale{';
			$marketing_agency_custom_css .='font-size: '.esc_attr($marketing_agency_woocommerce_sale_font_size).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_woocommerce_sale_padding_top_bottom = get_theme_mod('marketing_agency_woocommerce_sale_padding_top_bottom');
	if($marketing_agency_woocommerce_sale_padding_top_bottom != false){
		$marketing_agency_custom_css .='.woocommerce span.onsale{';
			$marketing_agency_custom_css .='padding-top: '.esc_attr($marketing_agency_woocommerce_sale_padding_top_bottom).'; padding-bottom: '.esc_attr($marketing_agency_woocommerce_sale_padding_top_bottom).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_woocommerce_sale_padding_left_right = get_theme_mod('marketing_agency_woocommerce_sale_padding_left_right');
	if($marketing_agency_woocommerce_sale_padding_left_right != false){
		$marketing_agency_custom_css .='.woocommerce span.onsale{';
			$marketing_agency_custom_css .='padding-left: '.esc_attr($marketing_agency_woocommerce_sale_padding_left_right).'; padding-right: '.esc_attr($marketing_agency_woocommerce_sale_padding_left_right).';';
		$marketing_agency_custom_css .='}';
	}

	/*------------------ Logo  -------------------*/

	$marketing_agency_logo_padding = get_theme_mod('marketing_agency_logo_padding');
	if($marketing_agency_logo_padding != false){
		$marketing_agency_custom_css .='.logo{';
			$marketing_agency_custom_css .='padding: '.esc_attr($marketing_agency_logo_padding).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_logo_margin = get_theme_mod('marketing_agency_logo_margin');
	if($marketing_agency_logo_margin != false){
		$marketing_agency_custom_css .='.logo{';
			$marketing_agency_custom_css .='margin: '.esc_attr($marketing_agency_logo_margin).';';
		$marketing_agency_custom_css .='}';
	}

	// Site title Font Size
	$marketing_agency_site_title_font_size = get_theme_mod('marketing_agency_site_title_font_size');
	if($marketing_agency_site_title_font_size != false){
		$marketing_agency_custom_css .='.logo p.site-title, .logo h1{';
			$marketing_agency_custom_css .='font-size: '.esc_attr($marketing_agency_site_title_font_size).';';
		$marketing_agency_custom_css .='}';
	}

	// Site tagline Font Size
	$marketing_agency_site_tagline_font_size = get_theme_mod('marketing_agency_site_tagline_font_size');
	if($marketing_agency_site_tagline_font_size != false){
		$marketing_agency_custom_css .='.logo p.site-description{';
			$marketing_agency_custom_css .='font-size: '.esc_attr($marketing_agency_site_tagline_font_size).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_site_title_color = get_theme_mod('marketing_agency_site_title_color');
	if($marketing_agency_site_title_color != false){
		$marketing_agency_custom_css .='p.site-title a{';
			$marketing_agency_custom_css .='color: '.esc_attr($marketing_agency_site_title_color).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_site_tagline_color = get_theme_mod('marketing_agency_site_tagline_color');
	if($marketing_agency_site_tagline_color != false){
		$marketing_agency_custom_css .='.logo p.site-description{';
			$marketing_agency_custom_css .='color: '.esc_attr($marketing_agency_site_tagline_color).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_logo_width = get_theme_mod('marketing_agency_logo_width');
	if($marketing_agency_logo_width != false){
		$marketing_agency_custom_css .='.logo img{';
			$marketing_agency_custom_css .='width: '.esc_attr($marketing_agency_logo_width).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_logo_height = get_theme_mod('marketing_agency_logo_height');
	if($marketing_agency_logo_height != false){
		$marketing_agency_custom_css .='.logo img{';
			$marketing_agency_custom_css .='height: '.esc_attr($marketing_agency_logo_height).';';
		$marketing_agency_custom_css .='}';
	}

	// Woocommerce img

	$marketing_agency_shop_featured_image_border_radius = get_theme_mod('marketing_agency_shop_featured_image_border_radius', 0);
	if($marketing_agency_shop_featured_image_border_radius != false){
		$marketing_agency_custom_css .='.woocommerce ul.products li.product a img{';
			$marketing_agency_custom_css .='border-radius: '.esc_attr($marketing_agency_shop_featured_image_border_radius).'px;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_shop_featured_image_box_shadow = get_theme_mod('marketing_agency_shop_featured_image_box_shadow');
	if($marketing_agency_shop_featured_image_box_shadow != false){
		$marketing_agency_custom_css .='.woocommerce ul.products li.product a img{';
				$marketing_agency_custom_css .='box-shadow: '.esc_attr($marketing_agency_shop_featured_image_box_shadow).'px '.esc_attr($marketing_agency_shop_featured_image_box_shadow).'px '.esc_attr($marketing_agency_shop_featured_image_box_shadow).'px #ddd;';
		$marketing_agency_custom_css .='}';
	}

	/*------------------ Preloader Background Color  -------------------*/

	$marketing_agency_preloader_bg_color = get_theme_mod('marketing_agency_preloader_bg_color');
	if($marketing_agency_preloader_bg_color != false){
		$marketing_agency_custom_css .='#preloader{';
			$marketing_agency_custom_css .='background-color: '.esc_attr($marketing_agency_preloader_bg_color).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_preloader_border_color = get_theme_mod('marketing_agency_preloader_border_color');
	if($marketing_agency_preloader_border_color != false){
		$marketing_agency_custom_css .='.loader-line{';
			$marketing_agency_custom_css .='border-color: '.esc_attr($marketing_agency_preloader_border_color).'!important;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_preloader_bg_img = get_theme_mod('marketing_agency_preloader_bg_img');
	if($marketing_agency_preloader_bg_img != false){
		$marketing_agency_custom_css .='#preloader{';
			$marketing_agency_custom_css .='background: url('.esc_attr($marketing_agency_preloader_bg_img).');-webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_header_first_color = get_theme_mod('marketing_agency_header_first_color');

	$marketing_agency_header_second_color = get_theme_mod('marketing_agency_header_second_color');

	if($marketing_agency_header_first_color != false || $marketing_agency_header_second_color != false){
		$marketing_agency_custom_css .='#header{
		background: linear-gradient(to right, '.esc_attr($marketing_agency_header_first_color).', '.esc_attr($marketing_agency_header_second_color).');
		}';
	}

	$marketing_agency_single_blog_post_navigation_show_hide = get_theme_mod('marketing_agency_single_blog_post_navigation_show_hide',true);
	if($marketing_agency_single_blog_post_navigation_show_hide != true){
		$marketing_agency_custom_css .='.post-navigation{';
			$marketing_agency_custom_css .='display: none;';
		$marketing_agency_custom_css .='}';
	}

	/*----------------Sroll to top Settings ------------------*/

	$marketing_agency_scroll_to_top_font_size = get_theme_mod('marketing_agency_scroll_to_top_font_size');
	if($marketing_agency_scroll_to_top_font_size != false){
		$marketing_agency_custom_css .='.scrollup i{';
			$marketing_agency_custom_css .='font-size: '.esc_attr($marketing_agency_scroll_to_top_font_size).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_scroll_to_top_padding = get_theme_mod('marketing_agency_scroll_to_top_padding');
	$marketing_agency_scroll_to_top_padding = get_theme_mod('marketing_agency_scroll_to_top_padding');
	if($marketing_agency_scroll_to_top_padding != false){
		$marketing_agency_custom_css .='.scrollup i{';
			$marketing_agency_custom_css .='padding-top: '.esc_attr($marketing_agency_scroll_to_top_padding).'!important;padding-bottom: '.esc_attr($marketing_agency_scroll_to_top_padding).'!important;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_scroll_to_top_width = get_theme_mod('marketing_agency_scroll_to_top_width');
	if($marketing_agency_scroll_to_top_width != false){
		$marketing_agency_custom_css .='.scrollup i{';
			$marketing_agency_custom_css .='width: '.esc_attr($marketing_agency_scroll_to_top_width).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_scroll_to_top_height = get_theme_mod('marketing_agency_scroll_to_top_height');
	if($marketing_agency_scroll_to_top_height != false){
		$marketing_agency_custom_css .='.scrollup i{';
			$marketing_agency_custom_css .='height: '.esc_attr($marketing_agency_scroll_to_top_height).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_scroll_to_top_border_radius = get_theme_mod('marketing_agency_scroll_to_top_border_radius');
	if($marketing_agency_scroll_to_top_border_radius != false){
		$marketing_agency_custom_css .='.scrollup i{';
			$marketing_agency_custom_css .='border-radius: '.esc_attr($marketing_agency_scroll_to_top_border_radius).'px;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_header_img_position = get_theme_mod('marketing_agency_header_img_position','center top');
	if($marketing_agency_header_img_position != false){
		$marketing_agency_custom_css .='#header{';
			$marketing_agency_custom_css .='background-position: '.esc_attr($marketing_agency_header_img_position).'!important;';
		$marketing_agency_custom_css .='}';
	}

	/*----------------Social Icons Settings ------------------*/

	$marketing_agency_social_icon_font_size = get_theme_mod('marketing_agency_social_icon_font_size');
	if($marketing_agency_social_icon_font_size != false){
		$marketing_agency_custom_css .='#sidebar .custom-social-icons i, #footer-2 .custom-social-icons i{';
			$marketing_agency_custom_css .='font-size: '.esc_attr($marketing_agency_social_icon_font_size).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_social_icon_padding = get_theme_mod('marketing_agency_social_icon_padding');
	if($marketing_agency_social_icon_padding != false){
		$marketing_agency_custom_css .='#sidebar .custom-social-icons i, #footer-2 .custom-social-icons i{';
			$marketing_agency_custom_css .='padding: '.esc_attr($marketing_agency_social_icon_padding).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_social_icon_width = get_theme_mod('marketing_agency_social_icon_width');
	if($marketing_agency_social_icon_width != false){
		$marketing_agency_custom_css .='#sidebar .custom-social-icons i, #footer-2 .custom-social-icons i{';
			$marketing_agency_custom_css .='width: '.esc_attr($marketing_agency_social_icon_width).';';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_social_icon_height = get_theme_mod('marketing_agency_social_icon_height');
	if($marketing_agency_social_icon_height != false){
		$marketing_agency_custom_css .='#sidebar .custom-social-icons i, #footer-2 .custom-social-icons i{';
			$marketing_agency_custom_css .='height: '.esc_attr($marketing_agency_social_icon_height).';';
		$marketing_agency_custom_css .='}';
	}

	/*---------------- Footer Settings ------------------*/

	$marketing_agency_button_footer_heading_letter_spacing = get_theme_mod('marketing_agency_button_footer_heading_letter_spacing',1);
	$marketing_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label, a.rsswidget.rss-widget-title{';
		$marketing_agency_custom_css .='letter-spacing: '.esc_attr($marketing_agency_button_footer_heading_letter_spacing).'px;';
	$marketing_agency_custom_css .='}';

	$marketing_agency_button_footer_font_size = get_theme_mod('marketing_agency_button_footer_font_size','25');
	$marketing_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label, a.rsswidget.rss-widget-title{';
		$marketing_agency_custom_css .='font-size: '.esc_attr($marketing_agency_button_footer_font_size).'px;';
	$marketing_agency_custom_css .='}';

	$marketing_agency_theme_lay = get_theme_mod( 'marketing_agency_button_footer_text_transform','Capitalize');
	if($marketing_agency_theme_lay == 'Capitalize'){
		$marketing_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label{';
			$marketing_agency_custom_css .='text-transform:Capitalize;';
		$marketing_agency_custom_css .='}';
	}
	if($marketing_agency_theme_lay == 'Lowercase'){
		$marketing_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label, a.rsswidget.rss-widget-title{';
			$marketing_agency_custom_css .='text-transform:Lowercase;';
		$marketing_agency_custom_css .='}';
	}
	if($marketing_agency_theme_lay == 'Uppercase'){
		$marketing_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label, a.rsswidget.rss-widget-title{';
			$marketing_agency_custom_css .='text-transform:Uppercase;';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_footer_heading_weight = get_theme_mod('marketing_agency_footer_heading_weight');
	if($marketing_agency_footer_heading_weight != false){
		$marketing_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label, a.rsswidget.rss-widget-title{';
			$marketing_agency_custom_css .='font-weight: '.esc_attr($marketing_agency_footer_heading_weight).';';
		$marketing_agency_custom_css .='}';
	}

	/*---------------------------Footer Style -------------------*/

	$marketing_agency_theme_lay = get_theme_mod( 'marketing_agency_footer_template','marketing-agency-footer-one');
    if($marketing_agency_theme_lay == 'marketing-agency-footer-one'){
		$marketing_agency_custom_css .='#footer{';
			$marketing_agency_custom_css .='';
		$marketing_agency_custom_css .='}';

	}else if($marketing_agency_theme_lay == 'marketing-agency-footer-two'){
		$marketing_agency_custom_css .='#footer{';
			$marketing_agency_custom_css .='background: linear-gradient(to right, #f9f8ff, #dedafa);';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='#footer p, #footer li a, #footer, #footer h3, #footer a.rsswidget, #footer #wp-calendar a, .copyright a, #footer .custom_details, #footer ins span, #footer .tagcloud a, .main-inner-box span.entry-date a, nav.woocommerce-MyAccount-navigation ul li:hover a, #footer ul li a, #footer table, #footer th, #footer td, #footer caption, #sidebar caption,#footer nav.wp-calendar-nav a,#footer .search-form .search-field{';
			$marketing_agency_custom_css .='color:#000;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='#footer ul li::before{';
			$marketing_agency_custom_css .='background:#000;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='#footer table, #footer th, #footer td,#footer .search-form .search-field,#footer .tagcloud a{';
			$marketing_agency_custom_css .='border: 1px solid #000;';
		$marketing_agency_custom_css .='}';

	}else if($marketing_agency_theme_lay == 'marketing-agency-footer-three'){
		$marketing_agency_custom_css .='#footer{';
			$marketing_agency_custom_css .='background: #232524;';
		$marketing_agency_custom_css .='}';
	}
	else if($marketing_agency_theme_lay == 'marketing-agency-footer-four'){
		$marketing_agency_custom_css .='#footer{';
			$marketing_agency_custom_css .='background: #f7f7f7;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='#footer p, #footer li a, #footer, #footer h3, #footer a.rsswidget, #footer #wp-calendar a, .copyright a, #footer .custom_details, #footer ins span, #footer .tagcloud a, .main-inner-box span.entry-date a, nav.woocommerce-MyAccount-navigation ul li:hover a, #footer ul li a, #footer table, #footer th, #footer td, #footer caption, #sidebar caption,#footer nav.wp-calendar-nav a,#footer .search-form .search-field{';
			$marketing_agency_custom_css .='color:#000;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='#footer ul li::before{';
			$marketing_agency_custom_css .='background:#000;';
		$marketing_agency_custom_css .='}';
		$marketing_agency_custom_css .='#footer table, #footer th, #footer td,#footer .search-form .search-field,#footer .tagcloud a{';
			$marketing_agency_custom_css .='border: 1px solid #000;';
		$marketing_agency_custom_css .='}';
	}
	else if($marketing_agency_theme_lay == 'marketing-agency-footer-five'){
		$marketing_agency_custom_css .='#footer{';
			$marketing_agency_custom_css .='background: linear-gradient(to right, #01093a, #2d0b00);';
		$marketing_agency_custom_css .='}';
	}

	$marketing_agency_responsive_preloader_hide = get_theme_mod('marketing_agency_responsive_preloader_hide',false);
	if($marketing_agency_responsive_preloader_hide == true && get_theme_mod('marketing_agency_loader_enable',false) == false){
		$marketing_agency_custom_css .='@media screen and (min-width:575px){
			#preloader{';
			$marketing_agency_custom_css .='display:none !important;';
		$marketing_agency_custom_css .='} }';
	}

	if($marketing_agency_responsive_preloader_hide == false){
		$marketing_agency_custom_css .='@media screen and (max-width:575px){
			#preloader{';
			$marketing_agency_custom_css .='display:none !important;';
		$marketing_agency_custom_css .='} }';
	}

	$marketing_agency_bradcrumbs_alignment = get_theme_mod( 'marketing_agency_bradcrumbs_alignment','Left');
    if($marketing_agency_bradcrumbs_alignment == 'Left'){
    	$marketing_agency_custom_css .='@media screen and (min-width:768px) {';
		$marketing_agency_custom_css .='.bradcrumbs{';
			$marketing_agency_custom_css .='text-align:start;';
		$marketing_agency_custom_css .='}}';
	}else if($marketing_agency_bradcrumbs_alignment == 'Center'){
		$marketing_agency_custom_css .='@media screen and (min-width:768px) {';
		$marketing_agency_custom_css .='.bradcrumbs{';
			$marketing_agency_custom_css .='text-align:center;';
		$marketing_agency_custom_css .='}}';
	}else if($marketing_agency_bradcrumbs_alignment == 'Right'){
		$marketing_agency_custom_css .='@media screen and (min-width:768px) {';
		$marketing_agency_custom_css .='.bradcrumbs{';
			$marketing_agency_custom_css .='text-align:end;';
		$marketing_agency_custom_css .='}}';
	}