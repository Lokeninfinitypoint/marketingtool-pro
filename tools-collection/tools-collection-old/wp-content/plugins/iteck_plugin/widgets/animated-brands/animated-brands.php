<?php
namespace IteckPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.0.0
 */
class Iteck_Animated_Brands extends Widget_Base {

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
		return 'iteck-animated-brands';
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
		return __('Iteck Animated Brands', 'iteck_plg');
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
		return 'eicon-spinner';
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
	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Counter Settings', 'iteck_plg' ),
			]
		);

        $this->add_control(
            'bubbles_image',
            [
                'label' => __( 'Bubbles Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
				    'url' => Utils::get_placeholder_image_src(),
				],
            ]
        );

        $this->add_control(
            'brands_image',
            [
                'label' => __( 'Brands Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
				    'url' => Utils::get_placeholder_image_src(),
				],
            ]
        );

        $this->add_control(
            'choose_us_image',
            [
                'label' => __( 'Background Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
				    'url' => Utils::get_placeholder_image_src(),
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
        $settings = $this->get_settings_for_display();
    
        ?>

        <div class="iteck-animated-brands">
            <img src="<?php echo esc_url($settings['choose_us_image']['url']) ?>" alt="" class="choose_us_img">
            <img src="<?php echo esc_url($settings['brands_image']['url']) ?>" alt="" class="choose_us_brands">        
            <img src="<?php echo esc_url($settings['bubbles_image']['url']) ?>" alt="" class="choose_us_bubbles">
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