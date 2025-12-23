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
class Iteck_Testimonials_Show extends Widget_Base {

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
		return 'iteck-testimonials-show';
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
		return __( 'Iteck Testimonials Show','iteck_plg' );
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

    protected function register_controls(){
        $this->start_controls_section(
            'content',
            [
                'label' => __('Content', 'iteck_plg')
            ]
        );
        // Create a new Repeater instance
        $repeater = new Repeater();

        // Add fields to the repeater
        $repeater->add_control(
            'testimonial_date',
            [
                'label' => __( 'Date', 'iteck_plg' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Jul 13, 2021',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'testimonial_text',
            [
                'label' => __( 'Testimonial Text', 'iteck_plg' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Your testimonial text here.',
            ]
        );

        $repeater->add_control(
            'user_name',
            [
                'label' => __( 'User Name', 'iteck_plg' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'John Doe',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'user_role',
            [
                'label' => __( 'User Role', 'iteck_plg' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Role',
            ]
        );

        $repeater->add_control(
            'user_image',
            [
                'label' => __( 'User Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $repeater->add_control(
            'icon_image',
            [
                'label' => __( 'Icon Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        // Add the repeater control to the widget
        $this->add_control(
            'testimonials',
            [
                'label' => __( 'Testimonials', 'iteck_plg' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ user_name }}}',
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => __( 'Show Arrows', 'iteck_plg' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'iteck_plg' ),
                'label_off' => __( 'No', 'iteck_plg' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="iteck-testimonials-show">
                <div class="title-wrapper mb-50">
                    <div class="row align-items-end">
                        <div class="col-lg-12">
                            <?php if ( 'yes' === $settings['show_arrows'] ): ?>
                                <div class="arrows ms-lg-auto mt-4 mt-lg-0 mb-30">
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-button-next"></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="testimonials-slider16 wow fadeInUp slow">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="row">
                                        <?php foreach ( $settings['testimonials'] as $testimonial ): ?>
                                        <div class="col-lg-4">
                                            <div class="testi-card">
                                                <div class="date fs-16px color-666">
                                                    <img src="<?php echo esc_url( $testimonial['icon_image']['url'] ); ?>" alt="" class="comma me-2">
                                                    <span class="txt"><?php echo esc_html( $testimonial['testimonial_date'] ); ?></span>
                                                </div>
                                                <div class="main-txt fs-14px color-666 my-4 lh-6"><?php echo esc_html( $testimonial['testimonial_text'] ); ?></div>
                                                <div class="user-wrapper">
                                                    <div class="avatar">
                                                        <img src="<?php echo esc_url( $testimonial['user_image']['url'] ); ?>" alt="">
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
                            <div class="swiper-slide">
                                <div class="row">
                                        <?php foreach ( $settings['testimonials'] as $testimonial ): ?>
                                        <div class="col-lg-4">
                                            <div class="testi-card">
                                                <div class="date fs-16px color-666">
                                                    <img src="<?php echo esc_url( $testimonial['icon_image']['url'] ); ?>" alt="" class="comma me-2">
                                                    <span class="txt"><?php echo esc_html( $testimonial['testimonial_date'] ); ?></span>
                                                </div>
                                                <div class="main-txt fs-14px color-666 my-4 lh-6"><?php echo esc_html( $testimonial['testimonial_text'] ); ?></div>
                                                <div class="user-wrapper">
                                                    <div class="avatar">
                                                        <img src="<?php echo esc_url( $testimonial['user_image']['url'] ); ?>" alt="">
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
                            <div class="swiper-slide">
                                <div class="row">
                                        <?php foreach ( $settings['testimonials'] as $testimonial ): ?>
                                        <div class="col-lg-4">
                                            <div class="testi-card">
                                                <div class="date fs-16px color-666">
                                                    <img src="<?php echo esc_url( $testimonial['icon_image']['url'] ); ?>" alt="" class="comma me-2">
                                                    <span class="txt"><?php echo esc_html( $testimonial['testimonial_date'] ); ?></span>
                                                </div>
                                                <div class="main-txt fs-14px color-666 my-4 lh-6"><?php echo esc_html( $testimonial['testimonial_text'] ); ?></div>
                                                <div class="user-wrapper">
                                                    <div class="avatar">
                                                        <img src="<?php echo esc_url( $testimonial['user_image']['url'] ); ?>" alt="">
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
                    </div>
                </div>
        </div>
        <?php
    }
}