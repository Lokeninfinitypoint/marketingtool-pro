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


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Iteck_Pricing_table extends Widget_Base
{

    public function get_name()
    {
        return 'iteck-pricing-table';
    }
	
	//script depend
	public function get_script_depends() { return [ 'iteck-pricing']; }

    public function get_title()
    {
        return __('Iteck Pricing Table', 'iteck_plg');
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
			'section_content',
			[
				'label' => __('Pricing Settings', 'iteck_plg'),
			]
		);

        $this->add_control(
			'table_style',
			[
				'label' => __( 'Table Style', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' => __( 'Style 1', 'iteck_plg' ),
					'style-2' => __( 'Style 2', 'iteck_plg' ),
				],
				'default' => 'style-1',
			]
		);

        $this->add_control(
			'plans_count',
			[
				'label' => __( 'Plans Count', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( '1', 'iteck_plg' ),
                    '2' => __( '2', 'iteck_plg' ),
                    '3' => __( '3', 'iteck_plg' ),
				],
				'default' => '3',
			]
		);

		$this->add_control(
			'yearly_plans',
			[
				'label'         => __( 'Yearly Plans', 'iteck_plg' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'iteck_plg' ),
				'label_off'     => __( 'No', 'iteck_plg' ),
				'return_value'  => 'yes',
				'default'  		=> 'no',
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
			'btn_style',
			[
				'label' => __( 'Button Style', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' => __( 'Style 1', 'iteck_plg' ),
					'style-2' => __( 'Style 2', 'iteck_plg' ),
				],
				'default' => 'style-1',
			]
		);

        $this->add_control(
			'monthly_button_title',
			[
				'label' => esc_html__( 'Monthly Button Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Billed Monthly', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'yearly_plans' => 'yes'
				]
			]
		);

        $this->add_control(
			'monthly_button_discount',
			[
				'label' => esc_html__( 'Monthly Button Discount', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
					'yearly_plans' => 'yes'
				]
			]
		);

        $this->add_control(
			'yearly_button_title',
			[
				'label' => esc_html__( 'Yearly Button Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Billed yearly', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'yearly_plans' => 'yes'
				]
			]
		);

        $this->add_control(
			'yearly_button_discount',
			[
				'label' => esc_html__( 'Yearly Button Discount', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '-15%', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'yearly_plans' => 'yes'
				]
			]
		);

		$this->add_control(
            'image',
            [
                'label' => __( 'Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
				'url' => Utils::get_placeholder_image_src(),
				],
            ]
        );

        $this->add_control(
			'image_text',
			[
				'label' => esc_html__( 'Image Description Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
			'plan1_content',
			[
				'label' => __('Plan 1 Content', 'iteck_plg'),
			]
		);

        $this->add_control(
			'plan1_title',
			[
				'label' => esc_html__( 'Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan1_description',
			[
				'label' => esc_html__( 'Description', 'iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'But I must explain to you how all this mistaken idea.', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan1_monthly_price',
			[
				'label' => esc_html__( 'Monthly Price', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '9.99', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan1_yearly_price',
			[
				'label' => esc_html__( 'Yearly Price', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '9.99', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan1_currency_symbol',
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
			'plan1_currency_symbol_custom',
			[
				'label' => __( 'Custom Symbol', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'currency_symbol' => 'custom',
				],
			]
		);

		$this->add_control(
			'plan1_monthly_period',
			[
				'label' => esc_html__( 'Monthly Period', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'In Month', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan1_yearly_period',
			[
				'label' => esc_html__( 'Yearly Period', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'In Year', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan1_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get Started Now', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
            'plan1_btn_link',
            [
                'label' => __( 'Button Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

		$this->add_control(
			'plan1_recommended',
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
			'plan1_recommended_text',
			[
				'label' => esc_html__( 'Recommended Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Recommended', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'plan1_recommended' => 'yes',
				]
			]
		);

		$this->add_control(
            'plan1_recommended_image',
            [
                'label' => __( 'Recommended Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem.plan-1 .label' => 'background-image:url({{url}});'
				],
				'condition' => [
					'plan1_recommended' => 'yes',
				]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
			'plan2_content',
			[
				'label' => __('Plan 2 Content', 'iteck_plg'),
                'condition' => [
                    'plans_count' => ['2','3'],
                ],
			]
		);

        $this->add_control(
			'plan2_title',
			[
				'label' => esc_html__( 'Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan2_description',
			[
				'label' => esc_html__( 'Description', 'iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'But I must explain to you how all this mistaken idea.', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan2_monthly_price',
			[
				'label' => esc_html__( 'Monthly Price', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '9.99', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan2_yearly_price',
			[
				'label' => esc_html__( 'Yearly Price', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '9.99', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan2_currency_symbol',
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
			'plan2_currency_symbol_custom',
			[
				'label' => __( 'Custom Symbol', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'plan2_currency_symbol' => 'custom',
				],
			]
		);

		$this->add_control(
			'plan2_monthly_period',
			[
				'label' => esc_html__( 'Monthly Period', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'In Month', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan2_yearly_period',
			[
				'label' => esc_html__( 'Yearly Period', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'In Year', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan2_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get Started Now', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
            'plan2_btn_link',
            [
                'label' => __( 'Button Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

		$this->add_control(
			'plan2_recommended',
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
			'plan2_recommended_text',
			[
				'label' => esc_html__( 'Recommended Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Recommended', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'plan2_recommended' => 'yes',
				]
			]
		);

		$this->add_control(
            'plan2_recommended_image',
            [
                'label' => __( 'Recommended Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem.plan-2 .label' => 'background-image:url({{url}});'
				],
				'condition' => [
					'plan2_recommended' => 'yes',
				]
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
			'plan3_content',
			[
				'label' => __('Plan 3 Content', 'iteck_plg'),
                'condition' => [
                    'plans_count' => '3',
                ],
			]
		);

        $this->add_control(
			'plan3_title',
			[
				'label' => esc_html__( 'Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan3_description',
			[
				'label' => esc_html__( 'Description', 'iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'But I must explain to you how all this mistaken idea.', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan3_monthly_price',
			[
				'label' => esc_html__( 'Monthly Price', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '9.99', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan3_yearly_price',
			[
				'label' => esc_html__( 'Yearly Price', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '9.99', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan3_currency_symbol',
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
			'plan3_currency_symbol_custom',
			[
				'label' => __( 'Custom Symbol', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'plan3_currency_symbol' => 'custom',
				],
			]
		);

		$this->add_control(
			'plan3_monthly_period',
			[
				'label' => esc_html__( 'Monthly Period', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'In Month', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan3_yearly_period',
			[
				'label' => esc_html__( 'Yearly Period', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'In Year', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'plan3_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get Started Now', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
            'plan3_btn_link',
            [
                'label' => __( 'Button Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

		$this->add_control(
			'plan3_recommended',
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
			'plan3_recommended_text',
			[
				'label' => esc_html__( 'Recommended Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Recommended', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'plan3_recommended' => 'yes',
				]
			]
		);

		$this->add_control(
            'plan3_recommended_image',
            [
                'label' => __( 'Recommended Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem.plan-3 .label' => 'background-image:url({{url}});'
				],
				'condition' => [
					'plan3_recommended' => 'yes',
				]
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
			'features_content',
			[
				'label' => __('Features', 'iteck_plg'),
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
			'plan1_icon_or_text',
			[
				'label' => __( 'Plan 1 Available Type', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'text' => [
						'title' => __( 'Text', 'iteck_plg' ),
						'icon' => 'fa fa-heading',
					],
					'icon' => [
						'title' => __( 'Icon', 'iteck_plg' ),
						'icon' => 'fa fa-image',
					],
					'none' => [
						'title' => __( 'none', 'iteck_plg' ),
						'icon' => 'fa fa-ban',
					],
				],
				'default' => 'none',
			]
		);

		$repeater->add_control(
			'plan1_feature_text',
			[
				'label' => esc_html__( 'Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( ' Marketing strategy', 'iteck_plg' ),
				'label_block' => true,
                'condition' => [
                    'plan1_icon_or_text' => 'text'
                ],
			]
		);

        $repeater->add_control(
			'plan1_selected_icon',
			[
				'label' => esc_html__( 'Icon', 'iteck_plg' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
                'condition' => [
                    'plan1_icon_or_text' => 'icon'
                ],
			]
		);

        $repeater->add_control(
			'plan2_icon_or_text',
			[
				'label' => __( 'Plan 2 Available Type', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'text' => [
						'title' => __( 'Text', 'iteck_plg' ),
						'icon' => 'fa fa-heading',
					],
					'icon' => [
						'title' => __( 'Icon', 'iteck_plg' ),
						'icon' => 'fa fa-image',
					],
					'none' => [
						'title' => __( 'none', 'iteck_plg' ),
						'icon' => 'fa fa-ban',
					],
				],
				'default' => 'none',
			]
		);

		$repeater->add_control(
			'plan2_feature_text',
			[
				'label' => esc_html__( 'Feature', 'iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( ' Marketing strategy', 'iteck_plg' ),
				'label_block' => true,
                'condition' => [
                    'plan2_icon_or_text' => 'text'
                ],
			]
		);

        $repeater->add_control(
			'plan2_selected_icon',
			[
				'label' => esc_html__( 'Icon', 'iteck_plg' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
                'condition' => [
                    'plan2_icon_or_text' => 'icon'
                ],
			]
		);

        $repeater->add_control(
			'plan3_icon_or_text',
			[
				'label' => __( 'Plan 3 Available Type', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'text' => [
						'title' => __( 'Text', 'iteck_plg' ),
						'icon' => 'fa fa-heading',
					],
					'icon' => [
						'title' => __( 'Icon', 'iteck_plg' ),
						'icon' => 'fa fa-image',
					],
					'none' => [
						'title' => __( 'none', 'iteck_plg' ),
						'icon' => 'fa fa-ban',
					],
				],
				'default' => 'none',
			]
		);

		$repeater->add_control(
			'plan3_feature_text',
			[
				'label' => esc_html__( 'Feature', 'iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( ' Marketing strategy', 'iteck_plg' ),
				'label_block' => true,
                'condition' => [
                    'plan3_icon_or_text' => 'text'
                ],
			]
		);

        $repeater->add_control(
			'plan3_selected_icon',
			[
				'label' => esc_html__( 'Icon', 'iteck_plg' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
                'condition' => [
                    'plan3_icon_or_text' => 'icon'
                ],
			]
		);

		$repeater->add_control(
			'popup_info',
			[
				'label' => esc_html__('Popup Info'),
				'type' => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'iteck_plg' ),
				'label_off'     => __( 'No', 'iteck_plg' ),
				'return_value'  => 'yes',
				'default'  		=> 'no',
			]
		);

		

		$repeater->add_control(
			'popup_info_title',
			[
				'label' => esc_html__( 'Popup Info Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Free Google Analysis', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'popup_info' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'popup_info_text',
			[
				'label' => esc_html__( 'Popup Info Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. In voluptatum, eos ad quia sint recusandae.', 'iteck_plg' ),
				'label_block' => true,
				'condition' => [
					'popup_info' => 'yes',
				]
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

        $this->end_controls_section();

		$this->start_controls_section(
			'tabel_head_settings',
			[
				'label' => __( 'Table Head Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tabel_head_content_position',
			[
				'label' => esc_html__( 'Content Position', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
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
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'tabel_head_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tabel_head_padding',
			[
				'label' => esc_html__('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem h6' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem h6',
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
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem small' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem small',
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
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem h2' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem h2',
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
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem h2 span' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem h2 span',
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

		$this->add_control(
			'features_color',
			[
				'label' => esc_html__( 'Features Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-body .price-bodyItems .price-item span' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .iteck-price-table .content .price-body .price-bodyItems .price-item span',
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
					'{{WRAPPER}} .iteck-price-table .card-body li i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-price-table .card-body li svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'features_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .card-body li i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-price-table .card-body li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_settings',
			[
				'label' => __( 'Button Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Period Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .btn span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_text_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-price-table .btn span',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'label' => __('Button Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-price-table .btn',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .iteck-price-table .btn',
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
					'{{WRAPPER}} .iteck-price-table .btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_padding',
			[
				'label' => esc_html__('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_margin',
			[
				'label' => esc_html__('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'table_icon_settings',
			[
				'label' => __( 'Table Icon','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'table_icon_width',
			[
				'label' => esc_html__( 'Image Width', 'iteck_plg' ),
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
					'{{WRAPPER}} .iteck-price-table .price-headTitle img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'table_icon_height',
			[
				'label' => esc_html__( 'Image Height', 'iteck_plg' ),
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
					'{{WRAPPER}} .iteck-price-table .price-headTitle img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'table_icon_margin',
			[
				'label' => esc_html__('Image Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .price-headTitle img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'table_icon_container_padding',
			[
				'label' => esc_html__('Image Container Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .price-headTitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'recommended_plan_settings',
			[
				'label' => __( 'Recommended Plan Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'recommended_plan_color',
			[
				'label' => esc_html__( 'recommended Plan Background', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem.recommended' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .iteck-price-table .content .price-body .price-bodyItems .price-item.recommended' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .iteck-price-table .content .price-body .price-bodyItems .price-item.recommended span' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .iteck-price-table .content .price-foot .price-footItem.recommended' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'recommended_plan_title_settings',
			[
				'label' => __( 'Recommended Plan Title Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'recommended_title_color',
			[
				'label' => esc_html__( 'Title Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem.recommended h6' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'recommended_title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem.recommended h6',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'recommended_plan_price_settings',
			[
				'label' => __( 'Recommended Plan Price Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'recommended_price_color',
			[
				'label' => esc_html__( 'Price Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem.recommended h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'recommended_price_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem.recommended h2',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'recommended_plan_period_settings',
			[
				'label' => __( 'Recommended Plan Period Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'recommended_period_color',
			[
				'label' => esc_html__( 'Period Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem.recommended h2 span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'recommended_period_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-price-table .content .price-head .price-headItem.recommended h2 span',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'recommended_plan_features_settings',
			[
				'label' => __( 'Recommended Plan Features Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'recommended_features_color',
			[
				'label' => esc_html__( 'Features Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-body .price-bodyItems .price-item.recommended span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'recommended_features_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-price-table .content .price-body .price-bodyItems .price-item.recommended span',
			]
		);

		$this->add_control(
			'recommended_features_border_color',
			[
				'label' => esc_html__( 'Features Border Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-price-table .content .price-body .price-bodyItems .price-item.recommended span' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        
        $symbols = [];

        $is_SVG =[];

        for($i = 1; $i <= 3; $i++):

            if($settings['plan'.$i.'_currency_symbol'] != 'custom'):

                $symbols['plan'.$i.''] = $settings['plan'.$i.'_currency_symbol'];

            else:

                $symbols['plan'.$i.''] = $settings['plan'.$i.'_currency_symbol_custom'];

            endif;

        endfor;

?>

        <div class="iteck-price-table <?php echo esc_attr($settings['table_style']) ?>">
			<?php if($settings['yearly_plans'] == 'yes'): ?>
			<div class="pricing-tabsHead text-center">
				<div class="price-radios">
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="inlineRadioOptions" id="monthly-input"
							value="option1">
						<label class="form-check-label" for="monthly-input">
						<?php echo wp_kses_post($settings['monthly_button_title']); ?>
						<?php if(!empty($settings['monthly_button_discount'])): ?> <small class="alert-danger text-danger rounded-pill ms-1"><?php echo wp_kses_post($settings['monthly_button_discount']); ?></small><?php endif; ?>
						</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="inlineRadioOptions" id="yearly-input"
							value="option2" checked>
						<label class="form-check-label" for="yearly-input">
							<?php echo wp_kses_post($settings['yearly_button_title']); ?>
							<?php if(!empty($settings['yearly_button_discount'])): ?> <small class="alert-danger text-danger rounded-pill ms-1"><?php echo wp_kses_post($settings['yearly_button_discount']); ?></small><?php endif; ?>
						</label>
					</div>
				</div>
			</div>
			<?php endif; ?>
            <div class="table-responsive d-none d-lg-block">
                <div class="content">
                    <div class="price-head">
                        <div class="price-headTitle">
                            <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="">
							<p><?php echo wp_kses_post($settings['image_text']); ?></p>
                        </div>

                        <div class="price-headItem plan-1 <?php if($settings['plan1_recommended'] == 'yes') echo 'recommended'; ?>">
                            <h6><?php echo wp_kses_post($settings['plan1_title']); ?></h6>
                            <h2 class="monthly_price"><?php echo wp_kses_post($symbols['plan1']).wp_kses_post($settings['plan1_monthly_price']); ?> <span><?php echo wp_kses_post($settings['plan1_monthly_period']); ?></span></h2>
                            <h2 class="yearly_price"><?php echo wp_kses_post($symbols['plan1']).wp_kses_post($settings['plan1_yearly_price']); ?> <span><?php echo wp_kses_post($settings['plan1_yearly_period']); ?></span></h2>
                            <small><?php echo wp_kses_post($settings['plan1_description']); ?></small>
							<?php if($settings['plan1_recommended'] == 'yes'): ?>
								<div class="label">
									<?php echo wp_kses_post($settings['plan1_recommended_text']); ?>
								</div>
							<?php endif; ?>
							<?php if($settings['btn_position'] == 'top'): ?>
								<a href="<?php echo esc_url($settings['plan1_btn_link']['url']) ?>" <?php if ( $settings['plan1_btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="btn <?php if($settings['btn_style'] == 'style-1') echo 'btn-3Dbutn'; else echo 'btn-arrow'; ?>">
									<span><?php echo wp_kses_post($settings['plan1_btn_text']); ?><?php if($settings['btn_style'] != 'style-1') echo '<i class="fas fa-long-arrow-alt-right"></i>'; ?></span>
								</a>
							<?php endif; ?>
                        </div>

                        <?php if($settings['plans_count'] != '1'): ?>
                        <div class="price-headItem plan-2 <?php if($settings['plan2_recommended'] == 'yes') echo 'recommended'; ?>">
                            <h6><?php echo wp_kses_post($settings['plan2_title']); ?></h6>
                            <h2 class="monthly_price"><?php echo wp_kses_post($symbols['plan2']).wp_kses_post($settings['plan2_monthly_price']); ?> <span><?php echo wp_kses_post($settings['plan2_monthly_period']); ?></span></h2>
                            <h2 class="yearly_price"><?php echo wp_kses_post($symbols['plan2']).wp_kses_post($settings['plan2_yearly_price']); ?> <span><?php echo wp_kses_post($settings['plan2_yearly_period']); ?></span></h2>
                            <small><?php echo wp_kses_post($settings['plan2_description']); ?></small>
							<?php if($settings['plan2_recommended'] == 'yes'): ?>
								<div class="label">
									<?php echo wp_kses_post($settings['plan2_recommended_text']); ?>
								</div>
							<?php endif; ?>
							<?php if($settings['btn_position'] == 'top'): ?>
								<a href="<?php echo esc_url($settings['plan2_btn_link']['url']) ?>" <?php if ( $settings['plan2_btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="btn <?php if($settings['btn_style'] == 'style-1') echo 'btn-3Dbutn'; else echo 'btn-arrow'; ?>">
									<span><?php echo wp_kses_post($settings['plan1_btn_text']); ?><?php if($settings['btn_style'] != 'style-1') echo '<i class="fas fa-long-arrow-alt-right"></i>'; ?></span>
								</a>
							<?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if($settings['plans_count'] == '3'): ?>
                        <div class="price-headItem plan-3 <?php if($settings['plan3_recommended'] == 'yes') echo 'recommended'; ?>">
                            <h6><?php echo wp_kses_post($settings['plan3_title']); ?></h6>
                            <h2 class="monthly_price"><?php echo wp_kses_post($symbols['plan3']).wp_kses_post($settings['plan3_monthly_price']); ?> <span><?php echo wp_kses_post($settings['plan3_monthly_period']); ?></span></h2>
                            <h2 class="yearly_price"><?php echo wp_kses_post($symbols['plan3']).wp_kses_post($settings['plan3_yearly_price']); ?> <span><?php echo wp_kses_post($settings['plan3_yearly_period']); ?></span></h2>
                            <small><?php echo wp_kses_post($settings['plan3_description']); ?></small>
							<?php if($settings['plan3_recommended'] == 'yes'): ?>
								<div class="label">
									<?php echo wp_kses_post($settings['plan3_recommended_text']); ?>
								</div>
							<?php endif; ?>
							<?php if($settings['btn_position'] == 'top'): ?>
								<a href="<?php echo esc_url($settings['plan3_btn_link']['url']) ?>" <?php if ( $settings['plan3_btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="btn <?php if($settings['btn_style'] == 'style-1') echo 'btn-3Dbutn'; else echo 'btn-arrow'; ?>">
									<span><?php echo wp_kses_post($settings['plan1_btn_text']); ?><?php if($settings['btn_style'] != 'style-1') echo '<i class="fas fa-long-arrow-alt-right"></i>'; ?></span>
								</a>
							<?php endif; ?>
                        </div>
                        <?php endif; ?>

                    </div>

                    <div class="price-body">

                        <?php foreach ( $settings['features_repeater'] as $index => $item ) : 
                            $plan1_is_SVG = isset($item['plan1_selected_icon']['library']) && 'svg' === $item['plan1_selected_icon']['library'];
                            $plan2_is_SVG = isset($item['plan2_selected_icon']['library']) && 'svg' === $item['plan2_selected_icon']['library'];
                            $plan3_is_SVG = isset($item['plan3_selected_icon']['library']) && 'svg' === $item['plan3_selected_icon']['library'];
                            ?>
                            <div class="price-bodyItems">
                                <div class="price-bodyTitle">
                                    <span><?php echo wp_kses_post($item['feature']); ?></span>
									<?php if($item['popup_info'] == 'yes'): ?>
									<div class="pop-info">
                                        <i class="fas fa-info-circle bttn-info"></i>
                                        <div class="hidden_content">
                                            <div class="title"><?php echo wp_kses_post($item['popup_info_title']); ?></div>
                                            <small class="small color-777 lh-5"><?php echo wp_kses_post($item['popup_info_text']); ?></small>
                                        </div>
                                    </div>
									<?php endif; ?>
                                </div>

                                <div class="price-item <?php if($settings['plan1_recommended'] == 'yes') echo 'recommended'; ?>">
                                    <?php if($item['plan1_icon_or_text'] == 'text'): echo '<span>'. wp_kses_post($item['plan1_feature_text']) .'</span>'; elseif($item['plan1_icon_or_text'] == 'icon'): if ( $plan1_is_SVG ): echo '<span class="features-icon">'; endif;  \Elementor\Icons_Manager::render_icon( $item['plan1_selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'features-icon' ] ); if ( $plan1_is_SVG ): echo '</span>'; endif; endif; ?>
                                </div>

								<?php if($settings['plans_count'] != '1'): ?>
                                <div class="price-item <?php if($settings['plan2_recommended'] == 'yes') echo 'recommended'; ?>">
                                	<?php if($item['plan2_icon_or_text'] == 'text'): echo '<span>'. wp_kses_post($item['plan2_feature_text']) .'</span>'; elseif($item['plan2_icon_or_text'] == 'icon'): if ( $plan2_is_SVG ): echo '<span class="features-icon">'; endif;  \Elementor\Icons_Manager::render_icon( $item['plan2_selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'features-icon' ] ); if ( $plan2_is_SVG ): echo '</span>'; endif; endif; ?>
                                </div>
								<?php endif; ?>

								<?php if($settings['plans_count'] == '3'): ?>
                                <div class="price-item <?php if($settings['plan3_recommended'] == 'yes') echo 'recommended'; ?>">
                                	<?php if($item['plan3_icon_or_text'] == 'text'): echo '<span>'. wp_kses_post($item['plan3_feature_text']) .'</span>'; elseif($item['plan3_icon_or_text'] == 'icon'): if ( $plan3_is_SVG ): echo '<span class="features-icon">'; endif;  \Elementor\Icons_Manager::render_icon( $item['plan3_selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'features-icon' ] ); if ( $plan3_is_SVG ): echo '</span>'; endif; endif; ?>
                                </div>
								<?php endif; ?>
                            </div>
                        <?php endforeach; ?>

                    </div>
					<?php if($settings['btn_position'] == 'bottom'): ?>
						<div class="price-foot">

							<div class="price-footTitle"></div>

							<div class="price-footItem <?php if($settings['plan1_recommended'] == 'yes') echo 'recommended'; ?>">
								<a href="<?php echo esc_url($settings['plan1_btn_link']['url']) ?>" <?php if ( $settings['plan1_btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="btn btn-3Dbutn">
									<span><?php echo wp_kses_post($settings['plan1_btn_text']); ?></span>
								</a>
							</div>

							<?php if($settings['plans_count'] != '1'): ?>
							<div class="price-footItem <?php if($settings['plan2_recommended'] == 'yes') echo 'recommended'; ?>">
								<a href="<?php echo esc_url($settings['plan2_btn_link']['url']) ?>" <?php if ( $settings['plan2_btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="btn btn-3Dbutn">
									<span><?php echo wp_kses_post($settings['plan2_btn_text']); ?></span>
								</a>
							</div>
							<?php endif; ?>

							<?php if($settings['plans_count'] == '3'): ?>
							<div class="price-footItem <?php if($settings['plan3_recommended'] == 'yes') echo 'recommended'; ?>">
								<a href="<?php echo esc_url($settings['plan3_btn_link']['url']) ?>" <?php if ( $settings['plan3_btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="btn btn-3Dbutn">
									<span><?php echo wp_kses_post($settings['plan3_btn_text']); ?></span>
								</a>
							</div>
							<?php endif; ?>

						</div>
					<?php endif; ?>

                </div>
            </div>
			<div class="mob-table d-block d-lg-none mt-5">
				<div class="mob-table-title">
					<div class="row gx-0">
						<div class="col-4">
							<div class="title-item">
								<h6><?php echo wp_kses_post($settings['plan1_title']); ?></h6>
								<h4 class="monthly_price"><?php echo wp_kses_post($symbols['plan1']).wp_kses_post($settings['plan1_monthly_price']); ?> <span><?php echo wp_kses_post($settings['plan1_monthly_period']); ?></span></h4>
                            	<h4 class="yearly_price"><?php echo wp_kses_post($symbols['plan1']).wp_kses_post($settings['plan1_yearly_price']); ?> <span><?php echo wp_kses_post($settings['plan1_yearly_period']); ?></span></h4>
								<p><?php echo wp_kses_post($settings['plan1_description']); ?></p>
							</div>
						</div>
						<?php if($settings['plans_count'] != '1'): ?>
						<div class="col-4">
							<div class="title-item">
								<h6><?php echo wp_kses_post($settings['plan2_title']); ?></h6>
								<h4 class="monthly_price"><?php echo wp_kses_post($symbols['plan2']).wp_kses_post($settings['plan2_monthly_price']); ?> <span><?php echo wp_kses_post($settings['plan2_monthly_period']); ?></span></h4>
                            	<h4 class="yearly_price"><?php echo wp_kses_post($symbols['plan2']).wp_kses_post($settings['plan2_yearly_price']); ?> <span><?php echo wp_kses_post($settings['plan2_yearly_period']); ?></span></h4>
								<p><?php echo wp_kses_post($settings['plan2_description']); ?></p>
							</div>
						</div>
						<?php endif; ?>
						<?php if($settings['plans_count'] == '3'): ?>
						<div class="col-4">
							<div class="title-item">
								<h6><?php echo wp_kses_post($settings['plan3_title']); ?></h6>
								<h4 class="monthly_price"><?php echo wp_kses_post($symbols['plan3']).wp_kses_post($settings['plan3_monthly_price']); ?> <span><?php echo wp_kses_post($settings['plan3_monthly_period']); ?></span></h4>
                            	<h4 class="yearly_price"><?php echo wp_kses_post($symbols['plan3']).wp_kses_post($settings['plan3_yearly_price']); ?> <span><?php echo wp_kses_post($settings['plan3_yearly_period']); ?></span></h4>
								<p><?php echo wp_kses_post($settings['plan3_description']); ?></p>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="mob-table-body">
					<?php foreach ( $settings['features_repeater'] as $index => $item ) : 
						$plan1_is_SVG = 'svg' === $item['plan1_selected_icon']['library'];
						$plan2_is_SVG = 'svg' === $item['plan2_selected_icon']['library'];
						$plan3_is_SVG = 'svg' === $item['plan3_selected_icon']['library'];
						?>
						<div class="table-row">
							<h5><?php echo wp_kses_post($item['feature']); ?></h5>
							<div class="row gx-0">
								<div class="col-4">
									<div class="price-item">
									<?php if($item['plan1_icon_or_text'] == 'text'): echo '<span>'. wp_kses_post($item['plan1_feature_text']) .'</span>'; elseif($item['plan1_icon_or_text'] == 'icon'): if ( $plan1_is_SVG ): echo '<span class="features-icon">'; endif;  \Elementor\Icons_Manager::render_icon( $item['plan1_selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'features-icon' ] ); if ( $plan1_is_SVG ): echo '</span>'; endif; endif; ?>
									</div>
								</div>
								<?php if($settings['plans_count'] != '1'): ?>
								<div class="col-4">
									<div class="price-item">
									<?php if($item['plan2_icon_or_text'] == 'text'): echo '<span>'. wp_kses_post($item['plan2_feature_text']) .'</span>'; elseif($item['plan2_icon_or_text'] == 'icon'): if ( $plan2_is_SVG ): echo '<span class="features-icon">'; endif;  \Elementor\Icons_Manager::render_icon( $item['plan2_selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'features-icon' ] ); if ( $plan2_is_SVG ): echo '</span>'; endif; endif; ?>
									</div>
								</div>
								<?php endif; ?>
								<?php if($settings['plans_count'] == '3'): ?>
								<div class="col-4">
									<div class="price-item">
									<?php if($item['plan3_icon_or_text'] == 'text'): echo '<span>'. wp_kses_post($item['plan3_feature_text']) .'</span>'; elseif($item['plan3_icon_or_text'] == 'icon'): if ( $plan3_is_SVG ): echo '<span class="features-icon">'; endif;  \Elementor\Icons_Manager::render_icon( $item['plan3_selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'features-icon' ] ); if ( $plan3_is_SVG ): echo '</span>'; endif; endif; ?>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="mob-table-foot">
					<div class="row gx-0">
						<div class="col-4">
							<div class="title-item">
								<h6><?php echo wp_kses_post($settings['plan1_title']); ?></h6>
								<a href="<?php echo esc_url($settings['plan1_btn_link']['url']) ?>" <?php if ( $settings['plan1_btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="btn btn-3Dbutn">
									<span><?php echo wp_kses_post($settings['plan1_btn_text']); ?></span>
								</a>
							</div>
						</div>
						<?php if($settings['plans_count'] != '1'): ?>
						<div class="col-4">
							<div class="title-item">
								<h6><?php echo wp_kses_post($settings['plan3_title']); ?></h6>
								<a href="<?php echo esc_url($settings['plan2_btn_link']['url']) ?>" <?php if ( $settings['plan2_btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="btn btn-3Dbutn">
									<span><?php echo wp_kses_post($settings['plan2_btn_text']); ?></span>
								</a>
							</div>
						</div>
						<?php endif; ?>
						<?php if($settings['plans_count'] == '3'): ?>
						<div class="col-4">
							<div class="title-item">
								<h6><?php echo wp_kses_post($settings['plan3_title']); ?></h6>
								<a href="<?php echo esc_url($settings['plan3_btn_link']['url']) ?>" <?php if ( $settings['plan3_btn_link']['is_external'] ) {echo'target="_blank"';} ?> class="btn btn-3Dbutn">
									<span><?php echo wp_kses_post($settings['plan3_btn_text']); ?></span>
								</a>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
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
