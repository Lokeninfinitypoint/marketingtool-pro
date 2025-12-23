<?php
namespace IteckPlugin\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.1.0 
 */
class Iteck_Logo extends Widget_Base {

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
		return 'iteck-logo';
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
		return __( 'Site Logo/Branding', 'iteck_plg' );
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
		return 'eicon-logo';
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
	protected function register_controls() {
	
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Logo Settings', 'iteck_plg' ),
			]
		);
	
		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'iteck_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'iteck_plg' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'iteck_plg' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'iteck_plg' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'logo_dark_light_style' );

		$this->start_controls_tab(
			'logo_dark',
			[
				'label' => __( 'Dark', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'logo_dark_type',
			[
				'label' => __( 'Logo type', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => __( 'Light', 'iteck_plg' ),
					'dark' => __( 'Dark', 'iteck_plg' ),
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'logo_light',
			[
				'label' => __( 'Light', 'iteck_plg' ),
			]
		);

		$this->add_control(
			'logo_light_type',
			[
				'label' => __( 'Logo type', 'iteck_plg' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dark',
				'options' => [
					'light' => __( 'Light', 'iteck_plg' ),
					'dark' => __( 'Dark', 'iteck_plg' ),
				],
				'frontend_available' => true,
			]
		);
		
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();

		$this->start_controls_section(
			'logo_settings',
			[
				'label' => __( 'Logo Setting', 'iteck_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'logo_padding',
			[
				'label' => __( 'Padding', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .custom-logo a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'logo_margin',
			[
				'label' => __( 'Padding', 'iteck_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .custom-logo a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_width',
			[
				'label' => esc_html__( 'Width', 'iteck_plg' ),
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
					'{{WRAPPER}} .custom-logo a img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_height',
			[
				'label' => esc_html__( 'Height', 'iteck_plg' ),
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
					'{{WRAPPER}} .custom-logo a img' => 'height: {{SIZE}}{{UNIT}} !important;',
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
		global $iteck_theme_settings;
		global $post;  
		if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
			$logo_height= iteck_option('iteck_logo_dim');
			$logo_height = $logo_height['height'];
			$logo_height_css = 'height:'.$logo_height;
			$logo_height_style = !empty($logo_height_css) ? ' style='.$logo_height_css : ''; 

		} else{$logo_height_style ="";}

		?> 
		 
         <div class="custom-logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<!-- Dark mode -->

					<img alt="<?php esc_attr_e ('Logo','iteck'); ?>" class="custom-logo-light" <?php echo esc_html($logo_height_style); ?> src="<?php 
						if (class_exists('ReduxFrameworkPlugin')&& iteck_option('iteck_header_logo_dark') ) {

							// Override redux option by widget setting
							if($settings['logo_dark_type']=='light'){
								$iteck_header_logo_dark = iteck_option('iteck_header_logo_dark');
							}else{
								$iteck_header_logo_dark = iteck_option('iteck_header_logo_white');
							}


							   if(is_array($iteck_header_logo_dark))
								$iteck_header_logo_dark =  $iteck_header_logo_dark['url'];
								echo esc_url ( $iteck_header_logo_dark); 
						} else { 
							echo get_template_directory_uri(); ?>/images/logo.png <?php 
						} ?>"> 
					
					<!-- Light mode -->
					<img alt="<?php esc_attr_e ('Logo','iteck'); ?>" class="custom-logo-dark" <?php echo esc_html($logo_height_style); ?> src="<?php 
						if ( class_exists('ReduxFrameworkPlugin')&& iteck_option('iteck_header_logo_white') ) {
							
							// Override redux option by widget setting
							if($settings['logo_light_type']=='dark'){ 
								$iteck_header_logo_white = iteck_option('iteck_header_logo_white');
							}else{
								$iteck_header_logo_white = iteck_option('iteck_header_logo_dark');
							}


							   if(is_array($iteck_header_logo_white))
								$iteck_header_logo_white =  $iteck_header_logo_white['url']; 
							echo esc_url ( $iteck_header_logo_white); 
						} else { 
							echo get_template_directory_uri(); ?>/images/logo-white.png <?php 
						} ?>">
			</a>
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


