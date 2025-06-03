<?php 
/**
 * Helpers
 */

/**
 * function send mail after register user
 *
 * @param [type] $user_id
 * @return void
 */
function wp_headless_send_mail_after_register_user($user_id) {
    // wp blog name
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $user = get_user_by('id', $user_id);
    $to = $user->user_email;
    $subject = 'Welcome to our website';
    $message = '
        <h2>Welcome to Our Website!</h2>
        <p>Dear ' . $user->display_name . ',</p>
        <p>Thank you for registering with us. Your account has been successfully created.</p>
        <p>You can now log in using your email address and password.</p>
        <p>If you have any questions or need assistance, please don\'t hesitate to contact us.</p>
        <p>Best regards,<br>The '. $blogname .'</p>
    ';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    return wp_mail($to, $subject, $message, $headers);
}