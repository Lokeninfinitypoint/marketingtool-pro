<?php
/**
 * Software Agency: Block Patterns
 *
 * @package Software Agency
 * @since   1.0.0
 */

/**
 * Register Block Pattern Category.
 */
if ( function_exists( 'register_block_pattern_category' ) ) {
	register_block_pattern_category( 'software-agency',
		array( 'label' => __( 'Software Agency', 'software-agency' ) )
	);
}

/**
 * Register Block Patterns.
 */
if ( function_exists( 'register_block_pattern' ) ) {
	register_block_pattern(
		'software-agency/banner-section',
		array(
			'title'      => __( 'Banner Section', 'software-agency' ),
			'categories' => array( 'software-agency' ),
			'content'    => "<!-- wp:cover {\"minHeight\":550,\"customGradient\":\"linear-gradient(180deg,rgb(235,236,240) 0%,rgb(248,249,253) 100%)\",\"align\":\"full\",\"className\":\"banner-section\"} -->\n<div class=\"wp-block-cover alignfull has-background-dim has-background-gradient banner-section\" style=\"background:linear-gradient(180deg,rgb(235,236,240) 0%,rgb(248,249,253) 100%);min-height:550px\"><div class=\"wp-block-cover__inner-container\"><!-- wp:columns {\"align\":\"full\",\"className\":\"mb-0\"} -->\n<div class=\"wp-block-columns alignfull mb-0\"><!-- wp:column {\"width\":\"60%\",\"className\":\"banner-content ps-md-5 ms-lg-5 mb-md-0 mb-4\"} -->\n<div class=\"wp-block-column banner-content ps-md-5 ms-lg-5 mb-md-0 mb-4\" style=\"flex-basis:60%\"><!-- wp:heading {\"level\":1,\"className\":\"my-5\",\"style\":{\"color\":{\"text\":\"#11235e\"},\"typography\":{\"fontSize\":90}}} -->\n<h1 class=\"my-5 has-text-color\" style=\"color:#11235e;font-size:90px\">Lorem ipsum dolor sit amet, consectetur adipiscing elit</h1>\n<!-- /wp:heading -->\n\n<!-- wp:search {\"label\":\"Search\",\"showLabel\":false,\"placeholder\":\"Search...\",\"width\":85,\"widthUnit\":\"%\",\"buttonText\":\"Search Now\",\"buttonPosition\":\"button-inside\"} /--></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"width\":\"40%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:40%\"><!-- wp:image {\"id\":848,\"sizeSlug\":\"large\",\"linkDestination\":\"none\",\"className\":\"mb-0\"} -->\n<figure class=\"wp-block-image size-large mb-0\"><img src=\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/banner.png\" alt=\"\" class=\"wp-image-848\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div></div>\n<!-- /wp:cover -->",
		)
	);

	register_block_pattern(
		'software-agency/product-section',
		array(
			'title'      => __( 'Product Section', 'software-agency' ),
			'categories' => array( 'software-agency' ),
			'content'    => "<!-- wp:group {\"align\":\"wide\",\"className\":\"product-section py-5 m-0\"} -->\n<div class=\"wp-block-group alignwide product-section py-5 m-0\"><div class=\"wp-block-group__inner-container\"><!-- wp:heading {\"align\":\"wide\",\"style\":{\"typography\":{\"fontSize\":75},\"color\":{\"text\":\"#11235e\"}}} -->\n<h2 class=\"alignwide has-text-color\" style=\"color:#11235e;font-size:75px\">Explore Bestselling Softwares</h2>\n<!-- /wp:heading -->\n\n<!-- wp:woocommerce/product-category {\"columns\":4,\"rows\":1,\"categories\":[17],\"contentVisibility\":{\"title\":true,\"price\":true,\"rating\":false,\"button\":true},\"align\":\"full\",\"className\":\"mx-0\"} /--></div></div>\n<!-- /wp:group -->",
		)
	);
}