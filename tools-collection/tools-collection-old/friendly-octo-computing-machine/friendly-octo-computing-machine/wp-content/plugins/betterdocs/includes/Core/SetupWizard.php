<?php

namespace WPDeveloper\BetterDocs\Core;

use WPDeveloper\BetterDocs\Utils\Base;
use WPDeveloper\BetterDocs\Admin\Builder\Rules;
use WPDeveloper\BetterDocs\Admin\Builder\GlobalFields;

class SetupWizard extends Base {
	private $settings;
	public function __construct( Settings $settings ) {
		$this->settings = $settings;

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
	}

	public function enqueue( $hook ) {
		if ( $hook !== 'betterdocs_page_betterdocs-setup' ) {
			return;
		}

		betterdocs()->assets->enqueue( 'betterdocs-quick-setup', 'admin/js/quick-setup.js' );
		betterdocs()->assets->localize( 'betterdocs-quick-setup', 'betterdocsQuickSetup', GlobalFields::normalize( $this->quickbuilder_setup() ) );
		betterdocs()->assets->enqueue( 'betterdocs-sweetalert', 'vendor/js/sweetalert.min.js', [] );
		betterdocs()->assets->enqueue( 'betterdocs-icons', 'admin/btd-icon/style.css' );
		betterdocs()->assets->enqueue( 'betterdocs-setup-wizard-qb-css', 'admin/css/quick-setup.css' );
		betterdocs()->assets->enqueue( 'betterdocs-setup-wizard-new-css', 'admin/css/setup-wizard.css' );
		betterdocs()->assets->enqueue( 'betterdocs-icons', 'admin//style.css' );
		betterdocs()->assets->enqueue( 'betterdocs-setup-wizard-default-js', 'admin/js/setup-wizard.js', [ 'jquery', 'betterdocs-sweetalert' ] );

		// Localize the script with new data
		betterdocs()->assets->localize(
			'betterdocs-setup-wizard',
			'bdquicksetup',
			[
				'finish_txt'    => __( 'Finish', 'betterdocs' ),
				'next_txt'      => __( 'Next', 'betterdocs' ),
				'customizerurl' => $this->customizer_settings_url(),
				'docspageurl'   => $this->docs_page_url(),
				'currentslug'   => $this->settings->get( 'docs_slug' ),
				'redirecturl'   => admin_url( '/admin.php?page=betterdocs-settings' )
			]
		);
	}

	public function normalize_options( $options ) {
		return GlobalFields::normalize_fields( $options );
	}

	public function get_pages() {
		$_pages = betterdocs()->query->get_posts(
			[
				'post_type'      => 'page',
				'numberposts'    => -1,
				'post_status'    => 'publish',
				'posts_per_page' => -1
			]
		);

		$__pages = [];

		if ( ! empty( $_pages ) ) {
			$__pages[0] = __( 'Select a Page', 'betterdocs' );
			foreach ( $_pages->posts as $page ) {
				$__pages[ $page->ID ] = esc_html( $page->post_title );
			}
		}

		return $__pages;
	}

