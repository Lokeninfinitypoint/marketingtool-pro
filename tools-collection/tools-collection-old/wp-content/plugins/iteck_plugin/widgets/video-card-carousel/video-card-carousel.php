<?php

namespace IteckPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) exit; // Exit if accessed directly



/**
 * @since 1.0.0
 */
class Iteck_Video_Card_Carousel extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'iteck-video-card-carousel';
    }
    //script depend
    public function get_script_depends()
    {
        return ['jquery-swiper', 'iteck-addons-custom-scripts', 'lity'];
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
    public function get_title()
    {
        return __('Iteck Video Card Carousel', 'iteck_plg');
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
    public function get_icon()
    {
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
    public function get_categories()
    {
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
    protected function _register_controls()
    {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Screenshots Settings', 'iteck_plg'),
            ]
        );

		$group_repeater = new \Elementor\Repeater();

        $group_repeater->add_control(
			'card_image',
			[
				'label' => esc_html__( 'Card Image', 'iteck_plg' ),
                'label' => __( 'Card Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
			]
		);

        $group_repeater->add_control(
			'video_url',
			[
                'label' => __( 'Video URL', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'placeholder' => __( 'Enter the video URL', 'iteck_plg' ),
			]
		);

        $group_repeater->add_control(
			'logo',
			[
                'label' => __( 'Logo', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
			]
		);

        $group_repeater->add_control(
			'text',
			[
                'label' => __( 'Text', 'iteck_plg' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'author',
			[
                'label' => __( 'Author Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
			]
		);

        $group_repeater->add_control(
			'name',
			[
                'label' => __( 'Name', 'iteck_plg' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __( 'name', 'iteck_plg' ),
			]
		);

        $group_repeater->add_control(
			'position',
			[
                'label' => __( 'Position', 'iteck_plg' ),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default' => __( 'CEO', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'items_list',
			[
				'label' => __( 'Testimonials List', 'iteck_plg' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $group_repeater->get_controls(),
				'default' => [
					[
						'position' => esc_html__( 'CEO', 'iteck_plg' ),
					],
				],
				'title_field' => '{{{ name }}}',
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'text_style',
			[
				'label' => esc_html__( 'Text', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-video-card-carousel .video-card .info h4' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .iteck-video-card-carousel .video-card .info h4',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-video-card-carousel .video-card .info h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_margin',
			[
				'label' => esc_html__( 'Margin', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-video-card-carousel .video-card .info h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'name_style',
			[
				'label' => esc_html__( 'Name', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-video-card-carousel .video-card .info .author .inf h6' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .iteck-video-card-carousel .video-card .info .author .inf h6',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_responsive_control(
			'name_padding',
			[
				'label' => esc_html__( 'Padding', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-video-card-carousel .video-card .info .author .inf h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'name_margin',
			[
				'label' => esc_html__( 'Margin', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-video-card-carousel .video-card .info .author .inf h6' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'position_style',
			[
				'label' => esc_html__( 'Position', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'position_color',
			[
				'label' => esc_html__( 'Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-video-card-carousel .video-card .info .author .inf p' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'position_typography',
				'selector' => '{{WRAPPER}} .iteck-video-card-carousel .video-card .info .author .inf p',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_responsive_control(
			'position_padding',
			[
				'label' => esc_html__( 'Padding', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-video-card-carousel .video-card .info .author .inf p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'position_margin',
			[
				'label' => esc_html__( 'Margin', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-video-card-carousel .video-card .info .author .inf p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
    protected function render()
    {
        $settings = $this->get_settings();

?>
        <div class="iteck-video-card-carousel">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php foreach($settings['items_list'] as $index => $item): ?>
                        <div class="swiper-slide">
                            <div class="video-card">
                                <div class="row gx-0">
                                    <div class="col-lg-6">
                                        <div class="img img-cover">
                                            <img src="<?php echo esc_url($item['card_image']['url']) ?>" alt="">
                                            <a href="<?php echo esc_url($item['video_url']['url']) ?>" data-lity class="play_icon">
                                                <i class="fas fa-play"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="info">
                                            <div class="icon">
                                                <img src="<?php echo esc_url($item['logo']['url']) ?>" alt="">
                                            </div>
                                            <h4><?php echo wp_kses_post($item['text']) ?></h4>
                                            <div class="author">
                                                <div class="img icon-50 rounded-circle overflow-hidden img-cover me-3 flex-shrink-0">
                                                    <img src="<?php echo esc_url($item['author']['url']) ?>" alt="">
                                                </div>
                                                <div class="inf">
                                                    <p><?php echo wp_kses_post($item['position']) ?></p>
                                                    <h6><?php echo wp_kses_post($item['name']) ?></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="swiper-pagination"></div>
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
    protected function content_template()
    {
    }
}
