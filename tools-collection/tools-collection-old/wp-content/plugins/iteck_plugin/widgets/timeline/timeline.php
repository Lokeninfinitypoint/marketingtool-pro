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
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Iteck_Timeline extends Widget_Base { 

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
		return 'iteck-timeline';
	}
	
	//script depend
	public function get_script_depends() { return [ 'jquery-swiper','wow','iteck-addons-custom-scripts','iteck-post-list-carousel']; }
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
		return __( 'Iteck Timeline', 'iteck_plg' );
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
		return 'eicon-post-list';
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
				'label' => __( 'Clients Settings.', 'iteck_plg' ),
			]
		);

        $this->add_control(
			'time_line_layout',
			[
				'label' => __( 'Layout', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'vertical' => esc_html__( 'Vertical', 'iteck_plg' ),
					'horizontal' => esc_html__( 'Horizontal', 'iteck_plg' ),
				],
				'default' => 'vertical',
			]
		);

		$this->add_control(
			'show_count',
			[
				'label' => __('Count Show', 'iteck_plg'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Yes', 'iteck_plg'),
				'label_off' => __('No', 'iteck_plg'),
				'return_value' => 'yes',
			]
		);

		$repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'card_title',
            [
                'label' => __('Card Title', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Built New Team',
            ]
        );

        $repeater->add_control(
            'card_year',
            [
                'label' => __('Card Year', 'iteck_plg'),
                'type' => Controls_Manager::TEXT,
                'default' => '2000',
            ]
        );

		$repeater->add_control(
			'card_position',
			[
				'label' => esc_html__( 'Card Position (Vertical)', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'iteck_plg' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'iteck_plg' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'right',
			]
		);

        $repeater->add_control(
            'card_text',
            [
                'label' => __('Card Text', 'iteck_plg'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Sass which stands for Syntactically awesome style sheet is an extension of that enables you to use things like variables.',
            ]
        );

		$repeater->add_control(
			'card_img',
			[
				'label' => esc_html__( 'Card Image (Horizontal)', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

        $this->add_control(
			'cards_repeater',
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
			'section_title_style',
			[
				'label' => esc_html__('Title style', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__('Title Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-timeline .timeline-content .card-info h6' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .iteck-timeline .timeline-content .card-info h6',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__('Text style', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__('Text Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-timeline .timeline-content .card-info p' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .iteck-timeline .timeline-content .card-info p',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_year_style',
			[
				'label' => esc_html__('Year style', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'year_color',
			[
				'label' => esc_html__('Year Color', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-timeline .year' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'year_typography',
				'selector' => '{{WRAPPER}} .iteck-timeline .year',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_horizontal_style',
			[
				'label' => esc_html__('horizontal style', 'iteck_plg'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'time_line_layout' => 'horizontal',
				]
			]
		);

		$this->add_control(
			'item_bg',
			[
				'label' => esc_html__('Item Background', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-timeline.horizontal-style .timeline-content .card-info' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_bg',
			[
				'label' => esc_html__('Image Background', 'iteck_plg'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-timeline.horizontal-style .timeline-content .card-info .icon' => 'background-color: {{VALUE}};',
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

        <div class="iteck-timeline <?php echo esc_attr($settings['time_line_layout'].'-style') ?>">
            <div class="timeline-content">
				<?php if($settings['time_line_layout'] == 'horizontal'): ?>
					<div class="row">
				<?php endif; ?>
                <?php $counter = 01; foreach($settings['cards_repeater'] as $index => $item): ?>
					<?php if($settings['time_line_layout'] == 'vertical'): ?>
                    <div class="timeline-card">
                        <div class="row justify-content-around align-items-center">
							<?php endif; ?>
                            <?php if($item['card_position'] == 'right' && $settings['time_line_layout'] == 'vertical'): ?>
                                <div class="col-lg-4">
                                    <div class="card-year text-lg-end wow left_to_right_apperance ">
                                        <h3><?php echo wp_kses_post($item['card_year']); ?></h3>
                                    </div> 
                                </div>
                            <?php endif; ?>
                            <div class="<?php if($settings['time_line_layout'] == 'vertical') echo esc_attr('col-lg-4'); else echo esc_attr('col-lg-3 col-sm-6'); ?>">
                                <div class="card-info wow left_to_right_apperance ">
									<?php if($settings['time_line_layout'] == 'horizontal'): ?>
										<div class="icon">
											<img src="<?php echo esc_url($item['card_img']['url']); ?>" alt="">
										</div>
									<?php endif; ?>
									<?php if($settings['time_line_layout'] == 'horizontal'): ?><div class="info"><?php endif; ?>
                                    <h6><?php echo wp_kses_post($item['card_title']); ?></h6>
									<?php if(!empty($item['card_text'])): ?>
                                    <p><?php echo wp_kses_post($item['card_text']); ?></p>
									<?php endif;
									if($settings['show_count'] == 'yes'): ?>
                                    <span class="num"><?php if($counter < 10) echo '0'; echo wp_kses_post($counter); ?></span>
									<?php endif; ?>
									<?php if($settings['time_line_layout'] == 'horizontal'): ?></div><?php endif; ?>
									<?php if($settings['time_line_layout'] == 'horizontal'): ?>
                                    	<span class="year"><?php echo wp_kses_post($item['card_year']); ?></span>
									<?php endif; ?>
                                </div> 
                            </div>
                            <?php if($item['card_position'] == 'left' && $settings['time_line_layout'] == 'vertical'): ?>
                                <div class="col-lg-4">
                                    <div class="card-year wow left_to_right_apperance ">
                                        <h3><?php echo wp_kses_post($item['card_year']); ?></h3>
                                    </div> 
                                </div>
                            <?php endif; ?>
						<?php if($settings['time_line_layout'] == 'vertical'): ?>
                        </div>
						<div class="line wow"></div>
                    </div>
					<?php endif; ?>
                <?php $counter++; endforeach; ?>
            </div>
			<?php if($settings['time_line_layout'] == 'horizontal'): ?>
				<div class="progress">
					<div class="progress-bar wow" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
			<?php endif; ?>
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



