<?php
/**
 * One-time, server-side provisioning for a deployed site.
 *
 * Run by deploy.sh after the theme files are synced. Because it boots
 * WordPress directly on the server, it does the things a remote API can't:
 * activate the theme, create the landing page and set it as the front page,
 * and (once Gravity Forms is active) build the assessment form and wire its
 * ID into the theme's Site Options.
 *
 * Every step is guarded by a marker file in the user's home directory, so it
 * runs once and never fights manual changes on later deploys.
 *
 * Usage: php provision.php <wp-load.php path> <theme-slug> <home-dir>
 */

if ($argc < 4) {
    fwrite(STDERR, "usage: provision.php <wp-load> <slug> <home>\n");
    exit(1);
}
list(, $wp_load, $slug, $home) = $argv;

define('WP_USE_THEMES', false);
define('WP_ADMIN', true); // make switch_theme() and theme helpers available
require $wp_load;
require_once ABSPATH . 'wp-admin/includes/theme.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

$home = rtrim($home, '/');
// Markers are namespaced per slug so each site provisions independently
// (a shared marker would make the second site on the account get skipped).
// solar-naturally keeps its original ".solar-*" markers for back-compat so it
// does not re-run and duplicate its form.
$marker = fn($n) => "$home/.prov-$slug-$n";
$legacy = fn($n) => ($slug === 'solar-naturally') ? "$home/.solar-$n" : null;
$done   = fn($n) => file_exists($marker($n)) || ($legacy($n) && file_exists($legacy($n)));
$mark   = fn($n) => @touch($marker($n));

/* 0a. Install plugins from the server-side cache (~/plugin-cache or ~/plugins).
       Drop the plugin zips into that folder once (cPanel File Manager); they get
       installed here and on every future site, without putting licensed paid
       binaries in the public repo.

       Only the plugins the build standard requires are installed. WP Rocket,
       migration plugins, and anything else left in the folder are deliberately
       ignored (WP Rocket is the reviewer's to install and configure). */
$allowed = ['advanced-custom-fields-pro', 'advanced-custom-fields', 'gravityforms', 'wordpress-seo'];
$cache = null;
foreach (["$home/plugin-cache", "$home/plugins"] as $cand) {
    if (is_dir($cand)) { $cache = $cand; break; }
}
if ($cache) {
    foreach (glob("$cache/*.zip") as $zip) {
        if (!class_exists('ZipArchive')) {
            echo "cannot read zips (no ZipArchive); skipping " . basename($zip) . "\n";
            continue;
        }
        $za = new ZipArchive;
        if ($za->open($zip) !== true) { echo "could not open " . basename($zip) . "\n"; continue; }
        $first = $za->getNameIndex(0);
        $top   = $first ? explode('/', $first)[0] : '';
        if (!in_array($top, $allowed, true)) {
            echo "ignoring non-build plugin: " . ($top ?: basename($zip)) . "\n";
        } elseif (is_dir(WP_PLUGIN_DIR . "/$top")) {
            echo "plugin already installed, skipping: $top\n";
        } else {
            $za->extractTo(WP_PLUGIN_DIR);
            echo "installed plugin from cache: $top\n";
        }
        $za->close();
    }
}

/* 0. Activate the plugins the theme expects, if installed. Not marker-guarded:
      idempotent and re-checked each deploy, so a plugin installed later gets
      switched on automatically. ACF Pro is preferred over the free ACF. */
$acf_pro = file_exists(WP_PLUGIN_DIR . '/advanced-custom-fields-pro/acf.php');
$plugins = [
    'Advanced Custom Fields PRO' => 'advanced-custom-fields-pro/acf.php',
    'Advanced Custom Fields'     => $acf_pro ? null : 'advanced-custom-fields/acf.php',
    'Gravity Forms'              => 'gravityforms/gravityforms.php',
    'Yoast SEO'                  => 'wordpress-seo/wp-seo.php',
];
foreach ($plugins as $label => $file) {
    if (!$file) { continue; }
    if (!file_exists(WP_PLUGIN_DIR . '/' . $file)) {
        echo "plugin not installed: $label\n";
        continue;
    }
    if (is_plugin_active($file)) {
        echo "plugin already active: $label\n";
        continue;
    }
    $res = activate_plugin($file);
    echo is_wp_error($res)
        ? "plugin activation failed ($label): " . $res->get_error_message() . "\n"
        : "plugin activated: $label\n";
}

