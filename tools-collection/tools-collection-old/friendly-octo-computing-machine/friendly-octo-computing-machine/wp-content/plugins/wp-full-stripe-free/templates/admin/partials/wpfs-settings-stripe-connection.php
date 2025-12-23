<?php
/** @var $stripeData */

$user_version = get_option( 'wpfp_user_v1', 'no' );
$commission   = 'yes' === $user_version ? '1.9%' : '5%';
?>
<form <?php $view->formAttributes(); ?>>
    <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>"
        value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
    <div class="wpfs-form__cols">
        <div class="wpfs-form__col">
            <!-- connect account -->
            <div class="wpfs-form-block">
                <?php if ( $stripeData->apiMode === MM_WPFS::STRIPE_API_MODE_LIVE ) : ?>
                    <?php if ( ! ( $stripeData->useWpLivePlatform ) ) : ?>
                        <div class="wpfs-form-group">
                            <label class="wpfs-form-label"><?php esc_html_e( 'Connection Status', 'wp-full-stripe-free' ); ?></label>
                            <button id="wpfs-create-live-stripe-connect-account" class="wpfs-btn-connect wpfs-button-loader">
                                <span><?php _e( 'Connect with', 'wp-full-stripe-free' ); ?></span>
                                <svg width="49" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M48.4718 10.3338c0-3.41791-1.6696-6.11484-4.8607-6.11484-3.2045 0-5.1434 2.69693-5.1434 6.08814 0 4.0187 2.289 6.048 5.5743 6.048 1.6023 0 2.8141-.3604 3.7296-.8678v-2.6702c-.9155.4539-1.9658.7343-3.2987.7343-1.3061 0-2.464-.4539-2.6121-2.0294h6.5841c0-.1735.0269-.8678.0269-1.1882Zm-6.6514-1.26838c0-1.50868.929-2.13618 1.7773-2.13618.8213 0 1.6965.6275 1.6965 2.13618h-3.4738Zm-8.5499-4.84646c-1.3195 0-2.1678.61415-2.639 1.04139l-.1751-.82777h-2.9621V20l3.3661-.7076.0134-3.7784c.4847.3471 1.1984.8411 2.3832.8411 2.4102 0 4.6048-1.9225 4.6048-6.1548-.0134-3.87186-2.235-5.98134-4.5913-5.98134Zm-.8079 9.19894c-.7944 0-1.2656-.2804-1.5888-.6275l-.0134-4.95328c.35-.38719.8348-.65421 1.6022-.65421 1.2253 0 2.0735 1.36182 2.0735 3.11079 0 1.7891-.8347 3.1242-2.0735 3.1242Zm-9.6001-9.98666 3.3796-.72096V0l-3.3796.70761v2.72363Zm0 1.01469h3.3796V16.1282h-3.3796V4.44593Zm-3.6219.98798-.2154-.98798h-2.9083V16.1282h3.3661V8.21095c.7944-1.02804 2.1408-.84112 2.5582-.69426V4.44593c-.4309-.16022-2.0062-.45394-2.8006.98798Zm-6.7322-3.88518-3.2853.69426-.01346 10.69421c0 1.976 1.49456 3.4313 3.48726 3.4313 1.1041 0 1.912-.2003 2.3563-.4406v-2.7103c-.4309.1736-2.5583.7877-2.5583-1.1882V7.28972h2.5583V4.44593h-2.5583l.0135-2.8972ZM3.40649 7.83712c0-.5207.43086-.72096 1.14447-.72096 1.0233 0 2.31588.30707 3.33917.85447V4.83311c-1.11755-.44059-2.22162-.61415-3.33917-.61415C1.81769 4.21896 0 5.63418 0 7.99733c0 3.68487 5.11647 3.09747 5.11647 4.68627 0 .6141-.53858.8144-1.29258.8144-1.11755 0-2.54477-.4539-3.675782-1.0681v3.1776c1.252192.534 2.517842.761 3.675782.761 2.80059 0 4.72599-1.3752 4.72599-3.765-.01346-3.97867-5.14339-3.27106-5.14339-4.76638Z" fill="#fff"></path></svg>
                            </button>

                            <p>
                                <?php echo sprintf(
                                __( 'For more information, see our %1$sStripe Connect Setup Guide%2$s.', 'wp-full-stripe-free' ),
                                '<a href="https://docs.themeisle.com/article/2100-step-by-step-guide-to-setup-stripe-on-fullpay-v7" target="_blank">',
                                '</a>'
                                ) ?>
                            </p>
                        </div>

                        <?php if( isset( $stripeData->liveSecretKey ) && ! empty( $stripeData->liveSecretKey ) && $stripeData->liveSecretKey !== 'YOUR_LIVE_SECRET_KEY' ): ?>
                            <div class="wpfs-form-group">
                                <label for="<?php $view->liveSecretKey()->id(); ?>" class="wpfs-form-label">
                                    <?php $view->liveSecretKey()->label(); ?>
                                </label>
                                <input id="<?php $view->liveSecretKey()->id(); ?>"
                                    name="<?php $view->liveSecretKey()->name(); ?>" type="text"
                                    value="<?php echo esc_html($stripeData->liveSecretKey); ?>"
                                    class="wpfs-form-control"
                                >
                            </div>
                            <div class="wpfs-form-group">
                                <label for="<?php $view->livePublishableKey()->id(); ?>" class="wpfs-form-label">
                                    <?php $view->livePublishableKey()->label(); ?>
                                </label>
                                <input id="<?php $view->livePublishableKey()->id(); ?>"
                                    name="<?php $view->livePublishableKey()->name(); ?>" type="text"
                                    value="<?php echo esc_html($stripeData->livePublishableKey); ?>"
                                    class="wpfs-form-control"
                                >
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- live connect -->
                        <?php if ( is_null( $stripeData->liveAccountId ) ): ?>
                            <div class="wpfs-form-group">
                                <label class="wpfs-form-label"><?php esc_html_e( 'Connection Status', 'wp-full-stripe-free' ); ?></label>
                                <button id="wpfs-create-live-stripe-connect-account" class="wpfs-btn-connect wpfs-button-loader">
                                    <span><?php _e( 'Connect with', 'wp-full-stripe-free' ); ?></span>
                                    <svg width="49" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M48.4718 10.3338c0-3.41791-1.6696-6.11484-4.8607-6.11484-3.2045 0-5.1434 2.69693-5.1434 6.08814 0 4.0187 2.289 6.048 5.5743 6.048 1.6023 0 2.8141-.3604 3.7296-.8678v-2.6702c-.9155.4539-1.9658.7343-3.2987.7343-1.3061 0-2.464-.4539-2.6121-2.0294h6.5841c0-.1735.0269-.8678.0269-1.1882Zm-6.6514-1.26838c0-1.50868.929-2.13618 1.7773-2.13618.8213 0 1.6965.6275 1.6965 2.13618h-3.4738Zm-8.5499-4.84646c-1.3195 0-2.1678.61415-2.639 1.04139l-.1751-.82777h-2.9621V20l3.3661-.7076.0134-3.7784c.4847.3471 1.1984.8411 2.3832.8411 2.4102 0 4.6048-1.9225 4.6048-6.1548-.0134-3.87186-2.235-5.98134-4.5913-5.98134Zm-.8079 9.19894c-.7944 0-1.2656-.2804-1.5888-.6275l-.0134-4.95328c.35-.38719.8348-.65421 1.6022-.65421 1.2253 0 2.0735 1.36182 2.0735 3.11079 0 1.7891-.8347 3.1242-2.0735 3.1242Zm-9.6001-9.98666 3.3796-.72096V0l-3.3796.70761v2.72363Zm0 1.01469h3.3796V16.1282h-3.3796V4.44593Zm-3.6219.98798-.2154-.98798h-2.9083V16.1282h3.3661V8.21095c.7944-1.02804 2.1408-.84112 2.5582-.69426V4.44593c-.4309-.16022-2.0062-.45394-2.8006.98798Zm-6.7322-3.88518-3.2853.69426-.01346 10.69421c0 1.976 1.49456 3.4313 3.48726 3.4313 1.1041 0 1.912-.2003 2.3563-.4406v-2.7103c-.4309.1736-2.5583.7877-2.5583-1.1882V7.28972h2.5583V4.44593h-2.5583l.0135-2.8972ZM3.40649 7.83712c0-.5207.43086-.72096 1.14447-.72096 1.0233 0 2.31588.30707 3.33917.85447V4.83311c-1.11755-.44059-2.22162-.61415-3.33917-.61415C1.81769 4.21896 0 5.63418 0 7.99733c0 3.68487 5.11647 3.09747 5.11647 4.68627 0 .6141-.53858.8144-1.29258.8144-1.11755 0-2.54477-.4539-3.675782-1.0681v3.1776c1.252192.534 2.517842.761 3.675782.761 2.80059 0 4.72599-1.3752 4.72599-3.765-.01346-3.97867-5.14339-3.27106-5.14339-4.76638Z" fill="#fff"></path></svg>
                                </button>

                                <p>
                                    <?php echo sprintf(
                                        __( 'For more information, see our %1$sStripe Connect Setup Guide%2$s.', 'wp-full-stripe-free' ),
                                        '<a href="https://docs.themeisle.com/article/2100-step-by-step-guide-to-setup-stripe-on-fullpay-v7" target="_blank">',
                                        '</a>'
                                    ) ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="wpfs-form-group">
                                <?php if ( isset( $stripeData->liveAccountStatus ) && ( MM_WPFS_Options::OPTION_ACCOUNT_STATUS_COMPLETE === $stripeData->liveAccountStatus || MM_WPFS_Options::OPTION_ACCOUNT_STATUS_ENABLED === $stripeData->liveAccountStatus ) ): ?>
                                    <div class="wpfs-inline-message wpfs-inline-message--success wpfs-inline-message--w448">
                                        <div class="wpfs-inline-message__inner">
                                            <label class="wpfs-form-label"><?php esc_html_e( 'Connection Status', 'wp-full-stripe-free' ); ?></label>
                                            <p class="wpfs-center my-16">
                                                <strong><?php _e( 'Account Status:', 'wp-full-stripe-free' ); ?> <?php echo $stripeData->liveAccountStatus; ?></strong>
                                                <button class="wpfs-btn-secondary wpfs-button-loader"  id="wpfs-clear-live-stripe-connect-account">
                                                    <?php _e( 'Disconnect', 'wp-full-stripe-free' ); ?>
                                                </button>
                                            </p>

                                            <?php if ( ! WPFS_License::is_active() && ( $stripeData->useWpLivePlatform || $stripeData->useWpTestPlatform ) ): ?>
                                                <p>
                                                    <?php 
                                                      // translators: 1: commission percentage, 2: opening anchor tag, 3: closing anchor tag
                                                      echo sprintf(
                                                        __( 'Please note that you will be charged %1$s per transaction in addition to Stripe fees. %2$sUpgrade to Pro%3$s for no added fee and priority support.', 'wp-full-stripe-free' ), 
                                                        $commission,
                                                        '<a href="' . esc_url( tsdk_utmify( 'https://paymentsplugin.com/pricing/', 'admin-connection' ) ) . '" target="_blank">',
                                                        '</a>' 
                                                      );
                                                    ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="wpfs-inline-message wpfs-inline-message--success wpfs-inline-message--w448">
                                        <div class="wpfs-inline-message__inner">
                                            <p><strong><?php _e( 'Note:', 'wp-full-stripe-free' ); ?></strong>  <?php esc_html_e( 'Your account is not yet ready', 'wp-full-stripe-free' ); ?></p>
                                            <p><?php esc_html_e( 'Click the Update Account Information below to complete your setup.', 'wp-full-stripe-free' ); ?></p>

                                            <p>
                                                <a href="<?php echo $stripeData->liveAccountLink; ?>" class="wpfs-btn wpfs-btn-primary wpfs-button-loader">
                                                    <?php _e( 'Update Account Information', 'wp-full-stripe-free' ); ?>
                                                </a>
                                                <button class="wpfs-btn wpfs-btn-text wpfs-button-loader"  id="wpfs-clear-live-stripe-connect-account">
                                                    <?php _e( 'Clear Stripe test setup', 'wp-full-stripe-free' ); ?>
                                                </button>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if ( ! ( $stripeData->useWpTestPlatform ) ) : ?>
                        <div class="wpfs-form-group">
                            <label class="wpfs-form-label"><?php esc_html_e( 'Connection Status', 'wp-full-stripe-free' ); ?></label>
                            <button id="wpfs-create-test-stripe-connect-account" class="wpfs-btn-connect wpfs-button-loader">
                                <span><?php _e( 'Connect with', 'wp-full-stripe-free' ); ?></span>
                                <svg width="49" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M48.4718 10.3338c0-3.41791-1.6696-6.11484-4.8607-6.11484-3.2045 0-5.1434 2.69693-5.1434 6.08814 0 4.0187 2.289 6.048 5.5743 6.048 1.6023 0 2.8141-.3604 3.7296-.8678v-2.6702c-.9155.4539-1.9658.7343-3.2987.7343-1.3061 0-2.464-.4539-2.6121-2.0294h6.5841c0-.1735.0269-.8678.0269-1.1882Zm-6.6514-1.26838c0-1.50868.929-2.13618 1.7773-2.13618.8213 0 1.6965.6275 1.6965 2.13618h-3.4738Zm-8.5499-4.84646c-1.3195 0-2.1678.61415-2.639 1.04139l-.1751-.82777h-2.9621V20l3.3661-.7076.0134-3.7784c.4847.3471 1.1984.8411 2.3832.8411 2.4102 0 4.6048-1.9225 4.6048-6.1548-.0134-3.87186-2.235-5.98134-4.5913-5.98134Zm-.8079 9.19894c-.7944 0-1.2656-.2804-1.5888-.6275l-.0134-4.95328c.35-.38719.8348-.65421 1.6022-.65421 1.2253 0 2.0735 1.36182 2.0735 3.11079 0 1.7891-.8347 3.1242-2.0735 3.1242Zm-9.6001-9.98666 3.3796-.72096V0l-3.3796.70761v2.72363Zm0 1.01469h3.3796V16.1282h-3.3796V4.44593Zm-3.6219.98798-.2154-.98798h-2.9083V16.1282h3.3661V8.21095c.7944-1.02804 2.1408-.84112 2.5582-.69426V4.44593c-.4309-.16022-2.0062-.45394-2.8006.98798Zm-6.7322-3.88518-3.2853.69426-.01346 10.69421c0 1.976 1.49456 3.4313 3.48726 3.4313 1.1041 0 1.912-.2003 2.3563-.4406v-2.7103c-.4309.1736-2.5583.7877-2.5583-1.1882V7.28972h2.5583V4.44593h-2.5583l.0135-2.8972ZM3.40649 7.83712c0-.5207.43086-.72096 1.14447-.72096 1.0233 0 2.31588.30707 3.33917.85447V4.83311c-1.11755-.44059-2.22162-.61415-3.33917-.61415C1.81769 4.21896 0 5.63418 0 7.99733c0 3.68487 5.11647 3.09747 5.11647 4.68627 0 .6141-.53858.8144-1.29258.8144-1.11755 0-2.54477-.4539-3.675782-1.0681v3.1776c1.252192.534 2.517842.761 3.675782.761 2.80059 0 4.72599-1.3752 4.72599-3.765-.01346-3.97867-5.14339-3.27106-5.14339-4.76638Z" fill="#fff"></path></svg>
                            </button>

                            <p>
                                <?php echo sprintf(
                                    __( 'For more information, see our %1$sStripe Connect Setup Guide%2$s.', 'wp-full-stripe-free' ),
                                    '<a href="https://docs.themeisle.com/article/2100-step-by-step-guide-to-setup-stripe-on-fullpay-v7" target="_blank">',
                                    '</a>'
                                ) ?>
                            </p>
                        </div>

                        <!-- Test Stripe direct -->
                        <div class="wpfs-form-group">
                            <label for="<?php $view->testSecretKey()->id(); ?>" class="wpfs-form-label">
                                <?php $view->testSecretKey()->label(); ?>
                            </label>
                            <input id="<?php $view->testSecretKey()->id(); ?>"
                                name="<?php $view->testSecretKey()->name(); ?>" type="text"
                                value="<?php echo esc_html($stripeData->testSecretKey); ?>"
                                class="wpfs-form-control"
                            >
                        </div>
                        <div class="wpfs-form-group">
                            <label for="<?php $view->testPublishableKey()->id(); ?>" class="wpfs-form-label">
                                <?php $view->testPublishableKey()->label(); ?>
                            </label>
                            <input id="<?php $view->testPublishableKey()->id(); ?>"
                                name="<?php $view->testPublishableKey()->name(); ?>" type="text"
                                value="<?php echo esc_html($stripeData->testPublishableKey); ?>"
                                class="wpfs-form-control"
                            >
                        </div>
                    <?php else: ?>
                        <!-- Test connect -->
                        <?php if ( is_null( $stripeData->testAccountId ) ): ?>
                            <div class="wpfs-form-group">
                                <label class="wpfs-form-label"><?php esc_html_e( 'Connection Status', 'wp-full-stripe-free' ); ?></label>
                                <button id="wpfs-create-test-stripe-connect-account" class="wpfs-btn-connect wpfs-button-loader">
                                    <span><?php _e( 'Connect with', 'wp-full-stripe-free' ); ?></span>
                                    <svg width="49" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M48.4718 10.3338c0-3.41791-1.6696-6.11484-4.8607-6.11484-3.2045 0-5.1434 2.69693-5.1434 6.08814 0 4.0187 2.289 6.048 5.5743 6.048 1.6023 0 2.8141-.3604 3.7296-.8678v-2.6702c-.9155.4539-1.9658.7343-3.2987.7343-1.3061 0-2.464-.4539-2.6121-2.0294h6.5841c0-.1735.0269-.8678.0269-1.1882Zm-6.6514-1.26838c0-1.50868.929-2.13618 1.7773-2.13618.8213 0 1.6965.6275 1.6965 2.13618h-3.4738Zm-8.5499-4.84646c-1.3195 0-2.1678.61415-2.639 1.04139l-.1751-.82777h-2.9621V20l3.3661-.7076.0134-3.7784c.4847.3471 1.1984.8411 2.3832.8411 2.4102 0 4.6048-1.9225 4.6048-6.1548-.0134-3.87186-2.235-5.98134-4.5913-5.98134Zm-.8079 9.19894c-.7944 0-1.2656-.2804-1.5888-.6275l-.0134-4.95328c.35-.38719.8348-.65421 1.6022-.65421 1.2253 0 2.0735 1.36182 2.0735 3.11079 0 1.7891-.8347 3.1242-2.0735 3.1242Zm-9.6001-9.98666 3.3796-.72096V0l-3.3796.70761v2.72363Zm0 1.01469h3.3796V16.1282h-3.3796V4.44593Zm-3.6219.98798-.2154-.98798h-2.9083V16.1282h3.3661V8.21095c.7944-1.02804 2.1408-.84112 2.5582-.69426V4.44593c-.4309-.16022-2.0062-.45394-2.8006.98798Zm-6.7322-3.88518-3.2853.69426-.01346 10.69421c0 1.976 1.49456 3.4313 3.48726 3.4313 1.1041 0 1.912-.2003 2.3563-.4406v-2.7103c-.4309.1736-2.5583.7877-2.5583-1.1882V7.28972h2.5583V4.44593h-2.5583l.0135-2.8972ZM3.40649 7.83712c0-.5207.43086-.72096 1.14447-.72096 1.0233 0 2.31588.30707 3.33917.85447V4.83311c-1.11755-.44059-2.22162-.61415-3.33917-.61415C1.81769 4.21896 0 5.63418 0 7.99733c0 3.68487 5.11647 3.09747 5.11647 4.68627 0 .6141-.53858.8144-1.29258.8144-1.11755 0-2.54477-.4539-3.675782-1.0681v3.1776c1.252192.534 2.517842.761 3.675782.761 2.80059 0 4.72599-1.3752 4.72599-3.765-.01346-3.97867-5.14339-3.27106-5.14339-4.76638Z" fill="#fff"></path></svg>
                                </button>

                                <p>
                                    <?php echo sprintf(
                                    __( 'For more information, see our %1$sStripe Connect Setup Guide%2$s.', 'wp-full-stripe-free' ),
                                    '<a href="https://docs.themeisle.com/article/2100-step-by-step-guide-to-setup-stripe-on-fullpay-v7" target="_blank">',
                                    '</a>'
                                    ) ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="wpfs-form-group">
                                <?php if ( isset( $stripeData->testAccountStatus ) && ( MM_WPFS_Options::OPTION_ACCOUNT_STATUS_COMPLETE === $stripeData->testAccountStatus || MM_WPFS_Options::OPTION_ACCOUNT_STATUS_ENABLED === $stripeData->testAccountStatus ) ): ?>
                                    <div class="wpfs-inline-message wpfs-inline-message--success wpfs-inline-message--w448">
                                        <div class="wpfs-inline-message__inner">
                                            <label class="wpfs-form-label"><?php esc_html_e( 'Connection Status', 'wp-full-stripe-free' ); ?></label>
                                            <p class="wpfs-center my-16">
                                                <strong><?php _e( 'Account Status:', 'wp-full-stripe-free' ); ?> <?php echo $stripeData->testAccountStatus; ?></strong>
                                                <button class="wpfs-btn-secondary wpfs-button-loader"  id="wpfs-clear-test-stripe-connect-account">
                                                    <?php _e( 'Disconnect', 'wp-full-stripe-free' ); ?>
                                                </button>
                                            </p>

                                            <?php if ( ! WPFS_License::is_active() && ( $stripeData->useWpLivePlatform || $stripeData->useWpTestPlatform ) ): ?>
                                                <p>
                                                    <?php 
                                                      // translators: 1: commission percentage, 2: opening anchor tag, 3: closing anchor tag
                                                      echo sprintf(
                                                        __( 'Please note that you will be charged %1$s per transaction in addition to Stripe fees. %2$sUpgrade to Pro%3$s for no added fee and priority support.', 'wp-full-stripe-free' ), 
                                                        $commission,
                                                        '<a href="' . esc_url( tsdk_utmify( 'https://paymentsplugin.com/pricing/', 'admin-connection' ) ) . '" target="_blank">',
                                                        '</a>'
                                                      );
                                                    ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="wpfs-inline-message wpfs-inline-message--success wpfs-inline-message--w448">
                                        <div class="wpfs-inline-message__inner">
                                            <p><strong><?php _e( 'Note:', 'wp-full-stripe-free' ); ?></strong>  <?php esc_html_e( 'Your account is not yet ready', 'wp-full-stripe-free' ); ?></p>
                                            <p><?php esc_html_e( 'Click the Update Account Information below to complete your setup.', 'wp-full-stripe-free' ); ?></p>

                                            <p>
                                                <a href="<?php echo $stripeData->testAccountLink; ?>" class="wpfs-btn wpfs-btn-primary wpfs-button-loader">
                                                    <?php _e( 'Update Account Information', 'wp-full-stripe-free' ); ?>
                                                </a>
                                                <button class="wpfs-btn wpfs-btn-text wpfs-button-loader"  id="wpfs-clear-test-stripe-connect-account">
                                                    <?php _e( 'Clear Stripe test setup', 'wp-full-stripe-free' ); ?>
                                                </button>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- mode toggle -->
            <div class="wpfs-form-block">
                <div class="wpfs-form-group">
                    <label class="wpfs-form-label"><?php esc_html_e( 'Account Mode', 'wp-full-stripe-free' ); ?></label>
                    <div class="wpfs-form-check-list">
                        <div class="wpfs-form-check">
                            <input class="wpfs-form-check-input" id="<?php $view->apiMode()->id(); ?>-test" name="<?php $view->apiMode()->name(); ?>" type="radio" value="<?php echo MM_WPFS::STRIPE_API_MODE_TEST; ?>"  <?php echo $stripeData->apiMode === MM_WPFS::STRIPE_API_MODE_TEST ? 'checked' : ''; ?>>
                            <label class="wpfs-form-check-label" for="<?php $view->apiMode()->id(); ?>-test"><?php esc_html_e('Test', 'wp-full-stripe-free'); ?></label>
                        </div>
                    </div>
                    <div class="wpfs-form-check-list">
                        <div class="wpfs-form-check">
                            <input class="wpfs-form-check-input" id="<?php $view->apiMode()->id(); ?>-live" name="<?php $view->apiMode()->name(); ?>" type="radio" value="<?php echo MM_WPFS::STRIPE_API_MODE_LIVE; ?>"  <?php echo $stripeData->apiMode === MM_WPFS::STRIPE_API_MODE_LIVE ? 'checked' : ''; ?>>
                            <label class="wpfs-form-check-label" for="<?php $view->apiMode()->id(); ?>-live"><?php esc_html_e('Live', 'wp-full-stripe-free'); ?></label>
                        </div>
                    </div>
                    <p>
                        <?php esc_html_e( 'While in Test Mode, real payments will not be processed. Before adding the form(s) to your website, switch to Live Mode and create duplicate forms with Live products and pricing.', 'wp-full-stripe-free' ); ?>
                    </p>
                </div>
            </div>

            <!-- save/cancel -->
            <div class="wpfs-form-actions">
                <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader" type="submit">
                    <?php esc_html_e('Save settings', 'wp-full-stripe-free'); ?>
                </button>
            </div>
        </div>
    </div>
</form>