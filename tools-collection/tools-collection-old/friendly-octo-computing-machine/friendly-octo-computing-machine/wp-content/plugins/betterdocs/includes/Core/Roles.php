<?php

namespace WPDeveloper\BetterDocs\Core;

use WPDeveloper\BetterDocs\Utils\Base;
use WPDeveloper\BetterDocs\Utils\Database;

class Roles extends Base {
	/**
	 * Summary of Database
	 * @var Database
	 */
	public $database;

	public function __construct( Database $database ) {
		$this->database = $database;

		$this->assgin_faq_builder_capability_to_admin(); //will run when it is called
	}

	/**
	 * Assign FAQ Builder Capability To The Admin
	 */
	public function assgin_faq_builder_capability_to_admin() {
		if( current_user_can('administrator') && ! current_user_can('read_faq_builder') ) { // if the current user is admin, and current user does not have faq menu visibility option, then assign the faq menu visibility capability
			$current_user_role = get_role('administrator');
			$current_user_role->add_cap('read_faq_builder');
		}
	}

	/**
	 * Default Roles Capabilities
	 *
	 * @var array
	 */
	public function defaults_capabilities() {
		$default_capabilities = [
			'administrator' => [
				// post type related caps
				'edit_docs',
				'edit_others_docs',
				'edit_private_docs',
				'edit_published_docs',
				'read_private_docs',
				'publish_docs',
				'delete_docs',
				'delete_private_docs',
				'delete_published_docs',
				'delete_others_docs',

				// doc_terms related caps
				'manage_doc_terms',
				'edit_doc_terms',
				'delete_doc_terms',

				// kb terms related caps
				'manage_knowledge_base_terms',
				'edit_knowledge_base_terms',
				'delete_knowledge_base_terms',

				// Settings and Analytics Related caps
				'edit_docs_settings',
				'read_docs_analytics',
				'read_faq_builder'
			],
			'editor'        => [
				// post type related caps
				'edit_docs',
				'edit_others_docs',
				'edit_private_docs',
				'edit_published_docs',
				'read_private_docs',
				'publish_docs',
				'delete_docs',
				'delete_private_docs',
				'delete_published_docs',
				'delete_others_docs',

				// doc_terms related caps
				'manage_doc_terms',
				'edit_doc_terms',
				'delete_doc_terms',

				// kb terms related caps
				'manage_knowledge_base_terms',
				'edit_knowledge_base_terms',
				'delete_knowledge_base_terms'
			],
			'author'        => [
				'edit_docs',
				'edit_published_docs',
				'publish_docs',
				'delete_docs',
				'delete_published_docs'
			],
			'contributor'   => [
				'edit_docs',
				'delete_docs'
			],
			'other'         => [
				// post type related caps
				'edit_docs',
				'delete_docs'
			]
		];

		return apply_filters( 'betterdocs_default_caps', $default_capabilities );
	}

	public function setup( $remove = false ) {
		if ( $this->database->get( '_betterdocs_caps_initialized', false ) && ! $remove ) {
			return;
		}

		global $wp_roles;

		$capabilities = $this->defaults_capabilities();

		if ( $remove ) {
			unset( $capabilities['administrator'] );
		}

		foreach ( $capabilities as $role => $caps ) {
			foreach ( $caps as $cap ) {
				if ( $remove ) {
					$wp_roles->remove_cap( $role, $cap );
					continue;
				}

				$wp_roles->add_cap( $role, $cap );
			}
		}

		$this->database->get( '_betterdocs_caps_initialized', ! $remove );
	}
}
