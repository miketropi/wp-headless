<?php 
/**
 * Hooks 
 */

add_action( 'wp_headless_after_user_register', 'wp_headless_send_mail_after_register_user', 20 );