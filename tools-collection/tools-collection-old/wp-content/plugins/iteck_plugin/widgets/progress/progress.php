<?php
namespace IteckPlugin\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Frontend;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Base;
use Elementor\Group_Control_Background;


/**
 * Elementor icon widget.
 *
 * Elementor widget that displays an icon from over 600+ icons.
 *
 * @since 1.0.0
 */
class Iteck_Progress extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve icon widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'iteck-progress';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve icon widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'iteck Progress', 'iteck_plg' );
	}


	/**
	 * Get widget icon.
	 *
	 * Retrieve icon widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-skill-bar';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the icon widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'iteck-menu-elements' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'progress', 'bar' ];
	}

	//script depend
	public function get_script_depends() { 
		return ['iteck-progress']; 
	}


	/**
	 * Register icon widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_progress',
			[
				'label' => esc_html__( 'Progress Bar', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your title', 'iteck_plg' ),
				'default' => esc_html__( 'My Skill', 'iteck_plg' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'percent',
			[
				'label' => esc_html__( 'Percentage', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 50,
					'unit' => '%',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_progress_style',
			[
				'label' => esc_html__( 'Progress Bar', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'bar_color',
			[
				'label' => esc_html__( 'Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-progress-line .skill-progress .progres' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bar_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-progress-line .skill-progress' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bar_height',
			[
				'label' => esc_html__( 'Height', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .iteck-progress-line .skill-progress' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'bar_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .iteck-progress-line .skills-progress' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_control(
			'divider1',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thin'
			]
		);

		$this->add_control(
			'percentage_pos',
			[
				'label' => __('Percentage Position', 'iteck_plg'),
				'type' => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'x_positioning',
			[
				'label' => __('X Positioning', 'iteck_plg'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'iteck_plg'),
						'icon' => 'eicon-text-align-left',
					],
					'right' => [
						'title' => __('Right', 'iteck_plg'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
			]
		);

		$this->add_responsive_control(
			'icon_x',
			[
				'label' => __( 'Icon X Position', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-progress-line .progres:after' => 'left: {{SIZE}}{{UNIT}}; right: auto !important;',
				],
				'condition' => [
					'x_positioning' => 'left'
				]
			]
		);

		$this->add_responsive_control(
			'icon_x_right',
			[
				'label' => __( 'Icon X (Right) Position', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 500,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-progress-line .progres:after' => 'right: {{SIZE}}{{UNIT}}; left: auto !important;',
				],
				'condition' => [
					'x_positioning' => 'right'
				]
			]
		);

		$this->add_responsive_control(
			'icon_y',
			[
				'label' => __( 'Icon Y position', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-progress-line .progres:after' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'divider2',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thin'
			]
		);

		$this->add_control(
			'percentage_styles',
			[
				'label' => __('Percentage Styles', 'iteck_plg'),
				'type' => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'per_color',
			[
				'label' => esc_html__( 'Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-progress-line .percent' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'per_typography',
				'selector' => '{{WRAPPER}} .iteck-progress-line .percent',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title Style', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-progress-line h5' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .iteck-progress-line h5',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .iteck-progress-line h5',
			]
		);

		$this->end_controls_section();

		
	}

	/**
	 * Render progress widget output on the frontend.
	 * Make sure value does no exceed 100%.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		?>

		<div class="iteck-progress-line">
			<div class="justify-content-between w-100 d-flex align-items-center mb-15">
				<h5 class="fz-14 mb-15"><?php echo __($settings['title'], 'iteck_plg'); ?></h5>
				<span class="percent"> <?php echo esc_html($settings['percent']['size'], 'iteck_plg'); ?> <?php echo __('%', 'iteck_plg'); ?></span>
			</div>
			<div class="skill-progress">
				<div class="progres" data-value="<?php echo esc_attr($settings['percent']['size'], 'iteck_plg') . '%' ?>"></div>
			</div>
		</div>

		<?php
	}
}
