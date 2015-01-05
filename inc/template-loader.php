<?php
/**
 * Feature Name: Template Loader
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Checks which template should be included
 *
 * @wp-hook	template_include
 * @param	string $template
 * @return	string the template file
 */
function uf_template_include( $template ) {

	// check if we have an action or a template
	if ( get_query_var( 'uf' ) == 'user-action' )
		return;

	$user_template = get_template_directory() . '/user-frontend/' . get_query_var( 'uf' ) . '.php';
	$new_template = plugin_dir_path( __FILE__ ) . '../templates/' . get_query_var( 'uf' ) . '.php';
	$is_uf = FALSE;

	if ( file_exists( $user_template ) ) {
		$is_uf     = TRUE;
		$template   = $user_template;
	} else if ( file_exists( $new_template ) ) {
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

/**
 * Rewrite the permalinks
 *
 * @wp-hook	generate_rewrite_rules
 * @param	object $wp_rewrite the current permalink setup
 * @return	void
 */
function uf_generate_rewrite_rules( $wp_rewrite ) {

	$rules = array(
		'user-action'                          => 'index.php?uf=user-action',
		'user-error'                           => 'index.php?uf=user-error',
		'user-login'                           => 'index.php?uf=user-login',
		'user-profile'                         => 'index.php?uf=user-profile',
		'user-register'                        => 'index.php?uf=user-register',
		'user-reset-password'                  => 'index.php?uf=user-reset-password',
		'user-forgot-password'                 => 'index.php?uf=user-forgot-password',
		'user-activation/?([A-Za-z0-9-_.,]+)?' => 'index.php?uf=user-activation&key=$matches[1]',
	);

	$wp_rewrite->rules = $rules + $wp_rewrite->rules;
}

/**
 * query var registration
 *
 * @wp-hookquery_vars
 * @param	array $qvars the query vars
 * @return	string the manipulated query vars array
 */
function uf_query_vars( $qvars ){
	$qvars[] = 'uf';
	return $qvars;
}

/**
 * Set is_home to false and no robots tag
 *
 * @wp-hook	template_redirect
 * @return	void
 */
function uf_template_redirect() {
	global $wp_query;

	if ( $wp_query->get( 'uf' ) ) {
		$wp_query->is_home = FALSE; // Set is_home parameter to false
		add_action( 'wp_head', 'wp_no_robots' );
	}
}
