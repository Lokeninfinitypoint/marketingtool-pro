<?php
/**
 * Marketing Agency: Block Patterns
 *
 * @package Marketing Agency
 * @since   1.0.0
 */

/**
 * Register Block Pattern Category.
 */
if ( function_exists( 'register_block_pattern_category' ) ) {

	register_block_pattern_category(
		'marketing-agency',
		array( 'label' => __( 'Marketing Agency', 'marketing-agency' ) )
	);
}

/**
 * Register Block Patterns.
 */
if ( function_exists( 'register_block_pattern' ) ) {
	register_block_pattern(
		'marketing-agency/banner-section',
		array(
			'title'      => __( 'Banner Section', 'marketing-agency' ),
			'categories' => array( 'marketing-agency' ),
			'content'    => "<!-- wp:cover {\"url\":\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/banner.png\",\"id\":5455,\"align\":\"full\",\"className\":\"banner-section\"} -->\n<div class=\"wp-block-cover alignfull has-background-dim banner-section\" style=\"background-image:url(" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/banner.png)\"><div class=\"wp-block-cover__inner-container\"><!-- wp:columns {\"align\":\"full\"} -->\n<div class=\"wp-block-columns alignfull\"><!-- wp:column {\"verticalAlignment\":\"center\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center\"><!-- wp:heading {\"textAlign\":\"left\",\"level\":1} -->\n<h1 class=\"has-text-align-left\">Lorem ipsum dolor sit amet, Consectetur</h1>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"align\":\"left\"} -->\n<p class=\"has-text-align-left\">Lorem Ipsum has been the industrys standard. Lorem Ipsum has been the industry standard. Lorem Ipsum has been the industrys standard dummy text</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:buttons -->\n<div class=\"wp-block-buttons\"><!-- wp:button {\"borderRadius\":30,\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(93,23,223) 0%,rgb(9,133,249) 100%)\"}},\"className\":\"btn\"} -->\n<div class=\"wp-block-button btn\"><a class=\"wp-block-button__link has-background\" style=\"border-radius:30px;background:linear-gradient(135deg,rgb(93,23,223) 0%,rgb(9,133,249) 100%)\">GET STARTED</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"align\":\"center\",\"id\":5445,\"width\":388,\"height\":331,\"sizeSlug\":\"large\",\"linkDestination\":\"media\",\"className\":\"banner-circle-img\"} -->\n<div class=\"wp-block-image banner-circle-img\"><figure class=\"aligncenter size-large is-resized\"><img src=\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/circle.png\" alt=\"\" class=\"wp-image-5445\" width=\"388\" height=\"331\"/></figure></div>\n<!-- /wp:image --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div></div>\n<!-- /wp:cover -->",
		)
	);

	register_block_pattern(
		'marketing-agency/services-section',
		array(
			'title'      => __( 'Services Section', 'marketing-agency' ),
			'categories' => array( 'marketing-agency' ),
			'content'    => "<!-- wp:cover {\"overlayColor\":\"white\",\"align\":\"wide\",\"className\":\"article-outer-box\"} -->\n<div class=\"wp-block-cover alignwide has-white-background-color has-background-dim article-outer-box\"><div class=\"wp-block-cover__inner-container\"><!-- wp:heading {\"textAlign\":\"center\",\"style\":{\"color\":{\"text\":\"#333333\"}}} -->\n<h2 class=\"has-text-align-center has-text-color\" style=\"color:#333333\">Lorem ipsum dolor sit amet, Consectetur adipiscing</h2>\n<!-- /wp:heading -->\n\n<!-- wp:columns {\"align\":\"wide\",\"className\":\"article-container\"} -->\n<div class=\"wp-block-columns alignwide article-container\"><!-- wp:column {\"className\":\"article-section\"} -->\n<div class=\"wp-block-column article-section\"><!-- wp:heading {\"textAlign\":\"center\",\"level\":3,\"style\":{\"color\":{\"text\":\"#333333\"}}} -->\n<h3 class=\"has-text-align-center has-text-color\" style=\"color:#333333\">Graphic Design</h3>\n<!-- /wp:heading -->\n\n<!-- wp:image {\"align\":\"center\",\"id\":5424,\"sizeSlug\":\"large\",\"linkDestination\":\"media\"} -->\n<div class=\"wp-block-image\"><figure class=\"aligncenter size-large\"><img src=\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/services1.png\" alt=\"\" class=\"wp-image-5424\"/></figure></div>\n<!-- /wp:image -->\n\n<!-- wp:paragraph {\"align\":\"center\",\"style\":{\"color\":{\"text\":\"#666666\"}}} -->\n<p class=\"has-text-align-center has-text-color\" style=\"color:#666666\">Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"className\":\"article-section\"} -->\n<div class=\"wp-block-column article-section\"><!-- wp:heading {\"textAlign\":\"center\",\"level\":3,\"style\":{\"color\":{\"text\":\"#333333\"}}} -->\n<h3 class=\"has-text-align-center has-text-color\" style=\"color:#333333\">UI/UX &amp; Interaction</h3>\n<!-- /wp:heading -->\n\n<!-- wp:image {\"align\":\"center\",\"id\":5426,\"sizeSlug\":\"large\",\"linkDestination\":\"media\"} -->\n<div class=\"wp-block-image\"><figure class=\"aligncenter size-large\"><img src=\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/services2.png\" alt=\"\" class=\"wp-image-5426\"/></figure></div>\n<!-- /wp:image -->\n\n<!-- wp:paragraph {\"align\":\"center\",\"style\":{\"color\":{\"text\":\"#666666\"}}} -->\n<p class=\"has-text-align-center has-text-color\" style=\"color:#666666\">Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"className\":\"article-section\"} -->\n<div class=\"wp-block-column article-section\"><!-- wp:heading {\"textAlign\":\"center\",\"level\":3,\"style\":{\"color\":{\"text\":\"#333333\"}}} -->\n<h3 class=\"has-text-align-center has-text-color\" style=\"color:#333333\">Animation &amp; Motion</h3>\n<!-- /wp:heading -->\n\n<!-- wp:image {\"align\":\"center\",\"id\":5427,\"sizeSlug\":\"large\",\"linkDestination\":\"media\"} -->\n<div class=\"wp-block-image\"><figure class=\"aligncenter size-large\"><img src=\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/services3.png\" alt=\"\" class=\"wp-image-5427\"/></figure></div>\n<!-- /wp:image -->\n\n<!-- wp:paragraph {\"align\":\"center\",\"style\":{\"color\":{\"text\":\"#666666\"}}} -->\n<p class=\"has-text-align-center has-text-color\" style=\"color:#666666\">Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:paragraph {\"align\":\"center\",\"placeholder\":\"Write title\",\"fontSize\":\"large\"} -->\n<p class=\"has-text-align-center has-large-font-size\"></p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:cover -->",
		)
	);
}