<?php
/**
 * Author:          Stefan Cotitosu <stefan@themeisle.com>
 * Created on:      2019-02-27
 *
 * @package Neve Pro
 */

namespace Neve_Pro\Modules\Blog_Pro\Customizer;

use HFG\Traits\Core;
use Neve\Core\Settings\Mods;
use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Control;
use Neve_Pro\Core\Loader;
use Neve_Pro\Modules\Blog_Pro\Module;
use Neve_Pro\Traits\Sanitize_Functions;

/**
 * Class Blog_Pro
 *
 * @package Neve_Pro\Modules\Blog_Pro\Customizer
 */
class Blog_Pro extends Base_Customizer {
	use Core;
	use Sanitize_Functions;

	/**
	 * The minimum value of some customizer controls is 0 to able to allow usability relative to CSS units.
	 * That can be removed after the https://github.com/Codeinwp/neve/issues/3609 issue is handled.
	 *
	 * That is defined here against the usage of old Neve versions, Base_Customizer class of the stable Neve version already has the RELATIVE_CSS_UNIT_SUPPORTED_MIN_VALUE constant.
	 */
	const RELATIVE_CSS_UNIT_SUPPORTED_MIN_VALUE = 0;

	/**
	 * Holds the section name.
	 *
	 * @var string $section
	 */
	private $section = 'neve_blog_archive_layout';

	/**
	 * Base initialization
	 */
	public function init() {

		parent::init();
		add_filter( 'neve_single_post_elements', array( $this, 'filter_single_post_elements' ) );
		add_filter( 'neve_customizer_control_args', [ $this, 'adjust_tabs' ], 10, 2 );
		add_filter( 'neve_customizer_control_args', [ $this, 'adjust_ordering_inner_controls' ], 10, 2 );
	}

	/**
	 * Adjust the section tabs.
	 *
	 * @param array  $args Control args.
	 * @param string $control_id Control ID.
	 *
	 * @return array
	 */
	public function adjust_tabs( $args, $control_id ) {
		if ( $control_id !== 'neve_blog_archive_layout_tabs' ) {
			return $args;
		}

		if ( ! isset( $args['controls'], $args['controls']['general'], $args['controls']['style'] ) ) {
			return $args;
		}

		$args['controls']['general'] = array_merge(
			$args['controls']['general'],
			array_fill_keys(
				[
					'neve_pagination_type',

					'neve_posts_order',

					'neve_read_more_text',
					'neve_read_more_style',


					'neve_blog_list_image_position',
					'neve_blog_list_image_width',


					'neve_blog_content_alignment',
					'neve_blog_content_vertical_alignment',

					'neve_featured_post_image_position',
					'neve_featured_post_content_align',
					'neve_featured_post_image_align',
				],
				[]
			)
		);

		$args['controls']['style'] = array_merge(
			$args['controls']['style'],
			array_fill_keys(
				[
					'neve_blog_grid_spacing',
					'neve_blog_list_spacing',

					'neve_blog_covers_min_height',
					'neve_blog_covers_overlay_color',

					'neve_blog_show_on_hover',
					'neve_blog_image_hover',

					'neve_featured_post_background',
					'neve_featured_post_padding',
					'neve_featured_post_min_height',
				],
				[]
			)
		);

		return $args;
	}

