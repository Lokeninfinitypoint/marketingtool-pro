<?php
use DeployerForGit\ApiRequests\PackageUpdate;
use DeployerForGit\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h3>
	<span class="dashicons dashicons-admin-appearance"></span>
	<?php echo esc_attr__( 'Installed Themes', 'deployer-for-git' ); ?> (<?php echo count( $theme_list ); ?>)
</h3>

<?php if ( empty( $theme_list ) ) : ?>
	<?php
		$theme_istall_page_url = \DeployerForGit\Helper::install_theme_url();
	?>
	<div><?php echo esc_attr__( 'No themes installed yet...  Go and install one.', 'deployer-for-git' ); ?></div>
	<div class="dfg_mt_10" >
		<a href="<?php echo esc_url( $theme_istall_page_url ); ?>" class="button">
			<?php echo esc_attr__( 'Install Theme', 'deployer-for-git' ); ?>
		</a>
	</div>
<?php endif; ?>

<div class="dfg_package_boxes">
	<?php foreach ( $theme_list as $theme_info ) : ?>
		<?php $endpoint_url = PackageUpdate::package_update_url( $theme_info['slug'], 'theme' ); ?>
		<div class="dfg_package_box">
			<div>
				<h3>
					<?php
					$provider_logo_url = plugin_dir_url( DFG_FILE ) . 'assets/img/' . $theme_info['provider'] . '-logo.svg';
					?>
					<img src="<?php echo esc_url( $provider_logo_url ); ?>" alt="<?php echo esc_attr( DeployerForGit\Helper::available_providers()[ $theme_info['provider'] ] ); ?>" >
					<?php echo esc_attr( $theme_info['slug'] ); ?>
					<?php if ( $theme_info['is_private_repository'] ) : ?>

						<i class="dashicons dashicons-lock" title="<?php echo esc_attr__( 'Private Repo', 'deployer-for-git' ); ?>"></i>

						<?php if ( 'github' === $theme_info['provider'] ) : ?>
							<?php $pat_token_type = Helper::is_github_pat_token( $theme_info['options']['access_token'] ); ?>
							<?php if ( $pat_token_type ) : ?>
								<i class="dashicons dashicons-post-status" title="<?php echo $pat_token_type ? esc_attr__( 'Fine-grained personal access token used', 'deployer-for-git' ) : ''; ?>"></i>
							<?php endif; ?>
						<?php endif; ?>

					<?php else : ?>
						<i class="dashicons dashicons-unlock" title="<?php echo esc_attr__( 'Public Repo', 'deployer-for-git' ); ?>"></i>
					<?php endif; ?>
					<?php
					$current_theme = wp_get_theme();
					if ( $current_theme->get_template() === $theme_info['slug'] ) :
						?>
						| <span><?php echo esc_attr__( 'Active', 'deployer-for-git' ); ?></span>
						<?php
					endif;
					?>
				</h3>
				<ul>
					<li>
					<?php echo esc_attr__( 'Branch', 'deployer-for-git' ); ?>
						<i class="dashicons dashicons-randomize" title="<?php echo esc_attr__( 'Branch', 'deployer-for-git' ); ?>"></i>:
						<code><?php echo esc_attr( $theme_info['branch'] ); ?></code>
					</li>
					<li>
						<pre style="white-space: normal;"><?php echo esc_attr( $theme_info['repo_url'] ); ?></pre>
					</li>
					<li>
						<a href="#" data-show-ptd-btn >
							<?php echo esc_attr__( '» Show Push-to-Deploy URL', 'deployer-for-git' ); ?>
						</a>
						<div class="dfg_package_box_action">
							<input type="url" disabled value="<?php echo esc_url( $endpoint_url ); ?>">
							<a data-copy-url-btn="<?php echo esc_url( $endpoint_url ); ?>" class="button" href="#" aria-label="<?php echo esc_attr__( 'Copy URL', 'deployer-for-git' ); ?>">
								<span class="dashicons dashicons-admin-page"></span>
								<span class="text" ><?php echo esc_attr__( 'Copy URL', 'deployer-for-git' ); ?></span>
							</a>
						</div>
					</li>
					<li class="dfg_package_box_buttons" >
						<button data-package-type="theme"
						data-trigger-ptd-btn="<?php echo esc_url( $endpoint_url ); ?>"
						title="<?php echo esc_attr__( 'Remove', 'deployer-for-git' ); ?>"
						class="button button-primary update-package-btn" >
							<span class="dashicons dashicons-update"></span>&nbsp;
							<span class="text" ><?php echo esc_attr__( 'Update Theme', 'deployer-for-git' ); ?></span>
						</button>

						<?php
						$themes_url = is_multisite() ? network_admin_url( 'themes.php' ) : admin_url( 'themes.php' );
						$theme_url  = add_query_arg( 'theme', $theme_info['slug'], $themes_url );
						?>
						<a href="<?php echo esc_url( $theme_url ); ?>"
							title="<?php echo esc_attr__( 'Remove', 'deployer-for-git' ); ?>"
							class="delete-theme button" ><span class="dashicons dashicons-trash"></span></a>
					</li>
				</ul>
			</div>
		</div>
	<?php endforeach; ?>
</div>
