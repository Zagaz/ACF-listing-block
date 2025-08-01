<?php
/**
 * Hero Block Main Class
 */

class Blacksmith_Hero_Block {
    
    public function __construct() {
        add_action('init', array($this, 'register_block'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_editor_assets'));
        add_action('admin_notices', array($this, 'check_dependencies'));
    }
    
    public function register_block() {
        // Check if ACF is active
        if (!function_exists('acf_register_block_type')) {
            return;
        }
        
        acf_register_block_type(array(
            'name'              => 'blacksmith-hero',
            'title'             => __('Blacksmith Hero Block', 'blacksmith-hero'),
            'description'       => __('A customizable hero block with video background support.', 'blacksmith-hero'),
            'render_template'   => BLACKSMITH_HERO_PLUGIN_PATH . 'templates/hero-block.php',
            'category'          => 'design',
            'icon'              => 'format-image',
            'keywords'          => array('hero', 'banner', 'video', 'background'),
            'supports'          => array(
                'align' => array('full', 'wide'),
                'anchor' => true,
                'customClassName' => true,
            ),
            'example'           => array(
                'attributes' => array(
                    'mode' => 'preview',
                    'data' => array(
                        'hero_header' => 'Welcome to Our Website',
                        'hero_subheader' => 'Beautiful hero section with video backgrounds',
                        'bg_type' => 'video',
                        'cta_text' => 'Get Started'
                    )
                )
            )
        ));
    }
    
    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'blacksmith-hero-block',
            BLACKSMITH_HERO_PLUGIN_URL . 'assets/css/main.css',
            array(),
            BLACKSMITH_HERO_VERSION
        );
        
        wp_enqueue_script(
            'blacksmith-hero-frontend',
            BLACKSMITH_HERO_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            BLACKSMITH_HERO_VERSION,
            true
        );
    }
    
    public function enqueue_editor_assets() {
        wp_enqueue_style(
            'blacksmith-hero-editor',
            BLACKSMITH_HERO_PLUGIN_URL . 'assets/css/main.css',
            array(),
            BLACKSMITH_HERO_VERSION
        );
        
        wp_enqueue_script(
            'blacksmith-hero-editor',
            BLACKSMITH_HERO_PLUGIN_URL . 'assets/js/block-editor.js',
            array('wp-blocks', 'wp-i18n', 'wp-element'),
            BLACKSMITH_HERO_VERSION,
            true
        );
    }
    
    public function check_dependencies() {
        if (!function_exists('acf_register_block_type')) {
            echo '<div class="notice notice-error"><p>';
            echo __('<strong>Blacksmith Hero Block</strong> requires <strong>Advanced Custom Fields PRO</strong> to be installed and activated.', 'blacksmith-hero');
            echo '</p></div>';
        }
    }
}