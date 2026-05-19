<?php
if (!defined('ABSPATH')) { exit; }

add_action('after_setup_theme', function() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('responsive-embeds');

    register_nav_menus([
        'primary'         => 'Primary Navigation',
        'footer_services' => 'Footer — Services',
        'footer_company'  => 'Footer — Company',
    ]);

    add_image_size('hero', 1920, 1080, true);
    add_image_size('card', 800, 600, true);
    add_image_size('thumb-sq', 600, 600, true);
    add_image_size('founder', 900, 1100, true);
});
