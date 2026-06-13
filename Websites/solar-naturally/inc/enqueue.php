<?php
if (!defined('ABSPATH')) { exit; }

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'sn-main',
        SN_THEME_URI . '/assets/css/main.css',
        [],
        SN_THEME_VERSION
    );

    wp_enqueue_script(
        'sn-main',
        SN_THEME_URI . '/assets/js/main.js',
        [],
        SN_THEME_VERSION,
        ['in_footer' => true]
    );
});

// Fonts are self-hosted; preload the critical heading font (latin subset).
add_action('wp_head', function() {
    printf(
        '<link rel="preload" href="%s/assets/fonts/archivo-400-latin.woff2" as="font" type="font/woff2" crossorigin>' . "\n",
        esc_url(SN_THEME_URI)
    );
    printf(
        '<link rel="preload" href="%s/assets/fonts/hanken-grotesk-400-latin.woff2" as="font" type="font/woff2" crossorigin>' . "\n",
        esc_url(SN_THEME_URI)
    );
}, 1);
