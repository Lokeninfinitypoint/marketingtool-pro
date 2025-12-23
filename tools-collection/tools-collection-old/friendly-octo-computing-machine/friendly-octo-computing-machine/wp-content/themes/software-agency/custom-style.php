<?php
	/*-----------------------First highlight color-------------------*/

	$software_agency_first_color = get_theme_mod('software_agency_first_color');

	$software_agency_custom_css= "";

	if($software_agency_first_color != false){
		$software_agency_custom_css .='#preloader{';
			$software_agency_custom_css .='background-color: '.esc_attr($software_agency_first_color).';';
		$software_agency_custom_css .='}';
	}

	if($software_agency_first_color != false){
		$software_agency_custom_css .='.post-main-box:hover h2 a, .post-main-box:hover .post-info a, .post-info:hover a, .middle-bar strong a:hover, .logo .site-title a:hover, #slider .carousel-caption h1 a:hover, .top-bar a:hover, .copyright a:hover{';
			$software_agency_custom_css .='color: '.esc_attr($software_agency_first_color).'!important;';
		$software_agency_custom_css .='}';
	}

	if($software_agency_first_color != false){
		$software_agency_custom_css .='.woocommerce ul.products li.product a img{';
			$software_agency_custom_css .='border-color: '.esc_attr($software_agency_first_color).';';
		$software_agency_custom_css .='}';
	}

	/*---------------- Second highlight color-------------------*/

	$software_agency_second_color = get_theme_mod('software_agency_second_color');

	if($software_agency_second_color != false){
		$software_agency_custom_css .='a{';
			$software_agency_custom_css .='color: '.esc_attr($software_agency_second_color).';';
		$software_agency_custom_css .='}';
	}

	if($software_agency_first_color != false || $software_agency_second_color != false){
		$software_agency_custom_css .='.custom-social-icons i:hover,.main-navigation .current_page_item > a, .main-navigation .current-menu-item > a, .main-navigation .current_page_ancestor > a, .main-navigation a:hover, .more-btn a,#comments input[type="submit"],#comments a.comment-reply-link,input[type="submit"],.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button,.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt,nav.woocommerce-MyAccount-navigation ul li, .scrollup i, .pagination span, .pagination a, .widget_product_search button, .toggle-nav i, .woocommerce ul.products li.product:hover span.onsale, #footer .wp-block-search .wp-block-search__button, #sidebar .wp-block-search .wp-block-search__button, .bradcrumbs a:hover, .bradcrumbs span,.post-categories li a:hover, nav.navigation.posts-navigation .nav-previous a, nav.navigation.posts-navigation .nav-next a, .wp-block-button__link,.wp-block-woocommerce-cart .wc-block-cart__submit-button, .wc-block-components-checkout-place-order-button, .wc-block-components-totals-coupon__button, .wc-block-components-order-summary-item__quantity,a.added_to_cart.wc-forward,a.wc-block-components-checkout-return-to-cart-button	{
		background: linear-gradient(to right, '.esc_attr($software_agency_first_color).', '.esc_attr($software_agency_second_color).') !important;
		}';
	}

	/*---------------- Third highlight color-------------------*/

	$software_agency_third_color = get_theme_mod('software_agency_third_color');

	if($software_agency_third_color != false){
		$software_agency_custom_css .='.slide-search input[type="submit"],.get-start-btn, #footer-2,.main-inner-box span.entry-date,.more-btn a:hover,input[type="submit"]:hover,#comments input[type="submit"]:hover,#comments a.comment-reply-link:hover,#slider .carousel-control-prev-icon:hover, #slider .carousel-control-next-icon:hover,#sidebar h3, #sidebar .wp-block-search .wp-block-search__label, .pagination .current,.pagination a:hover,#footer .tagcloud a:hover,#sidebar .tagcloud a:hover,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover,.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,.woocommerce span.onsale,.widget_product_search button:hover, nav.navigation.posts-navigation .nav-previous a:hover, nav.navigation.posts-navigation .nav-next a:hover, .wp-block-tag-cloud a:hover, #sidebar .wp-block-heading, .wp-block-button .wp-block-button__link:hover{';
			$software_agency_custom_css .='background-color: '.esc_attr($software_agency_third_color).';';
		$software_agency_custom_css .='}';
	}

	if($software_agency_third_color != false){
		$software_agency_custom_css .='#footer .textwidget a,#footer li a:hover,.post-main-box:hover h3 a,#sidebar ul li a:hover,.post-navigation a:hover .post-title, .post-navigation a:focus .post-title,.post-navigation a:hover,.post-navigation a:focus, nav.woocommerce-MyAccount-navigation ul li a:hover, .get-start-btn a:hover{';
			$software_agency_custom_css .='color: '.esc_attr($software_agency_third_color).';';
		$software_agency_custom_css .='}';
	}

	/*---------------- Fourth highlight color-------------------*/

	$software_agency_fourth_color = get_theme_mod('software_agency_fourth_color');

	if($software_agency_fourth_color != false){
		$software_agency_custom_css .='#slider .carousel-control-prev-icon,  #slider .carousel-control-next-icon, #footer, .get-start-btn:hover, .slide-search input[type="submit"]:hover{';
			$software_agency_custom_css .='background-color: '.esc_attr($software_agency_fourth_color).';';
		$software_agency_custom_css .='}';
	}

	if($software_agency_fourth_color != false){
		$software_agency_custom_css .='h1,h2,h3,h4,h5,h6, .slide-search input[type="submit"],p.site-title a, .logo h1 a, p.site-description,.middle-bar strong,.middle-bar i.fas.fa-phone,.middle-bar i.fas.fa-envelope-open,.get-start-btn a,span.cart_no i,.more-btn a:hover,input[type="submit"]:hover,#comments input[type="submit"]:hover,#comments a.comment-reply-link:hover,#slider .carousel-control-prev-icon:hover, #slider .carousel-control-next-icon:hover,#sidebar h3, #sidebar .wp-block-search .wp-block-search__label, .copyright p a,.pagination .current,.pagination a:hover,#footer .tagcloud a:hover,#sidebar h3 a.rsswidget,#sidebar .tagcloud a:hover,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover,.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,.woocommerce span.onsale,.widget_product_search button:hover, #slider .carousel-control-next-icon:hover,#slider .carousel-control-prev-icon:hover,#about-us strong, #slider .carousel-caption h1 a, .post-main-box h2 a,.post-info span a, .woocommerce div.product .product_title, .woocommerce div.product p.price, .woocommerce div.product span.price,.woocommerce ul.products li.product .price, .woocommerce .quantity .qty, .copyright p{';
			$software_agency_custom_css .='color: '.esc_attr($software_agency_fourth_color).';';
		$software_agency_custom_css .='}';
	}

	if($software_agency_fourth_color != false){
		$software_agency_custom_css .='.wp-block-button .wp-block-button__link:hover{';
			$software_agency_custom_css .='color: '.esc_attr($software_agency_fourth_color).'!important;';
		$software_agency_custom_css .='}';
	}

	if($software_agency_fourth_color != false){
		$software_agency_custom_css .='.woocommerce .quantity .qty{';
			$software_agency_custom_css .='border-color: '.esc_attr($software_agency_fourth_color).';';
		$software_agency_custom_css .='}';
	}

	/*---------------------------Width Layout -------------------*/

	$software_agency_theme_lay = get_theme_mod( 'software_agency_width_option','Full Width');
    if($software_agency_theme_lay == 'Boxed'){
		$software_agency_custom_css .='body{';
			$software_agency_custom_css .='max-width: 1140px; width: 100%; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto;';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='#slider .carousel-caption h1 a{';
			$software_agency_custom_css .='font-size: 50px;';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='#slider .carousel-control-prev, #slider .carousel-control-next{';
			$software_agency_custom_css .='bottom: 20px;';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='.scrollup i{';
		  $software_agency_custom_css .='right: 100px;';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='.scrollup.left i{';
		  $software_agency_custom_css .='left: 100px;';
		$software_agency_custom_css .='}';
	}else if($software_agency_theme_lay == 'Wide Width'){
		$software_agency_custom_css .='body{';
			$software_agency_custom_css .='width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='.scrollup i{';
		  $software_agency_custom_css .='right: 30px;';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='.scrollup.left i{';
		  $software_agency_custom_css .='left: 30px;';
		$software_agency_custom_css .='}';
	}else if($software_agency_theme_lay == 'Full Width'){
		$software_agency_custom_css .='body{';
			$software_agency_custom_css .='max-width: 100%;';
		$software_agency_custom_css .='}';
	}

	/*--------------------- Slider Content Layout -------------------*/

	$software_agency_theme_lay = get_theme_mod( 'software_agency_slider_content_option','Left');
    if($software_agency_theme_lay == 'Left'){
		$software_agency_custom_css .='#slider .carousel-caption{';
			$software_agency_custom_css .='text-align:left; right: 20%;';
		$software_agency_custom_css .='}';
	}else if($software_agency_theme_lay == 'Center'){
		$software_agency_custom_css .='#slider .carousel-caption{';
			$software_agency_custom_css .='text-align:center; right: 10%; left: 10%;';
		$software_agency_custom_css .='}';
	}else if($software_agency_theme_lay == 'Right'){
		$software_agency_custom_css .='#slider .carousel-caption{';
			$software_agency_custom_css .='text-align:right; right: 10%; left: 20%;';
		$software_agency_custom_css .='}';
	}

	/*------------- Slider Content Padding Settings ------------------*/

	$software_agency_slider_content_padding_top_bottom = get_theme_mod('software_agency_slider_content_padding_top_bottom');
	$software_agency_slider_content_padding_left_right = get_theme_mod('software_agency_slider_content_padding_left_right');
	if($software_agency_slider_content_padding_top_bottom != false || $software_agency_slider_content_padding_left_right != false){
		$software_agency_custom_css .='#slider .carousel-caption{';
			$software_agency_custom_css .='top: '.esc_attr($software_agency_slider_content_padding_top_bottom).'; bottom: '.esc_attr($software_agency_slider_content_padding_top_bottom).';left: '.esc_attr($software_agency_slider_content_padding_left_right).';right: '.esc_attr($software_agency_slider_content_padding_left_right).';';
		$software_agency_custom_css .='}';
	}

	/*---------------------------Blog Layout -------------------*/

	$software_agency_theme_lay = get_theme_mod( 'software_agency_blog_layout_option','Default');
    if($software_agency_theme_lay == 'Default'){
		$software_agency_custom_css .='.post-main-box{';
			$software_agency_custom_css .='';
		$software_agency_custom_css .='}';
	}else if($software_agency_theme_lay == 'Center'){
		$software_agency_custom_css .='.post-main-box, .post-main-box h2, .post-info, .new-text p{';
			$software_agency_custom_css .='text-align:center;';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='.post-info{';
			$software_agency_custom_css .='margin-top:10px;';
		$software_agency_custom_css .='}';
	}else if($software_agency_theme_lay == 'Left'){
		$software_agency_custom_css .='.post-main-box, .post-main-box h2, .post-info, .new-text p, #our-services p{';
			$software_agency_custom_css .='text-align:Left;';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='.post-main-box h2{';
			$software_agency_custom_css .='margin-top:10px;';
		$software_agency_custom_css .='}';
	}

	/*--------------------- Blog Page Posts -------------------*/

	$software_agency_blog_page_posts_settings = get_theme_mod( 'software_agency_blog_page_posts_settings','Into Blocks');
    if($software_agency_blog_page_posts_settings == 'Without Blocks'){
		$software_agency_custom_css .='.post-main-box{';
			$software_agency_custom_css .='box-shadow: none; border: none; margin:30px 0;';
		$software_agency_custom_css .='}';
	}

	/*--------------------- Grid Posts -------------------*/

	$software_agency_display_grid_posts_settings = get_theme_mod( 'software_agency_display_grid_posts_settings','Into Blocks');
    if($software_agency_display_grid_posts_settings == 'Without Blocks'){
		$software_agency_custom_css .='.grid-post-main-box{';
			$software_agency_custom_css .='box-shadow: none; border: none; margin:30px 0; background: none;';
		$software_agency_custom_css .='}';
	}

	$software_agency_grid_featured_image_box_shadow = get_theme_mod('software_agency_grid_featured_image_box_shadow',0);
	if($software_agency_grid_featured_image_box_shadow != false){
		$software_agency_custom_css .='.grid-post-main-box .box-image img, .grid-post-main-box .feature-box img, #content-vw img{';
			$software_agency_custom_css .='box-shadow: '.esc_attr($software_agency_grid_featured_image_box_shadow).'px '.esc_attr($software_agency_grid_featured_image_box_shadow).'px '.esc_attr($software_agency_grid_featured_image_box_shadow).'px #cccccc;';
		$software_agency_custom_css .='}';
	}

	$software_agency_grid_featured_image_border_radius = get_theme_mod('software_agency_grid_featured_image_border_radius', 0);
	if($software_agency_grid_featured_image_border_radius != false){
		$software_agency_custom_css .='.grid-post-main-box .box-image img, .grid-post-main-box .feature-box img, #content-vw img{';
			$software_agency_custom_css .='border-radius: '.esc_attr($software_agency_grid_featured_image_border_radius).'px;';
		$software_agency_custom_css .='}';
	}

	// featured image dimention
	$software_agency_blog_post_featured_image_dimension = get_theme_mod('software_agency_blog_post_featured_image_dimension', 'default');
	$software_agency_blog_post_featured_image_custom_width = get_theme_mod('software_agency_blog_post_featured_image_custom_width',250);
	$software_agency_blog_post_featured_image_custom_height = get_theme_mod('software_agency_blog_post_featured_image_custom_height',250);
	if($software_agency_blog_post_featured_image_dimension == 'custom'){
		$software_agency_custom_css .='.post-main-box img{';
			$software_agency_custom_css .='width: '.esc_attr($software_agency_blog_post_featured_image_custom_width).'; height: '.esc_attr($software_agency_blog_post_featured_image_custom_height).';';
		$software_agency_custom_css .='}';
	}

	/*----------------Responsive Media -----------------------*/

	$software_agency_resp_slider = get_theme_mod( 'software_agency_resp_slider_hide_show',true);
	if($software_agency_resp_slider == true && get_theme_mod( 'software_agency_slider_arrows', true) == false){
    	$software_agency_custom_css .='#slider{';
			$software_agency_custom_css .='display:none;';
		$software_agency_custom_css .='} ';
	}
    if($software_agency_resp_slider == true){
    	$software_agency_custom_css .='@media screen and (max-width:575px) {';
		$software_agency_custom_css .='#slider{';
			$software_agency_custom_css .='display:block;';
		$software_agency_custom_css .='} }';
	}else if($software_agency_resp_slider == false){
		$software_agency_custom_css .='@media screen and (max-width:575px) {';
		$software_agency_custom_css .='#slider{';
			$software_agency_custom_css .='display:none;';
		$software_agency_custom_css .='} }';
	}

	$software_agency_resp_sidebar = get_theme_mod( 'software_agency_sidebar_hide_show',true);
    if($software_agency_resp_sidebar == true){
    	$software_agency_custom_css .='@media screen and (max-width:575px) {';
		$software_agency_custom_css .='#sidebar{';
			$software_agency_custom_css .='display:block;';
		$software_agency_custom_css .='} }';
	}else if($software_agency_resp_sidebar == false){
		$software_agency_custom_css .='@media screen and (max-width:575px) {';
		$software_agency_custom_css .='#sidebar{';
			$software_agency_custom_css .='display:none;';
		$software_agency_custom_css .='} }';
	}

	$software_agency_resp_scroll_top = get_theme_mod( 'software_agency_resp_scroll_top_hide_show',true);
	if($software_agency_resp_scroll_top == true && get_theme_mod( 'software_agency_footer_scroll',true) != true){
    	$software_agency_custom_css .='.scrollup i{';
			$software_agency_custom_css .='visibility:hidden !important;';
		$software_agency_custom_css .='} ';
	}
    if($software_agency_resp_scroll_top == true){
    	$software_agency_custom_css .='@media screen and (max-width:575px) {';
		$software_agency_custom_css .='.scrollup i{';
			$software_agency_custom_css .='visibility:visible !important;';
		$software_agency_custom_css .='} }';
	}else if($software_agency_resp_scroll_top == false){
		$software_agency_custom_css .='@media screen and (max-width:575px){';
		$software_agency_custom_css .='.scrollup i{';
			$software_agency_custom_css .='visibility:hidden !important;';
		$software_agency_custom_css .='} }';
	}

	$software_agency_resp_menu_toggle_btn_bg_color = get_theme_mod('software_agency_resp_menu_toggle_btn_bg_color');
	if($software_agency_resp_menu_toggle_btn_bg_color != false){
		$software_agency_custom_css .='.toggle-nav i,.sidenav .closebtn{';
			$software_agency_custom_css .='background: '.esc_attr($software_agency_resp_menu_toggle_btn_bg_color).';';
		$software_agency_custom_css .='}';
	}

	/*---------------- Menus Settings ------------------*/

	$software_agency_navigation_menu_font_size = get_theme_mod('software_agency_navigation_menu_font_size');
	if($software_agency_navigation_menu_font_size != false){
		$software_agency_custom_css .='.main-navigation a{';
			$software_agency_custom_css .='font-size: '.esc_attr($software_agency_navigation_menu_font_size).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_header_menus_color = get_theme_mod('software_agency_header_menus_color');
	if($software_agency_header_menus_color != false){
		$software_agency_custom_css .='.main-navigation a{';
			$software_agency_custom_css .='color: '.esc_attr($software_agency_header_menus_color).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_header_menus_hover_color = get_theme_mod('software_agency_header_menus_hover_color');
	if($software_agency_header_menus_hover_color != false){
		$software_agency_custom_css .='.main-navigation a:hover{';
			$software_agency_custom_css .='color: '.esc_attr($software_agency_header_menus_hover_color).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_header_submenus_color = get_theme_mod('software_agency_header_submenus_color');
	if($software_agency_header_submenus_color != false){
		$software_agency_custom_css .='.main-navigation ul ul a{';
			$software_agency_custom_css .='color: '.esc_attr($software_agency_header_submenus_color).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_header_submenus_hover_color = get_theme_mod('software_agency_header_submenus_hover_color');
	if($software_agency_header_submenus_hover_color != false){
		$software_agency_custom_css .='.main-navigation ul.sub-menu a:hover{';
			$software_agency_custom_css .='color: '.esc_attr($software_agency_header_submenus_hover_color).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_menus_item = get_theme_mod( 'software_agency_menus_item_style','None');
    if($software_agency_menus_item == 'None'){
		$software_agency_custom_css .='.main-navigation a{';
			$software_agency_custom_css .='';
		$software_agency_custom_css .='}';
	}else if($software_agency_menus_item == 'Zoom In'){
		$software_agency_custom_css .='.main-navigation a:hover{';
			$software_agency_custom_css .='transition: all 0.3s ease-in-out !important; transform: scale(1.2) !important; color: #fff;';
		$software_agency_custom_css .='}';
	}

	$software_agency_navigation_menu_font_weight = get_theme_mod('software_agency_navigation_menu_font_weight','600');
	if($software_agency_navigation_menu_font_weight != false){
		$software_agency_custom_css .='.main-navigation a{';
			$software_agency_custom_css .='font-weight: '.esc_attr($software_agency_navigation_menu_font_weight).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_theme_lay = get_theme_mod( 'software_agency_menu_text_transform','Capitalize');
	if($software_agency_theme_lay == 'Capitalize'){
		$software_agency_custom_css .='.main-navigation a{';
			$software_agency_custom_css .='text-transform:Capitalize;';
		$software_agency_custom_css .='}';
	}
	if($software_agency_theme_lay == 'Lowercase'){
		$software_agency_custom_css .='.main-navigation a{';
			$software_agency_custom_css .='text-transform:Lowercase;';
		$software_agency_custom_css .='}';
	}
	if($software_agency_theme_lay == 'Uppercase'){
		$software_agency_custom_css .='.main-navigation a{';
			$software_agency_custom_css .='text-transform:Uppercase;';
		$software_agency_custom_css .='}';
	}

	/*---------------- Button Settings ------------------*/

	$software_agency_button_border_radius = get_theme_mod('software_agency_button_border_radius');
	if($software_agency_button_border_radius != false){
		$software_agency_custom_css .='.post-main-box .more-btn a{';
			$software_agency_custom_css .='border-radius: '.esc_attr($software_agency_button_border_radius).'px;';
		$software_agency_custom_css .='}';
	}

	$software_agency_button_padding_top_bottom = get_theme_mod('software_agency_button_padding_top_bottom');
	$software_agency_button_padding_left_right = get_theme_mod('software_agency_button_padding_left_right');
	if($software_agency_button_padding_top_bottom != false || $software_agency_button_padding_left_right != false){
		$software_agency_custom_css .='.post-main-box .more-btn a{';
			$software_agency_custom_css .='padding-top: '.esc_attr($software_agency_button_padding_top_bottom).'!important;
			padding-bottom: '.esc_attr($software_agency_button_padding_top_bottom).'!important;
			padding-left: '.esc_attr($software_agency_button_padding_left_right).'!important;
			padding-right: '.esc_attr($software_agency_button_padding_left_right).'!important;';
		$software_agency_custom_css .='}';
	}

	$software_agency_button_font_size = get_theme_mod('software_agency_button_font_size',14);
	$software_agency_custom_css .='.post-main-box .more-btn a{';
		$software_agency_custom_css .='font-size: '.esc_attr($software_agency_button_font_size).';';
	$software_agency_custom_css .='}';

	$software_agency_theme_lay = get_theme_mod( 'software_agency_button_text_transform','Uppercase');
	if($software_agency_theme_lay == 'Capitalize'){
		$software_agency_custom_css .='.post-main-box .more-btn a{';
			$software_agency_custom_css .='text-transform:Capitalize;';
		$software_agency_custom_css .='}';
	}
	if($software_agency_theme_lay == 'Lowercase'){
		$software_agency_custom_css .='.post-main-box .more-btn a{';
			$software_agency_custom_css .='text-transform:Lowercase;';
		$software_agency_custom_css .='}';
	}
	if($software_agency_theme_lay == 'Uppercase'){
		$software_agency_custom_css .='.post-main-box .more-btn a{';
			$software_agency_custom_css .='text-transform:Uppercase;';
		$software_agency_custom_css .='}';
	}

	$software_agency_button_letter_spacing = get_theme_mod('software_agency_button_letter_spacing');
	$software_agency_custom_css .='.post-main-box .more-btn a{';
		$software_agency_custom_css .='letter-spacing: '.esc_attr($software_agency_button_letter_spacing).';';
	$software_agency_custom_css .='}';

	/*---------------- Single Blog Page Settings ------------------*/

	$software_agency_single_blog_comment_title = get_theme_mod('software_agency_single_blog_comment_title', 'Leave a Reply');
	if($software_agency_single_blog_comment_title == ''){
		$software_agency_custom_css .='#comments h2#reply-title {';
			$software_agency_custom_css .='display: none;';
		$software_agency_custom_css .='}';
	}

	$software_agency_single_blog_comment_button_text = get_theme_mod('software_agency_single_blog_comment_button_text', 'Post Comment');
	if($software_agency_single_blog_comment_button_text == ''){
		$software_agency_custom_css .='#comments p.form-submit {';
			$software_agency_custom_css .='display: none;';
		$software_agency_custom_css .='}';
	}

	$software_agency_comment_width = get_theme_mod('software_agency_single_blog_comment_width');
	if($software_agency_comment_width != false){
		$software_agency_custom_css .='#comments textarea{';
			$software_agency_custom_css .='width: '.esc_attr($software_agency_comment_width).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_single_blog_post_navigation_show_hide = get_theme_mod('software_agency_single_blog_post_navigation_show_hide',true);
	if($software_agency_single_blog_post_navigation_show_hide != true){
		$software_agency_custom_css .='.post-navigation{';
			$software_agency_custom_css .='display: none;';
		$software_agency_custom_css .='}';
	}

	$software_agency_singlepost_image_box_shadow = get_theme_mod('software_agency_singlepost_image_box_shadow',0);
	if($software_agency_singlepost_image_box_shadow != false){
		$software_agency_custom_css .='.feature-box img{';
			$software_agency_custom_css .='box-shadow: '.esc_attr($software_agency_singlepost_image_box_shadow).'px '.esc_attr($software_agency_singlepost_image_box_shadow).'px '.esc_attr($software_agency_singlepost_image_box_shadow).'px #cccccc;';
		$software_agency_custom_css .='}';
	}

	$software_agency_related_image_box_shadow = get_theme_mod('software_agency_related_image_box_shadow',0);
	if($software_agency_related_image_box_shadow != false){
		$software_agency_custom_css .='.related-post .box-image img{';
			$software_agency_custom_css .='box-shadow: '.esc_attr($software_agency_related_image_box_shadow).'px '.esc_attr($software_agency_related_image_box_shadow).'px '.esc_attr($software_agency_related_image_box_shadow).'px #cccccc;';
		$software_agency_custom_css .='}';
	}

	/*-------------- Copyright Alignment ----------------*/

	$software_agency_copyright_background_color = get_theme_mod('software_agency_copyright_background_color');
	if($software_agency_copyright_background_color != false){
		$software_agency_custom_css .='#footer-2{';
			$software_agency_custom_css .='background-color: '.esc_attr($software_agency_copyright_background_color).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_footer_background_color = get_theme_mod('software_agency_footer_background_color');
	if($software_agency_footer_background_color != false){
		$software_agency_custom_css .='#footer{';
			$software_agency_custom_css .='background-color: '.esc_attr($software_agency_footer_background_color).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_footer_widgets_heading = get_theme_mod( 'software_agency_footer_widgets_heading','Left');
    if($software_agency_footer_widgets_heading == 'Left'){
		$software_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label{';
		$software_agency_custom_css .='text-align: left;';
		$software_agency_custom_css .='}';
	}else if($software_agency_footer_widgets_heading == 'Center'){
		$software_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label{';
			$software_agency_custom_css .='text-align: center;';
		$software_agency_custom_css .='}';
	}else if($software_agency_footer_widgets_heading == 'Right'){
		$software_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label{';
			$software_agency_custom_css .='text-align: right;';
		$software_agency_custom_css .='}';
	}

	$software_agency_footer_widgets_content = get_theme_mod( 'software_agency_footer_widgets_content','Left');
    if($software_agency_footer_widgets_content == 'Left'){
		$software_agency_custom_css .='#footer .widget{';
		$software_agency_custom_css .='text-align: left;';
		$software_agency_custom_css .='}';
	}else if($software_agency_footer_widgets_content == 'Center'){
		$software_agency_custom_css .='#footer .widget{';
			$software_agency_custom_css .='text-align: center;';
		$software_agency_custom_css .='}';
	}else if($software_agency_footer_widgets_content == 'Right'){
		$software_agency_custom_css .='#footer .widget{';
			$software_agency_custom_css .='text-align: right;';
		$software_agency_custom_css .='}';
	}

	$software_agency_copyright_font_size = get_theme_mod('software_agency_copyright_font_size');
	if($software_agency_copyright_font_size != false){
		$software_agency_custom_css .='.copyright p{';
			$software_agency_custom_css .='font-size: '.esc_attr($software_agency_copyright_font_size).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_resp_stickycopyright = get_theme_mod( 'software_agency_stickycopyright_hide_show',false);
	if($software_agency_resp_stickycopyright == true && get_theme_mod( 'software_agency_copyright_sticky',false) != true){
    	$software_agency_custom_css .='.copyright-sticky{';
			$software_agency_custom_css .='position:static;';
		$software_agency_custom_css .='} ';
	}

	$software_agency_footer_social_icons_font_size = get_theme_mod('software_agency_footer_social_icons_font_size','16');
	$software_agency_custom_css .='.copyright .widget i{';
		$software_agency_custom_css .='font-size: '.esc_attr($software_agency_footer_social_icons_font_size).'px;';
	$software_agency_custom_css .='}';

	/*-------------- Copyright Alignment ----------------*/
	$software_agency_copyright_alingment = get_theme_mod('software_agency_copyright_alingment');
	if($software_agency_copyright_alingment != false){
		$software_agency_custom_css .='.copyright p,#footer-2 p{';
			$software_agency_custom_css .='text-align: '.esc_attr($software_agency_copyright_alingment).';';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='
		@media screen and (max-width:720px) {
			.copyright p,#footer-2 p{';
			$software_agency_custom_css .='text-align: center;} }';
	}

	$software_agency_align_footer_social_icon = get_theme_mod('software_agency_align_footer_social_icon');
	if($software_agency_align_footer_social_icon != false){
		$software_agency_custom_css .='.copyright .widget{';
			$software_agency_custom_css .='text-align: '.esc_attr($software_agency_align_footer_social_icon).';';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='
		@media screen and (max-width:720px) {
			.copyright .widget{';
			$software_agency_custom_css .='text-align: center;} }';
	}

	$software_agency_footer_padding = get_theme_mod('software_agency_footer_padding');
	if($software_agency_footer_padding != false){
		$software_agency_custom_css .='#footer{';
			$software_agency_custom_css .='padding: '.esc_attr($software_agency_footer_padding).' 0;';
		$software_agency_custom_css .='}';
	}

	$software_agency_footer_icon = get_theme_mod('software_agency_footer_icon');
	if($software_agency_footer_icon == false){
		$software_agency_custom_css .='.copyright p{';
			$software_agency_custom_css .='width:100%; text-align:center; float:none;';
		$software_agency_custom_css .='}';
	}

	$software_agency_footer_background_image = get_theme_mod('software_agency_footer_background_image');
	if($software_agency_footer_background_image != false){
		$software_agency_custom_css .='#footer{';
			$software_agency_custom_css .='background: url('.esc_attr($software_agency_footer_background_image).');background-size:cover;';
		$software_agency_custom_css .='}';
	}

	$software_agency_theme_lay = get_theme_mod( 'software_agency_img_footer','scroll');
	if($software_agency_theme_lay == 'fixed'){
		$software_agency_custom_css .='#footer{';
			$software_agency_custom_css .='background-attachment: fixed !important;';
		$software_agency_custom_css .='}';
	}elseif ($software_agency_theme_lay == 'scroll'){
		$software_agency_custom_css .='#footer{';
			$software_agency_custom_css .='background-attachment: scroll !important;';
		$software_agency_custom_css .='}';
	}

	$software_agency_footer_img_position = get_theme_mod('software_agency_footer_img_position','center center');
	if($software_agency_footer_img_position != false){
		$software_agency_custom_css .='#footer{';
			$software_agency_custom_css .='background-position: '.esc_attr($software_agency_footer_img_position).'!important;';
		$software_agency_custom_css .='}';
	} 
	/*---------------------------Slider Height ------------*/

	$software_agency_slider_height = get_theme_mod('software_agency_slider_height');
	if($software_agency_slider_height != false){
		$software_agency_custom_css .='#slider img{';
			$software_agency_custom_css .='height: '.esc_attr($software_agency_slider_height).';';
		$software_agency_custom_css .='}';
	}

	/*-------------- Woocommerce Settings ----------------*/

	$software_agency_related_product_show_hide = get_theme_mod('software_agency_related_product_show_hide',true);
	if($software_agency_related_product_show_hide != true){
		$software_agency_custom_css .='.related.products{';
			$software_agency_custom_css .='display: none;';
		$software_agency_custom_css .='}';
	}

	$software_agency_products_btn_padding_top_bottom = get_theme_mod('software_agency_products_btn_padding_top_bottom');
	if($software_agency_products_btn_padding_top_bottom != false){
		$software_agency_custom_css .='.woocommerce a.button{';
			$software_agency_custom_css .='padding-top: '.esc_attr($software_agency_products_btn_padding_top_bottom).' !important; padding-bottom: '.esc_attr($software_agency_products_btn_padding_top_bottom).' !important;';
		$software_agency_custom_css .='}';
	}

	$software_agency_products_btn_padding_left_right = get_theme_mod('software_agency_products_btn_padding_left_right');
	if($software_agency_products_btn_padding_left_right != false){
		$software_agency_custom_css .='.woocommerce a.button{';
			$software_agency_custom_css .='padding-left: '.esc_attr($software_agency_products_btn_padding_left_right).' !important; padding-right: '.esc_attr($software_agency_products_btn_padding_left_right).' !important;';
		$software_agency_custom_css .='}';
	}

	$software_agency_woocommerce_sale_position = get_theme_mod( 'software_agency_woocommerce_sale_position','right');
    if($software_agency_woocommerce_sale_position == 'left'){
		$software_agency_custom_css .='.woocommerce ul.products li.product .onsale{';
			$software_agency_custom_css .='left: 10px !important; right: auto !important;';
		$software_agency_custom_css .='}';
	}else if($software_agency_woocommerce_sale_position == 'right'){
		$software_agency_custom_css .='.woocommerce ul.products li.product .onsale{';
			$software_agency_custom_css .='left: auto; right: 0;';
		$software_agency_custom_css .='}';
	}

	/*------------------ Logo  -------------------*/

	// Site title Font Size
	$software_agency_site_title_font_size = get_theme_mod('software_agency_site_title_font_size');
	if($software_agency_site_title_font_size != false){
		$software_agency_custom_css .='.logo p.site-title, .logo h1{';
			$software_agency_custom_css .='font-size: '.esc_attr($software_agency_site_title_font_size).';';
		$software_agency_custom_css .='}';
	}

	// Site tagline Font Size
	$software_agency_site_tagline_font_size = get_theme_mod('software_agency_site_tagline_font_size');
	if($software_agency_site_tagline_font_size != false){
		$software_agency_custom_css .='.logo p.site-description{';
			$software_agency_custom_css .='font-size: '.esc_attr($software_agency_site_tagline_font_size).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_logo_padding = get_theme_mod('software_agency_logo_padding');
	if($software_agency_logo_padding != false){
		$software_agency_custom_css .=' .logo{';
			$software_agency_custom_css .='padding: '.esc_attr($software_agency_logo_padding).' !important;';
		$software_agency_custom_css .='}';
	}

	$software_agency_logo_margin = get_theme_mod('software_agency_logo_margin');
	if($software_agency_logo_margin != false){
		$software_agency_custom_css .='.logo{';
			$software_agency_custom_css .='margin: '.esc_attr($software_agency_logo_margin).';';
		$software_agency_custom_css .='}';
	}


	$software_agency_site_title_color = get_theme_mod('software_agency_site_title_color');
	if($software_agency_site_title_color != false){
		$software_agency_custom_css .='p.site-title a{';
			$software_agency_custom_css .='color: '.esc_attr($software_agency_site_title_color).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_site_tagline_color = get_theme_mod('software_agency_site_tagline_color');
	if($software_agency_site_tagline_color != false){
		$software_agency_custom_css .='.logo p.site-description{';
			$software_agency_custom_css .='color: '.esc_attr($software_agency_site_tagline_color).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_logo_width = get_theme_mod('software_agency_logo_width');
	if($software_agency_logo_width != false){
		$software_agency_custom_css .='.logo img{';
			$software_agency_custom_css .='width: '.esc_attr($software_agency_logo_width).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_logo_height = get_theme_mod('software_agency_logo_height');
	if($software_agency_logo_height != false){
		$software_agency_custom_css .='.logo img{';
			$software_agency_custom_css .='height: '.esc_attr($software_agency_logo_height).';';
		$software_agency_custom_css .='}';
	}

	// Woocommerce img

	$software_agency_shop_featured_image_border_radius = get_theme_mod('software_agency_shop_featured_image_border_radius', 0);
	if($software_agency_shop_featured_image_border_radius != false){
		$software_agency_custom_css .='.woocommerce ul.products li.product a img{';
			$software_agency_custom_css .='border-radius: '.esc_attr($software_agency_shop_featured_image_border_radius).'px;';
		$software_agency_custom_css .='}';
	}

	$software_agency_shop_featured_image_box_shadow = get_theme_mod('software_agency_shop_featured_image_box_shadow',0);
	if($software_agency_shop_featured_image_box_shadow != false){
		$software_agency_custom_css .='.woocommerce ul.products li.product a img{';
			$software_agency_custom_css .='box-shadow: '.esc_attr($software_agency_shop_featured_image_box_shadow).'px '.esc_attr($software_agency_shop_featured_image_box_shadow).'px '.esc_attr($software_agency_shop_featured_image_box_shadow).'px #ddd;';
		$software_agency_custom_css .='}';
	}

	/*---------------- Posts Settings ------------------*/

	$software_agency_featured_image_border_radius = get_theme_mod('software_agency_featured_image_border_radius', 0);
	if($software_agency_featured_image_border_radius != false){
		$software_agency_custom_css .='.box-image img, .feature-box img{';
			$software_agency_custom_css .='border-radius: '.esc_attr($software_agency_featured_image_border_radius).'px;';
		$software_agency_custom_css .='}';
	}

	$software_agency_featured_image_box_shadow = get_theme_mod('software_agency_featured_image_box_shadow',0);
	if($software_agency_featured_image_box_shadow != false){
		$software_agency_custom_css .='.box-image img, #content-vw img{';
			$software_agency_custom_css .='box-shadow: '.esc_attr($software_agency_featured_image_box_shadow).'px '.esc_attr($software_agency_featured_image_box_shadow).'px '.esc_attr($software_agency_featured_image_box_shadow).'px #cccccc;';
		$software_agency_custom_css .='}';
	}

	/*--------------- Preloader Background Color  -------------------*/

	$software_agency_preloader_bg_color = get_theme_mod('software_agency_preloader_bg_color');
	if($software_agency_preloader_bg_color != false){
		$software_agency_custom_css .='#preloader{';
			$software_agency_custom_css .='background-color: '.esc_attr($software_agency_preloader_bg_color).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_preloader_border_color = get_theme_mod('software_agency_preloader_border_color');
	if($software_agency_preloader_border_color != false){
		$software_agency_custom_css .='.loader-line{';
			$software_agency_custom_css .='border-color: '.esc_attr($software_agency_preloader_border_color).'!important;';
		$software_agency_custom_css .='}';
	}

	$software_agency_preloader_bg_img = get_theme_mod('software_agency_preloader_bg_img');
	if($software_agency_preloader_bg_img != false){
		$software_agency_custom_css .='#preloader{';
			$software_agency_custom_css .='background: url('.esc_attr($software_agency_preloader_bg_img).');-webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;';
		$software_agency_custom_css .='}';
	}

	// Header Background Color
	$software_agency_header_background_color = get_theme_mod('software_agency_header_background_color');
	if($software_agency_header_background_color != false){
		$software_agency_custom_css .='.middle-bar{';
			$software_agency_custom_css .='background-color: '.esc_attr($software_agency_header_background_color).';';
		$software_agency_custom_css .='}';
	} 

	$software_agency_header_img_position = get_theme_mod('software_agency_header_img_position','center top');
	if($software_agency_header_img_position != false){
		$software_agency_custom_css .='.middle-bar{';
			$software_agency_custom_css .='background-position: '.esc_attr($software_agency_header_img_position).'!important;';
		$software_agency_custom_css .='}';
	}

	/*----------------Sroll to top Settings ------------------*/

	$software_agency_scroll_to_top_font_size = get_theme_mod('software_agency_scroll_to_top_font_size');
	if($software_agency_scroll_to_top_font_size != false){
		$software_agency_custom_css .='.scrollup i{';
			$software_agency_custom_css .='font-size: '.esc_attr($software_agency_scroll_to_top_font_size).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_scroll_to_top_padding = get_theme_mod('software_agency_scroll_to_top_padding');
	$software_agency_scroll_to_top_padding = get_theme_mod('software_agency_scroll_to_top_padding');
	if($software_agency_scroll_to_top_padding != false){
		$software_agency_custom_css .='.scrollup i{';
			$software_agency_custom_css .='padding-top: '.esc_attr($software_agency_scroll_to_top_padding).'!important;padding-bottom: '.esc_attr($software_agency_scroll_to_top_padding).'!important;';
		$software_agency_custom_css .='}';
	}

	$software_agency_scroll_to_top_width = get_theme_mod('software_agency_scroll_to_top_width');
	if($software_agency_scroll_to_top_width != false){
		$software_agency_custom_css .='.scrollup i{';
			$software_agency_custom_css .='width: '.esc_attr($software_agency_scroll_to_top_width).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_scroll_to_top_height = get_theme_mod('software_agency_scroll_to_top_height');
	if($software_agency_scroll_to_top_height != false){
		$software_agency_custom_css .='.scrollup i{';
			$software_agency_custom_css .='height: '.esc_attr($software_agency_scroll_to_top_height).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_scroll_to_top_border_radius = get_theme_mod('software_agency_scroll_to_top_border_radius');
	if($software_agency_scroll_to_top_border_radius != false){
		$software_agency_custom_css .='.scrollup i{';
			$software_agency_custom_css .='border-radius: '.esc_attr($software_agency_scroll_to_top_border_radius).'px;';
		$software_agency_custom_css .='}';
	}

	/*----------------Social Icons Settings ------------------*/

	$software_agency_social_icon_font_size = get_theme_mod('software_agency_social_icon_font_size');
	if($software_agency_social_icon_font_size != false){
		$software_agency_custom_css .='#sidebar .custom-social-icons i, #footer-2 .custom-social-icons i{';
			$software_agency_custom_css .='font-size: '.esc_attr($software_agency_social_icon_font_size).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_social_icon_padding = get_theme_mod('software_agency_social_icon_padding');
	if($software_agency_social_icon_padding != false){
		$software_agency_custom_css .='#sidebar .custom-social-icons i, #footer-2 .custom-social-icons i{';
			$software_agency_custom_css .='padding: '.esc_attr($software_agency_social_icon_padding).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_social_icon_width = get_theme_mod('software_agency_social_icon_width');
	if($software_agency_social_icon_width != false){
		$software_agency_custom_css .='#sidebar .custom-social-icons i, #footer-2 .custom-social-icons i{';
			$software_agency_custom_css .='width: '.esc_attr($software_agency_social_icon_width).';';
		$software_agency_custom_css .='}';
	}

	$software_agency_social_icon_height = get_theme_mod('software_agency_social_icon_height');
	if($software_agency_social_icon_height != false){
		$software_agency_custom_css .='#sidebar .custom-social-icons i, #footer-2 .custom-social-icons i{';
			$software_agency_custom_css .='height: '.esc_attr($software_agency_social_icon_height).';';
		$software_agency_custom_css .='}';
	}

	/*---------------- Footer Settings ------------------*/

	$software_agency_button_footer_heading_letter_spacing = get_theme_mod('software_agency_button_footer_heading_letter_spacing',1);
	$software_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label, a.rsswidget.rss-widget-title{';
		$software_agency_custom_css .='letter-spacing: '.esc_attr($software_agency_button_footer_heading_letter_spacing).'px;';
	$software_agency_custom_css .='}';

	$software_agency_button_footer_font_size = get_theme_mod('software_agency_button_footer_font_size','40');
	$software_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label, a.rsswidget.rss-widget-title{';
		$software_agency_custom_css .='font-size: '.esc_attr($software_agency_button_footer_font_size).'px;';
	$software_agency_custom_css .='}';

	$software_agency_theme_lay = get_theme_mod( 'software_agency_button_footer_text_transform','Capitalize');
	if($software_agency_theme_lay == 'Capitalize'){
		$software_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label{';
			$software_agency_custom_css .='text-transform:Capitalize;';
		$software_agency_custom_css .='}';
	}
	if($software_agency_theme_lay == 'Lowercase'){
		$software_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label, a.rsswidget.rss-widget-title{';
			$software_agency_custom_css .='text-transform:Lowercase;';
		$software_agency_custom_css .='}';
	}
	if($software_agency_theme_lay == 'Uppercase'){
		$software_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label, a.rsswidget.rss-widget-title{';
			$software_agency_custom_css .='text-transform:Uppercase;';
		$software_agency_custom_css .='}';
	}

	$software_agency_footer_heading_weight = get_theme_mod('software_agency_footer_heading_weight','400');
	if($software_agency_footer_heading_weight != false){
		$software_agency_custom_css .='#footer h3, #footer .wp-block-search .wp-block-search__label, a.rsswidget.rss-widget-title{';
			$software_agency_custom_css .='font-weight: '.esc_attr($software_agency_footer_heading_weight).';';
		$software_agency_custom_css .='}';
	}

	/*---------------------------Footer Style -------------------*/

	$software_agency_theme_lay = get_theme_mod( 'software_agency_footer_template','software_agency-footer-one');
    if($software_agency_theme_lay == 'software_agency-footer-one'){
		$software_agency_custom_css .='#footer{';
			$software_agency_custom_css .='';
		$software_agency_custom_css .='}';

	}else if($software_agency_theme_lay == 'software_agency-footer-two'){
		$software_agency_custom_css .='#footer{';
			$software_agency_custom_css .='background: linear-gradient(to right, #f9f8ff, #dedafa);';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='#footer p, #footer li a, #footer, #footer h3, #footer a.rsswidget, #footer #wp-calendar a, .copyright a, #footer .custom_details, #footer ins span, #footer .tagcloud a, .main-inner-box span.entry-date a, nav.woocommerce-MyAccount-navigation ul li:hover a, #footer ul li a, #footer table, #footer th, #footer td, #footer caption, #sidebar caption,#footer nav.wp-calendar-nav a,#footer .search-form .search-field{';
			$software_agency_custom_css .='color:#000;';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='#footer ul li::before{';
			$software_agency_custom_css .='background:#000;';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='#footer table, #footer th, #footer td,#footer .search-form .search-field,#footer .tagcloud a{';
			$software_agency_custom_css .='border: 1px solid #000;';
		$software_agency_custom_css .='}';

	}else if($software_agency_theme_lay == 'software_agency-footer-three'){
		$software_agency_custom_css .='#footer{';
			$software_agency_custom_css .='background: #232524;';
		$software_agency_custom_css .='}';
	}
	else if($software_agency_theme_lay == 'software_agency-footer-four'){
		$software_agency_custom_css .='#footer{';
			$software_agency_custom_css .='background: #f7f7f7;';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='#footer p, #footer li a, #footer, #footer h3, #footer a.rsswidget, #footer #wp-calendar a, .copyright a, #footer .custom_details, #footer ins span, #footer .tagcloud a, .main-inner-box span.entry-date a, nav.woocommerce-MyAccount-navigation ul li:hover a, #footer ul li a, #footer table, #footer th, #footer td, #footer caption, #sidebar caption,#footer nav.wp-calendar-nav a,#footer .search-form .search-field{';
			$software_agency_custom_css .='color:#000;';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='#footer ul li::before{';
			$software_agency_custom_css .='background:#000;';
		$software_agency_custom_css .='}';
		$software_agency_custom_css .='#footer table, #footer th, #footer td,#footer .search-form .search-field,#footer .tagcloud a{';
			$software_agency_custom_css .='border: 1px solid #000;';
		$software_agency_custom_css .='}';
	}
	else if($software_agency_theme_lay == 'software_agency-footer-five'){
		$software_agency_custom_css .='#footer{';
			$software_agency_custom_css .='background: linear-gradient(to right, #01093a, #2d0b00);';
		$software_agency_custom_css .='}';
	}

	$software_agency_resp_stickyheader = get_theme_mod( 'software_agency_stickyheader_hide_show',false);
	if($software_agency_resp_stickyheader == true && get_theme_mod( 'software_agency_sticky_header',false) != true){
    	$software_agency_custom_css .='.header-fixed{';
			$software_agency_custom_css .='position:static;';
		$software_agency_custom_css .='} ';
	}
    if($software_agency_resp_stickyheader == true){
    	$software_agency_custom_css .='@media screen and (max-width:575px) {';
		$software_agency_custom_css .='.header-fixed{';
			$software_agency_custom_css .='position:fixed;';
		$software_agency_custom_css .='} }';
	}else if($software_agency_resp_stickyheader == false){
		$software_agency_custom_css .='@media screen and (max-width:575px){';
		$software_agency_custom_css .='.header-fixed{';
			$software_agency_custom_css .='position:static;';
		$software_agency_custom_css .='} }';
	}

	$software_agency_responsive_preloader_hide = get_theme_mod('software_agency_responsive_preloader_hide',false);
	if($software_agency_responsive_preloader_hide == true && get_theme_mod('software_agency_loader_enable',false) == false){
		$software_agency_custom_css .='@media screen and (min-width:575px){
			#preloader{';
			$software_agency_custom_css .='display:none !important;';
		$software_agency_custom_css .='} }';
	}

	if($software_agency_responsive_preloader_hide == false){
		$software_agency_custom_css .='@media screen and (max-width:575px){
			#preloader{';
			$software_agency_custom_css .='display:none !important;';
		$software_agency_custom_css .='} }';
	}

	$software_agency_bradcrumbs_alignment = get_theme_mod( 'software_agency_bradcrumbs_alignment','Left');
    if($software_agency_bradcrumbs_alignment == 'Left'){
    	$software_agency_custom_css .='@media screen and (min-width:768px) {';
		$software_agency_custom_css .='.bradcrumbs{';
			$software_agency_custom_css .='text-align:start;';
		$software_agency_custom_css .='}}';
	}else if($software_agency_bradcrumbs_alignment == 'Center'){
		$software_agency_custom_css .='@media screen and (min-width:768px) {';
		$software_agency_custom_css .='.bradcrumbs{';
			$software_agency_custom_css .='text-align:center;';
		$software_agency_custom_css .='}}';
	}else if($software_agency_bradcrumbs_alignment == 'Right'){
		$software_agency_custom_css .='@media screen and (min-width:768px) {';
		$software_agency_custom_css .='.bradcrumbs{';
			$software_agency_custom_css .='text-align:end;';
		$software_agency_custom_css .='}}';
	}
