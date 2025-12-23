<?php
    /** @var $view MM_WPFS_Admin_FormView */
    /** @var $form */
?>
<div class="wpfs-form-block">
    <div class="wpfs-form-group">
        <label class="wpfs-form-label"><?php $view->feeRecovery()->label(); ?></label>
        <div class="wpfs-form-check-list">
            <?php $options = $view->feeRecovery()->options(); ?>
            <?php foreach ( $options as $option ): ?>
                <div class="wpfs-form-check">
                    <input id="<?php $option->id(); ?>" name="<?php $option->name(); ?>" <?php $option->attributes(); ?> value="<?php $option->value(); ?>" <?php echo $form->feeRecovery == $option->value(false) ? 'checked' : ''; ?>>
                    <label class="wpfs-form-check-label" for="<?php $option->id(); ?>"><?php $option->label(); ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="wpfs-form-block wpfs-fee-recovery--can-be-disabled">
    <div class="wpfs-form-group">
        <label class="wpfs-form-label"><?php $view->feeRecoveryOptIn()->label(); ?></label>
        <div class="wpfs-form-check-list">
            <?php $options = $view->feeRecoveryOptIn()->options(); ?>
            <?php foreach ( $options as $option ): ?>
                <div class="wpfs-form-check">
                    <input id="<?php $option->id(); ?>" name="<?php $option->name(); ?>" <?php $option->attributes(); ?> value="<?php $option->value(); ?>" <?php echo $form->feeRecoveryOptIn == $option->value(false) ? 'checked' : ''; ?>>
                    <label class="wpfs-form-check-label" for="<?php $option->id(); ?>"><?php $option->label(); ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="wpfs-form-block wpfs-fee-recovery--can-be-disabled">
    <div class="wpfs-form-group">
        <label for="<?php $view->feeRecoveryOptInMessage()->id(); ?>" class="wpfs-form-label"><?php $view->feeRecoveryOptInMessage()->label(); ?></label>
        <input id="<?php $view->feeRecoveryOptInMessage()->id(); ?>" name="<?php $view->feeRecoveryOptInMessage()->name(); ?>" <?php $view->feeRecoveryOptInMessage()->attributes(); ?> value="<?php echo esc_html( $form->feeRecoveryOptInMessage ); ?>">
        <div class="wpfs-form-help"><?php echo __( 'Insert {{fee_amount}} to display the fees amount in the message.', 'wp-full-stripe-free' ) ?></div>
    </div>
</div>

<?php if ( $view instanceof MM_WPFS_Admin_InlineSubscriptionFormView || $view instanceof MM_WPFS_Admin_CheckoutSubscriptionFormView ): ?>
    <div class="wpfs-form-block wpfs-fee-recovery--can-be-disabled">
        <div class="wpfs-form-group">
            <label for="<?php $view->feeRecoveryCurrency()->id(); ?>" class="wpfs-form-label"><?php $view->feeRecoveryCurrency()->label(); ?></label>
            <select class="wpfs-form-control mx-none" id="<?php $view->feeRecoveryCurrency()->id(); ?>" name="<?php $view->feeRecoveryCurrency()->name(); ?>" <?php $view->feeRecoveryCurrency()->attributes(); ?>>
                <?php foreach ( MM_WPFS_Currencies::getAvailableCurrencies() as $key => $value ): ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php echo $form->feeRecoveryCurrency == $key ? 'selected' : ''; ?>>
                        <?php echo esc_html( $value['code'] . ' (' . $value['symbol'] . ')' ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
<?php endif; ?>

<div class="wpfs-form-block wpfs-fee-recovery--can-be-disabled">
    <div class="wpfs-form-group">
        <label for="<?php $view->feeRecoveryFeePercentage()->id(); ?>" class="wpfs-form-label"><?php $view->feeRecoveryFeePercentage()->label(); ?></label>
        <div class="wpfs-input-group wpfs-input-group--sm">
            <input id="<?php $view->feeRecoveryFeePercentage()->id(); ?>" name="<?php $view->feeRecoveryFeePercentage()->name(); ?>" <?php $view->feeRecoveryFeePercentage()->attributes(); ?> value="<?php echo esc_html( $form->feeRecoveryFeePercentage ); ?>">
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
        <div class="wpfs-input-group wpfs-input-group-<?php $view->feeRecoveryFeeAdditionalAmount()->name(); ?> wpfs-input-group--sm">
            <div class="wpfs-input-group-append">
                <span class="wpfs-input-group-text">$</span>
            </div>
            <input id="<?php $view->feeRecoveryFeeAdditionalAmount()->id(); ?>" name="<?php $view->feeRecoveryFeeAdditionalAmount()->name(); ?>" <?php $view->feeRecoveryFeeAdditionalAmount()->attributes(); ?> value="<?php echo esc_html( $form->feeRecoveryFeeAdditionalAmount ); ?>">
        </div>
        <div class="wpfs-form-help"><?php echo __( 'Additional amount to be added on top of percentage fee.', 'wp-full-stripe-free' ) ?></div>
    </div>
</div>