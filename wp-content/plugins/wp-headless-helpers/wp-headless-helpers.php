<?php 
/**
 * Plugin Name: WP Headless Helpers.
 * Plugin URI: 
 * Description: Helper functions and utilities for headless WordPress setup
 * Version: 1.0.2
 * Author: @Mike
 * Author URI: #
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-headless-helpers
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// require composer autoload.php
require_once plugin_dir_path(__FILE__). 'vendor/autoload.php';

// Include the user registration functionality
require_once plugin_dir_path(__FILE__). 'inc/helpers.php';
require_once plugin_dir_path(__FILE__). 'inc/hooks.php';

require_once plugin_dir_path(__FILE__). 'inc/user.php';
require_once plugin_dir_path(__FILE__) . 'inc/user-register.php';

require_once plugin_dir_path(__FILE__). 'inc/post.php';
