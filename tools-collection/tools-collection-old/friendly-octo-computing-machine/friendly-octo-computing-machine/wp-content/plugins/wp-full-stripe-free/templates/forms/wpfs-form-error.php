<?php
    /** @var $ex Exception */
    if ( current_user_can( 'administrator' ) ) {
        $this->fullstripe_load_css();
        $errorMessage = $ex->getMessage();
        if ( strpos( $errorMessage, 'Invalid API Key provided' ) !== false ) {
            $errorMessage = 'Invalid API Key provided. Please connect your Stripe account in the WP Full Stripe settings.';
        } elseif ( strpos( $errorMessage, 'No such price' ) !== false ) {
            $errorMessage = 'No such price found. Please select the correct price from the WP Full Stripe settings.';
        }
    } else {
        $errorMessage = 'An error occurred. Please contact the website administrator.';
    }
?>
<form class="wpfs-form wpfs-w-60">
    <div class="wpfs-form-message wpfs-form-message--notice">
        <div class="wpfs-form-message-title">WP Full Pay shortcode error</div>
        <?php echo esc_html( $errorMessage ); ?>

        <?php if ( current_user_can( 'administrator' ) ): ?>
            <div class="wpfs-form-actions">
                <a target="_blank" class="wpfs-btn wpfs-btn-primary wpfs-mr-2" href="<?php echo admin_url( 'admin.php?page=wpfs-settings-stripe' ); ?>">Go to Settings</a>
            </div>
        <?php endif; ?>
    </div>
</form>