/* 1. Activate the theme. Idempotent and not marker-guarded: activate whenever
      the theme is present and not already the active one, so it reliably comes
      on once the files land (and recovers if an earlier deploy used a wrong
      path). Once active, get_stylesheet() matches and this is a no-op. */
if (wp_get_theme($slug)->exists()) {
    if (get_stylesheet() !== $slug) {
        switch_theme($slug);
        echo "theme activated: $slug\n";
        $mark('theme-activated');
    } else {
        echo "theme already active: $slug\n";
    }
} else {
    echo "theme '$slug' not found in themes dir yet; will retry next deploy\n";
}

/* 2. Landing page + static front page (once). */
if (!$done('page-created')) {
    $template = 'page-templates/landing-page.php';
    $existing = get_posts([
        'post_type'   => 'page',
        'post_status' => 'any',
        'meta_key'    => '_wp_page_template',
        'meta_value'  => $template,
        'numberposts' => 1,
    ]);
    $pid = $existing ? $existing[0]->ID : wp_insert_post([
        'post_title'  => get_bloginfo('name') ?: ucwords(str_replace('-', ' ', $slug)),
        'post_name'   => 'home',
        'post_status' => 'publish',
        'post_type'   => 'page',
    ]);
    if ($pid && !is_wp_error($pid)) {
        update_post_meta($pid, '_wp_page_template', $template);
        update_option('show_on_front', 'page');
        update_option('page_on_front', (int) $pid);
        echo "landing page ready (ID $pid) and set as front page\n";
        $mark('page-created');
    } else {
        echo "could not create landing page; will retry next deploy\n";
    }
}

/* 3. Gravity Forms assessment form + wire ID into Site Options (once GF active).
 * Solar-specific: other themes (e.g. hvnladvisory) document their forms in the
 * theme README for the install agent to build and wire via ACF. */
if ($slug === 'solar-naturally' && !$done('gf-created') && class_exists('GFAPI')) {
    $form = [
        'title'       => 'Solar Assessment',
        'description' => '',
        'button'      => ['type' => 'text', 'text' => 'Get my free assessment'],
        'fields'      => [
            ['id' => 1, 'type' => 'text',  'label' => 'First name',                 'isRequired' => true],
            ['id' => 2, 'type' => 'text',  'label' => 'Last name',                  'isRequired' => true],
            ['id' => 3, 'type' => 'phone', 'label' => 'Phone number',               'isRequired' => true],
            ['id' => 4, 'type' => 'email', 'label' => 'Email address',              'isRequired' => true],
            ['id' => 5, 'type' => 'text',  'label' => 'Suburb',                     'isRequired' => true],
            ['id' => 6, 'type' => 'select','label' => 'Average monthly power bill', 'isRequired' => true,
                'choices' => [
                    ['text' => 'Under $200',    'value' => 'Under $200'],
                    ['text' => '$200 to $350',  'value' => '$200 to $350'],
                    ['text' => '$350+',         'value' => '$350+'],
                ]],
            ['id' => 7, 'type' => 'radio', 'label' => 'Do you own your home?',      'isRequired' => true,
                'choices' => [
                    ['text' => 'Yes', 'value' => 'Yes'],
                    ['text' => 'No',  'value' => 'No'],
                ]],
        ],
    ];
    $form_id = GFAPI::add_form($form);
    if (!is_wp_error($form_id)) {
        if (function_exists('update_field')) {
            update_field('assessment_form_id', $form_id, 'option');
        } else {
            update_option('options_assessment_form_id', $form_id);
            update_option('_options_assessment_form_id', 'field_sn_assessment_form_id');
        }
        echo "gravity form created (ID $form_id) and wired into Site Options\n";
        $mark('gf-created');
    } else {
        echo "gravity form creation failed: " . $form_id->get_error_message() . "\n";
    }
} elseif ($slug === 'solar-naturally' && !$done('gf-created')) {
    echo "Gravity Forms not active yet; assessment form will be created once it is\n";
}

/* 3b. HVNL Advisory forms: the hero "Exposure Review" form and the "Contact"
 * form. Both redirect to /thank-you/, BCC hello@onlinecreativedudes.com, use
 * the international phone format, and have the honeypot on. Their IDs are
 * written into the Home page's ACF fields (hero_form_id, contact_form_id). */
