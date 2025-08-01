<?php

/**
 * Property Taxonomy
 */

if (!defined('ABSPATH')) {
    exit;
}

class Blacksmith_Property_Taxonomy {

    public function __construct() {
        add_action('init', array($this, 'register_property_type_taxonomy'));
    }

    public function register_property_type_taxonomy() {
        $labels = array(
            'name'              => _x('Property Types', 'taxonomy general name', 'blacksmith-listing'),
            'singular_name'     => _x('Property Type', 'taxonomy singular name', 'blacksmith-listing'),
            'search_items'      => __('Search Property Types', 'blacksmith-listing'),
            'all_items'         => __('All Property Types', 'blacksmith-listing'),
            'parent_item'       => __('Parent Property Type', 'blacksmith-listing'),
            'parent_item_colon' => __('Parent Property Type:', 'blacksmith-listing'),
            'edit_item'         => __('Edit Property Type', 'blacksmith-listing'),
            'update_item'       => __('Update Property Type', 'blacksmith-listing'),
            'add_new_item'      => __('Add New Property Type', 'blacksmith-listing'),
            'new_item_name'     => __('New Property Type Name', 'blacksmith-listing'),
            'menu_name'         => __('Property Types', 'blacksmith-listing'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'property-type'),
        );

        register_taxonomy('property_type', array('property'), $args);

        // Add default terms
        if (!term_exists('Residential', 'property_type')) {
            wp_insert_term('Residential', 'property_type');
        }
        if (!term_exists('Commercial', 'property_type')) {
            wp_insert_term('Commercial', 'property_type');
        }
        if (!term_exists('Industrial', 'property_type')) {
            wp_insert_term('Industrial', 'property_type');
        }
    }
}