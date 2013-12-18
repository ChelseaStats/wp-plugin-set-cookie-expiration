/*
Plugin Name: TCR Set Cookie Expire
Description: Set the expire time for cookies in <a href="options-privacy.php">Settings &raquo; Privacy</a>.
Version: 1.0.0
Plugin URI: http://thecellarroom.net
Author: The Cellar Room Limited
Author URI: http://www.thecellarroom.net
Copyright (c) 2013 The Cellar Room Limited
*/
if ( is_admin() ) :

/**
 * Register settings.
 */
function _TCR_set_cookie_expire_admin()
{
    foreach ( array( 'normal' => 'Normal', 'remember' => 'Remember' ) as $type => $label ) {
        register_setting( 'privacy', "{$type}_cookie_expire", 'absint' );
        add_settings_field( "{$type}_cookie_expire", $label . ' cookie expire', '_TCR_set_cookie_expire_option', 'privacy', 'default', $type );
    }
}
add_action( 'admin_init', '_TCR_set_cookie_expire_admin' );

/**
 * Setting field callback.
 */
function _TCR_set_cookie_expire_option( $type )
{
    if ( !$expires = get_option("{$type}_cookie_expire") )
        $expires = $type === 'normal' ? 2 : 14;
    echo '<input type="text" name="' . $type . '_cookie_expire" value="' . intval( $expires ) . '" class="small-text" /> days';
}

endif;

/**
 * Filter in our user-specified expire times.
 */
function _TCR_set_cookie_expire_filter( $default, $user_ID, $remember )
{
    if ( !$expires = get_option( $remember ? 'remember_cookie_expire' : 'normal_cookie_expire' ) )
        $expires = 0;

    if ( $expires = ( intval( $expires ) * 86400 ) ) // get seconds
        $default = $expires;
    return $default;
}
add_filter( 'auth_cookie_expiration', '_TCR_set_cookie_expire_filter', 10, 3 );

?>
