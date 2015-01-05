<?php
/**
 * Feature Name: Action Handler
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * handles all the incoming actions
 *
 * wp-hook	init
 * @return	boolean
 */
function uf_action_handler() {

	// check if the rewrite rules have been flushed
	$plugin_data = get_plugin_data( UF_PLUGIN_BASEFILE );
	$plugin_version = $plugin_data[ 'Version' ];
	if ( get_option( 'user-frontend-rewrite-rules' . $plugin_version,  FALSE ) == FALSE ) {
		flush_rewrite_rules();
		update_option( 'user-frontend-rewrite-rules' . $plugin_version, 1 );
	}

	// checking the action
	if ( ! isset( $_REQUEST[ 'action' ] ) || ! has_action( 'uf_' .$_REQUEST[ 'action' ] ) ) {
		// check if we need to do something here
		if ( strstr( $_SERVER[ 'REQUEST_URI' ], '/user-action/' ) ) {
			wp_safe_redirect( home_url( '/user-error/?message=noaction' ) );
			exit;
		} else {
			return FALSE;
		}
	}

	// checking the nonce
	$nonce_request_key = 'wp_uf_nonce_' . $_REQUEST[ 'action' ];
	if ( ! isset( $_REQUEST[ $nonce_request_key ] ) || ! wp_verify_nonce( $_REQUEST[ $nonce_request_key ], $_REQUEST[ 'action' ] ) ) {
		wp_safe_redirect( home_url( '/user-error/?message=nononce' ) );
		exit;
	}

	do_action( 'uf_set_request_vars' );
	do_action( 'uf_' . $_REQUEST[ 'action' ] );
	return TRUE;
}
