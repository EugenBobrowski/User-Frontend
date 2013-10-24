<?php
/**
 * Plugin Name:	User Frontend
 * Description:	This plugin restricts the access to the admin panel and provides register, profile, login and logout features inside of the theme. 
 * Version:		1.0
 * Author:		HerrLlama for Inpsyde GmbH
 * Author URI:	http://inpsyde.com
 * Licence:		GPLv3
 */

// check wp
if ( ! function_exists( 'add_action' ) )
	return;

// constants
define( 'UF_TEXTDOMAIN', 'user-frontend-td' );

// kickoff
add_action( 'plugins_loaded', 'uf_init' );
function uf_init() {
	
	// This is for the Heartbeat API and auth check.
	// If auth check is failed, the user will be promted to login again.
	// It loads wp_login_url() in a iframe. Currently the user-login.php is
	// not optimized for this iframe, so lets just link the login URL, which opens
	// in a new tab. Fore more, see wp_auth_check_html()
	add_action( 'wp_auth_check_same_domain', '__return_false' );
	
	// helpers
	require_once dirname( __FILE__ ) . '/inc/localization.php';
	require_once dirname( __FILE__ ) . '/inc/restrict-backend-access.php';
	require_once dirname( __FILE__ ) . '/inc/helpers.php';
	
	// scripts
	require_once dirname( __FILE__ ) . '/inc/scripts.php';
	
	// template includes and the action handler
	require_once dirname( __FILE__ ) . '/inc/template-loader.php';
	require_once dirname( __FILE__ ) . '/inc/action-handler.php';
	
	// load all the actions
	require_once dirname( __FILE__ ) . '/inc/action-activation.php';
	require_once dirname( __FILE__ ) . '/inc/action-forgot-password.php';
	require_once dirname( __FILE__ ) . '/inc/action-login.php';
	require_once dirname( __FILE__ ) . '/inc/action-logout.php';
	require_once dirname( __FILE__ ) . '/inc/action-profile.php';
	require_once dirname( __FILE__ ) . '/inc/action-register.php';
	require_once dirname( __FILE__ ) . '/inc/action-reset-password.php';
}