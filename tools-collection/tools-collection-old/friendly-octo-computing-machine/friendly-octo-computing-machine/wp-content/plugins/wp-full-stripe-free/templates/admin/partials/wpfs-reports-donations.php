<?php
/** @var $data */
/** @var $pageUrl */
/** @var $rangeFilter */
/** @var $db */
/** @var $stripe */
/** @var $currencies */
?>
<div class="wpfs-form-block__title"><?php esc_html_e( 'Donation Reports', 'wp-full-stripe-free' ); ?></div>

<div class="wpfs-report-card">
    <h3 class="wpfs-report-card-title"><?php esc_html_e( 'Total Donations for Period', 'wp-full-stripe-free' ); ?></h3>
    <p class="wpfs-report-card-value"><?php echo $data->total_donations; ?></p>
</div>

<div class="wpfs-report-cards">
    <div class="wpfs-report-card">
        <h3 class="wpfs-report-card-title"><?php esc_html_e( 'Average Donation Amount', 'wp-full-stripe-free' ); ?></h3>
        <p class="wpfs-report-card-value"><?php echo $data->average_donation; ?></p>
    </div>
    <div class="wpfs-report-card">
        <h3 class="wpfs-report-card-title"><?php esc_html_e( 'Total Number of Donations', 'wp-full-stripe-free' ); ?></h3>
        <p class="wpfs-report-card-value"><?php echo $data->donations_count; ?></p>
    </div>
</div>

<div class="wpfs-report-cards">
    <div class="wpfs-report-card">
        <h3 class="wpfs-report-card-title"><?php esc_html_e( 'Last 10 Donations', 'wp-full-stripe-free' ); ?></h3>
        <table class="wpfs-report-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Date', 'wp-full-stripe-free' ); ?></th>
                    <th><?php esc_html_e( 'Donor', 'wp-full-stripe-free' ); ?></th>
                    <th><?php esc_html_e( 'Amount', 'wp-full-stripe-free' ); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach( $data->donations as $donation ) : ?>
                <tr>
                    <td><?php echo date( 'M d, Y', strtotime( $donation->created_at ) ); ?></td>
                    <td><?php echo $stripe->getCustomerName( $donation->stripeCustomerID ); ?></td>
                    <td><?php echo MM_WPFS_Currencies::format_amount_with_currency( $donation->currency, $donation->amount ); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if ( empty( $data->donations ) ) : ?>
                <tr>
                    <td colspan="3" class="empty"><?php esc_html_e( 'No donations found for the selected period.', 'wp-full-stripe-free' ); ?></td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="wpfs-report-card">
        <h3 class="wpfs-report-card-title"><?php esc_html_e( 'Top Donors', 'wp-full-stripe-free' ); ?></h3>
        <table class="wpfs-report-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Donor', 'wp-full-stripe-free' ); ?></th>
                    <th><?php esc_html_e( 'Total Donated', 'wp-full-stripe-free' ); ?></th>
                    <th><?php esc_html_e( 'Avg. Donation', 'wp-full-stripe-free' ); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach( $data->top_donors as $donors ) : ?>
                <tr>
                    <td><?php echo $stripe->getCustomerName( $donors->stripeCustomerID ); ?></td>
                    <td><?php echo MM_WPFS_Currencies::format_amount_with_currency( $donors->currency, $donors->average ); ?></td>
                    <td><?php echo MM_WPFS_Currencies::format_amount_with_currency( $donors->currency, $donors->total ); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if ( empty( $data->top_donors ) ) : ?>
                <tr>
                    <td colspan="3" class="empty"><?php esc_html_e( 'No donations found for the selected period.', 'wp-full-stripe-free' ); ?></td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
