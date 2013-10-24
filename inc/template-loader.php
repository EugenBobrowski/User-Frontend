<?php
/**
 * Feature Name:	Template Loader
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'template_include', 'uf_template_include' );
function uf_template_include( $template ) {
	
	// check if we have an action or a template
	if ( get_query_var( 'uf' ) == 'user-action' )
		return;
	
	$user_template = get_query_template( get_query_var( 'uf' ) );
	$new_template = plugin_dir_path( __FILE__ ) . '../templates/' . get_query_var( 'uf' ) . '.php';
	$is_uf = FALSE;

	if ( $user_template ){
		$is_uf     = TRUE;
		$template   = $user_template;
	} else if ( file_exists( $new_template ) ){
		$is_uf     = TRUE;
		$template   = $new_template;
	}
	
	if ( $is_uf ) {
		// we have to rebuild the request_vars after
		// the redirect to wp-load.php on our custom
		// login-, register, logout-stuff!
		do_action( 'uf_get_request_vars' );
	}

	return $template;
}

// Rewrite Rules
add_action( 'generate_rewrite_rules', 'uf_generate_rewrite_rules' );
function uf_generate_rewrite_rules( $wp_rewrite ) {

	$rules = array(
		'user-action'								=> 'index.php?uf=user-action',
		'user-login'								=> 'index.php?uf=user-login',
		'user-profile'								=> 'index.php?uf=user-profile',
		'user-register'								=> 'index.php?uf=user-register',
		'user-reset-password'						=> 'index.php?uf=user-reset-password',
		'user-forgot-password'						=> 'index.php?uf=user-forgot-password',
		'user-activation/?([A-Za-z0-9-_.,]+)?'		=> 'index.php?uf=user-activation&key=$matches[1]',
	);

	$wp_rewrite->rules = $rules + $wp_rewrite->rules;
}

// query var registration
add_action( 'query_vars', 'uf_query_vars' );
function uf_query_vars( $qvars ){
	$qvars[] = 'uf';
	return $qvars;
}

// Set is_home to false and no robots tag
add_action( 'template_redirect', 'uf_template_redirect' );
function uf_template_redirect() {
	global $wp_query;

	if ( $wp_query->get( 'uf' ) ) {
		$wp_query->is_home = FALSE; // Set is_home parameter to false
		add_action( 'wp_head', 'wp_no_robots' );
	}
}