<?php
/**
 * Optimized Theme Script Loading
 * 
 * Performance optimizations:
 * - Deferred loading for non-critical scripts
 * - Conditional loading based on page features
 * - Reduced number of HTTP requests
 * - Modern async loading patterns
 */

/**
 * Add async or defer attributes to script tags
 * 
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string Modified script tag.
 */
function iteck_add_async_defer_attributes($tag, $handle) {
    // Scripts that should be deferred (non-critical)
    $defer_scripts = array(
        'superfish',
        'jquery-fitvids',
        'jquery-magnific-popup',
        'slick',
        'slick-animation',
        'theia-sticky-sidebar',
        'svgembedder',
        'iteck-totop',
        'animated-headline',
        'splitting',
        'isotope.pkgd',
        'simpleParallax',
        'jquery-counterup',
        'jquery-knob',
        'jquery-wow',
        'jquery-appear',
    );
    
    // Scripts that should load async (independent)
    $async_scripts = array(
        'pace',
    );
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    if (in_array($handle, $async_scripts)) {
        return str_replace(' src', ' async src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'iteck_add_async_defer_attributes', 10, 2);

/**
 * Add preload hints for critical assets
 */
function iteck_add_preload_hints() {
    // Preload critical fonts
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    
    // Preload critical CSS
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/css/bootstrap.min.css" as="style">' . "\n";
    
    // Preload critical JS
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/js/bootstrap.min.js" as="script">' . "\n";
    
    // DNS prefetch for external resources
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
}
add_action('wp_head', 'iteck_add_preload_hints', 1);

/**
 * Check if page needs specific features
 * 
 * @param string $feature Feature to check for.
 * @return bool Whether the feature is needed.
 */
function iteck_needs_feature($feature) {
    global $post;
    
    switch ($feature) {
        case 'slider':
            // Check if page has slider shortcode or widget
            return is_front_page() || (isset($post) && has_shortcode($post->post_content, 'slider'));
            
        case 'portfolio':
            // Check if this is a portfolio page
            return is_post_type_archive('portfolio') || is_singular('portfolio');
            
        case 'gallery':
            // Check if page has gallery
            return isset($post) && (has_shortcode($post->post_content, 'gallery') || strpos($post->post_content, 'iteck-gallery') !== false);
            
        case 'counter':
            // Check if page has counter elements
            return isset($post) && (strpos($post->post_content, 'counter') !== false || strpos($post->post_content, 'progress') !== false);
            
        case 'parallax':
            // Check if page has parallax sections
            return isset($post) && strpos($post->post_content, 'parallax') !== false;
            
        case 'magnific':
            // Check if page has popup images
            return isset($post) && (strpos($post->post_content, 'popup-img') !== false || strpos($post->post_content, 'magnific') !== false);
            
        default:
            return true;
    }
}

/**
 * Optimized theme scripts loading
 */
function iteck_theme_scripts_optimized() {
    $template_uri = get_template_directory_uri();
    $version = wp_get_theme()->get('Version');
    
    // Critical scripts - always load
    wp_enqueue_script('modernizr', $template_uri . '/assets/js/modernizr.js', array('jquery'), $version, false);
    wp_enqueue_script('bootstrap', $template_uri . '/assets/js/bootstrap.min.js', array(), $version, true);
    wp_enqueue_script('jquery-sticky', $template_uri . '/assets/js/jquery.sticky.js', array(), $version, true);
    wp_enqueue_script('pace', $template_uri . '/assets/js/pace.min.js', array(), $version, true);
    
    // Navigation scripts
    wp_enqueue_script('superfish', $template_uri . '/assets/js/superfish.js', array(), $version, true);
    
    // Conditional: Video responsive
    if (is_singular('post') || is_singular('page')) {
        wp_enqueue_script('jquery-fitvids', $template_uri . '/assets/js/jquery.fitvids.js', array(), $version, true);
    }
    
    // Conditional: Popup images
    if (iteck_needs_feature('magnific')) {
        wp_enqueue_script('jquery-magnific-popup', $template_uri . '/assets/js/jquery.magnific-popup.min.js', array(), $version, true);
    }
    
    // Conditional: Slider features
    if (iteck_needs_feature('slider') || is_front_page()) {
        wp_enqueue_script('slick', $template_uri . '/assets/js/slick.min.js', array(), $version, true);
        wp_enqueue_script('slick-animation', $template_uri . '/assets/js/slick-animation.js', array(), $version, true);
        wp_enqueue_script('swiper', $template_uri . '/assets/js/swiper.min.js', array('jquery'), $version, true);
    }
    
    // Conditional: Sidebar features
    if (is_singular('post') || is_page_template('template-sidebar.php')) {
        wp_enqueue_script('resizesensor', $template_uri . '/assets/js/ResizeSensor.min.js', array(), $version, true);
        wp_enqueue_script('theia-sticky-sidebar', $template_uri . '/assets/js/theia-sticky-sidebar.min.js', array(), $version, true);
    }
    
    // Conditional: SVG embedder
    wp_enqueue_script('svgembedder', $template_uri . '/assets/js/svgembedder.min.js', array(), $version, true);
    
    // Back to top - always needed
    wp_enqueue_script('iteck-totop', $template_uri . '/assets/js/totop.js', array(), $version, true);
    
    // Conditional: Animated headlines
    if (!is_rtl()) {
        wp_enqueue_script('splitting', $template_uri . '/assets/js/splitting.min.js', array(), $version, true);
    }
    
    // Conditional: Portfolio/Gallery features
    if (iteck_needs_feature('portfolio') || iteck_needs_feature('gallery')) {
        wp_enqueue_script('isotope.pkgd', $template_uri . '/assets/js/isotope.pkgd.min.js', array(), $version, true);
    }
    
    // Conditional: Parallax
    if (iteck_needs_feature('parallax')) {
        wp_enqueue_script('simpleParallax', $template_uri . '/assets/js/simpleParallax.min.js', array('jquery'), $version, true);
    }
    
    // Conditional: Counter/Progress features
    if (iteck_needs_feature('counter')) {
        wp_enqueue_script('waypoints', $template_uri . '/assets/js/waypoints.min.js', array(), $version, true);
        wp_enqueue_script('jquery-counterup', $template_uri . '/assets/js/jquery.counterup.min.js', array(), $version, true);
        wp_enqueue_script('jquery-knob', $template_uri . '/assets/js/jquery.knob.js', array(), $version, true);
        wp_enqueue_script('jquery-appear', $template_uri . '/assets/js/jquery.appear.js', array(), $version, true);
    }
    
    // WOW animations - always load for consistency
    wp_enqueue_script('jquery-wow', $template_uri . '/assets/js/wow.min.js', array(), $version, true);
    
    // Main scripts - always load
    wp_enqueue_script('iteck-scripts', $template_uri . '/assets/js/scripts.js', array('jquery'), $version, true);
    
    // Localize script for conditional features
    wp_localize_script('iteck-scripts', 'iteckConfig', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'features' => array(
            'slider' => iteck_needs_feature('slider'),
            'portfolio' => iteck_needs_feature('portfolio'),
            'gallery' => iteck_needs_feature('gallery'),
            'counter' => iteck_needs_feature('counter'),
            'parallax' => iteck_needs_feature('parallax'),
        ),
    ));
}

/**
 * Remove default script registration and use optimized version
 */
function iteck_remove_default_scripts() {
    // Remove the original function if it exists
    remove_action('wp_enqueue_scripts', 'iteck_theme_scripts');
}
add_action('wp_enqueue_scripts', 'iteck_remove_default_scripts', 1);

// Register optimized scripts
add_action('wp_enqueue_scripts', 'iteck_theme_scripts_optimized', 10);

/**
 * Remove unused jQuery migrate in production
 */
function iteck_remove_jquery_migrate($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
}
add_action('wp_default_scripts', 'iteck_remove_jquery_migrate');

/**
 * Optimize font loading with display swap
 */
function iteck_fonts_url_optimized() {
    $font_url = '';
    
    if ('off' !== _x('on', 'Google font: on or off', 'iteck')) {
        // Add display=swap for better font loading performance
        $font_url = add_query_arg(
            array(
                'family' => urlencode('Open Sans:400,600,700|Poppins:400,500,600,700'),
                'display' => 'swap',
            ),
            '//fonts.googleapis.com/css2'
        );
    }
    return $font_url;
}
