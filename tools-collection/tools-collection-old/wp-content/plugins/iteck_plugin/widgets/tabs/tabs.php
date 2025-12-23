<?php
namespace IteckPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Iteck_tabs extends Widget_Base {

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
		return 'iteck-tabs';
	}
		//script depend
	public function get_script_depends() { return [ 'iteck-jquery-ui','iteck-bootstrap-bundle', 'lity' ]; }

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
		return __( 'Iteck Tabs', 'iteck_plg' );
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
		return 'eicon-tabs';
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
				'label' => __( 'Tabs Settings', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'reverse_columns',
			[
				'label'         => __( 'Reverse Columns', 'iteck_plg' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'iteck_plg' ),
				'label_off'     => __( 'No', 'iteck_plg' ),
				'return_value'  => 'yes',
				'default'  		=> 'no',
			]
		);

		$repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label' => __('Tab Title', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => '1 Click Acesss',
            ]
        );

		$repeater->add_control(
			'tab_icon',
			[
				'label' =>esc_html__( 'Tab Icon', 'iteck_plg' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
			]
		);

        $repeater->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $repeater->add_control(
            'content_title',
            [
                'label' => __('Content Title', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => 'This Website Needs from <br> Startup to Success.',
            ]
        );

        $repeater->add_control(
            'content_subtitle',
            [
                'label' => __('Content Sub-Title', 'iteck_plg'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Cost-effective hosting that delivers secure, reliable performance.',
            ]
        );

        $repeater->add_control(
            'content_text',
            [
                'label' => __('Content Text', 'iteck_plg'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => 'Websites are stored or “hosted” on a publicly accessible computer (a server). Some websites require an entire server to themselves.',
            ]
        );

		$repeater->add_control(
			'content_image',
			[
				'label' =>esc_html__( 'Image', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'label_block' => true,
				'default' => [
				    'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'content_icon',
			[
				'label' =>esc_html__( 'Icon', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'label_block' => true,
				'default' => [
				    'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'content_video',
			[
				'label' =>esc_html__( 'Video URL', 'iteck_plg' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);
		
		$repeater->add_control(
			'show_btn',
			[
				'label' => __( 'Show Button', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);

        $repeater->add_control(
            'btn_text',
            [
                'label' => __('Button Text', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Learn More',
				'condition' => [
					'show_btn' => 'yes'
				]
            ]
        );

		$repeater->add_control(
			'btn_url',
			[
				'label' =>esc_html__( 'Button URL', 'iteck_plg' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'condition' => [
					'show_btn' => 'yes'
				]
			]
		);
		
		$repeater->add_control(
			'show_btn_side_text',
			[
				'label' => __( 'Show Button Side Text', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);

        $repeater->add_control(
            'side_text_title',
            [
                'label' => __('Side Text Title', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Learn More',
				'condition' => [
					'show_btn_side_text' => 'yes'
				]
            ]
        );

        $repeater->add_control(
            'side_text_content',
            [
                'label' => __('Side Text content', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Learn More',
				'condition' => [
					'show_btn_side_text' => 'yes'
				]
            ]
        );

		$repeater->add_control(
			'side_text_url',
			[
				'label' =>esc_html__( 'Side Text URL', 'iteck_plg' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'condition' => [
					'show_btn' => 'yes'
				]
			]
		);

        $this->add_control(
			'tabs_repeater',
			[
				'label' => esc_html__( 'Projects', 'iteck_plg' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => esc_html__( '1 Click Acesss', 'iteck_plg' ),
					],
					[
						'tab_title' => esc_html__( 'Update Management', 'iteck_plg' ),
					],
					[
						'tab_title' => esc_html__( 'Site Monitoring', 'iteck_plg' ),
					],
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'tabs_icons_style',
			[
				'label' => esc_html__('Tabs Icons', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tabs_icons_color',
			[
				'label' => esc_html__( 'Icon Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .nav .nav-link svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .iteck-tabs .nav .nav-link i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .nav .nav-link svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-tabs .nav .nav-link img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .iteck-tabs .nav .nav-link i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'tabs_style_section',
			[
				'label' => esc_html__('Tabs Style', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'tabs_style',
			[
				'label' => __( 'Tabs Style', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style-1' => __( 'Style 1', 'iteck_plg' ),
					'style-2' => __( 'Style 2', 'iteck_plg' ),
					'style-3' => __( 'Style 3', 'iteck_plg' ),
				],
				'default' => 'style-1',
			]
		);

		$this->start_controls_tabs('tabs_title_style');

		$this->start_controls_tab(
			'tabs_title_normal',
			[
				'label' => esc_html__('Normal', 'iteck_plg'),
			]
		);

		$this->add_control(
			'tabs_titles_color',
			[
				'label' => esc_html__( 'Titles Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .nav .nav-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tabs_titles_typography',
				'selector' => '{{WRAPPER}} .iteck-tabs .nav .nav-link',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_title_active',
			[
				'label' => esc_html__('Active', 'iteck_plg'),
			]
		);

		$this->add_control(
			'tabs_titles_color_active',
			[
				'label' => esc_html__( 'Titles Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .nav .nav-link.active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tabs_titles_typography_active',
				'selector' => '{{WRAPPER}} .iteck-tabs .nav .nav-link.active',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

        $this->start_controls_section(
			'content_style_section',
			[
				'label' => esc_html__('Content', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => esc_html__( 'Title', 'iteck_plg' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_title_color',
			[
				'label' => esc_html__( 'Title Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .feat-content .info h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_title_additional_color',
			[
				'label' => esc_html__( 'Title Additional Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .feat-content .info h2 span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_title_typography',
				'selector' => '{{WRAPPER}} .iteck-tabs .feat-content .info h2',
			]
		);

		$this->add_control(
			'heading_subtitle',
			[
				'label' => esc_html__( 'Sub-Title', 'iteck_plg' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_subtitle_color',
			[
				'label' => esc_html__( 'Content Sub-Title Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_subtitle_typography',
				'selector' => '{{WRAPPER}} .iteck-tabs .subtitle',
			]
		);

		$this->add_control(
			'heading_text',
			[
				'label' => esc_html__( 'Text', 'iteck_plg' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_text_color',
			[
				'label' => esc_html__( 'Content Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .feat-content .info .tab-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_text_typography',
				'selector' => '{{WRAPPER}} .iteck-tabs .feat-content .info .tab-content',
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label' => __('Content Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .feat-content' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => __('Content Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .feat-content' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'video_button_style',
			[
				'label' => esc_html__('Video Button', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'video_button_color',
			[
				'label' => esc_html__( 'Video Button Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .feat-content .play_icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'button_style_section',
			[
				'label' => esc_html__('Button Style', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .iteck-tabs .feat-content .tab-button',
			]
		);

		$this->start_controls_tabs('button_style_section_tabs');

		$this->start_controls_tab(
			'button_style_normal',
			[
				'label' => esc_html__('Normal', 'iteck_plg'),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .feat-content .tab-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg',
			[
				'label' => esc_html__( 'Background', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .feat-content .tab-button' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-tabs .feat-content .tab-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_style_hover',
			[
				'label' => esc_html__('Hover', 'iteck_plg'),
			]
		);

		$this->add_control(
			'button_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .feat-content .tab-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_hover',
			[
				'label' => esc_html__( 'Background', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-tabs .feat-content .tab-button:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .iteck-tabs .feat-content .tab-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

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
		<!-- ====== start choose us ====== -->
        <div class="iteck-tabs">
            <div class="tabs-content">
                <ul class="nav <?php echo esc_attr($settings['tabs_style']); ?> nav-pills mb-3" id="pills-tab" role="tablist">
                    <?php $first_tab = true; foreach ($settings['tabs_repeater'] as $index => $item) : ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php if ($first_tab) esc_html_e('active'); ?>" id="tab-<?php esc_html_e($item['_id']); ?>" data-bs-toggle="pill" data-bs-target="#content-<?php esc_html_e($item['_id']); ?>" type="button" role="tab" aria-controls="pills-<?php esc_html_e($item['_id']); ?>" aria-selected="<?php if ($first_tab) esc_html_e('true'); else esc_html_e('false'); ?>">
                                    <?php Icons_Manager::render_icon( $item['tab_icon'], [ 'aria-hidden' => 'true' ] ); esc_html_e($item['tab_title']); ?>
                            </button>
                        </li>
                    <?php $first_tab = false; endforeach; ?>
                </ul>
                <div class="tab-content pt-2" id="pills-tabContent">
                    <?php $first_tab = true; foreach ($settings['tabs_repeater'] as $index => $item) : ?>
                        <div class="tab-pane fade show <?php if ($first_tab) esc_html_e('active'); ?>" id="content-<?php esc_html_e($item['_id']); ?>" role="tabpanel">
							<?php if(!empty($item['content_subtitle'])): ?><p class="text-center subtitle"><?php echo wp_kses_post($item['content_subtitle']); ?></p><?php endif; ?>
                            <div class="feat-content">
                                <div class="row align-items-center gx-0">
									<?php if(!empty($item['content_image']['url']) && $settings['reverse_columns'] != 'yes'): ?>
                                    <div class="<?php if($settings['reverse_columns'] == 'yes') echo esc_attr('col-lg-7'); else echo esc_attr('col-lg-6'); ?>">
                                        <div class="img img-cover">
                                            <img src="<?php echo esc_url($item['content_image']['url']); ?>" alt="">
                                        </div>
                                    </div>
									<?php endif; ?>
                                    <div class="<?php if(!empty($item['content_image']['url']) && $settings['reverse_columns'] == 'yes') echo esc_attr('col-lg-5'); elseif(!empty($item['content_image']['url']) && $settings['reverse_columns'] != 'yes') echo esc_attr('col-lg-5 offset-lg-1'); else echo esc_attr('col-lg-12') ?>">
                                        <div class="info mt-4 mt-lg-0">
											<?php if(!empty($item['content_icon']['url'])): ?>
												<div class="icon">
													<img src="<?php echo esc_url($item['content_icon']['url']); ?>" alt="">
												</div>
											<?php endif; ?>
											<?php if(!empty($item['content_title'])): ?><h2><?php echo wp_kses_post($item['content_title']); ?></h2><?php endif; ?>
											<?php if(!empty($item['content_text'])): ?><div class="tab-content"><?php echo wp_kses_post($item['content_text']); ?></div><?php endif; ?>
                                        </div>
										<div class="d-flex align-items-center mt-40">
											<?php if($item['show_btn'] == 'yes'): ?>
											<div class="btns">
												<a href="<?php echo esc_url($item['btn_url']['url']); ?>" <?php if ( $item['btn_url']['is_external'] ) {echo'target="_blank"';} ?> class="tab-button">
													<span><?php echo wp_kses_post($item['btn_text']); ?></span>
												</a>
											</div>
											<?php endif; ?>
											<?php if($item['show_btn_side_text'] == 'yes'): ?>
											<div class="inf ms-3">
												<p class="color-999"><?php echo wp_kses_post($item['side_text_title']); ?></p>
												<a href="<?php echo esc_url($item['side_text_url']['url']); ?>" <?php if ( $item['side_text_url']['is_external'] ) {echo'target="_blank"';} ?> class="fw-bold color-000"><?php echo wp_kses_post($item['side_text_content']); ?></a>
											</div>
											<?php endif; ?>
										</div>
                                    </div>
									<?php if(!empty($item['content_image']['url']) && $settings['reverse_columns'] == 'yes'): ?>
                                    <div class="<?php if($settings['reverse_columns'] == 'yes') echo esc_attr('col-lg-7'); else echo esc_attr('col-lg-6'); ?>">
                                        <div class="img img-cover">
                                            <img src="<?php echo esc_url($item['content_image']['url']); ?>" alt="">
                                        </div>
                                    </div>
									<?php endif; ?>
                                </div>
								<?php if(!empty($item['content_video']['url'])): ?>
									<a href="<?php echo esc_url($item['content_video']['url']); ?>" class="play_icon" data-lity>
										<i class="fas fa-play"></i>
									</a>
								<?php endif; ?>
                            </div>
                        </div>
                    <?php $first_tab = false; endforeach; ?>
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


