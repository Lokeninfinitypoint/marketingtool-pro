<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Preload important fonts -->
    <link rel="preload" href="<?php echo get_stylesheet_directory_uri(); ?>/fonts/UnifySans-Regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo get_stylesheet_directory_uri(); ?>/fonts/UnifySans-Bold.woff2" as="font" type="font/woff2" crossorigin>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <?php wordstream_clone_header(); ?>
    
    <div id="content" class="site-content">