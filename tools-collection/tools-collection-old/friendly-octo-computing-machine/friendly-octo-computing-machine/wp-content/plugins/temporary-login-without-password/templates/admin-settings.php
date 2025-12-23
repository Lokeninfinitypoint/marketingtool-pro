<?php
/**
 * Admin Settings Template
 *
 * @package Temporary Login Without Password
 */
 
/**
 * Filter to render custom tab 
 */
$base_url = admin_url( 'users.php?page=wp-temporary-login-without-password&tab=' );
$tabs = array(
	'home' => array(
		'url'  => $base_url . 'home',
		'name' => __( 'Temporary Logins', 'temporary-login-without-password' ),
		'visible' => ! $is_temporary_login, 
		'priority'=> 10,
	),
	'settings' => array(
		'url'  => $base_url . 'settings',
		'name' => __( 'Settings', 'temporary-login-without-password' ),
		'visible' => ! $is_temporary_login, 
		'priority'=> 20,
	),
	'system-info' => array(
		'url'  => $base_url . 'system-info',
		'name' => __( 'System Info', 'temporary-login-without-password' ),
		'visible' => true, 
		'priority'=> 90,
	),
	'other-plugins' => array(
		'url'  => $base_url . 'other-plugins',
		'name' => __( 'Other Awesome Plugins', 'temporary-login-without-password' ),
		'visible' => true,
		'priority'=> 100, 
	), 
); 

$filtered_tabs =  apply_filters( 'tlwp_custom_extra_tab', $tabs, $active_tab ); 
// 🔹 Sort tabs by priority
uasort( $filtered_tabs, function( $a, $b ) {
    if ( $a['priority'] == $b['priority'] ) {
        return 0;
    }
    return ( $a['priority'] < $b['priority'] ) ? -1 : 1;
});
?>
<h2 class="nav-tab-wrapper">
	<?php
	foreach ( $filtered_tabs as $slug => $tab ) { 

		if ( empty( $tab['visible'] ) ) {
			continue;
		}

		$active = ( $slug === $active_tab ) ? 'nav-tab-active' : ''; ?>
		<a href="<?php echo esc_url( $tab['url'] ); ?>" class="nav-tab <?php echo esc_attr( $active ); ?>">
		 	<?php echo esc_html( $tab['name'] ); ?>
		</a> 
	<?php } ?>
	<?php if (! Wp_Temporary_Login_Without_Password::is_pro() ) { ?>
		<a href="<?php echo esc_url( admin_url( 'users.php?page=wp-temporary-login-without-password&tab=tlwp-pricing' ) ); ?>" class="nav-tab <?php echo 'tlwp-pricing' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__( 'Go Pro 🔥', 'temporary-login-without-password' ); ?></a>
		<?php
	}?>
</h2>

