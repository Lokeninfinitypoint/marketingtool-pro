<?php
/**
 * Template part for BetterDocs Code Snippet
 *
 * @var string $code_content
 * @var string $language
 * @var bool $show_language_label
 * @var bool $show_copy_button
 * @var bool $show_copy_tooltip
 * @var bool $show_header
 * @var bool $show_line_numbers
 * @var string $theme
 * @var string $block_id (optional)
 * @var string $widget_type (optional)
 * @var string $file_name (optional)
 * @var bool $show_traffic_lights (optional)
 * @var bool $show_file_icon (optional)
 * @var string $file_icon (optional)
 */

if ( empty( $code_content ) ) {
    return;
}

// Generate unique ID for this code snippet
$snippet_id = isset( $block_id ) ? $block_id : 'betterdocs-code-snippet-' . wp_rand( 1000, 9999 );

// Set defaults for optional parameters
$show_language_label = isset( $show_language_label ) ? $show_language_label : true;
$show_copy_button = isset( $show_copy_button ) ? $show_copy_button : true;
$show_copy_tooltip = isset( $show_copy_tooltip ) ? $show_copy_tooltip : false;
$show_header = isset( $show_header ) ? $show_header : true;
$file_name = isset( $file_name ) ? $file_name : '';
$show_traffic_lights = isset( $show_traffic_lights ) ? $show_traffic_lights : true;
$show_file_icon = isset( $show_file_icon ) ? $show_file_icon : true;
$file_icon = isset( $file_icon ) ? $file_icon : '';

// Sanitize inputs (preserve code content as-is for display purposes)
$language = sanitize_text_field( $language );
$theme = sanitize_text_field( $theme );
$file_name = sanitize_text_field( $file_name );
$file_icon = esc_url( $file_icon );

// Import Helper class for file icon functionality
use WPDeveloper\BetterDocs\Utils\Helper;

// Generate line numbers if needed
$line_numbers = [];
if ( $show_line_numbers ) {
    $lines = explode( "\n", $code_content );
    $line_numbers = range( 1, count( $lines ) );
}

// Enqueue necessary assets
wp_enqueue_script( 'betterdocs-code-snippet' );
wp_enqueue_style( 'betterdocs-code-snippet' );
?>

<div class="betterdocs-code-snippet-wrapper theme-<?php echo esc_attr( $theme ); ?> <?php echo esc_attr( $snippet_id ); ?>"
     id="<?php echo esc_attr( $snippet_id ); ?>"
     data-language="<?php echo esc_attr( $language ); ?>"
     data-copy-button="<?php echo $show_copy_button ? 'true' : 'false'; ?>">

    <?php if ( $show_header ) : ?>
        <div class="betterdocs-code-snippet-header betterdocs-file-preview-header">
        <div class="betterdocs-file-preview-left">
            <?php if ( $show_traffic_lights ) : ?>
                <div class="betterdocs-traffic-lights">
                    <span class="traffic-light traffic-light-red"></span>
                    <span class="traffic-light traffic-light-yellow"></span>
                    <span class="traffic-light traffic-light-green"></span>
                </div>
            <?php endif; ?>

            <div class="betterdocs-file-info">
                <?php if ( $show_file_icon ) : ?>
                    <div class="betterdocs-file-icon">
                        <?php if ( ! empty( $file_icon ) ) : ?>
                            <img src="<?php echo esc_url( $file_icon ); ?>" alt="<?php esc_attr_e( 'File icon', 'betterdocs' ); ?>" />
                        <?php else : ?>
                            <span class="betterdocs-file-icon-emoji"><?php echo esc_html( Helper::get_file_icon_by_language( $language ) ); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $file_name ) ) : ?>
                    <div class="betterdocs-file-name">
                        <span class="file-name-text"><?php echo esc_html( $file_name ); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="betterdocs-file-preview-right">
            <?php if ( $show_copy_button ) : ?>
                <div class="betterdocs-code-snippet-copy-container">
                    <button class="betterdocs-code-snippet-copy-button"
                            type="button"
                            data-clipboard-target="#<?php echo esc_attr( $snippet_id ); ?> .betterdocs-code-snippet-code code"
                            aria-label="<?php esc_attr_e( 'Copy code to clipboard', 'betterdocs' ); ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 1H4C2.9 1 2 1.9 2 3V17H4V3H16V1ZM19 5H8C6.9 5 6 5.9 6 7V21C6 22.1 6.9 23 8 23H19C20.1 23 21 22.1 21 21V7C21 5.9 20.1 5 19 5ZM19 21H8V7H19V21Z" fill="currentColor"/>
                        </svg>
                    </button>
                    <?php if ( $show_copy_tooltip ) : ?>
                        <div class="betterdocs-code-snippet-tooltip"><?php esc_html_e( 'Copy to clipboard', 'betterdocs' ); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        </div>
    <?php endif; ?>

    <div class="betterdocs-code-snippet-content">
        <?php if ( $show_line_numbers ) : ?>
            <div class="betterdocs-code-snippet-line-numbers" aria-hidden="true">
                <?php foreach ( $line_numbers as $line_num ) : ?>
                    <div class="line-number"><?php echo esc_html( $line_num ); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <pre class="betterdocs-code-snippet-code language-<?php echo esc_attr( $language ); ?>"><code><?php echo esc_html( $code_content ); ?></code></pre>
    </div>
</div>

<?php
// Add inline script for copy functionality if copy button is enabled
if ( $show_copy_button ) :
?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // Initialize copy functionality for this specific snippet
    const snippet = document.getElementById('<?php echo esc_js( $snippet_id ); ?>');
    if (snippet && window.BetterDocsCodeSnippet) {
        window.BetterDocsCodeSnippet.initCopyButton(snippet);
    }
});
</script>
<?php endif; ?>
