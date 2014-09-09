<?php
/**
 * Feature Name: Scripts
 * Author:       HerrLlama for wpcoding.de
 * Author URI:   http://wpcoding.de
 * Licence:      GPLv3
 */

/**
 * Adds the needed javascript in the frontend
 *
 * @wp-hook	wp_enqueue_scripts
 * @return	void
 */
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