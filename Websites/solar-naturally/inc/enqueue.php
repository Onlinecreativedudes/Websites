<?php
if (!defined('ABSPATH')) { exit; }

add_action('wp_enqueue_scripts', function() {
    // Version assets by file modification time so every deploy busts browser
    // and server (LiteSpeed) caches automatically — a fixed version string
    // leaves stale CSS/JS being served after edits.
    $css_path = SN_THEME_PATH . '/assets/css/main.css';
    $js_path  = SN_THEME_PATH . '/assets/js/main.js';
    $css_ver  = file_exists($css_path) ? (string) filemtime($css_path) : SN_THEME_VERSION;
    $js_ver   = file_exists($js_path)  ? (string) filemtime($js_path)  : SN_THEME_VERSION;

    wp_enqueue_style(
        'sn-main',
        SN_THEME_URI . '/assets/css/main.css',
        [],
        $css_ver
    );

    wp_enqueue_script(
        'sn-main',
        SN_THEME_URI . '/assets/js/main.js',
        [],
        $js_ver,
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
