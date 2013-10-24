<?php
// **********************************************************
// If you want to have an own template for this action
// just copy this file into your current theme folder
// and change the markup as you want to
// **********************************************************
if ( is_user_logged_in() ) {
	wp_safe_redirect( get_bloginfo( 'url' ) . '/user-profile/' );
	exit;
}
get_header(); ?>

<h2><?php _e( 'Register', UF_TEXTDOMAIN ); ?></h2>
<?php echo apply_filters( 'uf_register_messages', isset( $_GET[ 'message' ] ) ? $_GET[ 'message' ] : '' ); ?>

<form action="<?php echo uf_get_action_url( 'register' ); ?>" method="post">
	<?php wp_nonce_field( 'register', 'wp_uf_nonce_register' ); ?>

	<table class="form-table">
		<tr>
			<th><label for="user_login"><?php _e( 'Username' ); ?></label></th>
			<td><input type="text" name="user_login" id="user_login" class="regular-text" /> <span class="description"><?php _e( 'Usernames cannot be changed.' ); ?></span></td>
		</tr>
		<tr>
			<th><label for="email"><?php _e( 'E-mail' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label></th>
			<td><input type="text" name="email" id="email" class="regular-text" /></td>
		</tr>
	</table>
	
	<input type="submit" name="submit" id="submit" value="<?php _e( 'Register', UF_TEXTDOMAIN ); ?>">

</form>

<?php get_footer(); ?>