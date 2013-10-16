<?php
// **********************************************************
// If you want to have an own template for this action
// just copy this file into your current theme folder
// and change the markup as you want to
// **********************************************************
if ( is_user_logged_in() ) {
	wp_safe_redirect( home_url( '/user-profile/' ) );
	exit;
}
?>
<?php get_header(); ?>

<h3><?php _e( 'Activation', UF_TEXTDOMAIN ); ?></h3>
<?php
	$message = apply_filters( 'uf_activation_messages', isset( $_GET[ 'message' ] ) ? $_GET[ 'message' ] : '' );
	echo $message;

	// defining the action
	$the_action = 'activation';
?>
<form action="<?php echo UF_ACTION_URL; ?>?action=activation" method="post">
	<?php wp_nonce_field( $the_action, 'wp_uf_nonce_' . $the_action ); ?>

	<p>
		<?php _e( 'Please enter your key.', UF_TEXTDOMAIN ) ?>
	</p>
	<p>
		<label for="user_key"><?php _e( 'Key:', UF_TEXTDOMAIN ); ?></label>
		<input type="text" name="user_key" id="user_key" value="<?php echo isset( $_GET[ 'key' ] ) ? $_GET[ 'key' ] : ''; ?>">
	</p>
	<p>
		<input type="submit" name="submit" id="submit" value="<?php _e( 'Activate' ); ?>">
	</p>
</form>

<?php get_footer(); ?>
