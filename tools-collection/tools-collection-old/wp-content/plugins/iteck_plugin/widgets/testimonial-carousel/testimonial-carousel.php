<?php

namespace IteckPlugin\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Group_Control_Box_Shadow;


if (! defined('ABSPATH')) exit; // Exit if accessed directly



/**
 * @since 1.0.0
 */
class Iteck_Testimonial_Carousel extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'iteck-testimonial-carousel';
    }

    //script depend
    public function get_script_depends()
    {
        return ['jquery-swiper', 'wow', 'iteck-addons-custom-scripts'];
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Iteck Testimonial Carousel', 'iteck_plg');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'fa fa-sun-o';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['iteck-elements'];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function _register_controls()
    {

        //--------------------------------------------------- Item Content ------------------------------------
        $this->start_controls_section(
            'section_item_content',
            [
                'label' => __('Item', 'iteck_plg'),
            ]
        );

        $this->add_control(
            'info_in_row',
            [
                'label'         => __('Info in Row', 'iteck_plg'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __('Yes', 'iteck_plg'),
                'label_off'     => __('No', 'iteck_plg'),
                'return_value'  => 'yes',
                'default'          => false,
                'condition'     => [
                    'cards_style!' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'cards_style',
            [
                'label'         => __('Cards Style', 'iteck_plg'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __('Yes', 'iteck_plg'),
                'label_off'     => __('No', 'iteck_plg'),
                'return_value'  => 'yes',
                'default'          => false,
                'condition'     => [
                    'info_in_row!' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'show_review',
            [
                'label'         => __('Show Rating', 'iteck_plg'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __('Yes', 'iteck_plg'),
                'label_off'     => __('No', 'iteck_plg'),
                'return_value'  => 'yes',
                'default'          => 'yes',
            ]
        );

        $this->add_control(
            'rating_position',
            [
                'label' => esc_html__('Rating Position', 'iteck_plg'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'bottom',
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'iteck_plg'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'iteck_plg'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
            ]
        );

        $this->add_control(
            'image_position',
            [
                'label' => esc_html__('Image Position', 'iteck_plg'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'bottom',
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'iteck_plg'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'iteck_plg'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
            ]
        );

        $this->add_control(
            'position_separate_line',
            [
                'label'         => __('Position in Separete Line', 'iteck_plg'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __('Yes', 'iteck_plg'),
                'label_off'     => __('No', 'iteck_plg'),
                'return_value'  => 'yes',
                'default'          => 'no',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'name',
            [
                'label' => __('Name', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('John Doe', 'iteck_plg'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'position',
            [
                'label' => __('Position', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('CEO', 'iteck_plg'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'text',
            [
                'label' => __('Text', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __('URL', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('Leave it blank if you don\'t want to use it', 'iteck_plg'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => __('Image', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'rate',
            [
                'label' => esc_html__('Rate', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    '1' => esc_html__('1', 'iteck_plg'),
                    '2' => esc_html__('2', 'iteck_plg'),
                    '3' => esc_html__('3', 'iteck_plg'),
                    '4' => esc_html__('4', 'iteck_plg'),
                    '5' => esc_html__('5', 'iteck_plg'),
                ],
            ]
        );

        $this->add_control(
            'items_list',
            [
                'label' => __('Testimonials List', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'name' => __('John Doe', 'iteck_plg'),
                        'position' => __('CEO', 'iteck_plg'),
                        'text' => __('This is a sample testimonial.', 'iteck_plg'),
                        'link' => [
                            'url' => '#',
                        ],
                        'image' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                        'rate' => '5',
                    ],
                ],
                'title_field' => '{{{ name }}}',
            ]
        );


        $this->add_control(
            'show_arrows',
            [
                'label'         => __('Show Arrows', 'iteck_plg'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __('Yes', 'iteck_plg'),
                'label_off'     => __('No', 'iteck_plg'),
                'return_value'  => 'yes',
                'default'          => 'no',
                'condition'        => [
                    'cards_style!' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'arrows_position',
            [
                'label' => esc_html__('Arrows Position', 'iteck_plg'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => esc_html__('Top', 'iteck_plg'),
                    'bottom' => esc_html__('Bottom', 'iteck_plg'),
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                    'cards_style!' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'         => __('Show Pagination', 'iteck_plg'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __('Yes', 'iteck_plg'),
                'label_off'     => __('No', 'iteck_plg'),
                'return_value'  => 'yes',
                'default'          => 'yes',
                'condition'        => [
                    'cards_style!' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'slides_to_view',
            [
                'label' => __('Slides To View', 'iteck_plg'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'condition'        => [
                    'cards_style!' => 'yes'
                ]

            ]
        );

        $this->add_control(
            'card_slides_to_view',
            [
                'label' => __('Slides To View', 'iteck_plg'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'condition'        => [
                    'cards_style' => 'yes'
                ]

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'item_settings',
            [
                'label' => __('Item Setting', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('testimonial_item');

        $this->start_controls_tab(
            'item_normal',
            [
                'label' => esc_html__('Normal', 'iteck_plg'),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'label' => __('Background', 'iteck_plg'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card',
                'exclude' => ['image'],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => esc_html__('Border Radius', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'item_hover',
            [
                'label' => esc_html__('Hover', 'iteck_plg'),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_background_hover',
                'label' => __('Background', 'iteck_plg'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card:hover',
                'exclude' => ['image'],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_hover',
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card:hover',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'item_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow_hover',
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'hr',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Padding', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'size_units' => ['px', '%'],
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_margin',
            [
                'label' => esc_html__('Margin', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'size_units' => ['px', '%'],
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'name_settings',
            [
                'label' => __('Name Setting', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__('Text Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .review-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .review-name',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'review_settings',
            [
                'label' => __('Review Setting', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'review_color',
            [
                'label' => esc_html__('Review Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .review-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'review_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .review-text',
            ]
        );

        $this->add_responsive_control(
            'review_align',
            [
                'label' => esc_html__('Alignment', 'iteck_plg'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'iteck_plg'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'iteck_plg'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'iteck_plg'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'iteck_plg'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card .review-text' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'position_settings',
            [
                'label' => __('Position Setting', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'position_color',
            [
                'label' => esc_html__('Position Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .review-position' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'position_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .review-position',
            ]
        );

        $this->add_control(
            'position_link_color',
            [
                'label' => esc_html__('Position link Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .review-position a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'position_link_typography',
                'label' => esc_html__('Position link Typography', 'iteck_plg'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .review-position a',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'image_settings',
            [
                'label' => __('Image Setting', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_size',
            [
                'label' => esc_html__('Image size', 'iteck_plg'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card .rev_user' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'image_align',
            [
                'label' => esc_html__('Alignment', 'iteck_plg'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'iteck_plg'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'iteck_plg'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'iteck_plg'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'iteck_plg'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card .user-img' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_outline',
            [
                'label' => esc_html__('Image Outline', 'iteck_plg'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card .rev_user' => 'outline-width: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card .rev_user',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card .rev_user' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('Padding', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card .rev_user' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'size_units' => ['px', '%'],
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card .rev_user' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'size_units' => ['px', '%'],
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'rating_settings',
            [
                'label' => __('Rating Setting', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'rating_color',
            [
                'label' => esc_html__('Rating Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card .rev_stars i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_size',
            [
                'label' => esc_html__('Rating Size', 'iteck_plg'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card .rev_stars' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_margin',
            [
                'label' => esc_html__('Margin', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_card .rev_stars' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'size_units' => ['px', '%'],
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'pagination_settings',
            [
                'label' => __('Pagination Setting', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'dot_color',
            [
                'label' => esc_html__('Dot Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .pagination_circle .swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dot_active_color',
            [
                'label' => esc_html__('Dot Active Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .pagination_circle .swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_active_color',
            [
                'label' => esc_html__('Border Active Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .pagination_circle .swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active:before' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_margin',
            [
                'label' => esc_html__('Dots Margin', 'iteck_plg'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .pagination_circle .swiper-pagination .swiper-pagination-bullet' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'size_units' => ['px', '%'],
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'arrows_settings',
            [
                'label' => __('Arrows Setting', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'arrows_size',
            [
                'label' => esc_html__('Arrows Size', 'iteck_plg'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-next::after' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-prev::after' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_weight',
            [
                'label' => esc_html__('Arrows weight', 'iteck_plg'),
                'type' => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'normal' => esc_html__('Normal', 'iteck_plg'),
                    'bold' => esc_html__('Bold', 'iteck_plg'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-next::after' => 'font-weight: {{VALUE}};',
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-prev::after' => 'font-weight: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_arrows_style');

        $this->start_controls_tab(
            'arrows_normal_tab',
            [
                'label' => esc_html__('Normal', 'iteck_plg'),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_border',
                'label' => esc_html__('Border', 'iteck_plg'),
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-next, {{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-prev',
            ]
        );

        $this->add_control(
            'arrows_border_radius',
            [
                'label' => esc_html__('Border Radius', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_box_shadow',
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-next, {{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-prev',
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => esc_html__('Arrow Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-next::after' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-prev::after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'arrows_bg',
                'label' => __('Background', 'iteck_plg'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-next, {{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-prev',
                'fields_options' => [
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{color.VALUE}};',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'arrows_hover_tab',
            [
                'label' => esc_html__('Hover', 'iteck_plg'),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrows_border_hover',
                'label' => esc_html__('Border', 'iteck_plg'),
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-next:hover, {{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-prev:hover',
            ]
        );

        $this->add_control(
            'arrows_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-prev:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrows_box_shadow_hover',
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-next:hover, {{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-prev:hover',
            ]
        );

        $this->add_control(
            'arrow_color_hover',
            [
                'label' => esc_html__('Arrow Color', 'iteck_plg'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-next:hover::after' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-prev:hover::after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'arrows_bg_hover',
                'label' => __('Background', 'iteck_plg'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-next:hover, {{WRAPPER}} .iteck-testimonial-carousel .reviews_slider .swiper-button-prev:hover',
                'fields_options' => [
                    'color' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{color.VALUE}};',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $items = $settings['slides_to_view'] ? $settings['slides_to_view'] : 1;
        $card_items = $settings['card_slides_to_view'] ? $settings['card_slides_to_view'] : 3;

?>
        <div class="iteck-testimonial-carousel <?php if ($settings['info_in_row'] == 'yes'){ echo 'style-2" data-slider-settings=\'{"items":' . $items . '}\'';
                                                } elseif ($settings['cards_style'] == 'yes'){ echo 'cards-style" data-slider-settings=\'{"items":' . $card_items . '}\'';
                                                } else { echo 'style-1" data-slider-settings=\'{"items":' . $items . '}\''; }; ?>>
            <div class="reviews_slider">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ($settings['items_list'] as $index => $item): ?>
                            <div class="swiper-slide">
                                <?php if ($settings['info_in_row'] != 'yes' && $settings['slides_to_view'] == 1): ?>
                                    <div class="row justify-content-center">
                                        <div class="col-lg-7">
                                        <?php endif; ?>
                                        <<?php if (!empty($item['link']['url'])): echo "a href='" . esc_url($item['link']['url']) . "'";
                                                if ($item['link']['is_external']): echo ' target="_blank"';
                                                endif;
                                            else: echo "div";
                                            endif; ?> class="reviews_card <?php if ($settings['info_in_row'] != 'yes' && $settings['slides_to_view'] == 1) echo "fade"; ?>">
                                            <?php if (!empty($item['image']['url']) && $settings['image_position'] == 'top'): ?>
                                                <div class="rev_user">
                                                    <img src="<?php echo esc_url($item['image']['url']) ?>" alt="">
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($settings['show_review'] == 'yes' && $settings['rating_position'] == 'top' && $settings['info_in_row'] != 'yes'): ?>
                                                <div class="rev_stars">
                                                    <?php for ($i = 0; $i < $item['rate']; $i++): ?>
                                                        <i class="fa fa-star"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            <?php endif; ?>
                                            <h5 class="review-text"><?php echo wp_kses_post($item['text']); ?></h5>
                                            <?php if ($settings['info_in_row'] == 'yes'): ?>
                                                <div class="user-img">
                                                <?php endif; ?>
                                                <?php if (!empty($item['image']['url']) && $settings['image_position'] == 'bottom'): ?>
                                                    <div class="rev_user">
                                                        <img src="<?php echo esc_url($item['image']['url']) ?>" alt="">
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($settings['show_review'] == 'yes' && $settings['rating_position'] == 'bottom' && $settings['info_in_row'] != 'yes'): ?>
                                                    <div class="rev_stars">
                                                        <?php for ($i = 0; $i < $item['rate']; $i++): ?>
                                                            <i class="fa fa-star"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="review-info">
                                                    <?php if ($settings['show_review'] == 'yes' && $settings['info_in_row'] == 'yes'): ?>
                                                        <div class="rev_stars">
                                                            <?php for ($i = 0; $i < $item['rate']; $i++): ?>
                                                                <i class="fa fa-star"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <span class="review-name"><?php echo wp_kses_post($item['name']); ?></span>
                                                    <div class="review-position <?php if ($settings['position_separate_line'] == 'yes') echo 'd-block'; ?>"><?php echo wp_kses_post($item['position']); ?></div>
                                                </div>
                                                <?php if ($settings['info_in_row'] == 'yes'): ?>
                                                </div>
                                            <?php endif; ?>
                                        </<?php if (!empty($item['link']['url'])): echo "a";
                                            else: echo "div";
                                            endif; ?>>
                                        <?php if ($settings['info_in_row'] != 'yes' && $settings['slides_to_view'] == 1): ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php if ($settings['show_pagination'] == 'yes'): ?>
                    <div class="pagination_circle">
                        <div class="swiper-pagination"></div>
                    </div>
                <?php endif;
                if ($settings['show_arrows'] == 'yes'): ?>
                    <div class="swiper-button-next <?php if ($settings['arrows_position'] == 'bottom') echo 'arrows-pos-bottom'; ?>"></div>
                    <div class="swiper-button-prev <?php if ($settings['arrows_position'] == 'bottom') echo 'arrows-pos-bottom'; ?>"></div>
                <?php endif; ?>
            </div>
        </div>
<?php
    }

    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function content_template() {}
}
