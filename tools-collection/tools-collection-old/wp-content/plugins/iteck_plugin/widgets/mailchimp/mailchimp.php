<?php
namespace IteckPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Iteck_MailChimp extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'iteck-mailchimp';
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
	public function get_title() {
		return __( 'Iteck MailChimp Form', 'iteck_plg' );
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
	public function get_icon() {
		return 'fa fa-wpforms';
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
	public function get_categories() {
		return [ 'ravo-elements' ];
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
		return [ 'from', 'input', 'contact' ];
	}

    
    public function iteck_mailChimp_forms(){
        $formlist = array();
        $forms_args = array( 
        	'posts_per_page' => -1,
        	'post_status' => 'publish',
        	'post_type'=> 'mwp_form', 
        );

        $forms = get_posts( $forms_args );
        if( $forms ){
            foreach ( $forms as $form ){
                $formlist[$form->ID] = $form->form_id;
            }
        }else{
            $formlist['0'] = __('Form not found', 'iteck_plg');
        }
        return $formlist;
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
	protected function _register_controls() {
	
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'MailChimp form', 'iteck_plg' ),
			]
		);

        $this->add_control(
			'form_id',
			[
				'label' => __( 'Select MailChimp form', 'iteck_plg' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => array_keys($this->iteck_mailChimp_forms())[0],
			]
		);

		$this->add_control(
			'form_layout',
			[
				'label' => __( 'Layout', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one-block' => __( 'One Block', 'iteck_plg' ),
					'button-inside' => __( 'Button Inside', 'iteck_plg' ),
					'button-in-row' => __( 'Button in Row', 'iteck_plg' ),
				],
				'default' => 'one-block',
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'form_settings',
			[
				'label' => __( 'Form Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'input_height',
			[
				'label' => __( 'Input height', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp.button-inside input' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'form_layout' => 'button-inside'
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'form_placeholder_typography',
				'label'     => __( 'Typography', 'iteck_plg' ),
				'selector'  => '{{WRAPPER}} .iteck-mc4wp input',
			]
		);
		
		$this->add_control(
			'form_placeholder',
			[
				'label' => __( 'Placeholder Color','iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-mc4wp input::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-mc4wp input:-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-mc4wp input:-moz-placeholder' => 'color: {{VALUE}};',
				],
			]
		);
		
		
		$this->add_control(
			'form_text',
			[
				'label' => __( 'Text Color','iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .iteck-mc4wp input:not(.wpcf7-submit) ' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-mc4wp textarea' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'form_bg',
			[
				'label' => __( 'Background Color','iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .iteck-mc4wp input' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .iteck-mc4wp textarea' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'form_border',
				'label' => esc_html__( 'Border', 'iteck_plg' ),
				'selector' => '{{WRAPPER}} .iteck-mc4wp input, {{WRAPPER}} .iteck-mc4wp textarea',
			]
		);
		
		$this->add_responsive_control(
			'form_border_radius',
			[
				'label' => __( 'Border Radius', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp input' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-mc4wp textarea' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'form_padding',
			[
				'label' => __( 'Padding', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp input' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-mc4wp textarea' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'form_border_color_active',
			[
				'label' => __( 'Border Color on Focus','iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp input:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .iteck-mc4wp textarea:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'fields_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-mc4wp input, {{WRAPPER}} .iteck-mc4wp select, {{WRAPPER}} .iteck-mc4wp textarea',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'icon_settings',
			[
				'label' => __( 'Icon Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_x',
			[
				'label' => __( 'Icon X Position', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp .icon' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_y',
			[
				'label' => __( 'Icon Y position', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp .icon' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color','iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp .icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'btn_settings',
			[
				'label' => __( 'Button Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'btn_width',
			[
				'label' => __( 'Button Width', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp.button-inside button' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'form_layout' => 'button-inside'
				]
			]
		);

		$this->add_responsive_control(
			'btn_height',
			[
				'label' => __( 'Button height', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp.button-inside button' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'form_layout' => 'button-inside'
				]
			]
		);

		$this->add_responsive_control(
			'btn_x',
			[
				'label' => __( 'Button X Position', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp.button-inside button' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'form_layout' => 'button-inside'
				]
			]
		);

		$this->add_responsive_control(
			'btn_y',
			[
				'label' => __( 'Button Y position', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp.button-inside button' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'form_layout' => 'button-inside'
				]
			]
		);

		$this->add_control(
			'btn_color_type',
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
			'btn_color',
			[
				'label' => esc_html__( 'Button Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp button' => 'color: {{VALUE}};',
				],
				'condition' => [
					'btn_color_type' => 'solid'
				]
			]
		);

		$this->add_control(
            'btn_gradient_bg_color1',
            [
                'label' => _x( 'First Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'title' => _x( 'First Color', 'Background Control', 'iteck_plg' ),
                'render_type' => 'ui',
                'condition' => [
                    'btn_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );


        $this->add_control(
            'btn_gradient_bg_color1_stop', 
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
                    'btn_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'btn_gradient_bg_color2',
            [
                'label' => _x( 'Second Color', 'Background Control', 'iteck_plg' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'btn_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'btn_gradient_bg_color2_stop', 
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
                    'btn_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'btn_gradient_type', 
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
                    'btn_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'btn_gradient_angle', 
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
                    '{{WRAPPER}} .iteck-mc4wp button' => 'background: linear-gradient({{SIZE}}{{UNIT}}, {{btn_gradient_bg_color1.VALUE}} {{btn_gradient_bg_color1_stop.SIZE}}{{btn_gradient_bg_color1_stop.UNIT}},{{btn_gradient_bg_color2.VALUE}} {{btn_gradient_bg_color2_stop.SIZE}}{{btn_gradient_bg_color2_stop.UNIT}}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;',
                ],

                'condition' => [
                    'btn_color_type' => [ 'gradient'],
                    'btn_gradient_type' => 'linear',
                ],
                'of_type' => 'gradient',
            ]
        );

		$this->add_control(
			'btn_stroke_color',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp button' => '-webkit-text-stroke-color: {{VALUE}};',
				],
				'condition' => [
					'btn_color_type' => [ 'stroke'],
				]
			]
		);

		$this->add_control(
            'btn_stroke_size', 
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
                    '{{WRAPPER}} .iteck-mc4wp button' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}}; color: transparent;',
                ],

                'condition' => [
                    'btn_color_type' => [ 'stroke'],
                ],
            ]
        );


        $this->add_control(
            'gradient_animation_hover',
            [
                'label' => __( 'Gradient Animation Hover', 'iteck_plg' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'iteck_plg' ),
                'label_off' => __( 'No', 'iteck_plg' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
		

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'btn_background',
				'label' => __('Button Backround', 'iteck_plg'),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .iteck-mc4wp .wpcf7-submit, {{WRAPPER}} .iteck-mc4wp button',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'btn_background_hover',
				'label' => __('Button Backround', 'iteck_plg'),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .iteck-mc4wp .wpcf7-submit:hover, {{WRAPPER}} .iteck-mc4wp button:hover',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'btn_border',
				'selector' => '{{WRAPPER}} .iteck-mc4wp .wpcf7-submit, {{WRAPPER}} .iteck-mc4wp button',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'btn_typography',
				'label'     => __( 'Typography', 'iteck_plg' ),
				'selector'  => '{{WRAPPER}} .iteck-mc4wp .wpcf7-submit, {{WRAPPER}} .iteck-mc4wp button',
			]
		);
		
		$this->add_responsive_control(
			'btn_margin',
			[
				'label' => __( 'Margin', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp .wpcf7-submit' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-mc4wp button' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => __( 'Padding', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp .wpcf7-submit' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-mc4wp button' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'btn_border_radius',
			[
				'label' => __( 'Border Radius', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-mc4wp .wpcf7-submit' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-mc4wp button' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	protected function render() {
		$settings = $this->get_settings_for_display();
		$id = $settings['form_id' ];
		$shortcode=do_shortcode( '[mc4wp_form id="'. $id .'"]' );

		?>

		<div class="iteck-mc4wp <?php if($settings['gradient_animation_hover'] == 'yes') echo 'iteck-submit-animation'; ?> <?php echo esc_attr($settings['form_layout']); ?>"><?php echo $shortcode; ?></div>

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
	protected function content_template() { 
	
	}
}


