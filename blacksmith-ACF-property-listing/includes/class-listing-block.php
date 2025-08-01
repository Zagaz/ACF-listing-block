<?php

/**
 * Property Listing Block
 */

if (!defined('ABSPATH')) {
    exit;
}

class Blacksmith_Listing_Block {

    public function __construct() {
        add_action('acf/init', array($this, 'register_block'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_filter_properties', array($this, 'ajax_filter_properties'));
        add_action('wp_ajax_nopriv_filter_properties', array($this, 'ajax_filter_properties'));
    }

    public function register_block() {
        if (function_exists('acf_register_block_type')) {
            acf_register_block_type(array(
                'name'              => 'property-listing',
                'title'             => __('Property Listing', 'blacksmith-listing'),
                'description'       => __('Display a grid of property listings with filtering and search.', 'blacksmith-listing'),
                'render_template'   => BLACKSMITH_LISTING_PLUGIN_PATH . 'templates/listing-block.php',
                'render_callback'   => array($this, 'render_block'),
                'category'          => 'blacksmith',
                'icon'              => 'admin-home',
                'keywords'          => array('property', 'listing', 'real estate'),
                'supports'          => array(
                    'align' => array('wide', 'full'),
                    'mode' => false,
                ),
                'enqueue_style'     => BLACKSMITH_LISTING_PLUGIN_URL . 'assets/css/block.css',
                'enqueue_script'    => BLACKSMITH_LISTING_PLUGIN_URL . 'assets/js/block.js',
            ));
        }
    }

    public function enqueue_scripts() {
        if (has_block('acf/property-listing')) {
            wp_enqueue_style(
                'blacksmith-inter-font',
                'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap',
                array(),
                null
            );
            wp_enqueue_style(
                'blacksmith-listing-block',
                BLACKSMITH_LISTING_PLUGIN_URL . 'assets/css/block.css',
                array('blacksmith-inter-font'),
                BLACKSMITH_LISTING_VERSION
            );

            wp_enqueue_script(
                'blacksmith-listing-frontend',
                BLACKSMITH_LISTING_PLUGIN_URL . 'assets/js/frontend.js',
                array('jquery'),
                BLACKSMITH_LISTING_VERSION,
                true
            );

            wp_localize_script('blacksmith-listing-frontend', 'blacksmith_listing_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('blacksmith_listing_nonce')
            ));
        }
    }

    public function ajax_filter_properties() {
        check_ajax_referer('blacksmith_listing_nonce', 'nonce');

        $search = sanitize_text_field($_POST['search'] ?? '');
        $property_type = sanitize_text_field($_POST['property_type'] ?? '');
        $selected_properties = isset($_POST['selected_properties']) ? array_map('intval', $_POST['selected_properties']) : array();
        $display_mode = sanitize_text_field($_POST['display_mode'] ?? 'all');
        $posts_per_page = intval($_POST['posts_per_page'] ?? 6);

        $args = array(
            'post_type' => 'property',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
        );

        // Handle manual selection
        if ($display_mode === 'manual' && !empty($selected_properties)) {
            $args['post__in'] = $selected_properties;
        }

        // Add search
        if (!empty($search)) {
            $args['s'] = $search;
        }

        // Add taxonomy filter
        if (!empty($property_type)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'property_type',
                    'field'    => 'slug',
                    'terms'    => $property_type,
                )
            );
        }

        $query = new WP_Query($args);
        
        ob_start();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                include BLACKSMITH_LISTING_PLUGIN_PATH . 'templates/property-card.php';
            }
        } else {
            echo '<p class="no-properties">' . __('No properties found.', 'blacksmith-listing') . '</p>';
        }
        wp_reset_postdata();
        
        $html = ob_get_clean();
        
        wp_send_json_success(array(
            'html' => $html,
            'found_posts' => $query->found_posts
        ));
    }

    public function render_block($block, $content = '', $is_preview = false, $post_id = 0) {
        // Pass $is_preview to your template
        include BLACKSMITH_LISTING_PLUGIN_PATH . 'templates/listing-block.php';
    }
}