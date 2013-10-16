<?php
/**
 * Feature Name:	Scripts
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

add_action( 'wp_enqueue_scripts', 'uf_wp_enqueue_scripts' );
function uf_wp_enqueue_scripts() {

	$script_suffix = '.js';
	if ( defined( 'WP_DEBUG' ) )
		$script_suffix = '.dev.js';

	wp_register_script(
		'uf-frontend-scripts',
		plugin_dir_url( __FILE__ ) . '../js/password' . $script_suffix,
		array( 'jquery', 'utils' )
	);

	wp_enqueue_script( 'uf-frontend-scripts' );
	wp_localize_script( 'uf-frontend-scripts', 'uf_vars', array(
		'strength_indicator'	=> __( 'Strength indicator' ),
		'very_weak'				=> __( 'Very weak' ),
		'weak'					=> __( 'Weak' ),
		'medium'				=> __( 'Medium' ),
		'strong'				=> __( 'Strong' ),
		'mismatch'				=> __( 'Mismatch' )
	) );
}