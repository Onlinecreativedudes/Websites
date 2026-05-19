<?php
/**
 * Demo content seeder.
 *
 * Runs once on theme activation (or when manually triggered from
 * Tools → Seed Dent Content). It:
 *   1. Imports placeholder images from /assets/seed/ into the Media Library
 *   2. Creates / updates the Thank You and Home pages with the correct templates
 *   3. Writes every ACF field on Home + Site Options with the launch copy
 *   4. Sets Home as the static front page
 *
 * Tracks completion via an option flag so it never runs twice unless requested.
 */
if (!defined('ABSPATH')) { exit; }

define('OCD_SEED_OPTION', 'ocd_dent_seed_done');
define('OCD_SEED_DIR', OCD_THEME_PATH . '/assets/seed');

/**
 * Run automatically once after the theme is switched on.
 */
add_action('after_switch_theme', function() {
    if (!function_exists('acf_get_field_groups')) {
        // ACF not active yet — install agent will need to activate plugins, then
        // manually re-run via Tools menu.
        return;
    }
    if (get_option(OCD_SEED_OPTION)) { return; }
    ocd_seed_run();
});

/**
 * Tools menu entry — manual re-run / force re-seed.
 */
add_action('admin_menu', function() {
    add_management_page(
        'Seed Dent Content',
        'Seed Dent Content',
        'manage_options',
        'ocd-seed',
        'ocd_seed_admin_page'
    );
});

function ocd_seed_admin_page() {
    if (!current_user_can('manage_options')) { return; }

    if (isset($_POST['ocd_seed_run']) && check_admin_referer('ocd_seed_run')) {
        delete_option(OCD_SEED_OPTION);
        $result = ocd_seed_run();
        echo '<div class="notice notice-success"><p><strong>Seed complete.</strong> ' . esc_html($result) . '</p></div>';
    }

    $done = get_option(OCD_SEED_OPTION);
    ?>
    <div class="wrap">
        <h1>Seed Dent Content</h1>
        <p>This will populate the Site Options, Home page (Landing Page template), and Thank You page with the launch copy from the original landing page design. Placeholder images are imported into the Media Library.</p>
        <p>Status: <strong><?php echo $done ? 'Seeded on ' . esc_html($done) : 'Not yet seeded'; ?></strong></p>
        <form method="post">
            <?php wp_nonce_field('ocd_seed_run'); ?>
            <p>
                <button type="submit" name="ocd_seed_run" class="button button-primary">
                    <?php echo $done ? 'Re-run seed (overwrites Home & Site Options content)' : 'Seed content now'; ?>
                </button>
            </p>
        </form>
        <h2>What gets created</h2>
        <ul style="list-style: disc; padding-left: 24px;">
            <li>Thank You page (slug <code>/thank-you/</code>, template Thank You)</li>
            <li>Home page (template Landing Page) with all sections populated</li>
            <li>Home set as the static front page</li>
            <li>Placeholder images imported to the Media Library and attached to all image fields</li>
            <li>Site Options: phone, email, branding, schema details, promo pills</li>
        </ul>
    </div>
    <?php
}

/**
 * Main entry point.
 */
function ocd_seed_run() {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $images = ocd_seed_import_images();
    $ty_id  = ocd_seed_create_thankyou($images);
    $home_id = ocd_seed_create_home($images, $ty_id);
    ocd_seed_site_options($images);
    ocd_seed_set_front_page($home_id);

    update_option(OCD_SEED_OPTION, current_time('mysql'));
    return sprintf('Home page #%d, Thank You page #%d, %d images imported.', $home_id, $ty_id, count($images));
}

/**
 * Import every file in /assets/seed/ into the Media Library, keyed by filename.
 * Skips re-importing if a previous import set the option mapping.
 *
 * @return array<string,int> Filename (no extension) => attachment ID.
 */
