<?php
if (!defined('ABSPATH')) { exit; }

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'ocd-fonts',
        'https://fonts.googleapis.com/css2?family=Archivo:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'ocd-main',
        OCD_THEME_URI . '/assets/css/main.css',
        ['ocd-fonts'],
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

add_action('wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1);
