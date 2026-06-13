<?php
if (!defined('ABSPATH')) { exit; }

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'ocd-main',
        OCD_THEME_URI . '/assets/css/main.css',
        [],
        OCD_THEME_VERSION
    );

    wp_enqueue_script(
        'ocd-main',
        OCD_THEME_URI . '/assets/js/main.js',
        [],
        OCD_THEME_VERSION,
        ['in_footer' => true, 'strategy' => 'defer']
    );
});

/**
 * Preload the critical heading font so the hero renders without a swap flash.
 */
add_action('wp_head', function() {
    printf(
        '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
        esc_url(OCD_THEME_URI . '/assets/fonts/schibsted-grotesk-latin.woff2')
    );
}, 5);
