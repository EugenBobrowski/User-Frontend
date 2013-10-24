<?php
/**
 * Feature Name:	Helpers
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

// function to load the action url for the forms
function uf_get_action_url( $action ) {
	
	return home_url( '/user-action/?action=' . $action );
}

// standard login form arguments
function uf_login_form_args() {
	$args = array(
		'echo'				=> TRUE,
		'redirect'			=> ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // Default redirect is back to the current page
		'form_id'			=> 'loginform',
		'label_username'	=> __( 'Username' ),
		'label_password'	=> __( 'Password' ),
		'label_remember'	=> __( 'Remember Me' ),
		'label_log_in'		=> __( 'Log In' ),
		'id_username'		=> 'user_login',
		'id_password'		=> 'user_pass',
		'id_remember'		=> 'rememberme',
		'id_submit'			=> 'wp-submit',
		'remember'			=> TRUE,
		'value_username'	=> '',
		'value_remember'	=> FALSE, // Set this to true to default the "Remember me" checkbox to checked
	);
	
	return apply_filters( 'uf_login_form_args', $args );
}

// on submit, we have to store the post and get
// vars in session to get them back after redirect..
add_action( 'uf_set_request_vars', 'uf_set_request_vars' );
function uf_set_request_vars(){

	maybe_start_session();

	if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' )
		$_SESSION[ 'uf_post_vars' ]    = $_POST;

	// on a get- and/or post-request, you can send GET-Params
	$_SESSION[ 'uf_get_vars' ]     = $_GET;

}

// when loading the template, we have to refetch
// after Redirect the POST and GET-vars from Session..
add_action( 'uf_get_request_vars', 'uf_get_request_vars' );
function uf_get_request_vars(){

	maybe_start_session();

	// trying to get the POST-vars session
	if ( array_key_exists( 'uf_post_vars', $_SESSION ) )
		$_POST = array_merge( $_SESSION[ 'uf_post_vars' ], $_POST );

	// trying to get the GET-Vars from session
	if ( array_key_exists( 'uf_get_vars', $_SESSION ) )
		$_GET = array_merge( $_SESSION[ 'uf_get_vars' ], $_GET );

	// now we have to combine the GET, POST and REQUEST to rebuild the old $_REQUEST
	$_REQUEST =  array_merge( $_GET, $_POST, $_REQUEST );
}

// wordpress-style function to start the session when net started.
function maybe_start_session(){
	if ( ! session_id() )
		session_start();
}

// Some URL Stuff
add_action( 'lostpassword_url', 'uf_lostpassword_url' );
function uf_lostpassword_url( $url ) {
	return home_url( '/user-forgot-password/' );
}

add_action( 'register_url', 'uf_register_url' );
function uf_register_url( $url ) {
	return home_url( '/user-register/' );
}

add_action( 'wp_signup_location', 'uf_signup_location' );
function uf_signup_location( $url ) {
	return home_url( '/user-register/' );
}

add_action( 'login_url', 'uf_login_url', 10, 2 );
function uf_login_url( $url, $redirect = '' ) {
	$login_url = home_url( '/user-login/' );

	if ( ! empty( $redirect ) )
		$login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );

	return $login_url;
}

add_action( 'logout_url', 'uf_logout_url', 10, 2 );
function uf_logout_url( $url, $redirect = '' ) {
	
	$args = array( 'action' => 'logout' );
	if ( ! empty( $redirect ) )
		$args[ 'redirect_to' ] = urlencode( $redirect );

	$logout_url = add_query_arg( $args, home_url( '/' ) );
	$logout_url = str_replace( '&amp;', '&', $logout_url );
	$logout_url = add_query_arg( 'wp_uf_nonce_logout', wp_create_nonce( 'logout' ), $logout_url );
	$logout_url = esc_html( $logout_url );
	
	return $logout_url;
}

add_action( 'edit_profile_url', 'uf_edit_profile_url' );
function uf_edit_profile_url( $url ) {
	if ( is_admin() )
		return $url;

	return home_url( '/user-profile/' );
}