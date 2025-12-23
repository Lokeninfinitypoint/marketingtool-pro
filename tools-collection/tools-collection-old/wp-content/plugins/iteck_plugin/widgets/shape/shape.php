<?php

namespace IteckPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if (!defined('ABSPATH')) exit; // Exit if accessed directly



/**
 * @since 1.1.0 
 */
class Iteck_Shape extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'iteck-shape';
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
        return __('Iteck Shape', 'iteck_plg');
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
        return 'eicon-shape';
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
        return ['iteck-menu-elements'];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function register_controls()
    {

        $this->start_controls_section(
            'section_title',
            [
                'label' => __('Shape Settings', 'iteck_plg'),
            ]
        );

        $this->add_responsive_control(
            'shape_width',
            [
                'label' => esc_html__('Width', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'devices' => ['desktop', 'tablet', 'mobile'],
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .iteck-shape::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'shape_height',
            [
                'label' => esc_html__('Height', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'devices' => ['desktop', 'tablet', 'mobile'],
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .iteck-shape::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'shape_border_radius',
            [
                'label' => esc_html__('Border Radius', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'devices' => ['desktop', 'tablet', 'mobile'],
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .iteck-shape::after' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'shape_background',
				'label' => __('Shape Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-shape::after',
			]
		);

		$this->add_control(
			'iteck_animation',
			[
				'label' => esc_html__( 'Animation', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'iteck_plg' ),
					'rotate' => esc_html__( 'Rotate', 'iteck_plg' ),
					'wave' => esc_html__( 'Wave', 'iteck_plg' ),
					'slide-up-down' => esc_html__( 'Slide Up Down', 'iteck_plg' ),
					'slide-right-left' => esc_html__( 'Slide Right Left', 'iteck_plg' ),
					'move-right-left' => esc_html__( 'Move Right Left', 'iteck_plg' ),
					'scale-up-down' => esc_html__( 'Scale Up Down', 'iteck_plg' ),
				],
				'separator' => 'before',
				'prefix_class' => 'iteck-shape-animation-',
			]
		);
		
		$this->add_control(
			'iteck_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 200,
						'min' => 1,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-shape::after' => 'animation-duration: {{SIZE}}s;',
				],
				'condition' => [
					'iteck_animation!' => 'none'
				]
			]
		);
		
		$this->add_control(
			'iteck_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 200,
						'min' => 1,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-shape::after' => 'animation-delay: {{SIZE}}s;',
				],
				'condition' => [
					'iteck_animation!' => 'none'
				]
			]
		);

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings();

        ?>

        <div class="iteck-shape">

        </div>

    <?php }

    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function content_template()
    {
    }
}
