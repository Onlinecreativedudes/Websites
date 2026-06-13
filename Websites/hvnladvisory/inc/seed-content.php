<?php
/**
 * Demo content seeder.
 *
 * Runs once on theme activation (or when manually triggered from
 * Tools → Seed HVNL Content). It:
 *   1. Imports placeholder images from /assets/seed/ into the Media Library
 *   2. Creates / updates the Thank You and Home pages with the correct templates
 *   3. Writes every ACF field on Home + Site Options with the launch copy
 *   4. Sets Home as the static front page
 *
 * Tracks completion via an option flag so it never runs twice unless requested.
 */
if (!defined('ABSPATH')) { exit; }

define('OCD_SEED_OPTION', 'ocd_hvnl_seed_done');
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
        'Seed HVNL Content',
        'Seed HVNL Content',
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
        <h1>Seed HVNL Content</h1>
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
            <li>Site Options: logo, phone, email, footer and schema details</li>
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

    $images  = ocd_seed_import_images();
    $ty_id   = ocd_seed_create_thankyou($images);
    $home_id = ocd_seed_create_home($images, $ty_id);
    ocd_seed_site_options($images);
    ocd_seed_set_front_page($home_id);

    update_option(OCD_SEED_OPTION, current_time('mysql'));
    return sprintf('Home page #%d, Thank You page #%d, %d images imported.', $home_id, $ty_id, count($images));
}

/**
 * Import every file in /assets/seed/ into the Media Library, keyed by filename.
 *
 * @return array<string,int> Filename (no extension) => attachment ID.
 */
function ocd_seed_import_images() {
    $map = get_option('ocd_hvnl_seed_images', []);
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

    update_option('ocd_hvnl_seed_images', $map);
    return $map;
}

/**
 * Helper: lookup a seeded attachment ID by base filename, setting alt text.
 */
