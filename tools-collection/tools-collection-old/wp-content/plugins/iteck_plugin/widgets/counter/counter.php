<?php
namespace IteckPlugin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.0.0
 */
class Iteck_Counter extends Widget_Base {

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
		return 'iteck-counter';
	}

    //script depend
	public function get_script_depends() { 
        return [ 'counterup' ]; 
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
		return __('Iteck Counter', 'iteck_plg');
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
		return 'eicon-counter';
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
	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Counter Settings', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'title',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Title', 'iteck_plg' ),
				'default' => esc_html__( 'Happy Clients', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'number',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Counter Number', 'iteck_plg' ),
				'default' => 1400,
			]
		);
		
		$this->add_control(
			'show_image',
			[
				'label' => __( 'Image', 'iteck_plg' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'iteck_plg' ),
				'label_off' => __( 'No', 'iteck_plg' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'iteck_plg' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'show_image' => 'yes'
				]
			]
		);
        
        $this->add_control(
			'suffix_type',
			[
				'label' => __( 'Suffix Type', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'icon' => __( 'Icon', 'iteck_plg' ),
					'text' => __( 'Text', 'iteck_plg' ),
				],
				'default' => 'icon',
			]
		);

		$this->add_control(
			'suffix',
			[
				'label' => __( 'Number Suffix', 'iteck_plg' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'placeholder' => __( 'Plus', 'iteck_plg' ),
                'condition' => [
                    'suffix_type' => 'text',
                ]
			]
		);
			
		$this->add_control(
			'suffix_icon',
			[
				'type'    => Controls_Manager::ICONS,
				'label'   => esc_html__( 'Icon', 'iteck_plg' ),
				'default' => array(
			      'value' => 'flaticon-award',
			      'library' => 'fa-solid',
				),
                'condition' => [
                    'suffix_type' => 'icon',
                ]
			]
		);

		$this->add_control(
			'speed',
			[
				'type'    => Controls_Manager::NUMBER,
				'label'   => esc_html__( 'Animation Speed', 'iteck_plg' ),
				'default' => 2000,
				'description' => esc_html__( 'The total duration of the count animation in milisecond eg. 5000', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'steps',
			[
				'type'    => Controls_Manager::NUMBER,
				'label'   => esc_html__( 'Animation Steps', 'iteck_plg' ),
				'default' => 50,
				'description' => esc_html__( 'Counter steps eg. 10', 'iteck_plg' ),
			]
		);
        
        $this->add_control(
			'title_position',
			[
				'label' => __( 'Title Position', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'inline' => __( 'Inline', 'iteck_plg' ),
					'block' => __( 'Block', 'iteck_plg' ),
				],
				'default' => 'inline',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'iteck_plg' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'iteck_plg' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'iteck_plg' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'iteck_plg' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .iteck-counter .num_item' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .iteck-counter .num_item .num' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_number_style',
			[
				'label' => esc_html__( 'Number Style', 'iteck_plg' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'number_color',
			[
				'label' => esc_html__( 'Number Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-counter .num .counter' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_typography',
				'label' => esc_html__( 'Number Typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-counter .num .counter',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_suffix_style',
			[
				'label' => esc_html__( 'Suffix Style', 'iteck_plg' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'suffix_color',
			[
				'label' => esc_html__( 'Suffix Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-counter .num .counter+span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'suffix_typography',
				'label' => esc_html__( 'Suffix Typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-counter .num .counter+span',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title Style', 'iteck_plg' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-counter .inf' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Title Typography', 'iteck_plg' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .iteck-counter .inf',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image Style', 'iteck_plg' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Image Width', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-counter .num_item .img img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_padding',
			[
				'label' => esc_html__('Padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-counter .num_item .img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_margin',
			[
				'label' => esc_html__('Margin', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-counter .num_item .img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
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

		$number = preg_replace("/[^0-9.,]/", "", $settings['number']);
    
        ?>

        <div class="iteck-counter">
            <div class="num_item <?php if($settings['title_position'] == 'block') echo esc_attr('d-block'); ?>">
				<?php if($settings['show_image'] == 'yes'): ?>
				<div class="img">
					<img src="<?php echo esc_url($settings['image']['url']) ?>" alt="">
				</div>
				<div class="number-card">
				<?php endif; ?>
                <div class="num">
                    <span class="counter" data-itecksteps="<?php echo esc_attr($settings['steps']) ?>" data-iteckspeed="<?php echo esc_attr($settings['speed']) ?>">
                        <?php echo $number; ?>
                    </span>
                    <span>
                        <?php if($settings['suffix_type'] == 'icon'): ?>
                            <i class="<?php echo esc_attr($settings['suffix_icon']['value']) ?>"></i>
                        <?php else: ?>
                            <?php echo wp_kses_post($settings['suffix']);
                        endif; ?>
                    </span>
                </div>
                <div class="inf">
                    <?php echo wp_kses_post($settings['title']); ?>
                </div>
				<?php if($settings['show_image'] == 'yes'): ?></div><?php endif; ?>
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