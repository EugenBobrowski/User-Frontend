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

<h3><?php _e( 'Lost your password?', UF_TEXTDOMAIN ); ?></h3>
<?php
	$message = apply_filters( 'uf_forgot_password_messages', isset( $_GET[ 'message' ] ) ? $_GET[ 'message' ] : '' );
	echo $message;

	// defining the action
	$the_action = 'forgot_password';
?>
<form action="<?php echo UF_ACTION_URL; ?>?action=<?php echo $the_action; ?>" method="post">
	<?php wp_nonce_field( $the_action, 'wp_uf_nonce_' . $the_action ); ?>
	<p>
		<?php _e( 'Please enter your username or email address. You will receive a link to create a new password via email.' ) ?>
	</p>
	<p>
		<label for="user_login"><?php _e( 'Username or E-mail:' ); ?></label>
		<input type="text" name="user_login" id="user_login">
	</p>
	<p>
		<input type="submit" name="submit" id="submit" value="<?php _e( 'Get New Password' ); ?>">
	</p>
</form>

<?php get_footer(); ?>
