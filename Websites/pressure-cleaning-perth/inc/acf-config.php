<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Save and load ACF JSON inside the theme. Field groups, the post types, and the
 * taxonomy all live in /acf-json and are synced into the database at deploy via
 * `wp acf json sync`. No field groups are registered in PHP.
 */
add_filter('acf/settings/save_json', function () {
    return PCP_THEME_PATH . '/acf-json';
});

add_filter('acf/settings/load_json', function ($paths) {
    unset($paths[0]);
    $paths[] = PCP_THEME_PATH . '/acf-json';
    return $paths;
});
