<?php
    $is_active  = WPFS_License::is_active();
    $is_expired = WPFS_License::is_expired();
    $status     = $is_active ? __( 'Active', 'wp-full-stripe-free' ) : ( $is_expired ? __( 'Expired', 'wp-full-stripe-free' ) : __( 'Inactive', 'wp-full-stripe-free' ) );
    $icon       = $is_active ? 'wpfs-icon-check' : 'wpfs-icon-error';
    $settings   = WPFS_License::get_activation_url();
?>
<a class="wpfs-btn wpfs-btn-outline-primary wpfs-page-header__license js-tooltip" href="<?php echo esc_url( $settings ); ?>" data-tooltip-content="license-tooltip">
    <span class="<?php esc_attr( $icon ); ?>"></span>
    <?php echo $status; ?>
</a>
<div class="wpfs-tooltip-content" data-tooltip-id="license-tooltip">
    <div class="wpfs-info-tooltip"><?php esc_html_e( 'Manage License', 'wp-full-stripe-free' ); ?></div>
</div>
