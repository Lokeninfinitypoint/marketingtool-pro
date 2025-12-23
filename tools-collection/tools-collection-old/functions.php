<?php
/*
Theme Name: WordStream Clone
Description: A WordPress theme designed to mimic WordStream's professional marketing website design
Version: 1.0
Author: Your Name
Template: Divi
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function wordstream_clone_setup() {
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('title-tag');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'wordstream-clone'),
        'footer' => __('Footer Menu', 'wordstream-clone'),
    ));
}
add_action('after_setup_theme', 'wordstream_clone_setup');

// Enqueue scripts and styles
function wordstream_clone_scripts() {
    // Enqueue parent theme style
    wp_enqueue_style('divi-style', get_template_directory_uri() . '/style.css');
    
    // Enqueue child theme style
    wp_enqueue_style('wordstream-clone-style', get_stylesheet_directory_uri() . '/style.css', array('divi-style'), '1.0');
    
    // Enqueue custom JavaScript
    wp_enqueue_script('wordstream-clone-script', get_stylesheet_directory_uri() . '/js/wordstream-clone.js', array('jquery'), '1.0', true);
    
    // Add Google Fonts
    wp_enqueue_style('wordstream-fonts', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap');
}
add_action('wp_enqueue_scripts', 'wordstream_clone_scripts');

// Add WordStream brand colors to Divi customizer
function wordstream_clone_customizer($wp_customize) {
    // Add WordStream Brand Section
    $wp_customize->add_section('wordstream_brand', array(
        'title' => __('WordStream Brand Settings', 'wordstream-clone'),
        'priority' => 30,
    ));
    
    // Primary Blue Color
    $wp_customize->add_setting('ws_primary_blue', array(
        'default' => '#1e3b99',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ws_primary_blue', array(
        'label' => __('Primary Blue', 'wordstream-clone'),
        'section' => 'wordstream_brand',
    )));
    
    // Accent Orange Color
    $wp_customize->add_setting('ws_accent_orange', array(
        'default' => '#ff6900',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ws_accent_orange', array(
        'label' => __('Accent Orange', 'wordstream-clone'),
        'section' => 'wordstream_brand',
    )));
}
add_action('customize_register', 'wordstream_clone_customizer');

// Add custom body classes
function wordstream_clone_body_classes($classes) {
    $classes[] = 'wordstream-clone';
    $classes[] = 'ws-override';
    return $classes;
}
add_filter('body_class', 'wordstream_clone_body_classes');

// Custom header function
function wordstream_clone_header() {
    ?>
    <div class="ws-header">
        <div class="ws-nav-container">
            <div class="ws-logo">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    echo '<a href="' . esc_url(home_url('/')) . '"><img src="' . get_stylesheet_directory_uri() . '/images/wordstream-logo.svg" alt="' . get_bloginfo('name') . '" /></a>';
                }
                ?>
            </div>
            
            <nav class="ws-navigation">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'ws-nav-menu',
                    'container' => false,
                    'fallback_cb' => 'wordstream_clone_fallback_menu',
                ));
                ?>
            </nav>
            
            <div class="ws-cta-buttons">
                <a href="<?php echo esc_url(home_url('/free-tools')); ?>" class="ws-btn ws-btn-secondary">Free Tools</a>
                <a href="<?php echo esc_url(home_url('/get-demo')); ?>" class="ws-btn ws-btn-primary">Get a Demo</a>
            </div>
            
            <button class="ws-mobile-menu-toggle" aria-label="Toggle mobile menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
    <?php
}

// Fallback menu
function wordstream_clone_fallback_menu() {
    echo '<ul class="ws-nav-menu">
        <li><a href="' . esc_url(home_url('/')) . '">Home</a></li>
        <li><a href="' . esc_url(home_url('/blog')) . '">Blog</a></li>
        <li><a href="' . esc_url(home_url('/learn')) . '">Learn</a></li>
        <li><a href="' . esc_url(home_url('/resources')) . '">Resources</a></li>
        <li><a href="' . esc_url(home_url('/about')) . '">About</a></li>
    </ul>';
}

// Custom footer function
function wordstream_clone_footer() {
    ?>
    <footer class="ws-footer">
        <div class="ws-footer-content">
            <div class="ws-grid ws-grid-4">
                <div class="ws-footer-section">
                    <h4>Free Tools</h4>
                    <ul class="ws-footer-links">
                        <li><a href="<?php echo esc_url(home_url('/google-ads-grader')); ?>">Google Ads Grader</a></li>
                        <li><a href="<?php echo esc_url(home_url('/facebook-ads-grader')); ?>">Facebook Ads Grader</a></li>
                        <li><a href="<?php echo esc_url(home_url('/keyword-tool')); ?>">Free Keyword Tool</a></li>
                        <li><a href="<?php echo esc_url(home_url('/website-grader')); ?>">Website Grader</a></li>
                    </ul>
                </div>
                
                <div class="ws-footer-section">
                    <h4>Learn</h4>
                    <ul class="ws-footer-links">
                        <li><a href="<?php echo esc_url(home_url('/blog')); ?>">Blog</a></li>
                        <li><a href="<?php echo esc_url(home_url('/guides')); ?>">Guides</a></li>
                        <li><a href="<?php echo esc_url(home_url('/ppc-university')); ?>">PPC University</a></li>
                        <li><a href="<?php echo esc_url(home_url('/marketing-101')); ?>">Marketing 101</a></li>
                    </ul>
                </div>
                
                <div class="ws-footer-section">
                    <h4>Solutions</h4>
                    <ul class="ws-footer-links">
                        <li><a href="<?php echo esc_url(home_url('/marketing-services')); ?>">Digital Marketing</a></li>
                        <li><a href="<?php echo esc_url(home_url('/agency-solutions')); ?>">Agency Solutions</a></li>
                        <li><a href="<?php echo esc_url(home_url('/multi-location')); ?>">Multi-Location</a></li>
                    </ul>
                </div>
                
                <div class="ws-footer-section">
                    <h4>Company</h4>
                    <ul class="ws-footer-links">
                        <li><a href="<?php echo esc_url(home_url('/about')); ?>">About WordStream</a></li>
                        <li><a href="<?php echo esc_url(home_url('/careers')); ?>">Careers</a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="ws-footer-bottom">
                <div class="ws-social-links">
                    <a href="#" aria-label="Facebook">📘</a>
                    <a href="#" aria-label="Twitter">🐦</a>
                    <a href="#" aria-label="LinkedIn">💼</a>
                    <a href="#" aria-label="Instagram">📷</a>
                    <a href="#" aria-label="YouTube">📹</a>
                </div>
                <p>&copy; <?php echo date('Y'); ?> WordStream Clone. All Rights Reserved. | 
                   <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">Privacy Policy</a> | 
                   <a href="<?php echo esc_url(home_url('/terms')); ?>">Terms & Conditions</a>
                </p>
            </div>
        </div>
    </footer>
    <?php
}

// Widget areas
function wordstream_clone_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'wordstream-clone'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here.', 'wordstream-clone'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Widget Area', 'wordstream-clone'),
        'id' => 'footer-widgets',
        'description' => __('Add widgets for the footer area.', 'wordstream-clone'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-widget-title">',
        'after_title' => '</h4>',
    ));
}
add_action('widgets_init', 'wordstream_clone_widgets_init');

// Custom post types for tools and resources
function wordstream_clone_custom_post_types() {
    // Free Tools Post Type
    register_post_type('free_tools', array(
        'labels' => array(
            'name' => 'Free Tools',
            'singular_name' => 'Free Tool',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-admin-tools',
    ));
    
    // Resources Post Type
    register_post_type('resources', array(
        'labels' => array(
            'name' => 'Resources',
            'singular_name' => 'Resource',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-book-alt',
    ));
}
add_action('init', 'wordstream_clone_custom_post_types');

// Add custom meta boxes
function wordstream_clone_meta_boxes() {
    add_meta_box(
        'tool_details',
        'Tool Details',
        'wordstream_clone_tool_details_callback',
        'free_tools'
    );
}
add_action('add_meta_boxes', 'wordstream_clone_meta_boxes');

function wordstream_clone_tool_details_callback($post) {
    wp_nonce_field('wordstream_clone_tool_details', 'wordstream_clone_tool_details_nonce');
    
    $tool_url = get_post_meta($post->ID, '_tool_url', true);
    $tool_features = get_post_meta($post->ID, '_tool_features', true);
    
    echo '<table class="form-table">';
    echo '<tr><th scope="row">Tool URL</th>';
    echo '<td><input type="url" name="tool_url" value="' . esc_attr($tool_url) . '" class="regular-text" /></td></tr>';
    echo '<tr><th scope="row">Features</th>';
    echo '<td><textarea name="tool_features" rows="5" class="large-text">' . esc_textarea($tool_features) . '</textarea></td></tr>';
    echo '</table>';
}

// Save meta box data
function wordstream_clone_save_tool_details($post_id) {
    if (!isset($_POST['wordstream_clone_tool_details_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['wordstream_clone_tool_details_nonce'], 'wordstream_clone_tool_details')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (isset($_POST['tool_url'])) {
        update_post_meta($post_id, '_tool_url', esc_url_raw($_POST['tool_url']));
    }
    
    if (isset($_POST['tool_features'])) {
        update_post_meta($post_id, '_tool_features', sanitize_textarea_field($_POST['tool_features']));
    }
}
add_action('save_post', 'wordstream_clone_save_tool_details');

// Add Divi Builder support for custom post types
function wordstream_clone_add_divi_support() {
    function et_divi_post_types($post_types) {
        $post_types[] = 'free_tools';
        $post_types[] = 'resources';
        return $post_types;
    }
    add_filter('et_builder_post_types', 'et_divi_post_types');
}
add_action('init', 'wordstream_clone_add_divi_support');

// Shortcodes for WordStream components
function wordstream_clone_hero_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => 'Stop wasting money in Google Ads',
        'subtitle' => 'Get an instant audit of your account with the Free Google Ads Performance Grader.',
        'cta_text' => 'Get Your Grade',
        'cta_url' => '#',
    ), $atts);
    
    ob_start();
    ?>
    <div class="ws-hero">
        <div class="ws-hero-container">
            <h1><?php echo esc_html($atts['title']); ?></h1>
            <p><?php echo esc_html($atts['subtitle']); ?></p>
            <a href="<?php echo esc_url($atts['cta_url']); ?>" class="ws-btn ws-btn-primary"><?php echo esc_html($atts['cta_text']); ?></a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('ws_hero', 'wordstream_clone_hero_shortcode');

function wordstream_clone_stats_shortcode($atts) {
    $atts = shortcode_atts(array(
        'stat1_number' => '142M',
        'stat1_label' => 'hours saved',
        'stat2_number' => '275M',
        'stat2_label' => 'leads delivered',
        'stat3_number' => '$15.9B',
        'stat3_label' => 'in revenue generated',
    ), $atts);
    
    ob_start();
    ?>
    <div class="ws-stats">
        <div class="ws-container">
            <h2>Get ready to grow your business</h2>
            <div class="ws-grid ws-grid-3">
                <div class="ws-stat-item">
                    <span class="ws-stat-number"><?php echo esc_html($atts['stat1_number']); ?></span>
                    <span class="ws-stat-label"><?php echo esc_html($atts['stat1_label']); ?></span>
                </div>
                <div class="ws-stat-item">
                    <span class="ws-stat-number"><?php echo esc_html($atts['stat2_number']); ?></span>
                    <span class="ws-stat-label"><?php echo esc_html($atts['stat2_label']); ?></span>
                </div>
                <div class="ws-stat-item">
                    <span class="ws-stat-number"><?php echo esc_html($atts['stat3_number']); ?></span>
                    <span class="ws-stat-label"><?php echo esc_html($atts['stat3_label']); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('ws_stats', 'wordstream_clone_stats_shortcode');

// Add admin styles
function wordstream_clone_admin_styles() {
    wp_enqueue_style('wordstream-clone-admin', get_stylesheet_directory_uri() . '/admin/admin-styles.css');
}
add_action('admin_enqueue_scripts', 'wordstream_clone_admin_styles');
?>