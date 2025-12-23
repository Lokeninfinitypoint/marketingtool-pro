<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TLWP_Install' ) ) {

    class TLWP_Install { 

        /**
		 * DB updates and callbacks that need to be run per version.
		 *
		 * @since 4.0.0
		 * @var array
		 */
		private static $db_updates = array( 
			'1.9.2' => array(
				'tlwp_update_192_add_activity_log',
				'tlwp_update_192_db_version',
			),

			'1.9.3' => array(
				'tlwp_update_193_add_bulk_user_import',
				'tlwp_update_193_db_version',
			),
        );

        /**
		 * Init Install/ Update Process
		 *
		 * @since 4.0.0
		 */
		public static function init() {
			
			if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

				if ( Wp_Temporary_Login_Without_Password::is_pro() ) {

					add_action( 'admin_init', array( __CLASS__, 'check_version' ), 5 ); 

				}
			}
		}

        /**
		 * Install if required
		 *
		 * @since 4.0.0
		 */
		public static function check_version() {

			$current_db_version = get_option( 'tlwp_db_version', '1.0.0' );

			// Get latest available DB update version
			$latest_db_version_to_update = self::get_latest_db_version_to_update();
			 
			if ( version_compare( $current_db_version, $latest_db_version_to_update, '<' ) ) {
 
				self::install();
			}
			
		}
		

		/**
		 * Begin Installation
		 *
		 * @since 4.0.0tlwp
		 */
		public static function install() {

			// Create Files
			self::create_files();
 
			if ( ! is_blog_installed() ) { 

				return;
			}

			// Check if we are not already running this routine.
            if ( 'yes' === get_transient( 'tlwp_installing' ) ) { 

				return;
			} 
 
			if ( self::is_new_install() ) {  
 
				// If we made it till here nothing is running yet, lets set the transient now.
				set_transient( 'tlwp_installing', 'yes', MINUTE_IN_SECONDS * 10 );

				tlwp_maybe_define_constant( 'TLWP_INSTALLING', true );

				// Create Tables
				self::create_tables(); 
			}

			self::maybe_update_db_version(); 
			delete_transient( 'tlwp_installing' );

		} 

        /**
		 * Is this new Installation?
		 *
		 * @return bool
		 *
		 * @since 4.0.0
		 */
		public static function is_new_install() {
			/**
			 * We are storing tlwp_db_version if it's new installation.
			 *  
			 */
			return is_null( get_option( 'tlwp_db_version', null ) );
		}

        /**
		 * Get latest db version based on available updates.
		 *
		 * @return mixed
		 *
		 * @since 4.0.0
		 */
		public static function get_latest_db_version_to_update() {
			$updates         = self::get_db_update_callbacks();
			$update_versions = array_keys( $updates );

			usort( $update_versions, 'version_compare' );

			return end( $update_versions );
		}

        /**
		 * Require DB updates?
		 *
		 * @return bool
		 *
		 * @since 4.0.0
		 */
		private static function needs_db_update() { 

			$current_db_version = get_tlwp_db_version();
			
		    $latest_db_version_to_update = self::get_latest_db_version_to_update(); 
			  
			return ! is_null( $current_db_version ) && version_compare( $current_db_version, $latest_db_version_to_update, '<' );
		}

        /**
		 * Check whether database update require? If require do update.
		 *
		 * @since 4.0.0
		 */
		private static function maybe_update_db_version() {
            
			if ( self::needs_db_update() ) {
				
				if ( apply_filters( 'tlwp_enable_auto_update_db', true ) ) { 
					self::update();
				}  
			} 
		}

        /**
		 * Get all database updates
		 *
		 * @return array
		 *
		 * @since 4.0.0
		 */
		public static function get_db_update_callbacks() {
			return self::$db_updates;
		}

        /**
		 * Do database update.
		 *
		 * @param bool $force
		 *
		 * @since 4.0.0
		 */
		private static function update( $force = false ) {
            
			// Check if we are not already running this routine.
			if ( ! $force && 'yes' === get_transient( 'tlwp_updating' ) ) { 
				return;
			}

			set_transient( 'tlwp_updating', 'yes', MINUTE_IN_SECONDS * 5 );

			$current_db_version = get_tlwp_db_version();

			$tasks_to_process = get_option( 'tlwp_update_tasks_to_process', array() );

			// Get all tasks processed
			$processed_tasks = get_option( 'tlwp_update_processed_tasks', array() );
 
			// Get al tasks to process
			$tasks = self::get_db_update_callbacks();
 
			if ( count( $tasks ) > 0 ) {

				
				foreach ( $tasks as $version => $update_callbacks ) {

					if ( version_compare( $current_db_version, $version, '<' ) ) {
						foreach ( $update_callbacks as $update_callback ) {
							if ( ! in_array( $update_callback, $tasks_to_process ) && ! in_array( $update_callback, $processed_tasks ) ) { 
								$tasks_to_process[] = $update_callback;
							}  
						}
					}
				}
			}

			if ( count( $tasks_to_process ) > 0 ) {
 
				update_option( 'tlwp_update_tasks_to_process', $tasks_to_process );

				self::dispatch();

			} else {
				
				delete_transient( 'tlwp_updating' );
			}

		}

        /**
		 * Dispatch database updates.
		 *
		 * @since 4.0.0
		 */
		public static function dispatch() { 

			$batch = get_option( 'tlwp_update_tasks_to_process', array() );
 
			if ( count( $batch ) > 0 ) {

				// We may require lots of memory
				// Add filter to increase memory limit
				add_filter( 'tlwp_memory_limit', 'tlwp_increase_memory_limit' );

				wp_raise_memory_limit( 'tlwp' );

				// Remove the added filter function so that it won't be called again if wp_raise_memory_limit called later on.
				remove_filter( 'tlwp_memory_limit', 'tlwp_increase_memory_limit' );

				// It may take long time to process database update.
				// So, increase execution time
				@set_time_limit( 360 );

				foreach ( $batch as $key => $value ) {

					$is_value_exists = true;
					// $task_transient = $value . '_processed';
					$tlwp_update_processed_tasks = get_option( 'tlwp_update_processed_tasks', array() );
					$task                         = false; // By default it's set to false

					// Check whether the tasks is already processed? If not, process it.
					if ( ! in_array( $value, $tlwp_update_processed_tasks ) ) {
						$is_value_exists = false; 
						$task = (bool) self::task( $value ); 
					} else { 
						unset( $batch[ $key ] );
					}

					if ( false === $task ) {

						if ( ! $is_value_exists ) {
							$tlwp_update_processed_tasks[] = $value;
							update_option( 'tlwp_update_processed_tasks', $tlwp_update_processed_tasks );
						}

						unset( $batch[ $key ] );
					}
				}

				update_option( 'tlwp_update_tasks_to_process', $batch );
			}

			// Delete update transient
			delete_transient( 'tlwp_updating' );
		}

        /**
		 * Run individual database update.
		 *
		 * @param $callback
		 *
		 * @return bool|callable
		 *
		 * @since 4.0.0
		 */
		public static function task( $callback ) {
 
			include_once dirname( __FILE__ ) . '/upgrade/tlwp-update-functions.php';

			$result = false;

			if ( is_callable( $callback ) ) { 
				$result = (bool) call_user_func( $callback );
			}  

			return $result ? $callback : false;
		}

		/**
		 * Update DB Version & DB Update history
		 *
		 * @param null $version
		 *
		 * @since 4.0.0
		 */
		public static function update_db_version( $version = null ) {

			$latest_db_version_to_update = self::get_latest_db_version_to_update();

			update_option( 'tlwp_db_version', is_null( $version ) ? $latest_db_version_to_update : $version );

			if ( ! is_null( $version ) ) {
				$db_update_history_option = 'db_update_history';

				$tlwp_db_update_history_data = TLWP_Common::get_tlwp_option( $db_update_history_option, array() );

				$tlwp_db_update_history_data[ $version ] = tlwp_get_current_date_time();

				TLWP_Common::set_tlwp_option( $db_update_history_option, $tlwp_db_update_history_data );
			}
		}

        /**
		 * Create tables
		 *
		 * @param null $version
		 *
		 * @since 4.0.0
		 *
		 * @modify 4.4.9
		 */
		public static function create_tables( $version = null ) {

			global $wpdb;

			$collate = '';

			if ( $wpdb->has_cap( 'collation' ) ) {
				$collate = $wpdb->get_charset_collate();
			}

			if ( is_null( $version ) ) {
				$schema_fn = 'get_schema';
			} else {
				$v         = str_replace( '.', '', $version );
				$schema_fn = 'get_tlwp_' . $v . '_schema';
			} 
			
			$wpdb->hide_errors();
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( self::$schema_fn( $collate ) );
		}

        public static function get_tlwp_192_schema( $collate = '' ) {

            global $wpdb;

            $tables = "
            CREATE TABLE `{$wpdb->prefix}tlwp_activity_logs` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, 
                `alert_id` BIGINT(20) DEFAULT NULL,
                `object` VARCHAR(255) NOT NULL,
                `action` VARCHAR(255) NOT NULL,                
                `user_roles` VARCHAR(255) NOT NULL,
                `username` VARCHAR(255) NULL,
                `user_id` BIGINT(20) NULL,                
                `post_status` VARCHAR(255) NOT NULL,
                `post_type` VARCHAR(255) NOT NULL,
                `post_id` BIGINT(20) NOT NULL,
                `created_on` DOUBLE NOT NULL,
                `client_ip` VARCHAR(255) NOT NULL,
                `user_agent` VARCHAR(255) NOT NULL,
                PRIMARY KEY (id)
            ) $collate;

            CREATE TABLE `{$wpdb->prefix}tlwp_activity_logs_meta` (
                `meta_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `activity_log_id` BIGINT(20) UNSIGNED NOT NULL,
                `meta_key` VARCHAR(255) DEFAULT NULL,
                `meta_value` LONGTEXT DEFAULT NULL,
                PRIMARY KEY (meta_id),
                KEY activity_log_id (activity_log_id),
                KEY meta_key (meta_key)
            ) $collate;

            ";

            return $tables;

        }

		public static function get_tlwp_193_schema( $collate = '' ) {

            global $wpdb;

            $tables = "
            CREATE TABLE {$wpdb->prefix}tlwp_user_temp_import (
				`ID` bigint(20) NOT NULL AUTO_INCREMENT,
				`data` longtext NOT NULL,
				`identifier` char(13) NOT NULL,
				PRIMARY KEY (ID)
			) $collate; 

            ";

            return $tables;

        }

        /**
		 * Collect multiple version schema
		 *
		 * @param string $collate
		 *
		 * @return string
		 *
		 * @since 4.2.0
		 */
		private static function get_schema( $collate = '' ) {

			$tables = self::get_tlwp_192_schema( $collate );
			$tables .= self::get_tlwp_193_schema( $collate );

			return $tables;
		}

        /**
		 * Create files/ directory
		 *
		 * @since 4.1.13
		 */
		public static function create_files() {

			// Want to bypass creation of files?
			if ( apply_filters( 'tlwp_install_skip_create_files', false ) ) {
				return;
			}

			$files = array(
				array(
					'base'    => TLWP_LOG_DIR,
					'file'    => '.htaccess',
					'content' => 'deny from all',
				),
				array(
					'base'    => TLWP_LOG_DIR,
					'file'    => 'index.html',
					'content' => '',
				),
			); 

			foreach ( $files as $file ) {
				if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
					$file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' );
					if ( $file_handle ) {
						fwrite( $file_handle, $file['content'] );
						fclose( $file_handle ); 
					}
				}
			}
		}

	}

	TLWP_Install::init(); 

}