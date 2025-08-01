<?php

/**
 * Plugin Name: Blacksmith ACF Listing Block
 * Plugin URI: https://blacksmith.dev
 * Description: Independent ACF Property Listing Block with filtering and search functionality
 * Version: 1.0.0
 * Author: Andre Ranulfo
 * License: GPL v2 or later
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Text Domain: blacksmith-listing
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('BLACKSMITH_LISTING_VERSION', '1.0.0');
define('BLACKSMITH_LISTING_PLUGIN_URL', plugin_dir_url(__FILE__));
define('BLACKSMITH_LISTING_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('BLACKSMITH_LISTING_PLUGIN_FILE', __FILE__);

// Load required files
require_once BLACKSMITH_LISTING_PLUGIN_PATH . 'includes/class-property-cpt.php';
require_once BLACKSMITH_LISTING_PLUGIN_PATH . 'includes/class-taxonomy.php';
require_once BLACKSMITH_LISTING_PLUGIN_PATH . 'includes/class-listing-block.php';
require_once BLACKSMITH_LISTING_PLUGIN_PATH . 'includes/class-acf-fields.php';
require_once plugin_dir_path(__FILE__) . 'includes/ajax.php';

// Initialize the plugin
function blacksmith_listing_block_init() {
    new Blacksmith_Property_CPT();
    new Blacksmith_Property_Taxonomy();
    new Blacksmith_Listing_Block();
    new Blacksmith_Listing_ACF_Fields();
}
add_action('plugins_loaded', 'blacksmith_listing_block_init');

// Activation hook
register_activation_hook(__FILE__, function() {
    // Create CPT and taxonomy
    new Blacksmith_Property_CPT();
    new Blacksmith_Property_Taxonomy();
    flush_rewrite_rules();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});

function blacksmith_listing_enqueue_scripts() {
    wp_enqueue_script(
        'blacksmith-listing-pagination',
        plugins_url('assets/js/pagination.js', __FILE__),
        array(),
        '1.0',
        true
    );
    wp_localize_script('blacksmith-listing-pagination', 'blacksmithListingAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'blacksmith_listing_enqueue_scripts');

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('dashicons');
});