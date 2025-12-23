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
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Group_Control_Box_Shadow;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		 
/**
 * @since 1.0.0
 */
class Iteck_Testimonials_Slider extends Widget_Base {

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
		return 'iteck-testimonials-slider';
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
		return __( 'Iteck Testimonials Slider','iteck_plg' );
	}

	public function get_script_depends() { return [ 'jquery-swiper','wow','iteck-addons-custom-scripts']; }


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

    
     protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'iteck_plg' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'date',
            [
                'label' => __( 'Date', 'iteck_plg' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Jul 13, 2021', 'iteck_plg' ),
            ]
        );

        $repeater->add_control(
            'testimonial_text',
            [
                'label' => __( 'Testimonial Text', 'iteck_plg' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __( 'Phasellus eros lectus, ultricies ut nisl inleulemo rhoncusem...', 'iteck_plg' ),
            ]
        );

        $repeater->add_control(
            'user_name',
            [
                'label' => __( 'User Name', 'iteck_plg' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Tawam Rahman', 'iteck_plg' ),
            ]
        );

        $repeater->add_control(
            'user_role',
            [
                'label' => __( 'User Role', 'iteck_plg' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Support Consultant', 'iteck_plg' ),
            ]
        );

        $repeater->add_control(
            'user_avatar',
            [
                'label' => __( 'User Avatar', 'iteck_plg' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __( 'Top Icon', 'iteck_plg' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'label' => __( 'Testimonials', 'iteck_plg' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'date' => __( 'Jul 13, 2021', 'iteck_plg' ),
                        'testimonial_text' => __( 'Phasellus eros lectus, ultricies ut nisl inleulemo rhoncusem...', 'iteck_plg' ),
                        'user_name' => __( 'Tawam Rahman', 'iteck_plg' ),
                        'user_role' => __( 'Support Consultant', 'iteck_plg' ),
                    ],
                    [
                        'date' => __( 'Jul 13, 2021', 'iteck_plg' ),
                        'testimonial_text' => __( 'Phasellus eros lectus, ultricies ut nisl inleulemo rhoncusem...', 'iteck_plg' ),
                        'user_name' => __( 'Tawam Rahman', 'iteck_plg' ),
                        'user_role' => __( 'Support Consultant', 'iteck_plg' ),
                    ],
                ],
                'title_field' => '{{{ user_name }}}',
            ]
        );

        $this->add_control(
            'arrows_style',
            [
                'label' => __('Arrows Style', 'iteck_plg'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top' => 'Top',
                    'sides' => 'Sides'
                ],
                'default' => 'top',

            ]
        );

        $this->add_control(
            'slides_to_view',
            [
                'label' => __('Slides To View', 'iteck_plg'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,

            ]
        );

        $this->end_controls_section();

        

        $this->start_controls_section(
            'item_style',
            [
                'label' => __('Item Style', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_responsive_control(
			'item_height',
			[
				'label' => __( 'Item height', 'iteck_plg' ),
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
					'{{WRAPPER}} .iteck-testimonials-slider .testi-card' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .iteck-testimonials-slider .testi-card' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => __('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .iteck-testimonials-slider .testi-card' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'text_style',
            [
                'label' => __('Text Style', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'text_align',
            [
                'label' => esc_html__('Alignment', 'iteck_plg'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'iteck_plg'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'iteck_plg'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'iteck_plg'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'iteck_plg'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonials-slider .testi-card .main-txt' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => esc_html__( 'typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-testimonials-slider .testi-card .main-txt',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'date_style',
            [
                'label' => __('Date Style', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'date_align',
            [
                'label' => esc_html__('Alignment', 'iteck_plg'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'iteck_plg'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'iteck_plg'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'iteck_plg'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'iteck_plg'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonials-slider .testi-card .date' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'label' => esc_html__( 'typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-testimonials-slider .testi-card .date',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'user_style',
            [
                'label' => __('User Style', 'iteck_plg'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'user_align',
            [
                'label' => esc_html__('Alignment', 'iteck_plg'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'iteck_plg'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'iteck_plg'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'iteck_plg'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'iteck_plg'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .iteck-testimonials-slider .testi-card .user-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'user_typography',
				'label' => esc_html__( 'typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-testimonials-slider .testi-card .user-wrapper',
			]
		);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $items = $settings['slides_to_view'] ? $settings['slides_to_view'] : 3;

        if ( ! empty( $settings['testimonials'] ) ) {
            ?>
            <div class="iteck-testimonials-slider arrows-<?php echo $settings['arrows_style'] ?>" data-slider-settings='{"items":<?php echo $items ?>}'>
                <div class="arrows col-lg-12 ms-lg-auto">
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
                <div class="testi-slider swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ( $settings['testimonials'] as $testimonial ) : ?>
                            <div class="swiper-slide">
                                <div class="testi-card">
                                    <div class="date fs-16px color-666">
                                        <img src="<?php echo esc_url($settings['icon']['url']); ?>" alt="" class="comma me-2">
                                        <span class="txt"><?php echo esc_html( $testimonial['date'] ); ?></span>
                                    </div>
                                    <div class="main-txt fs-14px color-666 my-4 lh-6">
                                        <?php echo esc_html( $testimonial['testimonial_text'] ); ?>
                                    </div>
                                    <div class="user-wrapper">
                                        <div class="avatar">
                                            <img src="<?php echo esc_url( $testimonial['user_avatar']['url'] ); ?>" alt="">
                                        </div>
                                        <div class="cont">
                                            <h6><?php echo esc_html( $testimonial['user_name'] ); ?></h6>
                                            <small><?php echo esc_html( $testimonial['user_role'] ); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}