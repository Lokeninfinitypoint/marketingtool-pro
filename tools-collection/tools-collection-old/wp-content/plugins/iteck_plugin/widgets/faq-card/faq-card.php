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
class Iteck_Faq_Card extends Widget_Base {

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
		return 'iteck-faq-card';
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
		return __( 'Iteck Faq Card','iteck_plg' );
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
	protected function _register_controls() {

		//--------------------------------------------------- Item Content ------------------------------------
		$this->start_controls_section(
			'section_item_content',
			[
				'label' => __( 'Item','iteck_plg' ),
			]
		);

		$this->add_control(
			'number',
			[
				'label' => __( 'Number','iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => __( '1', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title','iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'This is the heading', 'iteck_plg' ),
			]
		);
		
		$this->add_control( 
			'text',
			[
				'label' => __( 'Text','iteck_plg' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'placeholder' => 'Insert your text..',
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'iteck_plg' ),
			]
		);

		$this->add_control(
            'image',
            [
                'label' => __( 'Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'infobox_item_styling',
			[
				'label' => __( 'Item Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => __('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-faq-card .faq-card' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'label' => __('Button Icon Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-faq-card .faq-card',
			]
		);
		
		$this->add_control(
			'top_line_color',
			[
				'label' => __( 'Top Line Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-faq-card .faq-card' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-faq-card .faq-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-faq-card .faq-card',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'infobox_title_styling',
			[
				'label' => __( 'Title Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Title typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-faq-card .faq-card .info .title',
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-faq-card .faq-card .info .title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-faq-card .faq-card .info .title' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'infobox_text_styling',
			[
				'label' => __( 'Text Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => esc_html__( 'Text typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-faq-card .faq-card .info .text',
			]
		);
		
		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-faq-card .faq-card .info .text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-faq-card .faq-card .info .text' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		<div class="iteck-faq-card">
            <div class="faq-card">
                <span class="numb"><?php echo esc_html($settings['number']); ?></span>
                <div class="info">
                    <h6 class="title"><?php echo wp_kses_post($settings['title']); ?></h6>
                    <p class="text"><?php echo wp_kses_post($settings['text']); ?></p>
                </div>
                <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="" class="icon">
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


