<?php
use DeployerForGit\Logger;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1>
		<?php echo esc_attr__( 'Logs', 'deployer-for-git' ); ?>
	</h1>

	<?php if ( isset( $form_submitted ) && $form_submitted ) : ?>
		<?php if ( isset( $clear_log_result ) && $clear_log_result ) : ?>
			<div class="updated">
				<p><?php echo esc_attr__( 'Log file cleared', 'deployer-for-git' ); ?></p>
			</div>
		<?php else : ?>
			<div class="error">
				<p><?php echo esc_attr__( 'Can\'t clear the log file.', 'deployer-for-git' ); ?></p>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php $logger = new Logger(); ?>
	<textarea class="large-text code dfg_log_textarea" readonly><?php echo esc_textarea( $logger->display_log_content() ); ?></textarea>

	<form method="post" action="" onsubmit="return confirm('<?php echo esc_attr__( 'Are you sure?', 'deployer-for-git' ); ?>');">
		<input type="hidden" name="action" value="clear_log_file">
		<?php wp_nonce_field( DFG_SLUG . '_clear_log_file', DFG_SLUG . '_nonce' ); ?>
		<input type="submit" class="button button-primary" value="<?php echo esc_attr__( 'Clear log file', 'deployer-for-git' ); ?>">
	</form>
</div>
