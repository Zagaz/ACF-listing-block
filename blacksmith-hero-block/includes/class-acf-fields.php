<?php
/**
 * ACF Fields Registration
 */

class Blacksmith_Hero_ACF_Fields {
    
    public function __construct() {
        add_action('acf/init', array($this, 'register_fields'));
    }
    
    public function register_fields() {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array(
                'key' => 'group_blacksmith_hero_block',
                'title' => 'Hero Block Settings',
                'fields' => array(
                    // Background Type
                    array(
                        'key' => 'field_hero_bg_type',
                        'label' => 'Background Type',
                        'name' => 'bg_type',
                        'type' => 'radio',
                        'instructions' => 'Choose the type of background for your hero section.',
                        'required' => 1,
                        'choices' => array(
                            'none' => 'Solid Color',
                            'image' => 'Background Image',
                            'video' => 'YouTube Video'
                        ),
                        'default_value' => 'none',
                        'layout' => 'horizontal'
                    ),
                    
                    // Background Color
                    array(
                        'key' => 'field_hero_bg_color',
                        'label' => 'Background Color',
                        'name' => 'bg_color',
                        'type' => 'color_picker',
                        'instructions' => 'Select a background color.',
                        'default_value' => '#f5f5f5',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_hero_bg_type',
                                    'operator' => '==',
                                    'value' => 'none'
                                )
                            )
                        )
                    ),
                    
                    // Background Image
                    array(
                        'key' => 'field_hero_bg_image',
                        'label' => 'Background Image',
                        'name' => 'bg_image',
                        'type' => 'image',
                        'instructions' => 'Upload a background image for the hero section.',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_hero_bg_type',
                                    'operator' => '==',
                                    'value' => 'image'
                                )
                            )
                        )
                    ),
                    
                    // YouTube Video URL
                    array(
                        'key' => 'field_hero_bg_video',
                        'label' => 'YouTube Video URL',
                        'name' => 'bg_video',
                        'type' => 'url',
                        'instructions' => 'Enter a YouTube video URL',
                        'placeholder' => 'https://www.youtube.com/watch?v=...',
                        'default_value' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_hero_bg_type',
                                    'operator' => '==',
                                    'value' => 'video'
                                )
                            )
                        )
                    ),
                    
                    // Video Settings
                    array(
                        'key' => 'field_hero_video_autoplay',
                        'label' => 'Video Autoplay',
                        'name' => 'video_autoplay',
                        'type' => 'true_false',
                        'instructions' => 'Enable video autoplay.',
                        'default_value' => 1,
                        'ui' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_hero_bg_type',
                                    'operator' => '==',
                                    'value' => 'video'
                                )
                            )
                        )
                    ),
                    
                    array(
                        'key' => 'field_hero_video_mute',
                        'label' => 'Video Mute',
                        'name' => 'video_mute',
                        'type' => 'true_false',
                        'instructions' => 'Mute the video.',
                        'default_value' => 1,
                        'ui' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_hero_bg_type',
                                    'operator' => '==',
                                    'value' => 'video'
                                )
                            )
                        )
                    ),
                    
                    array(
                        'key' => 'field_hero_video_loop',
                        'label' => 'Video Loop',
                        'name' => 'video_loop',
                        'type' => 'true_false',
                        'instructions' => 'Loop the video continuously.',
                        'default_value' => 1,
                        'ui' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_hero_bg_type',
                                    'operator' => '==',
                                    'value' => 'video'
                                )
                            )
                        )
                    ),
                    
                    // Content Fields
                    array(
                        'key' => 'field_hero_header',
                        'label' => 'Hero Title',
                        'name' => 'hero_header',
                        'type' => 'text',
                        'instructions' => 'Enter the main title.',
                        'placeholder' => 'Welcome to Our Website',
                        'default_value' => 'Welcome to Our Amazing Website',
                        'maxlength' => 100
                    ),
                    
                    array(
                        'key' => 'field_hero_subheader',
                        'label' => 'Hero Subtitle',
                        'name' => 'hero_subheader',
                        'type' => 'textarea',
                        'instructions' => 'Enter a subtitle or description.',
                        'placeholder' => 'This is a compelling subtitle...',
                        'default_value' => 'Discover amazing content and experiences that will transform your journey.',
                        'rows' => 3,
                        'maxlength' => 300
                    ),
                    
                    // CTA Fields
                    array(
                        'key' => 'field_hero_cta_text',
                        'label' => 'CTA Button Text',
                        'name' => 'cta_text',
                        'type' => 'text',
                        'instructions' => 'Enter text for the call-to-action button.',
                        'placeholder' => 'Get Started',
                        'default_value' => 'Click Here',
                        'maxlength' => 30
                    ),
                    
                    array(
                        'key' => 'field_hero_cta_link',
                        'label' => 'CTA Button Link',
                        'name' => 'cta_link',
                        'type' => 'link',
                        'instructions' => 'Set the link for the CTA button.',
                        'return_format' => 'array'
                    ),
                    
                    array(
                        'key' => 'field_hero_cta_bg_color',
                        'label' => 'CTA Background Color',
                        'name' => 'cta_bg_color',
                        'type' => 'color_picker',
                        'instructions' => 'Choose the background color for the CTA button.',
                        'default_value' => '#4285f4'
                    ),
                    
                    array(
                        'key' => 'field_hero_cta_text_color',
                        'label' => 'CTA Text Color',
                        'name' => 'cta_text_color',
                        'type' => 'color_picker',
                        'instructions' => 'Choose the text color for the CTA button.',
                        'default_value' => '#ffffff'
                    )
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/blacksmith-hero'
                        )
                    )
                )
            ));
        }
    }
}