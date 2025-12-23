<?php

namespace Blocksy;

class Local_Gravatars_Init {
	public function __construct($url = '') {
		add_filter(
			'blocksy_performance_after_emojis_customizer_options',
			[$this, 'register_gravatars_options']
		);

		add_action('init', function() {
			add_filter(
				'get_avatar_url',
				function ($url) {
					global $pagenow;

					if (blc_theme_functions()->blocksy_get_theme_mod('store_gravatars_locally', 'no') === 'yes') {
						if (str_contains($url, 'gravatar.com')) {
							$local_gravatars = new Local_Gravatars($url);
							$avatar_url = $local_gravatars->get_gravatar();

							return $avatar_url;
						}
					}

					return $url;
				}
			);
		});
	}

	public function register_gravatars_options($opt) {
		$opts[] = [
			'store_gravatars_locally' => [
				'label' => __('Store Gravatars Locally', 'blocksy-companion'),
				'type' => 'ct-switch',
				'value' => 'no',
				'divider' => 'bottom:full',
				'setting' => ['transport' => 'postMessage'],
				'desc' => __('Store and load Gravatars locally for increased privacy and performance.', 'blocksy-companion')
			]
		];

		return $opts;
	}
}

class Local_Gravatars {
	private static $instance = null;
	protected $remote_url;
	protected $base_path;
	protected $subfolder_name;
	protected $base_url;
	protected $gravatars_folder;

	const CLEANUP_FREQUENCY = 'weekly';
	const MAX_PROCESS_TIME = 5;

	private static $start_time;
	private static $has_stopped = false;

	public function __construct($url = '') {
		$this->remote_url = $url;

		$this->schedule_cleanup();

		add_action('delete_gravatars_folder', array(
			$this,
			'delete_gravatars_folder'
		));
	}

	public function get_gravatar() {
		if (! $this->should_process()) {
			return $this->remote_url;
		}

		if (! file_exists($this->get_base_path())) {
			$this->get_filesystem()->mkdir(
				$this->get_base_path(),
				FS_CHMOD_DIR
			);
		}

		$parsed_url = wp_parse_url($this->remote_url);

		$parsed_query_string = [];

		if (isset($parsed_url['query'])) {
			parse_str($parsed_url['query'], $parsed_query_string);
		}

		$filename = basename($parsed_url['path']);

		// Include default in the filename
		// https://github.com/WordPress/wordpress-develop/blob/d2a7bee9a6201f0276492ff29afd8c509480d266/src/wp-includes/link-template.php#L4533
		if (isset($parsed_query_string['d'])) {
			$filename = md5(
				$filename . '-' . $parsed_query_string['d']
			);
		}

		$path = $this->get_base_path() . '/' . $filename;

		if (! file_exists($path)) {
			if (! function_exists('download_url')) {
				require_once wp_normalize_path(
					ABSPATH . '/wp-admin/includes/file.php'
				);
			}

			$tmp_path = download_url($this->remote_url);

			if (! is_wp_error($tmp_path)) {
				$success = $this->get_filesystem()->move($tmp_path, $path, true);

				if (! $success) {
					return $this->remote_url;
				}
			}
		}

		return $this->get_base_url() . '/' . $filename;
	}

	public function get_base_path() {
		if (! $this->base_path) {
			$wp_uploads = wp_upload_dir();
			$this->base_path = $wp_uploads['basedir'] . '/gravatars';
		}

		return $this->base_path;
	}

	public function get_base_url() {
		if (! $this->base_url) {
			$wp_uploads = wp_upload_dir();
			$this->base_url = $wp_uploads['baseurl'] . '/gravatars';
		}

		return $this->base_url;
	}

	public function schedule_cleanup() {
		if (! is_multisite() || (is_multisite() && is_main_site())) {
			if (
				! wp_next_scheduled('delete_gravatars_folder')
				&&
				! wp_installing()
			) {
				wp_schedule_event(
					time(),
					self::CLEANUP_FREQUENCY,
					'delete_gravatars_folder'
				);
			}
		}
	}

	public function delete_gravatars_folder() {
		return $this->get_filesystem()
			->delete($this->get_base_path(), true);
	}

	protected function get_filesystem() {
		global $wp_filesystem;

		if (! $wp_filesystem) {
			if (! function_exists('WP_Filesystem')) {
				require_once wp_normalize_path(ABSPATH . '/wp-admin/includes/file.php');
			}

			\WP_Filesystem();
		}

		return $wp_filesystem;
	}

	public function should_process() {
		if (self::$has_stopped) {
			return false;
		}

		if (! self::$start_time) {
			self::$start_time = time();
		}

		if (time() > self::$start_time + $this->get_max_process_time()) {
			self::$has_stopped = true;
			return false;
		}

		return true;
	}

	public function get_max_process_time() {
		return self::MAX_PROCESS_TIME;
	}
}
