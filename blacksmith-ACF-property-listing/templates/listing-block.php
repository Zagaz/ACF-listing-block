<?php

/**
 * Property Listing Block Template
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get block settings
$display_mode = get_field('display_mode') ?: 'all';
$selected_properties = get_field('selected_properties') ?: array();
$posts_per_page = get_field('posts_per_page') ?: 6;
$show_filters = get_field('show_filters');
$show_search = get_field('show_search');
$grid_columns = get_field('grid_columns') ?: '3';

// Generate unique block ID
$block_id = 'property-listing-' . uniqid();

// Set up query args
$args = array(
    'post_type' => 'property',
    'post_status' => 'publish',
    'posts_per_page' => $posts_per_page,
);

if ($display_mode === 'manual' && !empty($selected_properties)) {
    $args['post__in'] = $selected_properties;
    $args['orderby'] = 'post__in';
}

$properties = new WP_Query($args);
?>
<div class="blacksmith-property-full">
    <div class="blacksmith-property-listing-wrapper">
    
        <div id="<?php echo esc_attr($block_id); ?>" class="blacksmith-property-listing" data-block-id="<?php echo esc_attr($block_id); ?>">
            <h2>Property Listings</h2>
            <?php if ($show_filters || $show_search): ?>


<?PHP  //FILTER?>


            <div class="property-filters">
                <?php if ($show_filters): ?>

                    
                <div class="taxonomy-filter">
                    <label for="property-type-<?php echo esc_attr($block_id); ?>" style="">Category</label>
                    <select id="property-type-<?php echo esc_attr($block_id); ?>" 
                            class="property-type-filter" 
                            data-block-id="<?php echo esc_attr($block_id); ?>">
                        <option value=""><?php esc_html_e('Please Select', 'blacksmith-listing'); ?></option>
                        <?php
                        $terms = get_terms(array(
                            'taxonomy' => 'property_type',
                            'hide_empty' => true,
                        ));
                        foreach ($terms as $term): ?>
                            <option value="<?php echo esc_attr($term->slug); ?>">
                                <?php echo esc_html($term->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>


                
        
                <?php if ($show_search): ?>
                <div class="search-filter">
                    <div class="property-search-wrapper" style="position: relative;">
                        <input type="text" 
                               id="property-search-<?php echo esc_attr($block_id); ?>" 
                               class="property-search" 
                               placeholder="<?php esc_attr_e('Search', 'blacksmith-listing'); ?>"
                               data-block-id="<?php echo esc_attr($block_id); ?>">
                        <span class="dashicons dashicons-search property-search-icon"></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>



            <?php
        //END FILTER
        endif; ?>
        
            <div class="property-grid columns-<?php echo esc_attr($grid_columns); ?>" 
                 id="property-grid-<?php echo esc_attr($block_id); ?>">
                <?php if ($properties->have_posts()): ?>
                    <?php while ($properties->have_posts()): $properties->the_post(); ?>
                        <?php include BLACKSMITH_LISTING_PLUGIN_PATH . 'templates/property-card.php'; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-properties"><?php esc_html_e('No properties found.', 'blacksmith-listing'); ?></p>
                <?php endif; ?>
            </div> <!-- end property-grid -->

            <!-- Paginator -->
            <div class="property-paginator" id="property-paginator-<?php echo esc_attr($block_id); ?>">
                <!-- Pagination buttons will be rendered here by JS -->
            </div>
            
            <!-- Hidden fields for AJAX -->
            <input type="hidden" class="display-mode" value="<?php echo esc_attr($display_mode); ?>">
            <input type="hidden" class="selected-properties" value="<?php echo esc_attr(implode(',', $selected_properties)); ?>">
            <input type="hidden" class="posts-per-page" value="<?php echo esc_attr($posts_per_page); ?>">
            
            <div class="loading-spinner" style="display: none;">
                <span><?php esc_html_e('Loading...', 'blacksmith-listing'); ?></span>
            </div>
        </div>
    </div>

</div>




<?php wp_reset_postdata(); ?>

<?php
if (!empty($is_preview)) {
    // Show only the preview markup in the editor
    echo '<div class="property-listing-preview">';
    echo '<h3>Property Listing Block Preview</h3>';
    for ($i = 1; $i <= 3; $i++) {
        echo '<div class="property-card">';
        echo '<h4>Sample Property ' . $i . '</h4>';
        echo '<p>Type: Apartment</p>';
        echo '</div>';
    }
    echo '</div>';
    return; // Stop further output!
}