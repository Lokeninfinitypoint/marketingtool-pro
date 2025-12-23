<?php
namespace IteckPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Iteck_Offcanvas extends Widget_Base {

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
		return 'iteck-offcanvas';
	}
		//script depend
	public function get_script_depends() { return [ 'iteck-header-offcanvas' ]; }

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
		return __( 'Iteck Offcanvas', 'iteck_plg' );
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
		return 'eicon-apps';
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
				'label' => __( 'Settings','iteck_plg' ),
			]
		);

		$this->add_control(
			'action',
			[
				'label' => __( 'Open or Close', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'open' => __( 'Open', 'iteck_plg' ),
					'close' => __( 'Close', 'iteck_plg' ),
				],
				'default' => 'open',
			]
		);
 
		$this->add_control(
			'image_icon',
			[
				'label' => __( 'Media Type', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'icon' => [
						'title' => __( 'Icon', 'iteck_plg' ),
						'icon' => 'fa fa-smile-o',
					],
					'image' => [
						'title' => __( 'Image', 'iteck_plg' ),
						'icon' => 'fa fa-image',
					],

				],
				'default' => 'icon',
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'iteck_plg' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-bars',
					'library' => 'fa-solid',
				],
				'condition' => [
					'image_icon' => 'icon'
				]
			]
		);

		$this->add_control(
            'image',
            [
                'label' => __( 'Image', 'iteck_plg' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'	=> [
					'image_icon' => 'image',
				]
            ]
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'popup_open_text_settings',
			[
				'label' => __( 'Popup Open Text Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'popup_open_text_typography',
				'selector' => '{{WRAPPER}} .iteck-offcanvas .open-popup .text',
			]
		);

		$this->add_control(
			'popup_open_text_color',
			[
				'label' => esc_html__( 'Popup Open Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-offcanvas .open-popup .text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'label' => esc_html__( 'Item Border', 'iteck_plg' ),
				'selector' => '{{WRAPPER}} .iteck-offcanvas .open-popup',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => __('Item Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-offcanvas .open-popup' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'icon_style',
			[
				'label' => __( 'Icon Style','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_wrapper_size',
			[
				'label' => esc_html__( 'Icon Wrapper Size', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-offcanvas .menu-icon.icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon size', 'iteck_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .iteck-offcanvas .menu-icon.icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-offcanvas .menu-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .iteck-offcanvas .menu-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .iteck-offcanvas.sidepanel .menu-icon span' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_bg',
			[
				'label' => esc_html__( 'Icon Background', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-offcanvas .menu-icon.icon' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => esc_html__( 'Border', 'iteck_plg' ),
				'selector' => '{{WRAPPER}} .iteck-offcanvas .menu-icon.icon',
			]
		);

		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => __('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-offcanvas .menu-icon.icon' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$this->add_inline_editing_attributes( 'title' );
		$this->add_inline_editing_attributes( 'text' );
		?>

		<div class="iteck-offcanvas sidepanel hidden-xs hidden-sm d-flex">
			<div class="menu-icon <?php if($settings['image_icon'] == 'icon') echo 'icon'; ?>">
				<?php if($settings['action'] == 'open'):
					if($settings['image_icon'] == 'image'): ?>
						<img src="<?php echo esc_url($settings['image']['url']); ?>" alt="">
					<?php else: 
						\Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'features-icon' ] );
					endif;
				else: ?>
					<div class="text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
						<?php if($settings['image_icon'] == 'image'): ?>
							<img src="<?php echo esc_url($settings['image']['url']); ?>" alt="">
						<?php else: 
							\Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'features-icon' ] );
						endif; ?>
					</div>
				<?php endif; ?>
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