<?php  
if ( 'home' === $active_tab && !$is_temporary_login ) { ?>
	<div class="wrap wtlwp wtlwp-settings-wrap" id="temporary-logins">
		<div class="w-full flex flex-row justify-between items-center mt-4">
			<div class="w-3/4"> 
				<h2 class="font-semibold text-gray-700">
					<?php //echo esc_html__( 'Temporary Logins', 'temporary-login-without-password' ); ?> 
					<span class="cursor-pointer ml-3 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white transition duration-150 ease-in-out px-3 py-1 hover:bg-gray-50 hover:text-gray-700 focus:ring-2 focus:ring-blue-200" id="add-new-wtlwp-form-button"><?php esc_html_e( 'Create New', 'temporary-login-without-password' ); ?></span>		
				</h2>
			</div>
			<div class="w-1/5 text-sm text-gray-600 text-right">
				<?php echo esc_html( Wp_Temporary_Login_Without_Password_Common::count_tlwp_users() . ' Users' ); ?>
			</div>
		</div>
		
		<div class="wtlwp wtlwp-settings">
			<!-- Add New Form Start -->

			<div class="wrap new-wtlwp-form" id="new-wtlwp-form">
				<?php include WTLWP_PLUGIN_DIR . '/templates/new-login.php'; ?>
			</div>

			<?php
			
			if ( $do_update ) { ?>

				<div class="wrap update-wtlwp-form" id="update-wtlwp-form">
					<?php 
					include WTLWP_PLUGIN_DIR . '/templates/update-login.php'; ?>
				</div>

			<?php } ?>

			<?php 
            $wtlwp_generated_url = esc_url( $wtlwp_generated_url );
			if ( ! empty( $wtlwp_generated_url ) ) { 
				$is_one_click_user_active = get_transient( 'wtlwp_one_click_user_active' );
				$user_id   = get_transient( 'wtlwp_one_click_user_id' );
				$enable_login_url = Wp_Temporary_Login_Without_Password_Common::get_manage_login_url( $user_id, 'enable' );
				?>

	<div class="wrap rounded-md bg-white shadow-md my-4 py-4 pl-4 pr-3 border-indigo-600 border-2" id="generated-wtlwp-login-link">
		<p class="py-1.5 text-gray-500 font-medium tracking-wide text-sm">
						<?php esc_attr_e( "Here's a temporary login link", 'temporary-login-without-password' ); ?>
		</p>

		<input id="wtlwp-click-to-copy-btn" type="text" class="wtlwp-wide-input form-input text-sm" value="<?php echo esc_url( $wtlwp_generated_url ); ?>">

		<?php if ( 'yes' === $is_one_click_user_active ) : ?>
			<!-- Show Enable Button -->
			<button id="wtlwp-enable-one-click-login" 
				data-user-id="<?php echo esc_attr( $user_id ); ?>" 
				title="<?php esc_attr_e( 'Enable One Click Login', 'temporary-login-without-password' ); ?>"
				class="p-2 border-transparent text-indigo-600 rounded-full hover:text-gray-600 focus:outline-none focus:text-gray-600 focus:bg-gray-100 transition duration-150 ease-in-out ml-1 hover:rounded-full hover:bg-gray-100 inline-flex items-center">
				<svg class="w-6 h-6 inline-block -mt-1" fill="currentColor" viewBox="0 0 24 24">
					<title><?php esc_html_e( 'Enable', 'temporary-login-without-password' ); ?></title>
					<path d="M17 8h-1V6a4 4 0 00-8 0v2H7a1 1 0 00-1 1v11a1 1 0 001 1h10a1 1 0 001-1V9a1 1 0 00-1-1zm-6 8a1 1 0 112 0 1 1 0 01-2 0zm-2-8V6a2 2 0 014 0v2H9z"/>
				</svg>
			</button>
		<?php endif; ?>

		<!-- Copy Button & Info Text for all users -->
		<button class="wtlwp-copy-to-clipboard p-2 border-transparent text-indigo-600 rounded-full hover:text-gray-600 focus:outline-none focus:text-gray-600 focus:bg-gray-100 transition duration-150 ease-in-out ml-1 hover:rounded-full hover:bg-gray-100 <?php echo ( 'yes' === $is_one_click_user_active ) ? 'hidden' : ''; ?>" data-clipboard-action="copy" data-clipboard-target="#wtlwp-click-to-copy-btn">
			<svg class="w-6 h-6 inline-block -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<title><?php echo esc_html__( 'Copy', 'temporary-login-without-password' ); ?></title>
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
			</svg>
		</button>

		<span id="copied-text-message-wtlwp-click-to-copy-btn"></span>
		<p class="py-1.5 tracking-wide text-gray-600 wtlwp-copy-to-clipboard-text <?php echo ( 'yes' === $is_one_click_user_active ) ? 'hidden' : ''; ?>">
			<?php esc_attr_e( 'User can directly login to WordPress admin panel without username and password by opening this link.', 'temporary-login-without-password' ); ?>
			<?php if ( ! empty( $user_email ) ) : ?>
				<?php 
				    // translators: %s: mailto link.
					echo ' ' . sprintf( __( '<a href="%s">Email</a> temporary login link to user', 'temporary-login-without-password' ), esc_url( $mailto_link ) ); ?>
			<?php endif; ?>
		</p>
	</div>
			<?php } ?>

			<!-- Add New Form End -->

			<!-- List All Generated Logins Start -->
			<div class="wrap list-wtlwp-logins mt-4" id="list-wtlwp-logins">
				<?php load_template( WTLWP_PLUGIN_DIR . '/templates/list-temporary-logins.php' ); ?>
			</div>
			<!-- List All Generated Logins End -->
		</div>
	</div>
<?php } elseif ( 'settings' === $active_tab && ! $is_temporary_login) { ?>
	<div class="wrap wtlwp list-wtlwp-logins" id="wtlwp-logins-settings">
		<?php include WTLWP_PLUGIN_DIR . '/templates/temporary-logins-settings.php'; ?>
	</div>

<?php } elseif ('other-plugins' === $active_tab ) { ?>
	<div class="wrap wtlwp list-wtlwp-logins" id="wtlwp-logins-other-plugins">
		<?php include WTLWP_PLUGIN_DIR . '/templates/other-plugins.php'; ?>
	</div>

<?php } elseif('tlwp-pricing' === $active_tab) {?>
	<div class="wrap wtlwp tlwp-pricing" id="wtlwp-logins-pricing">
		<?php include WTLWP_PLUGIN_DIR . '/templates/pricing.php'; ?>
	</div>

<?php } elseif('system-info' === $active_tab) { ?>
	<div class="wrap wtlwp tlwp-sytem-info" id="tlwp-system-info">
		<?php include WTLWP_PLUGIN_DIR . '/templates/system-info.php'; ?>
	</div>
<?php } ?>

<?php
/**
 * Hook to render custom tab content 
 */
do_action( 'tlwp_custom_extra_tab_content' );
?> 
