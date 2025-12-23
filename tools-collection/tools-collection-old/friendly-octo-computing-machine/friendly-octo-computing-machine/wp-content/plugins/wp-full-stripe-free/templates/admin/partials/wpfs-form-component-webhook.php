<?php
    /** @var $view MM_WPFS_Admin_FormView */
    /** @var $form */
    /** @var $data */

    $webhook_json = $form->webhook ?? '';

    if ( ! empty( $webhook ) ) {
        $webhook = json_decode( $webhook_json, true );
    }

    if ( ! empty( $webhook ) && empty( $webhook['headers'] ) ) {
        $webhook['headers'] = [];
    }

    if ( empty( $webhook ) ) {
        $webhook = [
            'url'    => '',
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];
    }
?>

<input id="<?php $view->webhook()->id(); ?>" name="<?php $view->webhook()->name(); ?>" value="<?php echo htmlspecialchars( $webhook_json, ENT_QUOTES, 'UTF-8' ); ?>" <?php $view->webhook()->attributes(); ?>/>

<div class="wpfs-form-group">
    <label for="wpfs-form-webhook-url" class="wpfs-form-label"><?php _e( 'Webhook URL', 'wp-full-stripe-free' ); ?></label>
    <input id="wpfs-form-webhook-url" name="wpfs-form-webhook-url" type="url" class="wpfs-form-control" value="<?php echo $webhook['url'] ?? ''; ?>">
</div>

<div class="wpfs-form-group" id="wpfs-form-webhook-headers">
    <label class="wpfs-form-label"><?php _e( 'Headers', 'wp-full-stripe-free' ); ?></label>

    <?php if ( ! empty( $webhook['headers'] ) ) : ?>
        <?php foreach ( $webhook['headers'] as $key => $value ) : ?>
            <div class="wpfs-form-group wpfs-center wpfs-gap">
                <input type="text" class="wpfs-form-control" name="wpfs-form-webhook-header-key[]" placeholder="Content-Type" value="<?php echo esc_attr( $key ); ?>" placeholder="<?php _e( 'Key', 'wp-full-stripe-free' ); ?>">
                <input type="text" class="wpfs-form-control" name="wpfs-form-webhook-header-value[]" placeholder="application/json" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php _e( 'Value', 'wp-full-stripe-free' ); ?>">
                <button class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20" id="wpfs-remove-webhook-header">
                    <span class="wpfs-icon-trash"></span>
                </button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <button class="wpfs-field-list__add" id="wpfs-add-webhook-header">
        <div class="wpfs-icon-add-circle wpfs-field-list__icon"></div>
        <?php _e( 'Add New Header', 'wp-full-stripe-free' ); ?>
    </button>
</div>

<button class="wpfs-btn-secondary wpfs-button-loader" id="wpfs-test-webhook">
    <?php _e( 'Test Webhook', 'wp-full-stripe-free' ); ?>
</button>

<p>
    <?php esc_html_e( 'A POST payload will be sent to this URL when a payment is successfully made.', 'wp-full-stripe-free' ); ?>
    <a href="https://docs.themeisle.com/article/2224-receiving-payment-notifications-via-webhooks" target="_blank"><?php esc_html_e( 'Learn more about Webhooks', 'wp-full-stripe-free' ); ?></a>
</p>
