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
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Group_Control_Box_Shadow;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		 
/**
 * @since 1.0.0
 */
class Iteck_Instagram_Cards extends Widget_Base {

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
		return 'iteck-instagram-cards';
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
		return __( 'Iteck Instagram Cards','iteck_plg' );
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
		return 'fab fa-instagram';
	}

	public function get_script_depends() { return [ 'jquery-swiper','iteck-swiper-slider-script' ]; }


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
            'content',
            [
                'label' => __('Content', 'iteck_plg')
            ]
        );
        $repeater = new Repeater();

        // Repeater: Image
        $repeater->add_control(
            'slide_image',
            [
                'label' => __( 'Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        // Repeater: Link
        $repeater->add_control(
            'slide_link',
            [
                'label' => __( 'Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'iteck_plg' ),
                'default' => [
                    'url' => '#',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        // Add Repeater Control to the Widget
        $this->add_control(
            'slides',
            [
                'label' => __( 'Slides', 'iteck_plg' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->end_controls_section();
    }

    // Widget frontend display
    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="iteck-instagram-slider">
            <div class="swiper-wrapper">
                <?php foreach ( $settings['slides'] as $slide ) : ?>
                    <div class="swiper-slide">
                        <a href="<?php echo esc_url( $slide['slide_link']['url'] ); ?>" class="item">
                            <img src="<?php echo esc_url( $slide['slide_image']['url'] ); ?>" alt="" class="img-cover">
                            <span class="icon"> <i class="fab fa-instagram"></i> </span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}