	/**
	 *  Adjust control slotting in the content ordering control.
	 *
	 * @param array  $args The control args.
	 * @param string $control_id The control ID.
	 *
	 * @return array
	 */
	public function adjust_ordering_inner_controls( $args, $control_id ) {
		if ( $control_id !== 'neve_post_content_ordering' ) {
			return $args;
		}

		if ( ! isset( $args['components'] ) ) {
			return $args;
		}

		if ( isset( $args['components']['thumbnail']['controls'] ) ) {
			$args['components']['thumbnail']['controls'] = array_merge(
				$args['components']['thumbnail']['controls'],
				[
					'neve_blog_image_hover'         => [
						'label'   => esc_html__( 'Hover Effect', 'neve-pro-addon' ),
						'type'    => 'select',
						'choices' => $this->get_blog_image_hover_choices(),
					],
					'neve_blog_list_image_position' => [
						'label'   => esc_html__( 'Image Position', 'neve-pro-addon' ),
						'type'    => 'button-group',
						'choices' => $this->get_image_position_choices(),
					],
					'neve_blog_list_image_width'    => [
						'label' => esc_html__( 'Image Width', 'neve-pro-addon' ),
						'type'  => 'range',
						'attrs' => [
							'min'        => 0,
							'max'        => 100,
							'units'      => [ '%' ],
							'defaultVal' => 35,
						],
					],
				]
			);
		}
		if ( isset( $args['components']['title-meta']['controls'] ) ) {
			$args['components']['title-meta']['controls'] = array_merge(
				$args['components']['title-meta']['controls'],
				[]
			);

			$args['components']['excerpt']['controls'] = array_merge(
				$args['components']['excerpt']['controls'],
				[
					'neve_read_more_style' => [
						'type'    => 'select',
						'label'   => esc_html__( 'Read More Style', 'neve-pro-addon' ),
						'choices' => $this->get_read_more_style_choices(),
					],
					'neve_read_more_text'  => [
						'label' => esc_html__( 'Read More Text', 'neve-pro-addon' ),
						'type'  => 'text',
					],
				]
			);
		}
			return $args;
	}

