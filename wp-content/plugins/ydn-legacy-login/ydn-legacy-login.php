<?php
/*
Plugin Name: YDN Legacy Login
Plugin URI:  http://yaledailynews.com
Description: This plugin allows authentication of users with legacy passwords
Version: 1.0
Author: Michael DiScala
License: GPL2
*/

/**
 * This filter will attempt to authenticate users against legacy logins 
 * imported from the Ellington cms.  If no legacy password is available,
 * it simply passes without doing anything. If a legacy password is available,
 * it attempts to authenticate the user against that password.  If the credentials
 * are valid, the legacy password is converted into the wordpress format.  If the credentials 
 * are invalid, an appropriate error is returned */
function ydn_legacy_authentication_filter($user, $username, $password) {
  if ($username == '' || $password == '') {
    return; /* let other filters worry about error handling */
  }

  $matching_user = get_user_by("login", $username);
  if (!$matching_user) {
    return; /* let other filters worry about error handling when the user doesn't exist */
  }

  $matching_user_legacy_pw = get_user_meta($matching_user->ID, "ydn_legacy_password", true);
  if ($matching_user_legacy_pw == '') {
    return; /* there's no legacy password, so move on */
  }

  $legacy_pw_parts = explode('$', $matching_user_legacy_pw);
  if ( count($legacy_pw_parts) != 3) {
    /* the legacy password doesn't follow the correct format. delete it */
    delete_user_meta( $matching_user->ID, "ydn_legacy_password"); 
    return;
  }

  $salt = $legacy_pw_parts[1];
  $hash = $legacy_pw_parts[2];

  if ($hash == sha1($salt . $password) ) {
    wp_set_password( $password, $matching_user->ID ); //setup their password in the WP encryption scheme
    delete_user_meta( $matching_user->ID, "ydn_legacy_password"); //remove the old password
    $user = $matching_user; //return the auth'd user
  } else {
    $user = new WP_Error('denied', __("That username/password combination does not exist in our database.", "ydn") );
  }

  return $user;
}

add_filter('authenticate','ydn_legacy_authentication_filter',10,3);

/**
 * This action simply removes the legacy metadata in case the password is reset by
 * other means within wordpress */

function ydn_legacy_remove_password_meta($user, $new_pass) {
  delete_user_meta( $user->ID, "ydn_legacy_password");
}

add_action( "password_reset", "ydn_legacy_remove_password_meta");

?>
