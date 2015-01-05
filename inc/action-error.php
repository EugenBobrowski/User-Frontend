<?php
/**
 * Feature Name: Action Error
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Displays a message
 *
 * @wp-hook	uf_error_messages
 * @param	string $message
 * @return	void
 */
function uf_error_messages( $message ) {

	switch ( $message ) {
		case 'noaction':
			?><div class="error"><p><?php _e( 'No action was setted.', 'user-frontend-td' ); ?></p></div><?php
			break;
		case 'nononce':
		default:
			?><div class="error"><p><?php _e( 'Are you sure you are supposed to do this? ', 'user-frontend-td' ); ?></p></div><?php
			break;
	}
}
