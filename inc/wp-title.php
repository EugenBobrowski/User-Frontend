<?php
/**
 * Feature Name: WP Title
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Filter the text of the page title.
 *
 * @wp-hook	wp_title
 * @param	string $title       Page title.
 * @param	string $sep         Title separator.
 * @param	string $seplocation Location of the separator (left or right).
 * @return	string the manipulated title
 */
function uf_wp_title( $title, $sep, $seplocation ) {

	// get current url
	$current_action = get_query_var( 'uf' );
	if ( ! $current_action )
		return $title;

	// switch the title
	switch ( $current_action ) {
		case 'user-login':
			$title = apply_filters( 'uf_wp_title_user_login', __( 'Login', 'user-frontend-td' ) );
			break;
		case 'user-profile':
			$title = apply_filters( 'uf_wp_title_user_profile', __( 'Profile', 'user-frontend-td' ) );
			break;
		case 'user-register':
			$title = apply_filters( 'uf_wp_title_user_register', __( 'Register', 'user-frontend-td' ) );
			break;
		case 'user-reset-password':
			$title = apply_filters( 'uf_wp_title_user_reset_password', __( 'Reset Password', 'user-frontend-td' ) );
			break;
		case 'user-forgot-password':
			$title = apply_filters( 'uf_wp_title_user_forgot_password', __( 'Forgot Password', 'user-frontend-td' ) );
			break;
		case 'user-activation':
			$title = apply_filters( 'uf_wp_title_user_activation', __( 'Activation', 'user-frontend-td' ) );
			break;
		default:
			$title = $title;
			break;
	}

	// set the tempory seperator
	$t_sep = '%WP_TITILE_SEP%'; // Temporary separator, for accurate flipping, if necessary

	// set prefix
	$prefix = '';
	if ( !empty( $title ) )
		$prefix = " $sep ";

	/**
	 * Filter the parts of the page title.
	 *
	 * @since 4.0.0
	 *
	 * @param array $title_array Parts of the page title.
	 */
	$title_array = apply_filters( 'wp_title_parts', explode( $t_sep, $title ) );

	// Determines position of the separator and direction of the breadcrumb
	if ( 'right' == $seplocation ) { // sep on right, so reverse the order
		$title_array = array_reverse( $title_array );
		$title = implode( " $sep ", $title_array ) . $prefix;
	} else {
		$title = $prefix . implode( " $sep ", $title_array );
	}

	return $title;
}