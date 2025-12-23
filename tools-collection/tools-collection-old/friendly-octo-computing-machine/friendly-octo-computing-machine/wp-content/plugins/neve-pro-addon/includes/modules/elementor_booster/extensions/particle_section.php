<?php
/**
 * Particles class
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Extensions
 */

namespace Neve_Pro\Modules\Elementor_Booster\Extensions;

use Elementor\Controls_Manager;

/**
 * Class Particle_Section
 */
class Particle_Section {

	/**
	 * Particle_Section constructor.
	 */
	public function __construct() {
		add_action( 'elementor/frontend/section/before_render', array( $this, 'before_render' ) );
		add_action(
			'elementor/element/section/section_layout/after_section_end',
			array(
				$this,
				'register_controls',
			),
			10
		);
		add_action( 'elementor/frontend/section/after_render', array( $this, 'after_render' ) );
	}

	/**
	 * Register Particles Controls.
	 *
	 * @param Object $element Elementor instance.
	 */
	public function register_controls( $element ) {

		$element->start_controls_section(
			'neb_particles_section',
			[
				'label' => __( 'Particles', 'neve-pro-addon' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);

		$element->add_control(
			'neb_particle_switch',
			[
				'label'              => __( 'Enable Particles', 'neve-pro-addon' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_particle_area_zindex',
			[
				'label'              => __( 'Z-index', 'neve-pro-addon' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => - 1,
				'condition'          => [
					'neb_particle_switch' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_particle_theme_from',
			[
				'label'              => __( 'Theme Source', 'neve-pro-addon' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => [
					'presets' => [
						'title' => __( 'Defaults', 'neve-pro-addon' ),
						'icon'  => 'fa fa-list',
					],
					'custom'  => [
						'title' => __( 'Custom', 'neve-pro-addon' ),
						'icon'  => 'fa fa-edit',
					],
				],
				'condition'          => [
					'neb_particle_switch' => 'yes',
				],
				'default'            => 'presets',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_particle_preset_themes',
			[
				'label'              => esc_html__( 'Preset Themes', 'neve-pro-addon' ),
				'type'               => Controls_Manager::SELECT,
				'label_block'        => true,
				'options'            => [
					'default'  => __( 'Default', 'neve-pro-addon' ),
					// translators: particle preset theme name.
					'nasa'     => __( 'Nasa', 'neve-pro-addon' ),
					// translators: particle preset theme name.
					'bubble'   => __( 'Bubble', 'neve-pro-addon' ),
					// translators: particle preset theme name.
					'snow'     => __( 'Snow', 'neve-pro-addon' ),
					// translators: particle preset theme name.
					'nyan_cat' => __( 'Nyan Cat', 'neve-pro-addon' ),
				],
				'default'            => 'default',
				'condition'          => [
					'neb_particle_theme_from' => 'presets',
					'neb_particle_switch'     => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_particles_custom_style',
			[
				'label'              => __( 'Custom Style', 'neve-pro-addon' ),
				'type'               => Controls_Manager::TEXTAREA,
				'description'        =>
					sprintf(
						/* translators: %s is a link with label 'Here'.*/
						__( 'You can generate custom particles JSON code from %s. Simply just past the JSON code above.', 'neve-pro-addon' ),
						sprintf(
							'<a href="http://vincentgarreau.com/particles.js/#default" target="_blank">%s</a>',
							__( 'Here', 'neve-pro-addon' )
						)
					),
				'condition'          => [
					'neb_particle_theme_from' => 'custom',
					'neb_particle_switch'     => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_reduced_motion_switch',
			[
				'label'              => sprintf(
					/* translators: %s is a link with label 'reduce motion'. */
					__( 'Disable effect on %s devices', 'neve-pro-addon' ),
					sprintf(
						'<a target="_blank" href="https://a11y-101.com/development/reduced-motion">%s</a>',
						__( 'reduce motion', 'neve-pro-addon' )
					)
				),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);

		$element->end_controls_section();

	}

	/**
	 * Before section render.
	 *
	 * @param Object $element Elementor instance.
	 */
	public function before_render( $element ) {

		$settings = $element->get_settings();

		if ( $settings['neb_particle_switch'] === 'yes' ) {
			wp_enqueue_script( 'neb-particles-script' );
			$element->add_render_attribute(
				'_wrapper',
				[
					'id' => 'neb-section-particles-' . $element->get_id(),
				]
			);
		}

	}

	/**
	 * After section render.
	 *
	 * @param Object $element Elementor instance.
	 */
	public function after_render( $element ) {

		$data     = $element->get_data();
		$settings = $element->get_settings_for_display();
		$type     = $data['elType'];
		$zindex   = ! empty( $settings['neb_particle_area_zindex'] ) ? $settings['neb_particle_area_zindex'] : 0;

		if ( ( 'section' === $type ) && ( $element->get_settings( 'neb_particle_switch' ) === 'yes' ) ) {
			?>
			<style>
				.elementor-element-<?php echo esc_attr( $element->get_id() ); ?>.neb-particles-section > canvas {
					z-index: <?php echo esc_attr( $zindex ); ?>;
					position: absolute;
					top: 0;
				}
			</style>
			<?php
		}
	}

}
