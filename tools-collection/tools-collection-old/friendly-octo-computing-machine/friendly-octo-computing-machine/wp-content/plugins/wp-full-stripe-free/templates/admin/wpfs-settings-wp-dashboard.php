<?php
/** @var $backLinkUrl */
/** @var $view MM_WPFS_Admin_WordpressDashboardView */
/** @var $wpDashboardData */
?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-settings-wp-dashboard">
        <?php include('partials/wpfs-header-with-back-link.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <div class="wpfs-page-settings-container">
            <?php include('partials/wpfs-settings-sidebar.php'); ?>

            <form <?php $view->formAttributes(); ?>>
                <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>" value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
                <div class="wpfs-form__cols">
                    <div class="wpfs-form__col">
                        <div class="wpfs-form-block">
                            <div class="wpfs-form-group">
                                <label class="wpfs-form-label"><?php $view->decimalSeparator()->label(); ?></label>
                                <div class="wpfs-form-check-list">
                                    <div class="wpfs-form-check">
                                        <?php $options = $view->decimalSeparator()->options(); ?>
                                        <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $wpDashboardData->decimalSeparator == $options[0]->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
                                    </div>
                                    <div class="wpfs-form-check">
                                        <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $wpDashboardData->decimalSeparator == $options[1]->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="wpfs-form-group">
                                <label class="wpfs-form-label"><?php $view->useSymbolNotCode()->label(); ?></label>
                                <div class="wpfs-form-check-list">
                                    <div class="wpfs-form-check">
                                        <?php $options = $view->useSymbolNotCode()->options(); ?>
                                        <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $wpDashboardData->useSymbolNotCode == $options[0]->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
                                    </div>
                                    <div class="wpfs-form-check">
                                        <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $wpDashboardData->useSymbolNotCode == $options[1]->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="wpfs-form-group">
                                <label class="wpfs-form-label"><?php $view->currencySymbolAtFirstPosition()->label(); ?></label>
                                <div class="wpfs-form-check-list">
                                    <div class="wpfs-form-check">
                                        <?php $options = $view->currencySymbolAtFirstPosition()->options(); ?>
                                        <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $wpDashboardData->currencySymbolAtFirstPosition == $options[0]->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
                                    </div>
                                    <div class="wpfs-form-check">
                                        <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $wpDashboardData->currencySymbolAtFirstPosition == $options[1]->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="wpfs-form-group">
                                <label class="wpfs-form-label"><?php $view->putSpaceBetweenSymbolAndAmount()->label(); ?></label>
                                <div class="wpfs-form-check-list">
                                    <div class="wpfs-form-check">
                                        <?php $options = $view->putSpaceBetweenSymbolAndAmount()->options(); ?>
                                        <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $wpDashboardData->putSpaceBetweenSymbolAndAmount == $options[0]->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
                                    </div>
                                    <div class="wpfs-form-check">
                                        <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $wpDashboardData->putSpaceBetweenSymbolAndAmount == $options[1]->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wpfs-form-block">
                            <div class="wpfs-form-block__title"><?php esc_html_e( 'Fee Recovery', 'wp-full-stripe-free' ); ?></div>
                            <div class="wpfs-form-group">
                                <label class="wpfs-form-label"><?php $view->feeRecovery()->label(); ?></label>
                                <div class="wpfs-form-check-list">
                                    <div class="wpfs-form-check">
                                        <?php $options = $view->feeRecovery()->options(); ?>
                                        <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $wpDashboardData->feeRecovery == $options[0]->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
                                    </div>
                                    <div class="wpfs-form-check">
                                        <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $wpDashboardData->feeRecovery == $options[1]->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wpfs-form-block wpfs-fee-recovery--can-be-disabled">
                            <div class="wpfs-form-group">
                                <label class="wpfs-form-label"><?php $view->feeRecoveryOptIn()->label(); ?></label>
                                <div class="wpfs-form-check-list">
                                    <div class="wpfs-form-check">
                                        <?php $options = $view->feeRecoveryOptIn()->options(); ?>
                                        <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $wpDashboardData->feeRecoveryOptIn == $options[0]->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
                                    </div>
                                    <div class="wpfs-form-check">
                                        <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $wpDashboardData->feeRecoveryOptIn == $options[1]->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wpfs-form-block wpfs-fee-recovery--can-be-disabled">
                            <div class="wpfs-form-group">
                                <label for="<?php $view->feeRecoveryOptInMessage()->id(); ?>" class="wpfs-form-label"><?php $view->feeRecoveryOptInMessage()->label(); ?></label>
                                <input id="<?php $view->feeRecoveryOptInMessage()->id(); ?>" name="<?php $view->feeRecoveryOptInMessage()->name(); ?>" <?php $view->feeRecoveryOptInMessage()->attributes(); ?> value="<?php echo esc_html( $wpDashboardData->feeRecoveryOptInMessage ); ?>">
                                <div class="wpfs-form-help"><?php echo __( 'Insert {{fee_amount}} to display the fees amount in the message.', 'wp-full-stripe-free' ) ?></div>
                            </div>
                        </div>

                        <div class="wpfs-form-block wpfs-fee-recovery--can-be-disabled">
                            <div class="wpfs-form-group">
                                <label for="<?php $view->feeRecoveryCurrency()->id(); ?>" class="wpfs-form-label"><?php $view->feeRecoveryCurrency()->label(); ?></label>
                                <select class="wpfs-form-control mx-none" id="<?php $view->feeRecoveryCurrency()->id(); ?>" name="<?php $view->feeRecoveryCurrency()->name(); ?>" <?php $view->feeRecoveryCurrency()->attributes(); ?>>
                                    <?php foreach ( MM_WPFS_Currencies::getAvailableCurrencies() as $key => $value ): ?>
                                        <option value="<?php echo esc_attr( $key ); ?>" <?php echo $wpDashboardData->feeRecoveryCurrency == $key ? 'selected' : ''; ?>>
                                            <?php echo esc_html( $value['code'] . ' (' . $value['symbol'] . ')' ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="wpfs-form-help"><?php echo __( 'This currency is used for Subscription forms only.', 'wp-full-stripe-free' ) ?></div>
                            </div>
                        </div>

                        <div class="wpfs-form-block wpfs-fee-recovery--can-be-disabled">
                            <div class="wpfs-form-group">
                                <label for="<?php $view->feeRecoveryFeePercentage()->id(); ?>" class="wpfs-form-label"><?php $view->feeRecoveryFeePercentage()->label(); ?></label>
                                <div class="wpfs-input-group wpfs-input-group--sm">
                                    <input id="<?php $view->feeRecoveryFeePercentage()->id(); ?>" name="<?php $view->feeRecoveryFeePercentage()->name(); ?>" <?php $view->feeRecoveryFeePercentage()->attributes(); ?> value="<?php echo esc_html( $wpDashboardData->feeRecoveryFeePercentage ); ?>">
                                    <div class="wpfs-input-group-append">
                                        <span class="wpfs-input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="wpfs-form-help"><?php echo __( 'Enter the fee percentage.', 'wp-full-stripe-free' ) ?></div>
                            </div>
                        </div>

                        <div class="wpfs-form-block wpfs-fee-recovery--can-be-disabled">
                            <div class="wpfs-form-group">
                                <label for="<?php $view->feeRecoveryFeeAdditionalAmount()->id(); ?>" class="wpfs-form-label"><?php $view->feeRecoveryFeeAdditionalAmount()->label(); ?></label>
                                <div class="wpfs-input-group wpfs-input-group--sm">
                                    <div class="wpfs-input-group-append">
                                        <span class="wpfs-input-group-text">$</span>
                                    </div>
                                    <input id="<?php $view->feeRecoveryFeeAdditionalAmount()->id(); ?>" name="<?php $view->feeRecoveryFeeAdditionalAmount()->name(); ?>" <?php $view->feeRecoveryFeeAdditionalAmount()->attributes(); ?> value="<?php echo esc_html( $wpDashboardData->feeRecoveryFeeAdditionalAmount ); ?>">
                                </div>
                                <div class="wpfs-form-help"><?php echo __( 'Additional amount to be added on top of percentage fee. It will inherit currency from the form settings.', 'wp-full-stripe-free' ) ?></div>
                            </div>
                        </div>
                        <div class="wpfs-form-actions">
                            <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader" type="submit"><?php esc_html_e( 'Save settings', 'wp-full-stripe-free' ); ?></button>
                            <a href="<?php echo $backLinkUrl; ?>" class="wpfs-btn wpfs-btn-text"><?php esc_html_e( 'Cancel', 'wp-full-stripe-free' ); ?></a>
                        </div>
                    </div>
                    <div class="wpfs-form__col">
                        <div class="wpfs-inline-message wpfs-inline-message--info wpfs-inline-message--w448">
                            <div class="wpfs-inline-message__inner">
                                <div class="wpfs-inline-message__title"><?php esc_html_e( 'What are these settings for?', 'wp-full-stripe-free' ); ?></div>
                                <p><?php esc_html_e( 'Options on this page control how payment amounts, dates, etc. are localized on the WordPress dashboard pages of WP Full Pay.', 'wp-full-stripe-free' ); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="wpfs-success-message-container"></div>
        </div>
    </div>
	<?php include( 'partials/wpfs-demo-mode.php' ); ?>
</div>

<script type="text/template" id="wpfs-success-message">
    <div class="wpfs-floating-message__inner">
        <div class="wpfs-floating-message__message"><%- successMessage %></div>
        <button class="wpfs-btn wpfs-btn-icon js-hide-flash-message">
            <span class="wpfs-icon-close"></span>
        </button>
    </div>
</script>
