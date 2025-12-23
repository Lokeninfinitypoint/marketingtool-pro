<?php
/**
 * Template for BetterDocs Code Snippet Widget
 *
 * @var string $code_content
 * @var string $language
 * @var bool $show_copy_button
 * @var bool $show_line_numbers
 * @var string $theme
 * @var string $widget_type
 */

if ( empty( $code_content ) ) {
    return;
}

// Use the template part for consistent rendering
$view_object->get( 'templates/parts/code-snippet', [
    'code_content'       => $code_content,
    'language'           => $language,
    'show_copy_button'   => $show_copy_button,
    'show_line_numbers'  => $show_line_numbers,
    'theme'              => $theme,
    'widget_type'        => $widget_type,
] );
