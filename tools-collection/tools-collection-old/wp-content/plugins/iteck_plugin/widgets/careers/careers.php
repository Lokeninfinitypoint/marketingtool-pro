<?php
namespace IteckPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.1.0 
 */
class Iteck_Careers extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'iteck-careers';
	}

	//script depend
	public function get_script_depends() { return [ 'jquery-swiper','wow','iteck-addons-custom-scripts']; }

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
		return __( 'Iteck Careers', 'iteck_plg' );
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
		return 'eicon-person';
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
		return [ 'iteck-menu-elements' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
	
		$this->start_controls_section(
			'careers_section',
			[
				'label' => __( 'Careers Settings', 'iteck_plg' ),
			]
		);
        
        $this->add_control(
			'type',
			[
				'label' => __('Layout Type', 'iteck_plg'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'item' => __('Item', 'iteck_plg'),
					'slider' => __('Slider', 'iteck_plg'),
				],
				'default' => 'item'
			]
        );

        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'repeater_title',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Title', 'iteck_plg' ),
				'default' => esc_html__( 'Wesite Developer', 'iteck_plg' ),
			]
		);
		
		$repeater->add_control(
			'repeater_link',
			[
				'label' => __( 'Button Link','iteck_plg' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'wwww.link.com',
			]
		); 

		$repeater->add_control(
			'repeater_description',
			[
				'type'    => Controls_Manager::TEXTAREA,
				'label'   => esc_html__( 'Description', 'iteck_plg' ),
				'default' => esc_html__( 'We’re looking for a Senior Website Developer to join our team', 'iteck_plg' ),
			]
		);

		$repeater->add_control(
			'repeater_work_time',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Work Time', 'iteck_plg' ),
				'default' => esc_html__( 'Full-time', 'iteck_plg' ),
			]
		);

		$repeater->add_control(
			'repeater_salary',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Salary', 'iteck_plg' ),
				'default' => esc_html__( '10k - $20k', 'iteck_plg' ),
			]
		);

		$repeater->add_control(
			'repeater_trend',
			[
				'label'         => __( 'Trend', 'iteck_plg' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'iteck_plg' ),
				'label_off'     => __( 'No', 'iteck_plg' ),
				'return_value'  => 'yes',
				'default'  		=> false,
			]
		);

        $this->add_control(
            'careers_repeater',
            [
                'label' => esc_html__('Careers Items', 'iteck_plg'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'repeater_title' => esc_html__('Wesite Developer', 'iteck_plg'),
                    ],
                ],
                'title_field' => '{{{ repeater_title }}}',
				'condition' => [
					'type' => 'slider'
				]
            ]
        );

		$this->add_control(
			'title',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Title', 'iteck_plg' ),
				'default' => esc_html__( 'Wesite Developer', 'iteck_plg' ),
				'condition' => [
					'type' => 'item'
				]
			]
		);
		
		$this->add_control(
			'link',
			[
				'label' => __( 'Button Link','iteck_plg' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'wwww.link.com',
				'condition' => [
					'type' => 'item'
				]
			]
		); 

		$this->add_control(
			'description',
			[
				'type'    => Controls_Manager::TEXTAREA,
				'label'   => esc_html__( 'Description', 'iteck_plg' ),
				'default' => esc_html__( 'We’re looking for a Senior Website Developer to join our team', 'iteck_plg' ),
				'condition' => [
					'type' => 'item'
				]
			]
		);

		$this->add_control(
			'work_time',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Work Time', 'iteck_plg' ),
				'default' => esc_html__( 'Full-time', 'iteck_plg' ),
				'condition' => [
					'type' => 'item'
				]
			]
		);

		$this->add_control(
			'salary',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Salary', 'iteck_plg' ),
				'default' => esc_html__( '10k - $20k', 'iteck_plg' ),
				'condition' => [
					'type' => 'item'
				]
			]
		);

		$this->add_control(
			'trend',
			[
				'label'         => __( 'Trend', 'iteck_plg' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'iteck_plg' ),
				'label_off'     => __( 'No', 'iteck_plg' ),
				'return_value'  => 'yes',
				'default'  		=> false,
				'condition' => [
					'type' => 'item'
				]
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
		
		$this->add_control(
			'trend_color',
			[
				'label' => __( 'Trend color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card .trend-mark' => 'background-color: {{VALUE}};',
				],
                'condition' => [
                    'trend' => 'yes'
                ]
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => __('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('tabs_item_style');

		$this->start_controls_tab(
			'tab_item',
			[
				'label' => esc_html__('Normal', 'iteck_plg'),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'label' => __('Button Icon Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-careers .position-card',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .iteck-careers .position-card',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-careers .position-card',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_icon_hover',
			[
				'label' => esc_html__('Hover', 'iteck_plg'),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'item_background_hover',
				'label' => __('Button Icon Background', 'iteck_plg'),
				'types' => [ 'classic','gradient' ],
				'selector' => '{{WRAPPER}} .iteck-careers .position-card:hover',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border_hover',
				'selector' => '{{WRAPPER}} .iteck-careers .position-card:hover',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'item_border_radius_hover',
			[
				'label' => esc_html__('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow_hover',
				'selector' => '{{WRAPPER}} .iteck-careers .position-card:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

        $this->start_controls_section(
			'title_styling',
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
				'selector' => '{{WRAPPER}} .iteck-careers .position-card h5',
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card h5' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label' => __( 'Title color Hover', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card:hover h5' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .iteck-careers .position-card h5' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'description_styling',
			[
				'label' => __( 'Description Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => esc_html__( 'Description typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-careers .position-card p',
			]
		);
		
		$this->add_control(
			'description_color',
			[
				'label' => __( 'Description color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'description_margin',
			[
				'label' => __('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card p' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'work_time_styling',
			[
				'label' => __( 'Work Time Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'work_time_typography',
				'label' => esc_html__( 'Work Time typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-careers .position-card .time',
			]
		);
		
		$this->add_control(
			'work_time_color',
			[
				'label' => __( 'Work Time color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card .time' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'work_time_icon_size',
			[
				'label' => __( 'Icon size', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card .time i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'work_time_icon_color',
			[
				'label' => __( 'Icon color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card .time i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'salary_styling',
			[
				'label' => __( 'Salary Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'salary_typography',
				'label' => esc_html__( 'Work Time typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-careers .position-card .salary',
			]
		);
		
		$this->add_control(
			'salary_color',
			[
				'label' => __( 'Work Time color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card .salary' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'salary_icon_size',
			[
				'label' => __( 'Icon size', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card .salary i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'salary_icon_color',
			[
				'label' => __( 'Icon color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .position-card .salary i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'arrows_styling',
			[
				'label' => __( 'Arrows Settings', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'arrows_color',
			[
				'label' => __( 'Arrows color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .swiper-button-next:after, {{WRAPPER}} .iteck-careers .swiper-button-prev:after' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'arrows_bg',
			[
				'label' => __( 'Arrows Background', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .swiper-button-next, {{WRAPPER}} .iteck-careers .swiper-button-prev' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'arrows_color_hover',
			[
				'label' => __( 'Arrows Hover color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .swiper-button-next:hover:after, {{WRAPPER}} .iteck-careers .swiper-button-prev:hover:after' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'arrows_bg_hover',
			[
				'label' => __( 'Arrows Hover Background', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-careers .swiper-button-next:hover, {{WRAPPER}} .iteck-careers .swiper-button-prev:hover' => 'background: {{VALUE}};',
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
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings(); 

		?> 
		 
         <div class="iteck-careers">
			<?php if($settings['type'] == 'slider'): ?>
				<div class="iteck-careers-slider">
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<?php foreach($settings['careers_repeater'] as $index => $item): ?>
								<div class="swiper-slide">
									<a href="<?php echo esc_url($item['repeater_link']['url']) ?>" <?php if ( $item['repeater_link']['is_external'] ) {echo'target="_blank"';} ?> class="position-card">
										<h5><?php echo wp_kses_post($item['repeater_title']); ?></h5>
										<p><?php echo wp_kses_post($item['repeater_description']); ?></p>
										<div class="time">
											<span class="time me-4"> <i class="fal fa-clock me-1"></i> <?php echo wp_kses_post($item['repeater_work_time']); ?></span>
											<span class="salary"> <i class="fal fa-dollar-sign me-1"></i> <?php echo wp_kses_post($item['repeater_salary']); ?></span>
										</div>
										<?php if($item['repeater_trend'] == 'yes'): ?>
											<span class="trend-mark"> <i class="fas fa-bolt"></i> </span>
										<?php endif; ?>
									</a>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div>
				</div>
			<?php else: ?>
				<a href="<?php echo esc_url($settings['link']['url']) ?>" <?php if ( $settings['link']['is_external'] ) {echo'target="_blank"';} ?> class="position-card">
					<h5><?php echo wp_kses_post($settings['title']); ?></h5>
					<p><?php echo wp_kses_post($settings['description']); ?></p>
					<div class="time">
						<span class="time me-4"> <i class="fal fa-clock me-1"></i> <?php echo wp_kses_post($settings['work_time']); ?></span>
						<span class="salary"> <i class="fal fa-dollar-sign me-1"></i> <?php echo wp_kses_post($settings['salary']); ?></span>
					</div>
					<?php if($settings['trend'] == 'yes'): ?>
						<span class="trend-mark"> <i class="fas fa-bolt"></i> </span>
					<?php endif; ?>
				</a>
			<?php endif; ?>
         </div>
		
	<?php }

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function content_template() { }
}


