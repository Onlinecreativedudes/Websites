<?php
if (!defined('ABSPATH')) { exit; }

add_action('after_setup_theme', function() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('responsive-embeds');

    register_nav_menus([
        'primary'         => 'Primary Navigation',
        'footer_quick'    => 'Footer — Quick Links',
        'footer_services' => 'Footer — Services',
    ]);

    // Image sizes used by the templates.
    add_image_size('hero', 2304, 1536, true);   // full-bleed hero / parallax bands
    add_image_size('card', 900, 700, true);      // service cards, value media
    add_image_size('portrait', 900, 1125, true); // about figure (4:5)
    add_image_size('square', 800, 800, true);    // gallery cells
});
