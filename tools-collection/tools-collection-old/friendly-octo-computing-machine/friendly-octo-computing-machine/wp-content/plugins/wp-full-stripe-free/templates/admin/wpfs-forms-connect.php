<?php
/** @var $data */
$accountId = isset($_GET['accountId']) ? sanitize_text_field($_GET['accountId']) : null;
$mode = isset($_GET['mode']) ? sanitize_text_field($_GET['mode']) : null;
?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-payment-forms">
        <?php include('partials/wpfs-header.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <div class="wpfs-connect-state">
            <div class="wpfs-connect-state__title">👋 <?php _e( 'Just One More Step!', 'wp-full-stripe-free' ); ?></div>
            <div class="wpfs-connect-state__subtitle"><?php _e( 'You\'ll need to set up your Stripe account to get started.', 'wp-full-stripe-free' ); ?></div>
            <div class="wpfs-connect-state__message"><?php _e( 'WP Full Pay integrates directly with Stripe to ensure secure, reliable payment processing. Complete your Stripe connection now to begin accepting payments on your site.', 'wp-full-stripe-free'); ?></div>

            <button id="wpfs-create-test-stripe-connect-account" class="wpfs-btn-connect wpfs-button-loader">
                <span><?php _e( 'Connect with', 'wp-full-stripe-free' ); ?></span>
                <svg width="49" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M48.4718 10.3338c0-3.41791-1.6696-6.11484-4.8607-6.11484-3.2045 0-5.1434 2.69693-5.1434 6.08814 0 4.0187 2.289 6.048 5.5743 6.048 1.6023 0 2.8141-.3604 3.7296-.8678v-2.6702c-.9155.4539-1.9658.7343-3.2987.7343-1.3061 0-2.464-.4539-2.6121-2.0294h6.5841c0-.1735.0269-.8678.0269-1.1882Zm-6.6514-1.26838c0-1.50868.929-2.13618 1.7773-2.13618.8213 0 1.6965.6275 1.6965 2.13618h-3.4738Zm-8.5499-4.84646c-1.3195 0-2.1678.61415-2.639 1.04139l-.1751-.82777h-2.9621V20l3.3661-.7076.0134-3.7784c.4847.3471 1.1984.8411 2.3832.8411 2.4102 0 4.6048-1.9225 4.6048-6.1548-.0134-3.87186-2.235-5.98134-4.5913-5.98134Zm-.8079 9.19894c-.7944 0-1.2656-.2804-1.5888-.6275l-.0134-4.95328c.35-.38719.8348-.65421 1.6022-.65421 1.2253 0 2.0735 1.36182 2.0735 3.11079 0 1.7891-.8347 3.1242-2.0735 3.1242Zm-9.6001-9.98666 3.3796-.72096V0l-3.3796.70761v2.72363Zm0 1.01469h3.3796V16.1282h-3.3796V4.44593Zm-3.6219.98798-.2154-.98798h-2.9083V16.1282h3.3661V8.21095c.7944-1.02804 2.1408-.84112 2.5582-.69426V4.44593c-.4309-.16022-2.0062-.45394-2.8006.98798Zm-6.7322-3.88518-3.2853.69426-.01346 10.69421c0 1.976 1.49456 3.4313 3.48726 3.4313 1.1041 0 1.912-.2003 2.3563-.4406v-2.7103c-.4309.1736-2.5583.7877-2.5583-1.1882V7.28972h2.5583V4.44593h-2.5583l.0135-2.8972ZM3.40649 7.83712c0-.5207.43086-.72096 1.14447-.72096 1.0233 0 2.31588.30707 3.33917.85447V4.83311c-1.11755-.44059-2.22162-.61415-3.33917-.61415C1.81769 4.21896 0 5.63418 0 7.99733c0 3.68487 5.11647 3.09747 5.11647 4.68627 0 .6141-.53858.8144-1.29258.8144-1.11755 0-2.54477-.4539-3.675782-1.0681v3.1776c1.252192.534 2.517842.761 3.675782.761 2.80059 0 4.72599-1.3752 4.72599-3.765-.01346-3.97867-5.14339-3.27106-5.14339-4.76638Z" fill="#fff"></path></svg>
            </button>
        </div>
    </div>
	<?php include( 'partials/wpfs-demo-mode.php' ); ?>

    <script type="text/javascript">
        // Define a global JavaScript variable for the accountId
        var accountIdFromPHP = <?php echo json_encode($accountId); ?>;
        var accountModeFromPHP = <?php echo json_encode($mode); ?>;
    </script>
</div>
