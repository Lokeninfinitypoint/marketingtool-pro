<?php
/** @var $data */
/** @var $pageUrl */
/** @var $rangeFilter */
/** @var $startDate */
/** @var $endDate */
/** @var $currencyFilter */
/** @var $db */
/** @var $stripe */
/** @var $currencies */
/** @var $lastWebhookEvent */

$has_payment_forms  = count( $data->payment_forms ) > 0;
$has_donation_forms = count( $data->donation_forms ) > 0;
?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-reports" id="wpfs-reports">
        <?php include('partials/wpfs-header.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <form name="wpfs-filter-reports" action="<?php echo $pageUrl; ?>" method="post">
            <div class="wpfs-page-controls">
                <div class="wpfs-form-group">
                    <label class="wpfs-form-label" for="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_REPORTS_FILTER; ?>"><?php esc_html_e( 'Date Range', 'wp-full-stripe-free' ); ?></label>
                    
                    <div class="wpfs-ui wpfs-form-select wpfs-page-controls__control wpfs-page-controls__control--w200">
                        <select class="js-selectmenu" name="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_REPORTS_FILTER; ?>" id="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_REPORTS_FILTER; ?>">
                            <option value="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_TODAY; ?>" <?php echo $rangeFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_TODAY ? "selected" : ""; ?>><?php _e( 'Today', 'wp-full-stripe-free' ); ?></option>
                            <option value="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_YESTERDAY; ?>" <?php echo $rangeFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_YESTERDAY ? "selected" : ""; ?>><?php _e( 'Yesterday', 'wp-full-stripe-free' ); ?></option>
                            <option value="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_LAST_7_DAYS; ?>" <?php echo $rangeFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_LAST_7_DAYS ? "selected" : ""; ?>><?php _e( 'Last 7 Days', 'wp-full-stripe-free' ); ?></option>
                            <option value="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_LAST_30_DAYS; ?>" <?php echo $rangeFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_LAST_30_DAYS ? "selected" : ""; ?>><?php _e( 'Last 30 Days', 'wp-full-stripe-free' ); ?></option>
                            <option value="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_THIS_MONTH; ?>" <?php echo $rangeFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_THIS_MONTH ? "selected" : ""; ?>><?php _e( 'This Month', 'wp-full-stripe-free' ); ?></option>
                            <option value="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_LAST_MONTH; ?>" <?php echo $rangeFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_LAST_MONTH ? "selected" : ""; ?>><?php _e( 'Last Month', 'wp-full-stripe-free' ); ?></option>
                            <option value="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_CUSTOM; ?>" <?php echo $rangeFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_RANGE_CUSTOM ? "selected" : ""; ?>><?php _e( 'Custom', 'wp-full-stripe-free' ); ?></option>
                        </select>
                    </div>
                </div>

                <div class="wpfs-form-group wpfs-hidden">
                    <label class="wpfs-form-label" for="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_REPORTS_START_DATE; ?>"><?php esc_html_e( 'Start Date', 'wp-full-stripe-free' ); ?></label>
                    <input type="date" name="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_REPORTS_START_DATE; ?>" id="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_REPORTS_START_DATE; ?>" value="<?php echo $startDate; ?>" class="wpfs-form-control" />
                </div>

                <div class="wpfs-form-group wpfs-hidden">
                    <label class="wpfs-form-label" for="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_REPORTS_END_DATE; ?>"><?php esc_html_e( 'End Date', 'wp-full-stripe-free' ); ?></label>
                    <input type="date" name="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_REPORTS_END_DATE; ?>" id="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_REPORTS_END_DATE; ?>" value="<?php echo $endDate; ?>" class="wpfs-form-control" />
                </div>

                <div class="wpfs-form-group">
                    <label class="wpfs-form-label" for="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_REPORTS_CURRENCY; ?>"><?php esc_html_e( 'Currency', 'wp-full-stripe-free' ); ?></label>
                    
                    <div class="wpfs-ui wpfs-form-select wpfs-page-controls__control wpfs-page-controls__control--w200">
                        <select class="js-selectmenu" name="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_REPORTS_CURRENCY; ?>" id="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_REPORTS_CURRENCY; ?>">
                            <?php foreach( $currencies as $key => $currency ): ?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php echo $currencyFilter === $key ? "selected" : ""; ?>><?php echo esc_html( $currency['code'] . ' (' . $currency['symbol'] . ')' ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader" id="wpfs-reports-filter"><?php esc_html_e( 'Filter', 'wp-full-stripe-free' ); ?></button>
            </div>
        </form>

        <div class="wpfs-page-reports__content">
            <?php if ( ! $lastWebhookEvent ): ?>
                <div class="wpfs-form-block">
                    <div class="wpfs-inline-message wpfs-inline-message--warning">
                        <div class="wpfs-inline-message__inner">
                            <div class="wpfs-inline-message__title"><?php esc_html_e('Ensure Webhooks are Properly Configured in Stripe', 'wp-full-stripe-free'); ?></div>
                            <p>
                                <?php 
                                echo sprintf(
                                    __( 'To ensure that reports are accurately recorded, please make sure that webhooks are properly configured in Stripe. This allows Stripe to send notifications to WP Full Pay when events occur. For more information on setting up webhooks, see our %1$sSetting up webhooks guide%2$s.', 'wp-full-stripe-free' ),
                                    '<a href="' . esc_url('https://docs.themeisle.com/article/2096-setting-up-webhooks') . '" target="_blank">',
                                    '</a>'
                                ); 
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( $data->first_view === 'donations' ): ?>
                <?php if ( $has_donation_forms ) include('partials/wpfs-reports-donations.php'); ?>
                <?php if ( $has_payment_forms ) include('partials/wpfs-reports-payments.php'); ?>
            <?php else: ?>
                <?php if ( $has_payment_forms ) include('partials/wpfs-reports-payments.php'); ?>
                <?php if ( $has_donation_forms ) include('partials/wpfs-reports-donations.php'); ?>
            <?php endif; ?>
        </div>
    </div>
</div>