if ($slug === 'hvnladvisory' && !$done('gf-created') && class_exists('GFAPI')) {
    $ty_url = home_url('/thank-you/');
    $bcc    = 'hello@onlinecreativedudes.com';

    // A GF notification to the admin, BCC'd to OCD, reply-to the form's email.
    $notification = function ($email_field_id) use ($bcc) {
        $nid = uniqid();
        return [$nid => [
            'id'        => $nid,
            'isActive'  => true,
            'name'      => 'Admin Notification',
            'event'     => 'form_submission',
            'to'        => '{admin_email}',
            'toType'    => 'email',
            'bcc'       => $bcc,
            'replyTo'   => '{' . $email_field_id . '}',
            'subject'   => 'New enquiry from {form_title}',
            'message'   => '{all_fields}',
        ]];
    };
    // Page-redirect confirmation to the Thank You page.
    $confirmation = function () use ($ty_url) {
        $cid = uniqid();
        return [$cid => [
            'id'        => $cid,
            'name'      => 'Default Confirmation',
            'isDefault' => true,
            'type'      => 'redirect',
            'url'       => $ty_url,
        ]];
    };

    $exposure = [
        'title'          => 'Exposure Review',
        'description'    => '',
        'enableHoneypot' => true,
        'button'         => ['type' => 'text', 'text' => 'Book My Exposure Review'],
        'fields'         => [
            ['id' => 1, 'type' => 'text',  'label' => 'Name',    'isRequired' => true],
            ['id' => 2, 'type' => 'text',  'label' => 'Company'],
            ['id' => 3, 'type' => 'email', 'label' => 'Email',   'isRequired' => true],
            ['id' => 4, 'type' => 'phone', 'label' => 'Phone',   'phoneFormat' => 'international'],
            ['id' => 5, 'type' => 'select','label' => 'Your role in the supply chain', 'isRequired' => true,
                'choices' => [
                    ['text' => 'We send or receive freight',                'value' => 'We send or receive freight'],
                    ['text' => 'We provide transport or logistics services','value' => 'We provide transport or logistics services'],
                    ['text' => 'We operate heavy vehicles',                 'value' => 'We operate heavy vehicles'],
                    ['text' => 'Not sure',                                  'value' => 'Not sure'],
                ]],
        ],
        'notifications'  => $notification(3),
        'confirmations'  => $confirmation(),
    ];

    $contact = [
        'title'          => 'Contact',
        'description'    => '',
        'enableHoneypot' => true,
        'button'         => ['type' => 'text', 'text' => 'Send Message'],
        'fields'         => [
            ['id' => 1, 'type' => 'text',     'label' => 'Name',    'isRequired' => true],
            ['id' => 2, 'type' => 'text',     'label' => 'Company'],
            ['id' => 3, 'type' => 'email',    'label' => 'Email',   'isRequired' => true],
            ['id' => 4, 'type' => 'phone',    'label' => 'Phone',   'phoneFormat' => 'international'],
            ['id' => 5, 'type' => 'textarea', 'label' => 'Message'],
        ],
        'notifications'  => $notification(3),
        'confirmations'  => $confirmation(),
    ];

    $exposure_id = GFAPI::add_form($exposure);
    $contact_id  = GFAPI::add_form($contact);

    if (!is_wp_error($exposure_id) && !is_wp_error($contact_id) && function_exists('update_field')) {
        // Front page = the seeded landing page; that is where the form-ID fields live.
        $page_id = (int) get_option('page_on_front');
        if (!$page_id) {
            $found = get_posts([
                'post_type' => 'page', 'post_status' => 'any', 'numberposts' => 1,
                'meta_key' => '_wp_page_template', 'meta_value' => 'page-templates/landing-page.php',
            ]);
            $page_id = $found ? $found[0]->ID : 0;
        }
        if ($page_id) {
            update_field('hero_form_id', $exposure_id, $page_id);
            update_field('contact_form_id', $contact_id, $page_id);
            echo "gravity forms created (exposure $exposure_id, contact $contact_id) and wired into page $page_id\n";
            $mark('gf-created');
        } else {
            echo "forms created but landing page not found yet; will wire next deploy\n";
        }
    } else {
        $err = is_wp_error($exposure_id) ? $exposure_id->get_error_message()
             : (is_wp_error($contact_id) ? $contact_id->get_error_message() : 'ACF update_field unavailable');
        echo "hvnladvisory form setup failed: $err\n";
    }
} elseif ($slug === 'hvnladvisory' && !$done('gf-created')) {
    echo "Gravity Forms not active yet; HVNL forms will be created once it is\n";
}

echo "provisioning pass complete\n";
