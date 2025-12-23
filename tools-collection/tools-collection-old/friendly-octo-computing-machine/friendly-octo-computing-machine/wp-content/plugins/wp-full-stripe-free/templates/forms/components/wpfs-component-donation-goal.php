<?php
/** @var MM_WPFS_FormView $view */
/** @var \StdClass $form */
if ( ! is_null( $view->donationGoal() ) ) {
    $donationGoal = $view->donationGoal();
    ?>
    <div class="wpfs-form-donation-goal">
        <div class="wpfs-progress-header">
            <div class="wpfs-amount-container">
                <div class="wpfs-amount"><?php echo $donationGoal['donated']; ?></div>
                <div class="wpfs-subtitle">
                    <?php
                        // translators: %s is the goal amount
                        echo sprintf( __( 'of %s Donated', 'wp-full-stripe-free' ), $donationGoal['goal'] );
                    ?>
                </div>
            </div>
            <div class="wpfs-percentage-container">
                <div class="wpfs-percentage"><?php echo $donationGoal['percent']; ?>%</div>
                <div class="wpfs-subtitle"><?php _e( 'of the Goal', 'wp-full-stripe-free' ); ?></div>
            </div>
        </div>
        <div class="wpfs-progress-bar">
            <div class="wpfs-progress-fill" style="width: <?php echo min( $donationGoal['percent'], 100 ); ?>%"></div>
        </div>
    </div>
    <?php
}
?>