<?php
namespace IteckPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Iteck_Projects_Carousel extends Widget_Base {

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
		return 'iteck-projects-carousel';
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
		return __( 'Iteck Projects Carousel', 'iteck_plg' );
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
		return 'eicon-slider-push';
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
				'label' => __( 'Projects Settings', 'iteck_plg' ),
			]
		);

        $this->add_control(
			'section_title',
			[
				'label' => esc_html__( 'Main Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Completed Projects', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $this->add_control(
			'section_subtitle',
			[
				'label' => esc_html__( 'Main Sub-Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'case study', 'iteck_plg' ),
				'label_block' => true,
			]
		);

		$group_repeater = new \Elementor\Repeater();

        $group_repeater->add_control(
			'project_title',
			[
				'label' => esc_html__( 'Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Hayden Co. Market Data Analysis', 'iteck_plg' ),
				'label_block' => true,
			]
		);

        $group_repeater->add_control(
			'project_description',
			[
				'label' => esc_html__( 'Description', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Stay focused and productive with a clean and clutter-free note space the flexible ways to organize. Everything melancholy uncommonly but solicitude.', 'iteck_plg' ),
				'label_block' => true,
			]
		);

		$group_repeater->add_control(
			'project_img',
			[
				'label' => esc_html__( 'Project Image', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$group_repeater->add_control(
			'animated_img',
			[
				'label' => esc_html__( 'Animated Image', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$group_repeater->add_control(
			'logo_img',
			[
				'label' => esc_html__( 'Logo Image', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$group_repeater->add_control(
			'project_tags',
			[
				'label' => __( 'Tags', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'tag1' => __( 'Tag 1', 'iteck_plg' ),
					'tag2' => __( 'Tag 2', 'iteck_plg' ),
					'tag3' => __( 'Tag 3', 'iteck_plg' ),
					'tag4' => __( 'Tag 3', 'iteck_plg' ),
					'tag5' => __( 'Tag 3', 'iteck_plg' ),
				],
				'default' => 'tag1',
			]
		);

        $group_repeater->add_control(
			'tag1_title',
			[
				'label' => esc_html__( 'Tag 1 Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Analysis', 'iteck_plg' ),
				'label_block' => true,
                'condition' => [
                    'project_tags' => 'tag1'
                ]
			]
		);

        $group_repeater->add_control(
            'tag1_link',
            [
                'label' => __( 'Tag 1 Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'condition' => [
                    'project_tags' => 'tag1'
                ]
            ]
        );

        $group_repeater->add_control(
			'tag2_title',
			[
				'label' => esc_html__( 'Tag 2 Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
                'condition' => [
                    'project_tags' => 'tag2'
                ]
			]
		);

        $group_repeater->add_control(
            'tag2_link',
            [
                'label' => __( 'Tag 2 Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'condition' => [
                    'project_tags' => 'tag2'
                ]
            ]
        );

        $group_repeater->add_control(
			'tag3_title',
			[
				'label' => esc_html__( 'Tag 3 Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
                'condition' => [
                    'project_tags' => 'tag3'
                ]
			]
		);

        $group_repeater->add_control(
            'tag3_link',
            [
                'label' => __( 'Tag 3 Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'condition' => [
                    'project_tags' => 'tag3'
                ]
            ]
        );

        $group_repeater->add_control(
			'tag4_title',
			[
				'label' => esc_html__( 'Tag 4 Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
                'condition' => [
                    'project_tags' => 'tag4'
                ]
			]
		);

        $group_repeater->add_control(
            'tag4_link',
            [
                'label' => __( 'Tag 4 Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'condition' => [
                    'project_tags' => 'tag4'
                ]
            ]
        );

        $group_repeater->add_control(
			'tag5_title',
			[
				'label' => esc_html__( 'Tag 5 Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
                'condition' => [
                    'project_tags' => 'tag5'
                ]
			]
		);

        $group_repeater->add_control(
            'tag5_link',
            [
                'label' => __( 'Tag 5 Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'condition' => [
                    'project_tags' => 'tag5'
                ]
            ]
        );

		$group_repeater->add_control(
			'project_fields',
			[
				'label' => __( 'Fields', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'field1' => __( 'Field 1', 'iteck_plg' ),
					'field2' => __( 'Field 2', 'iteck_plg' ),
					'field3' => __( 'Field 3', 'iteck_plg' ),
				],
				'default' => 'field1',
			]
		);

        $group_repeater->add_control(
			'field1_title',
			[
				'label' => esc_html__( 'Field 1 Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Client Name', 'iteck_plg' ),
				'label_block' => true,
                'condition' => [
                    'project_fields' => 'field1'
                ]
			]
		);

        $group_repeater->add_control(
			'field1_text',
			[
				'label' => esc_html__( 'Field 2 Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Miranda H. Halim', 'iteck_plg' ),
				'label_block' => true,
                'condition' => [
                    'project_fields' => 'field1'
                ]
			]
		);

        $group_repeater->add_control(
			'field2_title',
			[
				'label' => esc_html__( 'Field 2 Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Budget', 'iteck_plg' ),
				'label_block' => true,
                'condition' => [
                    'project_fields' => 'field2'
                ]
			]
		);

        $group_repeater->add_control(
			'field2_text',
			[
				'label' => esc_html__( 'Field 2 Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '$12,000', 'iteck_plg' ),
				'label_block' => true,
                'condition' => [
                    'project_fields' => 'field2'
                ]
			]
		);

        $group_repeater->add_control(
			'field3_title',
			[
				'label' => esc_html__( 'Field 3 Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Estimate', 'iteck_plg' ),
				'label_block' => true,
                'condition' => [
                    'project_fields' => 'field3'
                ]
			]
		);

        $group_repeater->add_control(
			'field3_text',
			[
				'label' => esc_html__( 'Field 3 Text', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '3 Months', 'iteck_plg' ),
				'label_block' => true,
                'condition' => [
                    'project_fields' => 'field3'
                ]
			]
		);

        $group_repeater->add_control(
            'link',
            [
                'label' => __( 'Project Link', 'iteck_plg' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

        $this->add_control(
			'projects_repeater',
			[
				'label' => esc_html__( 'Projects', 'iteck_plg' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $group_repeater->get_controls(),
				'default' => [
					[
						'project_title' => esc_html__( 'Hayden Co. Market Data Analysis', 'iteck_plg' ),
					],
					[
						'project_title' => esc_html__( 'Hayden Co. Market Data Analysis', 'iteck_plg' ),
					],
					[
						'project_title' => esc_html__( 'Hayden Co. Market Data Analysis', 'iteck_plg' ),
					],
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
		$settings = $this->get_settings();
	?>
		<div class="iteck-projects-carousel">
            <div class="container">
                <div class="section-head style-8 mb-80 wow fadeInUp">
                    <h6><?php echo wp_kses_post($settings['section_title']) ?></h6>
                    <h3><?php echo wp_kses_post($settings['section_subtitle']) ?></h3>
                    <div class="arrows">
                        <div class="swiper-button-next">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                        <div class="swiper-button-prev">
                            <i class="bi bi-arrow-left"></i>
                        </div>
                    </div>
                </div>
                <div class="content wow fadeIn">
                    <div class="projects-slider8">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                <?php foreach($settings['projects_repeater'] as $index => $item): ?>
                                    <div class="swiper-slide">
                                        <div class="project-card">
                                            <div class="row align-items-center">
                                                <div class="col-lg-6">
                                                    <div class="img">
                                                        <img src="<?php echo esc_url($item['project_img']['url']) ?>" alt="" class="main-img">
                                                        <div class="tags">
                                                            <?php if(!empty($item['tag1_title'])): ?><a href="<?php echo esc_url($item['tag1_link']['url']) ?>" <?php if ( $item['tag1_link']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['tag1_title']) ?></a><?php endif; ?>
                                                            <?php if(!empty($item['tag2_title'])): ?><a href="<?php echo esc_url($item['tag2_link']['url']) ?>" <?php if ( $item['tag2_link']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['tag2_title']) ?></a><?php endif; ?>
                                                            <?php if(!empty($item['tag3_title'])): ?><a href="<?php echo esc_url($item['tag3_link']['url']) ?>" <?php if ( $item['tag3_link']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['tag3_title']) ?></a><?php endif; ?>
                                                            <?php if(!empty($item['tag4_title'])): ?><a href="<?php echo esc_url($item['tag4_link']['url']) ?>" <?php if ( $item['tag4_link']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['tag4_title']) ?></a><?php endif; ?>
                                                            <?php if(!empty($item['tag5_title'])): ?><a href="<?php echo esc_url($item['tag5_link']['url']) ?>" <?php if ( $item['tag5_link']['is_external'] ) {echo'target="_blank"';} ?>><?php echo wp_kses_post($item['tag5_title']) ?></a><?php endif; ?>
                                                        </div>
                                                        <?php if(!empty($item['animated_img']['url'])): ?>
                                                            <img src="<?php echo esc_url($item['animated_img']['url']) ?>" alt="" class="img-chart slide_up_down">
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="info">
                                                        <?php if(!empty($item['logo_img']['url'])): ?>
                                                            <div class="project-logo">
                                                                <img src="<?php echo esc_url($item['logo_img']['url']) ?>" alt="">
                                                            </div>
                                                        <?php endif; ?>
                                                        <h4 class="title"><?php echo wp_kses_post($item['project_title']) ?></h4>
                                                        <p><?php echo wp_kses_post($item['project_description']) ?></p>
                                                        <div class="proj-det">
                                                            <?php if(!empty($item['field1_title'])): ?>
                                                                <div class="item">
                                                                    <p><?php echo wp_kses_post($item['field1_title']) ?></p>
                                                                    <h6><?php echo wp_kses_post($item['field1_text']) ?></h6>
                                                                </div>
                                                            <?php endif;
                                                            if(!empty($item['field2_title'])): ?>
                                                                <div class="item">
                                                                    <p><?php echo wp_kses_post($item['field2_title']) ?></p>
                                                                    <h6><?php echo wp_kses_post($item['field2_text']) ?></h6>
                                                                </div>
                                                            <?php endif;
                                                            if(!empty($item['field3_title'])): ?>
                                                                <div class="item">
                                                                    <p><?php echo wp_kses_post($item['field3_title']) ?></p>
                                                                    <h6><?php echo wp_kses_post($item['field3_text']) ?></h6>
                                                                </div>
                                                            <?php endif; ?>
                                                            <a href="<?php echo esc_url($item['link']['url']) ?>" <?php if ( $item['link']['is_external'] ) {echo'target="_blank"';} ?> class="icon">
                                                                <i class="bi bi-arrow-right"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
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


