<?php

/**
 * Property Card Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$property_id = get_the_ID();
$price = get_post_meta($property_id, '_property_price', true);
$location = get_post_meta($property_id, '_property_location', true);
?>

<div class="property-card">
        <div class="property-image" >
        <?php //Image goes here ?>
        </div>
    <div class="property-content">
        <div class="property-title" >
            <?php the_title(); ?>
            
        </div>
        <div class="property-excerpt" >
            <?php echo wp_trim_words(get_the_excerpt(), 12, '...'); ?>
        </div>
        <div class="property-price">
            <?php if ($price): ?>
                $<?php echo esc_html(number_format(floatval($price))); ?>/mo
            <?php endif; ?>
        </div>
        <div class="property-location" >
            <?php if ($location): ?>
                <?php echo esc_html($location); ?>
            <?php endif; ?>
        </div>
    </div>
</div>