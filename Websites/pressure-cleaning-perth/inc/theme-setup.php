<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Theme supports, menus, image sizes.
 */
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets']);
    add_theme_support('responsive-embeds');
    add_theme_support('automatic-feed-links');

    register_nav_menus([
        'primary'         => 'Primary Navigation',
        'footer_services' => 'Footer — Pressure Cleaning',
        'footer_commercial' => 'Footer — Commercial & Sealing',
        'footer_company'  => 'Footer — Company',
    ]);

    // Image sizes used across the design (all hard-cropped to match the layouts).
    add_image_size('pcp-hero', 1920, 1080, true);   // hero / cta-band backgrounds
    add_image_size('pcp-card', 800, 600, true);      // 4:3 service / portfolio tiles
    add_image_size('pcp-wide', 1200, 800, true);     // sealing / feature blocks
    add_image_size('pcp-portrait', 900, 1100, true); // owner / about portrait
});

/**
 * Front-end clean-up. Removes default output the design does not use.
 * Never touches admin or core files; fully reversible.
 */
add_action('init', function () {
    // Emoji.
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // oEmbed discovery.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');

    // Misc head meta the design and Yoast do not need.
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'rest_output_link_wp_head');
});

/**
 * Drop the block-library and classic-theme front-end CSS (no Gutenberg on the front end)
 * and jQuery Migrate. Delivery optimisation stays WP Rocket's job; this only removes
 * output the theme genuinely never uses.
 */
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');
}, 100);

add_action('wp_default_scripts', function ($scripts) {
    if (is_admin()) { return; }
    if (!empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            ['jquery-migrate']
        );
    }
});

/**
 * Pretty excerpt length / more for the blog cards.
 */
add_filter('excerpt_length', function () { return 26; });
add_filter('excerpt_more', function () { return '&hellip;'; });
