<?php
/**
 * Feature Name:	Action Login
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'uf_login', 'uf_perform_login' );
function uf_perform_login() {
	
	// check username
	if ( ! isset( $_POST[ 'user_login' ] ) || trim( $_POST[ 'user_login' ] ) == '' ) {
		wp_safe_redirect( home_url( '/user-login/?message=nodata' ) );
		exit;
	}

	// check password
	if ( ! isset( $_POST[ 'user_pass' ] ) || trim( $_POST[ 'user_pass' ] ) == '' ) {
		wp_safe_redirect( home_url( '/user-login/?message=nodata' ) );
		exit;
	}

	// set credentials
	$credentials = array(
		'user_login'	=> $_POST[ 'user_login' ],
		'user_password'	=> $_POST[ 'user_pass' ],
	);

	// remember me
	if ( isset( $_POST[ 'rememberme' ] ) && $_POST[ 'rememberme' ] == 'on' )
		$credentials[ 'remember' ] = 'on';

	// signon user
	$user = wp_signon( $credentials );
	
	// check login
	if ( ! is_wp_error( $user ) ) {
		if ( isset( $_POST[ 'redirect_to' ] ) && trim( $_POST[ 'redirect_to' ] ) != '' )
			wp_redirect( $_POST[ 'redirect_to' ] );
		else
			wp_safe_redirect( home_url( '/user-profile/' ) );
		exit;
	} else {
		wp_safe_redirect( home_url( '/user-login/?message=nologin' ) );
		exit;
	}
}

add_action( 'uf_login_messages', 'uf_login_messages' );
function uf_login_messages( $message ) {
	
	switch ( $message ) {
		case 'nodata':
			?><div class="error"><p><?php _e( 'We need some input values to handle the login.', UF_TEXTDOMAIN ); ?></p></div><?php
			break;
		case 'nologin':
			?><div class="error"><p><?php _e( 'Username or password is incorrect.', UF_TEXTDOMAIN ); ?></p></div><?php
			break;
		case 'password_resetted':
			?><div class="updated"><p><?php _e( 'Password has been resetted.', UF_TEXTDOMAIN ); ?></p></div><?php
			break;
		case 'registered':
			?><div class="updated"><p><?php _e( 'You have been successfully registered. Please check your mail for your credentials and more informations.', UF_TEXTDOMAIN ); ?></p></div><?php
			break;
		case 'regdisabled':
			?><div class="error"><p><?php _e( 'Registration is currently disabled.', UF_TEXTDOMAIN ); ?></p></div><?php
			break;
		case 'loggedout':
			?><div class="updated"><p><?php _e( 'You have been successfully logged out.', UF_TEXTDOMAIN ); ?></p></div><?php
			break;
		default:
			break;
	}
}
