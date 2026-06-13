<?php
if (!defined('ABSPATH')) { exit; }

add_action('acf/init', function() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => 'Site Options',
            'menu_title' => 'Site Options',
            'menu_slug'  => 'site-options',
            'capability' => 'edit_posts',
            'redirect'   => false,
            'icon_url'   => 'dashicons-admin-generic',
            'position'   => 2,
        ]);
    }
});
