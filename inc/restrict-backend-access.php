<?php
/**
 * Feature Name:	Restrict Backend Access
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'show_admin_bar', 'uf_maybe_remove_admin_bar' );
function uf_maybe_remove_admin_bar() {
	return current_user_can( 'edit_posts' );
}

add_action( 'admin_init', 'uf_maybe_block_backend' );
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
}