	/**
	 * Adds controls for the content ordering control in the theme.
	 *
	 * @return void
	 */
	private function add_additional_order_controls() {
		$this->add_control(
			new Control(
				'neve_blog_image_hover',
				array(
					'default'           => 'none',
					'sanitize_callback' => array( $this, 'sanitize_image_hover' ),
				),
				array(
					'label'           => esc_html__( 'Image style', 'neve-pro-addon' ),
					'section'         => $this->section,
					'priority'        => 15,
					'description'     => __( 'Select a hover effect for the post images.', 'neve-pro-addon' ),
					'type'            => Loader::has_compatibility( 'nested_ordering_control' ) ? 'hidden' : 'select',
					'choices'         => $this->get_blog_image_hover_choices(),
					'active_callback' => function () {
						if ( $this->is_list_layout() ) {
							return Mods::get( 'neve_blog_list_image_position', 'left' ) !== 'no';
						}
						return true;
					},
				)
			)
		);


		$this->add_control(
			new Control(
				'neve_blog_list_image_position',
				[
					'sanitize_callback' => function ( $value ) {
						if ( ! in_array( $value, [ 'left', 'no', 'right' ], true ) ) {
							return 'left';
						}

						return $value;
					},
					'default'           => 'left',
				],
				[
					'label'           => esc_html__( 'Image Position', 'neve-pro-addon' ),
					'section'         => $this->section,
					'choices'         => $this->get_image_position_choices(),
					'show_labels'     => true,
					'priority'        => 35,
					'active_callback' => [ $this, 'is_list_layout' ],
					'type'            => Loader::has_compatibility( 'nested_ordering_control' ) ? 'hidden' : 'neve_radio_buttons_control',
				],
				Loader::has_compatibility( 'nested_ordering_control' ) ? null : '\Neve\Customizer\Controls\React\Radio_Buttons'
			)
		);
		$this->add_control(
			new Control(
				'neve_blog_list_image_width',
				[
					'sanitize_callback' => 'absint',
					'transport'         => 'refresh',
					'default'           => 35,
				],
				[
					'label'                 => esc_html__( 'Image Width', 'neve-pro-addon' ),
					'section'               => $this->section,
					'type'                  => Loader::has_compatibility( 'nested_ordering_control' ) ? 'hidden' : 'neve_range_control',
					'input_attrs'           => [
						'min'        => 0,
						'max'        => 100,
						'units'      => [ '%' ],
						'defaultVal' => 35,
					],
					'priority'              => 40,
					'active_callback'       => function () {
						return $this->is_list_layout() && Mods::get( 'neve_blog_list_image_position', 'left' ) !== 'no';
					},
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'template' =>
							'body .nv-non-grid-article.has-post-thumbnail .non-grid-content {
							    width: calc(100% - {{value}}%);
					    	}
					    	body .layout-default .nv-post-thumbnail-wrap, body .layout-alternative .nv-post-thumbnail-wrap {
							    width: {{value}}%;
							    max-width: {{value}}%;
					    	}',
					],

				],
				Loader::has_compatibility( 'nested_ordering_control' ) ? null : '\Neve\Customizer\Controls\React\Range'
			)
		);
	}

	/**
	 * Add customizer section and controls
	 */
	public function add_controls() {
		$this->add_additional_order_controls();
		$this->add_blog_layout_controls();
		$this->add_ordering_content_controls();
		$this->add_read_more_controls();
		$this->add_featured_post_controls();

		if ( Loader::has_compatibility( 'meta_custom_fields' ) ) {
			add_action( 'customize_register', [ $this, 'add_meta_custom_fields' ], PHP_INT_MAX );
		}
	}

	/**
	 * Allow meta controls defined in lite to add more items.
	 */
	public function add_meta_custom_fields() {
		$this->change_customizer_object( 'control', 'neve_blog_post_meta_fields', 'allow_new_fields', 'yes' );
		$this->change_customizer_object( 'control', 'neve_blog_post_meta_fields', 'fields', $this->get_blocked_elements_fields() );
		$this->change_customizer_object( 'control', 'neve_blog_post_meta_fields', 'new_item_fields', $this->get_new_elements_fields() );

		$this->change_customizer_object( 'control', 'neve_single_post_meta_fields', 'allow_new_fields', 'yes' );
		$this->change_customizer_object( 'control', 'neve_single_post_meta_fields', 'fields', $this->get_blocked_elements_fields() );
		$this->change_customizer_object( 'control', 'neve_single_post_meta_fields', 'new_item_fields', $this->get_new_elements_fields() );
	}

	/**
	 * Add blog layout controls.
	 */
	private function add_blog_layout_controls() {
		$this->add_control(
			new Control(
				'neve_pagination_type',
				array(
					'default'           => 'number',
					'sanitize_callback' => array( $this, 'sanitize_pagination_type' ),
				),
				array(
					'label'    => esc_html__( 'Post Pagination', 'neve-pro-addon' ),
					'section'  => $this->section,
					'priority' => 85,
					'type'     => 'select',
					'choices'  => array(
						'number'   => esc_html__( 'Number', 'neve-pro-addon' ),
						'infinite' => esc_html__( 'Infinite Scroll', 'neve-pro-addon' ),
						'jump-to'  => esc_html__( 'Number', 'neve-pro-addon' ) . ' & ' . esc_html__( 'Search Field', 'neve-pro-addon' ),
					),
				)
			)
		);
		$this->add_control(
			new Control(
				'neve_blog_grid_spacing',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'transport'         => 'postMessage',
					'default'           => '{ "mobile": 30, "tablet": 30, "desktop": 30 }',
				],
				[
					'label'                 => esc_html__( 'Grid Spacing', 'neve-pro-addon' ),
					'section'               => $this->section,
					'type'                  => 'neve_responsive_range_control',
					'input_attrs'           => [
						'min'        => 0,
						'max'        => 300,
						'units'      => [ 'px', 'em', 'rem' ],
						'defaultVal' => [
							'mobile'  => 30,
							'tablet'  => 30,
							'desktop' => 30,
							'suffix'  => [
								'mobile'  => 'px',
								'tablet'  => 'px',
								'desktop' => 'px',
							],
						],
					],
					'priority'              => 191,
					'active_callback'       => function () {
						return ! $this->is_list_layout();
					},
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'     => [
							'vars'       => '--gridspacing',
							'suffix'     => 'px',
							'responsive' => true,
							'selector'   => '.posts-wrapper',
						],
						'responsive' => true,
						'template'   =>
							'body .posts-wrapper > article.layout-covers,
                             body .posts-wrapper > article.layout-grid {
							    margin-bottom: {{value}}px;
							    padding-right: calc({{value}}px/2);
							    padding-left: calc({{value}}px/2);
					    	}',
					],
				],
				'\Neve\Customizer\Controls\React\Responsive_Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_blog_list_spacing',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'transport'         => 'postMessage',
					'default'           => '{ "mobile": 60, "tablet": 60, "desktop": 60 }',
				],
				[
					'label'                 => esc_html__( 'List Spacing', 'neve-pro-addon' ),
					'section'               => $this->section,
					'type'                  => 'neve_responsive_range_control',
					'input_attrs'           => [
						'min'        => 0,
						'max'        => 300,
						'units'      => [ 'px', 'em', 'rem' ],
						'defaultVal' => [
							'mobile'  => 60,
							'tablet'  => 60,
							'desktop' => 60,
							'suffix'  => [
								'mobile'  => 'px',
								'tablet'  => 'px',
								'desktop' => 'px',
							],
						],
					],
					'priority'              => 191,
					'active_callback'       => [ $this, 'is_list_layout' ],
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'     => [
							'vars'       => '--spacing',
							'suffix'     => 'px',
							'responsive' => true,
							'selector'   => '.posts-wrapper',
						],
						'responsive' => true,
						'template'   =>
							'body .posts-wrapper .nv-non-grid-article {
							    margin-bottom: {{value}}px;
					    	}',
					],
				],
				'\Neve\Customizer\Controls\React\Responsive_Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_blog_covers_min_height',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'transport'         => 'postMessage',
					'default'           => '{ "mobile": 350, "tablet": 350, "desktop": 350 }',
				],
				[
					'label'                 => esc_html__( 'Card Min Height', 'neve-pro-addon' ),
					'section'               => $this->section,
					'type'                  => 'neve_responsive_range_control',
					'input_attrs'           => [
						'min'        => 0,
						'max'        => 1000,
						'units'      => [ 'px' ],
						'defaultVal' => [
							'mobile'  => 350,
							'tablet'  => 350,
							'desktop' => 350,
							'suffix'  => [
								'mobile'  => 'px',
								'tablet'  => 'px',
								'desktop' => 'px',
							],
						],
					],
					'priority'              => 193,
					'active_callback'       => [ $this, 'is_covers_layout' ],
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'     => [
							'vars'       => '--coverheight',
							'suffix'     => 'px',
							'responsive' => true,
							'selector'   => '.posts-wrapper',
						],
						'responsive' => true,
						'template'   =>
							'body .cover-post .inner {
							    min-height: {{value}}px;
					    	}',
					],
				],
				'\Neve\Customizer\Controls\React\Responsive_Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_blog_covers_overlay_color',
				array(
					'sanitize_callback' => 'neve_sanitize_colors',
					'default'           => 'rgba(0,0,0,0.75)',
					'transport'         => 'postMessage',
				),
				array(
					'label'                 => esc_html__( 'Overlay Color', 'neve-pro-addon' ),
					'section'               => $this->section,
					'priority'              => 194,
					'active_callback'       => [ $this, 'is_covers_layout' ],
					'default'               => 'rgba(0,0,0,0.75)',
					'input_attrs'           => [
						'allow_gradient' => Loader::has_compatibility( 'gradient_picker' ),
					],
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'   => [
							'vars'     => '--overlay',
							'selector' => '.posts-wrapper, .nv-ft-post',
						],
						'template' =>
							'body .cover-post:after {
							background: {{value}};
						}',
					],
				),
				'Neve\Customizer\Controls\React\Color'
			)
		);
	}

	/**
	 * Add ordering controls.
	 */
	private function add_ordering_content_controls() {
		$this->add_control(
			new Control(
				'neve_posts_order',
				array(
					'default'           => 'date_posted_desc',
					'sanitize_callback' => array( $this, 'sanitize_posts_sorting' ),
				),
				array(
					'label'    => esc_html__( 'Order posts by', 'neve-pro-addon' ),
					'section'  => $this->section,
					'priority' => 70,
					'type'     => 'select',
					'choices'  => array(
						'date_posted_desc' => esc_html__( 'Date posted descending', 'neve-pro-addon' ),
						'date_posted_asc'  => esc_html__( 'Date posted ascending', 'neve-pro-addon' ),
						'date_updated'     => esc_html__( 'Date updated', 'neve-pro-addon' ),
					),
				)
			)
		);
		// content alignment
		$align_choices = [
			'left'   => [
				'tooltip' => __( 'Left', 'neve-pro-addon' ),
				'icon'    => 'editor-alignleft',
			],
			'center' => [
				'tooltip' => __( 'Center', 'neve-pro-addon' ),
				'icon'    => 'editor-aligncenter',
			],
			'right'  => [
				'tooltip' => __( 'Right', 'neve-pro-addon' ),
				'icon'    => 'editor-alignright',
			],
		];
		$this->add_control(
			new Control(
				'neve_blog_content_alignment',
				[
					'sanitize_callback' => function ( $value ) {
						if ( ! in_array( $value, [ 'left', 'center', 'right' ], true ) ) {
							return 'left';
						}

						return $value;
					},
					'default'           => 'left',
					'transport'         => 'postMessage',
				],
				[
					'label'                 => esc_html__( 'Content Alignment', 'neve-pro-addon' ),
					'section'               => $this->section,
					'choices'               => $align_choices,
					'show_labels'           => true,
					'priority'              => 75,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'   => [
							'vars'     => '--alignment',
							'selector' => '.posts-wrapper',
						],
						'template' =>
							'body .cover-post .inner, 
                            body .nv-non-grid-article .content  .non-grid-content, 
							body .nv-non-grid-article .content .non-grid-content.alternative-layout-content,
                            body .article-content-col .content, 
                            body .article-content-col .content a, 
                            body .article-content-col .content li {
							    text-align: {{value}};
					    	}
					    	.layout-grid .nv-post-thumbnail-wrap a {
					    	    display: inline-block;
					    	}
					    	',
					],
				],
				'\Neve\Customizer\Controls\React\Radio_Buttons'
			)
		);
		// vertical alignment
		$align_choices = [
			'flex-start' => [
				'tooltip' => __( 'Top', 'neve-pro-addon' ),
				'icon'    => 'verticalTop',
			],
			'center'     => [
				'tooltip' => __( 'Middle', 'neve-pro-addon' ),
				'icon'    => 'verticalMiddle',
			],
			'flex-end'   => [
				'tooltip' => __( 'Bottom', 'neve-pro-addon' ),
				'icon'    => 'verticalBottom',
			],
		];
		$this->add_control(
			new Control(
				'neve_blog_content_vertical_alignment',
				[
					'sanitize_callback' => function ( $value ) {
						if ( ! in_array( $value, [ 'flex-start', 'center', 'flex-end' ], true ) ) {
							return 'flex-end';
						}

						return $value;
					},
					'transport'         => 'postMessage',
					'default'           => 'bottom',
				],
				[
					'label'                 => esc_html__( 'Content Alignment', 'neve-pro-addon' ),
					'section'               => $this->section,
					'show_labels'           => true,
					'choices'               => $align_choices,
					'priority'              => 80,
					'active_callback'       => [ $this, 'is_covers_layout' ],
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'   => [
							'vars'     => '--justify',
							'selector' => '.posts-wrapper',
						],
						'template' =>
							'body .cover-post .inner {
							    justify-content: {{value}};
					    	}',
					],
				],
				'\Neve\Customizer\Controls\React\Radio_Buttons'
			)
		);
	}

	/**
	 * Read More Options
	 */
	public function add_read_more_controls() {
		$rm_style_default = Loader::is_new_user_after_theme_v4() ? 'none' : 'text';

		/*
		 * Read More Text
		 */
		$this->add_control(
			new Control(
				'neve_read_more_text',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => esc_html__( 'Read More', 'neve-pro-addon' ) . ' &raquo;',
				),
				array(
					'priority'        => 190,
					'section'         => $this->section,
					'label'           => esc_html__( 'Read More Text', 'neve-pro-addon' ),
					'type'            => Loader::has_compatibility( 'nested_ordering_control' ) ? 'hidden' : 'text',
					'active_callback' => function () use ( $rm_style_default ) {
						return Mods::get( 'neve_read_more_style', $rm_style_default ) !== 'none';
					},
				)
			)
		);

		/*
		 * Read More Style
		 */
		$this->add_control(
			new Control(
				'neve_read_more_style',
				array(
					'default'           => $rm_style_default,
					'sanitize_callback' => array( $this, 'sanitize_read_more_style' ),
				),
				array(
					'label'    => esc_html__( 'Read More Style', 'neve-pro-addon' ),
					'section'  => $this->section,
					'priority' => 185,
					'type'     => Loader::has_compatibility( 'nested_ordering_control' ) ? 'hidden' : 'select',
					'choices'  => $this->get_read_more_style_choices(),
				)
			)
		);
	}

	/**
	 * Add controls for featured post.
	 */
	public function add_featured_post_controls() {
		if ( ! Loader::has_compatibility( 'featured_post' ) ) {
			return;
		}
		$this->add_control(
			new Control(
				'neve_featured_post_image_position',
				[
					'sanitize_callback' => [ $this, 'sanitize_fp_image_position' ],
					'transport'         => $this->selective_refresh,
					'default'           => 'top',
				],
				[
					'label'                 => esc_html__( 'Featured Post Image Position', 'neve-pro-addon' ),
					'section'               => $this->section,
					'priority'              => 280,
					'choices'               => [
						'left'  => [
							'tooltip' => esc_html__( 'Left', 'neve-pro-addon' ),
							'icon'    => 'align-pull-left',
						],
						'top'   => [
							'tooltip' => esc_html__( 'Top', 'neve-pro-addon' ),
							'icon'    => 'align-full-width',
						],
						'right' => [
							'tooltip' => esc_html__( 'Right', 'neve-pro-addon' ),
							'icon'    => 'align-pull-right',
						],
					],
					'show_labels'           => true,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar' => [
							'vars'       => [
								'--ftposttemplate',
								'--ftpostimgorder',
								'--ftpostcontentorder',
							],
							'valueRemap' => [
								'--ftposttemplate'     => [
									'left'   => '1fr 1.25fr',
									'center' => '1fr',
									'right'  => '1.25fr 1fr',
								],
								'--ftpostimgorder'     => [
									'left'  => '0',
									'right' => '1',
								],
								'--ftpostcontentorder' => [
									'left'  => '1',
									'right' => '0',
								],
							],
							'selector'   => '.nv-ft-post .content',
						],
					],
					'active_callback'       => [ $this, 'is_featured_post_grid_list' ],
				],
				'\Neve\Customizer\Controls\React\Radio_Buttons'
			)
		);

		$this->add_control(
			new Control(
				'neve_featured_post_image_align',
				[
					'sanitize_callback' => [ $this, 'sanitize_fp_image_align' ],
					'transport'         => $this->selective_refresh,
					'default'           => 'center',
				],
				[
					'label'                 => esc_html__( 'Featured Post Image Alignment', 'neve-pro-addon' ),
					'section'               => $this->section,
					'priority'              => 275,
					'choices'               => [
						'top'    => [
							'tooltip' => esc_html__( 'Top', 'neve-pro-addon' ),
							'icon'    => 'verticalTop',
						],
						'center' => [
							'tooltip' => esc_html__( 'Middle', 'neve-pro-addon' ),
							'icon'    => 'verticalMiddle',
						],
						'bottom' => [
							'tooltip' => esc_html__( 'Bottom', 'neve-pro-addon' ),
							'icon'    => 'verticalBottom',
						],
					],
					'show_labels'           => true,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar' => [
							'vars'     => '--ftpostimgalign',
							'selector' => '.nv-ft-post',
						],
					],
					'active_callback'       => [ $this, 'has_fp_img_top' ],
				],
				'\Neve\Customizer\Controls\React\Radio_Buttons'
			)
		);

		$this->add_control(
			new Control(
				'neve_featured_post_content_align',
				[
					'sanitize_callback' => [ $this, 'sanitize_fp_content_position' ],
					'transport'         => $this->selective_refresh,
					'default'           => 'center',
				],
				[
					'label'                 => esc_html__( 'Featured Post Content Alignment', 'neve-pro-addon' ),
					'section'               => $this->section,
					'choices'               => [
						'self-start' => [
							'tooltip' => esc_html__( 'Top', 'neve-pro-addon' ),
							'icon'    => 'verticalTop',
						],
						'center'     => [
							'tooltip' => esc_html__( 'Middle', 'neve-pro-addon' ),
							'icon'    => 'verticalMiddle',
						],
						'self-end'   => [
							'tooltip' => esc_html__( 'Bottom', 'neve-pro-addon' ),
							'icon'    => 'verticalBottom',
						],
					],
					'show_labels'           => true,
					'priority'              => 270,
					'active_callback'       => [ $this, 'is_featured_post' ],
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar' => [
							'vars'     => '--ftpostcontentalign',
							'selector' => '.nv-ft-post',
						],
					],

				],
				'\Neve\Customizer\Controls\React\Radio_Buttons'
			)
		);

		$this->add_control(
			new Control(
				'neve_featured_post_background',
				[
					'sanitize_callback' => 'neve_sanitize_colors',
					'transport'         => $this->selective_refresh,
					'default'           => 'var(--nv-light-bg)',
				],
				[
					'label'                 => esc_html__( 'Featured Post Background Color', 'neve-pro-addon' ),
					'section'               => $this->section,
					'type'                  => 'neve_color_control',
					'priority'              => 245,
					'active_callback'       => [ $this, 'is_featured_post_grid_list' ],
					'input_attrs'           => [
						'allow_gradient' => Loader::has_compatibility( 'gradient_picker' ),
					],
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar' => [
							'vars'     => '--fpbackground',
							'selector' => '.nv-ft-post',
						],
					],
				],
				'Neve\Customizer\Controls\React\Color'
			)
		);

		$this->add_control(
			new Control(
				'neve_featured_post_padding',
				[
					'sanitize_callback' => [ $this, 'sanitize_spacing_array' ],
					'transport'         => $this->selective_refresh,
					'default'           => $this->responsive_padding_default(),
				],
				[
					'label'                 => esc_html__( 'Featured Post Content Padding', 'neve-pro-addon' ),
					'section'               => $this->section,
					'priority'              => 246,
					'input_attrs'           => [
						'units' => [ 'px', 'em', 'rem' ],
						'min'   => 0,
					],
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar' => [
							'vars'       => '--fppadding',
							'selector'   => '.nv-ft-post',
							'responsive' => true,
						],
					],
					'active_callback'       => [ $this, 'is_featured_post' ],
				],
				'\Neve\Customizer\Controls\React\Spacing'
			)
		);

		$this->add_control(
			new Control(
				'neve_featured_post_min_height',
				[
					'sanitize_callback' => 'neve_sanitize_range_value',
					'transport'         => $this->selective_refresh,
					'default'           => '{ "mobile": 300, "tablet": 300, "desktop": 300 }',
				],
				[
					'label'                 => esc_html__( 'Featured Post Height', 'neve-pro-addon' ),
					'section'               => $this->section,
					'priority'              => 247,
					'type'                  => 'neve_responsive_range_control',
					'input_attrs'           => [
						'min'        => self::RELATIVE_CSS_UNIT_SUPPORTED_MIN_VALUE,
						'max'        => 800,
						'units'      => [ 'px', 'em', 'rem' ],
						'defaultVal' => [
							'mobile'  => 300,
							'tablet'  => 300,
							'desktop' => 300,
							'suffix'  => [
								'mobile'  => 'px',
								'tablet'  => 'px',
								'desktop' => 'px',
							],
						],
					],
					'active_callback'       => [ $this, 'is_featured_post' ],
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'     => [
							'vars'       => '--fpminheight',
							'suffix'     => 'px',
							'responsive' => true,
							'selector'   => '.nv-ft-post',
						],
						'responsive' => true,
					],
				],
				'\Neve\Customizer\Controls\React\Responsive_Range'
			)
		);
	}

	/**
	 * Sanitize freatured post image position
	 *
	 * @param string $value Control value.
	 *
	 * @return string
	 */
	public function sanitize_fp_image_position( $value ) {
		if ( ! in_array( $value, [ 'top', 'left', 'right' ], true ) ) {
			return 'top';
		}
		return $value;
	}

	/**
	 * Sanitize freatured post image alignment
	 *
	 * @param string $value Control value.
	 *
	 * @return string
	 */
	public function sanitize_fp_image_align( $value ) {
		if ( ! in_array( $value, [ 'top', 'center', 'bottom' ], true ) ) {
			return 'center';
		}
		return $value;
	}

	/**
	 * Sanitize freatured post content position
	 *
	 * @param string $value Control value.
	 *
	 * @return string
	 */
	public function sanitize_fp_content_position( $value ) {
		if ( ! in_array( $value, [ 'self-start', 'center', 'self-end' ], true ) ) {
			return 'left';
		}
		return $value;
	}

	/**
	 * Sanitize read more button style
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_read_more_style( $value ) {
		$allowed_values = array( 'text', 'primary_button', 'secondary_button' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'none';
		}

		return esc_html( $value );
	}

	/**
	 * Filter single post elements
	 *
	 * @param array $input - controls registered by the theme.
	 *
	 * @return array
	 */
	public function filter_single_post_elements( $input ) {

		$new_controls = array(
			'author-biography' => __( 'Author Biography', 'neve-pro-addon' ),
			'related-posts'    => __( 'Related Posts', 'neve-pro-addon' ),
			'sharing-icons'    => __( 'Sharing Icons', 'neve-pro-addon' ),
		);

		$single_post_elements = array_merge( $input, $new_controls );

		return $single_post_elements;
	}

	/**
	 * Sanitize posts sorting
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_posts_sorting( $value ) {
		$allowed_values = array( 'date_posted_asc', 'date_posted_desc', 'date_updated' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'date_posted_desc';
		}

		return esc_html( $value );
	}

	/**
	 * Active callback for image alignment.
	 *
	 * @return bool
	 */
	public function has_fp_img_top() {
		if ( ! $this->is_featured_post() ) {
			return false;
		}
		if ( ! $this->is_featured_post_grid_list() ) {
			return true;
		}
		$image_position = get_theme_mod( 'neve_featured_post_image_position', 'top' );
		return $image_position === 'top';
	}

	/**
	 * Active callback for featured posts control.
	 *
	 * @return bool
	 */
	public function is_featured_post_grid_list() {
		return $this->is_featured_post() && ( $this->is_grid_layout() || $this->is_list_layout() );
	}

	/**
	 * Check is featured post is enabled.
	 *
	 * @return bool
	 */
	public function is_featured_post() {
		return get_theme_mod( 'neve_enable_featured_post', false );
	}

	/**
	 * Checks if is list layout blog
	 *
	 * @return bool
	 */
	public function is_list_layout() {
		return get_theme_mod( $this->section, 'grid' ) === 'default';
	}

	/**
	 * Checks if is covers layout blog
	 *
	 * @return bool
	 */
	public function is_covers_layout() {
		return get_theme_mod( $this->section, 'grid' ) === 'covers';
	}

	/**
	 * Checks if is grid layout blog
	 *
	 * @return bool
	 */
	public function is_grid_layout() {
		return get_theme_mod( $this->section, 'grid' ) === 'grid';
	}

	/**
	 * Sanitize the pagination type
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_pagination_type( $value ) {
		$allowed_values = array( 'number', 'infinite', 'jump-to' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'number';
		}

		return esc_html( $value );
	}

	/**
	 * Get blog image hover effect choices.
	 */
	private function get_blog_image_hover_choices() {
		return array(
			'none'      => esc_html__( 'None', 'neve-pro-addon' ),
			'zoom'      => esc_html__( 'Zoom', 'neve-pro-addon' ),
			'next'      => esc_html__( 'Next Image', 'neve-pro-addon' ),
			'swipe'     => esc_html__( 'Swipe Next Image', 'neve-pro-addon' ),
			'blur'      => esc_html__( 'Blur', 'neve-pro-addon' ),
			'fadein'    => esc_html__( 'Fade In', 'neve-pro-addon' ),
			'fadeout'   => esc_html__( 'Fade Out', 'neve-pro-addon' ),
			'glow'      => esc_html__( 'Glow', 'neve-pro-addon' ),
			'colorize'  => esc_html__( 'Colorize', 'neve-pro-addon' ),
			'grayscale' => esc_html__( 'Grayscale', 'neve-pro-addon' ),
		);
	}

	/**
	 * Get read more style choices.
	 */
	private function get_read_more_style_choices() {
		return array(
			'none'             => esc_html__( 'None', 'neve-pro-addon' ),
			'text'             => esc_html__( 'Text', 'neve-pro-addon' ),
			'primary_button'   => esc_html__( 'Primary Button', 'neve-pro-addon' ),
			'secondary_button' => esc_html__( 'Secondary Button', 'neve-pro-addon' ),
		);
	}

	/**
	 * Get image position choices.
	 */
	private function get_image_position_choices() { 
		return [
			'left'  => [
				'tooltip' => __( 'Left', 'neve-pro-addon' ),
				'icon'    => 'align-pull-left',
			],
			'no'    => [
				'tooltip' => __( 'No image', 'neve-pro-addon' ),
				'icon'    => 'menu-alt',
			],
			'right' => [
				'tooltip' => __( 'Right', 'neve-pro-addon' ),
				'icon'    => 'align-pull-right',
			],
		];
	}
}
