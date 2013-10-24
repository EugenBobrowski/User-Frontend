<?php
/**
 * Feature Name:	Action Handler
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

// handles all the incoming actions
add_action( 'init', 'uf_action_handler' );
function uf_action_handler() {
	
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