<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use WPDeveloper\BetterDocs\Utils\Views;
use WPDeveloper\BetterDocs\Utils\Enqueue;
use WPDeveloper\BetterDocs\Editors\Editor;
use WPDeveloper\BetterDocs\Editors\Elementor;
use WPDeveloper\BetterDocs\Editors\BlockEditor;

return [
	Enqueue::class => new Enqueue( BETTERDOCS_ABSURL, BETTERDOCS_ABSPATH, BETTERDOCS_VERSION ),
	Views::class   => function ( $container ) {
		return new Views( BETTERDOCS_ABSPATH . 'views/', $container );
	},
	Editor::class  => function ( $container ) {
		return new Editor(
			$container,
			[
				'elementor'   => Elementor::class,
				'blockEditor' => BlockEditor::class
			]
		);
	}
];
