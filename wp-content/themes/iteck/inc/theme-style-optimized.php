<?php
/**
 * Optimized Theme Style Loading
 * 
 * Performance optimizations:
 * - Critical CSS inlining
 * - Conditional loading based on page features
 * - Reduced number of icon fonts (consolidate to one)
 * - Non-blocking CSS loading for non-critical styles
 */

/**
 * Add preload for critical stylesheets
 * 
 * @param string $html   The link tag HTML.
 * @param string $handle The stylesheet handle.
 * @return string Modified link tag.
 */
function iteck_add_preload_style($html, $handle) {
    // Critical styles that should be preloaded
    $preload_styles = array(
        'bootstrap',
        'iteck-style',
    );
    
    if (in_array($handle, $preload_styles)) {
        // Add preload hint before the stylesheet
        $preload = str_replace("rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $html);
        $noscript = '<noscript>' . $html . '</noscript>';
        return $preload . $noscript;
    }
    
    return $html;
}
// Uncomment to enable non-blocking CSS (may cause FOUC)
// add_filter('style_loader_tag', 'iteck_add_preload_style', 10, 2);

/**
 * Add media="print" for non-critical CSS (loads asynchronously)
 * 
 * @param string $html   The link tag HTML.
 * @param string $handle The stylesheet handle.
 * @return string Modified link tag.
 */
function iteck_defer_non_critical_css($html, $handle) {
    // Non-critical styles that can be deferred
    $deferred_styles = array(
        'animate',
        'magic',
        'splitting',
        'splitting-cells',
    );
    
    if (in_array($handle, $deferred_styles)) {
        $html = str_replace(
            "media='all'",
            "media='print' onload=\"this.media='all'\"",
            $html
        );
        // Add noscript fallback
        $noscript_html = str_replace("media='print' onload=\"this.media='all'\"", "media='all'", $html);
        $html .= '<noscript>' . $noscript_html . '</noscript>';
    }
    
    return $html;
}
add_filter('style_loader_tag', 'iteck_defer_non_critical_css', 10, 2);

/**
 * Check if page needs specific CSS features
 * 
 * @param string $feature Feature to check for.
 * @return bool Whether the feature is needed.
 */
function iteck_needs_css_feature($feature) {
    global $post;
    
    switch ($feature) {
        case 'magnific':
            return isset($post) && (
                strpos($post->post_content, 'popup-img') !== false ||
                strpos($post->post_content, 'magnific') !== false ||
                strpos($post->post_content, 'gallery') !== false
            );
            
        case 'slider':
            return is_front_page() || (isset($post) && (
                has_shortcode($post->post_content, 'slider') ||
                strpos($post->post_content, 'slick') !== false
            ));
            
        case 'swiper':
            return is_front_page() || (isset($post) && strpos($post->post_content, 'swiper') !== false);
            
        case 'animate':
            return true; // Usually needed on most pages
            
        default:
            return true;
    }
}

/**
 * Optimized theme styles loading
 */
function iteck_theme_styles_optimized() {
    $template_uri = get_template_directory_uri();
    $version = wp_get_theme()->get('Version');
    
    // Critical CSS - always load
    wp_enqueue_style('bootstrap', $template_uri . '/assets/css/bootstrap.min.css', array(), $version, 'all');
    
    // Consolidated icon font - use only Font Awesome (most complete)
    // Remove duplicate icon libraries to reduce bundle size
    wp_enqueue_style('font-awesome', $template_uri . '/assets/css/font-awesome.min.css', array(), $version, 'all');
    // Only load additional icon fonts if specifically needed
    // wp_enqueue_style('fontawesome', $template_uri . '/assets/css/fontawesome.min.css', array(), $version, 'all');
    // wp_enqueue_style('icon-font', $template_uri . '/assets/css/icon-font.min.css', array(), $version, 'all');
    // wp_enqueue_style('ionicons', $template_uri . '/assets/css/ionicons.min.css', array(), $version, 'all');
    // wp_enqueue_style('bootstrap-icons', $template_uri . '/assets/fonts/bootstrap-icons/bootstrap-icons.css', array(), $version, 'all');
    
    // Conditional: Popup/Gallery styles
    if (iteck_needs_css_feature('magnific')) {
        wp_enqueue_style('magnific-popup', $template_uri . '/assets/css/magnific-popup.css', array(), $version, 'all');
    }
    
    // Animation styles - can be deferred
    wp_enqueue_style('animate', $template_uri . '/assets/css/animate.css', array(), $version, 'all');
    
    // Magic CSS - only if needed (heavy animations)
    // wp_enqueue_style('magic', $template_uri . '/assets/css/magic.css', array(), $version, 'all');
    
    // Conditional: Slider styles
    if (iteck_needs_css_feature('slider')) {
        wp_enqueue_style('slick', $template_uri . '/assets/css/slick.css', array(), $version, 'all');
    }
    
    // Mobile menu styles
    wp_enqueue_style('jquery-fatnav', $template_uri . '/assets/css/jquery.fatNav.css', array(), $version, 'all');
    
    // Animated headline
    wp_enqueue_style('animate-headline', $template_uri . '/assets/css/animate.headline.css', array(), $version, 'all');
    
    // Conditional: Swiper styles
    if (iteck_needs_css_feature('swiper')) {
        wp_enqueue_style('swiper', $template_uri . '/assets/css/swiper.min.css', array(), $version, 'all');
    }
    
    // RTL-specific styles
    if (!is_rtl()) {
        wp_enqueue_style('splitting', $template_uri . '/assets/css/splitting.css', array(), $version, 'all');
        wp_enqueue_style('splitting-cells', $template_uri . '/assets/css/splitting-cells.css', array(), $version, 'all');
    }
    
    // Main theme style - always load
    wp_enqueue_style('iteck-style', get_stylesheet_directory_uri() . '/style.css', array(), $version, 'all');
    wp_style_add_data('iteck-style', 'rtl', 'replace');
    
    // RTL theme adjustments
    if (is_rtl()) {
        wp_enqueue_style('iteck-theme-rtl', get_stylesheet_directory_uri() . '/assets/css/theme-rtl.css', array(), $version, 'all');
    }
}

/**
 * Remove default style registration and use optimized version
 */
function iteck_remove_default_styles() {
    remove_action('wp_enqueue_scripts', 'iteck_theme_styles');
}
add_action('wp_enqueue_scripts', 'iteck_remove_default_styles', 1);

// Register optimized styles
add_action('wp_enqueue_scripts', 'iteck_theme_styles_optimized', 10);

/**
 * Optimized Google Fonts with display=swap and reduced weights
 */
function iteck_fonts_url_optimized() {
    $font_url = '';
    
    if ('off' !== _x('on', 'Google font: on or off', 'iteck')) {
        // Reduced font weights and added display=swap
        $font_url = add_query_arg(
            array(
                'family' => urlencode('Open Sans:400;600;700|Poppins:400;500;600;700'),
                'display' => 'swap',
            ),
            '//fonts.googleapis.com/css2'
        );
    }
    return $font_url;
}

/**
 * Optimized fonts enqueue
 */
function iteck_fonts_style_optimized() {
    wp_enqueue_style('iteck-fonts', iteck_fonts_url_optimized(), array(), '1.0.0');
}
// Remove default and add optimized
remove_action('wp_enqueue_scripts', 'iteck_fonts_style');
add_action('wp_enqueue_scripts', 'iteck_fonts_style_optimized');

/**
 * Add resource hints for external resources
 */
function iteck_resource_hints($hints, $relation_type) {
    if ('preconnect' === $relation_type) {
        $hints[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin' => 'anonymous',
        );
        $hints[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }
    
    if ('dns-prefetch' === $relation_type) {
        $hints[] = '//fonts.googleapis.com';
        $hints[] = '//fonts.gstatic.com';
    }
    
    return $hints;
}
add_filter('wp_resource_hints', 'iteck_resource_hints', 10, 2);

/**
 * Inline critical CSS for above-the-fold content
 */
function iteck_inline_critical_css() {
    // Only inline critical CSS in production
    if (defined('WP_DEBUG') && WP_DEBUG) {
        return;
    }
    
    ?>
    <style id="critical-css">
    /* Critical above-the-fold CSS */
    *,*::before,*::after{box-sizing:border-box}
    body{margin:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;line-height:1.6}
    .topnav{position:fixed;top:0;left:0;right:0;z-index:1000;background:rgba(255,255,255,0.98)}
    .container{width:100%;max-width:1200px;margin:0 auto;padding:0 15px}
    .nav-scroll{box-shadow:0 2px 10px rgba(0,0,0,0.1)}
    .loading{opacity:0;transition:opacity 0.3s}
    .loaded{opacity:1}
    </style>
    <?php
}
add_action('wp_head', 'iteck_inline_critical_css', 2);

/**
 * Remove query strings from static resources for better caching
 */
function iteck_remove_cssjs_ver($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
// Uncomment to enable (may affect cache busting)
// add_filter('style_loader_src', 'iteck_remove_cssjs_ver', 10, 2);
// add_filter('script_loader_src', 'iteck_remove_cssjs_ver', 10, 2);
