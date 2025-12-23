<?php

namespace IteckPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


if (!defined('ABSPATH')) exit; // Exit if accessed directly



/**
 * @since 1.0.0
 */
class Iteck_Button extends Widget_Base
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
		return 'iteck-button';
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
		return __('Iteck Button', 'iteck_plg');
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
		return 'eicon-button';
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

		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Button Settings', 'iteck_plg'),
			]
		);

		$this->add_control(
			'btn_text',
			[
				'label' => __('Button Text', 'iteck_plg'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Click now',

			]
		);

		$this->add_control(
			'link',
			[
				'label' => __('Button Link', 'iteck_plg'),
				'type' => Controls_Manager::URL,
				'placeholder' => 'Leave Link here',
			]
		);
		$this->add_control(
			'button_alignment',
			[
				'label' => __('Button Text Alignment', 'iteck_plg'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'iteck_plg'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'iteck_plg'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'iteck_plg'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'elementor-align-',
				'default' => 'left',
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__('Icon', 'iteck_plg'),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => esc_html__('Icon Position', 'iteck_plg'),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__('Before', 'iteck_plg'),
					'right' => esc_html__('After', 'iteck_plg'),
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label' => esc_html__('Icon Spacing', 'iteck_plg'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-button .iteck-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-button .iteck-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'animated_gradient_bg',
			[
				'label' => esc_html__( 'Animated Gradient Backgorund', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'your-plugin' ),
				'label_off' => esc_html__( 'Off', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'animated_icon',
			[
				'label' => esc_html__( 'Animated Icon', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'your-plugin' ),
				'label_off' => esc_html__( 'Off', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__('View', 'iteck_plg'),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label' => esc_html__('Button ID', 'iteck_plg'),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'title' => esc_html__('Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor'),
				'description' => sprintf(
					/* translators: %1$s Code open tag, %2$s: Code close tag. */
					esc_html__('Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'elementor'),
					'<code>',
					'</code>'
				),
				'separator' => 'before',

			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__('Button', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_display',
			[
				'label' => esc_html__('Button Display Type', 'iteck_plg'),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline-block',
				'options' => [
					'block' => esc_html__('Block', 'iteck_plg'),
					'inline-block' => esc_html__('Inline Block', 'iteck_plg'),
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-button' => 'display: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .iteck-button',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .iteck-button',
			]
		);

		$this->start_controls_tabs('tabs_button_style');

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__('Normal', 'iteck_plg'),
			]
		);

		$this->add_control(
			'button_text_color_type',
			[
				'label' => esc_html__( 'Text color type', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => 'Solid',
					'gradient' => 'Gradient',
				],
				'default' => 'solid',
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-button' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
				'condition' => [
					'button_text_color_type' => 'solid'
				]
			]
		);

		$this->add_control(
            'button_text_gradient_bg_color1',
            [
                'label' => _x( 'First Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'title' => _x( 'First Color', 'Background Control', 'iteck_plg' ),
                'render_type' => 'ui',
                'condition' => [
                    'button_text_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );


        $this->add_control(
            'button_text_gradient_bg_color1_stop', 
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
                    'button_text_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'button_text_gradient_bg_color2',
            [
                'label' => _x( 'Second Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'button_text_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'button_text_gradient_bg_color2_stop', 
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
                    'button_text_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'button_text_gradient_type', 
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
                    'button_text_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'button_text_gradient_angle', 
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
                    '{{WRAPPER}} .iteck-button .iteck-button-text' => 'background-image: linear-gradient({{SIZE}}{{UNIT}}, {{button_text_gradient_bg_color1.VALUE}} {{button_text_gradient_bg_color1_stop.SIZE}}{{button_text_gradient_bg_color1_stop.UNIT}},{{button_text_gradient_bg_color2.VALUE}} {{button_text_gradient_bg_color2_stop.SIZE}}{{button_text_gradient_bg_color2_stop.UNIT}}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;',
				],

                'condition' => [
                    'button_text_color_type' => [ 'gradient'],
                    'button_text_gradient_type' => 'linear',
                ],
                'of_type' => 'gradient',
            ]
        );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__('Background', 'iteck_plg'),
				'types' => ['classic', 'gradient'],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .iteck-button, {{WRAPPER}} .iteck-button.reverse .btn-animated-gr',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background: {{color.VALUE}}; background-image: none;',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .iteck-button',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'border_gradient',
				'label' => __('Border Gradient Color', 'iteck_plg'),
				'types' => [ 'gradient' ],
				'selector' => '{{WRAPPER}} .iteck-button',
				'exclude' => ['image'],
				'fields_options' => [
					'gradient_type' => [
						'default' => 'radial',
						'type' => Controls_Manager::HIDDEN,
					],
					'color' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background-color: none;',
						],
					],
					'gradient_angle' => [
						'selectors' => [
							'{{SELECTOR}}' => 'border-image-source: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
						],
						'type' => Controls_Manager::HIDDEN,
					],
					'gradient_position' => [
						'selectors' => [
							'{{SELECTOR}}' => 'border-image-slice: 1; border-image-source: radial-gradient(circle farthest-corner at 10% 20%, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
						],
					],
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-button .btn-animated-gr' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__('Hover', 'iteck_plg'),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__('Text Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-button:hover, {{WRAPPER}} .iteck-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-button:hover svg, {{WRAPPER}} .iteck-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'label' => esc_html__('Background', 'iteck_plg'),
				'types' => ['classic', 'gradient'],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .iteck-button:hover, {{WRAPPER}} .iteck-button:focus, {{WRAPPER}} .iteck-button .btn-animated-gr, {{WRAPPER}} .iteck-button:focus .btn-animated-gr',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background: {{color.VALUE}}; background-image: none;',
						],
					],
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border_hover',
				'selector' => '{{WRAPPER}} .iteck-button:hover',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'border_gradient_hover',
				'label' => __('Border Gradient Color', 'iteck_plg'),
				'types' => [ 'gradient' ],
				'selector' => '{{WRAPPER}} .iteck-button:hover',
				'exclude' => ['image'],
				'fields_options' => [
					'gradient_type' => [
						'default' => 'radial',
						'type' => Controls_Manager::HIDDEN,
					],
					'color' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background-color: none;',
						],
					],
					'gradient_angle' => [
						'selectors' => [
							'{{SELECTOR}}' => 'border-image-source: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
						],
						'type' => Controls_Manager::HIDDEN,
					],
					'gradient_position' => [
						'selectors' => [
							'{{SELECTOR}}' => 'border-image-slice: 1; border-image-source: radial-gradient(circle farthest-corner at 10% 20%, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
						],
					],
				],
			]
		);

		$this->add_control(
			'border_radius_hover',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-button:hover .btn-animated-gr' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .iteck-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__('Button Icon', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'selected_icon!' => '',
				]
			]
		);

		$this->start_controls_tabs('tabs_button_icon_style');

		$this->start_controls_tab(
			'tab_button_icon',
			[
				'label' => esc_html__('Normal', 'iteck_plg'),
			]
		);

		$this->add_control(
			'button_icon_color',
			[
				'label' => __('Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-button .iteck-button-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-button .iteck-button-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_icon_background',
				'label' => __('Button Icon Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-button .iteck-button-icon',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'selector' => '{{WRAPPER}} .iteck-button .iteck-button-icon',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'icon_border_gradient',
				'label' => __('Border Gradient Color', 'iteck_plg'),
				'types' => [ 'gradient' ],
				'selector' => '{{WRAPPER}} .iteck-button .iteck-button-icon',
				'exclude' => ['image'],
				'fields_options' => [
					'gradient_type' => [
						'default' => 'radial',
						'type' => Controls_Manager::HIDDEN,
					],
					'color' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background-color: none;',
						],
					],
					'gradient_angle' => [
						'selectors' => [
							'{{SELECTOR}}' => 'border-image-source: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
						],
						'type' => Controls_Manager::HIDDEN,
					],
					'gradient_position' => [
						'selectors' => [
							'{{SELECTOR}}' => 'border-image-slice: 1; border-image-source: radial-gradient(circle farthest-corner at 10% 20%, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
						],
					],
				],
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-button .iteck-button-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_icon_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-button .iteck-button-icon',
			]
		);
		
		$this->add_control(
			'button_icon_margin',
			[
				'label' => esc_html__('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-button .iteck-button-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_icon_hover',
			[
				'label' => esc_html__('Hover', 'iteck_plg'),
			]
		);

		$this->add_control(
			'button_icon_background_color_hover',
			[
				'label' => __('Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-button:hover .iteck-button-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-button:hover .iteck-button-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_icon_background_hover',
				'label' => __('Button Icon Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-button:hover .iteck-button-icon',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_border_hover',
				'selector' => '{{WRAPPER}} .iteck-button:hover .iteck-button-icon',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'icon_border_gradient_hover',
				'label' => __('Border Gradient Color', 'iteck_plg'),
				'types' => [ 'gradient' ],
				'selector' => '{{WRAPPER}} .iteck-button:hover .iteck-button-icon',
				'exclude' => ['image'],
				'fields_options' => [
					'gradient_type' => [
						'default' => 'radial',
						'type' => Controls_Manager::HIDDEN,
					],
					'color' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background-color: none;',
						],
					],
					'gradient_angle' => [
						'selectors' => [
							'{{SELECTOR}}' => 'border-image-source: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
						],
						'type' => Controls_Manager::HIDDEN,
					],
					'gradient_position' => [
						'selectors' => [
							'{{SELECTOR}}' => 'border-image-slice: 1; border-image-source: radial-gradient(circle farthest-corner at 10% 20%, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
						],
					],
				],
			]
		);

		$this->add_control(
			'icon_border_radius_hover',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-button:hover .iteck-button-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_icon_box_shadow_hover',
				'selector' => '{{WRAPPER}} .iteck-button:hover .iteck-button-icon',
			]
		);

		$this->add_control(
			'button_icon_animation_hover',
			[
				'label' => esc_html__('Icon Animation', 'iteck_plg'),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__('None', 'iteck_plg'),
					'right-to-left' => esc_html__('Right to Left', 'iteck_plg'),
				],
			]
		);
		
		$this->add_control(
			'button_icon_margin_hover',
			[
				'label' => esc_html__('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-button:hover .iteck-button-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_icon_wrapper',
			[
				'label' => esc_html__( 'Icon Wrapper', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-button .iteck-button-icon' => 'line-height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; text-align: center;',
					'{{WRAPPER}} .iteck-button .iteck-button-icon i' => 'line-height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		

		$this->add_control(
			'button_icon_size',
			[
				'label' => esc_html__( 'Icon size', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-button .iteck-button-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-button .iteck-button-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'button_icon_padding',
			[
				'label' => esc_html__('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-button .iteck-button-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'animated_gradient_bg_style',
			[
				'label' => esc_html__('Button', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'animated_gradient_bg' => 'yes',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'animated_gradient_background',
				'label' => esc_html__( 'Background', 'plugin-name' ),
				'types' => [ 'gradient' ],
				'selector' => '{{WRAPPER}} .iteck-button.animated-gradient-bg::before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'drop_shadow',
			[
				'label' => esc_html__('Drop Shadow', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'drop_shadow_offset_x',
			[
				'label' => esc_html__('Offset x', 'iteck_plg'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 200,
						'min' => 0,
						'step' => 1,
					],
				],
                'render_type' => 'ui',
			]
		);

		$this->add_control(
			'drop_shadow_offset_y',
			[
				'label' => esc_html__('Offset y', 'iteck_plg'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 200,
						'min' => 0,
						'step' => 1,
					],
				],
                'render_type' => 'ui',
			]
		);

		$this->add_control(
			'drop_shadow_blur_radius',
			[
				'label' => esc_html__('Blur Radius', 'iteck_plg'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 200,
						'min' => 0,
						'step' => 1,
					],
				],
                'render_type' => 'ui',
			]
		);

		$this->add_control(
			'drop_shadow_color',
			[
				'label' => __('Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-button' => 'filter: drop-shadow({{drop_shadow_offset_x.SIZE}}px {{drop_shadow_offset_y.SIZE}}px {{drop_shadow_blur_radius.SIZE}}px {{VALUE}});',
				],
			]
		);

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

		$migrated = isset($settings['__fa4_migrated']['selected_icon']);
		$is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();

		if (!$is_new && empty($settings['icon_align'])) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			//old default
			$settings['icon_align'] = $this->get_settings('icon_align');
		}

		$animated_class = '';

		if($settings['animated_gradient_bg'] == 'yes') $animated_class .= 'animated-gradient-bg';

		if($settings['animated_icon'] == 'yes') $animated_class .= ' animated-icon';


		$this->add_render_attribute([
			'content-wrapper' => [
				'class' => ['iteck-button-content-wrapper'],
			],
			'icon-align' => [
				'class' => [
					'iteck-button-icon',
					'iteck-align-icon-' . $settings['icon_align'],
					'hover-animation-' . $settings['button_icon_animation_hover']
				],
			],
			'btn_text' => [
				'class' => ['iteck-button-text'],
			],
		]);

		$this->add_inline_editing_attributes('btn_text', 'none');
?>

		<a href="<?php echo esc_url($settings['link']['url']); ?>" <?php if ( $settings['link']['is_external'] ) {echo'target="_blank"';} ?> class="iteck-button <?php echo esc_attr($animated_class); ?>">
			<span <?php $this->print_render_attribute_string('content-wrapper'); ?>>
				<?php if (!empty($settings['icon']) or !empty($settings['selected_icon']['value']) and ($settings['icon_align'] == 'left' or $settings['animated_icon'] == 'yes')) : ?>
					<span <?php $this->print_render_attribute_string('icon-align'); ?>>
						<?php if ($is_new || $migrated) :
							Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);
						else : ?>
							<i class="<?php echo esc_attr($settings['icon']); ?>" aria-hidden="true"></i>
						<?php endif; ?>
					</span>
				<?php endif; ?>
				<span <?php $this->print_render_attribute_string('btn_text'); ?>>
					<?php $this->print_unescaped_setting('btn_text'); ?>
				</span>
				<?php if (!empty($settings['icon']) or !empty($settings['selected_icon']['value'])  and ($settings['icon_align'] == 'right' or $settings['animated_icon'] == 'yes')) : ?>
					<span <?php $this->print_render_attribute_string('icon-align'); ?>>
						<?php if ($is_new || $migrated) :
							Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);
						else : ?>
							<i class="<?php echo esc_attr($settings['icon']); ?>" aria-hidden="true"></i>
						<?php endif; ?>
					</span>
				<?php endif; ?>
			</span>
		</a>

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
	protected function content_template()
	{
	}
}
