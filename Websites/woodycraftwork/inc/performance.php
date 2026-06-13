<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Dequeue unused default front-end output at the theme level.
 *
 * This removes nothing from the install and is fully reversible. It never
 * touches core files or admin functionality, and leaves the loopback / cron,
 * REST API, and oEmbed *endpoints* in place. Only the unused front-end asset
 * output is dropped (emoji script, block-library CSS for a non-Gutenberg
 * front end, oEmbed discovery and host JS, jQuery Migrate, front-end
 * dashicons). The reviewer's WP Rocket install handles delivery optimisation.
 */
add_action('init', function() {
    // Emoji detection script + styles (front end and admin print where harmless).
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // oEmbed discovery links and host JS (the endpoint itself stays).
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
});

/**
 * Block editor (Gutenberg) front-end CSS — the theme front end uses no blocks.
 */
add_action('wp_enqueue_scripts', function() {
    if (is_admin()) { return; }
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');
}, 100);

/**
 * Drop jQuery Migrate from the bundled jQuery (front end only).
 */
add_action('wp_default_scripts', function($scripts) {
    if (is_admin()) { return; }
    if (!empty($scripts->registered['jquery'])) {
        $deps = $scripts->registered['jquery']->deps;
        $scripts->registered['jquery']->deps = array_diff($deps, ['jquery-migrate']);
    }
});
