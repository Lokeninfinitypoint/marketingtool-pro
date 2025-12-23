<?php
/**
 * Password Login Form
 *
 * @package Neve_Pro\Modules\Access_Restriction\Templates
 * @since   3.6.0
 */
?>
<form class="nv-pswd-form" method="POST" action="<?php echo esc_url( site_url( 'wp-login.php?action=arpass' ) ); ?>">
	<?php wp_nonce_field( 'nv_ar_pass_form', 'nv_ar_nonce' ); ?>
	<?php esc_html_e( 'This content is password protected. To view it please enter your password below:', 'neve-pro-addon' ); ?>
	<ul>
		<li>
			<label><?php esc_html_e( 'Password', 'neve-pro-addon' ); ?></label>
		</li>
		<li>
			<input type="password" placeholder="<?php echo esc_attr( __( 'Enter your password', 'neve-pro-addon' ) ); ?>" name="nv_ar_password" />
		</li>
		<li>
			<button class="form-submit" type="submit"><?php echo esc_html_e( 'Enter', 'neve-pro-addon' ); ?></button>
		</li>
	</ul>
</form>
