<?php
/**
 * Feature Name:	Action Activation
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'uf_activation', 'uf_perform_activation' );
function uf_perform_activation() {

	if ( ! isset( $_POST[ 'user_key' ] ) ) {
		wp_safe_redirect( home_url( '/user-activation/?message=keynotvalid' ) );
		exit;
	} else {
		$key = $_POST[ 'user_key' ];
		$result = wpmu_activate_signup( $key );
		if ( is_wp_error( $result ) ) {
			wp_safe_redirect(  home_url( '/user-activation/?message=keynotvalid' ) );
			exit;
		} else {

			$user = get_userdata( $result[ 'user_id' ] );
			$username = $user->user_login;
			$password = $result[ 'password' ];

			// set credentials
			$credentials = array(
				'user_login'	=> $username,
				'user_password'	=> $password,
			);

			// signon user
			$user = wp_signon( $credentials );

			wp_safe_redirect( home_url( '/user-profile/?message=activated' ) );
			exit;
		}
	}
}

add_action( 'uf_activation_messages', 'uf_activation_messages' );
function uf_activation_messages( $message ) {
	switch ( $message ) {
		case 'keynotvalid':
			?><div class="error"><p><?php _e( 'The key is not valid.', UF_TEXTDOMAIN ); ?></p></div><?php
			break;
		default:
			break;
	}
}