	public function quickbuilder_setup() {
		$existing_plugins = betterdocs()->kbmigration->knowledge_base_plugins();
		$quick_setup      = [
			'id'            => 'betterdocs_quick_setup_metabox_wrapper',
			'title'         => __( 'Betterdocs Quick Setup', 'betterdocs' ),
			'object_types'  => [ 'betterdocs' ],
			'context'       => 'normal',
			'priority'      => 'high',
			'show_header'   => false,
			'tab_number'    => true,
			'is_pro_active' => betterdocs()->is_pro_active(),
			'logoURL'       => betterdocs()->assets->icon( 'betterdocs-icon.svg', true ),
			'layout'        => 'vertical',
			'values'        => betterdocs()->is_pro_active() ? array_merge( betterdocs()->settings->get_all(), [ 'enable_credit' => true ] ) : array_merge( betterdocs()->settings->get_all(), [ 'enable_credit' => true ], $this->pro_settings_default_values() ),
			'config'        => [
				'save_locally'    => true,
				'save'            => true,
				'active'          => 'getting-started',
				'sidebar'         => false,
				'title'           => false,
				'tab_number'      => true,
				'clickable'       => false,
				'completionTrack' => true,
				'content_heading' => [],
				'step'            => [
					'show'    => true,
					'buttons' => [
						'skip'                  => __( 'Skip This Step', 'betterdocs' ),
						'prev'                  => [
							'name'       => __( 'Previous', 'betterdocs' ),
							'type'       => 'customize',
							'customName' => __( 'Previous', 'betterdocs' ),
							'condition'  => 'getting-started',
						],
						'start'                 => [
							'name'       => 'Start',
							'type'       => 'customize',
							'customName' => __( 'Proceed to Next Step', 'betterdocs' ),
							'condition'  => 'getting-started',
							'ajax'       => [
								'on'       => 'click',
								'api'      => '/betterdocs/v1/plugin_insights',
								'data'     => [
									'product_list' => 'betterdocs'
								],
								'hideSwal' => true
							]
						],
						'next'                  => [
							'name'       => 'Next',
							'type'       => 'customize',
							'customName' => __( 'Next', 'betterdocs' ),
							'condition'  => 'getting-started',
						],
						'quick-builder-publish' => [
							'name'   => 'quick-builder-publish',
							'type'   => 'action',
							'action' => 'btd_quick_build_launch'
						]
					],
					'rules'   => Rules::is( 'config.active', 'getting-started', true )
				]
			],
			'submit'        => [
				'show' => false
			],
			'tabs'          => apply_filters(
				'betterdocs_quick_setup_tabs',
				[
					'getting-started' => apply_filters(
						'betterdocs_quick_setup_tab_getting_started',
						[
							'id'       => 'getting-started',
							'label'    => __( 'Getting Started', 'betterdocs' ),
							'classes'  => 'getting-started',
							'priority' => 10,
							'fields'   => [
								'getting_started_header' => [
									'name'        => 'getting_started_header',
									'type'        => 'header',
									'title'       => __( 'Quick Launch', 'betterdocs' ),
									'direction'   => 'column',
									'description' => __( 'Start your Knowledge Base configuration process with an easy-to-follow setup wizard.', 'betterdocs' ),
									'icon'        => '<img src="' . betterdocs()->assets->icon( 'icons/rocket.svg', true ) . '"/>',
									'priority'    => 1
								],
								'betterdocs-quick-setup-start' => [
									'name'      => 'betterdocs-quick-setup-start',
									'type'      => 'section',
									'priority'  => 2,
									'showSteps' => true,
									'fields'    => [
										'betterdocs-quick-setup-start-collapse' => [
											'name'     => 'betterdocs-quick-setup-start-collapse',
											'type'     => 'collapse',
											'priority' => 2,
											'label'    => __( 'By clicking this button, you are allowing this app to collect your information.', 'betterdocs' ),
											'collapse_title' => __( 'What We Collect?', 'betterdocs' ),
											'collapse_message' => __( 'We collect non-sensitive diagnostic data and plugin usage information. Your site URL, WordPress & PHP version, plugins & themes and email address to send you the discount coupon. This data lets us make sure this plugin always stays compatible with the most popular plugins and themes. No spam, we promise.', 'betterdocs' )
										]
									]
								]
							]
						]
					),

					'setup-page'      => apply_filters(
						'betterdocs_quick_setup_tab_setup_page',
						[
							'id'       => 'setup-page',
							'label'    => __( 'Setup Page', 'betterdocs' ),
							'classes'  => 'setup-page',
							'priority' => 30,
							'fields'   => [
								'setup_page_header' => [
									'name'        => 'setup_page_header',
									'type'        => 'header',
									'title'       => __( 'Page Setup Magic', 'betterdocs' ),
									'direction'   => 'row',
									'description' => __( 'Configure the structure and layout of your documentation pages to match your preferences for an organized Knowledge Base.', 'betterdocs' ),
									'icon'        => '<img src="' . betterdocs()->assets->icon( 'icons/content-setting.svg', true ) . '"/>',
									'priority'    => 1
								],
								'betterdocs-quick-setup-fields' => [
									'name'     => 'betterdocs-quick-setup-fields',
									'type'     => 'section',
									'priority' => 2,
									'fields'   => [
										'builtin_doc_page' => [
											'name'     => 'builtin_doc_page',
											'type'     => 'toggle',
											'label'    => __( 'Built-in Documentation Page', 'betterdocs' ),
											'enable_disable_text_active' => true,
											'default'  => 1,
											'priority' => 1,
											'label_subtitle' => __( 'If you disable root slug for KB Archives, your individual knowledge base URL will be like this: https://example.com/knowledgebase-1', 'betterdocs' )
										],
										'docs_page'        => [
											'name'     => 'docs_page',
											'label'    => __( 'Docs Page', 'betterdocs' ),
											'type'     => 'select',
											'default'  => 0,
											'priority' => 2,
											'search'   => true,
											'options'  => $this->normalize_options( $this->get_pages() ),
											'label_subtitle' => __( 'You will need to insert BetterDocs Shortcode inside the page. This page will be used as docs permalink.', 'betterdocs' ),
											'rules'    => Rules::is( 'builtin_doc_page', false )
										],
										'breadcrumb_doc_title' => [
											'name'     => 'breadcrumb_doc_title',
											'type'     => 'text',
											'label'    => __( 'Documentation Page Title', 'betterdocs' ),
											'default'  => __( 'Docs', 'betterdocs' ),
											'priority' => 3,
											'rules'    => Rules::is( 'builtin_doc_page', true )
										],
										'docs_slug'        => [
											'name'     => 'docs_slug',
											'type'     => 'text',
											'label'    => __( 'BetterDocs Root Slug', 'betterdocs' ),
											'default'  => 'docs',
											'priority' => 4,
											'rules'    => Rules::is( 'builtin_doc_page', true )
										],
										'permalink_structure' => [
											'name'     => 'permalink_structure',
											'type'     => 'permalink_structure',
											'label'    => __( 'Single Docs Permalink', 'betterdocs' ),
											'default'  => PostType::permalink_structure(),
											'priority' => 4,
											'tags'     => $this->normalize_options(
												[
													'%doc_category%'   => '%doc_category%',
													'%knowledge_base%' => '%knowledge_base%'
												]
											),
											'label_subtitle' => __( 'Make sure to keep Docs Root Slug in the Single Docs Permalink. You are not able to keep it blank. You can use the available tags from below.', 'betterdocs' )
										],

										'enable_glossaries' => [
											'name'     => 'enable_glossaries',
											'type'     => 'toggle',
											'label'    => __( 'Show Glossary', 'betterdocs' ),
											'label_subtitle' => __( 'Enable the glossary feature to allow users to look up definitions for terms used within your encyclopedia or glossaries themselves.', 'betterdocs' ),
											'enable_disable_text_active' => true,
											'default'  => false,
											'priority' => 5,
											'is_pro'   => true
										],
										'enable_encyclopedia' => [
											'name'     => 'enable_encyclopedia',
											'type'     => 'toggle',
											'label'    => __( 'Built-in Encyclopedia Page', 'betterdocs' ),
											'enable_disable_text_active' => true,
											'default'  => false,
											'priority' => 6,
											'is_pro'   => true
										],
										'enable_credit'    => [
											'name'     => 'enable_credit',
											'type'     => 'toggle',
											'label'    => __( 'Show Powered by BetterDocs', 'betterdocs' ),
											'enable_disable_text_active' => true,
											'default'  => true,
											'priority' => 7
										],
										'enable_faq_schema' => [
											'name'     => 'enable_faq_schema',
											'type'     => 'toggle',
											'label'    => __( 'FAQ Schema', 'betterdocs' ),
											'enable_disable_text_active' => true,
											'default'  => '',
											'priority' => 8
										],
										'advance_search'   => apply_filters(
											'betterdocs_advance_search_settings',
											[
												'name'     => 'advance_search',
												'type'     => 'toggle',
												'label'    => __( 'Advanced Search', 'betterdocs' ),
												'enable_disable_text_active' => true,
												'default'  => true,
												'priority' => 9,
												'is_pro'   => true
											]
										),
										'enable_disable'   => apply_filters(
											'betterdocs_setup_wizard_instant_answer',
											[
												'name'     => 'enable_disable',
												'type'     => 'toggle',
												'priority' => 10,
												'label'    => __( 'Instant Answer', 'betterdocs' ),
												'enable_disable_text_active' => true,
												'default'  => false,
												'is_pro'   => true
											]
										),
									]
								]
							]
						]
					),
					'create-content'  => apply_filters(
						'betterdocs_quick_setup_tab_create-content',
						[
							'id'       => 'create-content',
							'label'    => __( 'Create Content', 'betterdocs' ),
							'classes'  => 'create-content',
							'priority' => 40,
							'fields'   => [
								'create_content_header' => [
									'name'        => 'create_content_header',
									'type'        => 'header',
									'title'       => __( 'Content Crafting', 'betterdocs' ),
									'direction'   => 'row',
									'description' => __( 'Craft categories & articles for your Knowledge Base to efficiently organize and manage your repository with respective categories.', 'betterdocs' ),
									'icon'        => '<img src="' . betterdocs()->assets->icon( 'icons/create-content.svg', true ) . '"/>',
									'priority'    => 1
								],
								'create_content_video'  => [
									'name'     => 'create_content_video',
									'type'     => 'image',
									'media'    => [
										'url' => betterdocs()->assets->icon( 'setup-wizard/DocCreate.gif', true )
									],
									'priority' => 2
								]
							]
						]
					),
					'customize'       => apply_filters(
						'betterdocs_quick_setup_tab_customize',
						[
							'id'       => 'customize',
							'label'    => __( 'Customize', 'betterdocs' ),
							'classes'  => 'customize',
							'priority' => 50,
							'fields'   => [
								'customize_header' => [
									'name'        => 'customize_header',
									'type'        => 'header',
									'title'       => __( 'Style Your Documentation', 'betterdocs' ),
									'direction'   => 'row',
									'description' => __( 'Personalize the appearance of your documentation page, articles, and archive pages using the power of this Customizer.', 'betterdocs' ),
									'icon'        => '<img src="' . betterdocs()->assets->icon( 'icons/customize.svg', true ) . '"/>',
									'priority'    => 1
								],
								'customize_video'  => [
									'name'     => 'customize_video',
									'type'     => 'image',
									'media'    => [
										'url' => betterdocs()->assets->icon( 'setup-wizard/Customizer.gif', true )
									],
									'priority' => 2
								]
							]
						]
					),
					'finalize'        => apply_filters(
						'betterdocs_quick_setup_tab_finalize',
						[
							'id'       => 'finalize',
							'label'    => __( 'Finalize', 'betterdocs' ),
							'classes'  => 'finalize',
							'priority' => 60,
							'fields'   => [
								'finalize_header' => [
									'name'        => 'finalize_header',
									'type'        => 'header',
									'title'       => __( 'Congratulations!', 'betterdocs' ),
									'direction'   => 'row',
									'description' => __( 'Your documentation page is now ready for use. Enrich it with more articles to ensure proper categorization and valuable resources.', 'betterdocs' ),
									'icon'        => '<img src="' . betterdocs()->assets->icon( 'icons/thumbs-up.svg', true ) . '"/>',
									'priority'    => 1
								],
								'finalize_video'  => [
									'name'     => 'finalize_video',
									'type'     => 'image',
									'media'    => [
										'url' => betterdocs()->assets->icon( 'setup-wizard/ThankYou.gif', true )
									],
									'priority' => 2
								]
							]
						]
					)
				]
			)
		];

		if ( $existing_plugins ) {
			$quick_setup['tabs']['migration'] = apply_filters(
				'betterdocs_quick_setup_tab_migration',
				[
					'id'       => 'migration',
					'label'    => __( 'Migration', 'betterdocs' ),
					'classes'  => 'migration',
					'priority' => 20,
					'fields'   => [
						'migration_header'               => [
							'name'        => 'migration_header',
							'type'        => 'header',
							'title'       => __( 'Migration', 'betterdocs' ),
							'direction'   => 'column',
							'description' => __( 'We detected another Knoledge Base Plugin installed in this site. For BetterDocs to work efficiently, we will migrate the data from the plugin listed below, and deactivate the plugins, to avoid conflict.', 'betterdocs' ),
							'icon'        => '<img src="' . betterdocs()->assets->icon( 'icons/migration.svg', true ) . '"/>',
							'priority'    => 1,
						],
						'betterdocs-quick-setup-migrate' => [
							'name'     => 'betterdocs-quick-setup-migrate',
							'type'     => 'section',
							'priority' => 2,
							'fields'   => [
								'migration_step' => [
									'name'     => 'migration_step',
									'type'     => 'migration',
									'kb'       => $existing_plugins[0][0],
									'label'    => sprintf(
										/* translators: %s is the name of the plugin being migrated. */
										__( 'Migrate %s', 'betterdocs' ),
										$existing_plugins[0][1]
									),
									'priority' => 10
								]
							]
						]
					]
				]
			);
		}

		return $quick_setup;
	}

