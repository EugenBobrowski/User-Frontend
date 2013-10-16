<?php
/**
 * Feature Name:	Action Logout
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'uf_logout', 'uf_perform_logout' );
function uf_perform_logout() {
	
	wp_logout();

	wp_safe_redirect( home_url( '/user-login/?message=loggedout' ) );
}

add_action( 'uf_login_messages', 'uf_logout_messages' );
function uf_logout_messages( $message ) {
	switch ( $message ) {
		case 'loggedout':
			?><div class="updated"><p><?php _e( 'You have been successfully logged out.', UF_TEXTDOMAIN ); ?></p></div><?php
			break;
		default:
			break;
	}
}
