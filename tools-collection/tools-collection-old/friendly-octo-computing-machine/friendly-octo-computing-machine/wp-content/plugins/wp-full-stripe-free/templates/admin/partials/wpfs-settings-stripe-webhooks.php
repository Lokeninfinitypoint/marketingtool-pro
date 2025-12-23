<?php
/** @var $stripeData */
?>
<form <?php $view->formAttributes(); ?>>
    <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>"
        value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
    <div class="wpfs-form__cols">
        <div class="wpfs-form__col">
            <!-- connect account -->
            <div class="wpfs-form-block">
                <?php if ( $stripeData->apiMode === MM_WPFS::STRIPE_API_MODE_LIVE ) : ?>
                    <?php if ( ! ( $stripeData->useWpLivePlatform ) ) : ?>
                        <!-- live webhook -->
                        <?php if ( isset( $stripeData->liveEventTitle ) ): ?>
                            <div class="wpfs-form-block">
                                <div class="wpfs-form-block__subtitle">
                                    <?php esc_html_e('Webhooks', 'wp-full-stripe-free'); ?>
                                </div>
                                <div class="wpfs-webhook">
                                    <div
                                        class="wpfs-status-bullet <?php echo $stripeData->liveEventStyle; ?> wpfs-webhook__bullet">
                                        <strong>
                                            <?php echo $stripeData->liveEventTitle; ?>
                                        </strong>
                                    </div>
                                    <div class="wpfs-webhook__last-action">
                                        <?php echo $stripeData->liveEventDescription; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ( ! is_null( $stripeData->liveAccountId ) ): ?>
                            <!-- live webhook -->
                            <div class="wpfs-form-block">
                                <div class="wpfs-form-block__subtitle">
                                    <?php esc_html_e( 'Webhooks', 'wp-full-stripe-free' ); ?>
                                </div>
                                <div class="wpfs-webhook">
                                    <?php if ( isset( $stripeData->liveEventStyle ) && isset( $stripeData->liveEventTitle ) && isset( $stripeData->liveEventDescription ) ): ?>
                                        <div class="wpfs-status-bullet <?php echo $stripeData->liveEventStyle; ?> wpfs-webhook__bullet">
                                            <strong>
                                                <?php echo $stripeData->liveEventTitle; ?>
                                            </strong>
                                        </div>
                                        <div class="wpfs-webhook__last-action">
                                            <?php echo $stripeData->liveEventDescription; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="wpfs-webhook__last-action">
                                            <?php _e( 'No webhooks received yet.', 'wp-full-stripe-free' ); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if( ! ( $stripeData->useWpLivePlatform ) ): ?>
                                        <div class="wpfs-form-group">
                                            <label for="<?php $view->liveSecretKey()->id(); ?>" class="wpfs-form-label"><?php $view->liveSecretKey()->label(); ?></label>
                                            <input id="<?php $view->liveSecretKey()->id(); ?>" name="<?php $view->liveSecretKey()->name(); ?>" type="text" value="<?php echo esc_html( $stripeData->liveSecretKey ); ?>" class="wpfs-form-control" />
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- legacy setup -->
                            <div class="wpfs-form-block">
                                <div class="wpfs-inline-message wpfs-inline-message--info wpfs-inline-message--w448">
                                    <div class="wpfs-inline-message__inner">
                                        <div class="wpfs-inline-message__title"><?php esc_html_e( 'Add-on and custom hook configuration', 'wp-full-stripe-free' ); ?></div>
                                        <p><?php esc_html_e( 'Supply your Stripe Live secret key below to be able to use Members Add-on or access Stripe from custom code using hooks.', 'wp-full-stripe-free' ); ?></p>
                                    </div>
                                    <p>
                                        <label for="<?php $view->liveSecretKey()->id(); ?>" class="wpfs-form-label">
                                            <?php $view->liveSecretKey()->label(); ?>
                                        </label>
                                        <input id="<?php $view->liveSecretKey()->id(); ?>"
                                            name="<?php $view->liveSecretKey()->name(); ?>" type="text"
                                            value="<?php echo esc_html( $stripeData->liveSecretKey ); ?>"
                                            class="wpfs-form-control"
                                        >
                                    </p>
                                </div>
                            </div> 
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if ( ! ( $stripeData->useWpTestPlatform ) ) : ?>
                        <!-- test webhook -->
                        <?php if ( isset( $stripeData->testEventTitle ) ): ?>
                            <div class="wpfs-form-block">
                                <div class="wpfs-form-block__subtitle">
                                    <?php esc_html_e('Webhooks', 'wp-full-stripe-free'); ?>
                                </div>
                                <div class="wpfs-webhook">
                                    <div
                                        class="wpfs-status-bullet <?php echo $stripeData->testEventStyle; ?> wpfs-webhook__bullet">
                                        <strong>
                                            <?php echo $stripeData->testEventTitle; ?>
                                        </strong>
                                    </div>
                                    <div class="wpfs-webhook__last-action">
                                        <?php echo $stripeData->testEventDescription; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ( ! is_null( $stripeData->testAccountId ) ): ?>
                            <!-- live webhook -->
                            <div class="wpfs-form-block">
                                <div class="wpfs-form-block__subtitle">
                                    <?php esc_html_e( 'Webhooks', 'wp-full-stripe-free' ); ?>
                                </div>
                                <div class="wpfs-webhook">
                                    <?php if ( isset( $stripeData->testEventStyle ) && isset( $stripeData->testEventTitle ) && isset( $stripeData->testEventDescription ) ): ?>
                                        <div class="wpfs-status-bullet <?php echo $stripeData->testEventStyle; ?> wpfs-webhook__bullet">
                                            <strong>
                                                <?php echo $stripeData->testEventTitle; ?>
                                            </strong>
                                        </div>
                                        <div class="wpfs-webhook__last-action">
                                            <?php echo $stripeData->testEventDescription; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="wpfs-webhook__last-action">
                                            <?php _e( 'No webhooks received yet.', 'wp-full-stripe-free' ); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if( ! ( $stripeData->useWpTestPlatform ) ): ?>
                                        <div class="wpfs-form-group">
                                            <label for="<?php $view->testSecretKey()->id(); ?>" class="wpfs-form-label"><?php $view->testSecretKey()->label(); ?></label>
                                            <input id="<?php $view->testSecretKey()->id(); ?>" name="<?php $view->testSecretKey()->name(); ?>" type="text" value="<?php echo esc_html( $stripeData->testSecretKey ); ?>" class="wpfs-form-control" />
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- legacy setup -->
                            <div class="wpfs-form-block">
                                <div class="wpfs-inline-message wpfs-inline-message--info wpfs-inline-message--w448">
                                    <div class="wpfs-inline-message__inner">
                                        <div class="wpfs-inline-message__title"><?php esc_html_e( 'Add-on and custom hook configuration', 'wp-full-stripe-free' ); ?></div>
                                        <p><?php esc_html_e( 'Supply your Stripe Live secret key below to be able to use Members Add-on or access Stripe from custom code using hooks.', 'wp-full-stripe-free' ); ?></p>
                                    </div>
                                    <p>
                                        <label for="<?php $view->testSecretKey()->id(); ?>" class="wpfs-form-label">
                                            <?php $view->testSecretKey()->label(); ?>
                                        </label>
                                        <input id="<?php $view->testSecretKey()->id(); ?>"
                                            name="<?php $view->testSecretKey()->name(); ?>" type="text"
                                            value="<?php echo esc_html( $stripeData->testSecretKey ); ?>"
                                            class="wpfs-form-control"
                                        >
                                    </p>
                                </div>
                            </div> 
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- save/cancel -->
            <div class="wpfs-form-actions">
                <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader" type="submit">
                    <?php esc_html_e('Save settings', 'wp-full-stripe-free'); ?>
                </button>
            </div>
        </div>

        <div class="wpfs-form__col">
            <div class="wpfs-form-block">
                <div class="wpfs-inline-message wpfs-inline-message--info wpfs-inline-message--w448">
                    <div class="wpfs-inline-message__inner">
                        <div class="wpfs-inline-message__title"><?php esc_html_e( 'Configure your Webhooks in Stripe', 'wp-full-stripe-free' ); ?></div>
                        <p>
                            <?php echo sprintf(
                            __( 'This is so that Stripe is able to send notifications to WP Full Pay when events occur. This is required for the Connect flow to fully work. For more information on setting up webhooks, see our %1$sSetting up webhooks guide%2$s.', 'wp-full-stripe-free' ),
                            '<a href="https://docs.themeisle.com/article/2096-setting-up-webhooks" target="_blank">',
                            '</a>'
                            ) ?>
                        </p>
                    </div>
                    <p>
                        <label for="wpfs-webhookurl" class="wpfs-form-label">
                            <?php _e( 'Webhook URL', 'wp-full-stripe-free'); ?>
                        </label>
                        <input id="wpfs-webhookurl" type="text" readonly value="<?php echo $stripeData->webHookUrl; ?>" data-webhook-url="<?php echo esc_html($stripeData->webHookUrl); ?>" class="wpfs-form-control js-copy-webhook-url"">
                    </p>
                </div>
            </div> 
        </div>
    </div>
</form>