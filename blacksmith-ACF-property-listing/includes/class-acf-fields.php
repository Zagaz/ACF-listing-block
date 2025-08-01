<?php

/**
 * ACF Fields for Property Listing Block
 */

if (!defined('ABSPATH')) {
    exit;
}

class Blacksmith_Listing_ACF_Fields {

    public function __construct() {
        add_action('acf/init', array($this, 'register_acf_fields'));
    }

    public function register_acf_fields() {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array(
                'key' => 'group_property_listing_block',
                'title' => 'Property Listing Block Settings',
                'fields' => array(
                    array(
                        'key' => 'field_listing_display_mode',
                        'label' => 'Display Mode',
                        'name' => 'display_mode',
                        'type' => 'radio',
                        'choices' => array(
                            'all' => 'Show all published properties',
                            'manual' => 'Manually select specific properties'
                        ),
                        'default_value' => 'all',
                        'layout' => 'vertical',
                    ),
                    array(
                        'key' => 'field_listing_selected_properties',
                        'label' => 'Select Properties',
                        'name' => 'selected_properties',
                        'type' => 'relationship',
                        'post_type' => array('property'),
                        'filters' => array('search', 'taxonomy'),
                        'return_format' => 'id',
                        'min' => 0,
                        'max' => '',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_listing_display_mode',
                                    'operator' => '==',
                                    'value' => 'manual',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_listing_posts_per_page',
                        'label' => 'Properties per Page',
                        'name' => 'posts_per_page',
                        'type' => 'number',
                        'default_value' => 6,
                        'min' => 1,
                        'max' => 50,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_listing_display_mode',
                                    'operator' => '==',
                                    'value' => 'all',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_listing_show_filters',
                        'label' => 'Show Filters',
                        'name' => 'show_filters',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_listing_show_search',
                        'label' => 'Show Search Bar',
                        'name' => 'show_search',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_listing_grid_columns',
                        'label' => 'Grid Columns',
                        'name' => 'grid_columns',
                        'type' => 'select',
                        'choices' => array(
                            '1' => '1 Column',
                            '2' => '2 Columns',
                            '3' => '3 Columns',
                            '4' => '4 Columns'
                        ),
                        'default_value' => '3',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/property-listing',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
            ));
        }
    }
}