<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Auto-sync ACF local JSON into the database on admin load, so deploys
 * over the cPanel API (no WP-CLI) still land schema changes without a
 * manual "Sync available" click. Mirrors what `wp acf json sync` does.
 */
add_action('admin_init', function () {
    if (!function_exists('acf_get_field_groups') || wp_doing_ajax()) { return; }

    foreach (acf_get_field_groups() as $group) {
        $local    = acf_maybe_get($group, 'local', false);
        $modified = acf_maybe_get($group, 'modified', 0);
        $private  = acf_maybe_get($group, 'private', false);

        if ($local !== 'json' || $private) { continue; }

        $needs_sync = empty($group['ID'])
            || ($modified && $modified > get_post_modified_time('U', true, $group['ID']));

        if ($needs_sync) {
            $group['fields'] = acf_get_fields($group);
            acf_import_field_group($group);
        }
    }
});
