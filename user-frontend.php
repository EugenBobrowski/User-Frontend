<?php
/**
 * Plugin Name:	User Frontend
 * Description:	This plugin restricts the access to the admin panel and provides register, profile, login and logout features inside of the theme.
 * Version:		1.0.2
 * Author:		HerrLlama for wpcoding.de
 * Author URI:	http://wpcoding.de
 * Licence:		GPLv3
 */

// check wp
if ( ! function_exists( 'add_action' ) )
	return;

// set some constants
define( 'UF_PLUGIN_BASEFILE', __FILE__ );

/**
 * Inits the plugins, loads all the files
 * and registers all actions and filters
 *
 * @wp-hook	plugins_loaded
 * @return	void
 */
function uf_init() {

	// This is for the Heartbeat API and auth check.
	// If auth check is failed, the user will be promted to login again.
	// It loads wp_login_url() in a iframe. Currently the user-login.php is
	// not optimized for this iframe, so lets just link the login URL, which opens
	// in a new tab. Fore more, see wp_auth_check_html()
	add_action( 'wp_auth_check_same_domain', '__return_false' );

	// localization
	require_once dirname( __FILE__ ) . '/inc/localization.php';

	// set up the helper functions
	require_once dirname( __FILE__ ) . '/inc/helpers.php';
	add_action( 'uf_set_request_vars', 'uf_set_request_vars' );
	add_action( 'uf_get_request_vars', 'uf_get_request_vars' );
	add_action( 'lostpassword_url', 'uf_lostpassword_url' );
	add_action( 'register_url', 'uf_register_url' );
	add_action( 'wp_signup_location', 'uf_signup_location' );
	add_action( 'login_url', 'uf_login_url', 10, 2 );
	add_action( 'logout_url', 'uf_logout_url', 10, 2 );
	add_action( 'edit_profile_url', 'uf_edit_profile_url' );

	// restrict the backend access
	require_once dirname( __FILE__ ) . '/inc/restrict-backend-access.php';
	add_action( 'show_admin_bar', 'uf_maybe_remove_admin_bar' );
	add_action( 'admin_init', 'uf_maybe_block_backend' );

	// scripts
	require_once dirname( __FILE__ ) . '/inc/scripts.php';
	add_action( 'wp_enqueue_scripts', 'uf_wp_enqueue_scripts' );

	// template includes
	require_once dirname( __FILE__ ) . '/inc/template-loader.php';
	add_action( 'template_include', 'uf_template_include' );
	add_action( 'generate_rewrite_rules', 'uf_generate_rewrite_rules' );
	add_action( 'query_vars', 'uf_query_vars' );
	add_action( 'template_redirect', 'uf_template_redirect' );

	// the action handler
	require_once dirname( __FILE__ ) . '/inc/action-handler.php';
	add_action( 'init', 'uf_action_handler' );

	// the special error handler
	require_once dirname( __FILE__ ) . '/inc/action-error.php';
	add_action( 'uf_error_messages', 'uf_error_messages' );

	// user activation
	require_once dirname( __FILE__ ) . '/inc/action-activation.php';
	add_action( 'uf_activation', 'uf_perform_activation' );
	add_action( 'uf_activation_messages', 'uf_activation_messages' );

	// forgot password
	require_once dirname( __FILE__ ) . '/inc/action-forgot-password.php';
	add_action( 'uf_forgot_password', 'uf_perform_forgot_password' );
	add_action( 'uf_forgot_password_messages', 'uf_forgot_password_messages' );

	// user login
	require_once dirname( __FILE__ ) . '/inc/action-login.php';
	add_action( 'uf_login', 'uf_perform_login' );
	add_action( 'uf_login_messages', 'uf_login_messages' );

	// user logout
	require_once dirname( __FILE__ ) . '/inc/action-logout.php';
	add_action( 'uf_logout', 'uf_perform_logout' );

	// user profile
	require_once dirname( __FILE__ ) . '/inc/action-profile.php';
	add_action( 'uf_profile', 'uf_perform_profile_edit' );
	add_action( 'uf_profile_messages', 'uf_profile_messages' );

	// registration
	require_once dirname( __FILE__ ) . '/inc/action-register.php';
	add_action( 'uf_register', 'uf_perform_register_edit' );
	add_action( 'wpmu_signup_user_notification', 'uf_wpmu_signup_user_notification', 1, 4 );
	add_action( 'uf_register_messages', 'uf_register_messages' );

	// reset password
	require_once dirname( __FILE__ ) . '/inc/action-reset-password.php';
	add_action( 'uf_reset_password', 'uf_perform_reset_password' );
	add_action( 'uf_reset_password_messages', 'uf_reset_password_messages' );

	// wp titles
	require_once dirname( __FILE__ ) . '/inc/wp-title.php';
	add_action( 'wp_title', 'uf_wp_title', 10, 3 );
} add_action( 'plugins_loaded', 'uf_init' );
