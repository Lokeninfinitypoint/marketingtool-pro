<?php
$term = get_queried_object();

// Define the query
$args = array(
    'post_type' => 'portfolio',
    'porto_tag' => $term->slug

);
$query = new WP_Query( $args );

get_template_part( 'templates/portfolio/taxonomy','portfolio');
