<?php

use DeployerForGit\Helper;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( isset( $install_result ) ) {
    ?>
	<?php 
    if ( !is_wp_error( $install_result ) ) {
        ?>
		<div class="updated">
			<p>
				<?php 
        echo esc_attr__( 'Theme was successfully installed', 'deployer-for-git' );
        ?>
			</p>
		</div>
	<?php 
    } else {
        ?>
		<div class="error">
			<p>
				<!-- <strong><?php 
        echo esc_attr( $install_result->get_error_code() );
        ?></strong> -->
				<?php 
        echo esc_attr( $install_result->get_error_message() );
        ?>
			</p>
		</div>
	<?php 
    }
}
?>

<div class="wrap">
	<h1><?php 
echo esc_attr__( 'Install Theme', 'deployer-for-git' );
?></h1>
	<form class="dfg_install_package_form" method="post" action="">
		<input type="hidden" name="<?php 
echo esc_attr( DFG_SLUG . '_install_package_submitted' );
?>" value="1">
		<input type="hidden" name="package_type" value="theme">

		<?php 
wp_nonce_field( DFG_SLUG . '_install_package_form', DFG_SLUG . '_nonce' );
?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php 
echo esc_attr__( 'Provider Type', 'deployer-for-git' );
?></th>
				<td>
					<select name='provider_type'>
						<option value="" selected disabled><?php 
echo esc_attr__( 'Choose a provider', 'deployer-for-git' );
?></option>
						<?php 
foreach ( Helper::available_providers() as $provider_id => $name ) {
    ?>
							<option value="<?php 
    echo esc_attr( $provider_id );
    ?>"><?php 
    echo esc_attr( $name );
    ?></option>
						<?php 
}
?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php 
echo esc_attr__( 'Repository URL', 'deployer-for-git' );
?></th>
				<td>
					<input type="url" class="regular-text code" name="repository_url" value="" />
					<p class="description dfg_repo_url_description dfg_hidden" id="bitbucket-repo-url-description"><?php 
echo esc_attr__( 'Example: https://bitbucket.org/owner/wordpress-theme-name', 'deployer-for-git' );
?></p>
					<p class="description dfg_repo_url_description dfg_hidden" id="github-repo-url-description"><?php 
echo esc_attr__( 'Example: https://github.com/owner/wordpress-theme-name', 'deployer-for-git' );
?></p>
					<p class="description dfg_repo_url_description dfg_hidden" id="gitea-repo-url-description"><?php 
echo esc_attr__( 'Example: https://gitea.com/owner/wordpress-theme-name', 'deployer-for-git' );
?></p>
					<p class="description dfg_repo_url_description dfg_hidden" id="gitlab-repo-url-description"><?php 
echo esc_attr__( 'Example: https://gitlab.com/owner/wordpress-theme-name', 'deployer-for-git' );
?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php 
echo esc_attr__( 'Branch', 'deployer-for-git' );
?> <span class="dashicons dashicons-randomize"></span></th>
				<td>
					<input type="text" class="" placeholder="master" name="repository_branch" value="" />
					<p class="description"><?php 
echo esc_attr__( 'default is "master"', 'deployer-for-git' );
?></p>
				</td>
			</tr>

			<?php 
$private_repository_row_class = 'free';
?>

			<tr valign="top" class="dfg_hidden dfg_is_private_repository_row <?php 
echo esc_attr( $private_repository_row_class );
?>" >
				<th scope="row">
					<?php 
echo esc_attr__( 'Is Private Repository', 'deployer-for-git' );
?>

					<?php 
?>
						<br>
						<small><?php 
echo esc_attr__( '[Available in PRO version]', 'deployer-for-git' );
?></small>
					<?php 
?>

					<span class="dashicons dashicons-lock"></span>
				</th>
				<td><input type="checkbox" name="is_private_repository" value="1"></td>
			</tr>

			<?php 
?>
		</table>
		<div class="submit">
			<input type="submit" class="button-primary" value="<?php 
echo esc_attr__( 'Install Theme', 'deployer-for-git' );
?>" /><br><br>
			<p class="description"><i><?php 
echo esc_attr__( 'Note that if a theme with specified slug is already installed, this action will overwrite the already existing theme.', 'deployer-for-git' );
?><i></p>
		</div>
	</form>
</div>
