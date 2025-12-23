<?php

namespace IteckPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Utils;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Iteck_Pricing_Plans extends Widget_Base
{

    public function get_name()
    {
        return 'iteck-pricing-plans';
    }
	
	//script depend
	public function get_script_depends() { return [ 'iteck-pricing','iteck-jquery-ui','iteck-bootstrap-bundle']; }

    public function get_title()
    {
        return __('Iteck Pricing Plans', 'iteck_plg');
    }

    public function get_icon()
    {
        return 'eicon-price-table';
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

    protected function _register_controls()
    {
		

		$this->start_controls_section(
			'pricing_section',
			[
				'label' => __('Pricing plans', 'iteck_plg'),
			]
		);

		$this->add_control(
			'plans_mode',
			[
				'label' => __( 'Yearly and Monthly Plans Group', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);

        $this->add_control(
			'plans_style',
			[
				'label' => __( 'Plans Style', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'no-slider' => esc_html__( 'Yearly and Monthly', 'iteck_plg' ),
					'with-slider' => esc_html__( 'Yearly and Monthly With Slider', 'iteck_plg' ),
				],
				'default' => 'no-slider',
				'condition' => [
					'plans_mode' => 'yes',
				]
			]
		);

        $this->add_control(
			'plans_title',
			[
				'label' => esc_html__( 'Plans Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Minimalist Plans', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'plans_mode' => 'yes',
					'plans_style' => 'with-slider',
				]
			]
		);

        $this->add_control(
			'plans_subtitle',
			[
				'label' => esc_html__( 'Plans Sub-Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'OUR PLANS', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'plans_mode' => 'yes',
					'plans_style' => 'with-slider',
				]
			]
		);

        $this->add_control(
			'slider_text',
			[
				'label' => esc_html__( 'Slider Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'users', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'plans_mode' => 'yes',
					'plans_style' => 'with-slider',
				]
			]
		);

        $this->add_control(
			'plans_columns',
			[
				'label' => __( 'Plans Columns', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'12' => esc_html__( '1 Column', 'iteck_plg' ),
					'6' => esc_html__( '2 Columns', 'iteck_plg' ),
					'4' => esc_html__( '3 Columns', 'iteck_plg' ),
					'3' => esc_html__( '4 Columns', 'iteck_plg' ),
				],
				'default' => '4',
				'condition' => [
					'plans_mode' => 'yes',
				]
			]
		);

        $this->add_control(
			'monthly_plans_button_text',
			[
				'label' => esc_html__( 'Monthly Plans Button Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Billed Monthly', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'plans_mode' => 'yes',
				]
			]
		);

        $this->add_control(
			'yearly_plans_button_text',
			[
				'label' => esc_html__( 'Yearly Plans Button Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Billed annually', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'plans_mode' => 'yes',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'group_plans_content',
			[
				'label' => __('Pricing Content', 'iteck_plg'),
				'condition' => [
					'plans_mode' => 'yes',
				]
			]
		);

		$group_repeater = new \Elementor\Repeater();

		$group_repeater->add_control(
			'plan_title',
			[
				'label' => esc_html__( 'Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'plan_description',
			[
				'label' => esc_html__( 'Description', 'iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'But I must explain to you how all this mistaken idea.', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'plan_monthly_price',
			[
				'label' => esc_html__( 'Monthly Price', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '9.99', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'plan_yearly_price',
			[
				'label' => esc_html__( 'Yearly Price', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '9.99', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'plan_currency_symbol',
			[
				'label' => __( 'Currency Symbol', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'iteck_plg' ),
					'&#36;' => '&#36; ' . _x( 'Dollar', 'iteck_plg' ),
					'&#128;' => '&#128; ' . _x( 'Euro', 'iteck_plg' ),
					'&#3647;' => '&#3647; ' . _x( 'Baht', 'iteck_plg' ),
					'&#8355;' => '&#8355; ' . _x( 'Franc', 'iteck_plg' ),
					'&fnof;' => '&fnof; ' . _x( 'Guilder', 'iteck_plg' ),
					'kr' => 'kr ' . _x( 'Krona', 'iteck_plg' ),
					'&#8356;' => '&#8356; ' . _x( 'Lira', 'iteck_plg' ),
					'&#8359' => '&#8359 ' . _x( 'Peseta', 'iteck_plg' ),
					'&#8369;' => '&#8369; ' . _x( 'Peso', 'iteck_plg' ),
					'&#163;' => '&#163; ' . _x( 'Pound Sterling', 'iteck_plg' ),
					'R$' => 'R$ ' . _x( 'Real', 'iteck_plg' ),
					'&#8381;' => '&#8381; ' . _x( 'Ruble', 'iteck_plg' ),
					'&#8360;' => '&#8360; ' . _x( 'Rupee', 'iteck_plg' ),
					'&#8377;' => '&#8377; ' . _x( 'Rupee (Indian)', 'iteck_plg' ),
					'&#8362;' => '&#8362; ' . _x( 'Shekel', 'iteck_plg' ),
					'&#165;' => '&#165; ' . _x( 'Yen/Yuan', 'iteck_plg' ),
					'&#8361;' => '&#8361; ' . _x( 'Won', 'iteck_plg' ),
					'custom' => __( 'Custom', 'iteck_plg' ),
				],
				'default' => '&#36;',
			]
		);

        $group_repeater->add_control(
			'plan_currency_symbol_custom',
			[
				'label' => __( 'Custom Symbol', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'plan_currency_symbol' => 'custom',
				],
			]
		);

		$group_repeater->add_control(
			'plan_period',
			[
				'label' => esc_html__( 'Period', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'In Month', 'iteck_plg' ),
				'label_block' => true,
			]
		);
		
		$group_repeater->add_control(
			'plan_feature',
			[
				'label' => esc_html__( 'Feature', 'iteck_plg' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( ' Marketing strategy', 'iteck_plg' ),
				'label_block' => true,
			]
		);
		
		$group_repeater->add_control(
			'plan_box_image_icon',
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

		$group_repeater->add_control(
			'group_plan_selected_icon',
			[
				'label' => esc_html__( 'Plan Icon', 'iteck_plg' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition'	=> [
					'plan_box_image_icon'	=> 'icon'
				]
			]
		);

		$group_repeater->add_control(
			'group_plan_image',
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
					'plan_box_image_icon'	=> 'image'
				]
			]
		);

		$group_repeater->add_control(
			'plan_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get Started Now', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
            'plan_btn_link',
            [
                'label' => __( 'Button Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

		$group_repeater->add_control(
			'plan_recommended',
			[
				'label' => __( 'Recommended', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);

		$group_repeater->add_control(
			'plan_recommended_text',
			[
				'label' => esc_html__( 'Recommended Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Recommended', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'plan_recommended' => 'yes',
				]
			]
		);

		$group_repeater->add_control(
			'plan_recommended_position',
			[
				'label' => __( 'Recommended Position', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => __( 'Top', 'iteck_plg' ),
					'bottom' => __( 'Bottom', 'iteck_plg' ),
				],
				'default' => 'h2',
				'condition' => [
					'plan_recommended' => 'yes',
				]
			]
		);

        $this->add_control(
			'plans_repeater',
			[
				'label' => esc_html__( 'Features', 'iteck_plg' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $group_repeater->get_controls(),
				'default' => [
					[
						'plan_feature' => esc_html__( 'Marketing strategy', 'iteck_plg' ),
					],
					[
						'plan_feature' => esc_html__( 'Competitive analysis', 'iteck_plg' ),
					],
					[
						'plan_feature' => esc_html__( 'Monthly management', 'iteck_plg' ),
					],
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'sigle_plan_content',
			[
				'label' => __('Pricing Content', 'iteck_plg'),
				'condition' => [
					'plans_mode!' => 'yes',
				]
			]
		);

        $this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'But I must explain to you how all this mistaken idea.', 'iteck_plg' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'price_position',
			[
				'label' => esc_html__( 'Price Position', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'iteck_plg' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'iteck_plg' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'iteck_plg' ),
						'icon' => 'eicon-h-align-right',
					],
				],
			]
		);

        $this->add_control(
			'title_description',
			[
				'label' => esc_html__( 'Title Description', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
					'price_position!' => 'right'
				]
			]
		);

        $this->add_control(
			'hightlight_text',
			[
				'label' => esc_html__( 'Hightlight Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
					'price_position!' => 'right'
				]
			]
		);

        $this->add_control(
			'price',
			[
				'label' => esc_html__( 'Price', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '9.99', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'currency_symbol',
			[
				'label' => __( 'Currency Symbol', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'iteck_plg' ),
					'&#36;' => '&#36; ' . _x( 'Dollar', 'iteck_plg' ),
					'&#128;' => '&#128; ' . _x( 'Euro', 'iteck_plg' ),
					'&#3647;' => '&#3647; ' . _x( 'Baht', 'iteck_plg' ),
					'&#8355;' => '&#8355; ' . _x( 'Franc', 'iteck_plg' ),
					'&fnof;' => '&fnof; ' . _x( 'Guilder', 'iteck_plg' ),
					'kr' => 'kr ' . _x( 'Krona', 'iteck_plg' ),
					'&#8356;' => '&#8356; ' . _x( 'Lira', 'iteck_plg' ),
					'&#8359' => '&#8359 ' . _x( 'Peseta', 'iteck_plg' ),
					'&#8369;' => '&#8369; ' . _x( 'Peso', 'iteck_plg' ),
					'&#163;' => '&#163; ' . _x( 'Pound Sterling', 'iteck_plg' ),
					'R$' => 'R$ ' . _x( 'Real', 'iteck_plg' ),
					'&#8381;' => '&#8381; ' . _x( 'Ruble', 'iteck_plg' ),
					'&#8360;' => '&#8360; ' . _x( 'Rupee', 'iteck_plg' ),
					'&#8377;' => '&#8377; ' . _x( 'Rupee (Indian)', 'iteck_plg' ),
					'&#8362;' => '&#8362; ' . _x( 'Shekel', 'iteck_plg' ),
					'&#165;' => '&#165; ' . _x( 'Yen/Yuan', 'iteck_plg' ),
					'&#8361;' => '&#8361; ' . _x( 'Won', 'iteck_plg' ),
					'custom' => __( 'Custom', 'iteck_plg' ),
				],
				'default' => '&#36;',
			]
		);

        $this->add_control(
			'currency_symbol_custom',
			[
				'label' => __( 'Custom Symbol', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'currency_symbol' => 'custom',
				],
			]
		);

		$this->add_control(
			'period',
			[
				'label' => esc_html__( 'Period', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'In Month', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'feature',
			[
				'label' => esc_html__( 'Feature', 'iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( ' Marketing strategy', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $repeater->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'iteck_plg' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control(
			'available',
			[
				'label'         => __( 'Available', 'iteck_plg' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'iteck_plg' ),
				'label_off'     => __( 'No', 'iteck_plg' ),
				'return_value'  => 'yes',
				'default'  		=> 'yes',
			]
		);

		$repeater->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'iteck_plg' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'iteck_plg' ),
						'icon' => 'eicon-h-align-right',
					],
				],
			]
		);

        $this->add_control(
			'features_repeater',
			[
				'label' => esc_html__( 'Features', 'iteck_plg' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'feature' => esc_html__( 'Marketing strategy', 'iteck_plg' ),
					],
					[
						'feature' => esc_html__( 'Competitive analysis', 'iteck_plg' ),
					],
					[
						'feature' => esc_html__( 'Monthly management', 'iteck_plg' ),
					],
				],
				'title_field' => '{{{ feature }}}',
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
			'plan_selected_icon',
			[
				'label' => esc_html__( 'Plan Icon', 'iteck_plg' ),
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
			'plan_image',
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
				],
			]
		);

        $this->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Button Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get Started Now', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
            'btn_link',
            [
                'label' => __( 'Button Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

		$this->add_control(
			'btn_position',
			[
				'label' => esc_html__( 'Button Position', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'bottom',
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
			]
		);

		$this->add_control(
			'recommended',
			[
				'label' => __( 'Recommended', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'recommended_text',
			[
				'label' => esc_html__( 'Recommended Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Recommended', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'recommended' => 'yes',
				]
			]
		);

		$this->add_control(
			'recommended_position',
			[
				'label' => __( 'Recommended Position', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => __( 'Top', 'iteck_plg' ),
					'bottom' => __( 'Bottom', 'iteck_plg' ),
				],
				'default' => 'h2',
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'item_settings',
			[
				'label' => __( 'Item Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'label' => __('Button Icon Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .pricing-card',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .pricing-card',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'item_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_margin',
			[
				'label' => esc_html__('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .pricing-card',
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'item_head_settings',
			[
				'label' => __( 'Item Head Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_head_padding',
			[
				'label' => esc_html__('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card.style-1 .card-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_head_border',
				'selector' => '.iteck-pricing-plans .pricing-card.style-1 .card-head',
				'separator' => 'before',
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

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .title h4' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .pricing-title h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .title h4, {{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .pricing-title h2',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'description_settings',
			[
				'label' => __( 'Description Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Description Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .title .description' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .pricing-title p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .title .description, {{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .pricing-title p',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'price_settings',
			[
				'label' => __( 'Price Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Price Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .price h5, {{WRAPPER}} .iteck-pricing-plans h2.price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .price p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .price h5, {{WRAPPER}} .iteck-pricing-plans .price, {{WRAPPER}} .iteck-pricing-plans .pricing-card.style-1 .card-head h2.price, {{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .price p',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'period_settings',
			[
				'label' => __( 'Period Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'period_color',
			[
				'label' => esc_html__( 'Period Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .price small, {{WRAPPER}} .iteck-pricing-plans h2.price small' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'period_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .price small, {{WRAPPER}} .iteck-pricing-plans h2.price small',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'features_settings',
			[
				'label' => __( 'Features Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'features_min_height',
			[
				'label' => esc_html__( 'Features Min Height', 'iteck_plg' ),
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
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card.style-1 .card-body' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'features_align',
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
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .card-body li' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .pricing-info li' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'features_icon_color',
			[
				'label' => esc_html__( 'Features Icon Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .card-body li i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-pricing-plans .card-body li svg' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'features_icon_background',
				'label' => __('Features Icon Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .card-body li .features-icon, {{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .pricing-info li::before',
			]
		);

		$this->add_responsive_control(
			'features_icon_container_size',
			[
				'label' => esc_html__( 'Icon Container Size', 'iteck_plg' ),
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
					'{{WRAPPER}} .iteck-pricing-plans .card-body li .features-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-pricing-plans .card-body li .features-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'features_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'iteck_plg' ),
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
					'{{WRAPPER}} .iteck-pricing-plans .card-body li .features-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-pricing-plans .card-body li .features-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'features_color',
			[
				'label' => esc_html__( 'Features Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .card-body li small' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-pricing-plans .card-body ul li' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .pricing-info li' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'features_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .card-body li small, {{WRAPPER}} .iteck-pricing-plans .card-body ul li, {{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .pricing-info li',
			]
		);

		$this->add_control(
			'features_border_icon_radius',
			[
				'label' => esc_html__('Icon Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .card-body li .features-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'features_container_padding',
			[
				'label' => __('Container Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .card-body' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .pricing-info' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'features_padding',
			[
				'label' => __('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .card-body li' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .pricing-info li' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'features_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .card-body li' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .pricing-info li' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'features_border',
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .card-body li, {{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .pricing-info li',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'features_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .card-body li, {{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .pricing-info li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
			'plan_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'iteck_plg' ),
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
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card.style-1 .card-head.center-align .title .plan-title img.icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card.style-1 .card-head.center-align .title .plan-title i.icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'plan_color',
			[
				'label' => esc_html__( 'Plan Icon Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card.style-1 .card-head.center-align .title .plan-title i.icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'plan_icon_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card.style-1 .card-head.center-align .title .plan-title .icon' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slider_button_settings',
			[
				'label' => __( 'Button Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'plans_style' => 'with-slider'
				]
			]
		);

		$this->add_control(
			'slider_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .price p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'slider_button_text_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .price p',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'slider_button_background',
				'label' => __('Button Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .price',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'slider_button_background_hover',
				'label' => __('Button Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .pricing-card .price .price-btn',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_settings',
			[
				'label' => __( 'Section Title Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'plans_style' => 'with-slider'
				]
			]
		);

		$this->add_control(
			'section_title_color',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-head .section-head h3' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'section_title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-head .section-head h3',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_subtitle_settings',
			[
				'label' => __( 'Section Sub-Title Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'plans_style' => 'with-slider'
				]
			]
		);

		$this->add_control(
			'section_subtitle_color',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-head .section-head h6' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'section_subtitle_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-head .section-head h6',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tabs_settings',
			[
				'label' => __( 'Tabs Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'plans_style' => 'with-slider'
				]
			]
		);

		$this->add_control(
			'tabs_color',
			[
				'label' => esc_html__( 'Tabs Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-head .pricing-tabsHead .price-radios .form-check .form-check-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tabs_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-head .pricing-tabsHead .price-radios .form-check .form-check-label',
			]
		);

		$this->add_control(
			'tabs_active_color',
			[
				'label' => esc_html__( 'Tabs Active Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-head .pricing-tabsHead .price-radios .form-check .form-check-input:checked ~ .form-check-label' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rec_settings',
			[
				'label' => __( 'Recommended Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'plans_style' => 'with-slider'
				]
			]
		);

		$this->add_control(
			'rec_color',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .popular-head' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'rec_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .popular-head',
			]
		);

		$this->add_control(
			'rec_bg',
			[
				'label' => esc_html__( 'Background Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans.slider-pricing .pricing-body .popular-head' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_settings',
			[
				'label' => __( 'Button Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'plans_style' => 'no-slider'
				]
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
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card .btn span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'label' => __('Button Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .pricing-card .btn',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .pricing-card .btn',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card .btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card .btn:hover span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'label' => __('Button Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .pricing-card .btn:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border_hover',
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .pricing-card .btn:hover',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_radius_hover',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card .btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_text_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-pricing-plans .pricing-card .btn span',
			]
		);

		$this->add_control(
			'button_padding',
			[
				'label' => esc_html__('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card .btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card .btn' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => esc_html__( 'Button Size', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'100%' => esc_html__( 'Full Width', 'iteck_plg' ),
					'auto' => esc_html__( 'Auto', 'iteck_plg' ),
				],
				'default' => '100%',
				'selectors' => [
					'{{WRAPPER}} .iteck-pricing-plans .pricing-card .btn' => 'width: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if($settings['currency_symbol'] != 'custom'):

            $symbol =  $settings['currency_symbol'];

        else:

            $symbol =  $settings['currency_symbol_custom'];

        endif;

		$is_plan_SVG = isset($settings['plan_selected_icon']['library']) && 'svg' === $settings['plan_selected_icon']['library'];

		$counter = 1;

?>

        <div class="iteck-pricing-plans <?php if($settings['plans_style'] == 'with-slider') echo 'slider-pricing'; ?>">
			<?php if($settings['plans_mode'] == 'yes'): ?>
				<?php if($settings['plans_style'] == 'no-slider'): ?>
					<div class="toggle_switch d-flex align-items-center justify-content-center mb-20">
						<div class="form-check form-switch text-white p-0">
							<label class="form-check-label" for="monthly-input2"><small><?php esc_html_e($settings['monthly_plans_button_text']) ?></small></label>
							<input class="form-check-input float-none color-lightBlue" type="checkbox"
								id="monthly-input2" checked>
							<label class="form-check-label" for="monthly-input2"><small><?php esc_html_e($settings['yearly_plans_button_text']) ?></small></label>
						</div>
					</div>
				<?php else: ?>
					<div class="pricing-head wow fadeInUp">
						<div class="container">
							<div class="row align-items-center">
								<div class="col-lg-5">
									<div class="section-head style-8">
										<h6><?php esc_html_e($settings['plans_subtitle']) ?></h6>
										<h3 class="text-white"><?php esc_html_e($settings['plans_title']) ?></h3>
									</div>
								</div>
								<div class="col-lg-5">
									<div class="position-relative">
										<div id="slider-range-min"></div>
										<p class="users-number"> <input type="text" id="amount" readonly> <span><?php esc_html_e($settings['slider_text']) ?></span></p>    
									</div>
								</div>
								<div class="col-lg-2">
									<div class="pricing-tabsHead text-center">
										<div class="price-radios">
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="radio" name="inlineRadioOptions" id="monthly-input2"
													value="option1">
												<label class="form-check-label" for="monthly-input2">
												<?php esc_html_e($settings['monthly_plans_button_text']) ?>
												</label>
											</div>
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="radio" name="inlineRadioOptions" id="yearly-input2"
													value="option2" checked>
												<label class="form-check-label" for="yearly-input2">
												<?php esc_html_e($settings['yearly_plans_button_text']) ?>
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif;
			endif; ?>
			<?php if($settings['plans_mode'] != 'yes'): ?>
				<div class="pricing-card style-1">
					<div class="card-head <?php if($settings['price_position'] == 'center') echo 'center-align'; ?>">
						<div class="title">
							<h4 class="plan-title">
								<?php if($settings['recommended'] == 'yes' && $settings['recommended_position'] == 'top'): ?><span class="hint"><?php echo wp_kses_post($settings['recommended_text']); ?></span><?php endif; ?>
								<?php if($settings['box_image_icon'] == 'image'): ?>
									<span class="img-cont"><img src="<?php echo esc_url($settings['plan_image']['url']); ?>" class="icon" alt=""></span>
								<?php elseif($settings['box_image_icon'] == 'icon'): ?>
									<?php if ( $is_plan_SVG ) echo '<span class="icon">';  Icons_Manager::render_icon( $settings['plan_selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'icon' ] ); if ( $is_plan_SVG ) echo '</span>'; ?>
								<?php endif; ?>
								<?php echo wp_kses_post($settings['title']); if($settings['recommended'] == 'yes' && $settings['recommended_position'] == 'bottom') echo '<small>'. wp_kses_post($settings['recommended_text']) .'</small>'; ?>
							</h4>
							<?php if($settings['price_position'] != 'right' && !empty($settings['title_description'])): ?>
							<p class="title-description"><?php echo wp_kses_post($settings['title_description']); ?></p>
							<?php endif; ?>
							<?php if($settings['price_position'] != 'right'): ?>
								<h2 class="price"><?php echo wp_kses_post($symbol); ?><?php echo wp_kses_post($settings['price']); ?> <small> / <?php echo wp_kses_post($settings['period']); ?></small></h2>
							<?php endif; ?>
							<?php if($settings['price_position'] != 'right' && !empty($settings['hightlight_text'])): ?>
							<p class="hightLight"><?php echo wp_kses_post($settings['hightlight_text']); ?></p>
							<?php endif; ?>
							<small class="description"><?php echo wp_kses_post($settings['description']); ?></small>
						</div>
						<?php if($settings['price_position'] == 'right'): ?>
						<div class="price">
							<h5><?php echo wp_kses_post($symbol); ?><?php echo wp_kses_post($settings['price']); ?></h5>
							<small><?php echo wp_kses_post($settings['period']); ?></small>
						</div>
						<?php endif; ?>
					</div>
					<?php if($settings['btn_position'] == 'top'): ?>
					<a href="<?php echo esc_url($settings['btn_link']['url']); ?>" <?php if ( $settings['btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="btn">
						<span><?php echo wp_kses_post($settings['btn_text']); ?></span>
					</a>
					<?php endif; ?>
					<div class="card-body">
						<ul>
							<?php foreach ( $settings['features_repeater'] as $index => $item ) : $is_SVG = 'svg' === $item['selected_icon']['library']; ?>
								<li class="<?php if($item['available'] == 'yes') echo 'available'; if($item['icon_position'] == 'right') echo ' d-flex justify-content-between'; ?>">
									<?php if($item['icon_position'] == 'left'): ?> <span class="features-icon"><?php \Elementor\Icons_Manager::render_icon( $item['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?></span><?php endif; ?>
									<small><?php echo wp_kses_post($item['feature']); ?></small>
									<?php if($item['icon_position'] == 'right'): ?> <span class="features-icon r-icon"><?php \Elementor\Icons_Manager::render_icon( $item['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?></span><?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php if($settings['btn_position'] == 'bottom'): ?>
					<a href="<?php echo esc_url($settings['btn_link']['url']); ?>" <?php if ( $settings['btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="btn">
						<span><?php echo wp_kses_post($settings['btn_text']); ?></span>
					</a>
					<?php endif; ?>
				</div>
			<?php else: ?>
				<?php if($settings['plans_style'] == 'with-slider'): ?><div class="pricing-body"><div class="container"><?php endif; ?>
				<div class="row justify-content-center <?php if($settings['plans_style'] != 'with-slider') echo 'gx-0'; ?>">
					<?php foreach ( $settings['plans_repeater'] as $index => $item ) :  
                    
                    $is_plan_group_SVG = isset($item['group_plan_selected_icon']['library']) && 'svg' === $item['group_plan_selected_icon']['library']; 
					
					if(isset($item['plan_currency_symbol']) && $item['plan_currency_symbol'] != 'custom'):

						$plan_symbol =  $item['plan_currency_symbol'];
			
					else:
			
						$plan_symbol =  $item['plan_currency_symbol_custom'];
			
					endif;

					?>
					
						<div class="col-lg-<?php echo $settings['plans_columns']; if($settings['plans_style'] == 'with-slider') echo ' order-1 order-lg-0'; if($item['plan_recommended'] == 'yes') echo ' recommended-plan'; if($item['plan_recommended'] != 'yes') echo ' pt-60'; ?>">
							<?php if($settings['plans_style'] == 'no-slider'): ?>
								<div class="pricing-card group-plans style-1 <?php if($item['plan_recommended'] == 'yes') echo 'recommended '; if($counter == 1) echo 'first-item'; if($counter == 3) echo 'last-item'; ?>">
									<div class="card-head center-align">
										<div class="title">
											<h4 class="plan-title">
												<?php if($item['plan_recommended'] == 'yes' && $item['plan_recommended_position'] == 'top'): ?><span class="hint"><?php echo wp_kses_post($item['plan_recommended_text']); ?></span><?php endif; ?>
												<?php if($item['plan_box_image_icon'] == 'image'): ?>
													<span class="img-cont"><img src="<?php echo esc_url($item['group_plan_image']['url']); ?>" class="icon" alt=""></span>
												<?php elseif($item['plan_box_image_icon'] == 'icon'): ?>
													<?php if ( $is_plan_group_SVG ) echo '<span class="icon">';  Icons_Manager::render_icon( $item['group_plan_selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'icon' ] ); if ( $is_plan_group_SVG ) echo '</span>'; ?>
												<?php endif; ?>
												<?php echo wp_kses_post($item['plan_title']); if($item['plan_recommended'] == 'yes' && $item['plan_recommended_position'] == 'bottom') echo '<small>'. wp_kses_post($item['plan_recommended_text']) .'</small>'; ?>
											</h4>
											<div class="monthly_price">
												<h2 class="price"><?php echo wp_kses_post($plan_symbol); ?><?php echo wp_kses_post($item['plan_monthly_price']); ?> <small> / <?php echo wp_kses_post($item['plan_period']); ?></small></h2>
											</div>
											<div class="yearly_price show">
												<h2 class="price"><?php echo wp_kses_post($plan_symbol); ?><?php echo wp_kses_post($item['plan_yearly_price']); ?> <small> / <?php echo wp_kses_post($item['plan_period']); ?></small></h2>
											</div>
											<small class="description"><?php echo wp_kses_post($item['plan_description']); ?></small>
										</div>
									</div>
									<div class="card-body">
										<?php echo wp_kses_post($item['plan_feature']); ?>
									</div>
									<a href="<?php echo esc_url($item['plan_btn_link']['url']); ?>" <?php if ( $item['plan_btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="btn">
										<span><?php echo wp_kses_post($item['plan_btn_text']); ?></span>
									</a>
								</div>
							<?php else: ?>
								<?php if($item['plan_recommended'] == 'yes'): ?>
								<div class="popular-head">
                                    <p><?php echo wp_kses_post($item['plan_recommended_text']); ?></p>
                                </div>
								<?php endif; ?>
								<div class="pricing-card">
									<div class="pricing-title">
										<h2><?php echo wp_kses_post($item['plan_title']); ?></h2>
										<p><?php echo wp_kses_post($item['plan_description']); ?></p>
									</div>
									<div class="monthly_price">
										<div class="price">
											<p> <?php echo wp_kses_post($item['plan_period']); ?> <?php echo wp_kses_post($plan_symbol); ?><?php echo wp_kses_post($item['plan_monthly_price']); ?> </p>
											<a href="<?php echo esc_url($item['plan_btn_link']['url']); ?>" <?php if ( $item['plan_btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="price-btn"><?php echo wp_kses_post($item['plan_btn_text']); ?><i class="bi bi-arrow-right"></i> </a>
										</div>
									</div>
									<div class="yearly_price show">
										<div class="price">
											<p> <?php echo wp_kses_post($item['plan_period']); ?> <?php echo wp_kses_post($plan_symbol); ?><?php echo wp_kses_post($item['plan_yearly_price']); ?> </p>
											<a href="<?php echo esc_url($item['plan_btn_link']['url']); ?>" <?php if ( $item['plan_btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="price-btn"><?php echo wp_kses_post($item['plan_btn_text']); ?><i class="bi bi-arrow-right"></i> </a>
										</div>
									</div>
									<div class="pricing-info">
										<?php echo wp_kses_post($item['plan_feature']); ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					<?php $counter++; endforeach; ?>
				</div>
				<?php if($settings['plans_style'] == 'with-slider'): ?></div></div><?php endif; ?>
			<?php endif; ?>
        </div>

<?php

    }

    /**
     * Render Animated Headline widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.9.0
     * @access protected
     */
    protected function content_template()
    {
    }
}
