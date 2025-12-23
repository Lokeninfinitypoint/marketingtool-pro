<?php
function iteck_get_post_view($slug="") {
    $count = get_post_meta( get_the_ID(), 'post_views_count', true );
    if(empty($slug)){
        if($count==""){return esc_html("0");}
        else{ return esc_html($count);}
    }else{
        if($count==""){return esc_html__("0 Views", 'iteck');}
        elseif($count==1){return $count.esc_html__(" View ", 'iteck');}
        else{ return $count.esc_html__(" Views ", 'iteck');}
    }

}
function iteck_set_post_view() {
    $key = 'post_views_count';
    $post_id = get_the_ID();
    $count = (int) get_post_meta( $post_id, $key, true );
    $count++;
    update_post_meta( $post_id, $key, $count );
}

function iteck_posts_column_views( $columns ) {
    $columns['post_views'] = esc_html__(' Views ', 'iteck');
    return $columns;
}
function iteck_posts_custom_column_views( $column ) {
    if ( $column === 'post_views') {
        echo iteck_get_post_view();
    }
}
add_filter( 'manage_posts_columns', 'iteck_posts_column_views' );
add_action( 'manage_posts_custom_column', 'iteck_posts_custom_column_views' );

add_action( 'wp_head', 'iteck_set_post_view');
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);