<?php
namespace IteckPlugin\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
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
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		 
/**
 * @since 1.0.0
 */
class Iteck_Icon_Box extends Widget_Base {

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
		return 'iteck-icon-box';
	}

	//script depend
	public function get_script_depends() { return ['iteck-icon-box' ]; }

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
		return __( 'Iteck Icon Box','iteck_plg' );
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
	public function get_categories() {
		return [ 'iteck-elements' ];
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

		//--------------------------------------------------- Item Content ------------------------------------
		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Icon Box', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'box_image_icon',
			[
				'label' => __( 'Media Type', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'icon' => [
						'title' => __( 'Icon', 'iteck_plg' ),
						'icon' => 'fa fa-smile-o',
					],
					'image' => [
						'title' => __( 'Image', 'iteck_plg' ),
						'icon' => 'fa fa-image',
					],

				],
				'default' => 'icon',
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'iteck_plg' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition'	=> [
					'box_image_icon'	=> 'icon'
				]
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'	=> [
					'box_image_icon'	=> 'image'
				]
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'View', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'iteck_plg' ),
					'stacked' => esc_html__( 'Stacked', 'iteck_plg' ),
					'framed' => esc_html__( 'Framed', 'iteck_plg' ),
				],
				'default' => 'default',
				'prefix_class' => 'elementor-view-',
			]
		);

		$this->add_control(
			'shape',
			[
				'label' => esc_html__( 'Shape', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'circle' => esc_html__( 'Circle', 'iteck_plg' ),
					'square' => esc_html__( 'Square', 'iteck_plg' ),
				],
				'default' => 'circle',
				'condition' => [
					'view!' => 'default',
					'selected_icon[value]!' => '',
				],
				'prefix_class' => 'elementor-shape-',
			]
		);

		$this->add_control(
			'title_text',
			[
				'label' => esc_html__( 'Title & Description', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'This is the heading', 'iteck_plg' ),
				'placeholder' => esc_html__( 'Enter your title', 'iteck_plg' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'description_text',
			[
				'label' => '',
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'iteck_plg' ),
				'placeholder' => esc_html__( 'Enter your description', 'iteck_plg' ),
				'rows' => 10,
				'separator' => 'none',
				'show_label' => false,
			]
		);

		$this->add_control(
			'position',
			[
				'label' => esc_html__( 'layout', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'top',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'iteck_plg' ),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' => esc_html__( 'Top', 'iteck_plg' ),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'iteck_plg' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'elementor-position-',
				'toggle' => false,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'selected_icon[value]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon position', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'top',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'iteck_plg' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Top', 'iteck_plg' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'iteck_plg' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'toggle' => false,
				'condition' => [
					'position' => 'top',
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-icon-box .elementor-icon-box-icon' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_size',
			[
				'label' => esc_html__( 'Title HTML Tag', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link','iteck_plg' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'Leave it blank if you don\'t want to use this button',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'infobox_item_styling',
			[
				'label' => __( 'Item Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => __('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-icon-box' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('tabs_item_style');

		$this->start_controls_tab(
			'tab_item',
			[
				'label' => esc_html__('Normal', 'iteck_plg'),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'label' => __('Button Icon Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-icon-box',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .iteck-icon-box',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-icon-box',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_icon_hover',
			[
				'label' => esc_html__('Hover', 'iteck_plg'),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_background_hover',
				'label' => __('Button Icon Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-icon-box:hover',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border_hover',
				'selector' => '{{WRAPPER}} .iteck-icon-box:hover',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'item_border_radius_hover',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-icon-box:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow_hover',
				'selector' => '{{WRAPPER}} .iteck-icon-box:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'iteck_plg' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'selected_icon[value]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab(
			'icon_colors_normal',
			[
				'label' => esc_html__( 'Normal', 'iteck_plg' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'primary_color',
				'label' => __('Primary Color', 'iteck_plg'),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}}.elementor-view-stacked .elementor-icon i:before, {{WRAPPER}} .elementor-icon svg, {{WRAPPER}}.elementor-view-framed .elementor-icon i:before, {{WRAPPER}}.elementor-view-default .elementor-icon i:before',
				'exclude' => ['image'],
				'fields_options' => [
					'color' => [
						'selectors' => [
							'{{SELECTOR}}' => 'color: {{color.VALUE}}; fill: {{color.VALUE}};',
						],
					],
					'gradient_angle' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;',
						],
					],
					'gradient_position' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;',
						],
					],
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'secondary_color',
				'label' => __('Secondary Color', 'iteck_plg'),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}}.elementor-view-stacked .elementor-icon, {{WRAPPER}}.elementor-view-framed .elementor-icon i:before, {{WRAPPER}}.elementor-view-default .elementor-icon i:before',
				
				'condition' => [
					'view!' => 'default',
				],
			]
		);

		$this->add_control(
			'icon_opacity',
			[
				'label' => esc_html__( 'Item Opacity', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'step' => 0.01,
						'max' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-icon-box' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_colors_hover',
			[
				'label' => esc_html__( 'Hover', 'iteck_plg' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'primary_color_hover',
				'label' => __('Primary Color', 'iteck_plg'),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover i:before, {{WRAPPER}} .elementor-icon:hover svg, {{WRAPPER}}.elementor-view-framed .elementor-icon:hover i:before, {{WRAPPER}}.elementor-view-default .elementor-icon:hover i:before',
				'exclude' => ['image'],
				'fields_options' => [
					'color' => [
						'selectors' => [
							'{{SELECTOR}}' => 'color: {{color.VALUE}}; fill: {{color.VALUE}};',
						],
					],
					'gradient_angle' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;',
						],
					],
					'gradient_position' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;',
						],
					],
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'secondary_color_hover',
				'label' => __('Secondary Color', 'iteck_plg'),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover, {{WRAPPER}}.elementor-view-framed .elementor-icon:hover i:before, {{WRAPPER}}.elementor-view-default .elementor-icon:hover i:before',
				'condition' => [
					'view!' => 'default',
				],
			]
		);

		$this->add_control(
			'icon_opacity_hover',
			[
				'label' => esc_html__( 'Item Opacity', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'step' => 0.01,
						'max' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-icon-box:hover' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'iteck_plg' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_space',
			[
				'label' => esc_html__( 'Spacing', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-position-right .elementor-icon-box-icon' => 'margin-inline-start: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.elementor-position-left .elementor-icon-box-icon' => 'margin-inline-end: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.elementor-position-top .elementor-icon-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} .elementor-icon-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-icon i:before' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_container_size',
			[
				'label' => esc_html__( 'Container Size', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-icon-box .elementor-icon-box-icon .elementor-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .iteck-icon-box .elementor-icon-box-icon .elementor-icon i' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_lineheight',
			[
				'label' => esc_html__( 'Icon Line Height', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-icon-box .elementor-icon-box-icon .elementor-icon' => 'line-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .iteck-icon-box .elementor-icon-box-icon .elementor-icon i' => 'line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'rotate',
			[
				'label' => esc_html__( 'Rotate', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .elementor-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'border_width',
			[
				'label' => esc_html__( 'Border Width', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'view' => 'framed',
				],
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'view!' => 'default',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'img_filter_section',
			[
				'label' => esc_html__('Image filter', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'img_filter_tabs' );

		$this->start_controls_tab(
			'img_filter_tab_normal',
			[
				'label' => esc_html__( 'Normal', 'iteck_plg' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_filter',
				'selector' => '{{WRAPPER}} img',
			]
		);

		$this->add_control(
			'image_revert',
			[
				'label' => esc_html__('Image Filter Revert', 'iteck_plg'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'max' => 200,
						'min' => 0,
						'step' => 1,
					],
				],
			]
		);

		$this->add_control(
			'image_sepia',
			[
				'label' => esc_html__('Image Filter Sepia', 'iteck_plg'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'max' => 200,
						'min' => 0,
						'step' => 1,
					],
				],
                'selectors' => [
					'{{WRAPPER}} .iteck-icon-box img' => 'filter:  invert({{image_revert.SIZE}}%) sepia({{SIZE}}%) brightness( {{image_filter_brightness.SIZE}}% ) contrast( {{image_filter_contrast.SIZE}}% ) saturate( {{image_filter_saturate.SIZE}}% ) blur( {{image_filter_blur.SIZE}}px ) hue-rotate( {{image_filter_hue.SIZE}}deg ) !important;'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'img_filter_tab_hover',
			[
				'label' => esc_html__( 'Hover', 'iteck_plg' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_filter_hover',
				'selector' => '{{WRAPPER}} .iteck-icon-box:hover img',
			]
		);

		$this->add_control(
			'image_revert_hover',
			[
				'label' => esc_html__('Image Filter Revert', 'iteck_plg'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'max' => 200,
						'min' => 0,
						'step' => 1,
					],
				],
			]
		);

		$this->add_control(
			'image_sepia_hover',
			[
				'label' => esc_html__('Image Filter Sepia', 'iteck_plg'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'max' => 200,
						'min' => 0,
						'step' => 1,
					],
				],
                'selectors' => [
					'{{WRAPPER}} .iteck-icon-box:hover img' => 'filter:  invert({{image_revert_hover.SIZE}}%) sepia({{SIZE}}%) brightness( {{image_filter_hover_brightness.SIZE}}% ) contrast( {{image_filter_hover_contrast.SIZE}}% ) saturate( {{image_filter_hover_saturate.SIZE}}% ) blur( {{image_filter_hover_blur.SIZE}}px ) hue-rotate( {{image_filter_hover_hue.SIZE}}deg ) !important;'
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'icon_drop_shadow',
			[
				'label' => esc_html__('Icon Drop Shadow', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_drop_shadow_offset_x',
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
			'icon_drop_shadow_offset_y',
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
			'icon_drop_shadow_blur_radius',
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
			'icon_drop_shadow_color',
			[
				'label' => __('Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'filter: drop-shadow({{icon_drop_shadow_offset_x.SIZE}}px {{icon_drop_shadow_offset_y.SIZE}}px {{icon_drop_shadow_blur_radius.SIZE}}px {{VALUE}});',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'iteck_plg' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
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
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_vertical_alignment',
			[
				'label' => esc_html__( 'Vertical Alignment', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => esc_html__( 'Top', 'iteck_plg' ),
					'middle' => esc_html__( 'Middle', 'iteck_plg' ),
					'bottom' => esc_html__( 'Bottom', 'iteck_plg' ),
				],
				'default' => 'top',
				'prefix_class' => 'iteck-icon-box-vertical-align-',
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => esc_html__( 'Title', 'iteck_plg' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'title_tabs' );

		$this->start_controls_tab(
			'title_tab_normal',
			[
				'label' => esc_html__( 'Normal', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-icon-box-title a' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_tab_hover',
			[
				'label' => esc_html__( 'Hover', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label' => esc_html__( 'Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-icon-box:hover .elementor-icon-box-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-icon-box:hover .elementor-icon-box-title a' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'title_bottom_space',
			[
				'label' => esc_html__( 'Spacing', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .elementor-icon-box-title, {{WRAPPER}} .elementor-icon-box-title a',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .elementor-icon-box-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .elementor-icon-box-title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __('Title Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-title' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label' => esc_html__( 'Description', 'iteck_plg' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'description_tabs' );

		$this->start_controls_tab(
			'description_tab_normal',
			[
				'label' => esc_html__( 'Normal', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-description' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'description_tab_hover',
			[
				'label' => esc_html__( 'Hover', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'description_color_hover',
			[
				'label' => esc_html__( 'Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-icon-box:hover .elementor-icon-box-description' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .elementor-icon-box-description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'description_shadow',
				'selector' => '{{WRAPPER}} .elementor-icon-box-description',
			]
		);

		$this->add_responsive_control(
			'description_margin',
			[
				'label' => __('Description Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-description' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'iconbox_additional_description_styling',
			[
				'label' => __( 'Additional Description Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'additional_description_typography',
				'label' => esc_html__( 'Additional Description typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .elementor-icon-box-description span, {{WRAPPER}} .elementor-icon-box-description b',
			]
		);
		
		$this->add_control(
			'additional_description_color',
			[
				'label' => __( 'Additional Description color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-box-description span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-icon-box-description b' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render icon box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'icon', 'class', [ 'elementor-icon', 'elementor-animation-' . $settings['hover_animation'] ] );

		$icon_tag = 'span';

		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['icon'] = 'fa fa-star';
		}

		$has_icon = ! empty( $settings['icon'] );
		$has_image = ! empty( $settings['image']['url'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		$this->add_render_attribute( 'description_text', 'class', 'elementor-icon-box-description' );

		$this->add_inline_editing_attributes( 'title_text', 'none' );
		$this->add_inline_editing_attributes( 'description_text' );
		if ( ! $has_icon && ! empty( $settings['selected_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = ! isset( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		$icon_pos = 'position-top';
		if($settings['position'] == 'left') {
			$icon_pos = 'position-left';
			if(isset($settings['text_align']) && $settings['text_align'] == 'right'){
				$icon_pos .= ' justify-content-between';
			}
		} elseif ($settings['position'] == 'right') {
			$icon_pos = 'position-right';
			if(isset($settings['text_align']) && $settings['text_align'] == 'left'){
				$icon_pos .= ' justify-content-between';
			}
		}

		?>
		<div class="iteck-icon-box <?php echo esc_attr($icon_pos); ?> elementor-icon-box-wrapper">
			<?php if ( $has_icon or $has_image ) : ?>
			<div class="elementor-icon-box-icon">
				<span <?php $this->print_render_attribute_string( 'icon' ); ?> <?php $this->print_render_attribute_string( 'link' ); ?>>
				<?php
				if ( $is_new || $migrated ) {
					Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
				} elseif ( ! empty( $settings['icon'] ) ) {
					?><i <?php $this->print_render_attribute_string( 'i' ); ?>></i><?php
				}
				?>
				<?php if($settings['box_image_icon'] == 'icon'): ?>
				<?php else: ?>
				<img src="<?php echo esc_url($settings['image']['url']); ?>" alt="">
				<?php endif; ?>
				</span>
			</div>
			<?php endif; ?>
			<div class="elementor-icon-box-content">
				<<?php Utils::print_validated_html_tag( $settings['title_size'] ); ?> class="elementor-icon-box-title">
					<<?php Utils::print_validated_html_tag( $icon_tag ); ?> <?php $this->print_render_attribute_string( 'link' ); ?> <?php $this->print_render_attribute_string( 'title_text' ); ?>>
						<?php $this->print_unescaped_setting( 'title_text' ); ?>
					</<?php Utils::print_validated_html_tag( $icon_tag ); ?>>
				</<?php Utils::print_validated_html_tag( $settings['title_size'] ); ?>>
				<?php if ( ! Utils::is_empty( $settings['description_text'] ) ) : ?>
					<p <?php $this->print_render_attribute_string( 'description_text' ); ?>>
						<?php $this->print_unescaped_setting( 'description_text' ); ?>
					</p>
				<?php endif; ?>
			</div>
			<?php if (!empty($settings['link']['url'])) { ?>
				<a href="<?php echo esc_url($settings['link']['url']); ?>" <?php if ( $settings['link']['is_external'] ) {echo'target="_blank"';} ?> class="iteck-icon-box-link"> </a> 
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Render icon box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {
		
	}

	public function on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon', true );
	}

}


