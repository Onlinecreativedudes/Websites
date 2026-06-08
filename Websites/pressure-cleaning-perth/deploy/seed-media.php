<?php
/**
 * Media + ACF image seed. Run once after the theme is active, ACF JSON is synced,
 * and the page/service/commercial content has been seeded:
 *
 *   wp eval-file wp-content/themes/pressure-cleaning-perth/deploy/seed-media.php
 *
 * Imports the bundled seed images into the Media Library and fills the matching
 * ACF image fields ONLY where they are empty. Idempotent and non-destructive:
 * it never overwrites real photos the client has uploaded, and re-running reuses
 * the already-imported attachments.
 */

if (!defined('ABSPATH')) {
    fwrite(STDERR, "Run via: wp eval-file deploy/seed-media.php\n");
    exit(1);
}

if (!function_exists('pcp_seed_media')) {
    if (class_exists('WP_CLI')) { WP_CLI::error('pcp_seed_media() not found. Activate the Pressure Cleaning Perth theme first.'); }
    exit(1);
}

$report = pcp_seed_media();

if (class_exists('WP_CLI')) {
    foreach ($report as $line) { WP_CLI::log('  ' . $line); }
    WP_CLI::success('Media seeded. Bundled images are now in the Media Library and wired to empty ACF fields.');
} else {
    echo implode("\n", $report) . "\nMedia seed complete.\n";
}
