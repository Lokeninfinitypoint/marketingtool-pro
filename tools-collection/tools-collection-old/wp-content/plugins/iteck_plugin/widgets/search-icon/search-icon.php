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
class Iteck_Search_Icon extends Widget_Base {

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
		return 'iteck-search-icon';
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
		return esc_html__( 'Iteck Search Icon', 'iteck_plg' );
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
		return 'eicon-search';
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
		return [ 'search', 'icon', 'link' ];
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
			'section_icon',
			[
				'label' => esc_html__( 'Icon', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'search_style',
			[
				'label'   => __('Search Form Style', 'iteck_plg'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'icon' =>  __('Icon', 'iteck_plg'),
					'field' => __('Field', 'iteck_plg'),
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
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'iteck_plg' ),
						'icon' => 'eicon-text-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'iteck_plg' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'search_style' => [
					'search_style' => 'field'
				]
			]
		);

		$this->add_control(
			'place_holder_text',
			[
				'label' => __('Place Holder Text', 'iteck_plg'),
				'type' => Controls_Manager::TEXT,
				'default' => __('Type search keyword...', 'iteck_plg'),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'icon_style',
			[
				'label' => __( 'Style Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'iteck_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .itech-search-icon .search-icon-header a i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .itech-search-icon .search-icon-header a svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .itech-search-icon .search-icon-header a i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .itech-search-icon .search-icon-header a svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Color Hover', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .itech-search-icon .search-icon-header a:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .itech-search-icon .search-icon-header a:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'show_separator',
			[
				'label'         => __( 'Show separator', 'iteck_plg' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'iteck_plg' ),
				'label_off'     => __( 'No', 'iteck_plg' ),
				'return_value'  => 'yes',
				'default'  		=> 'yes',
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => esc_html__( 'Separator Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .itech-search-icon .search-icon-header a.search:after' => 'background: {{VALUE}};',
					'{{WRAPPER}} .custom-absolute-menu .search-icon-header a.search:after' => 'background: {{VALUE}};',
				],
				'condition' => [
					'show_separator' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'field_style',
			[
				'label' => __( 'Field Setting','iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'field_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-search-field .searchform .searchsubmit svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .iteck-search-field .searchform .searchsubmit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_text',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-search-field .searchform input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_text_placeholder',
			[
				'label' => esc_html__( 'Text Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-search-field .searchform input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_bg',
			[
				'label' => esc_html__( 'Background Color', 'iteck_plg' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .iteck-search-field .searchform' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'field_padding',
			[
				'label' => __('padding', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-search-field .searchform' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'field_border',
				'label' => esc_html__( 'Border', 'iteck_plg' ),
				'selector' => '{{WRAPPER}} .iteck-search-field .searchform',
			]
		);

		$this->add_responsive_control(
			'field_border_radius',
			[
				'label' => __('Border Radius', 'iteck_plg'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .iteck-search-field .searchform' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render icon widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();?>
		<?php if($settings['search_style'] == 'icon'): ?>
			<div class="iteck-search-icon">
				<div class="search-icon-header" > <!-- hidden-xs hidden-sm -->
					<a class="search <?php if($settings['show_separator'] != 'yes') echo 'hide'; ?>"  href="#">
						<?php \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'features-icon' ] ); ?>
					</a>
					<div class="black-search-block">
						<div class="black-search-table">
							<div class="black-search-table-cell">
								<div>
									<?php $iteck_unique_id = iteck_unique_id( 'search-form-' ); ?>
									<form role="search" method="get" id="<?php echo esc_attr( $iteck_unique_id ); ?>" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
										<input type="search" class="focus-input" placeholder="<?php echo esc_attr__($settings['place_holder_text'],'iteck'); ?>" value="<?php get_search_query()?>" name="s">
										<input type="submit" class="searchsubmit" value="">
									</form>
								</div>
							</div>
						</div>
						<div class="close-black-block"><a href="#"><i class="fa fa-times"></i></a></div>
					</div>
				</div>
			</div>
		<?php else: ?>
			<div class="iteck-search-field">
				<?php $iteck_unique_id = iteck_unique_id( 'search-form-' ); ?>
				<form role="search" method="get" id="<?php echo esc_attr( $iteck_unique_id ); ?>" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php if($settings['icon_position'] == 'left'): ?>	
						<button type="submit" class="searchsubmit">
							<?php \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'features-icon' ] ); ?>
						</button>
					<?php endif; ?>
					<input type="search" placeholder="<?php echo esc_attr__($settings['place_holder_text'],'iteck'); ?>" value="<?php get_search_query()?>" name="s">
					<?php if($settings['icon_position'] == 'right'): ?>	
						<button type="submit" class="searchsubmit right">
							<?php \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true', 'class' => 'features-icon' ] ); ?>
						</button>
					<?php endif; ?>
				</form>
			</div>
		<?php endif; ?>
	<?php
	}

	/**
	 * Render icon widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {}
}
