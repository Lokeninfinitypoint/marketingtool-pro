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
class Iteck_Throwable_Tags extends Widget_Base {

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
		return 'iteck-throwable-tags';
	}
	
	//script depend
	public function get_script_depends() { return [ 'iteckMatter', 'iteckGsap', 'iteck-throwable' ]; }

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
		return __( 'iteck Throwable Tags', 'iteck_plg' );
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
        $repeater1 = new Repeater();

    
        // Add fields to the repeater
        $repeater1->add_control(
            'item_type',
            [
                'label' => __('Type', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'text' => __('Text', 'iteck_plg'),
                    'image' => __('Image', 'iteck_plg'),
                    'icon' => __('Icon', 'iteck_plg'),
                ],
                'default' => 'text',
            ]
        );
    
        $repeater1->add_control(
            'text_content',
            [
                'label' => __('Text Content', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'item_type' => 'text',
                ],
            ]
        );
    
        $repeater1->add_control(
            'image_url',
            [
                'label' => __('Image URL', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'condition' => [
                    'item_type' => 'image',
                ],
            ]
        );
    
        $repeater1->add_control(
            'icon_url',
            [
                'label' => __('Icon URL', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'condition' => [
                    'item_type' => 'icon',
                ],
            ]
        );
    
        // Add the repeater to the widget
        $this->add_control(
            'items',
            [
                'label' => __('Items', 'iteck_plg'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater1->get_controls(),
                'title_field' => '{{{ item_type }}}',
            ]
        );

        $this->end_controls_section();
    }
    

    protected function render() {
        $settings = $this->get_settings();
    
        ?>
        <div class="iteck-throwable-tags">
            <div class="tags" data-tp-throwable-scene="true">
                <?php foreach ( $settings['items'] as $item ): ?>
                    <div class="item" data-tp-throwable-el="">
                        <?php if ( $item['item_type'] === 'text' ): ?>
                            <div class="bg-black text-white">
                                <h6><?php echo esc_html( $item['text_content'] ); ?></h6>
                            </div>
                        <?php elseif ( $item['item_type'] === 'image' ): ?>
                            <div class="border-0 bg-transparent">
                                <img src="<?php echo esc_url( $item['image_url']['url'] ); ?>" alt="" class="arrow">
                            </div>
                        <?php elseif ( $item['item_type'] === 'icon' ): ?>
                            <div class="border-0 bg-transparent">
                                <img src="<?php echo esc_url( $item['icon_url']['url'] ); ?>" alt="" class="arrow">
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    
    }
    
}