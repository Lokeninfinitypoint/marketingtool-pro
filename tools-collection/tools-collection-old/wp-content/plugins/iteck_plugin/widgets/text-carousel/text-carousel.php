<?php
namespace IteckPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Iteck_Text_Carousel extends Widget_Base {

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
		return 'iteck-text-carousel';
	}
		//script depend
	public function get_script_depends() { return [ 'jquery-swiper','iteck-swiper-slider-script' ]; }

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
		return __( 'Iteck Text Carousel', 'iteck_plg' );
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
		return 'eicon-blockquote';
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
	protected function _register_controls() {
	
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Text Carousel Settings', 'iteck_plg' ),
			]
		);

        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'text',
			[
				'type'    => Controls_Manager::TEXTAREA,
				'label'   => esc_html__( 'Text', 'iteck_plg' ),
				'default' => esc_html__( 'Sell your nft', 'iteck_plg' ),
			]
		);
        
        $repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'textdomain' ),
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'default' => [
					'url' => '#0',
				],
				'label_block' => true,
			]
		);
        
        $repeater->add_control(
			'img',
			[
				'label' => esc_html__( 'Choose Image', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

        $this->add_control(
            'text_repeater',
            [
                'label' => esc_html__('Text Items', 'iteck_plg'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'text' => esc_html__('Sell your nft', 'iteck_plg'),
                    ],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image style', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,

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
		$settings = $this->get_settings();
	?>
		<div class="iteck-text-carousel">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php foreach($settings['text_repeater'] as $index => $item): ?>
                        <div class="swiper-slide">
                            <a href="<?php echo esc_url($item['link']['url']) ?>" <?php if ( $item['link']['is_external'] ) {echo'target="_blank"';} ?>>
                                <?php if(!empty($item['img']['url'])): ?>
                                    <img src="<?php echo esc_url($item['img']['url']); ?>" alt="">
                                <?php endif; ?>
                                <h2><?php echo wp_kses_post($item['text']); ?></h2>
                            </a>
                        </div>
                    <?php endforeach; ?>
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
	protected function content_template() {
		
		
	}
}