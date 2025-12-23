<?php
namespace IteckPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Iteck_Post_List extends Widget_Base { 

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
		return 'iteck-post-list';
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
		return __( 'Iteck Post List', 'iteck_plg' );
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
		return 'eicon-post-list';
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
				'label' => __( 'Post List Settings.', 'iteck_plg' ),
			]
		);

        $this->add_control(
			'blog_post',
			[
				'label' => __('Blog Post to show', 'iteck_plg'),
				'type' => Controls_Manager::NUMBER,
				'default' => '6',

			]
		);

		$this->add_control(
			'2columns_view',
			[
				'label' => __('2 columns view', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Yes', 'iteck_plg'),
				'label_off' => __('No', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'cards_view',
			[
				'label' => __('Cards View', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __('Yes', 'iteck_plg'),
				'label_off' => __('No', 'iteck_plg'),
				'return_value' => 'yes',
				'condition' => [
					'2columns_view!' => 'yes',
				]

			]
		);
        
        $this->add_control(
			'cards_view_style',
			[
				'label' => __( 'Card View Style', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' => __( 'Style 1', 'iteck_plg' ),
					'style-2' => __( 'Style 2', 'iteck_plg' ),
					'style-3' => __( 'Style 3', 'iteck_plg' ),
				],
				'default' => 'style-1',
				'condition' => [
					'cards_view' => 'yes',
				]
			]
		);

		$this->add_control(
			'card_style3_icon',
			[
				'label' => __('Image', 'iteck_plg'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'2columns_view!' => 'yes',
					'cards_view' => 'yes',
					'cards_view_style' => 'style-3'
				]
			]
		);
        
        $this->add_control(
			'sideposts_ratio',
			[
				'label' => __( 'Side Posts Ratio', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'5-7' => __( '5:7', 'iteck_plg' ),
					'4-8' => __( '4:8', 'iteck_plg' ),
				],
				'default' => '5-7',
				'condition' => [
					'2columns_view' => 'yes',
				]
			]
		);
        
        $this->add_control(
			'columns_number',
			[
				'label' => __( 'Columns number', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'12' => __( '1 Column', 'iteck_plg' ),
					'6' => __( '2 Columns', 'iteck_plg' ),
					'4' => __( '3 Columns', 'iteck_plg' ),
					'3' => __( '4 Columns', 'iteck_plg' ),
				],
				'default' => '12',
				'condition' => [
					'2columns_view!' => 'yes',
					'cards_view_style!' => 'style-3'
				]
			]
		);

		$this->add_control(
			'sort_cat',
			[
				'label' => __('Sort post by Category', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __('Yes', 'iteck_plg'),
				'label_off' => __('No', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'blog_cat',
			[
				'label'   => __('Category', 'iteck_plg'),
				'type'    => Controls_Manager::SELECT2, 'options' => iteck_category_choice(),
				'condition' => [
					'sort_cat' => 'yes',
				],
				'multiple'   => 'true',
			]
		);

		$this->add_control(
			'paged_on',
			[
				'label' => __('Always show the same list on every page(not paged).', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'label_block' => true,
				'default' => '',
				'label_on' => __('Yes', 'iteck_plg'),
				'label_off' => __('No', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label' => __('Show Exerpt', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_excerpt2',
			[
				'label' => __('Show Second Exerpt', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
				'condition' => [
					'show_excerpt' => 'yes',
					'2columns_view' => 'yes'
				]
			]
		);

		$this->add_control(
			'excerpt',
			[
				'label' => __('Blog Excerpt Length', 'iteck_plg'),
				'type' => Controls_Manager::NUMBER,
				'default' => '150',
				'min' => 10,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_after',
			[
				'label' => __('After Excerpt text/symbol', 'iteck_plg'),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'show_excerpt' => 'yes',
				],
				'default' => '...',
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __('Show Featured Image', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_cat',
			[
				'label' => __('Show Category', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'cat_separator',
			[
				'label' => __('Categories Separator', 'iteck_plg'),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'show_cat' => 'yes',
				],
				'default' => '-',
			]
		);

		$this->add_control(
			'show_main_cat',
			[
				'label' => __('Main Show Category', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
				'condition' => [
					'2columns_view' => 'yes'
				]
			]
		);
        
        $this->add_control(
			'main_cat_pos',
			[
				'label' => __( 'Main Category Position', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => __( 'Top', 'iteck_plg' ),
					'bottom' => __( 'Bottom', 'iteck_plg' ),
				],
				'default' => 'bottom',
				'condition' => [
					'2columns_view' => 'yes',
					'show_main_cat' => 'yes'
				]
			]
		);
        
        $this->add_control(
			'top_info',
			[
				'label' => __( 'Info Style', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' => __( 'style 1', 'iteck_plg' ),
					'style-2' => __( 'style 2', 'iteck_plg' ),
					'style-3' => __( 'style 3', 'iteck_plg' ),
				],
				'default' => 'style-1',
			]
		);

		$this->add_control(
			'date_under_author',
			[
				'label' => __('Show Date Under Author', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
				'condition' => [
					'top_info' => 'style-1',
				]
			]
		);

		$this->add_control(
			'info_pos',
			[
				'label' => esc_html__( 'Info Position', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'top',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'iteck_plg' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'iteck_plg' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'condition' => [
					'top_info' => 'style-2',
				]
			]
		);

		$this->add_control(
			'date',
			[
				'label' => __('Show date', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_comments',
			[
				'label' => __('Show Comments', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_views',
			[
				'label' => __('Show Views', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'author',
			[
				'label' => __('Show Author', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		

		$this->add_control(
			'show_tags',
			[
				'label' => __('Show Tags', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'before_author_text',
			[
				'label' => __('Before Author Text', 'iteck_plg'),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'author' => 'yes',
				],
				'default' => 'By ',
			]
		);

		$this->add_control(
			'item_separator',
			[
				'label' => __('Item Separator', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);
        
        $this->add_control(
			'item_separator_style',
			[
				'label' => __( 'Item Separator Style', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' => __( 'style 1', 'iteck_plg' ),
					'style-2' => __( 'style 2', 'iteck_plg' ),
				],
				'default' => 'style-1',
				'condition' => [
					'item_separator' => 'yes'
				]
			]
		);

		$this->add_control(
			'read_more_btn',
			[
				'label' => __('Read More Button', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __('Show', 'iteck_plg'),
				'label_off' => __('Hide', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label' => __('Read More Button Text', 'iteck_plg'),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'author' => 'yes',
				],
				'default' => 'Read More',
				'condition' => [
					'read_more_btn' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_settings',
			[
				'label' => __( 'item Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_margin',
			[
				'label' => esc_html__('Item Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__('Item Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_content_padding',
			[
				'label' => esc_html__('Content Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => esc_html__( 'Border Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .card' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .iteck-post-list .card .categories' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .iteck-post-list .item-separator.style-2::after' => 'background-color: {{VALUE}};',
					
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'card_background',
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-post-list .card',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'card_border',
				'selector' => '{{WRAPPER}} .iteck-post-list .card',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'side_posts_settings',
			[
				'label' => __( 'Side Posts Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'2columns_view' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'side_posts_margin',
			[
				'label' => esc_html__('Item Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .side-posts .card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'side_posts_padding',
			[
				'label' => esc_html__('Item Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .side-posts .card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'side_posts_content_padding',
			[
				'label' => esc_html__('Content Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .side-posts .card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'side_posts_border_color',
			[
				'label' => esc_html__( 'Border Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .side-posts .card' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .iteck-post-list .side-posts .card .categories' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'title_settings',
			[
				'label' => __( 'Title Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->start_controls_tabs('tabs_title_style');

		$this->start_controls_tab(
			'tab_title_normal',
			[
				'label' => esc_html__('Normal', 'iteck_plg'),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .iteck-post-list .title',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_title_hover',
			[
				'label' => esc_html__('Hover', 'iteck_plg'),
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label' => esc_html__( 'Title Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list a:hover .title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography_hover',
				'selector' => '{{WRAPPER}} .iteck-post-list a:hover .title',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__('Title Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__('Title Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'category_settings',
			[
				'label' => __( 'Category Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'category_border',
			[
				'label' => esc_html__('Category Border', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .categories' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'category_border_color',
			[
				'label' => esc_html__( 'Border Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .categories' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'category_color',
			[
				'label' => esc_html__( 'Category Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .categories a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-post-list .categories' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'category_bg',
			[
				'label' => esc_html__( 'Category Background', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .card .card-body .top-info.style-2 .categories a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'category_typography',
				'selector' => '{{WRAPPER}} .iteck-post-list .categories a',
			]
		);

		$this->add_responsive_control(
			'category_margin',
			[
				'label' => esc_html__('Category Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'date_settings',
			[
				'label' => __( 'Date Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'dot_before',
			[
				'label' => __('Dot Before', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __('Yes', 'iteck_plg'),
				'label_off' => __('No', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);	
		
		$this->add_control(
			'icon_before',
			[
				'label' => __('Icon Before', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Yes', 'iteck_plg'),
				'label_off' => __('No', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);	

		$this->add_control(
			'date_icon_color',
			[
				'label' => esc_html__( 'Date Icon Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .top-info i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => esc_html__( 'Date Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .date' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'selector' => '{{WRAPPER}} .iteck-post-list .date',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'author_settings',
			[
				'label' => __( 'Author Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'author_avatar_bg_color',
			[
				'label' => esc_html__( 'Author Avatar Background Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .card .card-body .bottom-info .l-side .author-avater' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'author_avatar_color',
			[
				'label' => esc_html__( 'Author Avatar Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .card .card-body .bottom-info .l-side .author-avater' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'author_name_color',
			[
				'label' => esc_html__( 'Author Name Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .author-name b' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-post-list .top-info.style-3 .categories a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'author_color',
			[
				'label' => esc_html__( 'Author Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .author-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_name_typography',
				'selector' => '{{WRAPPER}} .iteck-post-list .author-name b, {{WRAPPER}} .iteck-post-list .top-info.style-3 .categories a',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_typography',
				'selector' => '{{WRAPPER}} .iteck-post-list .author-name',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'excerpt_settings',
			[
				'label' => __( 'Excerpt Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' => esc_html__( 'Excerpt Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .iteck-post-list .excerpt',
			]
		);

		$this->add_responsive_control(
			'excerpt_margin',
			[
				'label' => esc_html__('Excerpt Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'info_settings',
			[
				'label' => __( 'Info Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'comments_color',
			[
				'label' => esc_html__( 'Comments Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .r-side span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'comments_icon_color',
			[
				'label' => esc_html__( 'Comments Icon Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .r-side .comments' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'views_icon_color',
			[
				'label' => esc_html__( 'Views Icon Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .r-side .views' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'comments_typography',
				'selector' => '{{WRAPPER}} .iteck-post-list .r-side .comments, {{WRAPPER}} .iteck-post-list .r-side .views',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'thumbnail_settings',
			[
				'label' => __( 'Thumbnail Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'thumbnail_height',
			[
				'label' => esc_html__( 'Thumbnail Height', 'iteck_plg' ),
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
					'{{WRAPPER}} .iteck-post-list .card .img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'side_posts_thumbnail_height',
			[
				'label' => esc_html__( 'Side Posts Thumbnail Height', 'iteck_plg' ),
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
					'{{WRAPPER}} .iteck-post-list .side-posts .card .img' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'2columns_view' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'thumbnail_border',
				'selector' => '{{WRAPPER}} .iteck-post-list .card .img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'thumbnail_border_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .card .img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-post-list .card .img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'read_more_settings',
			[
				'label' => __( 'Read More Button Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'read_more_typography',
				'selector' => '{{WRAPPER}} .iteck-post-list .read-more',
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label' => esc_html__( 'Read More Button Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .read-more' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'read_more_margin',
			[
				'label' => esc_html__('Read More Button Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-post-list .read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$settings = $this->get_settings();

		if ($settings['paged_on']  != 'yes') {
			$iteck_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		} else {
			$iteck_paged = '';
		}
		if ($settings['sort_cat']  == 'yes') {
			$query = new \WP_Query(array(
				'posts_per_page'   => $settings['blog_post'],
				'paged' => $iteck_paged,
				'post_type' => 'post',
				'cat' => $settings['blog_cat']

			));
		} else {
			$query = new \WP_Query(array(
				'posts_per_page'   => $settings['blog_post'],
				'paged' => $iteck_paged,
				'post_type' => 'post'
			));
		}

		if($settings['sideposts_ratio'] == '5-7'):
			$image_col = '5';
			$content_col = '7';
		else:
			$image_col = '4';
			$content_col = '7';
		endif;

		?>

		<div class="iteck-post-list <?php if($settings['cards_view'] == 'yes') echo 'cards-view-'. $settings['cards_view_style'] .''; ?>">
            <div class="row justify-content-center">
                <div class="col-lg-12">
					<div class="row <?php if($settings['item_separator'] == 'yes') echo 'gx-5'; ?>">
						<?php if($settings['2columns_view'] == 'yes'): ?>
							<div class="col-lg-6 border-left">
								<?php while ($query->have_posts()) : $query->the_post(); ?>
									<div class="card border-0">
										<div class="img">
											<?php if(has_post_thumbnail()): ?>
												<img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" class="card-img-top" alt="...">
											<?php endif; ?>
											<?php if($settings['show_main_cat'] == 'yes' && $settings['main_cat_pos'] == 'top'): ?>
												<div class="top-categories"><?php the_category(' '. $settings['cat_separator'] .' '); ?></div>
											<?php endif; ?>
										</div>
										<div class="card-body px-0">
											<?php if(($settings['top_info'] == 'style-2' && $settings['info_pos'] == 'top') || $settings['top_info'] == 'style-1' || $settings['top_info'] == 'style-3'): ?>
												<small class="top-info <?php echo esc_attr($settings['top_info']); ?>">
													<?php if($settings['show_main_cat'] == 'yes' && $settings['main_cat_pos'] == 'bottom'): ?>
														<span class="categories"><?php if($settings['top_info'] != 'style-3') the_category(' '. $settings['cat_separator'] .' '); else echo '<a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ).'">'.get_the_author_meta( 'nickname' ).'</a>'; ?></span>
													<?php endif; ?>
													<?php if(($settings['top_info'] == 'style-1' && $settings['date_under_author'] != 'yes') || $settings['top_info'] != 'style-1'):
														if($settings['top_info'] != 'style-2' && $settings['icon_before'] == 'yes'): ?>
															<i class="<?php if($settings['top_info'] != 'style-3') echo 'bi bi-clock me-1'; else echo 'fal fa-calendar-alt me-1'; ?>"></i>
														<?php endif; ?>
														<a href="<?php echo esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j'))); ?>" class="date <?php if($settings['dot_before'] == 'yes') echo 'dot-before'; ?>"><?php echo get_the_date(__('M j, Y')); ?></a>
													<?php endif; ?>
													<?php if($settings['top_info'] == 'style-2'): ?>
													<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="author-name">
														<?php esc_html_e($settings['before_author_text']);?><b><?php echo get_the_author_meta( 'nickname' ); ?></b>
													</a>
													<?php endif; ?>
												</small>
											<?php endif; ?>
											<a href="<?php echo esc_url(get_the_permalink()); ?>">
												<h4 class="title"><?php the_title(); ?></h4>
											</a>
											<?php if($settings['show_excerpt'] == 'yes' && !empty(get_the_excerpt())): ?>
											<p class="excerpt">
												<?php $excerpt = get_the_excerpt();
												$excerpt = substr($excerpt, 0, $settings['excerpt']);
												echo $excerpt;
												echo esc_attr($settings['excerpt_after']) ?>
											</p>
											<?php endif; ?>
											<?php if($settings['top_info'] == 'style-2' && $settings['info_pos'] == 'bottom'): ?>
												<small class="top-info <?php echo esc_attr($settings['top_info']); ?>">
													<?php if($settings['show_main_cat'] == 'yes' && $settings['main_cat_pos'] == 'bottom'): ?>
														<span class="categories"><?php the_category(' '. $settings['cat_separator'] .' '); ?></span>
													<?php endif; ?>
													<?php if($settings['top_info'] != 'style-2' && $settings['icon_before'] == 'yes'): ?>
													<i class="bi bi-clock me-1"></i>
													<?php endif; ?>
													<a href="<?php echo esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j'))); ?>" class="date <?php if($settings['dot_before'] == 'yes') echo 'dot-before'; ?>"><?php echo get_the_date(__('M j, Y')); ?></a>
													<?php if($settings['top_info'] == 'style-2'): ?>
													<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="author-name">
														<?php esc_html_e($settings['before_author_text']);?><b><?php echo get_the_author_meta( 'nickname' ); ?></b>
													</a>
													<?php endif; ?>
												</small>
											<?php endif; ?>
											<?php if($settings['top_info'] != 'style-2'  && ($settings['author'] == 'yes' || $settings['show_comments'] == 'yes' || $settings['show_views'] == 'yes')): ?>
											<div class="bottom-info">
												<?php if($settings['author'] == 'yes'): ?>
												<div class="l-side">
													<span class="author-avater">
														<?php echo get_the_author_meta( 'nickname' )['0']; ?>
													</span>
													<?php if($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-2'): ?><div class="l-side-info"><?php endif; ?>
														<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="author-name">
															<?php esc_html_e($settings['before_author_text']);?><b><?php echo get_the_author_meta( 'nickname' ); ?></b>
														</a>
														<?php if(($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-2' && $settings['date'] == 'yes') || ($settings['cards_view'] == 'yes' && $settings['date'] == 'yes' && $settings['top_info'] == 'style-1' && $settings['date_under_author'] == 'yes')): ?>
															<a href="<?php echo esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j'))); ?>" class="date <?php if($settings['dot_before'] == 'yes') echo 'dot-before'; ?>"><span> <?php if($settings['top_info'] != 'style-1') echo 'Date:'; ?> </span><?php echo get_the_date(__('M j, Y')); ?></a>
														<?php endif; ?>
													<?php if($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-2'): ?></div><?php endif; ?>
												</div>
												<?php endif; 
												if($settings['show_comments'] == 'yes' || $settings['show_views'] == 'yes'): ?>
												<div class="r-side">
													<?php if($settings['show_comments'] == 'yes'): ?>
													<i class="bi bi-chat-left-text comments"></i>
													<span><?php echo get_comments_number(); ?></span>
													<?php endif; ?>
													<?php if($settings['show_views'] == 'yes'): ?>
													<i class="bi bi-eye views"></i>
													<span><?php echo esc_html( iteck_get_post_view()); ?></span>
													<?php endif; ?>
												</div>
												<?php endif; ?>
											</div>
											<?php endif; ?>
										</div>
										<?php if($settings['show_tags'] == 'yes') : $post_tags = get_the_tags(); ?>
											<?php if ( $post_tags ) : ?>
												<div class="tags">
													<?php foreach( $post_tags as $tag ) : ?>
														<span class="tag"><?php echo esc_html( $tag->name ); ?></span>
													<?php endforeach; ?>
												</div>
											<?php endif; ?>
										<?php endif; ?>
									</div>
									<?php break;
								endwhile; ?>
							</div>
						<?php endif; ?>
						<?php if($settings['2columns_view'] == 'yes'): ?><div class="col-lg-6 side-posts"><?php endif; ?>
							<?php $card_counter = 1; $count = 1; while ($query->have_posts()) : $query->the_post(); if($settings['cards_view'] == 'yes' && $settings['cards_view_style'] != 'style-2' && $card_counter == 1): $first_card = true; else: $first_card = false; endif;
								if(($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-1') && !$first_card && $card_counter == 2): echo '<div class="col-lg-7"><div class="row">'; endif; ?>
								<?php if($settings['2columns_view'] != 'yes' && $settings['columns_number'] != '1'): ?><div class="col-lg-<?php if($settings['cards_view'] == 'yes' && $settings['cards_view_style'] != 'style-3' && $first_card): echo '5'; elseif($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-1' && !$first_card): echo '6'; elseif($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-3' && $first_card): echo '8'; elseif($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-3' && !$first_card): echo '4'; else: echo $settings['columns_number']; endif; if($count%3 != 0 && $settings['columns_number'] != '12' && $settings['item_separator'] == 'yes'): echo ' item-separator '.$settings['item_separator_style'].''; endif; ?>"><?php endif; ?>
									<div class="card <?php if ($query->current_post +1 == $query->post_count) echo ' border-0'; if($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-3' && $first_card) echo 'main-card'; if($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-3' && !$first_card) echo ' sub-card'; ?>">
										<div class="row <?php if($settings['cards_view'] == 'yes') echo 'gx-0'; ?>">
											<?php if(($settings['cards_view'] == 'yes' && $first_card && $settings['cards_view_style'] == 'style-1') || ($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-3') || $settings['cards_view'] != 'yes'): ?>
												<div class="col-lg-<?php if($settings['2columns_view'] == 'yes'): echo $image_col; elseif(($settings['2columns_view'] != 'yes' && $settings['cards_view'] == 'yes' && $first_card && $settings['cards_view_style'] == 'style-1') || ($settings['2columns_view'] != 'yes' && $settings['columns_number'] == '12')): echo '4'; elseif($settings['2columns_view'] != 'yes' && $settings['cards_view'] == 'yes' && $first_card && $settings['cards_view_style'] == 'style-3'): echo '6'; else: echo '12'; endif; ?>">
													<?php if(has_post_thumbnail()): ?>
														<div class="img">
															<img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="...">
															<?php if($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-3'): ?> 
																<div class="center_icon img-contain">
																	<img src="<?php echo esc_url($settings['card_style3_icon']['url']) ?>" alt="">
																</div>
															<?php endif; ?>
														</div>
													<?php endif; ?>
												</div>
											<?php endif; ?>
											<div class="col-lg-<?php if($settings['2columns_view'] == 'yes'): echo $content_col; elseif(($settings['2columns_view'] != 'yes' && $settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-1' && $first_card) || ($settings['2columns_view'] != 'yes' && $settings['columns_number'] == '12')): echo '8'; elseif($settings['2columns_view'] != 'yes' && $settings['cards_view'] == 'yes' && $first_card && $settings['cards_view_style'] == 'style-3'): echo '6'; else: echo '12'; endif; ?>">
												<div class="card-body <?php if($settings['2columns_view'] != 'yes' && $settings['columns_number'] == '2' &&  $settings['cards_view_style'] != 'style-3') echo 'ptb-20'; elseif($settings['cards_view_style'] == 'style-3') echo ''; else echo 'ptlg-0'; ?>">
													<?php if($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-3') echo '<div class="cont">'; if(($settings['top_info'] == 'style-2' && $settings['info_pos'] == 'top') || $settings['top_info'] == 'style-1' || $settings['top_info'] == 'style-3'): ?>
														<small class="top-info <?php echo esc_attr($settings['top_info']); ?>">
															<?php if($settings['show_cat'] == 'yes'): ?>
																<span class="categories"><?php if($settings['top_info'] != 'style-3') the_category(' '. $settings['cat_separator'] .' '); else echo '<a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ).'">'.get_the_author_meta( 'nickname' ).'</a>'; ?></span>
															<?php endif; ?>
															<?php if(($settings['cards_view_style'] == 'style-1' || $settings['cards_view'] == 'yes') && $settings['date'] == 'yes'): ?>
																<?php if(($settings['top_info'] == 'style-1' && $settings['date_under_author'] != 'yes') || $settings['top_info'] != 'style-1'):
																	if($settings['top_info'] != 'style-2' && $settings['icon_before'] == 'yes'): ?>
																	<i class="<?php if($settings['top_info'] != 'style-3') echo 'bi bi-clock me-1'; else echo 'fal fa-calendar-alt me-1'; ?>"></i>
																	<?php endif; ?>
																	<a href="<?php echo esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j'))); ?>" class="date <?php if($settings['dot_before'] == 'yes') echo 'dot-before'; ?>"><?php echo get_the_date(__('M j, Y')); ?></a>
																<?php endif;
															endif; ?>
															<?php if($settings['top_info'] == 'style-2'): ?>
															<span class="author-name">
																<?php esc_html_e($settings['before_author_text']);?><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><b><?php echo get_the_author_meta( 'nickname' ); ?></b></a>
															</span>
															<?php endif; ?>
														</small>
													<?php endif; ?>
													<a href="<?php echo esc_url(get_the_permalink()); ?>">
														<h6 class="title"><?php the_title(); ?></h6>
													</a>
													<?php if($settings['show_excerpt'] == 'yes' && $settings['show_excerpt2'] == 'yes' && !empty(get_the_excerpt())): ?>
													<p class="excerpt">
														<?php $excerpt = get_the_excerpt();
														$excerpt = substr($excerpt, 0, $settings['excerpt']);
														echo $excerpt;
														echo esc_attr($settings['excerpt_after']) ?>
													</p>
													<?php endif; ?>
													<?php if($settings['top_info'] == 'style-2' && $settings['info_pos'] == 'bottom'): ?>
														<small class="top-info <?php echo esc_attr($settings['top_info']); ?>">
															<?php if($settings['show_cat'] == 'yes'): ?>
																<span class="categories"><?php the_category(' '. $settings['cat_separator'] .' '); ?></span>
															<?php endif; ?>
															<?php if($settings['top_info'] != 'style-2' && $settings['icon_before'] == 'yes'): ?>
															<i class="bi bi-clock me-1"></i>
															<?php endif; 
															if($settings['date'] == 'yes'): ?>
															<a href="<?php echo esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j'))); ?>" class="date <?php if($settings['dot_before'] == 'yes') echo 'dot-before'; ?>"><?php echo get_the_date(__('M j, Y')); ?></a>
															<?php endif; ?>
															<?php if($settings['top_info'] == 'style-2'): ?>
																<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="author-name">
																	<?php esc_html_e($settings['before_author_text']);?><b><?php echo get_the_author_meta( 'nickname' ); ?></b>
																</a>
															<?php endif; ?>
														</small>
													<?php endif; ?>
													<?php if(($settings['top_info'] == 'style-3' || ($settings['top_info'] == 'style-1' && $settings['date_under_author'] == 'yes' && $first_card) || ($settings['top_info'] == 'style-1' && $settings['date_under_author'] != 'yes'))  && ($settings['author'] == 'yes' || $settings['show_comments'] == 'yes' || $settings['show_views'] == 'yes')): ?>
													<div class="bottom-info">
														<?php if($settings['author'] == 'yes'): ?>
														<div class="l-side <?php if($settings['cards_view'] == 'yes' && $settings['top_info'] == 'style-1' && $settings['date_under_author'] == 'yes') echo 'date-bottom'; ?>">
															<span class="author-avater">
																<?php echo get_the_author_meta( 'nickname' )['0']; ?>
															</span>
															<?php if($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-2'): ?><div class="l-side-info"><?php elseif($settings['cards_view'] == 'yes' && $settings['top_info'] == 'style-1' && $settings['date_under_author'] == 'yes'): ?><div class="inf"><?php endif; ?>
																<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="author-name">
																	<?php esc_html_e($settings['before_author_text']);?><b><?php echo get_the_author_meta( 'nickname' ); ?></b>
																</a>
																<?php if(($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-2' && $settings['date'] == 'yes') || ($settings['cards_view'] == 'yes' && $settings['date'] == 'yes' && $settings['top_info'] == 'style-1' && $settings['date_under_author'] == 'yes' && $first_card)): ?>
																	<a href="<?php echo esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j'))); ?>" class="date <?php if($settings['dot_before'] == 'yes') echo 'dot-before'; ?>"><span> <?php if($settings['top_info'] != 'style-1') echo 'Date:'; ?> </span><?php echo get_the_date(__('M j, Y')); ?></a>
																<?php endif; ?>
															<?php if($settings['cards_view'] == 'yes' && ($settings['cards_view_style'] == 'style-2' || ($settings['top_info'] == 'style-1' && $settings['date_under_author'] == 'yes'))): ?></div><?php endif; ?>
														</div>
														<?php endif; 
														if($settings['show_comments'] == 'yes' || $settings['show_views'] == 'yes'): ?>
														<div class="r-side">
															<?php if($settings['show_comments'] == 'yes'): ?>
															<i class="bi bi-chat-left-text comments"></i>
															<span><?php echo get_comments_number(); ?></span>
															<?php endif; ?>
															<?php if($settings['show_views'] == 'yes'): ?>
															<i class="bi bi-eye views"></i>
															<span><?php echo esc_html( iteck_get_post_view()); ?></span>
															<?php endif; ?>
														</div>
														<?php endif; ?>
													</div>
													<?php endif; ?>
													<?php if($settings['read_more_btn'] == 'yes'): ?>
														<a href="<?php esc_url(the_permalink()); ?>" class="read-more"><?php echo esc_html($settings['read_more_text']); ?></a>
													<?php endif; ?>
													<?php if($settings['show_tags'] == 'yes') : $post_tags = get_the_tags(); ?>
														<?php if ( $post_tags ) : ?>
															<div class="tags">
																<?php foreach( $post_tags as $tag ) : ?>
																	<span class="tag"><?php echo esc_html( $tag->name ); ?></span>
																<?php endforeach; ?>
															</div>
														<?php endif; ?>
													<?php endif; ?>
												</div>
											</div>
										</div>
										<?php if($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-3') echo '</div>'; ?>
									</div>
								<?php if($settings['2columns_view'] != 'yes' && $settings['columns_number'] != '1'): ?></div><?php endif;
								if($settings['cards_view'] == 'yes' && $settings['cards_view_style'] == 'style-1' && !$first_card && $card_counter == 3): echo '</div></div>'; endif; ?>
							<?php $count++; $card_counter++; if($card_counter == 4): $card_counter = 1; endif; endwhile;wp_reset_postdata(); ?>
						<?php if($settings['2columns_view'] == 'yes'): ?></div><?php endif; ?>
					</div>
                </div>
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
	protected function content_template() {

	}
}



