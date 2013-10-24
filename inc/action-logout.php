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
	exit;
}