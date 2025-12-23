<?php

namespace DeployerForGit\Providers;

use DeployerForGit\Helper;
/**
 * Class GithubProvider
 *
 * @package Wp_Git_Deployer
 */
class GithubProvider extends BaseProvider {
    /**
     * Get provider repository handle
     *
     * @return string Repository handle
     */
    public function get_pretty_name() {
        return 'GitHub';
    }

    /**
     * Get provider repository zip URL
     *
     * @param  string $branch   Branch name, default is master.
     * @param  string $token    Access token.
     * @return string           Repository URL to download zip
     */
    public function get_zip_repo_url( $branch = 'master', $token = '' ) {
        $handle = $this->get_handle();
        // If token is not empty and contains 'github_pat_' then it's a personal access token,
        // and we need to use API to get the zip URL from redirect "Location" header.
        if ( !empty( $token ) && Helper::is_github_pat_token( $token ) ) {
        }
        return "https://github.com/{$handle}/archive/refs/heads/{$branch}.zip";
    }

    /**
     * Validate repository url
     *
     * @return WP_Error|boolean
     */
    protected function validate_repo_url() {
        parent::validate_repo_url();
        // check if string has exact format in a URL like this: https://github.com/company/wordpress-theme .
        if ( !preg_match( '/^https:\\/\\/github.com\\/[a-zA-Z0-9-_]+\\/[a-zA-Z0-9-_]+$/', $this->get_repo_url() ) ) {
            return new \WP_Error('invalid', __( 'Repository url must be a valid GitHub repository url', 'deployer-for-git' ));
        }
    }

}
