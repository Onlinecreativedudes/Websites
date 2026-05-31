<?php
if (!defined('ABSPATH')) { exit; }

add_action('wp_enqueue_scripts', function () {
    // Fonts. Matches the design source (Archivo, Hanken Grotesk, Caveat).
    // NOTE: self-hosting is deferred to deploy — see deploy/README.md.
    wp_enqueue_style(
        'pcp-fonts',
        'https://fonts.googleapis.com/css2?family=Archivo:wght@500;600;700;800;900&family=Hanken+Grotesk:wght@400;500;600;700&family=Caveat:wght@600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'pcp-main',
        PCP_THEME_URI . '/assets/css/main.css',
        ['pcp-fonts'],
        PCP_THEME_VERSION
    );

    wp_enqueue_script(
        'pcp-main',
        PCP_THEME_URI . '/assets/js/main.js',
        [],
        PCP_THEME_VERSION,
        ['in_footer' => true]
    );
}, 20);

// Preconnect to the font hosts early.
add_action('wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1);
