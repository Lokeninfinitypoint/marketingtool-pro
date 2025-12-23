<?php
?>
    <div class="wpfs-wizard">
        <div class="wpfs-container">
            <div class="wpfs-logo">
                <img src="<?php echo MM_WPFS_Assets::images( 'wpfs-logo.svg' ); ?>" alt="<?php _e( 'WP Full Pay', 'wp-full-stripe-free' ); ?>">
            </div>

            <div class="wpfs-card">
                <div class="wpfs-progress">
                    <div class="wpfs-step-marker wpfs-active">
                        <span class="wpfs-check"></span>
                    </div>
                    <div class="wpfs-progress-line"></div>
                    <div class="wpfs-step-marker">
                        <span class="wpfs-check"></span>
                    </div>
                    <div class="wpfs-progress-line"></div>
                    <div class="wpfs-step-marker">
                        <span class="wpfs-check"></span>
                    </div>
                </div>

                <div id="wpfs-step1" class="wpfs-step wpfs-active" data-step="connect">
                    <p class="wpfs-subtitle"><?php _e( 'Step 1 of 3', 'wp-full-stripe-free' ); ?></p>
                    <h1 class="wpfs-title"><?php _e( 'Connect to Stripe', 'wp-full-stripe-free' ); ?></h1>

                    <div class="wpfs-content-row">
                        <div class="wpfs-content-column">
                            <p class="wpfs-text-description"><?php _e( 'Start accepting payments in minutes with Stripe Connect - the most secure way to integrate Stripe with WordPress. Your account will be automatically configured with industry-best security settings.', 'wp-full-stripe-free' ); ?></p>
                        </div>
                        <div class="wpfs-content-column">
                            <ul class="wpfs-feature-list">
                                <li class="wpfs-feature-item">
                                    <span class="wpfs-feature-icon">✓</span>
                                    <?php _e( 'Enhanced security with Stripe Connect integration', 'wp-full-stripe-free' ); ?>
                                </li>
                                <li class="wpfs-feature-item">
                                    <span class="wpfs-feature-icon">✓</span>
                                    <?php _e( 'Accept payments in 135+ currencies worldwide', 'wp-full-stripe-free' ); ?>
                                </li>
                                <li class="wpfs-feature-item">
                                    <span class="wpfs-feature-icon">✓</span>
                                    <?php _e( 'Smart fraud prevention built-in', 'wp-full-stripe-free' ); ?>
                                </li>
                                <li class="wpfs-feature-item">
                                    <span class="wpfs-feature-icon">✓</span>
                                    <?php _e( 'Seamless checkout optimized for conversion', 'wp-full-stripe-free' ); ?>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <button id="wpfs-create-test-stripe-connect-account" class="wpfs-btn-connect wpfs-button-loader">
                        <span><?php _e( 'Connect with', 'wp-full-stripe-free' ); ?></span>
                        <svg width="49" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M48.4718 10.3338c0-3.41791-1.6696-6.11484-4.8607-6.11484-3.2045 0-5.1434 2.69693-5.1434 6.08814 0 4.0187 2.289 6.048 5.5743 6.048 1.6023 0 2.8141-.3604 3.7296-.8678v-2.6702c-.9155.4539-1.9658.7343-3.2987.7343-1.3061 0-2.464-.4539-2.6121-2.0294h6.5841c0-.1735.0269-.8678.0269-1.1882Zm-6.6514-1.26838c0-1.50868.929-2.13618 1.7773-2.13618.8213 0 1.6965.6275 1.6965 2.13618h-3.4738Zm-8.5499-4.84646c-1.3195 0-2.1678.61415-2.639 1.04139l-.1751-.82777h-2.9621V20l3.3661-.7076.0134-3.7784c.4847.3471 1.1984.8411 2.3832.8411 2.4102 0 4.6048-1.9225 4.6048-6.1548-.0134-3.87186-2.235-5.98134-4.5913-5.98134Zm-.8079 9.19894c-.7944 0-1.2656-.2804-1.5888-.6275l-.0134-4.95328c.35-.38719.8348-.65421 1.6022-.65421 1.2253 0 2.0735 1.36182 2.0735 3.11079 0 1.7891-.8347 3.1242-2.0735 3.1242Zm-9.6001-9.98666 3.3796-.72096V0l-3.3796.70761v2.72363Zm0 1.01469h3.3796V16.1282h-3.3796V4.44593Zm-3.6219.98798-.2154-.98798h-2.9083V16.1282h3.3661V8.21095c.7944-1.02804 2.1408-.84112 2.5582-.69426V4.44593c-.4309-.16022-2.0062-.45394-2.8006.98798Zm-6.7322-3.88518-3.2853.69426-.01346 10.69421c0 1.976 1.49456 3.4313 3.48726 3.4313 1.1041 0 1.912-.2003 2.3563-.4406v-2.7103c-.4309.1736-2.5583.7877-2.5583-1.1882V7.28972h2.5583V4.44593h-2.5583l.0135-2.8972ZM3.40649 7.83712c0-.5207.43086-.72096 1.14447-.72096 1.0233 0 2.31588.30707 3.33917.85447V4.83311c-1.11755-.44059-2.22162-.61415-3.33917-.61415C1.81769 4.21896 0 5.63418 0 7.99733c0 3.68487 5.11647 3.09747 5.11647 4.68627 0 .6141-.53858.8144-1.29258.8144-1.11755 0-2.54477-.4539-3.675782-1.0681v3.1776c1.252192.534 2.517842.761 3.675782.761 2.80059 0 4.72599-1.3752 4.72599-3.765-.01346-3.97867-5.14339-3.27106-5.14339-4.76638Z" fill="#fff"></path></svg>
                    </button>
                </div>

                <div id="wpfs-step2" class="wpfs-step" data-step="email">
                    <p class="wpfs-subtitle"><?php _e( 'Step 2 of 3', 'wp-full-stripe-free' ); ?></p>
                    <h1 class="wpfs-title"><?php _e( 'Payment Form Optimization', 'wp-full-stripe-free' ); ?></h1>

                    <p class="wpfs-text-description"><?php _e( 'Receive expert guidance on optimizing your payment forms for maximum conversions and revenue growth.', 'wp-full-stripe-free' ); ?></p>

                    <div>
                        <label class="wpfs-input-label"><?php _e( 'Where should we send your optimization tips?', 'wp-full-stripe-free' ); ?></label>
                        <input type="email" id="wpfs-subscribe-email" class="wpfs-input" placeholder="<?php _e( 'Enter your email address', 'wp-full-stripe-free' ); ?>" value="<?php echo wp_get_current_user()->user_email; ?>">
                        <p class="wpfs-input-hint"><?php _e( 'We\'ll send optimization recommendations to this email.', 'wp-full-stripe-free' ); ?></p>
                    </div>

                    <div class="wpfs-actions wpfs-actions-right">
                        <button class="wpfs-btn-secondary wpfs-next"><?php _e( 'Skip Step', 'wp-full-stripe-free' ); ?></button>
                        <button id="wpfs-subscribe-brevo" class="wpfs-btn wpfs-btn-primary wpfs-button-loader"><?php _e( 'Continue →', 'wp-full-stripe-free' ); ?></button>
                    </div>
                </div>
                <div id="wpfs-step3" class="wpfs-step" data-step="finished">
                    <p class="wpfs-subtitle"><?php _e( 'Step 3 of 3', 'wp-full-stripe-free' ); ?></p>
                    <h1 class="wpfs-title"><?php _e( '🎉 Setup Complete', 'wp-full-stripe-free' ); ?></h1>

                    <p class="wpfs-text-description"><?php _e( 'Your WP Full Pay plugin is now connected to Stripe securely. You\'re ready to create your first payment form and start accepting payments.', 'wp-full-stripe-free' ); ?></p>

                    <div class="wpfs-actions">
                        <a href="<?php echo MM_WPFS_Admin_Menu::getAdminUrlBySlug( self::SLUG_CREATE_FORM ); ?>" class="wpfs-btn wpfs-btn-primary wpfs-next"><?php _e( 'Create Payment Form', 'wp-full-stripe-free' ); ?></a>
                        <a href="<?php echo MM_WPFS_Admin_Menu::getAdminUrlBySlug( self::SLUG_FORMS ); ?>" class="wpfs-btn wpfs-btn-secondary"><?php _e( 'Return to Dashboard', 'wp-full-stripe-free' ); ?></a>
                    </div>
                </div>
            </div>

            <div class="wpfs-footer">
                <a href="<?php echo MM_WPFS_Admin_Menu::getAdminUrlBySlug( self::SLUG_FORMS ); ?>" class="wpfs-btn wpfs-btn-secondary"><?php _e( 'Close and exit the Setup Wizard', 'wp-full-stripe-free' ); ?></a>
            </div>
        </div>
    </div>
<?php
?>