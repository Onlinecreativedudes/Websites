<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Content seeding. Defines pcp_seed_content() but does not run it on load.
 * Run once at deploy with: wp eval-file deploy/seed.php
 *
 * Idempotent: pages/posts are matched by slug and skipped if they already exist.
 * Creates the page tree, assigns templates, sets the static front page and blog
 * page, seeds the Service and Commercial entries shown in the design, a sample
 * post, and a primary nav menu. ACF content falls back to the design copy baked
 * into the templates, so the site renders immediately.
 */
function pcp_seed_content() {
    $report = [];

    // -- Pages: slug => [title, template] --
    $pages = [
        'home'           => ['Home', ''],
        'why-us'         => ['Why Us', 'page-templates/why-us.php'],
        'gallery'        => ['Gallery', 'page-templates/gallery.php'],
        'contact'        => ['Contact', 'page-templates/contact.php'],
        'blog'           => ['Blog', ''],
        'privacy-policy' => ['Privacy Policy', ''],
        'terms-of-use'   => ['Terms of Use', ''],
    ];
    $ids = [];
    foreach ($pages as $slug => $cfg) {
        $existing = get_page_by_path($slug);
        if ($existing) {
            $ids[$slug] = $existing->ID;
            $report[] = "page exists: $slug (#{$existing->ID})";
            continue;
        }
        $id = wp_insert_post([
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => $cfg[0],
            'post_name'    => $slug,
            'post_content' => '',
        ]);
        if ($id && !is_wp_error($id)) {
            if ($cfg[1]) { update_post_meta($id, '_wp_page_template', $cfg[1]); }
            $ids[$slug] = $id;
            $report[] = "page created: $slug (#$id)";
        }
    }

    // -- Static front page + posts page --
    if (!empty($ids['home'])) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $ids['home']);
        $report[] = 'front page set to Home';
    }
    if (!empty($ids['blog'])) {
        update_option('page_for_posts', $ids['blog']);
        $report[] = 'posts page set to Blog';
    }

    // -- Service CPT entries (the six from the homepage) --
    $services = [
        ['Driveway Cleaning', 'Perth driveways cop a beating from dust, oil, moss and tyre marks. We restore concrete, limestone, exposed aggregate and brick paving.'],
        ['Roof Cleaning & Restoration', 'Lichen, algae and built-up debris age your roof faster and trap heat. A thorough clean and treatment restores and protects it.'],
        ['Patio & Paver Cleaning', 'We clean and restore paved surfaces, including delicate limestone, without causing damage, and can seal them to keep them cleaner for longer.'],
        ['House Washing', 'A professional exterior house wash removes mould, grime and oxidisation from rendered, brick and cladded surfaces and restores street appeal.'],
        ['Commercial Pressure Cleaning', 'Shopping centres, hospitals, schools, factory floors, car parks and cool rooms, scheduled to minimise disruption and meeting site compliance.'],
        ['Soft Wash & Specialised', 'Controlled low pressure for surfaces that cannot take high pressure. We also handle graffiti removal and vacuum recovery.'],
    ];
    if (post_type_exists('service')) {
        foreach ($services as $i => $s) {
            $slug = sanitize_title($s[0]);
            if (get_page_by_path($slug, OBJECT, 'service')) { continue; }
            $sid = wp_insert_post([
                'post_type'    => 'service',
                'post_status'  => 'publish',
                'post_title'   => $s[0],
                'post_name'    => $slug,
                'post_excerpt' => $s[1],
                'menu_order'   => $i,
            ]);
            if ($sid && !is_wp_error($sid)) { $report[] = "service created: {$s[0]} (#$sid)"; }
        }
    }

    // -- Commercial CPT entries --
    $commercial = [
        ['Property & Strata', 'Common-area pressure cleaning for strata and property managers, scheduled around residents.'],
        ['Healthcare & Hospitals', 'Compliant exterior and hard-surface cleaning for healthcare facilities.'],
        ['Hotels & Complexes', 'Entranceways, car parks and facades kept presentable for guests.'],
        ['Schools', 'Playground, walkway and building wash-downs scheduled around term and hours.'],
        ['Shopping Centres', 'Car parks, walkways and food-court hard surfaces cleaned after hours.'],
        ['Factory Floor Cleaning', 'Industrial floor and hard-surface cleaning with vacuum recovery where required.'],
    ];
    if (post_type_exists('commercial')) {
        foreach ($commercial as $i => $c) {
            $slug = sanitize_title($c[0]);
            if (get_page_by_path($slug, OBJECT, 'commercial')) { continue; }
            $cid = wp_insert_post([
                'post_type'    => 'commercial',
                'post_status'  => 'publish',
                'post_title'   => $c[0],
                'post_name'    => $slug,
                'post_excerpt' => $c[1],
                'menu_order'   => $i,
            ]);
            if ($cid && !is_wp_error($cid)) { $report[] = "commercial created: {$c[0]} (#$cid)"; }
        }
    }

    // -- Sample blog post --
    if (!get_page_by_path('how-often-should-you-clean-your-perth-roof', OBJECT, 'post')) {
        $pid = wp_insert_post([
            'post_type'    => 'post',
            'post_status'  => 'publish',
            'post_title'   => 'How Often Should You Clean Your Perth Roof?',
            'post_name'    => 'how-often-should-you-clean-your-perth-roof',
            'post_content' => 'Most Perth roofs benefit from a clean every one to two years, depending on tree cover, shade and how quickly lichen and moss take hold. This guide covers the signs to watch for and the right method for tile, Colorbond and terracotta.',
        ]);
        if ($pid && !is_wp_error($pid)) { $report[] = "sample post created (#$pid)"; }
    }

    // -- Primary nav menu --
    $menu_name = 'Primary';
    $menu = wp_get_nav_menu_object($menu_name);
    if (!$menu) {
        $menu_id = wp_create_nav_menu($menu_name);
        if (!is_wp_error($menu_id)) {
            $add = function ($title, $url) use ($menu_id) {
                wp_update_nav_menu_item($menu_id, 0, [
                    'menu-item-title'  => $title,
                    'menu-item-url'    => $url,
                    'menu-item-status' => 'publish',
                    'menu-item-type'   => 'custom',
                ]);
            };
            $add('Home', home_url('/'));
            $add('About Us', home_url('/why-us/'));
            $add('Pressure Cleaning', get_post_type_archive_link('service') ?: home_url('/services/'));
            $add('Commercial', get_post_type_archive_link('commercial') ?: home_url('/commercial/'));
            $add('Portfolio', home_url('/gallery/'));
            $add('Contact', home_url('/contact/'));
            $locations = get_theme_mod('nav_menu_locations', []);
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
            $report[] = "primary menu created (#$menu_id) and assigned";
        }
    } else {
        $report[] = 'primary menu already exists';
    }

    // Flush rewrite rules so CPT permalinks resolve.
    flush_rewrite_rules(false);
    $report[] = 'rewrite rules flushed';

    return $report;
}