function ocd_seed_import_images() {
    $map = get_option('ocd_dent_seed_images', []);
    if (!is_array($map)) { $map = []; }

    if (!is_dir(OCD_SEED_DIR)) { return $map; }

    foreach (glob(OCD_SEED_DIR . '/*.{jpg,jpeg,png}', GLOB_BRACE) as $path) {
        $key = pathinfo($path, PATHINFO_FILENAME);
        if (isset($map[$key]) && get_post($map[$key])) { continue; }

        $upload = wp_upload_bits(basename($path), null, file_get_contents($path));
        if (!empty($upload['error'])) { continue; }

        $filetype = wp_check_filetype(basename($upload['file']), null);
        $attachment = [
            'post_mime_type' => $filetype['type'],
            'post_title'     => sanitize_text_field(pathinfo($upload['file'], PATHINFO_FILENAME)),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ];
        $attach_id = wp_insert_attachment($attachment, $upload['file']);
        if (!is_wp_error($attach_id)) {
            $meta = wp_generate_attachment_metadata($attach_id, $upload['file']);
            wp_update_attachment_metadata($attach_id, $meta);
            $map[$key] = (int) $attach_id;
        }
    }

    update_option('ocd_dent_seed_images', $map);
    return $map;
}

/**
 * Helper: lookup a seeded attachment ID by base filename.
 */
function ocd_seed_img($images, $key) {
    return isset($images[$key]) ? (int) $images[$key] : 0;
}

/**
 * Create / update the Thank You page.
 */
function ocd_seed_create_thankyou($images) {
    $page = get_page_by_path('thank-you');
    $args = [
        'post_title'   => 'Thank You',
        'post_name'    => 'thank-you',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '',
    ];
    if ($page) {
        $args['ID'] = $page->ID;
        wp_update_post($args);
        $id = $page->ID;
    } else {
        $id = wp_insert_post($args);
    }
    update_post_meta($id, '_wp_page_template', 'page-templates/thank-you.php');

    update_field('ty_headline', 'Thanks, we got your message.', $id);
    update_field('ty_body', '<p>One of the Dent team will be in touch shortly — usually within the hour during business hours. In the meantime, save our number in your phone so you have it next time the breaker trips.</p>', $id);
    update_field('ty_cta', [
        'title'  => 'Back to home',
        'url'    => home_url('/'),
        'target' => '',
    ], $id);

    update_field('meta_description', "Thanks for getting in touch with Dent Electrical & Air. We'll be in touch shortly during business hours.", $id);

    return $id;
}

/**
 * Create / update the Home page with all the launch copy.
 */
