<?php
/**
 * Feature Name: Restrict Backend Access
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Determinates if a user has the
 * capabilities to see the admin bar.
 *
 * @wp-hook	show_admin_bar
 * @return	bool
 */
function uf_maybe_remove_admin_bar() {
	return current_user_can( 'edit_posts' );
}

/**
 * Checks if the current request should be
 * redirected to the frontend.
 *
 * @wp-hook	admin_init
 * @return	void
 */
function uf_maybe_block_backend() {
	// If user can edit posts do nothing
	if ( current_user_can( 'edit_posts' ) )
		return;

	// AJAX requests via wp-admin/admin-ajax.php needs to be passed
	if ( defined( 'DOING_AJAX') && DOING_AJAX )
		return;

	// Don't redirect when WP CLI is active. This prevents some notices
	// by WP CLI.
	if ( defined( 'WP_CLI' ) && WP_CLI )
		return;

	wp_safe_redirect( home_url() );
	exit;
}
