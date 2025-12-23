<?php
/** @var $backLinkUrl */
/** @var $view MM_WPFS_Admin_FeeRecoveryView */
?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-settings-fee-recovery">
        <?php include('partials/wpfs-header-with-back-link.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <div class="wpfs-page-settings-container">
            <?php include('partials/wpfs-settings-sidebar.php'); ?>
            <div>
                <form <?php $view->formAttributes(); ?>>
                    <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>" value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
                    <div class="wpfs-form__cols">
                        <div class="wpfs-form__col">

                            <div class="wpfs-form-actions">
                                <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader" type="submit"><?php esc_html_e( 'Save settings', 'wp-full-stripe-free' ); ?></button>
                                <a href="<?php echo $backLinkUrl; ?>" class="wpfs-btn wpfs-btn-text"><?php esc_html_e( 'Cancel', 'wp-full-stripe-free' ); ?></a>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="wpfs-success-message-container"></div>
            </div>
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
