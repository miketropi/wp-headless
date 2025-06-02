<?php
/**
 * User Registration API
 *
 * @package WP_Headless_Helpers
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Class for handling user registration via REST API
 */
class WP_Headless_User_Registration {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    /**
     * Register REST API routes
     */
    public function register_routes() {
        register_rest_route('wp-headless/v1', '/register', array(
            'methods'  => 'POST',
            'callback' => array($this, 'register_user'),
            'permission_callback' => '__return_true',
            'args' => array(
                'username' => array(
                    'required' => true,
                    'sanitize_callback' => 'sanitize_user',
                ),
                'email' => array(
                    'required' => true,
                    'validate_callback' => function($param) {
                        return is_email($param);
                    },
                    'sanitize_callback' => 'sanitize_email',
                ),
                'password' => array(
                    'required' => true,
                    'validate_callback' => function($param) {
                        return strlen($param) >= 8;
                    },
                ),
            ),
        ));
    }

    /**
     * Register a new user
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or error object on failure.
     */
    public function register_user($request) {
        $username = $request->get_param('username');
        $email = $request->get_param('email');
        $password = $request->get_param('password');

        // Check if username already exists
        if (username_exists($username)) {
            return new WP_Error(
                'username_exists',
                __('Username already exists', 'wp-headless-helpers'),
                array('status' => 400)
            );
        }

        // Check if email already exists
        if (email_exists($email)) {
            return new WP_Error(
                'email_exists',
                __('Email address already exists', 'wp-headless-helpers'),
                array('status' => 400)
            );
        }

        // Create the user
        $user_id = wp_create_user($username, $password, $email);

        // Check if user creation was successful
        if (is_wp_error($user_id)) {
            return $user_id;
        }

        // Set user role
        $user = new WP_User($user_id);
        $user->set_role('subscriber');

        // Return success response
        return new WP_REST_Response(
            array(
                'code' => 'user_created',
                'message' => __('User registered successfully', 'wp-headless-helpers'),
                'data' => array(
                    'user_id' => $user_id,
                    'username' => $username,
                    'email' => $email,
                ),
            ),
            201
        );
    }
}

// Initialize the class
$wp_headless_user_registration = new WP_Headless_User_Registration();