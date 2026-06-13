<?php
if (!defined('ABSPATH')) { exit; }

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'ocd-main',
        OCD_THEME_URI . '/assets/css/main.css',
        [],
        OCD_THEME_VERSION
    );

    // Footer script. Deferring and delay are left to WP Rocket.
    wp_enqueue_script(
        'ocd-main',
        OCD_THEME_URI . '/assets/js/main.js',
        [],
        OCD_THEME_VERSION,
        ['in_footer' => true]
    );
});

/**
 * Preload the two critical (latin) self-hosted fonts so the display face is
 * ready for the hero. font-display: swap is set on every @font-face in main.css.
 */
add_action('wp_head', function() {
    $fonts = [
        '/assets/fonts/jost-latin.woff2',
        '/assets/fonts/hanken-grotesk-latin.woff2',
    ];
    foreach ($fonts as $font) {
        printf(
            '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
            esc_url(OCD_THEME_URI . $font)
        );
    }
}, 1);
