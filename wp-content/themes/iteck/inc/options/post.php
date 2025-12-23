<?php
/**
 * Single post Tab For Theme Option.
 *
 * @package iteck
 */

// -> START Single post Options

Redux::setSection($iteck_pre, array(
        'title' => esc_html__('Single', 'iteck'),
        'id' => 'blog-single-option',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => [
            [
                'id' => 'iteck_single_type_layout',
                'type' => 'button_set',
                'title' => esc_html__('Default Post Layout', 'iteck'),
                'desc' => esc_html__('Note: each Post can be additionally customized within its Meta boxes section.', 'iteck'),
                'options' => [
                    '1' => esc_html__('Elegant', 'iteck'),
                    '2' => esc_html__('Classic', 'iteck'),
                    '3' => esc_html__('Overlay Image', 'iteck')
                ],
                'default' => '2'
            ],
            [
                'id' => 'blog_single_page_title-start',
                'type' => 'section',
                'title' => esc_html__('Page Title', 'iteck'),
                'indent' => true,
            ],
            [
                'id' => 'blog_title_conditional',
                'type' => 'switch',
                'title' => esc_html__('Page Title Text', 'iteck'),
                'on' => esc_html__('Custom', 'iteck'),
                'off' => esc_html__('Default', 'iteck'),
                'default' => true,
            ],
            [
                'id' => 'post_single_page_title_text',
                'type' => 'text',
                'title' => esc_html__('Custom Page Title Text', 'iteck'),
                'default' => esc_html__('Blog Post', 'iteck'),
                'required' => ['blog_title_conditional', '=', true],
            ],
            [
                'id' => 'blog_single_page_title_breadcrumbs_switch',
                'type' => 'switch',
                'title' => esc_html__('Breadcrumbs', 'iteck'),
                'on' => esc_html__('Use', 'iteck'),
                'off' => esc_html__('Hide', 'iteck'),
                'default' => true,
            ],
            [
                'id' => 'post_single_page_title_bg_image',
                'type' => 'background',
                'title' => esc_html__('Background Image', 'iteck'),
                'preview' => false,
                'preview_media' => true,
                'background-color' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                    'background-color' => '#101d27',
                ],
            ],
            [
                'id' => 'single_padding_layout_3',
                'type' => 'spacing',
                'mode' => 'padding',
                'all' => false,
                'bottom' => true,
                'top' => true,
                'left' => false,
                'right' => false,
                'title' => esc_html__('Padding Top/Bottom', 'iteck'),
                'desc' => esc_html__('Note: this setting affects only the "Overlay Image" post layout.', 'iteck'),
                'default' => [
                    'padding-top' => '320px',
                    'padding-bottom' => '0px',
                ],
            ],
            [
                'id' => 'blog_single_page_title-end',
                'type' => 'section',
                'indent' => false,
            ],

            [
                'id' => 'blog_single_appearance-start',
                'type' => 'section',
                'title' => esc_html__('Appearance', 'iteck'),
                'indent' => true,
                'required' => array( 'iteck_single_type_layout', '=', '2' )
            ],
            [
                'id' => 'featured_image_type', 
                'type' => 'button_set',
                'title' => esc_html__('Featured Image', 'iteck'),
                'options' => [
                    'default' => esc_html__('Default', 'iteck'),
                    'off' => esc_html__('Off', 'iteck'),
                ],
                'default' => 'default'
            ],


            [
                'id' => 'blog_single_appearance-end',
                'type' => 'section',
                'indent' => false,
            ],



            [
                'id' => 'single_post_related-start',
                'type' => 'section',
                'title' => esc_html__('Related posts', 'iteck'),
                'indent' => true,
            ],
            [
                'title' => esc_html__( 'Single Related Post Count', 'iteck' ),
                'id' => 'related_perpage',
                'type' => 'slider',
                'default' => 3,
                'min' => 1,
                'step' => 1,
                'max' => 24,
                'display_value' => 'text',
                //'required' => array( 'single_related_visibility', '=', '1' )
            ],
            [
                'id' => 'iteck_related_layout',
                'type' => 'button_set',
                'title' => esc_html__('Default Post Layout', 'iteck'),
                'desc' => esc_html__('Note: each Post can be additionally customized within its Meta boxes section.', 'iteck'),
                'options' => [
                    '0' => esc_html__('None', 'iteck'),
                    '1' => esc_html__('Standard', 'iteck'),
                    '2' => esc_html__('Slider', 'iteck')
                ],
                'default' => '1'
            ],
            [
                'id' => 'single_post_extras-start',
                'type' => 'section',
                'title' => esc_html__('Post Extras', 'iteck'),
                'indent' => true,
            ],

            [
                'id' => 'iteck_post_share_box',
                'type' => 'switch',
                'title' => esc_html__('Share Box', 'iteck'),
                'default' => false
            ],
        ]
));

?>