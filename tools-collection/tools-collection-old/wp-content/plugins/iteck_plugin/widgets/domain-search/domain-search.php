<?php

namespace IteckPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) exit; // Exit if accessed directly



/**
 * @since 1.0.0
 */
class Iteck_Domain_Search extends Widget_Base
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
        return 'iteck-domain-search';
    }
    //script depend
    public function get_script_depends()
    {
        return ['iteck-animation', 'jquery-swiper', 'iteck-swiper-slider-script'];
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
        return __('Iteck Domain Search', 'iteck_plg');
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
        return 'eicon-site-search';
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
                'label' => __('Domain Search Settings', 'iteck_plg'),
            ]
        );

        $this->add_control(
            'input_placeholder',
            [
                'label' => __('Seach Input Placeholder', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Search with any name',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Seach Button Text', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Search Now',
            ]
        );

        $this->add_control(
            'suffix_list',
            [
                'label' => __('Suffix List', 'iteck_plg'),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'suffix' => '.com',
                        'suffix_price' => '8.00',
                    ],
                    [
                        'suffix' => '.net',
                        'suffix_price' => '8.00',
                    ],

                ],
                'fields' => [
                    [
                        'name' => 'suffix',
                        'label' => __('Title', 'iteck_plg'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => __('.com', 'iteck_plg'),
                    ],
                    [
                        'name' => 'suffix_price',
                        'label' => __('Sub Title', 'iteck_plg'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => __('8.00', 'iteck_plg'),
                    ],
                    [
                        'name' => 'suffix_price_color',
                        'label' => esc_html__( 'Price Color', 'iteck_plg' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} {{CURRENT_ITEM}} span' => 'color: {{VALUE}};',
                        ],
                    ],
                    [
                        'name' => 'suffix_price_symbol',
                        'label' => __('Currency Symbol', 'iteck_plg'),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            '' => __('None', 'iteck_plg'),
                            '&#36;' => '&#36; ' . _x('Dollar', 'iteck_plg'),
                            '&#128;' => '&#128; ' . _x('Euro', 'iteck_plg'),
                            '&#3647;' => '&#3647; ' . _x('Baht', 'iteck_plg'),
                            '&#8355;' => '&#8355; ' . _x('Franc', 'iteck_plg'),
                            '&fnof;' => '&fnof; ' . _x('Guilder', 'iteck_plg'),
                            'kr' => 'kr ' . _x('Krona', 'iteck_plg'),
                            '&#8356;' => '&#8356; ' . _x('Lira', 'iteck_plg'),
                            '&#8359' => '&#8359 ' . _x('Peseta', 'iteck_plg'),
                            '&#8369;' => '&#8369; ' . _x('Peso', 'iteck_plg'),
                            '&#163;' => '&#163; ' . _x('Pound Sterling', 'iteck_plg'),
                            'R$' => 'R$ ' . _x('Real', 'iteck_plg'),
                            '&#8381;' => '&#8381; ' . _x('Ruble', 'iteck_plg'),
                            '&#8360;' => '&#8360; ' . _x('Rupee', 'iteck_plg'),
                            '&#8377;' => '&#8377; ' . _x('Rupee (Indian)', 'iteck_plg'),
                            '&#8362;' => '&#8362; ' . _x('Shekel', 'iteck_plg'),
                            '&#165;' => '&#165; ' . _x('Yen/Yuan', 'iteck_plg'),
                            '&#8361;' => '&#8361; ' . _x('Won', 'iteck_plg'),
                            'custom' => __('Custom', 'iteck_plg'),
                        ],
                        'default' => '&#36;',
                    ],
                    [
                        'name' => 'suffix_price_custom_symbol',
                        'label' => __('Custom Symbol', 'iteck_plg'),
                        'type' => Controls_Manager::TEXT,
                        'condition' => [
                            'suffix_price_symbol' => 'custom',
                        ],
                    ]
                ],
            ]
        );
        $this->add_control(
            'nav_prev',
            [
                'label' => __('Previous', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('Prev Slide', 'iteck_plg'),
                'condition' => [
                    'gallery_style' => array('1', '2', '3', '4')
                ],
            ]
        );
        $this->add_control(
            'nav_next',
            [
                'label' => __('Next', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('Next Slide', 'iteck_plg'),
                'condition' => [
                    'gallery_style' => array('1', '2', '3', '4')
                ],
            ]
        );
        $this->end_controls_section();

		$this->start_controls_section(
			'input_style',
			[
				'label' => __( 'Input Style', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'suffix_color',
			[
				'label' => __( 'Suffix color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-domain-search .domain-choose .form-group .form-select' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_margin',
			[
				'label' => __('Input Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-domain-search .domain-choose .form-group' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => __('Input Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-domain-search .domain-choose .form-group' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'input_border',
				'selector' => '{{WRAPPER}} .iteck-domain-search .domain-choose .form-group',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_border_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-domain-search .domain-choose .form-group' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'button_style',
			[
				'label' => __( 'Button Style', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'Button_typography',
				'label' => esc_html__( 'Additional Description typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-domain-search .domain-choose .butn',
			]
		);
		
		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Button Text color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-domain-search .domain-choose .butn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_bg',
				'label' => __('Button Background', 'iteck_plg'),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .iteck-domain-search .domain-choose .butn',
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => __('Button Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-domain-search .domain-choose .butn' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __('Button Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-domain-search .domain-choose .butn' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .iteck-domain-search .domain-choose .butn',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-domain-search .domain-choose .butn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        $settings = $this->get_settings();

?>

        <div class="iteck-domain-search">
            <div class="content">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <form method="POST" class="domain-choose">
                            <div class="form-group">
                                <span class="icon"> <i class="bi bi-search"></i> </span>
                                <input name="domain_name" type="text" placeholder="<?php echo esc_attr($settings['input_placeholder']); ?>">
                                <select name="suffix" id="suffix" class="form-select">
                                    <?php foreach ($settings['suffix_list'] as $index => $item) : ?>
                                        <option value="<?php echo esc_attr($item['suffix']); ?>"><?php echo wp_kses_post($item['suffix']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button name="check" class="butn bg-darkBlue2 border-0 rounded-3 text-white">
                                <span><?php echo wp_kses_post($settings['button_text']); ?></span>
                            </button>
                        </form>
                        <?php

                        if (isset($_POST['check'])) {

                            if (!empty($_POST['domain_name'])) {
                                $name_domain = trim($_POST['domain_name']) . $_POST['suffix'];

                                $godaddycheck = 'https://in.godaddy.com/domains/searchresults.aspx?checkAvail=1&tmskey=&domainToCheck=' . $name_domain . '';
                                $namecomcheck = 'https://www.name.com/domain/search/' . $name_domain . '';
                                $registercomcheck = 'http://www.register.com/domain/search/wizard.rcmx?searchDomainName=' . $name_domain . '&searchPath=Default&searchTlds=';
                                if (gethostbyname($name_domain) != $name_domain) {
                                    echo "<h3 class='result taken-domain'>". esc_html('Domain '.$name_domain.' has taken.', 'iteck_plg') ."</h3>";
                                } else {
                                    echo "<h3 class='result available-domain' >". esc_html('Domain '.$name_domain.' is available.', 'iteck_plg') ."</h3>";
                                }
                            } else {
                                echo "<h3 class='result enter-domian'>". esc_html('Error: Enter Domain Name.', 'iteck_plg') ."</h3>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="domain-names">
                    <div class="row justify-content-center wow fadeInUp">
                        <div class="col-lg-10">
                            <div class="row">
                                <?php foreach ($settings['suffix_list'] as $index => $item) :

                                    if (isset($settings['currency_symbol']) && $settings['currency_symbol'] != 'custom') :

                                        $symbol =  $item['suffix_price_symbol'];

                                    else :

                                        $symbol =  $item['suffix_price_custom_symbol'];

                                    endif; ?>
                                    <div class="col-lg-2 col-6">
                                        <div class="item elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>">
                                            <p> <i class="far fa-square"></i> <?php echo wp_kses_post($item['suffix']); ?><span>/<?php echo wp_kses_post($symbol . $item['suffix_price']) ?></span> </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
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
    protected function content_template()
    {
    }
}
