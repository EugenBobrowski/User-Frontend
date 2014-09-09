<?php
/**
 * Feature Name: Action Profile Edit
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Edits the user
 *
 * @wp-hook	uf_profile
 * @return	void
 */
function uf_perform_profile_edit() {

	// get user id
	$user_id = get_current_user_id();

	// perform profile actions for plugins
	do_action( 'personal_options_update', $user_id );

	// edit user
	if ( ! function_exists( 'edit_user' ) )
		require_once ABSPATH . '/wp-admin/includes/user.php';
	$errors = edit_user( $user_id );

	// check for errors (mainly password)
	if ( ! is_wp_error( $errors ) ) {
		wp_safe_redirect( home_url( '/user-profile/?message=updated' ) );
		exit;
	} else {
		$error_code = $errors->get_error_code();
		wp_safe_redirect( home_url( '/user-profile/?message=' . $error_code ) );
		exit;
	}
}

/**
 * Displays a message
 *
 * @wp-hook	uf_profile_messages
 * @param	string $message
 * @return	void
 */
function uf_profile_messages( $message ) {
	switch ( $message ) {
		case 'updated':
			?><div class="updated"><p><?php _e( 'Profile has been updated.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'pass':
			?><div class="error"><p><?php _e( 'The passwords mismatch.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'invalid_email':
			?><div class="error"><p><?php _e( 'E-Mail address is not valid.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'empty_email':
			?><div class="error"><p><?php _e( 'Please enter an E-Mail address.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'email_exists':
			?><div class="error"><p><?php _e( 'This email is already registered, please choose another one.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'activated':
			?><div class="updated"><p><?php _e( 'Your account has been activated. Now you can edit your profile.', 'user-frontend-td' ); ?></p></div><?php
			break;
		default:
			break;
	}
}
