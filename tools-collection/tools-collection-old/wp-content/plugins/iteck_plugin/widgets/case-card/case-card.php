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
class Iteck_Case_Card extends Widget_Base {

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
		return 'iteck-case-card';
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
		return __( 'Iteck case Card','iteck_plg' );
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

     protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'iteck_plg' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Image Control
        $this->add_control(
            'image',
            [
                'label' => __( 'Choose Image', 'iteck_plg' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        // Title Control
        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'iteck_plg' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Robot AI Ton', 'iteck_plg' ),
                'placeholder' => __( 'Enter your title', 'iteck_plg' ),
            ]
        );

        // Description Control
        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'iteck_plg' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Creative', 'iteck_plg' ),
                'placeholder' => __( 'Enter your description', 'iteck_plg' ),
            ]
        );

        $this->add_control(
            'url',
            [
                'label'=> __( 'Url', 'iteck_plg'),
                'type'=> \Elementor\Controls_Manager::URL,
                'placeholder'=> __( '#0', 'iteck_plg'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        // Get settings
        $settings = $this->get_settings_for_display();
        ?>
        <a href="<?php echo esc_url($settings['url']['url']); ?>" class="iteck-case-card wow fadeInUp" data-wow-delay="0.1s">
            <div class="img">
                <img src="<?php echo esc_url( $settings['image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['title'] ); ?>">
            </div>
            <div class="info">
                <h6><?php echo esc_html( $settings['title'] ); ?></h6>
                <p><?php echo esc_html( $settings['description'] ); ?></p>
            </div>
        </a>
        <?php
    }
}