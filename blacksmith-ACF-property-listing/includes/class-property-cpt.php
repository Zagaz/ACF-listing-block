<?php

/**
 * Property Custom Post Type
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Property Custom Post Type
 * 
 * This class registers a custom post type for properties,
 * adds meta boxes for property details, and handles saving of those details.
 */
class Blacksmith_Property_CPT {

    public function __construct() {
        add_action('init', array($this, 'register_property_cpt'));
        add_action('add_meta_boxes', array($this, 'add_property_meta_boxes'));
        add_action('save_post', array($this, 'save_property_meta'));
    }

    public function register_property_cpt() {
        $labels = array(
            'name'               => _x('Properties', 'post type general name', 'blacksmith-listing'),
            'singular_name'      => _x('Property', 'post type singular name', 'blacksmith-listing'),
            'menu_name'          => _x('Properties', 'admin menu', 'blacksmith-listing'),
            'name_admin_bar'     => _x('Property', 'add new on admin bar', 'blacksmith-listing'),
            'add_new'            => _x('Add New', 'property', 'blacksmith-listing'),
            'add_new_item'       => __('Add New Property', 'blacksmith-listing'),
            'new_item'           => __('New Property', 'blacksmith-listing'),
            'edit_item'          => __('Edit Property', 'blacksmith-listing'),
            'view_item'          => __('View Property', 'blacksmith-listing'),
            'all_items'          => __('All Properties', 'blacksmith-listing'),
            'search_items'       => __('Search Properties', 'blacksmith-listing'),
            'parent_item_colon'  => __('Parent Properties:', 'blacksmith-listing'),
            'not_found'          => __('No properties found.', 'blacksmith-listing'),
            'not_found_in_trash' => __('No properties found in Trash.', 'blacksmith-listing')
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'property'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-admin-home',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
            'taxonomies'         => array('property_type')
        );

        register_post_type('property', $args);
    }

    public function add_property_meta_boxes() {
        add_meta_box(
            'property-details',
            __('Property Details', 'blacksmith-listing'),
            array($this, 'property_details_callback'),
            'property',
            'normal',
            'high'
        );
    }

    public function property_details_callback($post) {
        // Use nonce for verification
    
        wp_nonce_field('property_details_nonce', 'property_details_nonce');

        $price = get_post_meta($post->ID, '_property_price', true);
        $location = get_post_meta($post->ID, '_property_location', true);

        // Get current property type terms
        $selected_terms = wp_get_post_terms($post->ID, 'property_type', array('fields' => 'ids'));
        $all_terms = get_terms(array('taxonomy' => 'property_type', 'hide_empty' => false));
        ?>
        <table class="form-table">
            <tr>
                <th><label for="property_price"><?php echo esc_html(__('Price', 'blacksmith-listing')); ?></label></th>
                <td><input type="text" id="property_price" name="property_price" value="<?php echo esc_attr($price); ?>" /></td>
            </tr>
            <tr>
                <th><label for="property_location"><?php echo esc_html(__('Location', 'blacksmith-listing')); ?></label></th>
                <td><input type="text" id="property_location" name="property_location" value="<?php echo esc_attr($location); ?>" /></td>
            </tr>
            <tr>
                <th><label for="property_type"><?php echo esc_html(__('Category', 'blacksmith-listing')); ?></label></th>
                <td>
                    <select name="property_type[]" id="property_type" multiple style="min-width:180px;">
                        <?php foreach ($all_terms as $term): ?>
                            <option value="<?php echo esc_attr($term->term_id); ?>" <?php echo in_array($term->term_id, $selected_terms) ? 'selected' : ''; ?>>
                                <?php echo esc_html($term->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="description"><?php esc_html_e('Hold Ctrl (Windows) or Cmd (Mac) to select multiple.', 'blacksmith-listing'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_property_meta($post_id) {
        if (!isset($_POST['property_details_nonce']) || !wp_verify_nonce($_POST['property_details_nonce'], 'property_details_nonce')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        update_post_meta($post_id, '_property_price', sanitize_text_field($_POST['property_price']));
        update_post_meta($post_id, '_property_location', sanitize_text_field($_POST['property_location']));

        // Save property type taxonomy
        if (isset($_POST['property_type'])) {
            $term_ids = array_map('intval', $_POST['property_type']);
            wp_set_object_terms($post_id, $term_ids, 'property_type');
        }
    }
}