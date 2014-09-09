<?php
/**
 * Feature Name: Action Logout
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Loads the current user out
 *
 * @wp-hook	uf_logout
 * @return	void
 */
function uf_perform_logout() {
	wp_logout();
	$url_after_logout = apply_filters( 'uf_perform_logout_url', '/user-login/?message=loggedout' );
	wp_safe_redirect( home_url( $url_after_logout ) );
	exit;
}