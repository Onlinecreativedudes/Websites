<?php
/**
 * Content seed. Run once after the theme is active and ACF JSON is synced:
 *
 *   wp eval-file wp-content/themes/pressure-cleaning-perth/deploy/seed.php
 *
 * Creates the page tree (Home, Why Us, Gallery, Contact, Blog, Privacy, Terms),
 * sets the static front page and blog page, assigns page templates, seeds the
 * Service and Commercial entries plus a sample post, and builds the primary menu.
 * Idempotent: existing pages/posts (matched by slug) are skipped.
 */

if (!defined('ABSPATH')) {
    fwrite(STDERR, "Run via: wp eval-file deploy/seed.php\n");
    exit(1);
}

if (!function_exists('pcp_seed_content')) {
    WP_CLI::error('pcp_seed_content() not found. Activate the Pressure Cleaning Perth theme first.');
}

$report = pcp_seed_content();

if (class_exists('WP_CLI')) {
    foreach ($report as $line) { WP_CLI::log('  ' . $line); }
    WP_CLI::success('Seed complete. Review Pages, Services, Commercial and Site Options in wp-admin.');
} else {
    echo implode("\n", $report) . "\nSeed complete.\n";
}
