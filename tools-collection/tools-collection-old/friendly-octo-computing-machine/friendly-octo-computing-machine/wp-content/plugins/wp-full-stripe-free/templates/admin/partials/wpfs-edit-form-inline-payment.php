<?php
/** @var $view MM_WPFS_Admin_InlinePaymentFormView */
/** @var $form MM_WPFS_Admin_PaymentFormModel */

// check status of connect
$liveStatus = $this->options->get( MM_WPFS_Options::OPTION_LIVE_ACCOUNT_STATUS );
$testStatus = $this->options->get( MM_WPFS_Options::OPTION_TEST_ACCOUNT_STATUS );

$liveComplete = $liveStatus === MM_WPFS_Options::OPTION_ACCOUNT_STATUS_COMPLETE || $liveStatus === MM_WPFS_Options::OPTION_ACCOUNT_STATUS_ENABLED ? true : false;
$testComplete = $testStatus === MM_WPFS_Options::OPTION_ACCOUNT_STATUS_COMPLETE || $testStatus === MM_WPFS_Options::OPTION_ACCOUNT_STATUS_ENABLED ? true : false;


?>
<style>
	.wpfs-form-check-label__payment_method::before {
		top: 50% !important;
		left: 0 !important;
		margin-top: -8px !important;
		margin-left: 15px;
	}

	.wpfs-form-check-label__payment_method::after {
		left: 15px !important;
	}
</style>