function ocd_seed_create_home($images, $ty_id) {
    $page = get_page_by_path('home');
    $args = [
        'post_title'   => 'Home',
        'post_name'    => 'home',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '',
    ];
    if ($page) {
        $args['ID'] = $page->ID;
        wp_update_post($args);
        $id = $page->ID;
    } else {
        $id = wp_insert_post($args);
    }
    update_post_meta($id, '_wp_page_template', 'page-templates/landing-page.php');

    // -- Hero
    update_field('hero_eyebrow', 'Perth Residential Specialists', $id);
    update_field('hero_headline',
        '<p>Reliable residential <span class="blue-mark">electrical work,</span> done <span class="highlight">properly.</span></p>',
        $id
    );
    update_field('hero_subhead', 'Perth families have trusted Dent Electrical & Air to keep their homes safe and properly powered for over 10 years. From a flickering downlight to a full home rewire, we cover the lot — free quotes, no call-out fees, and workmanship that actually lasts.', $id);
    update_field('hero_trust_points', [
        ['label' => 'Licensed & insured'],
        ['label' => 'Same-day available'],
        ['label' => 'Upfront pricing'],
    ], $id);
    update_field('hero_primary_cta', [
        'title' => 'Get my free quote',
        'url'   => '#contact',
        'target' => '',
    ], $id);
    update_field('hero_show_phone', 1, $id);
    update_field('hero_image', ocd_seed_img($images, '01-hero-bg'), $id);
    update_field('hero_form_title', 'Get a free quote in under 60 seconds.', $id);
    update_field('hero_form_subtitle', 'No call-out fees. No pressure. Straight answers.', $id);
    update_field('hero_form_footnote', 'We respond within the hour during business hours.', $id);

    // -- Trust bar
    update_field('trust_bar_items', [
        ['icon' => 'shield',        'line_one' => 'Licensed electrician',      'line_two' => '[EC #TBC]'],
        ['icon' => 'shield-check',  'line_one' => 'Workmanship',                'line_two' => 'guaranteed in writing'],
        ['icon' => 'badge',         'line_one' => 'Fully insured',              'line_two' => '$20m public liability'],
        ['icon' => 'clock',         'line_one' => 'Same-day callouts',          'line_two' => 'across Perth metro'],
        ['icon' => 'star',          'line_one' => '5-star Google rated',        'line_two' => 'locally owned'],
    ], $id);

    // -- Services
    update_field('services_eyebrow', 'Our services', $id);
    update_field('services_headline', '<p>Powering Perth <span class="blue-mark">homes &amp; businesses.</span></p>', $id);
    update_field('services_intro', "From a flickering downlight to a full home rewire. Six core services, one team, one number to call. Every job is quoted up front and signed off with the paperwork to prove it's compliant.", $id);
    update_field('contact_anchor_url', '#contact', $id);
    update_field('services_items', [
        [
            'title' => 'General electrical services',
            'copy'  => 'Power points, faulty wiring, ceiling fans, hard-wired smoke alarms, and all the small jobs your home eventually needs.',
            'tag'   => 'Electrical', 'tag_tone' => 'yellow',
            'image' => ocd_seed_img($images, '03-service-general'),
            'cta_text' => 'Get a quote',
        ],
        [
            'title' => 'Electrical inspections',
            'copy'  => 'A proper check of your switchboard, wiring, RCDs, and earth, with a written report and recommendations you can actually act on.',
            'tag'   => 'Inspections', 'tag_tone' => 'blue',
            'image' => ocd_seed_img($images, '04-service-inspections'),
            'cta_text' => 'Book an inspection',
        ],
        [
            'title' => 'Lighting solutions',
            'copy'  => "LED upgrades, downlight replacements, pendant installs, outdoor and security lighting. Bright where you need it, soft where you don't.",
            'tag'   => 'Lighting', 'tag_tone' => 'yellow',
            'image' => ocd_seed_img($images, '05-service-lighting'),
            'cta_text' => 'Explore options',
        ],
        [
            'title' => 'Switchboard upgrades',
            'copy'  => "Replace old ceramic fuses or a tripping board with a modern, compliant switchboard built for today's appliance loads and EV chargers.",
            'tag'   => 'Compliance', 'tag_tone' => 'dark',
            'image' => ocd_seed_img($images, '06-service-switchboard'),
            'cta_text' => 'Get a quote',
        ],
        [
            'title' => 'Home automation',
            'copy'  => 'Smart switches, app-controlled lighting, sensor automation, and integrated home systems wired in by an electrician who actually understands them.',
            'tag'   => 'Smart home', 'tag_tone' => 'blue',
            'image' => ocd_seed_img($images, '07-service-automation'),
            'cta_text' => 'Explore options',
        ],
        [
            'title' => 'Safety switch installations',
            'copy'  => "RCD installation and testing on every circuit. If a fault happens, power cuts in milliseconds, not when it's too late.",
            'tag'   => 'Safety', 'tag_tone' => 'dark',
            'image' => ocd_seed_img($images, '08-service-safety'),
            'cta_text' => 'Get a quote',
        ],
    ], $id);

    // -- CTA blue
    update_field('cta_blue_headline', '<p>Power problems don\'t wait.<br><span class="underline-mark">Neither do we.</span></p>', $id);
    update_field('cta_blue_copy', 'Most jobs booked before lunch are done the same day. Written quote first, no call-out fee, no surprise invoice.', $id);
    update_field('cta_blue_cta', ['title' => 'Get my free quote', 'url' => '#contact', 'target' => ''], $id);
    update_field('cta_blue_photo', ocd_seed_img($images, '12-cta-blue'), $id);

    // -- About
    update_field('about_photo', ocd_seed_img($images, '02-founder'), $id);
    update_field('about_photo_badge', 'On the tools every day', $id);
    update_field('about_eyebrow', 'About the founder', $id);
    update_field('about_headline', '<p>Run by a tradesman,<br><span class="blue-mark">not a call centre.</span></p>', $id);
    update_field('about_copy',
        "<p>Stephen started Dent Electrical &amp; Air because too many Perth families were copping the same treatment: late arrivals, surprise invoices, and work that needed re-doing six months later.</p>\n" .
        "<p>I'm Stephen Dent, and I've spent close to two decades in the electrical and air conditioning trade. Originally from Ireland, I've built this business on the same values I grew up with: do the job properly, be straight with people, and stand by your work.</p>\n" .
        "<p>Over the years I've handled the full range, residential and commercial, from straightforward repairs and maintenance through to full installations and system upgrades. Whatever the job, my approach doesn't change. I turn up when I say I will, I explain what needs doing in plain terms, and I make sure the work is safe, efficient, and built to last.</p>\n" .
        "<p>I take care on every property I step into, because it's someone's home or business, not just a worksite. Most of my work these days comes through repeat customers and referrals, which tells me the approach is right. Honest communication, fair pricing, and proper workmanship, every time.</p>",
        $id
    );
    update_field('about_sig_name', 'Stephen Dent', $id);
    update_field('about_sig_role', 'Owner & Lead Tradesman', $id);
    update_field('about_stats', [
        ['number' => '20+',     'label' => 'Years on the tools', 'tone' => 'yellow'],
        ['number' => '&lt;60min', 'label' => 'Response guarantee', 'tone' => 'default'],
        ['number' => '5.0',     'label' => 'Google rating',       'tone' => 'blue'],
    ], $id);

    // -- Why us
    update_field('why_eyebrow', 'Why Dent', $id);
    update_field('why_headline', '<p>The difference is <span class="yellow-mark">in the details.</span></p>', $id);
    update_field('why_intro', "We're not the cheapest in Perth. We are the team that turns up when we say, charges what we quote, and stands by the work long after the invoice is paid.", $id);
    update_field('why_items', [
        ['icon' => 'shield',       'title' => 'Licensed & insured',           'copy' => 'Fully qualified WA electricians, public liability and PI insured on every job. [CLIENT TO CONFIRM: EC number].'],
        ['icon' => 'panel',        'title' => 'Upfront fixed pricing',         'copy' => 'Quote first, work second. The number you agree to is the number on your invoice. No surprises, ever.'],
        ['icon' => 'clock',        'title' => 'Same-day availability',         'copy' => 'Lost power? Lights tripping the breaker? Most jobs we get to the same day, often within a couple of hours.'],
        ['icon' => 'home',         'title' => 'Clean and respectful',          'copy' => 'Drop sheets, shoe covers, and a tidy-up before we leave. Your home looks the same as when we walked in.'],
        ['icon' => 'pin',          'title' => 'Locally owned, locally based',  'copy' => 'Perth-born, Perth-based. We know the local building stock, the climate, and what actually holds up.'],
        ['icon' => 'shield-check', 'title' => 'Workmanship guarantee',         'copy' => '[CLIENT TO CONFIRM] year written guarantee on every job. If something we touched fails, we fix it, no argument.'],
    ], $id);

    // -- Feature: Compliance
    update_field('compliance_image', ocd_seed_img($images, '09-feature-compliance'), $id);
    update_field('compliance_image_tag', 'Compliance', $id);
    update_field('compliance_image_tag_tone', 'yellow', $id);
    update_field('compliance_eyebrow', 'Compliance', $id);
    update_field('compliance_headline', '<p>Tested, tagged, <span class="yellow-mark">and signed off.</span></p>', $id);
    update_field('compliance_copy', 'Every electrical job leaves with a Certificate of Compliance lodged with EnergySafety WA. Quality components, full RCD protection, and the paperwork that actually matters when you sell, insure, or claim. You keep the certificate. Your insurer keeps you covered.', $id);
    update_field('compliance_bullets', [
        ['text' => 'Certificate of Electrical Compliance on every job'],
        ['text' => 'RCD-protected circuits as standard, not as an upsell'],
        ['text' => 'Photo records of installation work, kept for [X] years'],
        ['text' => 'Quality components: Clipsal, HPM, NHP, Hager'],
    ], $id);
    update_field('compliance_primary_cta', ['title' => 'Get my free quote', 'url' => '#contact', 'target' => ''], $id);
    update_field('compliance_secondary_cta', ['title' => 'Call us', 'url' => 'tel:', 'target' => ''], $id);

    // -- Feature: Inspections
    update_field('inspections_image', ocd_seed_img($images, '10-feature-inspections'), $id);
    update_field('inspections_image_tag', 'Inspections', $id);
    update_field('inspections_image_tag_tone', 'blue', $id);
    update_field('inspections_eyebrow', 'Property inspections', $id);
    update_field('inspections_headline', '<p>Selling, leasing, <span class="blue-mark">or just want peace of mind?</span></p>', $id);
    update_field('inspections_copy', "Pre-sale and pre-lease electrical inspections, repairs, and certification. We uncover what a buyer's building inspector will find, before they do, and give you the paperwork to either fix it or move on with eyes open.", $id);
    update_field('inspections_bullets', [
        ['text' => '<strong>Pre-sale &amp; pre-lease inspections.</strong> Uncover issues before they tank a deal'],
        ['text' => '<strong>Repairs &amp; maintenance.</strong> Faults fixed promptly to keep systems compliant'],
        ['text' => '<strong>Upgrades.</strong> Modernise outdated wiring, fixtures, and systems for safety'],
        ['text' => '<strong>Certification &amp; documentation.</strong> Written reports for buyers, tenants, or insurers'],
    ], $id);
    update_field('inspections_primary_cta', ['title' => 'Book an inspection', 'url' => '#contact', 'target' => ''], $id);
    update_field('inspections_secondary_cta', ['title' => 'Call us', 'url' => 'tel:', 'target' => ''], $id);

    // -- Feature: Process
    update_field('process_image', ocd_seed_img($images, '11-feature-process'), $id);
    update_field('process_image_tag', 'Process', $id);
    update_field('process_image_tag_tone', 'yellow', $id);
    update_field('process_eyebrow', 'How it works', $id);
    update_field('process_headline', '<p>Three steps. <span class="yellow-mark">No surprises.</span></p>', $id);
    update_field('process_copy', "The whole point of hiring a tradesman is to take the headache off your hands. Our process is built so you know exactly what's happening at every stage, what it costs, and when it'll be done.", $id);
    update_field('process_bullets', [
        ['text' => '<strong>Book.</strong> Call, text, or use the form. We confirm a time within the hour.'],
        ['text' => '<strong>Quote.</strong> On-site assessment, fixed price, no obligation to proceed.'],
        ['text' => '<strong>Service.</strong> Job done, area cleaned, paperwork sent same day.'],
    ], $id);
    update_field('process_primary_cta', ['title' => 'Start with step one', 'url' => '#contact', 'target' => ''], $id);
    update_field('process_secondary_cta', ['title' => 'Call us', 'url' => 'tel:', 'target' => ''], $id);

    // -- CTA yellow
    update_field('cta_yellow_headline', '<p>Tired of tradies <span class="underline-mark">who don\'t turn up?</span></p>', $id);
    update_field('cta_yellow_copy', "We confirm the arrival window, call when we're on the way, and arrive when we said we would. Old fashioned, we know.", $id);
    update_field('cta_yellow_cta', ['title' => "Book a job we'll show up to", 'url' => '#contact', 'target' => ''], $id);

    // -- Reviews
    update_field('reviews_eyebrow', 'What Perth says', $id);
    update_field('reviews_headline', '<p>The work speaks. <span class="blue-mark">So do our customers.</span></p>', $id);
    update_field('reviews_intro', "Every review below is unedited from Google. We don't filter, edit, or pay for them. If a job goes pear-shaped, we own it and fix it, that's the only way the rating stays where it is.", $id);
    update_field('reviews_items', [
        [
            'quote'    => '[CLIENT TO CONFIRM: real Google review #1 text. Approx 30-45 words. Should mention specific service and outcome.]',
            'name'     => '[Reviewer Name]',
            'location' => '[Suburb]',
            'rating'   => 5,
        ],
        [
            'quote'    => '[CLIENT TO CONFIRM: real Google review #2 text. Approx 30-45 words. Different service angle than review 1.]',
            'name'     => '[Reviewer Name]',
            'location' => '[Suburb]',
            'rating'   => 5,
        ],
        [
            'quote'    => '[CLIENT TO CONFIRM: real Google review #3 text. Approx 30-45 words. Ideally mentions punctuality, pricing, or workmanship.]',
            'name'     => '[Reviewer Name]',
            'location' => '[Suburb]',
            'rating'   => 5,
        ],
    ], $id);

    // -- Contact
    update_field('contact_eyebrow', 'Get in touch', $id);
    update_field('contact_headline', '<p>Keep the lights on, <span class="yellow-mark">with one call.</span></p>', $id);
    update_field('contact_form_title', 'Send us a message.', $id);
    update_field('contact_form_subtitle', 'No spam. No call-out fees. We respond within the hour during business hours.', $id);
    update_field('contact_service_area', 'Perth metro, WA', $id);
    update_field('contact_hours_text', 'Mon to Sat, 7am to 6pm', $id);
    update_field('contact_hours_extra', '[CLIENT TO CONFIRM: after-hours availability]', $id);

    // -- Final CTA
    update_field('final_cta_headline', '<p>One call.<br><span class="yellow-mark">One sparky.</span><br>Sorted.</p>', $id);
    update_field('final_cta_copy', "Whether it's a flickering light, a tripping breaker, or a full home rewire, we'll turn up when we said, charge what we quoted, and leave the place tidy.", $id);
    update_field('final_cta_cta', ['title' => 'Get my free quote', 'url' => '#contact', 'target' => ''], $id);
    update_field('final_cta_show_phone', 1, $id);
    update_field('final_cta_photo', ocd_seed_img($images, '13-final-cta'), $id);

    // -- Page SEO
    update_field('meta_description', "Perth's trusted residential electrician. Free quotes, no call-out fees, same-day service. Licensed, insured, and locally owned.", $id);
    update_field('og_image', ocd_seed_img($images, '01-hero-bg'), $id);

    return $id;
}

