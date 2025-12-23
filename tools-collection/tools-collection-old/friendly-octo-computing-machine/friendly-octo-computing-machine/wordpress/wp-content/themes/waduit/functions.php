<?php
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style('waduit-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
  $app = get_template_directory() . '/assets/css/app.css';
  if (file_exists($app)) {
    wp_enqueue_style('waduit-app', get_template_directory_uri() . '/assets/css/app.css', ['waduit-style'], wp_get_theme()->get('Version'));
  }
});
add_theme_support('title-tag');
add_theme_support('post-thumbnails');
