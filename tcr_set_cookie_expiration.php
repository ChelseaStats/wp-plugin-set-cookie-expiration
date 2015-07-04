<?php
/*
Plugin Name: TCR Set Cookie Expire
Description: Set the expire time for cookies in <a href="options-privacy.php">Settings &raquo; Privacy</a>.
Version: 1.0.0
Plugin URI: http://thecellarroom.uk
Author: The Cellar Room Limited
Author URI: http://thecellarroom.uk
Copyright (c) 2013 The Cellar Room Limited
*/

if ( !class_exists( 'tcr_cookie_monster' ) ) :

	class tcr_cookie_monster {

		function __construct() {
			add_filter( 'auth_cookie_expiration', array ( $this, 'tcr_set_cookie_expire_filter' ), 10, 3 );

			if ( is_admin() ) :
				add_action( 'admin_init',  array ( $this, 'tcr_set_cookie_expire_admin') );
			endif;
		}


		function tcr_set_cookie_expire_admin() {
			foreach ( array ( 'normal' => 'Normal', 'remember' => 'Remember' ) as $type => $label ) {
				register_setting( 'privacy', "{$type}_cookie_expire", 'absint' );
				add_settings_field( "{$type}_cookie_expire", $label . ' cookie expire', array( $this, 'tcr_set_cookie_expire_option') , 'privacy', 'default', $type );
			}
		}

		function tcr_set_cookie_expire_option( $type ) {
			if ( ! $expires = get_option( "{$type}_cookie_expire" ) ) {
				$expires = $type === 'normal' ? 2 : 14;
			}
			echo '<input type="text" name="' . $type . '_cookie_expire" value="' . intval( $expires ) . '" class="small-text" /> days';
		}


		function tcr_set_cookie_expire_filter( $default, $user_ID, $remember ) {
			if ( ! $expires = get_option( $remember ? 'remember_cookie_expire' : 'normal_cookie_expire' ) ) {
				$expires = 0;
			}

			if ( $expires = ( intval( $expires ) * 86400 ) ) // get seconds
			{
				$default = $expires;
			}

			return $default;
		}


	}

	new tcr_cookie_monster;

endif;