function ocd_seed_img($images, $key, $alt = '') {
    $id = isset($images[$key]) ? (int) $images[$key] : 0;
    if ($id && $alt) {
        update_post_meta($id, '_wp_attachment_image_alt', $alt);
    }
    return $id;
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

    update_field('ty_headline', 'Request received.', $id);
    update_field('ty_body', '<p>Thanks — a certified auditor will be in touch within one business day to arrange your exposure review. No sales pitch, just clarity on where you stand.</p>', $id);
    update_field('ty_cta', [
        'title'  => 'Back to home',
        'url'    => home_url('/'),
        'target' => '',
    ], $id);

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
    update_field('hero_kicker', 'Independent HVNL & Chain of Responsibility Advisory', $id);
    update_field('hero_headline', 'Outsourcing transport doesn\'t outsource the <span class="accent">liability</span>.', $id);
    update_field('hero_intro', 'If your business sends, receives or manages freight, Chain of Responsibility obligations under the Heavy Vehicle National Law sit with you and your directors — even when transport is fully outsourced. We build practical, commercially simple compliance frameworks that let you demonstrate reasonable steps with confidence.', $id);
    update_field('hero_ticks', [
        ['label' => 'Led by certified auditors, not generalist consultants'],
        ['label' => 'Evidence-based frameworks that stand up to regulator scrutiny'],
        ['label' => 'Built around how your operation actually runs'],
    ], $id);
    update_field('hero_image', ocd_seed_img($images, '01-hero-bg', 'Heavy vehicle freight on the highway at sunset'), $id);
    update_field('hero_form_eyebrow', 'No cost · no obligation', $id);
    update_field('hero_form_heading', 'Book a 30-Minute Exposure Review', $id);
    update_field('hero_form_subheading', 'Find out where Chain of Responsibility obligations sit in your operation.', $id);
    update_field('hero_form_footnote', 'A certified auditor will respond within one business day. No sales pitch — just clarity on where you stand.', $id);

    // -- Trust bar
    update_field('trust_bar_items', [
        ['label' => 'Certified auditor led'],
        ['label' => 'Independent advice'],
        ['label' => 'Evidence-based audits'],
        ['label' => 'NHVAS module audits'],
        ['label' => 'Practical, commercial frameworks'],
        ['label' => 'Australia-wide coverage'],
    ], $id);

    // -- Blind spots
    update_field('bs_kicker', 'The hidden exposure', $id);
    update_field('bs_headline', 'Three blind spots that catch directors out', $id);
    update_field('bs_intro', 'Most Chain of Responsibility exposure is not deliberate. It hides inside assumptions like these.', $id);
    update_field('bs_cards', [
        [
            'title' => 'Reliance without verification',
            'text'  => 'You trust your transport providers. But there is no structured monitoring or documented oversight behind that trust — and trust alone is not a defence.',
        ],
        [
            'title' => 'Policy without evidence',
            'text'  => 'The policies exist. The audit trails, training records and monitoring that prove they actually work do not.',
        ],
        [
            'title' => 'Delegation mistaken for accountability',
            'text'  => 'Operational responsibility can be delegated. Legal accountability stays with the business and its executives.',
        ],
    ], $id);
    update_field('bs_cta_text', 'If any of these sound familiar, a 30-minute exposure review will show you exactly where you stand.', $id);
    update_field('bs_cta_label', 'Book a 30-Minute Exposure Review', $id);

    // -- Services
    update_field('services_kicker', 'What we do', $id);
    update_field('services_headline', 'Chain of Responsibility compliance services', $id);
    update_field('services_intro', 'From a fast baseline assessment to full enforcement response, one advisory team covers the entire compliance lifecycle.', $id);
    update_field('services_items', [
        [
            'title' => 'Online CoR self-assessment',
            'text'  => 'A fast compliance baseline. Risk-rated gap analysis with a prioritised action plan you can act on immediately.',
        ],
        [
            'title' => 'On-site CoR audit',
            'text'  => 'Evidence-based assurance through interviews, site walkthroughs and record sampling, completed by a certified auditor.',
        ],
        [
            'title' => 'System design & implementation',
            'text'  => 'Governance, controls and documentation built around how your operation actually works — not a generic template.',
        ],
        [
            'title' => 'Managed compliance support',
            'text'  => 'Ongoing oversight, reporting cadence and action tracking that keeps your systems current and your evidence healthy.',
        ],
        [
            'title' => 'Notices, investigations & enforcement',
            'text'  => 'Structured triage, evidence packs and corrective action programs when regulator scrutiny escalates.',
        ],
        [
            'title' => 'NHVAS audits',
            'text'  => 'Independent Maintenance, Mass and Fatigue module audits with corrective action planning to support accreditation.',
        ],
    ], $id);

    // -- Offer banner
    update_field('offer_image', ocd_seed_img($images, '02-cta-team', 'The HVNL Advisory team in consultation'), $id);
    update_field('offer_kicker', 'The offer', $id);
    update_field('offer_headline', 'Not sure whether CoR even applies to you?', $id);
    update_field('offer_intro', 'That question is exactly what the 30-minute exposure review answers. In one structured conversation, we will:', $id);
    update_field('offer_ticks', [
        ['label' => 'Clarify where Chain of Responsibility obligations sit in your operation'],
        ['label' => 'Identify any structural blind spots in your oversight'],
        ['label' => 'Outline two to three practical steps to strengthen your position'],
    ], $id);
    update_field('offer_cta_label', 'Book a 30-Minute Exposure Review', $id);
    update_field('offer_microcopy', 'No cost. No obligation. No sales pitch.', $id);

    // -- About
    update_field('about_kicker', 'Field-grounded advisory', $id);
    update_field('about_headline', 'Advice from the <span class="accent">field</span>, not a textbook', $id);
    update_field('about_body', "<p>I'm Urszula Kelly, founder and Principal Advisor at HVNL Advisory, and a Certified Auditor. My job is simple to describe and hard to do: translate regulatory obligation into everyday operational clarity, tailored to how your business actually runs.</p><p>Alongside me, advisors Darryl Brown and Mel Booth bring the same field-first approach to every engagement across Australia — every engagement auditor-led, for freight owners, 3PLs and operators of all sizes.</p>", $id);
    update_field('about_credentials', [
        ['label' => 'Certified Auditor & Principal Advisor'],
        ['label' => 'Working with freight owners, 3PLs & operators of all sizes'],
    ], $id);
    update_field('about_cta_label', 'Book a 30-Minute Exposure Review', $id);
    update_field('about_portrait', ocd_seed_img($images, '05-portrait-urszula', 'Urszula Kelly, Founder & Principal Advisor'), $id);
    update_field('about_stat_number', '19+', $id);
    update_field('about_stat_label', 'Years experience', $id);
    update_field('about_badge_name', 'Urszula Kelly', $id);
    update_field('about_badge_role', 'Founder · Principal Advisor & Auditor', $id);

    // -- Team
    update_field('team_headline', 'Meet the <span class="accent">team</span>', $id);
    update_field('team_members', [
        [
            'photo' => ocd_seed_img($images, '05-portrait-urszula'),
            'name'  => 'Urszula Kelly',
            'role'  => 'Founder, Principal Advisor & Auditor',
        ],
        [
            'photo' => ocd_seed_img($images, '06-portrait-darryl', 'Darryl Brown'),
            'name'  => 'Darryl Brown',
            'role'  => 'Advisor & Auditor',
        ],
        [
            'photo' => ocd_seed_img($images, '07-portrait-mel', 'Mel Booth'),
            'name'  => 'Mel Booth',
            'role'  => 'Advisor & Auditor',
        ],
    ], $id);

    // -- Why us
    update_field('why_kicker', 'Why us', $id);
    update_field('why_headline', 'Why businesses <span class="accent">trust</span> us with their CoR compliance', $id);
    update_field('why_cards', [
        [
            'title' => 'Certified auditor led',
            'text'  => 'Every engagement is led by a certified auditor — not a generalist consultant working from a checklist.',
        ],
        [
            'title' => 'Independent & impartial',
            'text'  => 'We have no stake in your transport contracts or providers. You get findings you can rely on.',
        ],
        [
            'title' => 'Evidence first',
            'text'  => 'We build audit trails and records that hold up under scrutiny — not policies that sit on a shelf.',
        ],
        [
            'title' => 'Commercially simple',
            'text'  => 'Controls designed around how your operation runs, without slowing it down or burying it in paperwork.',
        ],
        [
            'title' => 'Defensible by design',
            'text'  => 'Everything we build maps back to the reasonable steps standard that regulators assess against.',
        ],
        [
            'title' => 'Baseline to enforcement',
            'text'  => 'One advisory team across the full lifecycle, from self-assessment through to enforcement response.',
        ],
    ], $id);

    // -- Industries
    update_field('ind_image', ocd_seed_img($images, '03-industries-fleet', 'Fleet of trucks lined up at a despatch centre'), $id);
    update_field('ind_kicker', 'Industries we work with', $id);
    update_field('ind_headline', 'Deep in the sectors that live and breathe <span class="accent">freight</span>', $id);
    update_field('ind_intro', 'From inbound raw materials to last-mile delivery, these are the businesses where Chain of Responsibility duties bite hardest — and where we do our best work.', $id);
    update_field('ind_cards', [
        [
            'title' => 'Machinery manufacturers',
            'text'  => 'Heavy, high-value loads and constant inbound–outbound freight that place you firmly in the chain.',
        ],
        [
            'title' => 'Food manufacturers',
            'text'  => 'Tight delivery windows and perishable freight where scheduling pressure collides with CoR duty.',
        ],
        [
            'title' => 'Transport & logistics companies',
            'text'  => 'Operators and 3PLs carrying primary duties across every load, driver and subcontractor.',
        ],
        [
            'title' => 'Supermarkets',
            'text'  => 'Complex inbound networks and DC operations with consignor and receiver obligations at scale.',
        ],
    ], $id);

    // -- Who we help
    update_field('who_kicker', 'Who we help', $id);
    update_field('who_headline', 'Built for <span class="accent">every party</span> in the chain', $id);
    update_field('who_rows', [
        [
            'label'   => 'Freight owners',
            'heading' => 'You outsource the trucks. The law says you still own the risk.',
            'text'    => 'Retailers, manufacturers and resource companies are parties in the chain the moment freight moves on their behalf. We help you meet Chain of Responsibility obligations through practical controls, governance and defensible evidence — even when every truck belongs to someone else.',
            'ticks'   => [
                ['label' => 'Structured oversight of outsourced transport providers'],
                ['label' => 'Governance frameworks sized for your risk profile'],
                ['label' => 'Evidence your board can stand behind'],
            ],
            'cta_label' => 'Book a 30-Minute Exposure Review',
            'image'     => ocd_seed_img($images, '08-split-boardroom', 'The HVNL Advisory team at the boardroom table'),
        ],
        [
            'label'   => '3PLs & contract logistics',
            'heading' => 'Consistent assurance across every site, customer and subcontractor',
            'text'    => "Warehousing and transport providers sit in the middle of everyone's chain. We help you build assurance that holds across multiple sites, demanding customers and layered subcontractor networks — without slowing your operation down.",
            'ticks'   => [
                ['label' => 'Subcontractor management frameworks that scale'],
                ['label' => 'Customer-ready compliance evidence on demand'],
                ['label' => 'Consistent standards across every site you run'],
            ],
            'cta_label' => 'Book a 30-Minute Exposure Review',
            'image'     => ocd_seed_img($images, '09-split-containers', 'Container cargo freight ship with working crane'),
        ],
        [
            'label'   => 'Small & growing operators',
            'heading' => 'Compliance that grows with you, not against you',
            'text'    => 'Owner drivers, subcontractors and fleet managers do not need an enterprise compliance department. You need achievable controls and clean records that satisfy customer expectations today and scale as the fleet grows.',
            'ticks'   => [
                ['label' => 'Right-sized controls you can actually maintain'],
                ['label' => 'Records that win and keep contract work'],
                ['label' => 'A clear pathway as you add vehicles and drivers'],
            ],
            'cta_label' => 'Book a 30-Minute Exposure Review',
            'image'     => ocd_seed_img($images, '10-split-walking', 'The HVNL Advisory team walking'),
        ],
    ], $id);

    // -- How we work
    update_field('how_kicker', 'The process', $id);
    update_field('how_headline', 'How an engagement runs', $id);
    update_field('how_intro', 'No mystery, no scope creep. Five clear stages from first conversation to close-out.', $id);
    update_field('how_steps', [
        [
            'title' => 'Scope',
            'text'  => 'We define your role in the chain, the sites and functions in scope, and your risk profile.',
        ],
        [
            'title' => 'Review',
            'text'  => 'We review your documentation, records and operational context to focus effort where it matters.',
        ],
        [
            'title' => 'Audit',
            'text'  => 'Interviews, walkthroughs and evidence sampling, completed against the agreed plan.',
        ],
        [
            'title' => 'Findings',
            'text'  => 'Clear findings with risk ratings, recommended controls, owners and timeframes.',
        ],
        [
            'title' => 'Close-out',
            'text'  => 'Practical next steps — implementation support, verification or ongoing oversight.',
        ],
    ], $id);

    // -- Evidence banner
    update_field('evidence_headline', 'Regulators do not assess intentions. They assess <span class="accent">evidence</span>.', $id);
    update_field('evidence_text', 'Find out whether your records would hold up — before anyone asks to see them.', $id);
    update_field('evidence_cta_label', 'Book a 30-Minute Exposure Review', $id);

    // -- Reviews
    update_field('reviews_kicker', 'Client outcomes', $id);
    update_field('reviews_headline', 'What clients say', $id);
    update_field('reviews_items', [
        [
            'quote'    => '[CLIENT TO CONFIRM — reviews that name Urszula or reference a specific outcome will convert best.]',
            'name'     => '[Reviewer name]',
            'business' => '[Business type]',
        ],
        [
            'quote'    => '[CLIENT TO CONFIRM — source from Google reviews or direct testimonials.]',
            'name'     => '[Reviewer name]',
            'business' => '[Business type]',
        ],
        [
            'quote'    => '[CLIENT TO CONFIRM — specific, outcome-led reviews outperform generic praise.]',
            'name'     => '[Reviewer name]',
            'business' => '[Business type]',
        ],
    ], $id);

    // -- Final CTA
    update_field('final_image', ocd_seed_img($images, '04-final-cta-truck', 'Heavy dump truck at sunset'), $id);
    update_field('final_headline', 'Know where you stand before a <span class="accent">regulator decides</span> for you.', $id);
    update_field('final_text', 'Thirty minutes with a certified auditor. Clarity on your obligations, your blind spots and your next steps.', $id);
    update_field('final_cta_label', 'Book a 30-Minute Exposure Review', $id);

    // -- Contact
    update_field('contact_kicker', 'Get in touch', $id);
    update_field('contact_headline', 'Talk to an <span class="accent">advisor</span>', $id);
    update_field('contact_intro', 'Prefer to start with a conversation? Reach us directly — or book your exposure review and we will come to you prepared.', $id);
    update_field('contact_form_eyebrow', 'Send us a note', $id);
    update_field('contact_form_subheading', 'We reply within one business day.', $id);

    return $id;
}

