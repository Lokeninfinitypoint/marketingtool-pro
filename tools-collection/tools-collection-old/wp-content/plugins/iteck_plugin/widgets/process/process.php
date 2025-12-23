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
class Iteck_Process extends Widget_Base { 

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
		return 'iteck-process';
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
		return __( 'Iteck Process', 'iteck_plg' );
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
		return 'eicon-progress-tracker';
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
                'label' => __( 'Content', 'text-domain' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        // Add controls to repeater
        $repeater->add_control(
            'number',
            [
                'label' => __( 'Number', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1,
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __( 'Title', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Open Dashboard', 'text-domain' ),
                'placeholder' => __( 'Enter title', 'text-domain' ),
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => __( 'Description', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'text-domain' ),
                'placeholder' => __( 'Enter description', 'text-domain' ),
            ]
        );

        $this->add_control(
            'process_cards',
            [
                'label' => __( 'Process Cards', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'number' => 1,
                        'title' => __( 'Open Dashboard', 'text-domain' ),
                        'description' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'text-domain' ),
                    ],
                    [
                        'number' => 2,
                        'title' => __( 'AI Generate', 'text-domain' ),
                        'description' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'text-domain' ),
                    ],
                    [
                        'number' => 3,
                        'title' => __( 'AI Artwork', 'text-domain' ),
                        'description' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'text-domain' ),
                    ],
                    [
                        'number' => 4,
                        'title' => __( 'Output AI', 'text-domain' ),
                        'description' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'text-domain' ),
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $process_cards = $settings['process_cards'];
        ?>
        <div class="iteck-process">
            <div class="cards">
                <div class="row gx-5">
                    <?php foreach ( $process_cards as $index => $card ) : ?>
                        <div class="col-lg-3">
                            <div class="process-card wow fadeInUp" data-wow-delay="<?php echo esc_attr( 0.1 * ($index + 1) ); ?>s">
                                <span class="num"><?php echo esc_html( $card['number'] ); ?></span>
                                <h4><?php echo esc_html( $card['title'] ); ?></h4>
                                <div class="text"><?php echo esc_html( $card['description'] ); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }

    protected function _content_template() {
        ?>
        <#
        var process_cards = settings.process_cards;
        _.each( process_cards, function( card, index ) {
            var delay = 0.1 * (index + 1);
        #>
            <div class="col-lg-3">
                <div class="process-card wow fadeInUp" data-wow-delay="{{ delay }}s">
                    <span class="num">{{{ card.number }}}</span>
                    <h4>{{{ card.title }}}</h4>
                    <div class="text">{{{ card.description }}}</div>
                </div>
            </div>
        <#
        });
        #>
        <?php
    }

}