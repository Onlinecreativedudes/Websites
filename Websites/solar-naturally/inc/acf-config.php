<?php
if (!defined('ABSPATH')) { exit; }

add_filter('acf/settings/save_json', function() {
    return SN_THEME_PATH . '/acf-json';
});

add_filter('acf/settings/load_json', function($paths) {
    unset($paths[0]);
    $paths[] = SN_THEME_PATH . '/acf-json';
    return $paths;
});
