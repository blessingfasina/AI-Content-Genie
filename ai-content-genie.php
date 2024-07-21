<?php
/*
Plugin Name: AI Content Genie
Plugin URI:  https://geniuscreations.com.ng/ai-content-genie
Description: An AI-powered content generator for WordPress.
Version:     1.1.0
Author:      Blessing Fasina
Author URI:  https://geniuscreations.com.ng
License:     GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: ai-content-genie
Domain Path: /languages
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin version.
define('AI_CONTENT_GENIE_VERSION', '1.1.0');

// Define plugin path.
define('AI_CONTENT_GENIE_PATH', plugin_dir_path(__FILE__));

// Include the main class.
require_once AI_CONTENT_GENIE_PATH . 'includes/class-ai-content-genie.php';

// Initialize the plugin.
add_action('plugins_loaded', ['AI_Content_Genie', 'get_instance']);

// Add settings link to the plugin action links.
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'ai_content_genie_action_links');

function ai_content_genie_action_links($links) {
    $settings_link = '<a href="admin.php?page=ai-content-genie-settings">' . __('Settings', 'ai-content-genie') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
