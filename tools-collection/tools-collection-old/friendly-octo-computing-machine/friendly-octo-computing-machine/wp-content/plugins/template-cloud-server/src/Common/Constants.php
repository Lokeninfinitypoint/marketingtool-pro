<?php

namespace TI\Template_Cloud\Common;

class Constants {
	/**
	 * Get the translatable strings.
	 *
	 * @return array
	 */
	public static function get_strings() {
		return [
			'loading'                       => __( 'Loading', 'template-cloud-server' ),
			'templateCloud'                 => __( 'Template Cloud', 'template-cloud-server' ),
			'general'                       => __( 'General', 'template-cloud-server' ),
			'feedback'                      => __( 'Feedback', 'template-cloud-server' ),
			'accessKeys'                    => __( 'Access keys', 'template-cloud-server' ),
			'usefulLinks'                   => __( 'Useful links', 'template-cloud-server' ),
			'support'                       => __( 'Support', 'template-cloud-server' ),
			'featureRequest'                => __( 'Feature request', 'template-cloud-server' ),
			'documentation'                 => __( 'Documentation', 'template-cloud-server' ),

			'feedbackHeading'               => __( 'What\'s one thing you need in Templates Cloud?', 'template-cloud-server' ),
			'feedbackPlaceholder'           => __( 'Tell us how can we help you better with Templates Cloud', 'template-cloud-server' ),
			'feedbackError'                 => __( 'There was a problem submitting your feedback.', 'template-cloud-server' ),
			'feedbackEmpty'                 => __( 'Please provide a feedback before submitting the form.', 'template-cloud-server' ),
			'feedbackSuccess'               => __( 'Thank you for helping us improve Templates Cloud!', 'template-cloud-server' ),
			'feedbackDetails'               => __( 'Feedback details', 'template-cloud-server' ),
			'feedbackDisclaimer'            => __( 'We value privacy, that\'s why no domain name, email address or IP addresses are collected after you submit the survey. Below is a detailed view of all data that Themeisle will receive if you fill in this survey.', 'template-cloud-server' ),
			'submitFeedback'                => __( 'Submit feedback', 'template-cloud-server' ),
			'whatInfoDoWeCollect'           => __( 'What info do we collect?', 'template-cloud-server' ),
			'pluginVersion'                 => __( 'Plugin version', 'template-cloud-server' ),
			'textFromTheAboveTextArea'      => __( 'Text from the above text area', 'template-cloud-server' ),

			'verifiedExpiresAt'             => __( 'Verified - Expires at', 'template-cloud-server' ),
			'licenseKey'                    => __( 'License Key', 'template-cloud-server' ),
			'activate'                      => __( 'Activate', 'template-cloud-server' ),
			'deactivate'                    => __( 'Deactivate', 'template-cloud-server' ),
			/* translators: %s: link to Themeisle purchase history */
			'licenseInstructions'           => sprintf( __( 'Enter your license from %s purchase history in order to get plugin updates.', 'template-cloud-server' ), '<a href="https://store.themeisle.com/login/" class="text-primary" target="_blank">Themeisle</a>' ),
			'activateLicenseError'          => __( 'Can not activate this license!', 'template-cloud-server' ),

			'howItWorks'                    => __( 'How it works', 'template-cloud-server' ),
			'exposePatterns'                => __( 'Expose Patterns', 'template-cloud-server' ),
			'exposePatternsDescription'     => __( 'The plugin lets you expose saved patterns by creating Access Keys for specific Collections, making them available to external websites.', 'template-cloud-server' ),
			'generateAccessKeys'            => __( 'Generate Access Keys', 'template-cloud-server' ),
			'generateAccessKeysDescription' => __( 'You can generate keys for different collections, auto-generate the key, and select which patterns to share.', 'template-cloud-server' ),
			'clientIntegration'             => __( 'Client Integration', 'template-cloud-server' ),
			'clientIntegrationDescription'  => __( 'External sites using Otter Blocks can integrate by entering the access key, enabling them to import your patterns.', 'template-cloud-server' ),
			'importAndSync'                 => __( 'Import and Sync', 'template-cloud-server' ),
			'importAndSyncDescription'      => __( 'Once connected, client websites can import patterns and automatically sync updates, ensuring they always have the latest versions.', 'template-cloud-server' ),

			'invalidLicense'                => __( 'Invalid license key', 'template-cloud-server' ),
			'invalidLicenseDescription'     => __( 'In order to create access keys please make sure you have a valid license key.', 'template-cloud-server' ),
			'enterLicenseKey'               => __( 'Enter License Key', 'template-cloud-server' ),
			'keyName'                       => __( 'Key Name', 'template-cloud-server' ),
			'keyNameHelp'                   => __( 'Will appear as the category name on client site.', 'template-cloud-server' ),
			'noKeysFound'                   => __( 'No access keys found', 'template-cloud-server' ),
			'noCollections'                 => __( 'No collections found', 'template-cloud-server' ),
			'noCollectionsDescription'      => __( 'Add a new collection to any of the patterns in the editor and it will show up here.', 'template-cloud-server' ),
			'noCategories'                  => __( 'No categories found', 'template-cloud-server' ),
			'noCategoriesDescription'       => __( 'Add a new category to any of the patterns in the editor and it will show up here.', 'template-cloud-server' ),
			'collections'                   => __( 'Collections', 'template-cloud-server' ),
			'collectionsAccessHelp'         => __( 'Patterns that don\'t have a collection or category assigned are not included.', 'template-cloud-server' ),
			'includeCollections'            => __( 'Include Collections', 'template-cloud-server' ),
			'excludeCollections'            => __( 'Exclude Collections', 'template-cloud-server' ),
			'includeCategories'             => __( 'Include Categories', 'template-cloud-server' ),
			'excludeCategories'             => __( 'Exclude Categories', 'template-cloud-server' ),
			'all'                           => __( 'All Patterns', 'template-cloud-server' ),
			'include'                       => __( 'Include', 'template-cloud-server' ),
			'exclude'                       => __( 'Exclude', 'template-cloud-server' ), // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'cancel'                        => __( 'Cancel', 'template-cloud-server' ),
			'createAccessKey'               => __( 'Create Access Key', 'template-cloud-server' ),
			'updateAccessKey'               => __( 'Update Access Key', 'template-cloud-server' ),
			'edit'                          => __( 'Edit', 'template-cloud-server' ),
			'delete'                        => __( 'Delete', 'template-cloud-server' ),
			'copy'                          => __( 'Copy', 'template-cloud-server' ),
			'dismiss'                       => __( 'Dismiss', 'template-cloud-server' ),
			'deleteConfirm'                 => __( 'Are you sure you want to delete this key?', 'template-cloud-server' ),
			'key'                           => __( 'Key', 'template-cloud-server' ),
			'access'                        => __( 'Access', 'template-cloud-server' ),
			'actions'                       => __( 'Actions', 'template-cloud-server' ),
			'created'                       => __( 'Created', 'template-cloud-server' ),
			'apiUrl'                        => __( 'API URL', 'template-cloud-server' ),
			'unknownError'                  => __( 'An unknown error occurred. Please try again later.', 'template-cloud-server' ),
		];
	}
}
