<?php
/**
 * Blacksmith Hero Block Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check if we're in the block editor
$is_preview = isset($is_preview) ? $is_preview : false;

// Helper function for YouTube video ID extraction
if (!function_exists('blacksmith_get_youtube_video_id')) {
    function blacksmith_get_youtube_video_id($url) {
        if (empty($url)) return null;
        
        $parts = parse_url($url);
        if (!$parts || !isset($parts['host'])) return null;
        
        if ($parts['host'] === 'youtu.be') {
            return trim($parts['path'], '/');
        }
        
        if (strpos($parts['host'], 'youtube.com') !== false && isset($parts['query'])) {
            parse_str($parts['query'], $query_vars);
            return $query_vars['v'] ?? null;
        }
        
        return null;
    }
}

// Get ACF fields
$hero_bg_type = get_field('bg_type') ?: 'none';
$hero_title = get_field('hero_header');
$hero_subtitle = get_field('hero_subheader');
$hero_bg_image_id = get_field('bg_image');
$hero_bg_color = get_field('bg_color');
$hero_bg_video = get_field('bg_video');
$hero_bg_video_is_auto_play = get_field('video_autoplay');
$hero_bg_video_is_mute = get_field('video_mute');
$hero_bg_video_is_loop = get_field('video_loop');

// CTA Fields
$cta_link = get_field('cta_link');
$cta_text = get_field('cta_text');
$cta_bg_color = get_field('cta_bg_color');
$cta_text_color = get_field('cta_text_color');

// Handle CTA link
$cta_url = '';
$cta_target = '';
if ($cta_link) {
    if (is_array($cta_link)) {
        $cta_url = $cta_link['url'] ?? '';
        $cta_target = $cta_link['target'] ?? '';
    } else {
        $cta_url = $cta_link;
    }
}

// Generate background styles
$wrapper_classes = ['blacksmith-hero-wrapper'];
$wrapper_styles = [];

switch ($hero_bg_type) {
    case 'none':
        if ($hero_bg_color) {
            $wrapper_styles[] = "background-color: " . esc_attr($hero_bg_color);
        } else {
            $wrapper_styles[] = "background-color: #f5f5f5"; // Defauklt background color
        }
        $wrapper_classes[] = 'hero-bg-none';
        break;
        
    case 'image':
        if ($hero_bg_image_id) {
            $image_url = wp_get_attachment_image_url($hero_bg_image_id, 'full');
            if ($image_url) {
                $wrapper_styles[] = "background-image: url('" . esc_url($image_url) . "')";
                $wrapper_styles[] = "background-size: cover";
                $wrapper_styles[] = "background-position: center";
                $wrapper_styles[] = "background-repeat: no-repeat";
            }
        }
        $wrapper_classes[] = 'hero-bg-image';
        break;
        
    case 'video':
        $wrapper_classes[] = 'hero-bg-video';
        // Add fallback background color for video blocks
        if ($hero_bg_color) {
            $wrapper_styles[] = "background-color: " . esc_attr($hero_bg_color);
        } else {
            $wrapper_styles[] = "background-color: #333333";
        }
        break;
}

// Get YouTube video ID
$youtube_video_id = null;
if ($hero_bg_type === 'video') {
    // Use provided video or default fallback
    $video_url = $hero_bg_video ?: 'https://www.youtube.com/watch?v=ScMzIvxBSi4';
    $youtube_video_id = blacksmith_get_youtube_video_id($video_url);
    
    // Debug: Log video ID for troubleshooting (remove in production)
    if (defined('WP_DEBUG') && WP_DEBUG && $youtube_video_id) {
        error_log('Blacksmith Hero Block - YouTube Video ID: ' . $youtube_video_id);
    }
}

// Prepare attributes
$wrapper_class_attr = implode(' ', $wrapper_classes);
$wrapper_style_attr = !empty($wrapper_styles) ? ' style="' . implode('; ', $wrapper_styles) . '"' : '';
$unique_id = 'blacksmith-hero-' . uniqid();
?>

<div id="<?php echo esc_attr($unique_id); ?>" class="<?php echo esc_attr($wrapper_class_attr); ?>"<?php echo $wrapper_style_attr; ?>>

    <?php if ($hero_bg_type === 'video' && $youtube_video_id) : ?>
        <?php if ($is_preview) : ?>
            <!-- Backend Preview: Gradient background -->
            <div class="blacksmith-hero__video-preview"></div>
        <?php else : ?>
            <!-- Frontend: YouTube Video Background -->
            <div class="blacksmith-hero__video-background">
                <iframe 
                    src="https://www.youtube.com/embed/<?php echo esc_attr($youtube_video_id); ?>?autoplay=<?php echo $hero_bg_video_is_auto_play ? '1' : '0'; ?>&mute=<?php echo $hero_bg_video_is_mute ? '1' : '0'; ?>&loop=<?php echo $hero_bg_video_is_loop ? '1' : '0'; ?>&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&enablejsapi=1&version=3&playlist=<?php echo esc_attr($youtube_video_id); ?>&start=0&end=0&disablekb=1&fs=0&iv_load_policy=3&cc_load_policy=0"
                    frameborder="0" 
                    allow="autoplay; encrypted-media; fullscreen" 
                    allowfullscreen
                    loading="lazy">
                </iframe>
            </div>
            <div class="blacksmith-hero__video-overlay"></div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="blacksmith-hero__content">
        <div class="blacksmith-hero__container">

            <?php if ($hero_title) : ?>
                <div class="blacksmith-hero__header">
                    <h1 class="blacksmith-hero__title">
                        <?php echo esc_html($hero_title); ?>
                    </h1>
                </div>
            <?php endif; ?>

            <?php if ($hero_subtitle) : ?>
                <div class="blacksmith-hero__subheader">
                    <p class="blacksmith-hero__subtitle">
                        <?php echo esc_html($hero_subtitle); ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($cta_text) : ?>
                <div class="blacksmith-hero__cta">
                    <?php if ($cta_url) : ?>
                        <a href="<?php echo esc_url($cta_url); ?>"
                           class="blacksmith-hero__cta-link"
                           <?php if ($cta_target) : ?>target="<?php echo esc_attr($cta_target); ?>" rel="noopener noreferrer"<?php endif; ?>
                           style="background-color: <?php echo esc_attr($cta_bg_color ?: '#4285f4'); ?>; color: <?php echo esc_attr($cta_text_color ?: '#ffffff'); ?>;">
                            <?php echo esc_html($cta_text); ?>
                        </a>
                    <?php else : ?>
                        <span class="blacksmith-hero__cta-link"
                              style="background-color: <?php echo esc_attr($cta_bg_color ?: '#cccccc'); ?>; color: <?php echo esc_attr($cta_text_color ?: '#666666'); ?>;">
                            <?php echo esc_html($cta_text); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>