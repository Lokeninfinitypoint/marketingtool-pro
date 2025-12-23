<?php

namespace Blocksy;

class HooksManager {
	public function get_all_hooks() {
		return array_merge([
			[
				'type' => 'action',
				'hook' => 'wp_head',
				'title' => __('WP head', 'blocksy-companion'),
				// 'visual' => false,
				'group' => __('Head', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:head:start',
				'title' => __('WP head start', 'blocksy-companion'),
				// 'visual' => false,
				'group' => __('Head', 'blocksy-companion'),
				'attr' => ['data-type' => 'full:top-margin'],
				'priority' => 15
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:head:end',
				'title' => __('WP head end', 'blocksy-companion'),
				// 'visual' => false,
				'group' => __('Head', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'wp_body_open',
				'title' => __('WP body open', 'blocksy-companion'),
				// 'visual' => false,
				'group' => __('Head', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:header:before',
				'title' => __('Header before', 'blocksy-companion'),
				// 'visual' => false,
				'group' => __('Header', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:header:after',
				'title' => __('Header after', 'blocksy-companion'),
				// 'visual' => false,
				'group' => __('Header', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:header:offcanvas:desktop:top',
				'title' => __('Desktop top', 'blocksy-companion'),
				// 'visual' => false,
				'group' => __('Header offcanvas', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:header:offcanvas:desktop:bottom',
				'title' => __('Desktop bottom', 'blocksy-companion'),
				// 'visual' => false,
				'group' => __('Header offcanvas', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:header:offcanvas:mobile:top',
				'title' => __('Mobile top', 'blocksy-companion'),
				// 'visual' => false,
				'group' => __('Header offcanvas', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:header:offcanvas:mobile:bottom',
				'title' => __('Mobile bottom', 'blocksy-companion'),
				// 'visual' => false,
				'group' => __('Header offcanvas', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:sidebar:before',
				'title' => __('Sidebar before', 'blocksy-companion'),
				'group' => __('Left/Right sidebar', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:sidebar:start',
				'title' => __('Sidebar start', 'blocksy-companion'),
				'group' => __('Left/Right sidebar', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:sidebar:end',
				'title' => __('Sidebar end', 'blocksy-companion'),
				'group' => __('Left/Right sidebar', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:sidebar:after',
				'title' => __('Sidebar after', 'blocksy-companion'),
				'group' => __('Left/Right sidebar', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'dynamic_sidebar_before',
				'title' => __('Dynamic sidebar before', 'blocksy-companion'),
				'group' => __('All widget areas', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'dynamic_sidebar',
				'title' => __('Dynamic sidebar', 'blocksy-companion'),
				'group' => __('All widget areas', 'blocksy-companion')
			],


			[
				'type' => 'action',
				'hook' => 'dynamic_sidebar_after',
				'title' => __('Dynamic sidebar after', 'blocksy-companion'),
				'group' => __('All widget areas', 'blocksy-companion')
			],


			// page post title
			[
				'type' => 'action',
				'hook' => 'blocksy:hero:before',
				'title' => __('Before section', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:hero:title:before',
				'title' => __('Before title', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:hero:description:before',
				'title' => __('Before description', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:hero:breadcrumbs:before',
				'title' => __('Before breadcrumbs', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:hero:custom_meta:before',
				'title' => __('Before post meta', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:hero:custom_meta:first:before',
				'title' => __('Before first post meta', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:hero:custom_meta:second:before',
				'title' => __('Before second post meta', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:hero:title:after',
				'title' => __('After title', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:hero:description:after',
				'title' => __('After description', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:hero:breadcrumbs:after',
				'title' => __('After breadcrumbs', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:hero:custom_meta:first:after',
				'title' => __('After first post meta', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:hero:custom_meta:second:after',
				'title' => __('After second post meta', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:hero:custom_meta:after',
				'title' => __('After post meta', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:hero:after',
				'title' => __('After section', 'blocksy-companion'),
				'group' => __('Page/post title', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:content:before',
				'title' => __('Before content', 'blocksy-companion'),
				'group' => __('Content', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:content:top',
				'title' => __('Top content', 'blocksy-companion'),
				'group' => __('Content', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:content:bottom',
				'title' => __('Bottom content', 'blocksy-companion'),
				'group' => __('Content', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:content:after',
				'title' => __('After content', 'blocksy-companion'),
				'group' => __('Content', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:comments:before',
				'title' => __('Before comments', 'blocksy-companion'),
				'group' => __('Comments', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:comments:top',
				'title' => __('Top comments', 'blocksy-companion'),
				'group' => __('Comments', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:comments:title:before',
				'title' => __('Before title', 'blocksy-companion'),
				'group' => __('Comments', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:comments:title:after',
				'title' => __('After title', 'blocksy-companion'),
				'group' => __('Comments', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:comments:bottom',
				'title' => __('Bottom comments', 'blocksy-companion'),
				'group' => __('Comments', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:comments:after',
				'title' => __('After comments', 'blocksy-companion'),
				'group' => __('Comments', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:single:related_posts:before',
				'title' => __('Before related posts', 'blocksy-companion'),
				'group' => __('Related posts', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:single:related_posts:top',
				'title' => __('Related posts top', 'blocksy-companion'),
				'group' => __('Related posts', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:single:related_posts:title:before',
				'title' => __('Before title', 'blocksy-companion'),
				'group' => __('Related posts', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:single:related_posts:title:after',
				'title' => __('After title', 'blocksy-companion'),
				'group' => __('Related posts', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:single:related_posts:card:top',
				'title' => __('Card top', 'blocksy-companion'),
				'group' => __('Related posts', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:single:related_posts:featured_image:before',
				'title' => __('Before featured image', 'blocksy-companion'),
				'group' => __('Related posts', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:single:related_posts:featured_image:after',
				'title' => __('After featured image', 'blocksy-companion'),
				'group' => __('Related posts', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:single:related_posts:card:bottom',
				'title' => __('Card bottom', 'blocksy-companion'),
				'group' => __('Related posts', 'blocksy-companion')
			],


			[
				'type' => 'action',
				'hook' => 'blocksy:single:related_posts:bottom',
				'title' => __('Related posts bottom', 'blocksy-companion'),
				'group' => __('Related posts', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:single:related_posts:after',
				'title' => __('After related posts', 'blocksy-companion'),
				'group' => __('Related posts', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:loop:before',
				'title' => __('Before', 'blocksy-companion'),
				'group' => __('Loop', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:loop:after',
				'title' => __('After', 'blocksy-companion'),
				'group' => __('Loop', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:loop:card:start',
				'title' => __('Start', 'blocksy-companion'),
				'group' => __('Loop card', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:loop:card:end',
				'title' => __('End', 'blocksy-companion'),
				'group' => __('Loop card', 'blocksy-companion')
			],

			[
				'type' => 'dynamic',
				'hook' => 'blocksy:loop:card:cards-number',
				'title' => __('After certain number of cards', 'blocksy-companion'),
				'group' => __('Loop card', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:single:top',
				'title' => __('Top', 'blocksy-companion'),
				'group' => __('Single Post', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:single:content:top',
				'title' => __('Top content', 'blocksy-companion'),
				'group' => __('Single Post', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'dynamic',
				'hook' => 'blocksy:single:content:paragraphs-number',
				'title' => __('After certain number of blocks', 'blocksy-companion'),
				'group' => __('Single Post', 'blocksy-companion')
			],

			[
				'type' => 'dynamic',
				'hook' => 'blocksy:single:content:headings-number',
				'title' => __('Before certain number of headings', 'blocksy-companion'),
				'group' => __('Single Post', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:single:content:bottom',
				'title' => __('Bottom content', 'blocksy-companion'),
				'group' => __('Single Post', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:single:bottom',
				'title' => __('Bottom', 'blocksy-companion'),
				'group' => __('Single Post', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'woocommerce_login_form_start',
				'title' => __('Login form start', 'blocksy-companion'),
				'group' => __('Auth forms', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'woocommerce_login_form_end',
				'title' => __('Login form end', 'blocksy-companion'),
				'group' => __('Auth forms', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:account:modal:login:start',
				'title' => __('Login form modal start', 'blocksy-companion'),
				'group' => __('Auth forms', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:account:modal:login:end',
				'title' => __('Login form modal end', 'blocksy-companion'),
				'group' => __('Auth forms', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'woocommerce_register_form_start',
				'title' => __('Register form start', 'blocksy-companion'),
				'group' => __('Auth forms', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'woocommerce_register_form_end',
				'title' => __('Register form end', 'blocksy-companion'),
				'group' => __('Auth forms', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:account:modal:register:start',
				'title' => __('Register form modal start', 'blocksy-companion'),
				'group' => __('Auth forms', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:account:modal:register:end',
				'title' => __('Register form modal end', 'blocksy-companion'),
				'group' => __('Auth forms', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:account:modal:lostpassword:start',
				'title' => __('Lost password form modal start', 'blocksy-companion'),
				'group' => __('Auth forms', 'blocksy-companion')
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:account:modal:lostpassword:end',
				'title' => __('Lost password form modal end', 'blocksy-companion'),
				'group' => __('Auth forms', 'blocksy-companion')
			],
		],

		$this->get_grouped([
			[
				'hook' => 'woocommerce_before_main_content',
				'title' => __('Before main content', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'hook' => 'woocommerce_after_main_content',
				'title' => __('After main content', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'hook' => 'blocksy:pro:woo-extra:offcanvas-filters:top',
				'title' => __('Offcanvas Filters - Top', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'hook' => 'blocksy:pro:woo-extra:offcanvas-filters:bottom',
				'title' => __('Offcanvas Filters - Bottom', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'hook' => 'blocksy:pro:woo-extra:offcanvas:minicart:empty',
				'title' => __('Offcanvas Cart - Empty State', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'hook' => 'blocksy:pro:woo-extra:offcanvas:minicart:list:after',
				'title' => __('Offcanvas Cart - List After', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			]
		], __('WooCommerce Global', 'blocksy-companion')),

		$this->get_grouped(apply_filters('blocksy:hooks-manager:woocommerce-archive-hooks', [
			[
				'hook' => 'woocommerce_archive_description',
				'title' => __('Archive description', 'blocksy-companion')
			],

			[
				'hook' => 'woocommerce_before_shop_loop',
				'title' => __('Before shop loop', 'blocksy-companion')
			],

			/*
			[
				'hook' => 'woocommerce_before_shop_loop_item_title',
				'title' => __('Before shop loop item title', 'blocksy-companion')
			],

			[
				'hook' => 'woocommerce_after_shop_loop_item_title',
				'title' => __('After shop loop item title', 'blocksy-companion')
			],
			 */

			[
				'hook' => 'blocksy:woocommerce:product-card:title:before',
				'title' => __('Before shop loop item title', 'blocksy-companion')
			],

			[
				'hook' => 'blocksy:woocommerce:product-card:title:after',
				'title' => __('After shop loop item title', 'blocksy-companion')
			],

			[
				'hook' => 'blocksy:woocommerce:product-card:price:before',
				'title' => __('Before shop loop item price', 'blocksy-companion')
			],

			[
				'hook' => 'blocksy:woocommerce:product-card:price:after',
				'title' => __('After shop loop item price', 'blocksy-companion')
			],

			[
				'hook' => 'blocksy:woocommerce:product-card:actions:before',
				'title' => __('Before shop loop item actions', 'blocksy-companion')
			],

			[
				'hook' => 'blocksy:woocommerce:product-card:actions:after',
				'title' => __('After shop loop item actions', 'blocksy-companion')
			],

			[
				'hook' => 'woocommerce_after_shop_loop',
				'title' => __('After shop loop', 'blocksy-companion')
			],
		]), __('WooCommerce Archive', 'blocksy-companion')),

		$this->get_grouped([
			[
				'hook' => 'woocommerce_before_single_product',
				'title' => __('Before single product', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			/*
			[
				'hook' => 'woocommerce_before_single_product_summary',
				'title' => __('Before single product summary', 'blocksy-companion')
			],

			[
				'hook' => 'woocommerce_single_product_summary',
				'title' => __('Single product summary', 'blocksy-companion')
			],
			 */

			[
				'hook' => 'woocommerce_product_meta_start',
				'title' => __('Product meta start', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_product_meta_end',
				'title' => __('Product meta end', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_share',
				'title' => __('Share', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_after_single_product',
				'title' => __('After single product', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'hook' => 'blocksy:woocommerce:product-single:excerpt:before',
				'title' => __('Before single product excerpt', 'blocksy-companion')
			],

			[
				'hook' => 'blocksy:woocommerce:product-single:excerpt:after',
				'title' => __('After single product excerpt', 'blocksy-companion')
			],

			[
				'hook' => 'blocksy:woocommerce:product-single:tabs:before',
				'title' => __('Before single product tabs', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'hook' => 'blocksy:woocommerce:product-single:tabs:after',
				'title' => __('After single product tabs', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'hook' => 'blocksy:woocommerce:product-gallery:before',
				'title' => __('Before single product gallery', 'blocksy-companion')
			],

			[
				'hook' => 'blocksy:woocommerce:product-gallery:after',
				'title' => __('After single product gallery', 'blocksy-companion')
			],

			[
				'hook' => 'blocksy:woocommerce:product-single:add_to_cart:before',
				'title' => __('Before single product "Add to cart" button', 'blocksy-companion')
			],

			[
				'hook' => 'blocksy:woocommerce:product-single:add_to_cart:after',
				'title' => __('After single product "Add to cart" button', 'blocksy-companion')
			],

			[
				'hook' => 'blocksy:woocommerce:product-single:meta:before',
				'title' => __('Before single product meta', 'blocksy-companion')
			],

			[
				'hook' => 'blocksy:woocommerce:product-single:meta:after',
				'title' => __('After single product meta', 'blocksy-companion')
			],

		], __('WooCommerce Single Product', 'blocksy-companion')),

		$this->get_grouped([
			[
				'hook' => 'woocommerce_cart_is_empty',
				'title' => __('Cart is empty', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_before_cart',
				'title' => __('Before cart', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_before_cart_table',
				'title' => __('Before cart table', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_before_cart_contents',
				'title' => __('Before cart contents', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_cart_contents',
				'title' => __('Cart contents', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_after_cart_contents',
				'title' => __('After cart contents', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_cart_coupon',
				'title' => __('Cart coupon', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_cart_actions',
				'title' => __('Cart actions', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_after_cart_table',
				'title' => __('After cart table', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_cart_collaterals',
				'title' => __('Cart collaterals', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_before_cart_totals',
				'title' => __('Before cart totals', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_cart_totals_before_order_total',
				'title' => __('Cart totals before order total', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_cart_totals_after_order_total',
				'title' => __('Cart totals after order total', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_proceed_to_checkout',
				'title' => __('Proceed to checkout', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_after_cart_totals',
				'title' => __('After cart totals', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_after_cart',
				'title' => __('After cart', 'blocksy-companion')
			],

			[
				'hook' => 'woocommerce_before_mini_cart',
				'title' => __('Before Mini Cart', 'blocksy-companion')
			],

			[
				'hook' => 'woocommerce_before_mini_cart_contents',
				'title' => __('Before Mini Cart Contents', 'blocksy-companion')
			],

			[
				'hook' => 'woocommerce_mini_cart_contents',
				'title' => __('Mini Cart Contents', 'blocksy-companion')
			],

			[
				'hook' => 'woocommerce_widget_shopping_cart_before_buttons',
				'title' => __('Widget Shopping Cart Before Buttons', 'blocksy-companion')
			],

			[
				'hook' => 'woocommerce_widget_shopping_cart_after_buttons',
				'title' => __('Widget Shopping Cart After Buttons', 'blocksy-companion')
			],

			[
				'hook' => 'woocommerce_after_mini_cart',
				'title' => __('After Mini Cart', 'blocksy-companion')
			],
		], __('WooCommerce Cart', 'blocksy-companion')),


		$this->get_grouped([
			[
				'hook' => 'woocommerce_before_checkout_form',
				'title' => __('Before checkout form', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_checkout_before_customer_details',
				'title' => __('Before customer details', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_checkout_after_customer_details',
				'title' => __('After customer details', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_checkout_billing',
				'title' => __('Checkout billing', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_before_checkout_billing_form',
				'title' => __('Before checkout billing form', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_after_checkout_billing_form',
				'title' => __('After checkout billing form', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_before_order_notes',
				'title' => __('Before order notes', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_after_order_notes',
				'title' => __('After order notes', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_checkout_shipping',
				'title' => __('Checkout shipping', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_checkout_before_order_review',
				'title' => __('Checkout before order review', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_checkout_order_review',
				'title' => __('Checkout order review', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_review_order_before_cart_contents',
				'title' => __('Review order before cart contents', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_review_order_after_cart_contents',
				'title' => __('Review order after cart contents', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_review_order_before_order_total',
				'title' => __('Review order before order total', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_review_order_after_order_total',
				'title' => __('Review order after order total', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_review_order_before_payment',
				'title' => __('Review order before payment', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_review_order_before_submit',
				'title' => __('Review order before submit', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_review_order_after_submit',
				'title' => __('Review order after submit', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_review_order_after_payment',
				'title' => __('Review order after payment', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_checkout_after_order_review',
				'title' => __('Checkout after order review', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_after_checkout_form',
				'title' => __('After checkout form', 'blocksy-companion')
			],

		], __('WooCommerce Checkout', 'blocksy-companion')),


		$this->get_grouped([
			[
				'hook' => 'woocommerce_before_my_account',
				'title' => __('Before my account', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_before_account_navigation',
				'title' => __('Before account navigation', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_account_navigation',
				'title' => __('Account navigation', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_after_account_navigation',
				'title' => __('After account navigation', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_account_content',
				'title' => __('Account content', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_account_dashboard',
				'title' => __('Account dashboard', 'blocksy-companion')
			],
			[
				'hook' => 'woocommerce_after_my_account',
				'title' => __('After my account', 'blocksy-companion')
			],
		], __('WooCommerce Account', 'blocksy-companion')),


		$this->get_grouped([
			[
				'hook' => 'blocksy:ext:woocommerce-extra:added-to-cart:product:before',
				'title' => __('Added to Cart: Before product', 'blocksy-companion')
			],
			[
				'hook' => 'blocksy:ext:woocommerce-extra:added-to-cart:actions:before',
				'title' => __('Added to Cart: Before actions', 'blocksy-companion')
			],
			[
				'hook' => 'blocksy:ext:woocommerce-extra:added-to-cart:suggested_products:before',
				'title' => __('Added to Cart: Before suggested products', 'blocksy-companion')
			],
			[
				'hook' => 'blocksy:ext:woocommerce-extra:added-to-cart:suggested_products:after',
				'title' => __('Added to Cart: After suggested products', 'blocksy-companion')
			],
		], __('WooCommerce: Added to Cart', 'blocksy-companion')),

		[
			[
				'type' => 'action',
				'hook' => 'wp_footer',
				'title' => __('WP footer', 'blocksy-companion'),
				'group' => __('Footer', 'blocksy-companion'),
				'attr' => ['data-type' => 'full:bottom-margin']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:footer:before',
				'title' => __('Footer before', 'blocksy-companion'),
				'group' => __('Footer', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			],

			[
				'type' => 'action',
				'hook' => 'blocksy:footer:after',
				'title' => __('Footer after', 'blocksy-companion'),
				'group' => __('Footer', 'blocksy-companion'),
				'attr' => ['data-type' => 'full']
			]
        ],
		);
	}

	public function humanize_locations($locations) {
		$result = [];

		foreach ($locations as $location) {
			$name = null;

			if ($location['location'] === 'custom_hook') {
				$name = blc_safe_sprintf(
					// translators: %s is the custom hook name
					__('Custom Hook (%s)', 'blocksy-companion'),
					$location['custom_location']
				);
			}

			if ($location['location'] === 'blocksy:single:content:paragraphs-number') {
				$name = __('After Block Number', 'blocksy-companion') . ' ' . $location[
					'paragraphs_count'
				];
			}

			if ($location['location'] === 'blocksy:single:content:headings-number') {
				$name = __('Before Heading Number', 'blocksy-companion') . ' ' . $location[
					'headings_count'
				];
			}

			if (! $name) {
				$maybe_descriptor = $this->find_location_descriptor(
					$location['location']
				);

				if ($maybe_descriptor) {
					$name = $maybe_descriptor['title'];
				}
			}

			if (! $name) {
				$name = $location['location'];
			}

			$result[] = $name;
		}

		return $result;
	}

	private function find_location_descriptor($hook) {
		$all = $this->get_all_hooks();

		foreach ($all as $single_hook) {
			if ($single_hook['hook'] === $hook) {
				return $single_hook;
			}
		}

		return null;
	}

	private function get_grouped($items, $group = null) {
		$result = [];

		foreach ($items as $item) {
			$attr = [];
			$priority = 10;

			if (isset($item['attr'])) {
				$attr = $item['attr'];
			}

			if (isset($item['priority'])) {
				$priority = $item['priority'];
			}

			if (is_array($item)) {
				$result[] = array_merge([
					'type' => 'action',
					'attr' => $attr,
					'priority' => $priority,
					'hook' => $item['hook'],
					'title' => $item['title'],
				], $group ? ['group' => $group] : []);
			} else {
				$result[] = array_merge([
					'type' => 'action',
					'attr' => $attr,
					'priority' => $priority,
					'hook' => $item,
					'title' => $item,
				], $group ? ['group' => $group] : []);
			}
		}

		return $result;
	}
}

