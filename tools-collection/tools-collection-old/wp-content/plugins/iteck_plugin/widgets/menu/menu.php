<?php
namespace IteckPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.1.0
 */
class Iteck_Menu extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'iteck-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Iteck Menu', 'iteck_plg' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-th-large';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'iteck-menu-elements' ];
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
	protected function _register_controls() {
	
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Menu to Display', 'iteck_plg' ),
			]
		);

		$menus =iteck_navmenu_navbar_menu_choices();
		if ( ! empty( $menus ) ) {
			$this->add_control(
				'iteck_menu',
				[
					'label'   => __( 'Select Menu', 'iteck_plg' ),
					'type'    => Controls_Manager::SELECT, 
					'options' => $menus,
					'default' => array_keys( $menus )[0],
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<strong>' . __( 'There are no menus in your site.', 'iteck_plg' ) . '</strong><br>' . sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'iteck_plg' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control(
			'menu_style',
			[
				'label' => __( 'Style', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'0' => __( 'Custom', 'iteck_plg' ),
					'1' => __( 'Preset 1', 'iteck_plg' ),
					'2' => __( 'Preset 2', 'iteck_plg' ),
					'3' => __( 'Preset 3', 'iteck_plg' ),
					'4' => __( 'Preset 4', 'iteck_plg' ),
				],
				'default' => '0',
			]
		);
		
		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => __( 'Horizontal', 'iteck_plg' ),
					'vertical' => __( 'Vertical', 'iteck_plg' ),
					'dropdown' => __( 'Dropdown', 'iteck_plg' ),
				],
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'align_items',
			[
				'label' => __( 'Align', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => __( 'Left', 'iteck_plg' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'iteck_plg' ),
						'icon' => 'eicon-h-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'iteck_plg' ),
						'icon' => 'eicon-h-align-right',
					],
					'justify' => [
						'title' => __( 'Stretch', 'iteck_plg' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'prefix_class' => 'text-',
				'condition' => [
					'layout!' => 'dropdown',
				],
			]
		);
        
        $this->add_control(
			'hover_style',
			[
				'label' => __( 'Hover Style', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'iteck_plg' ),
					'slide-up' => __( 'slide up', 'iteck_plg' ),
					'underline' => __( 'Underline', 'iteck_plg' ),
				],
				'default' => 'underline',
			]
		);
		
		$this->add_control(
			'menu_sticky',
			[
				'label' => __( 'Menu Sticky', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'yes' => __( 'Yes', 'iteck_plg' ),
					'no' => __( 'No', 'iteck_plg' ),
				],
				'default' => 'yes',
				'condition' => [
					'menu_style!' => '0',
				],
			]
		);


		
		$this->add_control(
			'menu_type',
			[
				'label' => __( 'Drop Down Type', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'left' => __( 'From Left', 'iteck_plg' ),
					'right' => __( 'From Right', 'iteck_plg' ),
				],
				'default' => 'left',
				'condition' => [
					'menu_style!' => '0',
				],
			]
		);

		
		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Parent Menu Align', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'iteck_plg' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'iteck_plg' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'iteck_plg'),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => '',
				'condition' => [
					'menu_style!' => '0',
				],
				'selectors' => [
					'{{WRAPPER}} .white-header, .custom-sticky' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'align_child',
			[
				'label' => __( 'Child Menu Align', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'iteck_plg' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'iteck_plg' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'iteck_plg'),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => '',
				'condition' => [
					'menu_style!' => '0',
				],
				'selectors' => [
					'{{WRAPPER}} .menu-box ul li ul' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'desktop_menu',
			[
				'label' => __( 'Desktop Menu', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'inline-block' => __( 'Show', 'iteck_plg' ),
					'none' => __( 'Hide', 'iteck_plg' ),
				],
				'default' => 'inline-block',
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .menu-box' => 'display: {{VALUE}};',
				],
				'condition' => [
					'menu_style!' => '0',
				],
			]
		);
		
		$this->add_responsive_control(
			'mobile_menu',
			[
				'label' => __( 'Mobile Menu', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'inline-block' => __( 'Show', 'iteck_plg' ),
					'none' => __( 'Hide', 'iteck_plg' ),
				],
				'default' => 'inline-block',
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .box-mobile' => 'display: {{VALUE}};',
				],
				'condition' => [
					'menu_style!' => '0',
				],
			]
		);

		$this->add_control(
			'menu_sticky_bg',
			[
				'label' => __( 'Sticky Background', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'condition' => [
					'menu_style!' => '0',
				],
				'selectors' => [
					'{{WRAPPER}} .is-sticky .stuck-nav' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section(); 


		$this->start_controls_section(
			'section_style_main-menu',
			[
				'label' => __( 'Main Menu', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout!' => 'dropdown',
				],

			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-nav .navigation li a',
			]
		);


		$this->start_controls_tabs( 'tabs_menu_item_style' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			[
				'label' => __( 'Normal', 'iteck_plg' ),
			]
		);

		$this->add_responsive_control(
			'padding_horizontal_menu_item',
			[
				'label' => __( 'Horizontal Padding', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li > a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'padding_vertical_menu_item',
			[
				'label' => __( 'Vertical Padding', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li > a' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'margin_horizontal_menu_item',
			[
				'label' => __( 'Horizontal Margin', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'margin_vertical_menu_item',
			[
				'label' => __( 'Vertical Margin', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'menu_space_between',
			[
				'label' => __( 'Space Between', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .elementor-nav-menu--layout-horizontal .elementor-nav-menu > li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .elementor-nav-menu--layout-horizontal .elementor-nav-menu > li:not(:last-child)' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-nav-menu--main:not(.elementor-nav-menu--layout-horizontal) .elementor-nav-menu > li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'menu_color',
			[
				'label' => __('Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-nav .navigation > li a::after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_arrow_color',
			[
				'label' => __('Arrow Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li a::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-nav .pages_links li a::after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'menu_border',
				'selector' => '{{WRAPPER}} .iteck-nav .navigation > li',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'menu_border_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_margin',
			[
				'label' => esc_html__('Item Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_padding',
			[
				'label' => esc_html__('Item Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_menu_item_hover',
			[
				'label' => __( 'Hover', 'iteck_plg' ),
			]
		);

		$this->add_responsive_control(
			'padding_underline_menu_item',
			[
				'label' => __( 'Underline position (px)', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .navigation > li.sfHover > a:before' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		); 

		$this->add_control(
			'menu_hover_opacity',
			[
				'label' => __( 'Opacity', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'step' => 0.1,
						'max' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li a:hover' => 'opacity: {{SIZE}};',
				],
			]
		); 

		$this->add_control(
			'menu_color_hover',
			[
				'label' => __('Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_arrow_color_hover',
			[
				'label' => __('Arrow Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li a:hover::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-nav .pages_links li a:hover::after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'menu_background_hover',
			[
				'label' => __('Background', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'menu_border_hover',
				'selector' => '{{WRAPPER}} .iteck-nav .navigation > li:hover',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'menu_border_radius_hover',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_active',
			[
				'label' => __( 'Active', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'menu_background',
			[
				'label' => __('Background', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li.current-menu-item' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'menu_border_active',
				'selector' => '{{WRAPPER}} .iteck-nav .navigation > li.current-menu-item',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'menu_border_radius_active',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .navigation > li.current-menu-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		
		

		$this->end_controls_section(); 

		$this->start_controls_section(
			'section_style_submain-menu',
			[
				'label' => __( 'Main Sub-Menu', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout!' => 'dropdown',
				],

			]
		);


		$this->start_controls_tabs( 'tabs_aubmenu_item_style' );

		$this->start_controls_tab(
			'tab_submenu_item_normal',
			[
				'label' => __( 'Normal', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'submenu_color',
			[
				'label' => __('Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .menu-wrapper ul li ul li a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_submenu_item_hover',
			[
				'label' => __( 'Hover', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'submenu_color_hover',
			[
				'label' => __('Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .menu-wrapper ul li ul li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'submenu_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-nav .menu-wrapper ul li ul li a',
			]
		);

		$this->add_responsive_control(
			'padding_vertical_submenu_item',
			[
				'label' => __( 'Top Position (px)', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-nav .menu-wrapper ul li ul' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		); 
        $this->add_control(
			'boxes_border_radius',
			[
				'label' => __('Border Radius', 'iteck_plg'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .menu-wrapper ul li ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

		$this->end_controls_section(); 

		$this->start_controls_section(
			'section_style_mobile-menu',
			[
				'label' => __( 'Mobile Menu', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout!' => 'dropdown',
				],

			]
		);

		$this->add_control(
			'mobile_menu_color',
			[
				'label' => __('Mobile Icon Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .hamburger__icon, {{WRAPPER}} .hamburger__icon:before, {{WRAPPER}} .hamburger__icon:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mobile_menu_color_active',
			[
				'label' => __('Mobile Icon Active Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .hamburger.active .hamburger__icon:before, {{WRAPPER}} .hamburger.active .hamburger__icon:after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .hamburger.active .hamburger__icon' => 'background-color: transparent;',
				],
			]
		);

		$this->add_control(
			'padding_top_mobile_menu',
			[
				'label' => __( 'Top position', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .fat-nav' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);	

		$this->add_control(
			'mobile_menu_wrapper',
			[
				'label' => esc_html__('Wrapper Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .fat-nav__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
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
	protected function render() {
		$settings = $this->get_settings();

		$nav_menu = array( 'menu' => $settings['iteck_menu'],'echo' => true,'menu_id' => '','items_wrap' => '<ul id="%1$s" class="home-nav navigation %2$s">%3$s</ul>' );
		 
		if($settings['hover_style'] != 'underline'){
			$nav_menu['menu_class'] = $settings['hover_style'];
		}
	?>
                        
	<div class="iteck-nav">
		<?php if($settings['menu_style'] == '4'): ?>
			<div class="pages_links">
				<?php if(!empty($settings['iteck_menu'])){
					wp_nav_menu( $nav_menu ); 
				}?>
            </div>
		<?php else: ?>
			<div class="main-menu menu-wrapper d-none d-md-block  <?php if ($settings['menu_type']=='right'){echo 'iteck-right-menu';} ?> ">
				<?php 
				if(!empty($settings['iteck_menu'])){
					wp_nav_menu( $nav_menu ); 
				}?>
			</div><!--/.menu-box -- hidden-xs hidden-sm-->
			<div class="mobile-wrapper d-block d-md-none"> <!-- hidden-lg hidden-md --> 
				<a href="#" class="hamburger"><div class="hamburger__icon"></div></a>
				<div class="fat-nav">
					<div class="fat-nav__wrapper">
						<?php 
						$menuParameters = array(
							'menu' => $settings['iteck_menu'],
							'container'       => true,
							'items_wrap'      => '<ul id="%1$s" class="mob-nav  %2$s">%3$s</ul>',
							'depth'           => 0,
						);
						?>
						<div class="fat-list"> <?php echo strip_tags(wp_nav_menu( $menuParameters ), '<a>' ); ?></div>
					</div>
				</div>
			</div><!--/.box-mobile-->
		<?php endif; ?>
    </div>                   
                            
     <?php
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function content_template() { 
		
	}
}


