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

<h3><?php _e( 'Login', UF_TEXTDOMAIN ); ?></h3>
<?php
	$message = apply_filters( 'uf_login_messages', isset( $_GET[ 'message' ] ) ? $_GET[ 'message' ] : '' );
	echo $message;

	// defining the action
	$the_action = 'login';
?>
<form action="<?php echo UF_ACTION_URL; ?>?action=<?php echo $the_action; ?>" method="post">
	<?php wp_nonce_field( $the_action, 'wp_uf_nonce_' . $the_action ); ?>
	<p>
		<label for="user_login"><?php _e( 'Username', UF_TEXTDOMAIN ); ?></label>
		<input type="text" name="user_login" id="user_login">
	</p>
	<p>
		<label for="user_pass"><?php _e( 'Password', UF_TEXTDOMAIN ); ?></label>
		<input type="password" name="user_pass" id="user_pass">
	</p>
	<p>
		<label for="rememberme"><input type="checkbox" name="rememberme" id="rememberme"> <?php _e( 'Remember', UF_TEXTDOMAIN ); ?></label>
		<input type="submit" name="submit" id="submit" value="<?php _e( 'Submit', UF_TEXTDOMAIN ); ?>">
	</p>
	<p>
		<a href="<?php echo home_url( '/user-forgot-password/' ); ?>"><?php _e( 'Forgot Password?', UF_TEXTDOMAIN ); ?></a> |
		<a href="<?php echo home_url( '/user-register/' ); ?>"><?php _e( 'Register', UF_TEXTDOMAIN ); ?></a>
	</p>
	<?php wp_nonce_field( 'login', 'wp_uf_nonce_login' ); ?>
</form>

<?php get_footer(); ?>
