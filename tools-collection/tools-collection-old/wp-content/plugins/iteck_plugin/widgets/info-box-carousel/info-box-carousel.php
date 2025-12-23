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
class Iteck_Info_Box_Carousel extends Widget_Base {

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
		return 'iteck-infobox-carousel';
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
		return __( 'Iteck Info Box Carousel','iteck_plg' );
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
			'cards',
			[
				'label' => __( 'Cards', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'cards' => __( 'Cards', 'iteck_plg' ),
					'hover-cards' => __( 'Hover Cards', 'iteck_plg' ),
				],
				'default' => 'cards',
			]
		);
		
		$this->add_control(
			'wave_style',
			[
				'label' => __( 'Wave Style', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
				'condition' => [
					'cards' => 'cards'
				]
			]
		);
		
		$this->add_control(
			'continued_slide',
			[
				'label' => __( 'continued slide', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
				'condition' => [
					'cards' => 'cards'
				]
			]
		);

		$this->add_control(
			'items_list',
			[
				'label' => __( 'tags List', 'iteck_plg' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'tag_text' => __( 'Management', 'iteck_plg' ),
						'tag_link' => __( '#0', 'iteck_plg' ),
					],
				],
				'fields' => [
                    [
						'name' => 'title',
						'label' => __( 'Title', 'iteck_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
                        'default' => __( 'IT Consultation', 'iteck_plg' ),
					],
					[
						'name' => 'text',
						'label' => __( 'Text', 'iteck_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
					],
                    [
                        'name' => 'image',
                        'label' => __( 'Image', 'iteck_plg' ),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'name' => 'tags_list',
                        'label' => esc_html__( 'Tags', 'iteck_plg' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'none',
                        'options' => [
                            'tag-1' => esc_html__( 'Tag 1', 'iteck_plg' ),
                            'tag-2' => esc_html__( 'Tag 2', 'iteck_plg' ),
                            'tag-3' => esc_html__( 'Tag 3', 'iteck_plg' ),
                            'tag-4' => esc_html__( 'Tag 4', 'iteck_plg' ),
                            'tag-5' => esc_html__( 'Tag 5', 'iteck_plg' ),
                            'tag-6' => esc_html__( 'Tag 6', 'iteck_plg' ),
                            'tag-7' => esc_html__( 'Tag 7', 'iteck_plg' ),
                        ],
                    ],
					[
						'name' => 'tag_text_1',
						'label' => __( 'Tag Text', 'iteck_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
                        'condition' => [
                            'tags_list' => 'tag-1',
                        ]
					],
					[
						'name' => 'tag_link_1',
						'label' => __( 'Tag Link', 'iteck_plg' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Leave it blank if you don\'t need this button', 'iteck_plg' ),
                        'condition' => [
                            'tags_list' => 'tag-1',
                        ]
					],
					[
						'name' => 'tag_text_2',
						'label' => __( 'Tag Text', 'iteck_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
                        'condition' => [
                            'tags_list' => 'tag-2',
                        ]
					],
					[
						'name' => 'tag_link_2',
						'label' => __( 'Tag Link', 'iteck_plg' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Leave it blank if you don\'t need this button', 'iteck_plg' ),
                        'condition' => [
                            'tags_list' => 'tag-2',
                        ]
					],
					[
						'name' => 'tag_text_3',
						'label' => __( 'Tag Text', 'iteck_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
                        'condition' => [
                            'tags_list' => 'tag-3',
                        ]
					],
					[
						'name' => 'tag_link_3',
						'label' => __( 'Tag Link', 'iteck_plg' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Leave it blank if you don\'t need this button', 'iteck_plg' ),
                        'condition' => [
                            'tags_list' => 'tag-3',
                        ]
					],
					[
						'name' => 'tag_text_4',
						'label' => __( 'Tag Text', 'iteck_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
                        'condition' => [
                            'tags_list' => 'tag-4',
                        ]
					],
					[
						'name' => 'tag_link_4',
						'label' => __( 'Tag Link', 'iteck_plg' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Leave it blank if you don\'t need this button', 'iteck_plg' ),
                        'condition' => [
                            'tags_list' => 'tag-4',
                        ]
					],
					[
						'name' => 'tag_text_5',
						'label' => __( 'Tag Text', 'iteck_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
                        'condition' => [
                            'tags_list' => 'tag-5',
                        ]
					],
					[
						'name' => 'tag_link_5',
						'label' => __( 'Tag Link', 'iteck_plg' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Leave it blank if you don\'t need this button', 'iteck_plg' ),
                        'condition' => [
                            'tags_list' => 'tag-5',
                        ]
					],
					[
						'name' => 'tag_text_6',
						'label' => __( 'Tag Text', 'iteck_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
                        'condition' => [
                            'tags_list' => 'tag-6',
                        ]
					],
					[
						'name' => 'tag_link_6',
						'label' => __( 'Tag Link', 'iteck_plg' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Leave it blank if you don\'t need this button', 'iteck_plg' ),
                        'condition' => [
                            'tags_list' => 'tag-6',
                        ]
					],
					[
						'name' => 'tag_text_7',
						'label' => __( 'Tag Text', 'iteck_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
                        'condition' => [
                            'tags_list' => 'tag-7',
                        ]
					],
					[
						'name' => 'tag_link_7',
						'label' => __( 'Tag Link', 'iteck_plg' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Leave it blank if you don\'t need this button', 'iteck_plg' ),
                        'condition' => [
                            'tags_list' => 'tag-7',
                        ]
					],
					[
						'name' => 'button_text',
						'label' => __( 'Button Text', 'iteck_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
					],
					[
						'name' => 'button_link',
						'label' => __( 'Button Link', 'iteck_plg' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Leave it blank if you don\'t need this button', 'iteck_plg' ),
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_style',
			[
				'label' => esc_html__('Item Style', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_item_style');

		$this->start_controls_tab(
			'tab_item',
			[
				'label' => esc_html__('Normal', 'iteck_plg'),
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box-carousel.carousel .service_box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .iteck-info-box-carousel.carousel .service_box',
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
					'{{WRAPPER}} .iteck-info-box-carousel.carousel .service_box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .iteck-info-box-carousel.carousel .service_box',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_item_hover',
			[
				'label' => esc_html__('Hover', 'iteck_plg'),
			]
		);

		$this->add_responsive_control(
			'item_padding_hover',
			[
				'label' => esc_html__('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box-carousel.carousel .service_box:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border_hover',
				'selector' => '{{WRAPPER}} .iteck-info-box-carousel.carousel .service_box:hover',
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
					'{{WRAPPER}} .iteck-info-box-carousel.carousel .service_box:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow_hover',
				'selector' => '{{WRAPPER}} .iteck-info-box-carousel.carousel .service_box:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

        $this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__('Title', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'label'     => __('Typography', 'iteck_plg'),
				'selector'  => '{{WRAPPER}} .iteck-info-box-carousel .service_box h4',
			]
		);

        $this->add_control(
			'title_color',
			[
				'label' => __('Title Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box-carousel .service_box h4' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'section_text',
			[
				'label' => esc_html__('Text', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'text_typography',
				'label'     => __('Typography', 'iteck_plg'),
				'selector'  => '{{WRAPPER}} .iteck-info-box-carousel .service_box p',
			]
		);

        $this->add_control(
			'text_color',
			[
				'label' => __('Text Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box-carousel .service_box p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'section_tags',
			[
				'label' => esc_html__('Tags', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'tags_typography',
				'label'     => __('Typography', 'iteck_plg'),
				'selector'  => '{{WRAPPER}} .iteck-info-box-carousel .service_box .tags a',
			]
		);

        $this->add_control(
			'tags_color',
			[
				'label' => __('Tags Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box-carousel .service_box .tags a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'tags_bg',
				'label' => __('Button Background', 'iteck_plg'),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .iteck-info-box-carousel .service_box .tags a',
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__('Button', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'button_typography',
				'label'     => __('Typography', 'iteck_plg'),
				'selector'  => '{{WRAPPER}} .iteck-info-box-carousel .service_box .read-more-btn',
			]
		);

        $this->add_control(
			'button_color',
			[
				'label' => __('Button Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box-carousel .service_box .read-more-btn' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'button_color_hover',
			[
				'label' => __('Button Color Hover', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-info-box-carousel .continued-slide .service_box:hover .read-more-btn' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-info-box-carousel .service_box .read-more-btn:hover' => 'color: {{VALUE}};',
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
        <div class="iteck-info-box-carousel <?php if($settings['wave_style'] == 'yes' && $settings['cards'] == 'cards'): echo 'wave-carousel'; elseif($settings['cards'] == 'hover-cards'): echo 'hover-cards'; else: echo 'carousel'; endif; ?>">
            <div class="services_slider <?php if($settings['continued_slide'] == 'yes') echo 'continued-slide'; else echo 'normal-slider'; ?> position-relative">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach( $settings['items_list'] as $index => $item ): ?>
                        <div class="swiper-slide">
                            <div class="service_box">
                                <div class="icon">
                                    <img src="<?php echo esc_url($item['image']['url']); ?>" alt="">
                                </div>
                                <div class="info">
                                    <h4><?php echo wp_kses_post($item['title']); ?></h4>
                                    <p class="op_7 mt_20 mb_30 px-3"><?php echo wp_kses_post($item['text']); ?></p>
                                    <div class="tags d-flex flex-wrap justify-content-center mt_30 style_2">
                                        <?php if(!empty($item['tag_text_1'])): ?>
                                        <a href="<?php echo esc_url($item['tag_link_1']['url']) ?>" <?php if ( $item['tag_link_1']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['tag_text_1']); ?></a>
                                        <?php endif;
                                        if(!empty($item['tag_text_2'])): ?>
                                        <a href="<?php echo esc_url($item['tag_link_2']['url']) ?>" <?php if ( $item['tag_link_2']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['tag_text_2']); ?></a>
                                        <?php endif;
                                        if(!empty($item['tag_text_3'])): ?>
                                        <a href="<?php echo esc_url($item['tag_link_3']['url']) ?>" <?php if ( $item['tag_link_3']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['tag_text_3']); ?></a>
                                        <?php endif;
                                        if(!empty($item['tag_text_4'])): ?>
                                        <a href="<?php echo esc_url($item['tag_link_4']['url']) ?>" <?php if ( $item['tag_link_4']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['tag_text_4']); ?></a>
                                        <?php endif;
                                        if(!empty($item['tag_text_5'])): ?>
                                        <a href="<?php echo esc_url($item['tag_link_5']['url']) ?>" <?php if ( $item['tag_link_5']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['tag_text_5']); ?></a>
                                        <?php endif;
                                        if(!empty($item['tag_text_6'])): ?>
                                        <a href="<?php echo esc_url($item['tag_link_6']['url']) ?>" <?php if ( $item['tag_link_6']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['tag_text_6']); ?></a>
                                        <?php endif;
                                        if(!empty($item['tag_text_7'])): ?>
                                        <a href="<?php echo esc_url($item['tag_link_7']['url']) ?>" <?php if ( $item['tag_link_7']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['tag_text_7']); ?></a>
                                        <?php endif; ?>
                                    </div>
									<?php if(!empty($item['button_link']['url'])): ?>
										<a class="read-more-btn" href="<?php echo esc_url($item['button_link']['url']); ?>" <?php if ( $settings['button_link']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['button_text']); ?></a>
									<?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="pagination_circle">
                    <div class="swiper-pagination"></div>
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
