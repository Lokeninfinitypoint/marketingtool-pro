<?php
namespace Elementor;

class Iteck_Extend_section {

    public function __construct() {

        /**
         * Section Controls
         */
        add_action( 'elementor/element/section/section_advanced/after_section_end', [$this, 'register_section_controls'] );
    }

    /**
     * Section Controls
     */
    public function register_section_controls( Controls_Stack $element ) {
        $element->start_controls_section(
            'iteck_onepagescroll_section',
            [
                'label'         => esc_html__( 'Iteck Sticky Settings', 'iteck_plg' ),
                'tab'           => Controls_Manager::TAB_ADVANCED,
                'hide_in_inner' => false,
            ]
        );
        // $element->add_control(
        //     'iteck_is_sticky',
        //     [
        //         'label'                 => esc_html__( 'Enable Sticky', 'iteck_plg' ),
        //         'type'                  => Controls_Manager::SWITCHER,
        //         'frontend_available'    => true,
        //         'return_value'          => 'section',
        //         'prefix_class'          => 'iteck-sticky-', 
        //     ]
        // );

		$element->add_control(
			'sticky',
			[
				'label' => __( 'Sticky', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'iteck_plg' ),
					'top' => __( 'Top', 'iteck_plg' ),
				],
				'separator' => 'before',
				'render_type' => 'none',
				'frontend_available' => true,
				'prefix_class'          => 'iteck-sticky-',
			]
		);

        $element->add_control(
            'sticky_background',
            [
                'label'     => __( 'Background Scroll', 'iteck_plg' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-section.is-stuck' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'sticky' => 'top',
                ],
            ]
        );

        // $element->add_control(
        //     'sticky_background4',
        //     [
        //         'label'     => __( 'Background Scroll', 'iteck_plg' ),
        //         'type'      => Controls_Manager::COLOR,
        //         'selectors' => [
        //             '{{WRAPPER}}.elementor-section' => 'background: {{VALUE}};',
        //             '{{WRAPPER}}.elementor-section' => 'background: linear-gradient( #12c2e9, #c471ed, #f64f59);',
        //         ],
        //         'condition' => [
        //             'sticky' => 'top',
        //         ],
        //     ]
        // );
        