/**
 * Site Options launch values.
 */
function ocd_seed_site_options($images) {
    update_field('site_logo', ocd_seed_img($images, 'logo', 'HVNL — Heavy Vehicle National Law Advisory'), 'option');
    update_field('announcement_text', 'Independent Chain of Responsibility & HVNL compliance advisory — servicing businesses across Australia', 'option');
    update_field('nav_cta_label', 'Book an Exposure Review', 'option');

    update_field('business_phone', '+61386186954', 'option');
    update_field('display_phone', '(03) 8618 6954', 'option');
    update_field('business_email', 'contact@hvnladvisory.com.au', 'option');
    update_field('coverage_text', 'All of Australia', 'option');

    update_field('footer_blurb', 'Independent Chain of Responsibility and HVNL compliance advisory for freight owners, logistics providers and heavy vehicle operators.', 'option');
    update_field('footer_cta_label', 'Book an Exposure Review', 'option');
    update_field('copyright_line', '© ' . date('Y') . ' Heavy Vehicle National Law Advisory. All rights reserved.', 'option');
    update_field('footer_disclaimer', 'General information only — not legal advice.', 'option');

    update_field('schema_business_type', 'ProfessionalService', 'option');
}

/**
 * Point the front page at Home.
 */
function ocd_seed_set_front_page($home_id) {
    if (!$home_id) { return; }
    update_option('show_on_front', 'page');
    update_option('page_on_front', (int) $home_id);
}
