<?php
/** @var $backLinkUrl */
?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-settings-license">
        <?php include('partials/wpfs-header-with-back-link.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <div class="wpfs-page-settings-container">
            <?php include('partials/wpfs-settings-sidebar.php'); ?>

            <div class="wpfs-form__cols">
                <div class="wpfs-form__col">
                    <div class="wpfs-form-block">
                        <?php if ( WPFS_License::is_active() || WPFS_License::is_expired() ): ?>
                            <form id="wpfs-form-license-deactivate" class="wpfs-form" method="post">
                                <input id="wpfs-form-license" name="wpfs-form-license" type="hidden" class="wpfs-form-control" value="<?php echo WPFS_License::get_key(); ?>">
                                <div class="wpfs-inline-message wpfs-inline-message--success wpfs-inline-message--w448">
                                    <div class="wpfs-inline-message__inner">
                                        <p class="wpfs-center my-16">
                                            <strong><?php esc_html_e( 'WP Full Pay License', 'wp-full-stripe-free' ); ?></strong>
                                            <button class="wpfs-btn-secondary wpfs-button-loader"  id="wpfs-clear-license">
                                                <?php _e( 'Disconnect', 'wp-full-stripe-free' ); ?>
                                            </button>
                                        </p>

                                        <p class="my-16">
                                            <span class="wpfs-center">
                                                <strong><?php _e( 'Status:', 'wp-full-stripe-free' ); ?></strong> <?php WPFS_License::is_active() ? _e( 'Active', 'wp-full-stripe-free' ) : _e( 'Expired', 'wp-full-stripe-free' ); ?>
                                            </span>
                                            <span class="wpfs-center">
                                                <strong><?php _e( 'Expiration:', 'wp-full-stripe-free' ); ?></strong> <?php echo WPFS_License::get_expiration_date( 'j F, Y' ); ?>
                                            </span>
                                        </p>

                                        <?php if ( WPFS_License::is_expired() ): ?>
                                            <p>
                                                <?php _e( 'Your license has expired. In order to continue receiving support and software updates you must renew your license key.', 'wp-full-stripe-free' ); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        <?php else: ?>
                        <form id="wpfs-form-license-activate" class="wpfs-form" method="post">
                            <div class="wpfs-form-group">
                                <label for="wpfs-form-license" class="wpfs-form-label"><?php esc_html_e( 'WP Full Pay License', 'wp-full-stripe-free' ); ?></label>
                                <input id="wpfs-form-license" name="wpfs-form-license" type="text" class="wpfs-form-control" value="<?php echo WPFS_License::get_key(); ?>">
                            </div>

                            <div class="wpfs-form-group">
                                <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader" type="submit"><?php esc_html_e( 'Activate', 'wp-full-stripe-free' ); ?></button>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ( ! WPFS_License::is_active() ): ?>
                    <div class="wpfs-form__col">
                        <div class="wpfs-inline-message wpfs-inline-message--info wpfs-inline-message--w448">
                            <div class="wpfs-inline-message__inner">
                                <div class="wpfs-inline-message__title"><?php esc_html_e( 'Where to get my license key?', 'wp-full-stripe-free' ); ?></div>
                                <p>
                                    <?php
                                        printf(
                                            esc_html__( 'Enter your license from %1$sThemeisle purchase history%2$s in order to get plugin updates.', 'wp-full-stripe-free' ),
                                            '<a href="https://store.themeisle.com/" target="_blank">',
                                            '</a>'
                                        );
                                    ?>
                                </p>

                                <p>
                                    <?php 
                                    // translators: 1: opening anchor tag, 2: closing anchor tag
                                    echo sprintf(
                                        __( '%1$sUpgrade to Pro%2$s for no added fee and priority support.', 'wp-full-stripe-free' ), 
                                        '<a href="' . esc_url( tsdk_utmify( 'https://paymentsplugin.com/pricing/', 'admin-connection' ) ) . '" target="_blank">',
                                        '</a>' 
                                    );
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div id="wpfs-success-message-container"></div>
        </div>
    </div>
    <div id="wpfs-dialog-container"></div>
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
