<?php
add_action('wp_ajax_blacksmith_property_pagination', 'blacksmith_property_pagination');
add_action('wp_ajax_nopriv_blacksmith_property_pagination', 'blacksmith_property_pagination');

function blacksmith_property_pagination() {
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 6;
    $display_mode = isset($_POST['display_mode']) ? sanitize_text_field($_POST['display_mode']) : 'all';
    $selected_properties = isset($_POST['selected_properties']) ? explode(',', sanitize_text_field($_POST['selected_properties'])) : array();

    $args = array(
        'post_type' => 'property',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
    );

    if ($display_mode === 'manual' && !empty($selected_properties)) {
        $args['post__in'] = $selected_properties;
        $args['orderby'] = 'post__in';
    }

    $properties = new WP_Query($args);

    ob_start();
    if ($properties->have_posts()) {
        while ($properties->have_posts()) {
            $properties->the_post();
            include BLACKSMITH_LISTING_PLUGIN_PATH . 'templates/property-card.php';
        }
    } else {
        echo '<p class="no-properties">' . esc_html__('No properties found.', 'blacksmith-listing') . '</p>';
    }
    wp_reset_postdata();
    $html = ob_get_clean();

    wp_send_json_success(array(
        'html' => $html,
        'max_num_pages' => $properties->max_num_pages,
    ));
}