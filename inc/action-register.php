<?php
/**
 * Feature Name: Action Register
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Performs the register of a user
 *
 * @wp-hook	uf_register
 * @return	void
 */
function uf_perform_register_edit() {

	if ( is_multisite() )
		uf_register_multisite_user();
	else
		uf_register_standard_user();
}

/**
 * Registers the user in a multisite setup
 * called at uf_perform_register_edit()
 *
 * @return	void
 */
function uf_register_multisite_user() {
	uf_validate_user_signup();
}

/**
 * Validates the user input and registers the
 * user, called at uf_perform_register_edit()
 *
 * @return	void
 */
function uf_validate_user_signup() {

	// set user mail
	$_POST[ 'user_name' ] = $_POST[ 'user_login' ];
	$_POST[ 'user_email' ] = $_POST[ 'email' ];

	// validate
	$result = uf_validate_user_form();
	extract( $result );

	if ( $errors->get_error_code() ) {
		wp_safe_redirect( home_url( '/user-register/?message=' . $errors->get_error_code() ) );
		exit;
	} else {
		$_POST[ 'add_to_blog' ] = get_current_blog_id();
		$_POST[ 'new_role' ] = 'subscriber';
		wpmu_signup_user( $user_name, $user_email, apply_filters( 'add_signup_meta', $_POST ) );
		wp_safe_redirect( home_url( '/user-login/?message=registered' ) );
		exit;
	}
}

/**
 * Validates the user input
 *
 * @return Ambigous <multitype:, mixed>
 */
function uf_validate_user_form() {
	return wpmu_validate_user_signup( $_POST[ 'user_name' ], $_POST[ 'user_email' ] );
}

/**
 * Validates the user input and registers the
 * user, called at uf_perform_register_edit()
 *
 * @return	void
 */
function uf_register_standard_user() {

	// set user mail
	$_POST[ 'user_email' ] = $_POST[ 'email' ];

	// set data
	$user_login = $_POST[ 'user_login' ];
	$user_email = $_POST[ 'user_email' ];

	// register the user
	$errors = uf_register_new_user( $user_login, $user_email );
	if ( ! is_wp_error( $errors ) ) {
		wp_safe_redirect( home_url( '/user-login/?message=registered' ) );
		exit;
	} else {
		wp_safe_redirect( home_url( '/user-register/?message=' . $errors->get_error_code() ) );
		exit;
	}
}

/**
 * Registers the new user
 *
 * @param	string $user_login the user name
 * @param	string $user_email the users email address
 * @return	mixed|Ambigous <number, WP_Error>
 */
function uf_register_new_user( $user_login, $user_email ) {
	$errors = new WP_Error();

	$sanitized_user_login = sanitize_user( $user_login );
	$user_email = apply_filters( 'user_registration_email', $user_email );

	// Check the username
	if ( $sanitized_user_login == '' ) {
		$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Please enter a username.' ) );
	} elseif ( ! validate_username( $user_login ) ) {
		$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.' ) );
		$sanitized_user_login = '';
	} elseif ( username_exists( $sanitized_user_login ) ) {
		$errors->add( 'username_exists', __( '<strong>ERROR</strong>: This username is already registered. Please choose another one.' ) );
	}

	// Check the e-mail address
	if ( $user_email == '' ) {
		$errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please type your e-mail address.' ) );
	} elseif ( ! is_email( $user_email ) ) {
		$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.' ) );
		$user_email = '';
	} elseif ( email_exists( $user_email ) ) {
		$errors->add( 'email_exists', __( '<strong>ERROR</strong>: This email is already registered, please choose another one.' ) );
	}

	do_action( 'register_post', $sanitized_user_login, $user_email, $errors );

	$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

	if ( $errors->get_error_code() )
		return $errors;

	$user_pass = wp_generate_password( 12, false);
	$user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );
	if ( ! $user_id ) {
		$errors->add( 'registerfail', sprintf( __( '<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !' ), get_option( 'admin_email' ) ) );
		return $errors;
	}

	update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.

	wp_new_user_notification( $user_id, $user_pass );
	
	apply_filters('uf_after_register', $user_id);

	return $user_id;
}

/**
 * Sends the notification
 *
 * @wp-hook	wpmu_signup_user_notification
 * @param	object $user
 * @param	string $user_email
 * @param	string $key
 * @param	string $meta
 * @return	void
 */
function uf_wpmu_signup_user_notification( $user, $user_email, $key, $meta ) {

	// Send email with activation link.
	$admin_email = get_site_option( 'admin_email' );
	if ( $admin_email == '' )
		$admin_email = 'support@' . $_SERVER['SERVER_NAME'];

	$from_name = get_site_option( 'site_name' ) == '' ? 'WordPress' : esc_html( get_site_option( 'site_name' ) );
	$message_headers = "From: \"{$from_name}\" <{$admin_email}>\n" . "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";
	$message = sprintf(
		apply_filters( 'wpmu_signup_user_notification_email',
			__( "To activate your user, please click the following link:\n\n%s\n\nAfter you activate, you will receive *another email* with your login." ),
			$user, $user_email, $key, $meta
		),
		home_url( '/user-activation/?key=' . $key )
	);

	$subject = sprintf(
		apply_filters( 'wpmu_signup_user_notification_subject',
			__( '[%1$s] Activate %2$s' ),
			$user, $user_email, $key, $meta
		),
		$from_name,
		$user
	);

	wp_mail( $user_email, $subject, $message, $message_headers );
}

/**
 * Displays a message
 *
 * @wp-hook	uf_register_messages
 * @param	string $message
 * @return	void
 */
function uf_register_messages( $message ) {

	switch ( $message ) {
		case 'user_name':
		case 'empty_username':
		case 'invalid_username':
		case 'illegal_names':
			?><div class="error"><p><?php _e( 'Invalid Username.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'username_exists':
			?><div class="error"><p><?php _e( 'This username exists.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'empty_email':
		case 'user_email':
		case 'invalid_email':
			?><div class="error"><p><?php _e( 'Invalid E-Mail address.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'email_exists':
		case 'user_email_used':
			?><div class="error"><p><?php _e( 'E-Mail exists.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'registerfail':
			?><div class="error"><p><?php _e( 'Something went wrong. Please consult the administrator.', 'user-frontend-td' ); ?></p></div><?php
			break;
		default:
			break;
	}
}
