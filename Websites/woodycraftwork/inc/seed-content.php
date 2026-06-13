<?php
/**
 * Demo content seeder.
 *
 * Runs once on theme activation (or via Tools -> Seed Woody Content). It:
 *   1. Imports placeholder images from /assets/seed/ into the Media Library
 *   2. Creates / updates the Thank You and Home pages with the right templates
 *   3. Writes every ACF field on Home + Site Options with the launch copy
 *   4. Sets Home as the static front page
 *
 * Tracks completion via an option flag so it never runs twice unless requested.
 */
if (!defined('ABSPATH')) { exit; }

define('OCD_SEED_OPTION', 'ocd_woody_seed_done');
define('OCD_SEED_DIR', OCD_THEME_PATH . '/assets/seed');

add_action('after_switch_theme', function() {
    if (!function_exists('acf_get_field_groups')) { return; }
    if (get_option(OCD_SEED_OPTION)) { return; }
    ocd_seed_run();
});

add_action('admin_menu', function() {
    add_management_page('Seed Woody Content', 'Seed Woody Content', 'manage_options', 'ocd-seed', 'ocd_seed_admin_page');
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
        <h1>Seed Woody Content</h1>
        <p>Populates Site Options, the Home page (Landing Page template) and the Thank You page with the launch copy and placeholder images.</p>
        <p>Status: <strong><?php echo $done ? 'Seeded on ' . esc_html($done) : 'Not yet seeded'; ?></strong></p>
        <form method="post">
            <?php wp_nonce_field('ocd_seed_run'); ?>
            <p><button type="submit" name="ocd_seed_run" class="button button-primary"><?php echo $done ? 'Re-run seed (overwrites Home & Site Options)' : 'Seed content now'; ?></button></p>
        </form>
    </div>
    <?php
}

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
 * Import /assets/seed/ into the Media Library, keyed by filename (no extension).
 *
 * @return array<string,int>
 */
function ocd_seed_import_images() {
    $map = get_option('ocd_woody_seed_images', []);
    if (!is_array($map)) { $map = []; }
    if (!is_dir(OCD_SEED_DIR)) { return $map; }

    foreach (glob(OCD_SEED_DIR . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE) as $path) {
        $key = pathinfo($path, PATHINFO_FILENAME);
        if (isset($map[$key]) && get_post($map[$key])) { continue; }

        $upload = wp_upload_bits(basename($path), null, file_get_contents($path));
        if (!empty($upload['error'])) { continue; }

        $filetype = wp_check_filetype(basename($upload['file']), null);
        $attach_id = wp_insert_attachment([
            'post_mime_type' => $filetype['type'],
            'post_title'     => sanitize_text_field(pathinfo($upload['file'], PATHINFO_FILENAME)),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ], $upload['file']);
        if (!is_wp_error($attach_id)) {
            wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $upload['file']));
            $map[$key] = (int) $attach_id;
        }
    }
    update_option('ocd_woody_seed_images', $map);
    return $map;
}

function ocd_seed_img($images, $key) {
    return isset($images[$key]) ? (int) $images[$key] : 0;
}

function ocd_seed_link($title, $url, $target = '') {
    return ['title' => $title, 'url' => $url, 'target' => $target];
}

function ocd_seed_create_thankyou($images) {
    $page = get_page_by_path('thank-you');
    $args = ['post_title' => 'Thank You', 'post_name' => 'thank-you', 'post_status' => 'publish', 'post_type' => 'page', 'post_content' => ''];
    if ($page) { $args['ID'] = $page->ID; wp_update_post($args); $id = $page->ID; }
    else { $id = wp_insert_post($args); }
    update_post_meta($id, '_wp_page_template', 'page-templates/thank-you.php');

    update_field('ty_headline', 'Thanks, we have your details.', $id);
    update_field('ty_body', '<p>Thanks for getting in touch with Woody Craftwork. One of the team will be in touch shortly to organise your free in-home consultation.</p>', $id);
    update_field('ty_cta', ocd_seed_link('Back to home', home_url('/')), $id);

    return $id;
}

