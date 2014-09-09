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
	if ( get_option( 'user-frontend-rewrite-rules',  FALSE ) == FALSE ) {
		flush_rewrite_rules();
		update_option( 'user-frontend-rewrite-rules', 1 );
	}

	// checking the action
	if ( ! isset( $_REQUEST[ 'action' ] ) || ! has_action( 'uf_' .$_REQUEST[ 'action' ] ) )
		return FALSE;

	//checking the nonce
	$nonce_request_key = 'wp_uf_nonce_' . $_REQUEST[ 'action' ];
	if ( ! isset( $_REQUEST[ $nonce_request_key ] ) || ! wp_verify_nonce( $_REQUEST[ $nonce_request_key ], $_REQUEST[ 'action' ] ) )
		return FALSE;

	do_action( 'uf_set_request_vars' );
	do_action( 'uf_' . $_REQUEST[ 'action' ] );
	return TRUE;
}