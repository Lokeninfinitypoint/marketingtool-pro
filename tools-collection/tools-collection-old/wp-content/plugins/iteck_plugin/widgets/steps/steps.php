<?php
namespace IteckPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Iteck_Steps extends Widget_Base {

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
		return 'iteck-steps';
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
		return __( 'iteck Steps', 'iteck_plg' );
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

     protected function register_controls() {
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
            'step_number',
            [
                'label' => __( 'Step Number', 'iteck_plg' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Step 01',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'step_title',
            [
                'label' => __( 'Step Title', 'iteck_plg' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Step Title',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'step_description',
            [
                'label' => __( 'Step Description', 'iteck_plg' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Description of the step.',
            ]
        );

        // Add the repeater control to the widget
        $this->add_control(
            'steps',
            [
                'label' => __( 'Steps', 'iteck_plg' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ step_number }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="iteck-steps">
                <div class="row">
                    <?php foreach ( $settings['steps'] as $step ): ?>
                        <div class="col-lg-3">
                            <div class="item">
                                <div class="title-wrapper">
                                    <div class="num"><?php echo esc_html( $step['step_number'] ); ?></div>
                                    <h6 class="item-title"><?php echo esc_html( $step['step_title'] ); ?></h6>
                                </div>
                                <div class="text"><?php echo esc_html( $step['step_description'] ); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
        </div>
        <?php
    }
}