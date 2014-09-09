<?php
/**
 * Feature Name: Action Forgot Password
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Checks if the forgot password call is valid
 *
 * @wp-hook	uf_forgot_password
 * @return	void
 */
function uf_perform_forgot_password() {

	$errors = uf_retrieve_password();
	if ( ! is_wp_error( $errors ) ) {
		wp_safe_redirect(  home_url( '/user-forgot-password/?message=confirmationsend' ) );
		exit();
	} else {
		wp_safe_redirect(  home_url( '/user-forgot-password/?message=' . $errors->get_error_code() ) );
		exit();
	}
}

/**
 * Retrives the password for a requested login
 *
 * @return WP_Error|mixed|boolean
 */
function uf_retrieve_password() {
	global $wpdb, $current_site;

	$errors = new WP_Error();

	if ( empty( $_POST[ 'user_login' ] ) ) {
		$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Enter a username or e-mail address.' ) );
	} else if ( strpos( $_POST[ 'user_login' ], '@' ) ) {
		$user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
		if ( empty( $user_data ) )
			$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: There is no user registered with that email address.' ) );
	} else {
		$login = trim( $_POST[ 'user_login'] );
		$user_data = get_user_by( 'login', $login );
	}

	do_action( 'lostpassword_post' );

	if ( $errors->get_error_code() )
		return $errors;

	if ( ! $user_data ) {
		$errors->add( 'invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.' ) );
		return $errors;
	}

	// redefining user_login ensures we return the right case in the email
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;

	do_action( 'retreive_password', $user_login );  // Misspelled and deprecated
	do_action( 'retrieve_password', $user_login );

	$allow = apply_filters( 'allow_password_reset', TRUE, $user_data->ID );

	if ( ! $allow )
		return new WP_Error( 'no_password_reset', __( 'Password reset is not allowed for this user' ) );
	else if ( is_wp_error( $allow ) )
		return $allow;

	$key = $wpdb->get_var( $wpdb->prepare( "SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login ) );
	if ( empty( $key ) ) {
		// Generate something random for a key...
		$key = wp_generate_password( 20, FALSE );
		do_action( 'retrieve_password_key', $user_login, $key );
		// Now insert the new md5 key into the db
		$wpdb->update( $wpdb->users, array( 'user_activation_key' => $key ), array( 'user_login' => $user_login ) );
	}
	$message = __( 'Someone requested that the password be reset for the following account:' ) . "\r\n\r\n";
	$message .= network_home_url( '/' ) . "\r\n\r\n";
	$message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
	$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
	$message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
	$message .= '<' . get_bloginfo( 'url' ) . '/user-reset-password/?key=' . $key . '&login=' . rawurlencode( $user_login ) . ">\r\n";

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	if ( is_multisite() )
		$blogname = $GLOBALS[ 'current_site' ]->site_name;
	else
		$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

	$title = sprintf( __( '[%s] Password Reset' ), $blogname );

	$title = apply_filters( 'retrieve_password_title', $title );
	$message = apply_filters( 'retrieve_password_message', $message, $key );

	if ( $message && ! wp_mail( $user_email, $title, $message ) )
		wp_die( __( 'The e-mail could not be sent.' ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function...' ) );

	return TRUE;
}

/**
 * Displays a message
 *
 * @wp-hook	uf_forgot_password_messages
 * @param	string $message the message id
 */
function uf_forgot_password_messages( $message ) {
	switch ( $message ) {
		case 'confirmationsend':
			?><div class="updated"><p><?php _e( 'Check your e-mail for the confirmation link.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'empty_username':
			?><div class="error"><p><?php _e( 'Please enter something.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'invalid_email':
			?><div class="error"><p><?php _e( 'The E-Mail address is not valid.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'invalidcombo':
			?><div class="error"><p><?php _e( 'Your input doesn\'t match anything.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'no_password_reset':
			?><div class="error"><p><?php _e( 'This action is not allowed.', 'user-frontend-td' ); ?></p></div><?php
			break;
		default:
			break;
	}
}