	public function views() {
		betterdocs()->views->get( 'admin/setup-wizard' );
	}

	public function customizer_settings_url() {
		$query = [
			'autofocus[panel]' => 'betterdocs_customize_options',
			'return'           => admin_url( 'edit.php?post_type=docs' )
		];

		$docs_slug = $this->settings->get( 'docs_slug', 'docs' );
		if ( $docs_slug ) {
			$query['url'] = site_url( '/' . $docs_slug );
		}
		$customizer_link = add_query_arg( $query, admin_url( 'customize.php' ) );

		return esc_url( $customizer_link );
	}

	public function docs_page_url() {
		return esc_url( site_url( '/' . $this->settings->get( 'docs_slug', 'docs' ) ) );
	}

	/**
	 * Call This Function As Helper, When Pro Is Deactivated, To Be Used As Settings Default Values, When Betterdocs Pro Is Deactivated
	 *
	 * @return array
	 */
	public function pro_settings_default_values() {
		return [
			'multiple_kb'                => false,
			'enable_glossaries'          => false,
			'enable_encyclopedia'        => false,
			'analytics_from'             => false,
			'unique_visitor_count'       => false,
			'exclude_bot_analytics'      => false,
			'show_attachment'            => false,
			'show_related_docs'          => false,
			'advance_search'             => false,
			'child_category_exclude'     => false,
			'kb_based_search'            => false,
			'enable_disable'             => false,
			'enable_content_restriction' => false
		];
	}
}