<form <?php $view->formAttributes(); ?>>
	<input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>"
		value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
	<input name="<?php echo MM_WPFS_Admin_FormViewConstants::FIELD_FORM_ID; ?>"
		value="<?php echo $form->paymentFormID; ?>" type="hidden">
	<input id="<?php $view->minimumPaymentAmountHidden()->id(); ?>"
		name="<?php $view->minimumPaymentAmountHidden()->name(); ?>" <?php $view->minimumPaymentAmountHidden()->attributes(); ?>>
	<div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_GENERAL; ?>">
		<div class="wpfs-form__cols">
			<div class="wpfs-form__col">
				<div class="wpfs-form-block">
					<div class="wpfs-form-block__title"><?php esc_html_e( 'Properties', 'wp-full-stripe-free' ); ?>
					</div>
					<?php include ( 'wpfs-form-component-display-name.php' ); ?>
				</div>
				<div class="wpfs-form-block">
					<div class="wpfs-form-block__title"><?php esc_html_e( 'Behavior', 'wp-full-stripe-free' ); ?></div>
					<?php include ( 'wpfs-form-component-redirect-after-payment.php' ); ?>
				</div>
				<?php include ( 'wpfs-form-component-action-buttons.php' ); ?>
			</div>
		</div>
	</div>
	<div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENT; ?>"
		style="display: none;">
		<div class="wpfs-form__cols">
			<div class="wpfs-form__col">
				<div class="wpfs-form-block">
					<?php include ( 'wpfs-form-component-currency.php' ); ?>
					<?php include ( 'wpfs-form-component-seat-country.php' ); ?>
					<?php include ( 'wpfs-form-component-payment-type.php' ); ?>
				</div>
			</div>
		</div>
		<div class="wpfs-form__cols" id="onetime-payment-stripe-product-list"
			style="<?php echo $form->customAmount == MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT ? 'display: none;' : ''; ?>">
			<div class="wpfs-form__col">
				<div class="wpfs-form-block">
					<?php include ( 'wpfs-form-component-payment-products.php' ); ?>
				</div>
			</div>
		</div>
		<div class="wpfs-form__cols">
			<div class="wpfs-form__col">
				<div class="wpfs-form-block">
					<?php include ( 'wpfs-form-component-minimum-payment-amount.php' ); ?>
					<?php include ( 'wpfs-form-component-charge-type.php' ); ?>
					<?php include ( 'wpfs-form-component-generate-invoice.php' ); ?>
					<?php include ( 'wpfs-form-component-transaction-description.php' ); ?>
				</div>
			</div>
		</div>
		<div class="wpfs-form__cols">
			<div class="wpfs-form__col">
				<div class="wpfs-form-block">
					<?php
					// set default payment methods on the form if they are not set
					if ( ! isset( $form->paymentMethods ) || empty( $form->paymentMethods ) ) {
						$form->paymentMethods = "[\"card\", \"link\"]";
					}
					// loop throug the payment methods and display them
					$counter = 0;
					$count_of_payment_methods = count( $view->getPaymentMethod()->options() );
					?>
					<label for=""
						class="wpfs-form-label wpfs-form-label--mb"><?php $view->getPaymentMethod()->label(); ?></label>
					<?php if ( $liveComplete || $testComplete ) { ?>
						<?php foreach ( $view->getPaymentMethod()->options() as $option ) {
							/* @var $option MM_WPFS_Control */
							if ( round( $count_of_payment_methods / 2 ) == $counter ) {
								echo '
						</div>
					</div>
					<div class="wpfs-form__col">
                        <div class="wpfs-form-block">';
							}
							$counter++;
							?>
							<?php include ( 'wpfs-form-component-payment-methods.php' ); ?>
						<?php } ?>
					<?php } else {
						$url = admin_url( 'admin.php?page=wpfs-settings-stripe' );

						?>
						<p><a href="<?php echo $url ?>">Connect your Stripe account</a> to be able to select payment methods</p>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="wpfs-form__cols">
			<div class="wpfs-form__col">
				<div class="wpfs-form-block">

					<?php include ( 'wpfs-form-component-action-buttons.php' ); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_TAX; ?>"
		style="display: none;">
		<div class="wpfs-form__cols">
			<div class="wpfs-form__col">
				<div class="wpfs-form-block">
					<?php include ( 'wpfs-form-component-tax-type.php' ); ?>
				</div>
				<div class="wpfs-form-block" id="tax-rates-settings"
					style="<?php echo $view->doesFormCalculateTax( $form ) ? '' : 'display: none;' ?>">
					<?php include ( 'wpfs-form-component-tax-rate-type.php' ); ?>
					<?php include ( 'wpfs-form-component-collect-customer-tax-id.php' ); ?>
					<?php include ( 'wpfs-form-component-tax-rates.php' ); ?>
				</div>
				<div class="wpfs-form-block">
					<?php include ( 'wpfs-form-component-action-buttons.php' ); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE; ?>"
		style="display: none;">
		<div class="wpfs-form__cols">
			<div class="wpfs-form__col">
				<div class="wpfs-form-block">
					<?php include ( 'wpfs-form-component-product-selector-style.php' ); ?>
				</div>
				<div class="wpfs-form-block">
					<?php include ( 'wpfs-form-component-submit-form-button-label.php' ); ?>
					<?php include ( 'wpfs-form-component-card-field-language.php' ); ?>
				</div>
				<div class="wpfs-form-block">
					<div class="wpfs-form-block__title">
						<?php esc_html_e( 'Locale', 'wp-full-stripe-free' ); ?>
					</div>
					<?php include ( 'wpfs-form-component-locale-currency.php' ); ?>
				</div>
				<?php include ( 'wpfs-form-component-action-buttons.php' ); ?>
			</div>
			<div class="wpfs-form__col">
				<?php include ( 'wpfs-form-component-css-selector.php' ); ?>
				<?php include ( 'wpfs-form-component-elements-appearance-selector.php' ); ?>

			</div>
		</div>
	</div>
	<div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_FORM_LAYOUT; ?>"
		style="display: none;">
		<div class="wpfs-form__cols">
			<div class="wpfs-form__col">
				<div class="wpfs-form-block">
					<div class="wpfs-form-block__title">
						<?php esc_html_e( 'Optional form fields', 'wp-full-stripe-free' ); ?>
					</div>
					<?php include ( 'wpfs-form-component-customer-data-inline.php' ); ?>
					<?php include ( 'wpfs-form-component-terms-of-service.php' ); ?>
					<?php include ( 'wpfs-form-component-coupon.php' ); ?>
				</div>
                <div class="wpfs-form-block">
                    <div class="wpfs-form-block__title"><?php esc_html_e( 'Fee Recovery', 'wp-full-stripe-free'); ?></div>
                    <?php include( 'wpfs-form-component-fee-recovery.php' ); ?>
                </div>
				<div class="wpfs-form-block">
					<div class="wpfs-form-block__title">
						<?php esc_html_e( 'Custom fields', 'wp-full-stripe-free' ); ?>
					</div>
					<?php include ( 'wpfs-form-component-custom-fields.php' ); ?>
				</div>
                <div class="wpfs-form-block">
                    <div class="wpfs-form-block__title"><?php esc_html_e( 'Security', 'wp-full-stripe-free'); ?></div>
                    <div class="wpfs-form-help">
                        <?php 
                            echo sprintf(
                                __( 'You can enable reCaptcha in the %1$sWP Full Pay settings%2$s.', 'wp-full-stripe-free' ),
                                '<a target="_blank" href="' . MM_WPFS_Admin_Menu::getAdminUrlBySlug( MM_WPFS_Admin_Menu::SLUG_SETTINGS_SECURITY ) . '">',
                                '</a>'
                            ); 
                        ?>
                    </div>
                </div>
				<?php include ( 'wpfs-form-component-action-buttons.php' ); ?>
			</div>
		</div>
	</div>
	<div class="wpfs-edit-form-pane"
		data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_EMAIL_NOTIFICATIONS; ?>" style="display: none;">
		<?php include ( 'wpfs-form-component-email-templates.php' ); ?>
		<?php include ( 'wpfs-form-component-action-buttons.php' ); ?>
	</div>
    <div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_WEBHOOK; ?>" style="display: none;">
        <div class="wpfs-form__cols">
            <div class="wpfs-form__col wpfs-form__col__third">
                <?php include( 'wpfs-form-component-webhook.php' ); ?>
                <?php include( 'wpfs-form-component-action-buttons.php' ); ?>
            </div>
        </div>
    </div>
</form>