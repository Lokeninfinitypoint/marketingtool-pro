<?php
/** @var $data */
/** @var $pageUrl */
/** @var $rangeFilter */
/** @var $currencyFilter */
/** @var $db */
/** @var $stripe */
/** @var $currencies */
?>
<script type="text/javascript">
    window.wpfsCurrency = '<?php echo MM_WPFS_Currencies::getCurrencySymbolFor( $currencyFilter ); ?>';
    window.wpfsRevenueData = <?php echo json_encode( $data->revenue_data ); ?>;
    window.wpfsRefundsData = <?php echo json_encode( $data->refunds_data ); ?>;
</script>

<div class="wpfs-form-block__title"><?php esc_html_e( 'Payments & Subscription Reports', 'wp-full-stripe-free' ); ?></div>

<div class="wpfs-report-card">
    <h3 class="wpfs-report-card-title"><?php esc_html_e( 'Total Revenue for Period', 'wp-full-stripe-free' ); ?></h3>
    <p class="wpfs-report-card-value"><?php echo $data->total_revenue; ?></p>
</div>

<div class="wpfs-report-cards wpfs-report-cards-third">
    <div class="wpfs-report-card">
        <h3 class="wpfs-report-card-title"><?php esc_html_e( 'Average Transaction', 'wp-full-stripe-free' ); ?></h3>
        <p class="wpfs-report-card-value"><?php echo $data->average_transaction; ?></p>
    </div>
    <div class="wpfs-report-card">
        <h3 class="wpfs-report-card-title"><?php esc_html_e( 'Total Payments', 'wp-full-stripe-free' ); ?></h3>
        <p class="wpfs-report-card-value"><?php echo $data->total_transactions; ?></p>
    </div>
    <div class="wpfs-report-card">
        <h3 class="wpfs-report-card-title"><?php esc_html_e( 'Active Subscriptions', 'wp-full-stripe-free' ); ?></h3>
        <p class="wpfs-report-card-value"><?php echo $data->active_subscriptions; ?></p>
    </div>
</div>

<div class="wpfs-report-cards">
    <div class="wpfs-report-card">
        <div class="wpfs-report-card-controls">
            <h3 class="wpfs-report-card-title"><?php esc_html_e( 'Forms Performance', 'wp-full-stripe-free' ); ?></h3>

            <div class="wpfs-form-group">
                <div class="wpfs-ui wpfs-form-select wpfs-page-controls__control--w200">
                    <select id="wpfs-reports-form-select">
                        <option value="all"><?php esc_html_e( 'All Forms', 'wp-full-stripe-free' ); ?></option>
                        <?php foreach( $data->payment_forms as $form ): ?>
                        <option value="<?php echo esc_attr( $form->id ); ?>-<?php echo esc_attr( $form->layout ); ?>_<?php echo esc_attr( $form->type ); ?>"><?php echo esc_attr( $form->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <canvas id="wpfs-revenue-chart"></canvas>
    </div>
    <div class="wpfs-report-card">
        <h3 class="wpfs-report-card-title"><?php esc_html_e( 'Refunded Revenue', 'wp-full-stripe-free' ); ?></h3>
        <canvas id="wpfs-refunds-chart"></canvas>
    </div>
</div>

<div class="wpfs-report-cards">
    <div class="wpfs-report-card">
        <h3 class="wpfs-report-card-title"><?php esc_html_e( 'Last 10 Transactions', 'wp-full-stripe-free' ); ?></h3>
        <table class="wpfs-report-table">
        <thead>
            <tr>
                <th><?php esc_html_e( 'Date', 'wp-full-stripe-free' ); ?></th>
                <th><?php esc_html_e( 'Customer', 'wp-full-stripe-free' ); ?></th>
                <th><?php esc_html_e( 'Form', 'wp-full-stripe-free' ); ?></th>
                <th><?php esc_html_e( 'Amount', 'wp-full-stripe-free' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $data->payments as $payment ) : ?>
                <tr>
                    <td><?php echo date( 'M d, Y', strtotime( $payment->created_at ) ); ?></td>
                    <td><?php echo $stripe->getCustomerName( $payment->stripeCustomerID ); ?></td>
                    <td><?php echo $db->getFormNameByReport( $payment ); ?></td>
                    <td><?php echo MM_WPFS_Currencies::format_amount_with_currency( $payment->currency, $payment->amount ); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if ( empty( $data->payments ) ) : ?>
                <tr>
                    <td colspan="3" class="empty"><?php esc_html_e( 'No transactions found for the selected period.', 'wp-full-stripe-free' ); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
        </table>
    </div>

    <div class="wpfs-report-card">
        <h3 class="wpfs-report-card-title"><?php esc_html_e( 'Top Customers', 'wp-full-stripe-free' ); ?></h3>
        <table class="wpfs-report-table">
        <thead>
            <tr>
                <th><?php esc_html_e( 'Customer', 'wp-full-stripe-free' ); ?></th>
                <th><?php esc_html_e( 'Total Spent', 'wp-full-stripe-free' ); ?></th>
                <th><?php esc_html_e( 'Avg. Payment', 'wp-full-stripe-free' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $data->top_customers as $customer ) : ?>
                <tr>
                    <td><?php echo $stripe->getCustomerName( $customer->stripeCustomerID ); ?></td>
                    <td><?php echo MM_WPFS_Currencies::format_amount_with_currency( $customer->currency, $customer->total ); ?></td>
                    <td><?php echo MM_WPFS_Currencies::format_amount_with_currency( $customer->currency, $customer->average ); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if ( empty( $data->payments ) ) : ?>
                <tr>
                    <td colspan="3" class="empty"><?php esc_html_e( 'No transactions found for the selected period.', 'wp-full-stripe-free' ); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
        </table>
    </div>
</div>