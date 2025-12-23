<?php

namespace IteckPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Box_Shadow;


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Iteck_Video_Popup_Button extends Widget_Base
{

    public function get_name()
    {
        return 'iteck-video-popup-button';
    }

    //script depend
	public function get_script_depends() { 
        return [ 'lity' ]; 
    }

    public function get_title()
    {
        return __('Iteck Video Popup Button', 'iteck_plg');
    }

    public function get_icon()
    {
        return 'eicon-video-playlist';
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

    protected function _register_controls()
    {

        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__('Button', 'iteck_plg'),
            ]
        );

        $this->add_control(
			'btn_icon',
			[
				'label' => esc_html__( 'Icon', 'iteck_plg' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-play',
					'library' => 'fa-solid',
				],
			]
		);

        $this->add_control(
			'btn_text',
			[
				'label' => __( 'Text','iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Watch the trailer', 'iteck_plg' ),
			]
		);

        $this->add_control(
			'btn_link',
			[
				'label' => __('Button Link', 'iteck_plg'),
				'type' => Controls_Manager::URL,
				'placeholder' => 'Leave Link here',
			]
		);

        $this->add_control(
			'button_alignment',
			[
				'label' => __('Button Alignment', 'iteck_plg'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'iteck_plg'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'iteck_plg'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'iteck_plg'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'elementor-align-',
				'default' => 'left',
			]
		);

		$this->add_control(
			'vid_btn_animate',
			[
				'label' => __( 'animate', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__('Button', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units' => [ 'px' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-play-button i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_space',
			[
				'label' => esc_html__( 'Icon Space', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units' => [ 'px' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-play-button .vid_btn i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'text_color',
			[
				'label' => __('Text Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-play-button .text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'text_typography',
				'label'     => __('Typography', 'iteck_plg'),
				'selector'  => '{{WRAPPER}} .iteck-play-button .text',
			]
		);

        $this->add_control(
			'icon_color',
			[
				'label' => __('Text Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-play-button i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_bg',
				'label' => __('Button Background', 'iteck_plg'),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .iteck-play-button .vid_btn i',
				'fields_options' => [
					'color' => [
						'selectors' => [
							'{{SELECTOR}}' => 'background: {{color.VALUE}};',
						],
					],
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'label' => esc_html__( 'Border', 'iteck_plg' ),
				'selector' => '{{WRAPPER}} .iteck-play-button .vid_btn i',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-play-button .vid_btn i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_shadow',
				'selector' => '{{WRAPPER}} .iteck-play-button .vid_btn i',
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'drop_shadow',
			[
				'label' => esc_html__('Drop Shadow', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'drop_shadow_offset_x',
			[
				'label' => esc_html__('Offset x', 'iteck_plg'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 200,
						'min' => 0,
						'step' => 1,
					],
				],
                'render_type' => 'ui',
			]
		);

		$this->add_control(
			'drop_shadow_offset_y',
			[
				'label' => esc_html__('Offset y', 'iteck_plg'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 200,
						'min' => 0,
						'step' => 1,
					],
				],
                'render_type' => 'ui',
			]
		);

		$this->add_control(
			'drop_shadow_blur_radius',
			[
				'label' => esc_html__('Blur Radius', 'iteck_plg'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 200,
						'min' => 0,
						'step' => 1,
					],
				],
                'render_type' => 'ui',
			]
		);

		$this->add_control(
			'drop_shadow_color',
			[
				'label' => __('Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-play-button i' => 'filter: drop-shadow({{drop_shadow_offset_x.SIZE}}px {{drop_shadow_offset_y.SIZE}}px {{drop_shadow_blur_radius.SIZE}}px {{VALUE}});',
				],
			]
		);

		$this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

?>

        <div class="iteck-play-button">
            <a href="<?php echo esc_url($settings['btn_link']['url']); ?>" data-lity class="vid_btn">
                <i class="<?php echo esc_attr($settings['btn_icon']['value']) ?> wow  <?php if($settings['vid_btn_animate'] == 'yes') echo 'scale-animation'; ?>"></i>
                <?php if(!empty($settings['btn_text'])){ ?>
					<span class="text">
						<?php echo wp_kses_post($settings['btn_text']) ?>
					</span>
				<?php } ?>
            </a>
        </div>

<?php

    }

    /**
     * Render Animated Headline widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.9.0
     * @access protected
     */
    protected function content_template()
    {
    }
}
