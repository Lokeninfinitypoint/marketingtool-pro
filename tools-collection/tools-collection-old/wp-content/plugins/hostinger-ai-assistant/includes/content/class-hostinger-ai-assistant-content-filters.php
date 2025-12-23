<?php

/**
 * The file that defines all content filters
 *
 * @link       https://hostinger.com
 * @since      1.1.2
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/admin
 */
class Hostinger_Ai_Assistant_Content_Filters {

    private Hostinger_Ai_Assistant_Helper $helper;

    public function __construct() {
        $this->helper = new Hostinger_Ai_Assistant_Helper();

        if ( $this->helper->is_preview_domain() ) {
            add_action(
                'wp_get_attachment_url',
                array(
                    $this,
                    'replace_media_attachment_url_with_wp_get_attachment_url',
                ),
                10,
                2
            );
        }

        add_filter( 'wp_kses_allowed_html', array( $this, 'custom_kses_allowed_html' ) );

        // Setup external featured image support.
        add_action( 'after_setup_theme', array( $this, 'external_featured_image_setup' ) );

        // Override the post thumbnail HTML with our external URL.
        add_filter( 'post_thumbnail_html', array( $this, 'external_featured_image_html' ), 10, 5 );

        // Save external featured image URL.
        add_action( 'save_post', array( $this, 'save_external_featured_image' ) );
    }

    /**
     * Setup support for external featured images
     */
    public function external_featured_image_setup() {
        if ( ! current_theme_supports( 'post-thumbnails' ) ) {
            add_theme_support( 'post-thumbnails' );
        }

        add_action( 'add_meta_boxes', array( $this, 'add_external_featured_image_meta_box' ) );
    }

    /**
     * Replace featured image HTML with external image when applicable
     */
    public function external_featured_image_html( $html, $post_id, $thumbnail_id, $size, $attr ) {
        $external_url = get_post_meta( $post_id, '_thumbnail_ext_url', true );

        if ( ! empty( $external_url ) ) {
            $attr = wp_parse_args(
                $attr,
                array(
                    'src'   => $external_url,
                    'alt'   => get_the_title( $post_id ),
                    'class' => 'external-featured-image wp-post-image',
                )
            );

            $html = '<img';
            foreach ( $attr as $name => $value ) {
                $html .= ' ' . $name . '="' . esc_attr( $value ) . '"';
            }
            $html .= ' />';
        }

        return $html;
    }

    /**
     * Add meta box to manage external featured image
     */
    public function add_external_featured_image_meta_box() {
        add_meta_box(
            'external_featured_image',
            __( 'External Featured Image', 'hostinger-ai-assistant' ),
            array(
                $this,
                'external_featured_image_meta_box_callback',
            ),
            null,
            'side',
            'low'
        );
    }

    /**
     * Meta box HTML
     */
    public function external_featured_image_meta_box_callback( $post ) {
        wp_nonce_field( 'external_featured_image_nonce', 'external_featured_image_nonce' );
        $external_url = get_post_meta( $post->ID, '_thumbnail_ext_url', true );
        ?>
        <p>
            <label for="external_featured_image_url"><?php _e( 'Image URL:', 'hostinger-ai-assistant' ); ?></label>
            <input type="url" id="external_featured_image_url" name="external_featured_image_url"
                    value="
                    <?php
                    echo esc_attr( $external_url );
                    ?>
                    " style="width:100%">
        </p>
        <?php
        if ( ! empty( $external_url ) ) :
            ?>
            <img src="
            <?php
            echo esc_url( $external_url );
            ?>
            " class="hts-external-featured-image-preview">
            <?php
        endif;
        ?>
        <?php
    }

    /**
     * Save the external featured image URL
     */
    public function save_external_featured_image( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! isset( $_POST['external_featured_image_nonce'] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_POST['external_featured_image_nonce'], 'external_featured_image_nonce' ) ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $url = isset( $_POST['external_featured_image_url'] ) ? esc_url_raw( $_POST['external_featured_image_url'] ) : '';
        if ( ! empty( $url ) ) {
            update_post_meta( $post_id, '_thumbnail_ext_url', $url );
        } else {
            delete_post_meta( $post_id, '_thumbnail_ext_url' );
        }
    }

    public function replace_media_attachment_url_with_wp_get_attachment_url( $url, $post_id ) {
        if ( empty( $_SERVER['HTTP_ORIGIN'] ) ) {
            return $url;
        }

        $parsed_url = parse_url( $url );

        if ( empty( $_SERVER['HTTP_ORIGIN'] ) ) {
            return $parsed_url['path'] ?? $url;
        }

        return $_SERVER['HTTP_ORIGIN'] . $parsed_url['path'];
    }

    public function custom_kses_allowed_html( $allowed_html ) {
        // Allow links with additional attributes.
        if ( ! isset( $allowed_html['a'] ) || ! is_array( $allowed_html['a'] ) ) {
            $allowed_html['a'] = array();
        }

        $allowed_html['a'] = array_merge(
            $allowed_html['a'],
            array(
                'href'   => true,
                'title'  => true,
                'target' => true,
            )
        );

        // Allow images with necessary attributes.
        if ( ! isset( $allowed_html['img'] ) || ! is_array( $allowed_html['img'] ) ) {
            $allowed_html['img'] = array();
        }

        $allowed_html['img'] = array_merge(
            $allowed_html['img'],
            array(
                'src'     => true,
                'srcset'  => true,
                'alt'     => true,
                'title'   => true,
                'class'   => true,
                'width'   => true,
                'height'  => true,
                'loading' => true,
                'sizes'   => true,
                'style'   => true,
            )
        );

        // Allow div containers which might wrap images.
        if ( ! isset( $allowed_html['div'] ) || ! is_array( $allowed_html['div'] ) ) {
            $allowed_html['div'] = array();
        }

        $allowed_html['div'] = array_merge(
            $allowed_html['div'],
            array(
                'class' => true,
                'id'    => true,
                'style' => true,
            )
        );

        return $allowed_html;
    }
}

$filters = new Hostinger_Ai_Assistant_Content_Filters();
