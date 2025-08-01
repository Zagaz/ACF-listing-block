<?php
/**
 * Plugin Name: Blacksmith Hero Block
 * Plugin URI: https://blacksmith.dev
 * Description: Independent ACF Hero Block with video background support and SCSS styling
 * Version: 1.0.0
 * Author: Andre Ranulfo
 * License: GPL v2 or later
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Text Domain: blacksmith-hero
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('BLACKSMITH_HERO_VERSION', '1.0.0');
define('BLACKSMITH_HERO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('BLACKSMITH_HERO_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('BLACKSMITH_HERO_PLUGIN_FILE', __FILE__);

// Load required files
require_once BLACKSMITH_HERO_PLUGIN_PATH . 'includes/class-hero-block.php';
require_once BLACKSMITH_HERO_PLUGIN_PATH . 'includes/class-acf-fields.php';

// Initialize the plugin
function blacksmith_hero_block_init() {
    new Blacksmith_Hero_Block();
    new Blacksmith_Hero_ACF_Fields();
}
add_action('plugins_loaded', 'blacksmith_hero_block_init');

// Activation hook
register_activation_hook(__FILE__, function() {
    flush_rewrite_rules();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});