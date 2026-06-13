<?php
if (!defined('ABSPATH')) { exit; }

add_action('after_setup_theme', function() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('responsive-embeds');

    add_image_size('sn-hero', 1920, 1280, true);
    add_image_size('sn-card', 900, 680, true);
    add_image_size('sn-gallery', 1200, 900, true);
});

// Trim default front-end output the theme never uses. Core and admin untouched.
add_action('init', function() {
    if (is_admin()) { return; }
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
});

add_action('wp_enqueue_scripts', function() {
    // Block-library CSS: Gutenberg is not used on the front end of this theme.
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');
    if (!is_admin_bar_showing()) {
        wp_deregister_style('dashicons');
    }
}, 20);

// jQuery Migrate is not needed by this theme's scripts.
add_action('wp_default_scripts', function($scripts) {
    if (is_admin() || empty($scripts->registered['jquery'])) { return; }
    $deps = &$scripts->registered['jquery']->deps;
    $deps = array_diff($deps, ['jquery-migrate']);
});
