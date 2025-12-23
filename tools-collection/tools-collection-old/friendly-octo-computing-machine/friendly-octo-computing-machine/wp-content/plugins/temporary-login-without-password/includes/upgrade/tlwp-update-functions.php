<?php
/**
 * Functions for updating data, used by the background updater.
 */
// phpcs:disable
defined( 'ABSPATH' ) || exit;


/* --------------------- TLWP 1.9.2 (Start)--------------------------- */
/**
 * To add activity log in TLWP PRO
 * TLWP PRO version 1.9.2 onwards
 */
function tlwp_update_192_add_activity_log() {
    TLWP_Install::create_tables('1.9.2');
}

function tlwp_update_192_db_version() {
	TLWP_Install::update_db_version( '1.9.2' );
} 

function tlwp_update_193_add_bulk_user_import() {
    TLWP_Install::create_tables('1.9.3');
}

function tlwp_update_193_db_version() {
	TLWP_Install::update_db_version( '1.9.3' );
} 