function ocd_seed_create_home($images, $ty_id) {
    $page = get_page_by_path('home');
    $args = ['post_title' => 'Home', 'post_name' => 'home', 'post_status' => 'publish', 'post_type' => 'page', 'post_content' => ''];
    if ($page) { $args['ID'] = $page->ID; wp_update_post($args); $id = $page->ID; }
    else { $id = wp_insert_post($args); }
    update_post_meta($id, '_wp_page_template', 'page-templates/landing-page.php');

    $call = ocd_seed_link('Call us', 'tel:0432175483');
    $quote = ocd_seed_link('Get a Quote', '#contact');

    /* Hero */
    update_field('hero_eyebrow', 'Family-owned Perth joinery', $id);
    update_field('hero_headline', 'Custom kitchens &amp; cabinetry<span class="thin">, designed &amp; built in Perth</span>', $id);
    update_field('hero_subhead', 'Family-owned joinery for homes and businesses. From the first design chat to a precise, on-time install, we handle the lot.', $id);
    update_field('hero_primary_cta', ocd_seed_link('Get a Quote', '#contact'), $id);
    update_field('hero_secondary_cta', ocd_seed_link('View Services', '#services'), $id);
    update_field('hero_image', ocd_seed_img($images, '01-hero'), $id);
    update_field('hero_image_mobile', ocd_seed_img($images, '01-hero'), $id);
    update_field('cb_eyebrow', 'No obligation', $id);
    update_field('cb_title', 'Request a call back', $id);

    update_field('trust_items', [
        ['label' => 'Family-owned Perth business'],
        ['label' => 'Free in-home consultation'],
        ['label' => 'Detailed design drawings'],
        ['label' => 'Realistic 3D visualisations'],
        ['label' => 'Honest, upfront pricing'],
        ['label' => 'On-time installation'],
    ], $id);

    /* Services */
    update_field('services_eyebrow', 'What we make', $id);
    update_field('services_headline', 'Our cabinetry services', $id);
    update_field('services_intro', 'Custom-built joinery for every room in the home, designed around how you actually live and finished to last.', $id);
    update_field('services_items', [
        ['title' => 'Custom Kitchens', 'copy' => 'The heart of your home, built to your layout, your storage and your finish. Designed with you, made to measure.', 'image' => ocd_seed_img($images, '02-service-kitchens'), 'cta_text' => 'Get a quote', 'cta_link' => $quote],
        ['title' => 'Bathroom & Vanity', 'copy' => 'Vanities, storage and finishes that make the most of every bathroom, big or small.', 'image' => ocd_seed_img($images, '03-service-bathroom'), 'cta_text' => 'Get a quote', 'cta_link' => $quote],
        ['title' => 'Laundry Cabinetry', 'copy' => 'Hard-working laundry storage with bench space and fittings that fit your routine.', 'image' => ocd_seed_img($images, '04-service-laundry'), 'cta_text' => 'Get a quote', 'cta_link' => $quote],
        ['title' => 'Wardrobes & Robes', 'copy' => 'From reach-in robes to full walk-ins, organised storage tailored to your room.', 'image' => ocd_seed_img($images, '05-service-wardrobes'), 'cta_text' => 'Get a quote', 'cta_link' => $quote],
        ['title' => 'Home Offices', 'copy' => 'Built-in desks, shelving and storage for a workspace that works as hard as you do.', 'image' => ocd_seed_img($images, '06-service-office'), 'cta_text' => 'Get a quote', 'cta_link' => $quote],
        ['title' => 'Entertainment & Living', 'copy' => 'TV units, display cabinetry and living room storage designed around your space.', 'image' => ocd_seed_img($images, '07-service-entertainment'), 'cta_text' => 'Get a quote', 'cta_link' => $quote],
    ], $id);

    /* CTA Band One */
    update_field('band_one_eyebrow', 'Free in-home consultation', $id);
    update_field('band_one_headline', 'Ready to start your cabinetry project?', $id);
    update_field('band_one_copy', "Book a free in-home consultation and we'll talk through your space, your ideas and your budget.", $id);
    update_field('band_one_cta', ocd_seed_link('Get a Quote', '#contact'), $id);
    update_field('band_one_image', ocd_seed_img($images, '04-service-laundry'), $id);

    /* About */
    update_field('about_image', ocd_seed_img($images, '08-about'), $id);
    update_field('about_stat_number', '20', $id);
    update_field('about_stat_label', 'Years of craft', $id);
    update_field('about_eyebrow', 'About us', $id);
    update_field('about_headline', 'A Perth family business that builds it properly', $id);
    update_field('about_lead', "I'm Filipe, and Woody Craftwork started with a simple idea: custom cabinetry should be built properly, finished beautifully and delivered when we say it will be.", $id);
    update_field('about_copy', "We're a close-knit, family-owned team, so the person you talk to at the first consultation is the same team that designs, builds and installs your cabinetry. No middlemen, no surprises, just honest work.", $id);
    update_field('about_checks', [
        ['label' => 'Family-owned and run'],
        ['label' => 'Precision-made fittings'],
        ['label' => 'Clear communication'],
        ['label' => 'Honest, upfront pricing'],
        ['label' => 'Installed on schedule'],
    ], $id);
    update_field('about_quote', "From concept to completion, we treat your home like it's our own. A finish we're proud to put our name on.", $id);
    update_field('about_quote_sig', 'Filipe — Founder', $id);

    /* Why Us */
    update_field('why_eyebrow', 'Why choose us', $id);
    update_field('why_headline', 'Why Perth homeowners choose us', $id);
    update_field('why_intro', 'A few reasons our clients trust us with their kitchens, robes and built-ins.', $id);
    update_field('why_items', [
        ['icon' => 'price',  'title' => 'Honest, upfront pricing', 'copy' => "Clear quotes with no hidden extras. You know what you're paying before we start."],
        ['icon' => 'home',   'title' => 'Local Perth family business', 'copy' => 'Owned and run locally, with hands-on care from people who answer the phone.'],
        ['icon' => 'eye',    'title' => 'See it in 3D first', 'copy' => 'Detailed drawings and realistic 3D visualisations, so you see your space before we build it.'],
        ['icon' => 'pencil', 'title' => 'Custom-built, not off-the-shelf', 'copy' => 'Every piece is made to measure for your room, your storage and your finish.'],
        ['icon' => 'chat',   'title' => 'Clear communication', 'copy' => 'One close-knit team keeping you in the loop from first chat to final install.'],
        ['icon' => 'clock',  'title' => 'On-time installation', 'copy' => 'We respect tight schedules and deliver a quality finish when we say we will.'],
    ], $id);

    /* Built to Last */
    update_field('built_eyebrow', 'Built to last', $id);
    update_field('built_headline', "Built to last,<br>finished to impress", $id);
    update_field('built_copy', "Cheap cabinetry looks fine on day one and falls apart by year three. We don't work that way. We use quality materials, precise joinery and fittings chosen to handle years of daily use.", $id);
    update_field('built_list', [
        ['label' => 'Quality materials and hardware'],
        ['label' => 'Precision-made joinery'],
        ['label' => 'Finishes built for daily use'],
        ['label' => 'Workmanship we stand behind'],
    ], $id);
    update_field('built_image', ocd_seed_img($images, '02-service-kitchens'), $id);
    update_field('built_tag', 'Made in Perth', $id);
    update_field('built_primary_cta', ocd_seed_link('Get a Quote', '#contact'), $id);
    update_field('built_secondary_cta', $call, $id);

    /* Statement */
    update_field('statement_heading', 'Designed once.<br>Built to <b>last a lifetime.</b>', $id);
    update_field('statement_sub', 'Custom joinery — made & installed across Perth', $id);
    update_field('statement_image', ocd_seed_img($images, '09-statement-bg'), $id);

    /* Design Process */
    update_field('design_eyebrow', 'Design process', $id);
    update_field('design_headline', "See it before<br>we build it", $id);
    update_field('design_copy', 'Big decisions are easier when you can picture the result. We start with an in-home consultation, then move to detailed design drawings and realistic 3D visualisations, so you can see exactly how your kitchen, robe or built-in will look before anything is made.', $id);
    update_field('design_list', [
        ['label' => 'Free in-home consultation'],
        ['label' => 'Detailed design drawings'],
        ['label' => 'Realistic 3D visualisations'],
        ['label' => 'Changes sorted before we build'],
    ], $id);
    update_field('design_image', ocd_seed_img($images, '06-service-office'), $id);
    update_field('design_tag', 'Design Process', $id);
    update_field('design_primary_cta', ocd_seed_link('Get a Quote', '#contact'), $id);

    /* Homes & Businesses */
    update_field('scope_eyebrow', 'Local Perth joiners', $id);
    update_field('scope_headline', "Cabinetry for homes<br>&amp; businesses", $id);
    update_field('scope_copy', 'From single-room makeovers to full home fit-outs, we build custom joinery for homeowners, renovators and builders right across Perth. Got a commercial fit-out or shop joinery in mind? Same precision, same honest pricing.', $id);
    update_field('scope_list', [
        ['label' => 'Kitchens, robes, laundries and more'],
        ['label' => 'Renovation and new-build joinery'],
        ['label' => 'Commercial and shop fit-outs'],
        ['label' => 'Made and installed across Perth'],
    ], $id);
    update_field('scope_image', ocd_seed_img($images, '07-service-entertainment'), $id);
    update_field('scope_tag', 'Local Perth Joiners', $id);
    update_field('scope_primary_cta', ocd_seed_link('Get a Quote', '#contact'), $id);
    update_field('scope_secondary_cta', $call, $id);

    /* CTA Band Two */
    update_field('band_two_eyebrow', "Let's begin", $id);
    update_field('band_two_headline', "Let's bring your project to life", $id);
    update_field('band_two_copy', "Tell us about your space and we'll turn it into a design you can see, then build that holds up.", $id);
    update_field('band_two_primary_cta', ocd_seed_link('Get a Quote', '#contact'), $id);
    update_field('band_two_secondary_cta', ocd_seed_link('Call Us', 'tel:0432175483'), $id);
    update_field('band_two_image', ocd_seed_img($images, '05-service-wardrobes'), $id);

    /* Gallery */
    update_field('gallery_eyebrow', 'Our work', $id);
    update_field('gallery_headline', 'Recent cabinetry, made in Perth', $id);
    update_field('gallery_intro', "A look at kitchens, robes and built-ins we've designed, made and installed for Perth homes.", $id);
    update_field('gallery_cta', ocd_seed_link('Get a Quote', '#contact'), $id);
    update_field('gallery_items', [
        ['image' => ocd_seed_img($images, '01-hero'), 'size' => 'wide'],
        ['image' => ocd_seed_img($images, '06-service-office'), 'size' => 'tall'],
        ['image' => ocd_seed_img($images, '04-service-laundry'), 'size' => 'normal'],
        ['image' => ocd_seed_img($images, '10-gallery-a'), 'size' => 'normal'],
        ['image' => ocd_seed_img($images, '05-service-wardrobes'), 'size' => 'tall'],
        ['image' => ocd_seed_img($images, '11-gallery-b'), 'size' => 'normal'],
        ['image' => ocd_seed_img($images, '09-statement-bg'), 'size' => 'normal'],
        ['image' => ocd_seed_img($images, '07-service-entertainment'), 'size' => 'wide'],
        ['image' => ocd_seed_img($images, '08-about'), 'size' => 'normal'],
        ['image' => ocd_seed_img($images, '12-gallery-c'), 'size' => 'normal'],
        ['image' => ocd_seed_img($images, '03-service-bathroom'), 'size' => 'normal'],
        ['image' => ocd_seed_img($images, '02-service-kitchens'), 'size' => 'normal'],
    ], $id);

    /* Reviews */
    update_field('reviews_eyebrow', 'Customer reviews', $id);
    update_field('reviews_headline', 'What Perth clients say', $id);
    update_field('reviews_intro', 'Placeholder reviews shown below — swap in real, verified reviews and add your rating & source (e.g. Google ★★★★★).', $id);
    update_field('reviews_items', [
        ['quote' => 'A real customer quote goes here. Specific about the job done and what stood out — authenticity beats polish.', 'name' => 'Customer Name', 'source' => 'Google review · Suburb', 'rating' => 5, 'is_placeholder' => 1],
        ['quote' => 'A real customer quote goes here. Mention the room, the timeline, and how the team communicated through the build.', 'name' => 'Customer Name', 'source' => 'Google review · Suburb', 'rating' => 5, 'is_placeholder' => 1],
        ['quote' => "A real customer quote goes here. End on the finish and whether they'd recommend Woody Craftwork to friends.", 'name' => 'Customer Name', 'source' => 'Google review · Suburb', 'rating' => 5, 'is_placeholder' => 1],
    ], $id);

    /* Contact */
    update_field('contact_eyebrow', 'Get in touch', $id);
    update_field('contact_headline', "Let's talk about your project", $id);
    update_field('contact_lead', "Tell us about your space and we'll get back to you with a free quote.", $id);
    update_field('contact_lines', [
        ['icon' => 'mail',  'label' => 'Email',     'value' => 'hello@woodycraftwork.com.au', 'link' => 'mailto:hello@woodycraftwork.com.au'],
        ['icon' => 'phone', 'label' => 'Phone',     'value' => '0432 175 483',                'link' => 'tel:0432175483'],
        ['icon' => 'pin',   'label' => 'Servicing', 'value' => 'Perth & surrounding suburbs', 'link' => ''],
        ['icon' => 'clock', 'label' => 'Hours',     'value' => 'Mon–Fri 7–5 · Sat by appt.',  'link' => ''],
    ], $id);
    update_field('contact_form_title', 'Request a free quote', $id);
    update_field('contact_form_subtitle', "Fill in a few details and we'll be in touch.", $id);

    /* Final CTA */
    update_field('final_eyebrow', 'Local craft you can trust', $id);
    update_field('final_headline', 'Your home deserves cabinetry built properly', $id);
    update_field('final_copy', 'Local craft you can trust, from the first design chat to the final install.', $id);
    update_field('final_cta', ocd_seed_link('Get a Quote', '#contact'), $id);
    update_field('final_image', ocd_seed_img($images, '01-hero'), $id);

    return $id;
}

function ocd_seed_site_options($images) {
    update_field('announcement_left', 'Custom cabinetry made in Perth', 'option');
    update_field('announcement_right', 'Free in-home consultations & 3D designs', 'option');
    update_field('logo_mark', ocd_seed_img($images, 'logo-mark'), 'option');
    update_field('brand_name', 'Woody Craftwork', 'option');
    update_field('brand_tagline', 'Perth Joinery', 'option');
    update_field('nav_cta', ocd_seed_link('Get a Quote', '#contact'), 'option');

    update_field('business_phone', '0432175483', 'option');
    update_field('display_phone', '0432 175 483', 'option');
    update_field('business_email', 'hello@woodycraftwork.com.au', 'option');

    update_field('footer_blurb', 'Family-owned custom cabinetry and joinery for homes and businesses across Perth. Kitchens, bathrooms, laundries, wardrobes, home offices and more, designed and built to last.', 'option');
    update_field('footer_copyright', '© {year} Woody Craftwork. ABN [CLIENT TO CONFIRM].', 'option');
    update_field('footer_credit', 'Made in Perth.', 'option');
}

function ocd_seed_set_front_page($home_id) {
    if (!$home_id) { return; }
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_id);
}
