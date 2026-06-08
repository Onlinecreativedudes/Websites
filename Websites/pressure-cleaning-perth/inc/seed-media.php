<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Media + ACF image seeding (OCD standard).
 *
 * Imports the theme's bundled seed images into the WordPress Media Library and
 * populates the matching ACF image fields — but ONLY where a field is currently
 * empty. This means:
 *   - the client sees real, editable images in the backend (not hard-coded files);
 *   - the images appear in the Media Library so they're manageable;
 *   - re-running never clobbers real photos the client has uploaded.
 *
 * Run once at deploy (after the theme is active and ACF JSON is synced):
 *   wp eval-file wp-content/themes/pressure-cleaning-perth/deploy/seed-media.php
 *
 * Defines functions only; does not run on load.
 */

/**
 * Import a theme-bundled image into the Media Library. Idempotent: a marker meta
 * (_pcp_seed_file) lets us reuse the existing attachment on re-runs.
 *
 * @param string $relpath Theme-relative path, eg assets/images/roof-tiles-clean.jpg
 * @param string $alt     Alt text.
 * @return int Attachment ID, or 0 on failure.
 */
function pcp_sideload_theme_image($relpath, $alt = '') {
    $relpath = ltrim($relpath, '/');
    $path = PCP_THEME_PATH . '/' . $relpath;
    if (!is_readable($path)) { return 0; }

    $marker = basename($relpath);

    // Reuse if already imported.
    $existing = get_posts([
        'post_type'   => 'attachment',
        'post_status' => 'inherit',
        'numberposts' => 1,
        'fields'      => 'ids',
        'meta_key'    => '_pcp_seed_file',
        'meta_value'  => $marker,
    ]);
    if ($existing) { return (int) $existing[0]; }

    require_once ABSPATH . 'wp-admin/includes/image.php';

    $upload = wp_upload_bits($marker, null, file_get_contents($path));
    if (!empty($upload['error'])) { return 0; }

    $filetype = wp_check_filetype($upload['file']);
    $attach_id = wp_insert_attachment([
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name(pathinfo($marker, PATHINFO_FILENAME)),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ], $upload['file']);
    if (is_wp_error($attach_id) || !$attach_id) { return 0; }

    $meta = wp_generate_attachment_metadata($attach_id, $upload['file']);
    wp_update_attachment_metadata($attach_id, $meta);
    if ($alt) { update_post_meta($attach_id, '_wp_attachment_image_alt', $alt); }
    update_post_meta($attach_id, '_pcp_seed_file', $marker);

    return (int) $attach_id;
}

/** Set an ACF field only when it is currently empty (never overwrites real content). */
function pcp_set_image_if_empty($field, $value, $post_id) {
    if (!function_exists('get_field') || $value === 0 || $value === [] || $value === null) { return false; }
    $cur = get_field($field, $post_id, false);
    if (!empty($cur)) { return false; }
    update_field($field, $value, $post_id);
    return true;
}

/** Pick the best-matching seed image filename for a service/industry title. */
function pcp_image_for_title($title) {
    $t = strtolower($title);
    $map = [
        'roof' => 'roof-tiles-clean.jpg',
        'driveway' => 'driveway-pavers.jpg',
        'house' => 'stone-tiles-clean.jpg',
        'building' => 'stone-tiles-clean.jpg',
        'wash' => 'spray-nozzle.jpg',
        'fascia' => 'sidewalk-clean.jpg',
        'eaves' => 'sidewalk-clean.jpg',
        'pool' => 'pool-pavers-vacuum.jpg',
        'patio' => 'pool-pavers-vacuum.jpg',
        'tile' => 'backyard-tiles.jpg',
        'tennis' => 'concrete-spray.jpg',
        'court' => 'concrete-spray.jpg',
        'fence' => 'spray-nozzle.jpg',
        'limestone' => 'stone-tiles-clean.jpg',
        'bore' => 'concrete-spray.jpg',
        'calcium' => 'spray-nozzle.jpg',
        'graffiti' => 'sidewalk-clean.jpg',
        'soft' => 'spray-nozzle.jpg',
        'factory' => 'warehouse-floor.jpg',
        'floor' => 'warehouse-floor.jpg',
        'strata' => 'commercial-repco.jpg',
        'commercial' => 'commercial-repco.jpg',
    ];
    foreach ($map as $kw => $file) {
        if (strpos($t, $kw) !== false) { return $file; }
    }
    return 'roof-washing-action.jpg';
}

/**
 * Import all bundled images and populate empty ACF image fields across the site.
 *
 * @return array Human-readable report lines.
 */