        $element->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'scroll_box_shadow',
                'label'     => __( 'Scroll Shadow', 'iteck_plg' ),
                'selector' => '{{WRAPPER}} .elementor-section.is-stuck',
            ]
        );


        $element->add_responsive_control(
            'offset_space',
            [
                'label' => __( 'Offset', 'iteck_plg' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.is-stuck' => 'top: {{SIZE}}{{UNIT}};',
                    '.admin-bar {{WRAPPER}}.is-stuck' => 'top: calc({{SIZE}}{{UNIT}} + 32px);', 
                ],
                'condition' => [
                    'sticky' => 'top',
                ],
            ]
        );

        $element->add_control(
            'separator_panel_style',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $element->add_control(
            'enable_gradient',
            [
                'label' => __( 'Enable Gradient (3rd)', 'iteck_plg' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'iteck_plg' ),
                'label_off' => __( 'No', 'iteck_plg' ),
                'return_value' => 'yes',
                'default' => false,
            ]
        );

        $element->add_control(
            'color',
            [
                'label' => _x( 'Gradient Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'title' => _x( 'Background Color', 'Background Control', 'iteck_plg' ),
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
            ]
        );


        $element->add_control(
            'color_stop', 
            [
                'label' => _x( 'Location', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
                'of_type' => 'gradient',
            ]
        );
        $element->add_control(
            'color_a',
            [
                'label' => _x( 'Second Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'color_a_stop', 
            [
                'label' => _x( 'Location', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'color_b',
            [
                'label' => _x( 'Second Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'color_b_stop', 
            [
                'label' => _x( 'Location', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'gradient_type', 
            [
                'label' => _x( 'Type', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => _x( 'Linear', 'Background Control', 'iteck_plg' ),
                    'radial' => _x( 'Radial', 'Background Control', 'iteck_plg' ),
                ],
                'default' => 'linear',
                'render_type' => 'ui',
                'condition' => [
                    'enable_gradient' => [ 'yes'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'gradient_angle', 
            [
                'label' => _x( 'Angle', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 180,
                ],
                'range' => [
                    'deg' => [
                        'step' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-section' => 'background: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}},{{color_a.VALUE}} {{color_a_stop.SIZE}}{{color_a_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}});',
                ],

                'condition' => [
                    'enable_gradient' => [ 'yes'],
                    'gradient_type' => 'linear',
                ],
                'of_type' => 'gradient',
            ]
        );


        $element->end_controls_section();

        /*
        $element->start_controls_section(
            'iteck_background_section',
            [
                'label'         => esc_html__( 'Iteck Background Settings', 'iteck_plg' ),
                'tab'           => Controls_Manager::TAB_ADVANCED,
                'hide_in_inner' => false,
            ]
        );

        $element->add_control(
			'light_choose_bg_type',
			[
				'label' => __( 'Light Mode Background', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'classic' => [
						'title' => __( 'Classic', 'iteck_plg' ),
						'icon' => 'eicon-paint-brush',
					],
					'gradient' => [
						'title' => __( 'Gradient', 'iteck_plg' ),
						'icon' => 'eicon-barcode',
					],
				],
				'toggle' => true,
			]
		);

        $element->add_control(
            'light_classic_bg_color',
            [
                'label' => _x( 'Background Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'title' => _x( 'Background Color', 'Background Control', 'iteck_plg' ),
                'selectors' => [
                    '{{WRAPPER}}.elementor-section' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'light_choose_bg_type' => [ 'classic'],
                ],
            ]
        );

        $element->add_control(
            'light_gradient_bg_color1',
            [
                'label' => _x( 'First Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'title' => _x( 'First Color', 'Background Control', 'iteck_plg' ),
                'render_type' => 'ui',
                'condition' => [
                    'light_choose_bg_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );


        $element->add_control(
            'light_gradient_bg_color1_stop', 
            [
                'label' => _x( 'Location', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'light_choose_bg_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'light_gradient_bg_color2',
            [
                'label' => _x( 'Second Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'light_choose_bg_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'light_gradient_bg_color2_stop', 
            [
                'label' => _x( 'Location', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'light_choose_bg_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'light_gradient_type', 
            [
                'label' => _x( 'Type', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => _x( 'Linear', 'Background Control', 'iteck_plg' ),
                    'radial' => _x( 'Radial', 'Background Control', 'iteck_plg' ),
                ],
                'default' => 'linear',
                'render_type' => 'ui',
                'condition' => [
                    'light_choose_bg_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'light_gradient_angle', 
            [
                'label' => _x( 'Angle', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 180,
                ],
                'range' => [
                    'deg' => [
                        'step' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-section' => 'background: linear-gradient({{SIZE}}{{UNIT}}, {{light_gradient_bg_color1.VALUE}} {{light_gradient_bg_color1_stop.SIZE}}{{light_gradient_bg_color1_stop.UNIT}},{{light_gradient_bg_color2.VALUE}} {{light_gradient_bg_color2_stop.SIZE}}{{light_gradient_bg_color2_stop.UNIT}});',
                ],

                'condition' => [
                    'light_choose_bg_type' => [ 'gradient'],
                    'light_gradient_type' => 'linear',
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
			'dark_choose_bg_type',
			[
				'label' => __( 'Dark Mode Background', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'classic' => [
						'title' => __( 'Classic', 'iteck_plg' ),
						'icon' => 'eicon-paint-brush',
					],
					'gradient' => [
						'title' => __( 'Gradient', 'iteck_plg' ),
						'icon' => 'eicon-barcode',
					],
				],
				'toggle' => true,
			]
		);

        $element->add_control(
            'dark_classic_bg_color',
            [
                'label' => _x( 'Background Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'title' => _x( 'Background Color', 'Background Control', 'iteck_plg' ),
                'selectors' => [
                    'body.iteck-dark-mode {{WRAPPER}}.elementor-section' => 'background: {{VALUE}};',
                    '@media(prefers-color-scheme:dark){body.iteck-auto-mode {{WRAPPER}}.elementor-section' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'dark_choose_bg_type' => [ 'classic'],
                ],
            ]
        );

        $element->add_control(
            'dark_gradient_bg_color1',
            [
                'label' => _x( 'First Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'title' => _x( 'First Color', 'Background Control', 'iteck_plg' ),
                'render_type' => 'ui',
                'condition' => [
                    'dark_choose_bg_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );


        $element->add_control(
            'dark_gradient_bg_color1_stop', 
            [
                'label' => _x( 'Location', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'dark_choose_bg_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'dark_gradient_bg_color2',
            [
                'label' => _x( 'Second Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'dark_choose_bg_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'dark_gradient_bg_color2_stop', 
            [
                'label' => _x( 'Location', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'dark_choose_bg_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'dark_gradient_type', 
            [
                'label' => _x( 'Type', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => _x( 'Linear', 'Background Control', 'iteck_plg' ),
                    'radial' => _x( 'Radial', 'Background Control', 'iteck_plg' ),
                ],
                'default' => 'linear',
                'render_type' => 'ui',
                'condition' => [
                    'dark_choose_bg_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->add_control(
            'dark_gradient_angle', 
            [
                'label' => _x( 'Angle', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 180,
                ],
                'range' => [
                    'deg' => [
                        'step' => 10,
                    ],
                ],
                'selectors' => [
                    'body.iteck-dark-mode {{WRAPPER}}.elementor-section' => 'background: linear-gradient({{SIZE}}{{UNIT}}, {{dark_gradient_bg_color1.VALUE}} {{dark_gradient_bg_color1_stop.SIZE}}{{dark_gradient_bg_color1_stop.UNIT}},{{dark_gradient_bg_color2.VALUE}} {{dark_gradient_bg_color2_stop.SIZE}}{{dark_gradient_bg_color2_stop.UNIT}});',
                    '@media (prefers-color-scheme: dark) { body.iteck-auto-mode {{WRAPPER}}.elementor-section' => 'background: linear-gradient({{SIZE}}{{UNIT}}, {{dark_gradient_bg_color1.VALUE}} {{dark_gradient_bg_color1_stop.SIZE}}{{dark_gradient_bg_color1_stop.UNIT}},{{dark_gradient_bg_color2.VALUE}} {{dark_gradient_bg_color2_stop.SIZE}}{{dark_gradient_bg_color2_stop.UNIT}});',
                ],

                'condition' => [
                    'dark_choose_bg_type' => [ 'gradient'],
                    'dark_gradient_type' => 'linear',
                ],
                'of_type' => 'gradient',
            ]
        );

        $element->end_controls_section();
        */
    }
    
}
new Iteck_Extend_section();
?>