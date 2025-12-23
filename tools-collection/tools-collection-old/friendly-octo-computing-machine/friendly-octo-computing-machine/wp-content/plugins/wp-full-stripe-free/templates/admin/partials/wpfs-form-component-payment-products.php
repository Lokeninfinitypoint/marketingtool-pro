<?php
    /** @var $view MM_WPFS_Admin_PaymentFormView */
    /** @var $form */
    /** @var $data */
?>
<div class="wpfs-form-group js-payment-type" data-payment-type="list-of-products">
    <label for="" class="wpfs-form-label"><?php esc_html_e( 'Available products', 'wp-full-stripe-free' ); ?></label>
    <div id="<?php echo MM_WPFS_Admin_PaymentFormView::FIELD_FORM_ONETIME_PRODUCTS_ERROR; ?>" class="wpfs-field-list" data-field-name="<?php echo MM_WPFS_Admin_PaymentFormView::FIELD_FORM_ONETIME_PRODUCTS_ERROR; ?>">
        <div id="wpfs-onetime-products" class="wpfs-field-list__list js-sortable ui-sortable"></div>
        <a class="wpfs-field-list__add js-add-onetime-product" href="">
            <div class="wpfs-icon-add-circle wpfs-field-list__icon"></div>
            <?php esc_html_e( 'Add products from Stripe', 'wp-full-stripe-free' ); ?>
        </a>
    </div>
    <div class="wpfs-form-check">
        <input id="<?php $view->allowCustomPaymentAmount()->id(); ?>" name="<?php $view->allowCustomPaymentAmount()->name(); ?>" <?php $view->allowCustomPaymentAmount()->attributes(); ?> <?php echo $form->allowListOfAmountsCustom == '1' ? 'checked' : ''; ?>>
        <label class="wpfs-form-check-label" for="<?php $view->allowCustomPaymentAmount()->id(); ?>"><?php $view->allowCustomPaymentAmount()->label(); ?></label>
    </div>
</div>
<input id="<?php $view->onetimeProducts()->id(); ?>" name="<?php $view->onetimeProducts()->name(); ?>" value="" <?php $view->onetimeProducts()->attributes(); ?>>
<script type="text/javascript">
    var wpfsOnetimeProducts = <?php echo json_encode( $data->products ); ?>;
    var wpfsCurrencies = <?php echo json_encode( MM_WPFS_Currencies::getAvailableCurrencies() ); ?>;
</script>
<script type="text/template" id="wpfs-modal-remove-onetime-product">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger js-remove-onetime-product-dialog"><?php _e( 'Remove product', 'wp-full-stripe-free'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Keep product', 'wp-full-stripe-free' ); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-onetime-product-template">
    <div class="wpfs-icon-expand-vertical-left-right wpfs-field-list__icon"></div>
    <div class="wpfs-field-list__info">
        <div class="wpfs-field-list__title"><%- name %></div>
        <div class="wpfs-field-list__meta"><%- priceLabel %></div>
    </div>
    <div class="wpfs-field-list__actions">
        <button class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-remove-onetime-product">
            <span class="wpfs-icon-trash"></span>
        </button>
    </div>
</script>
<div id="wpfs-add-onetime-products-dialog" class="wpfs-dialog-content" title="<?php esc_html_e( 'Add products from Stripe', 'wp-full-stripe-free'); ?>">
    <div class="wpfs-dialog-loader js-add-product-step-1">
        <div class="wpfs-dialog-loader__loader"></div>
        <p class="wpfs-dialog-content-text">
            <?php esc_html_e( 'Keep tight, we are retrieving the products from Stripe. It might take a few seconds.', 'wp-full-stripe-free'); ?>
        </p>
    </div>

    <div class="wpfs-dialog-scrollable js-add-product-step-2">
        <div class="wpfs-form-group">
            <div class="wpfs-dialog-actions">
                <input class="wpfs-form-control wpfs-w-35 js-stripe-product-autocomplete" type="text" placeholder="<?php esc_html_e( 'Search one-time products...', 'wp-full-stripe-free'); ?>">
                <button class="wpfs-btn wpfs-btn-primary js-create-new-product"><?php esc_html_e( 'Create new', 'wp-full-stripe-free'); ?></button>
            </div>
            <script type="text/template">
                <div class="wpfs-form-check wpfs-stripe-product-autocomplete__item">
                    <input type="checkbox" class="wpfs-form-check-input" id="stripe-product-autocomplete-{value}" value="{value}">
                    <label class="wpfs-form-check-label wpfs-stripe-product-autocomplete__label" for="stripe-product-autocomplete-{value}">
                        {label}
                        <div class="wpfs-stripe-product-autocomplete__price">{price}</div>
                    </label>
                </div>
            </script>
        </div>
    </div>
    <div class="wpfs-dialog-content-actions js-add-product-step-2">
        <button class="wpfs-btn wpfs-btn-primary js-add-onetime-products"><?php esc_html_e( 'Add products', 'wp-full-stripe-free'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php esc_html_e( 'Discard', 'wp-full-stripe-free'); ?></button>
    </div>
    <div class="js-add-product-step-4">
        <div class="wpfs-dialog-scrollable">
            <div class="wpfs-form-group">
                <label for="wpfs-create-product-label--<?php echo MM_WPFS::FORM_TYPE_ADMIN_CREATE_ONETIME_PRODUCT; ?>-name" class="wpfs-form-label"><?php esc_html_e('Product Name', 'wp-full-stripe-free'); ?></label>
                <input id="wpfs-create-product-label--<?php echo MM_WPFS::FORM_TYPE_ADMIN_CREATE_ONETIME_PRODUCT; ?>-name" class="wpfs-form-control" type="text" name="wpfs-create-product-label--<?php echo MM_WPFS::FORM_TYPE_ADMIN_CREATE_ONETIME_PRODUCT; ?>-name">
            </div>
            <div class="wpfs-form-group">
                <label for="wpfs-create-product-label--<?php echo MM_WPFS::FORM_TYPE_ADMIN_CREATE_ONETIME_PRODUCT; ?>-price" class="wpfs-form-label"><?php esc_html_e('Produce Price', 'wp-full-stripe-free'); ?></label>
                <div class="wpfs-input-group">
                    <div class="wpfs-input-group-prepend">
                        <span class="wpfs-input-group-text wpfs-create-product-label--<?php echo MM_WPFS::FORM_TYPE_ADMIN_CREATE_ONETIME_PRODUCT; ?>-currency-placeholder">$</span>
                    </div>
                    <input id="wpfs-create-product-label--<?php echo MM_WPFS::FORM_TYPE_ADMIN_CREATE_ONETIME_PRODUCT; ?>-price" class="wpfs-input-group-form-control" type="text" name="wpfs-create-product-label--<?php echo MM_WPFS::FORM_TYPE_ADMIN_CREATE_ONETIME_PRODUCT; ?>-price">
                </div>
            </div>
        </div>
        <div class="wpfs-dialog-content-actions">
            <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader js-create-new-product-action" type="submit"><?php esc_html_e('Create Product', 'wp-full-stripe-free'); ?></button>
            <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php esc_html_e( 'Discard', 'wp-full-stripe-free'); ?></button>
        </div>
    </div>
</div>