function pcp_seed_media() {
    $report = [];

    // 1. Import every bundled image into the Media Library.
    $images = glob(PCP_THEME_PATH . '/assets/images/*.jpg') ?: [];
    $brand  = glob(PCP_THEME_PATH . '/assets/brand/*.png') ?: [];
    $ids = []; // basename => attachment id
    foreach (array_merge($images, $brand) as $file) {
        $rel = str_replace(PCP_THEME_PATH . '/', '', $file);
        $alt = ucwords(str_replace('-', ' ', pathinfo($file, PATHINFO_FILENAME)));
        $id = pcp_sideload_theme_image($rel, $alt);
        if ($id) { $ids[basename($file)] = $id; }
    }
    $report[] = 'Media Library: ' . count($ids) . ' images present (imported or reused).';
    $img = function ($name) use ($ids) { return $ids[$name] ?? 0; };

    // 2. Site Options — logo.
    pcp_set_image_if_empty('site_logo', $img('logo-full.png'), 'option');

    // 3. Home page.
    $home = (int) get_option('page_on_front');
    if (!$home) { $p = get_page_by_path('home'); $home = $p ? $p->ID : 0; }
    if ($home) {
        pcp_set_image_if_empty('mobile_hero_image', $img('roof-washing-action.jpg'), $home);
        pcp_set_image_if_empty('sealing_image', $img('roof-tiles-clean.jpg'), $home);
        pcp_set_image_if_empty('local_image', $img('roof-clean-aerial.jpg'), $home);
        pcp_set_image_if_empty('about_image', $img('mascot.png'), $home);
        pcp_set_image_if_empty('gallery_images', array_values(array_filter([
            $img('driveway-pavers.jpg'), $img('roof-tiles-clean.jpg'), $img('backyard-tiles.jpg'),
            $img('concrete-spray.jpg'), $img('sidewalk-clean.jpg'), $img('warehouse-floor.jpg'),
        ])), $home);

        // Services repeater — populate with the design's six cards so they are editable.
        if (function_exists('get_field') && empty(get_field('services', $home))) {
            $cards = [
                ['driveway-pavers.jpg', 'Driveway Cleaning', 'Perth driveways cop a beating from dust, oil, moss and tyre marks. We restore concrete, limestone, exposed aggregate and brick paving.', 'driveway'],
                ['roof-tiles-clean.jpg', 'Roof Cleaning & Restoration', 'Lichen, algae and built-up debris age your roof faster and trap heat. A thorough clean and treatment restores and protects it.', 'roof'],
                ['pool-pavers-vacuum.jpg', 'Patio & Paver Cleaning', 'We clean and restore paved surfaces, including delicate limestone, without causing damage, and can seal them to stay cleaner for longer.', 'patio'],
                ['stone-tiles-clean.jpg', 'House Washing', 'A professional exterior house wash removes mould, grime and oxidisation from rendered, brick and cladded surfaces.', 'house'],
                ['commercial-repco.jpg', 'Commercial Pressure Cleaning', 'Shopping centres, hospitals, schools, factory floors, car parks and cool rooms, scheduled to minimise disruption.', 'commercial'],
                ['spray-nozzle.jpg', 'Soft Wash & Specialised', 'Controlled low pressure for surfaces that cannot take high pressure. We also handle graffiti removal and vacuum recovery.', 'soft'],
            ];
            $rows = [];
            foreach ($cards as $c) {
                // Best-effort: link each card to a matching service page if one exists.
                $url = '';
                $match = get_posts(['post_type' => 'service', 'numberposts' => 1, 's' => $c[3], 'fields' => 'ids']);
                if ($match) { $url = get_permalink($match[0]); }
                $rows[] = [
                    'image' => $img($c[0]),
                    'title' => $c[1],
                    'body'  => $c[2],
                    'link'  => $url ? ['title' => $c[1], 'url' => $url, 'target' => ''] : '',
                ];
            }
            update_field('services', $rows, $home);
        }
        $report[] = 'Home page image fields populated (where empty).';
    }

    // 4. Service pages — hero image each (where empty).
    $services = get_posts(['post_type' => 'service', 'numberposts' => -1]);
    foreach ($services as $s) {
        pcp_set_image_if_empty('hero_image', $img(pcp_image_for_title($s->post_title)), $s->ID);
        if (!has_post_thumbnail($s->ID)) {
            $tid = $img(pcp_image_for_title($s->post_title));
            if ($tid) { set_post_thumbnail($s->ID, $tid); }
        }
    }
    if ($services) { $report[] = count($services) . ' service hero images populated (where empty).'; }

    // 5. Commercial pages — hero image each (where empty).
    $commercial = get_posts(['post_type' => 'commercial', 'numberposts' => -1]);
    foreach ($commercial as $c) {
        pcp_set_image_if_empty('hero_image', $img(pcp_image_for_title($c->post_title)), $c->ID);
        if (!has_post_thumbnail($c->ID)) {
            $tid = $img(pcp_image_for_title($c->post_title));
            if ($tid) { set_post_thumbnail($c->ID, $tid); }
        }
    }
    if ($commercial) { $report[] = count($commercial) . ' commercial hero images populated (where empty).'; }

    return $report;
}
