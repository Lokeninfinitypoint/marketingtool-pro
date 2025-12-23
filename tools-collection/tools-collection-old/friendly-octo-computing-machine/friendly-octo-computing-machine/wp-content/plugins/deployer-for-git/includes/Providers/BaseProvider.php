<?php
namespace DeployerForGit\Providers;

/**
 * Base provider class
 */
class BaseProvider {

	/**
	 * Repository url
	 *
	 * @var string
	 */
	private $repo_url;

	/**
	 * Repository options
	 *
	 * @var array
	 */
	private $options;

	/**
	 * Constructor
	 *
	 * @param string $repo_url Repository url.
	 * @param array  $options  Repository options.
	 *
	 * @throws \InvalidArgumentException If repository url is invalid.
	 * @return void
	 */
	public function __construct( $repo_url = null, $options = array() ) {
		$this->repo_url = $repo_url;
		$this->options  = $options;
		if ( is_wp_error( $this->validate_repo_url() ) ) {
			throw new \InvalidArgumentException( esc_attr( $this->validate_repo_url()->get_error_message() ) );
		}
	}

	/**
	 * Get repository url
	 *
	 * @return string Repository url
	 */
	public function get_repo_url() {
		return $this->repo_url;
	}

	/**
	 * Validate repository url
	 *
	 * @return WP_Error|boolean
	 */
	protected function validate_repo_url() {
		// check if the repo url is empty.
		if ( empty( $this->repo_url ) ) {
			return new \WP_Error( 'empty', __( 'Repository url required', 'deployer-for-git' ) );
		}

		// check if the repo url is a valid url.
		if ( ! filter_var( $this->repo_url, FILTER_VALIDATE_URL ) ) {
			return new \WP_Error( 'invalid', __( 'Repository url must be a url', 'deployer-for-git' ) );
		}
	}

	/**
	 * Get provider repository zip URL
	 *
	 * @param  string $branch   Branch name, default is master.
	 * @return string           Repository URL to download zip.
	 */
	public function get_zip_repo_url( $branch = 'master' ) {
		return '';
	}

	/**
	 * Get provider repository handle
	 *
	 * @return string Repository handle
	 */
	protected function get_handle() {
		$parsed_url = wp_parse_url( $this->repo_url );
		$parts      = explode( '/', $parsed_url['path'] );
		$handle     = '';
		foreach ( $parts as $index => $part ) {
			if ( $index === 0 ) {
				continue;
			}
			$handle .= $part . '/';

			// if end of the path, remove the trailing slash.
			if ( $index === count( $parts ) - 1 ) {
				$handle = rtrim( $handle, '/' );
			}
		}

		return $handle;
	}

	/**
	 * Get domain name from repository url
	 */
	protected function get_domain() {
		$parsed_url = wp_parse_url( $this->repo_url );

		return $parsed_url['host'];
	}

	/**
	 * Get pretty provider name
	 *
	 * @return string Provider name
	 */
	public function get_pretty_name() {
		return '';
	}

	/**
	 * Get package slug from handle
	 *
	 * @return string Package slug
	 */
	public function get_package_slug() {
		$handle = $this->get_handle();

		// get the last part of the handle.
		$slug = basename( $handle );

		return $slug;
	}
}
