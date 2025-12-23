<?php
/** @var $tabId */
/** @var $this MM_WPFS_Admin_Menu */
/** @var $data */

?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-settings-configure-stripe-account">
        <?php include('partials/wpfs-header-with-back-link.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <div class="wpfs-page-settings-container">
            <?php include('partials/wpfs-settings-sidebar.php'); ?>

            <div>
                <?php include('partials/wpfs-header-block-tabs.php'); ?>
                <?php
                    $stripeData = $this->getStripeAccountData();

                    if ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_CONNECTION ) {
                        $view = new MM_WPFS_Admin_ConfigureStripeAccountView();

                        include('partials/wpfs-settings-stripe-connection.php');
                    } elseif ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_WEBHOOK ) {
                        $view = new MM_WPFS_Admin_ConfigureStripeAccountView();

                        include('partials/wpfs-settings-stripe-webhooks.php');
                    }
                ?>
            <div id="wpfs-success-message-container"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="wpfs-success-message">
    <div class="wpfs-floating-message__inner">
        <div class="wpfs-floating-message__message"><%- successMessage %></div>
        <button class="wpfs-btn wpfs-btn-icon js-hide-flash-message">
            <span class="wpfs-icon-close"></span>
        </button>
    </div>
</script>
