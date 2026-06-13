<?php
if (!defined('ABSPATH')) { exit; }

add_action('after_setup_theme', function() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('responsive-embeds');

    register_nav_menus([
        'primary'         => 'Primary Navigation',
        'footer-quick'    => 'Footer — Quick Links',
        'footer-services' => 'Footer — Services',
    ]);

    add_image_size('hero', 1920, 1080, true);
    add_image_size('card', 600, 400, true);
    add_image_size('portrait', 640, 800, true);
    add_image_size('split', 1000, 800, true);
});
