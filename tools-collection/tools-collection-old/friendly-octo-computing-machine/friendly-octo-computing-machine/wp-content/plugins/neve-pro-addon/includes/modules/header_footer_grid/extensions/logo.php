<?php
/**
 * Logo class.
 *
 * Holds the Pro features Logo component.
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Extensions;

use HFG\Core\Settings\Manager as SettingsManager;
use HFG\Traits\Core;
use Neve\Core\Styles\Dynamic_Selector;

/**
 * Class Component_Settings
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Customizer
 */
class Logo {
	use Core;

	const FONT_FAMILY = 'font_family';

	/**
	 * Init function.
	 *
	 * @access public
	 * @version 3.1
	 */
	public function init() {
		add_action( 'hfg_component_settings', [ $this, 'add_logo_features' ], 10, 2 );
		add_filter( 'neve_hfg_component_style', [ $this, 'add_css' ], 10, 2 );
	}

	/**
	 * Add customizer options to logo component.
	 *
	 * @param string $component_id Component id.
	 * @param string $section Section id.
	 */
	public function add_logo_features( $component_id, $section ) {
		if ( strpos( $component_id, 'logo' ) === false ) {
			return;
		}

		SettingsManager::get_instance()->add(
			[
				'id'                    => self::FONT_FAMILY,
				'group'                 => $component_id,
				'tab'                   => SettingsManager::TAB_STYLE,
				'transport'             => 'postMessage',
				'type'                  => '\Neve\Customizer\Controls\React\Font_Family',
				'sanitize_callback'     => 'sanitize_text_field',
				'live_refresh_selector' => true,
				'live_refresh_css_prop' => array(
					'cssVar' => [
						'vars'     => [ '--fontfamily', '--h1fontfamily' ],
						'selector' => '.builder-item--' . $component_id,
					],
				),
				'section'               => $section,
				'options'               => [
					'priority'    => 0,
					'input_attrs' => [
						'default_is_inherit' => true,
					],
				],
			]
		);
	}

	/**
	 * Add logo subscribers to the component.
	 *
	 * @param array  $css_array The subscribers.
	 * @param string $component_id The component id.
	 *
	 * @return array
	 */
	public function add_css( $css_array, $component_id ) {
		if ( strpos( $component_id, 'logo' ) === false ) {
			return $css_array;
		}

		$logo_selector = '.builder-item--' . $component_id;

		$css_array[] = [
			Dynamic_Selector::KEY_SELECTOR => $logo_selector,
			Dynamic_Selector::KEY_RULES    => [
				'--h1fontfamily' => [
					Dynamic_Selector::META_KEY => $component_id . '_' . self::FONT_FAMILY,
				],
				'--fontfamily'   => [
					Dynamic_Selector::META_KEY => $component_id . '_' . self::FONT_FAMILY,
				],
			],
		];

		return $css_array;
	}
}
