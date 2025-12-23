<?php
/**
 * Template used for component rendering wrapper.
 *
 * Name:    Header Footer Grid
 *
 * @since 1.2.8
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Templates;

use Neve_Pro\Modules\Header_Footer_Grid\Components\Custom_Layout;

$current_layout = \HFG\component_setting( Custom_Layout::POST_ID );

if ( is_customize_preview() && $current_layout === 'none' ) {
	$notice_html  = '<p style="margin-bottom:0">';
	$notice_html .= sprintf(
		/* translators: %1$s - Custom Layouts, %2$s: Individual Custom Layout */
		esc_html__( 'You have to activate the %1$s module from the theme options page and create an %2$s.', 'neve-pro-addon' ),
		'<strong>' . esc_html__( 'Custom Layouts', 'neve-pro-addon' ) . '</strong>',
		'<strong>' . esc_html__( 'Individual Custom Layout', 'neve-pro-addon' ) . '</strong>'
	);
	$notice_html .= '</p>';
	echo wp_kses(
		$notice_html,
		array(
			'p'      => array(
				'style' => true,
			),
			'strong' => array(),
		)
	);
} else {
	if ( $current_layout !== 'none' ) {
		do_action( 'neve_do_individual', $current_layout );
	}
}
