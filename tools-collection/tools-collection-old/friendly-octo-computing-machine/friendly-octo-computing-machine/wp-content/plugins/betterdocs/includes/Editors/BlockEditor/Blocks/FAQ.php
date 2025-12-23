<?php

namespace WPDeveloper\BetterDocs\Editors\BlockEditor\Blocks;

use WPDeveloper\BetterDocs\Editors\BlockEditor\Block;

class FAQ extends Block {
	protected $editor_scripts = [
		'betterdocs-faq',
		'betterdocs-blocks-editor'
	];

	protected $editor_styles = [
		'betterdocs-faq',
		'betterdocs-blocks-editor'
	];

	protected $frontend_styles = [
		'betterdocs-faq'
	];

	protected $frontend_scripts = [
		'betterdocs-faq'
	];

	/**
	 * unique name of block
	 * @return string
	 */
	public function get_name() {
		return 'faq';
	}

	public function get_default_attributes() {
		return [
			'blockId'                 => '',
			'resOption'               => 'Desktop',
			'blockRoot'               => 'better_docs',
			'blockMeta'               => null,
			'faqLayout'               => 'layout-1',
			'faqSectionText'          => 'Frequently Asked Questions',
			'faqSectionTitleTag'      => 'h2',
			'faqGroupTitleTag'        => 'h3',
			'faqSectionTitleColor'    => null,
			'includeFaqGroup'         => '',
			'excludeFaqGroup'         => '',
			'faqGroupTitleColor'      => null,
			'faqGroupTitleHoverColor' => null,
			'faqListColor'            => null,
			'faqContentColor'         => null,
			'faqIconColor'            => null,
			'faqGroupTitleTypography' => null,
			'faqPerPage'              => 9,

			'showButtonIcon'          => true,
			'buttonColor'             => '#528ffe'
		];
	}

	public function render( $attributes, $content ) {

		add_filter( 'betterdocs_header_layout_sequence', [ $this, 'header_sequence' ], 10, 4 );
		$this->views( 'layouts/faq' );
		remove_filter( 'betterdocs_header_layout_sequence', [ $this, 'header_sequence' ], 10 );
	}

	public function get_groups_ids( $json ) {
		$data = json_decode( $json, true );
		$ids  = '';
		if ( $data !== null ) {
			$ids = implode( ',', array_column( $data, 'value' ) );
		}

		return $ids;
	}

	public function view_params() {
		$attributes = &$this->attributes;
		$exclude    = $this->get_groups_ids( $attributes['excludeFaqGroup'] );
		$include    = $this->get_groups_ids( $attributes['includeFaqGroup'] );

		$terms_query = betterdocs()->query->faq_terms_query_args( $include, $exclude );

		return wp_parse_args(
			[
				'enable'           => true,
				'have_posts'       => true,
				'widget'           => $this,
				'layout'           => $attributes['faqLayout'],
				'terms_query_args' => $terms_query,
				'shortcode_attr'   => [
					'group_exclude'               => $exclude,
					'class'                       => 'betterdocs-faq-' . $attributes['faqLayout'] . ' ' . $attributes['blockId'],
					'groups'                      => $include,
					'is_gutenberg'                => true,
					'faq_heading'                 => $attributes['faqSectionText'],
					'faq_section_title_tag'       => $attributes['faqSectionTitleTag'],
					'faq_group_title_tag'         => $attributes['faqGroupTitleTag'],
					'faq_section_title_color'     => $attributes['faqSectionTitleColor'],
					'include_faq_group'           => $this->string_to_array( $attributes['includeFaqGroup'] ),
					'exclude_faq_group'           => $this->string_to_array( $attributes['excludeFaqGroup'] ),
					'faq_group_title_color'       => $attributes['faqGroupTitleColor'],
					'faq_group_title_hover_color' => $attributes['faqGroupTitleHoverColor'],
					'faq_list_color'              => $attributes['faqListColor'],
					'faq_content_color'           => $attributes['faqContentColor'],
					'faq_icon_color'              => $attributes['faqIconColor'],
					'faq_group_title_typography'  => $attributes['faqGroupTitleTypography'],
					'faq_per_page'                => $attributes['faqPerPage'],
					'show_button_icon'            => $attributes['showButtonIcon'],
					'button_icon_position'        => $attributes['faqLayout'] == 'layout-1' || $attributes['faqLayout'] == 'layout-3' || $attributes['faqLayout'] == 'layout-4' ? 'after' : 'before',
					'button_color'                => $attributes['buttonColor']
				]

			]
		);
	}

	public function header_sequence( $_layout_sequence, $layout, $widget_type, $_defined_vars ) {
		$_new_layout_sequence = [
			[
				'class'    => 'betterdocs-category-title-counts',
				'sequence' => [ 'category_title', 'category_counts' ]
			],
			'category_description'
		];

		return $_new_layout_sequence;
	}
}
