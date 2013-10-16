<?php
/**
 * Feature Name:	Action Reset Password
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'uf_reset_password', 'uf_perform_reset_password' );
function uf_perform_reset_password() {

	// get user
	$user = uf_check_password_reset_key( $_POST[ 'user_key' ], $_POST[ 'user_login' ] );

	// check for key
	if ( is_wp_error( $user ) ) {
		wp_safe_redirect( home_url( '/user-reset-password/?message=invalid_key' ) );
		exit;
	}

	// check password
	$errors = new WP_Error();
	if ( isset( $_POST[ 'pass1' ] ) && $_POST[ 'pass1' ] != $_POST[ 'pass2' ] )
		$errors->add( 'password_reset_mismatch', __( 'The passwords do not match.' ) );

	// action for plugins
	do_action( 'validate_password_reset', $errors, $user );

	// set action
	if ( ( ! $errors->get_error_code() ) && isset( $_POST['pass1'] ) && !empty( $_POST['pass1'] ) ) {
		uf_reset_password( $user, $_POST[ 'pass1' ] );
		wp_safe_redirect( home_url( '/user-login/?message=password_resetted' ) );
		exit;
	} else {
		wp_safe_redirect( home_url( '/user-reset-password/?message=validate_password_reset' ) );
		exit;
	}
}

// reset password
function uf_reset_password( $user, $new_pass ) {
	do_action( 'password_reset', $user, $new_pass );
	wp_set_password( $new_pass, $user->ID );
	wp_password_change_notification( $user );
}

// check password key
function uf_check_password_reset_key( $key, $login ) {
	global $wpdb;

	$key = preg_replace( '/[^a-z0-9]/i', '', $key );

	if ( empty( $key ) || !is_string( $key ) )
		return new WP_Error( 'invalid_key', __( 'Invalid key' ) );

	if ( empty( $login ) || !is_string( $login ) )
		return new WP_Error( 'invalid_key', __( 'Invalid key' ) );

	$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $login ) );

	if ( empty( $user ) )
		return new WP_Error( 'invalid_key', __( 'Invalid key' ) );

	return $user;
}

add_action( 'uf_reset_password_messages', 'uf_reset_password_messages' );
function uf_reset_password_messages( $message ) {
	switch ( $message ) {
		case 'invalid_key':
			?><div class="error"><p><?php _e( 'Invalid Key.', UF_TEXTDOMAIN ); ?></p></div><?php
			break;
		case 'password_reset_mismatch':
			?><div class="error"><p><?php _e( 'The passwords do not match.', UF_TEXTDOMAIN ); ?></p></div><?php
			break;
		default:
			break;
	}
}
