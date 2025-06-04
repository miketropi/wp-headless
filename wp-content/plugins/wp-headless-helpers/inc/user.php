<?php 
/**
 * user
 */
use Firebase\JWT\JWT;

add_filter('jwt_auth_token_before_dispatch', function ($data, $user) {
  $refresh_token = base64_encode(random_bytes(64));
  update_user_meta($user->ID, 'jwt_refresh_token', $refresh_token);

  $data['refresh_token'] = $refresh_token;
  return $data;
}, 10, 2);

add_action('rest_api_init', function () {
  register_rest_route('jwt-auth/v1', '/refresh', [
      'methods'  => 'POST',
      'callback' => 'wp_headless_jwt_refresh_token',
      'permission_callback' => '__return_true',
  ]);
});

function wp_headless_jwt_refresh_token($request) {
  $params = $request->get_json_params();
  $refresh_token = sanitize_text_field($params['refresh_token'] ?? '');

  if (!$refresh_token) {
      return new WP_Error('missing_token', 'Missing refresh token', ['status' => 400]);
  }

  $users = get_users([
      'meta_key' => 'jwt_refresh_token',
      'meta_value' => $refresh_token,
      'number' => 1,
      'count_total' => false,
  ]);

  if (empty($users)) {
      return new WP_Error('invalid_token', 'Invalid refresh token', ['status' => 403]);
  }

  $user = $users[0];

  // create new access token
  $issued_at = time();
  $not_before = apply_filters('jwt_auth_not_before', $issued_at, $issued_at);
  $expire = apply_filters('jwt_auth_expire', $issued_at + (60 * 15), $issued_at); // 15 phút

  $token = [
      'iss' => get_bloginfo('url'),
      'iat' => $issued_at,
      'nbf' => $not_before,
      'exp' => $expire,
      'data' => [
          'user' => [
              'id' => $user->ID,
          ],
      ],
  ];

  $secret_key = defined('JWT_AUTH_SECRET_KEY') ? JWT_AUTH_SECRET_KEY : false;
  if (!$secret_key) {
      return new WP_Error('jwt_auth_bad_config', 'JWT secret key not configured', ['status' => 500]);
  }

  $jwt = JWT::encode($token, $secret_key, 'HS256');

  return [
      'token' => $jwt,
      'user_email' => $user->user_email,
      'user_display_name' => $user->display_name,
  ];
}

add_action('rest_api_init', function () {
  register_rest_route('jwt-auth/v1', '/logout', [
      'methods'  => 'POST',
      'callback' => function ($request) {
          $user_id = get_current_user_id();
          if ($user_id) {
              delete_user_meta($user_id, 'jwt_refresh_token');
              return ['success' => true];
          }
          return new WP_Error('not_logged_in', 'User not logged in', ['status' => 403]);
      },
      'permission_callback' => function () {
          return is_user_logged_in();
      },
  ]);
});