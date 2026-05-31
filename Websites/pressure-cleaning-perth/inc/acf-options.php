<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Site Options screen. Registering an options page through ACF is permitted
 * (the no-PHP rule applies to field groups only — those stay in acf-json).
 */
add_action('acf/init', function () {
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
