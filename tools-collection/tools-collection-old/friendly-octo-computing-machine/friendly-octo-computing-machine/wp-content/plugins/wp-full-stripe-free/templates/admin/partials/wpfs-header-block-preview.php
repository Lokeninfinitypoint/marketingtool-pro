<?php
    /** @var $form */
    if ( ! isset( $form ) || ! isset( $formType ) ) {
        return;
    }

    $formTypeParts = explode( '_', $formType );

    if ( count( $formTypeParts ) < 2 ) {
        return;
    }

    $form->layout = array_shift( $formTypeParts );
    $form->type = implode( '_', $formTypeParts );

    $shortcode = MM_WPFS_Shortcode::createShortCodeByForm( $form );
?>
<a class="wpfs-btn wpfs-btn-outline-primary wpfs-page-header__preview js-tooltip js-open-preview-popover" data-tooltip-content="preview-tooltip" data-shortcode-value='<?php echo htmlspecialchars($shortcode); ?>'>
    <span class="wpfs-icon-view"></span><?php _e( 'Preview', 'wp-full-stripe-free' ); ?>
</a>
<div class="wpfs-tooltip-content" data-tooltip-id="preview-tooltip">
    <div class="wpfs-info-tooltip"><?php esc_html_e( 'Preview Form', 'wp-full-stripe-free' ); ?></div>
</div>

<script type="text/template" id="wpfs-modal-preview-form">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-inline-message wpfs-inline-message--info"><?php _e( 'If you don\'t see your changes, save the form to update the preview.', 'wp-full-stripe-free' ); ?></p>
        <p class="wpfs-dialog-content-text"><iframe src="<%= previewUrl %>" width="100%" height="600px" style="border:none;"></iframe></p>
    </div>
</script>