<?php
namespace IteckPlugin\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
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
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Iteck_Portfolio_Carousel extends Widget_Base { 

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
		return 'iteck-portfolio-carousel';
	}
	
	//script depend
	public function get_script_depends() { return [ 'jquery-isotope','jquery-swiper','wow','iteck-addons-custom-scripts','iteck-bootstrap-bundle']; }
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
		return __( 'Iteck Portfolio Carousel', 'iteck_plg' );
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
		return 'fa fa-clone';
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
	
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Portfolio Settings.', 'iteck_plg' ),
			]
		);
		
		$this->add_control(
			'portfolio_tabs',
			[
				'label' => __( 'Portfolio Tabs', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'portfolio_tabs_style',
			[
				'label' => __( 'Portfolio Tabs Style', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' => __( 'Style 1', 'iteck_plg' ),
					'style-2' => __( 'Style 2', 'iteck_plg' ),
				],
				'default' => 'style-1',
				'condition' => [
					'portfolio_tabs' => 'yes',
				]
			]
		);

		$this->add_control(
			'tabs_subtitle_img',
			[
				'label' => esc_html__( 'Tabs Sub-Title Image / info logo', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'portfolio_tabs' => 'yes',
				]
			]
		);

		$this->add_control(
			'tabs_subtitle',
			[
				'label' => esc_html__( 'Tabs Sub-Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your sub-title', 'iteck_plg' ),
				'default' => esc_html__( 'Case Study', 'iteck_plg' ),
				'condition' => [
					'portfolio_tabs' => 'yes',
				]
			]
		);

		$this->add_control(
			'tabs_title',
			[
				'label' => esc_html__( 'Tabs Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your title', 'iteck_plg' ),
				'default' => esc_html__( 'Creative Works We Done', 'iteck_plg' ),
				'condition' => [
					'portfolio_tabs' => 'yes',
				]
			]
		);

		$this->add_control(
			'tabs_btn_text',
			[
				'label' => esc_html__( 'Tabs Button Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Button Text', 'iteck_plg' ),
				'default' => esc_html__( 'More Projects', 'iteck_plg' ),
				'condition' => [
					'portfolio_tabs' => 'yes',
				]
			]
		);

		$this->add_control(
			'tabs_btn_link',
			[
				'label' => esc_html__( 'Tabs Button Link', 'iteck_plg' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'Enter Button link', 'iteck_plg' ),
				'condition' => [
					'portfolio_tabs' => 'yes',
				]
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
            'tab_title',
            [
                'label' => __('Tab Title', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Popular',
            ]
        );
		
		$repeater->add_control(
			'tab_portfolio_item',
			[
				'label' => __( 'Item to display', 'iteck_plg' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '8',
			]
		);

		$repeater->add_control(
			'tab_sort_cat',
			[
				'label' => __( 'Sort Portfolio by Portfolio Category', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);

		
		$repeater->add_control(
			'tab_blog_cat',
			[
				'label'   => __( 'Category to Show', 'iteck_plg' ),
				'type'    => Controls_Manager::SELECT2, 'options' => iteck_tax_choice(),
				'condition' => [
					'sort_cat' => 'yes',
				],
				'multiple'   => 'true',
			]
		);
		
		$repeater->add_control(
			'tab_port_order',
			[
				'label' => __( 'Orders', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'DESC' => __( 'Descending', 'iteck_plg' ),
					'ASC' => __( 'Ascending', 'iteck_plg' ),
					'rand' => __( 'Random', 'iteck_plg' ),
				],
				'default' => 'DESC',
			]
		);

        $this->add_control(
            'tabs_list',
            [
                'label' => esc_html__('Portfolio Tabs', 'iteck_plg'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'tab_title' => esc_html__('Popular', 'iteck_plg'),
                    ],
                    [
                        'tab_title' => esc_html__('Latest', 'iteck_plg'),
                    ],
                ],
                'title_field' => '{{{tab_title}}}',
				'condition' => [
					'portfolio_tabs' => 'yes'
				]
            ]
        );
		
		$this->add_control(
			'portfolio_item',
			[
				'label' => __( 'Item to display', 'iteck_plg' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '8',
				'condition' => [
					'portfolio_tabs!' => 'yes',
				]
			]
		);
		
		$this->add_control(
			'sort_cat',
			[
				'label' => __( 'Sort Portfolio by Portfolio Category', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
				'condition' => [
					'portfolio_tabs!' => 'yes',
				]
			]
		);

		
		$this->add_control(
			'blog_cat',
			[
				'label'   => __( 'Category to Show', 'iteck_plg' ),
				'type'    => Controls_Manager::SELECT2, 'options' => iteck_tax_choice(),
				'condition' => [
					'sort_cat' => 'yes',
				],
				'multiple'   => 'true',
				'condition' => [
					'portfolio_tabs!' => 'yes',
				]
			]
		);
		
		$this->add_control(
			'port_order',
			[
				'label' => __( 'Orders', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'DESC' => __( 'Descending', 'iteck_plg' ),
					'ASC' => __( 'Ascending', 'iteck_plg' ),
					'rand' => __( 'Random', 'iteck_plg' ),
				],
				'default' => 'DESC',
				'condition' => [
					'portfolio_tabs!' => 'yes',
				]
			]
		);

		$this->add_control(
			'show_tags',
			[
				'label' => __( 'Show Tags', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
				'condition' => [
					'portfolio_tabs!' => 'yes',
				]
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label' => __( 'Show Excerpt', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
				'condition' => [
					'portfolio_tabs!' => 'yes',
				]
			]
		);

		$this->add_control(
			'separated_image',
			[
				'label' => __( 'Separated Image', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
				'condition' => [
					'portfolio_tabs!' => 'yes',
				]
			]
		);

		$this->add_control(
			'slider_items',
			[
				'label' => __( 'Item to show', 'iteck_plg' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '3',
				'condition' => [
					'portfolio_tabs!' => 'yes',
				]
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'label' => __( 'Show Arrows', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Show', 'iteck_plg' ),
				'label_off' => __( 'Hide', 'iteck_plg' ),
				'return_value' => 'yes',
				'condition' => [
					'portfolio_tabs!' => 'yes',
				]
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => __( 'Show Navigation', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Show', 'iteck_plg' ),
				'label_off' => __( 'Hide', 'iteck_plg' ),
				'return_value' => 'yes',
				'condition' => [
					'portfolio_tabs!' => 'yes',
				]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'portfolio_styling',
			[
				'label' => __( 'Portfolio Item', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_portfolio_style');

		$this->start_controls_tab(
			'item_normal_tab',
			[
				'label' => esc_html__('Normal', 'iteck_plg'),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => esc_html__( 'Border', 'iteck_plg' ),
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card',
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_hover_tab',
			[
				'label' => esc_html__('Hover', 'iteck_plg'),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item_border_hover',
				'label' => esc_html__( 'Border', 'iteck_plg' ),
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card:hover',
			]
		);

		$this->add_control(
			'item_border_radius_hover',
			[
				'label' => esc_html__( 'Border Radius', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow_hover',
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'item_margin',
			[
				'label' => esc_html__('Item Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'item_space_between',
			[
				'label' => esc_html__( 'Slides Spacing', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 24,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
			]
		);

		$this->add_responsive_control(
			'item_container_padding',
			[
				'label' => esc_html__('Container Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .swiper-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'items_padding',
			[
				'label' => esc_html__('Items Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_title_styling',
			[
				'label' => __( 'Portfolio Title', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_title_color',
			[
				'label' => esc_html__( 'Title Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card .title a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .project-card.style-7 .info h3' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'item_title_color_hover',
			[
				'label' => esc_html__( 'Title Color Hover', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card:hover .title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'item_title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card:hover .title a, {{WRAPPER}} .iteck-slider-tabs-portfolio .project-card.style-7 .info h3',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_category_styling',
			[
				'label' => __( 'Portfolio Category', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_category_color',
			[
				'label' => esc_html__( 'Category Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card .info .category a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .project-card.style-7 .info h6' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_category_color_hover',
			[
				'label' => esc_html__( 'Category Color Hover', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card:hover .info .category a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'item_category_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card .info .category a, {{WRAPPER}} .iteck-slider-tabs-portfolio .project-card.style-7 .info h6',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_excerpt_styling',
			[
				'label' => __( 'Portfolio Excerpt', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_excerpt_color',
			[
				'label' => esc_html__( 'Excerpt Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card .info .text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'item_excerpt_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card .info .text',
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'section_tags',
			[
				'label' => esc_html__('Tags', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'tags_typography',
				'label'     => __('Typography', 'iteck_plg'),
				'selector'  => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card .info .tags a',
			]
		);

        $this->add_control(
			'tags_color',
			[
				'label' => __('Tags Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card .info .tags a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'tags_bg',
				'label' => __('Button Background', 'iteck_plg'),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_card .info .tags a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_arrow_styling',
			[
				'label' => __( 'Portfolio Arrows', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'arrows_size',
			[
				'label' => esc_html__( 'Arrows Size', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-container-rtl .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-container-rtl .swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
				'label' => esc_html__( 'Border', 'iteck_plg' ),
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-next, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-prev, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-prev, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-next, {{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-next, {{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-prev',
			]
		);

		$this->add_control(
			'arrows_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-container-rtl .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-container-rtl .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'arrows_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-next, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-prev, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-prev, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-next, {{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-next, {{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-prev',
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label' => esc_html__( 'Arrow Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-next::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-prev::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-prev::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-next::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-next::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-container-rtl .swiper-button-prev::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-prev::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-container-rtl .swiper-button-next::after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'arrows_bg',
				'label' => __('Background', 'iteck_plg'),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-next, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-prev, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-prev, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-next, {{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-next, {{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-prev',
				'fields_options' => [
					'color' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background: {{color.VALUE}} !important;',
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
				'label' => esc_html__( 'Border', 'iteck_plg' ),
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-next:hover, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-prev:hover, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-prev:hover, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-next:hover, {{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-next:hover, {{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-prev:hover',
			]
		);

		$this->add_control(
			'arrows_border_radius_hover',
			[
				'label' => esc_html__( 'Border Radius', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-prev:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-prev:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-container-rtl .swiper-button-prev:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-prev:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-container-rtl .swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'arrows_box_shadow_hover',
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-next:hover, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-prev:hover, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-prev:hover, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-next:hover, {{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-next:hover, {{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-prev:hover',
			]
		);

		$this->add_control(
			'arrow_color_hover',
			[
				'label' => esc_html__( 'Arrow Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-next:hover::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-prev:hover::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-prev:hover::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-next:hover::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-next:hover::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-container-rtl .swiper-button-prev:hover::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-prev:hover::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-container-rtl .swiper-button-next:hover::after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'arrows_bg_hover',
				'label' => __('Background', 'iteck_plg'),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-next:hover, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-prev:hover, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-button-prev:hover, {{WRAPPER}} .iteck-portfolio-carousel .portfolio_slider .swiper-container-rtl .swiper-button-next:hover, {{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-next:hover, {{WRAPPER}} .iteck-slider-tabs-portfolio .projects-tabs .swiper-button-prev:hover',
				'fields_options' => [
					'color' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background: {{color.VALUE}} !important;',
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_dots_styling',
			[
				'label' => __( 'Portfolio Dots', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'dot_color',
			[
				'label' => esc_html__( 'Dot Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .swiper-pagination-bullet' => 'color: {{VALUE}}; opacity: 1;',
				],
			]
		);

		$this->add_control(
			'dot_color_active',
			[
				'label' => esc_html__( 'Dot Active Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-portfolio-carousel .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs_subtitle_style',
			[
				'label' => esc_html__( 'Tabs Sub-Title', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'portfolio_tabs' => 'yes'
				]
			]
		);

		$this->add_control(
			'tabs_subtitle_color',
			[
				'label' => esc_html__( 'Tabs Sub-Title Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .top-title h5' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tabs_subtitle_typo',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-slider-tabs-portfolio .top-title h5',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs_title_style',
			[
				'label' => esc_html__( 'Tabs Title', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'portfolio_tabs' => 'yes'
				]
			]
		);

		$this->add_control(
			'tabs_title_color',
			[
				'label' => esc_html__( 'Tabs Title Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tabs_title_typo',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-slider-tabs-portfolio .title',
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
				],
				'default' => 'solid',
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
					'{{WRAPPER}} .iteck-slider-tabs-portfolio .title span' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .iteck-slider-tabs-portfolio .title span' => 'background: linear-gradient({{SIZE}}{{UNIT}}, {{gradient_bg_color1.VALUE}} {{gradient_bg_color1_stop.SIZE}}{{gradient_bg_color1_stop.UNIT}},{{gradient_bg_color2.VALUE}} {{gradient_bg_color2_stop.SIZE}}{{gradient_bg_color2_stop.UNIT}},{{gradient_bg_color3.VALUE}} {{gradient_bg_color3_stop.SIZE}}{{gradient_bg_color3_stop.UNIT}},{{gradient_bg_color4.VALUE}} {{gradient_bg_color4_stop.SIZE}}{{gradient_bg_color4_stop.UNIT}}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;',
                ],

                'condition' => [
                    'gradient_color_type' => [ 'gradient'],
                    'gradient_type' => 'linear',
                ],
                'of_type' => 'gradient',
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs_style_2',
			[
				'label' => esc_html__( 'Tabs Style 2', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'portfolio_tabs' => 'yes'
				]
			]
		);

		$this->add_control(
			'tabs_style_2_img',
			[
				'label' => esc_html__( 'Tabs Style Image', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'portfolio_tabs' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-slider-tabs-portfolio.style-2 .project-card .info::before' => 'background-image: url({{url}});'
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
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();

		$iteck_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

		if(isset($_GET['lang'])){
			$lang = $_GET['lang'];
		} else {
			$lang = '';
		}

		if($settings['portfolio_tabs'] != 'yes'):
			if ($settings['port_order'] != 'rand') {
				$order = 'order';
				$ord_val = $settings['port_order'];
			} else {
				$order = 'orderby';
				$ord_val = 'rand';
			}
			if ( $settings['sort_cat']  == 'yes' ) {
				$destudio_work = new \WP_Query(array(
					'posts_per_page'   => $settings['portfolio_item'],
					'post_type' =>  'portfolio', 'iteck_plg',
					'lang' => $lang,
					$order       =>  $ord_val,
					'tax_query' => array(
						array(
							'taxonomy' => 'portfolio_category',   // taxonomy name
							'field' => 'term_id',
							'terms' => $settings['blog_cat'],           // term_id, slug or name                // term id, term slug or term name
						)
					)
				)); 
			} else {
				$destudio_work = new \WP_Query(array(
					'paged' => $iteck_paged,
					'posts_per_page'   => $settings['portfolio_item'],
					'post_type' =>  'portfolio', 'iteck_plg',
					'lang' => $lang,
					$order       =>  $ord_val
				)); 
			}

		else:

			$tabs_query = [];
			foreach ($settings['tabs_list'] as $index => $item) :
				if ($item['tab_port_order'] != 'rand') {
					$order = 'order';
					$ord_val = $item['tab_port_order'];
				} else {
					$order = 'orderby';
					$ord_val = 'rand';
				}
				
				if ( $item['tab_sort_cat']  == 'yes' ) {
					$tabs_query[str_replace(' ', '-', $item['tab_title'])] = array(
						'posts_per_page'   => $item['tab_portfolio_item'],
						'post_type' =>  'portfolio', 'iteck_plg',
						$order       =>  $ord_val,
						'tax_query' => array(
							array(
								'taxonomy' => 'portfolio_category',   // taxonomy name
								'field' => 'term_id',
								'terms' => $item['tab_blog_cat'],           // term_id, slug or name                // term id, term slug or term name
							)
						)
					);
				} else {
					$tabs_query[str_replace(' ', '-', $item['tab_title'])] = array(
						'paged' => $iteck_paged,
						'posts_per_page'   => $item['tab_portfolio_item'],
						'post_type' =>  'portfolio', 'iteck_plg',
						$order       =>  $ord_val
					);
				}
				$tabs_query[str_replace(' ', '-', $item['tab_title'])] = new \WP_Query($tabs_query[str_replace(' ', '-', $item['tab_title'])]);
			endforeach;
		endif;

		$slides_spacing = $settings['item_space_between']['size'] ? $settings['item_space_between']['size'] : 24;
		

		if($settings['portfolio_tabs'] != 'yes'):?>

			<div class="iteck-portfolio-carousel <?php if($settings['separated_image'] == 'yes') echo 'separated-image'; ?>" data-slider-items="<?php echo esc_attr($settings['slider_items']); ?>" data-slides-spacing="<?php echo esc_attr($slides_spacing); ?>">
				<div class="content wow fadeIn slow">
					<div class="portfolio_slider">
						<div class="swiper-container">
							<div class="swiper-wrapper">
								<?php if ($destudio_work->have_posts()) : while  ($destudio_work->have_posts()) : $destudio_work->the_post();
								global $post ; ?>
								<div class="swiper-slide">
									<div class="portfolio_card">
										<div class="img">
											<img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="">
										</div>
										<div class="info">
											<h5 class="title">
												<a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a> 
											</h5>
											<small class="category">
												<?php
												$destudio_taxonomy = 'portfolio_category';
												$destudio_taxs = wp_get_post_terms($post->ID, $destudio_taxonomy);
												$destudio_cats = array();
												$count = 1;
												foreach ($destudio_taxs as $destudio_tax) { 
													if($count != 1) echo ', '; ?>
													<a class="cat" href="<?php echo esc_url( get_term_link( $destudio_tax->slug, $destudio_taxonomy ) ); ?>"><?php echo $destudio_tax->name; ?></a>
													<?php $count++;
												}; ?>
											</small>
											<?php if($settings['show_excerpt'] == 'yes'): ?>
											<div class="text"><?php the_excerpt(); ?></div>
											<?php endif;
											if($settings['show_tags'] == 'yes'): ?>
											<div class="tags">
											<?php if  (has_tag()) { ?>
												the_tags(' : ');?>
											<?php } ?>


											<?php
												$iteck_taxonomy_tag = 'porto_tag';
												$iteck_taxs_tag = wp_get_post_terms($post->ID, $iteck_taxonomy_tag);
												$iteck_tags = array();
												$count = 1;
												foreach ($iteck_taxs_tag as $iteck_tax_tag) { 
													if($count != 1) ?>
													<a class="cat" href="<?php echo esc_url( get_term_link( $iteck_tax_tag->slug, $iteck_taxonomy_tag ) ); ?>"><?php echo $iteck_tax_tag->name; ?></a>
													<?php $count++;
												}; ?>  
											</div>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<?php endwhile;
								else: ?>
									<div class="alert alert-warning"><?php _e('There is no Portfolio Post Found. You need to  choose the portfolio category to show or create at least 1 portfolio post first.','iteck-plg'); ?></div>
								<?php endif;  wp_reset_postdata();  ?>
							</div>
						</div>
						
						<?php if($settings['show_pagination'] == 'yes'): ?>
						<!-- ====== slider pagination ====== -->
						<div class="swiper-pagination"></div>
						<?php endif; ?>

						<?php if($settings['show_arrows'] == 'yes'): ?>
						<!-- ====== slider navigation ====== -->
						<div class="swiper-button-next"></div>
						<div class="swiper-button-prev"></div>
						<?php endif; ?>

					</div>
				</div>
			</div>
		<?php else: ?>
			<div class="iteck-slider-tabs-portfolio <?php echo $settings['portfolio_tabs_style']; ?>">
				<div class="content">
					<?php if($settings['portfolio_tabs_style'] == 'style-1'): ?>
					<div class="row">
						<div class="col-lg-3">
							<div class="section-head style-4 mb-50">
								<div class="top-title mb-10">
									<img src="<?php echo esc_url($settings['tabs_subtitle_img']['url']); ?>" alt="">
									<h5><?php echo $settings['tabs_subtitle'] ?></h5>
								</div>
								<h2 class="title"><?php echo $settings['tabs_title'] ?></h2>
							</div>
							<div class="projects-tabs">
								<ul class="nav nav-pills flex-column mb-3" id="pills-tab" role="tablist">
									<?php $first_tab = true;
									foreach ($settings['tabs_list'] as $index => $item) : ?>
										<li class="nav-item" role="presentation">
											<button class="nav-link <?php if ($first_tab) esc_html_e('active'); ?>" id="pills-<?php esc_html_e(str_replace(' ', '-', $item['tab_title'])); ?>-tab" data-bs-toggle="pill" data-bs-target="#pills-<?php esc_html_e(str_replace(' ', '-', $item['tab_title'])); ?>" type="button" role="tab" aria-controls="pills-<?php esc_html_e(str_replace(' ', '-', $item['tab_title'])); ?>" aria-selected="<?php if ($first_tab) esc_html_e('true'); else esc_html_e('false'); ?>">
												<i class="fa fa-long-arrow-right me-2 color-blue7"></i> <?php esc_html_e($item['tab_title']); ?> 
											</button>
										</li>
									<?php $first_tab = false;
									endforeach; ?>
								</ul>
								<div class="swiper-button-next">
									<i class="fa fa-long-arrow-right"></i>
								</div>
								<div class="swiper-button-prev">
									<i class="fa fa-long-arrow-left"></i>
								</div>
							</div>
						</div>
						<div class="col-lg-9">
							<?php else: ?>
								<div class="links-tabs">
									<ul class="nav nav-pills mb-40 justify-content-center" id="pills-tab" role="tablist">
										<?php $first_tab = true;
										foreach ($settings['tabs_list'] as $index => $item) : ?>
											<li class="nav-item" role="presentation">
												<button class="nav-link <?php if ($first_tab) esc_html_e('active'); ?>" id="pills-<?php esc_html_e(str_replace(' ', '-', $item['tab_title'])); ?>-tab" data-bs-toggle="pill" data-bs-target="#pills-<?php esc_html_e(str_replace(' ', '-', $item['tab_title'])); ?>" type="button" role="tab" aria-controls="pills-<?php esc_html_e(str_replace(' ', '-', $item['tab_title'])); ?>" aria-selected="<?php if ($first_tab) esc_html_e('true'); else esc_html_e('false'); ?>">
													<?php esc_html_e($item['tab_title']); ?> 
												</button>
											</li>
										<?php $first_tab = false;
										endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
							<div class="tab-content" id="pills-tabContent">
								<?php $first_tab = true;
								foreach ($settings['tabs_list'] as $index => $item) : ?>
									<div class="tab-pane fade show <?php if ($first_tab) esc_html_e('active'); ?>" id="pills-<?php esc_html_e(str_replace(' ', '-', $item['tab_title'])); ?>" role="tabpanel" aria-labelledby="pills-<?php esc_html_e(str_replace(' ', '-', $item['tab_title'])); ?>-tab">
										<div class="projects-slider7">
											<div class="swiper-container">
												<div class="swiper-wrapper">
													<?php while ($tabs_query[str_replace(' ', '-', $item['tab_title'])]->have_posts()) : $tabs_query[str_replace(' ', '-', $item['tab_title'])]->the_post(); global $post; ?>
														<div class="swiper-slide">
															<a href="<?php esc_url(the_permalink()); ?>" class="project-card">
																<div class="info">
																	<?php if($settings['portfolio_tabs_style'] == 'style-2'): ?><div class="row align-items-center"><div class="col-8"><?php endif; ?>
																	<h6>
																	<?php
																		$destudio_taxonomy = 'portfolio_category';
																		$destudio_taxs = wp_get_post_terms($post->ID, $destudio_taxonomy);
																		$destudio_cats = array();
																		$count = 1;
																		foreach ($destudio_taxs as $destudio_tax) { 
																			if($count != 1) echo ', '; ?>
																			<?php echo $destudio_tax->name; ?>
																			<?php $count++;
																		}; ?>
																	</h6>
																	<h3><?php the_title(); ?></h3>
																	<?php if($settings['portfolio_tabs_style'] == 'style-2'): ?>
																	</div>
																	<div class="col-4">
																		<div class="logo">
																			<?php if(!empty($settings['tabs_subtitle_img']['url'])): ?>
																			<img src="<?php echo esc_url($settings['tabs_subtitle_img']['url']); ?>" alt="">
																			<?php endif; ?>
																		</div>
																	</div>
																	</div>
																	<?php endif; ?>
																</div>
																<div class="img">
																	<img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="">
																</div>
															</a>
														</div>
													<?php endwhile; wp_reset_postdata(); ?>
												</div>
											</div>
										</div>
									</div>
								<?php $first_tab = false;
								endforeach; ?>
								<?php if($settings['portfolio_tabs_style'] == 'style-1'): ?>
								<a href="<?php echo $settings['tabs_btn_link']['url'] ?>" <?php if ( $settings['tabs_btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="iteck-button tabs-btn">
									<span class="iteck-button-content-wrapper">
										<span class="iteck-button-text"><?php echo $settings['tabs_btn_text'] ?></span>
										<span class="iteck-button-icon iteck-align-icon-right hover-animation-right-to-left"><i class="fas fa-long-arrow-alt-right bg-light"></i></span>
									</span>
								</a>
								<?php endif; ?>
							</div>
							<?php if($settings['portfolio_tabs_style'] == 'style-1'): ?>
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif;
		
		
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



