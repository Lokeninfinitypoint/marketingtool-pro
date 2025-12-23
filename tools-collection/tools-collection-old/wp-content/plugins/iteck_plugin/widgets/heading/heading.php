<?php
namespace IteckPlugin\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Utils;


/**
 * Elementor heading widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class Iteck_Heading extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve heading widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'iteck-heading';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve heading widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Iteck Heading', 'iteck_plg' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve heading widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-t-letter';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the heading widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'iteck-elements' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'heading', 'title', 'text' ];
	}

	/**
	 * Register heading widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your title', 'iteck_plg' ),
				'default' => esc_html__( 'Add Your Heading Text Here', 'iteck_plg' ),
				'description' => esc_html__( 'Note: use <span>...</span> to highlight text', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'iteck_plg' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'iteck_plg' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'iteck_plg' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'iteck_plg' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => __( 'H1', 'iteck_plg' ),
					'h2' => __( 'H2', 'iteck_plg' ),
					'h3' => __( 'H3', 'iteck_plg' ),
					'h4' => __( 'H4', 'iteck_plg' ),
					'h5' => __( 'H5', 'iteck_plg' ),
					'h6' => __( 'H6', 'iteck_plg' ),
					'div' => __( 'div', 'iteck_plg' ),
					'span' => __( 'span', 'iteck_plg' ),
					'p' => __( 'P', 'iteck_plg' ),
				],
				'default' => 'h2',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'custom_underline_section',
			[
				'label' => esc_html__( 'Custom Underline', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'custom_underline',
			[
				'label'         => __( 'Custom Underline', 'iteck_plg' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'iteck_plg' ),
				'label_off'     => __( 'No', 'iteck_plg' ),
				'return_value'  => 'yes',
				'default'  		=> false,
			]
		);

		$this->add_responsive_control(
			'underline_type',
			[
				'label' => esc_html__( 'Underline Type', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'image' => [
						'title' => esc_html__( 'Image', 'iteck_plg' ),
						'icon' => 'eicon-image',
					],
					'color' => [
						'title' => esc_html__( 'Color', 'iteck_plg' ),
						'icon' => 'eicon-global-colors',
					],
					'animated-line' => [
						'title' => esc_html__( 'Animated Line', 'iteck_plg' ),
						'icon' => 'eicon-animation',
					],
				],
				'default' => 'image',
				'condition' => [
					'custom_underline' => 'yes'
				],
				'prefix_class' => 'iteck-',
			]
		);

        $this->add_control(
            'custom_underline_image',
            [
                'label' => __( 'Brands Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
				'selectors' => [
					'{{WRAPPER}} .iteck-underline span:before' => 'background-image:url({{url}});'
				],
				'condition' => [
					'custom_underline' => 'yes',
					'underline_type' => 'image'
				]
            ]
        );

		$this->add_responsive_control(
			'custom_underline_image_size',
			[
				'label' => esc_html__( 'Image Size (Width)', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-underline span:before' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'custom_underline' => 'yes',
					'underline_type' => 'image'
				],
			]
		);

		$this->add_responsive_control(
			'custom_underline_image_height',
			[
				'label' => esc_html__( 'Image Size (Height)', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-underline span:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'custom_underline' => 'yes',
					'underline_type' => 'image'
				],
			]
		);

		$this->add_responsive_control(
			'custom_underline_image_top',
			[
				'label' => esc_html__( 'Top Position', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-underline span:before' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'custom_underline' => 'yes',
					'underline_type' => 'image'
				]
			]
		);

		$this->add_responsive_control(
			'custom_underline_image_left',
			[
				'label' => esc_html__( 'Left Position', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-underline span:before' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'custom_underline' => 'yes',
					'underline_type' => 'image'
				]
			]
		);

		$this->add_control(
			'custom_underline_color',
			[
				'label' => esc_html__( 'Underline Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-heading.iteck-underline-color span::after' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'custom_underline' => 'yes',
					'underline_type' => 'color'
				]
			]
		);

		$this->add_control(
			'custom_animated_underline_color',
			[
				'label' => esc_html__( 'Animated Underline Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}}.iteck-animated-line:hover::after' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'custom_underline' => 'yes',
					'underline_type' => 'animated-line'
				]
			]
		);

		$this->add_responsive_control(
			'custom_animated_underline_position',
			[
				'label' => esc_html__( 'Position', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.iteck-animated-line::after' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'custom_underline' => 'yes',
					'underline_type' => 'animated-line'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'text_additional_color_section',
			[
				'label' => esc_html__( 'Text Additional Color', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'text_additional_color',
			[
				'label'         => __( 'Text Additional Color', 'iteck_plg' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'iteck_plg' ),
				'label_off'     => __( 'No', 'iteck_plg' ),
				'return_value'  => 'yes',
				'default'  		=> false,
			]
		);

		$this->add_control(
			'text_small_options',
			[
				'label'         => __( 'Text Small Options', 'iteck_plg' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'iteck_plg' ),
				'label_off'     => __( 'No', 'iteck_plg' ),
				'return_value'  => 'yes',
				'default'  		=> false,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-heading' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-heading a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => esc_html__( 'Text Hover Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-heading a:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'link[url]!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-heading',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .iteck-heading',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Normal', 'iteck_plg' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'difference' => 'Difference',
					'exclusion' => 'Exclusion',
					'hue' => 'Hue',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-heading' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .iteck-heading',
			]
		);

		$this->end_controls_section();

		//**********************************************************************************************************

        $this->start_controls_section(
            'custom_underline_style',
            [
                'label'         => esc_html__( 'Custom Underline', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'custom_underline' => 'yes'
				]
            ]
        );


        $this->add_control(
			'custom_underline_text_color',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-heading.iteck-underline span, {{WRAPPER}} .iteck-heading.iteck-underline-color span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'custom_underline_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-heading.iteck-underline span, {{WRAPPER}} .iteck-heading.iteck-underline p',
			]
		);

		$this->add_responsive_control(
			'custom_underline_padding',
			[
				'label' => __('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-heading.iteck-underline span' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'custom_underline_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-heading.iteck-underline span' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


        $this->end_controls_section();

		$this->start_controls_section(
			'section_additional_color_style',
			[
				'label' => esc_html__( 'Additional Colors Options', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'text_additional_color' => 'yes'
				]
			]
		);

		$this->add_control(
			'gradient_color_type',
			[
				'label' => esc_html__( 'Color type', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => 'Solid',
					'gradient' => 'Gradient',
					'stroke' => 'Stroke',
				],
				'default' => 'solid',
			]
		);

		$this->add_control(
			'span_title_stroke_color',
			[
				'label' => esc_html__( 'Stroke Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-heading span' => '-webkit-text-stroke-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
            'title_stroke_size', 
            [
                'label' => _x( 'Angle', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'step' => 1,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .iteck-heading span' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}}; color: transparent;',
                ],

                'condition' => [
                    'gradient_color_type' => [ 'stroke'],
                ],
            ]
        );

		$this->add_control(
			'gradient_color',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-heading.iteck-additional-color span' => 'color: {{VALUE}};',
				],
				'condition' => [
					'gradient_color_type' => 'solid'
				]
			]
		);

		$this->add_control(
            'gradient_bg_color1',
            [
                'label' => _x( 'First Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'title' => _x( 'First Color', 'Background Control', 'iteck_plg' ),
                'render_type' => 'ui',
                'condition' => [
                    'gradient_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );


        $this->add_control(
            'gradient_bg_color1_stop', 
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
                    'gradient_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'gradient_bg_color2',
            [
                'label' => _x( 'Second Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'gradient_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'gradient_bg_color2_stop', 
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
                    'gradient_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

		$this->add_control(
            'gradient_bg_color3',
            [
                'label' => _x( 'Third Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'gradient_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'gradient_bg_color3_stop', 
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
                    'gradient_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

		$this->add_control(
            'gradient_bg_color4',
            [
                'label' => _x( 'Fourth Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'gradient_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'gradient_bg_color4_stop', 
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
                    'gradient_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
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
                    'gradient_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
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
                    '{{WRAPPER}} .iteck-heading.iteck-additional-color span' => 'background: linear-gradient({{SIZE}}{{UNIT}}, {{gradient_bg_color1.VALUE}} {{gradient_bg_color1_stop.SIZE}}{{gradient_bg_color1_stop.UNIT}},{{gradient_bg_color2.VALUE}} {{gradient_bg_color2_stop.SIZE}}{{gradient_bg_color2_stop.UNIT}},{{gradient_bg_color3.VALUE}} {{gradient_bg_color3_stop.SIZE}}{{gradient_bg_color3_stop.UNIT}},{{gradient_bg_color4.VALUE}} {{gradient_bg_color4_stop.SIZE}}{{gradient_bg_color4_stop.UNIT}}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;',
                ],

                'condition' => [
                    'gradient_color_type' => [ 'gradient'],
                    'gradient_type' => 'linear',
                ],
                'of_type' => 'gradient',
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'additional_color_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-heading.iteck-additional-color span',
			]
		);

		

		$this->end_controls_section();

		$this->start_controls_section(
			'text_small_options_style',
			[
				'label' => __('Text Small', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'text_small_options' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'additional_small_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-heading.iteck-additional-color small',
			]
		);

		$this->add_control(
			'additional_small_color',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-heading.iteck-additional-color small' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$iteck_underline = 'yes' == $settings['custom_underline'] && 'image' == $settings['underline_type'] ? ' iteck-underline' : ''; 
		$iteck_underline_color = 'yes' == $settings['custom_underline'] && 'color' == $settings['underline_type'] ? ' iteck-underline-color' : '';
		$iteck_gradient = 'yes' == $settings['text_additional_color'] ? ' iteck-additional-color' : ''; 
		$iteck_small = 'yes' == $settings['text_small_options'] ? ' iteck-additional-small' : ''; 

		if ( '' === $settings['title'] ) {
			return;
		}

		$this->add_render_attribute( 'title', 'class', 'iteck-heading'. $iteck_underline . $iteck_underline_color . $iteck_small . $iteck_gradient .'' );
		
		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'title', 'class', 'iteck-size-' . $settings['size'] );
		}

		$this->add_inline_editing_attributes( 'title' );

		$title = $settings['title'];

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['link'] );

			$title = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
		}

		$title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $settings['html_tag'] ), $this->get_render_attribute_string( 'title' ), $title );

		// PHPCS - the variable $title_html holds safe data.
		echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

	/**
	 * Render heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {

	}
}
