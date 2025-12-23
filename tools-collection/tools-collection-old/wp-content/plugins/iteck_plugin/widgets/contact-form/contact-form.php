<?php
namespace IteckPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


		
/**
 * @since 1.0.0
 */
class Iteck_Contact_Form extends Widget_Base {

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
		return 'iteck-contact-form';
	}

    //script depend
	public function get_script_depends() { 
        return [ 'iteck-contact-form' ]; 
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
		return __( 'Iteck Contact Form Shortcode', 'iteck_plg' );
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
		return [ 'from', 'input', 'contact' ];
	}

    
    public function iteck_contact_forms(){
        $formlist = array();
        $forms_args = array( 'posts_per_page' => -1, 'post_type'=> 'wpcf7_contact_form' );
        $forms = get_posts( $forms_args );
        if( $forms ){
            foreach ( $forms as $form ){
                $formlist[$form->ID] = $form->post_title;
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
				'label' => __( 'Title', 'iteck_plg' ),
			]
		);

        $this->add_control(
			'form_id',
			[
				'label' => __( 'Select contact form', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'default' => array_keys($this->iteck_contact_forms())[0],
				'options' => $this->iteck_contact_forms(),
			]
		);

		$this->add_control(
			'fields_in_row',
			[
				'label'         => __( 'Fields in Row', 'iteck_plg' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'iteck_plg' ),
				'label_off'     => __( 'No', 'iteck_plg' ),
				'return_value'  => 'yes',
				'default'  		=> false,
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'fields_settings',
			[
				'label' => __( 'Fields Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'fields_width',
			[
				'label' => esc_html__( 'Field Width', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .form-group' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'fields_in_row' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'fields_typography',
				'label'     => __('Typography', 'iteck_plg'),
				'selector'  => '{{WRAPPER}} .iteck-contact-shortcode input:not([type="submit"]), {{WRAPPER}} .iteck-contact-shortcode select, {{WRAPPER}} .iteck-contact-shortcode textarea, {{WRAPPER}} .iteck-contact-shortcode input::placeholder, {{WRAPPER}} .iteck-contact-shortcode textarea::placeholder',
			]
		);

		$this->add_control(
			'fields_text_color',
			[
				'label' => __('Input Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode input:not([type="submit"]), {{WRAPPER}} .iteck-contact-shortcode select, {{WRAPPER}} .iteck-contact-shortcode textarea' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'fields_icon_color',
			[
				'label' => __('Icon Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'fields_padding',
			[
				'label' => __('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode input:not([type="submit"], [type="checkbox"]), {{WRAPPER}} .iteck-contact-shortcode select, {{WRAPPER}} .iteck-contact-shortcode textarea' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'fields_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode input:not([type="submit"], [type="checkbox"]), {{WRAPPER}} .iteck-contact-shortcode select, {{WRAPPER}} .iteck-contact-shortcode textarea' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'fields_height',
			[
				'label' => esc_html__( 'input Height', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units' => [ 'px', 'vh', '%' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode input:not([type="submit"], [type="checkbox"]), {{WRAPPER}} .iteck-contact-shortcode select, {{WRAPPER}} .iteck-contact-shortcode textarea' => 'min-height:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'textarea_height',
			[
				'label' => esc_html__( 'Textarea Height', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units' => [ 'px', 'vh', '%' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode textarea' => 'min-height:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('tabs_fields_style');

		$this->start_controls_tab(
			'tab_fields_normal',
			[
				'label' => esc_html__('Normal', 'iteck_plg'),
			]
		);

		$this->add_control(
			'fields_bg',
			[
				'label' => __('Background', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode input, {{WRAPPER}} .iteck-contact-shortcode textarea, {{WRAPPER}} .iteck-contact-shortcode select' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'fields_placeholder_color',
			[
				'label' => __('Placeholder Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode input::placeholder, {{WRAPPER}} .iteck-contact-shortcode textarea::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'fields_border',
				'selector' => '{{WRAPPER}} .iteck-contact-shortcode input:not([type="submit"], [type="checkbox"]), {{WRAPPER}} .iteck-contact-shortcode select, {{WRAPPER}} .iteck-contact-shortcode textarea',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'fields_border_radius',
			[
				'label' => __('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode input:not([type="submit"], [type="checkbox"]), {{WRAPPER}} .iteck-contact-shortcode select, {{WRAPPER}} .iteck-contact-shortcode textarea' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'fields_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-contact-shortcode input:not([type="submit"]), {{WRAPPER}} .iteck-contact-shortcode select, {{WRAPPER}} .iteck-contact-shortcode textarea',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_fields_focus',
			[
				'label' => esc_html__('Focus', 'iteck_plg'),
			]
		);

		$this->add_control(
			'fields_bg_focus',
			[
				'label' => __('Background', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode input:focus, {{WRAPPER}} .iteck-contact-shortcode textarea:focus, {{WRAPPER}} .iteck-contact-shortcode select:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'fields_placeholder_color_focus',
			[
				'label' => __('Placeholder Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode input:focus::placeholder, {{WRAPPER}} .iteck-contact-shortcode textarea:focus::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'fields_border_focus',
				'selector' => '{{WRAPPER}} .iteck-contact-shortcode input:focus:not([type="submit"], [type="checkbox"]), {{WRAPPER}} .iteck-contact-shortcode select:focus, {{WRAPPER}} .iteck-contact-shortcode textarea:focus',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'fields_border_radius_focus',
			[
				'label' => __('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode input:focus:not([type="submit"], [type="checkbox"]), {{WRAPPER}} .iteck-contact-shortcode select:focus, {{WRAPPER}} .iteck-contact-shortcode textarea:focus' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'fields_box_shadow_focus',
				'selector' => '{{WRAPPER}} .iteck-contact-shortcode input:focus:not([type="submit"]), {{WRAPPER}} .iteck-contact-shortcode select:focus, {{WRAPPER}} .iteck-contact-shortcode textarea:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'button_settings',
			[
				'label' => __( 'Button Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'button_width',
			[
				'label' => esc_html__( 'Button Width', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-contact-shortcode .form-group+p' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_alignment',
			[
				'label' => __('Button Alignment', 'iteck_plg'),
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
				'default' => 'center',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'button_typography',
				'label'     => __('Typography', 'iteck_plg'),
				'selector'  => '{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
			'button_text_color',
			[
				'label' => __('Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'label' => esc_html__('Background', 'iteck_plg'),
				'types' => ['classic', 'gradient'],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => __('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit',
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
			'button_text_color_hover',
			[
				'label' => __('Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit:hover' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border_hover',
				'selector' => '{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit:hover',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_radius_hover',
			[
				'label' => __('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit:hover' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();

		$this->start_controls_section(
			'button_drop_shadow',
			[
				'label' => esc_html__('Button Drop Shadow', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_drop_shadow_offset_x',
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
			'button_drop_shadow_offset_y',
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
			'button_drop_shadow_blur_radius',
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
			'button_drop_shadow_color',
			[
				'label' => __('Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .wpcf7-submit' => 'filter: drop-shadow({{button_drop_shadow_offset_x.SIZE}}px {{button_drop_shadow_offset_y.SIZE}}px {{button_drop_shadow_blur_radius.SIZE}}px {{VALUE}});',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'check_box_settings',
			[
				'label' => __( 'Check Box Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'check_box_typography',
				'label'     => __('Typography', 'iteck_plg'),
				'selector'  => '{{WRAPPER}} .iteck-contact-shortcode .wpcf7-list-item span',
			]
		);

		$this->add_control(
			'check_box_color',
			[
				'label' => __('Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .wpcf7-list-item span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'check_box_alignment',
			[
				'label' => __('Button Alignment', 'iteck_plg'),
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
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .wpcf7-list-item' => 'text-align-last: {{VALUE}};'
				]
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

		$this->add_control(
			'x_positioning',
			[
				'label' => __('X Positioning', 'iteck_plg'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'iteck_plg'),
						'icon' => 'eicon-text-align-left',
					],
					'right' => [
						'title' => __('Right', 'iteck_plg'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
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
					'{{WRAPPER}} .iteck-contact-shortcode .icon' => 'left: {{SIZE}}{{UNIT}}; right: auto !important;',
				],
				'condition' => [
					'x_positioning' => 'left'
				]
			]
		);

		$this->add_responsive_control(
			'icon_x_right',
			[
				'label' => __( 'Icon X (Right) Position', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 500,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .icon' => 'right: {{SIZE}}{{UNIT}}; left: auto !important;',
				],
				'condition' => [
					'x_positioning' => 'right'
				]
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
					'{{WRAPPER}} .iteck-contact-shortcode .icon' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		
		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 500,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .icon' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Height', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 500,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .icon' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'icon_background',
				'label' => esc_html__('Icon Background', 'iteck_plg'),
				'types' => ['classic', 'gradient'],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .iteck-contact-shortcode .icon',
			]
		);


		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color','iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-contact-shortcode .icon' => 'color: {{VALUE}};',
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
		$shortcode=do_shortcode( '[contact-form-7 id="'. $id .'"]' ); ?>

		<div class="iteck-contact-shortcode button-<?php echo esc_attr($settings['button_alignment']); if($settings['fields_in_row'] == 'yes') echo ' fields-in-row'; ?>"><?php echo $shortcode; ?></div>
	
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