/**
 * Populate Site Options.
 */
function ocd_seed_site_options($images) {
    update_field('business_phone', '0487810565', 'option');
    update_field('display_phone',  '0487 810 565', 'option');
    update_field('business_email', 'admin@dentelectricalandair.com.au', 'option');
    update_field('business_address', '[CLIENT TO CONFIRM: full street address]', 'option');
    update_field('address_short', 'Perth, WA', 'option');

    update_field('site_logo',   ocd_seed_img($images, 'logo-header'), 'option');
    update_field('footer_logo', ocd_seed_img($images, 'logo-footer'), 'option');
    update_field('footer_blurb', "Perth's trusted residential electrician. Licensed, insured, owner-operated, and on call when you need us.", 'option');
    update_field('footer_disclaimer', '© {year} Dent Electrical & Air. All rights reserved.', 'option');
    update_field('ec_number', '[EC #TBC]', 'option');

    update_field('promo_pills', [
        ['label' => 'Free quotes'],
        ['label' => 'No call-out fees'],
        ['label' => 'Same-day service available'],
    ], 'option');
    update_field('nav_cta', ['title' => 'Get a quote', 'url' => '#contact', 'target' => ''], 'option');
    update_field('mobile_bar_quote_url', home_url('/#contact'), 'option');

    update_field('schema_business_type', 'Electrician', 'option');
    update_field('schema_suburb', 'Perth', 'option');
    update_field('schema_state', 'WA', 'option');
    update_field('schema_postcode', '[CLIENT TO CONFIRM]', 'option');
    update_field('schema_abn', '', 'option');
}

/**
 * Make the Home page the static front page.
 */
function ocd_seed_set_front_page($home_id) {
    if (!$home_id) { return; }
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_id);
}
