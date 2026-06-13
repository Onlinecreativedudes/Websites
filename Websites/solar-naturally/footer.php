<?php
if (!defined('ABSPATH')) { exit; }

$logo_light  = get_field('logo_light', 'option');
$tagline     = get_field('footer_tagline', 'option') ?: '"Working toward a cleaner future."';
$phone       = sn_site_phone_display() ?: '1300 168 138';
$email       = get_field('email_address', 'option') ?: 'sales@solarnaturally.com.au';
$service_area = get_field('service_area', 'option') ?: 'Bunbury & the South West, WA';
$footer_links = get_field('footer_links', 'option');
$cta_heading = get_field('footer_cta_heading', 'option') ?: 'Beat the 1 July price rise';
$cta_text    = get_field('footer_cta_text', 'option') ?: "Lock in the current rebate before it's gone.";
$fine_print  = get_field('footer_fine_print', 'option');
$modal_eyebrow  = get_field('modal_eyebrow', 'option') ?: 'Free assessment';
$modal_heading  = get_field('modal_heading', 'option') ?: 'Get your solar & battery assessment';
$modal_text     = get_field('modal_text', 'option') ?: 'No pressure, no obligation — an honest look at what your roof could save you.';

if (!$footer_links) {
    $footer_links = [
        ['label' => 'Packages',        'anchor' => '#services'],
        ['label' => 'Battery storage', 'anchor' => '#services'],
        ['label' => 'Projects',        'anchor' => '#gallery'],
        ['label' => 'Why us',          'anchor' => '#why'],
        ['label' => 'Reviews',         'anchor' => '#reviews'],
        ['label' => 'Free assessment', 'anchor' => '#assess'],
    ];
}

$logo_light_url = ($logo_light && !empty($logo_light['url'])) ? $logo_light['url'] : SN_THEME_URI . '/assets/img/logo-light.png';
?>

<footer class="ft">
    <div class="beams js-beams" data-palette="green" data-intensity="0.4" aria-hidden="true"><canvas class="beams__c"></canvas></div>
    <div class="wrap ft__in">
        <div class="ft__brand" data-rise="rise">
            <img class="ft__logo" src="<?php echo esc_url($logo_light_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="190" height="44">
            <p class="ft__tag script"><?php echo esc_html($tagline); ?></p>
            <div class="ft__awards">
                <img src="<?php echo esc_url(SN_THEME_URI); ?>/assets/img/awards/sunwiz-no1-wa-2025.png" alt="No.1 Solar Retailer WA 2025" width="92" height="92" loading="lazy">
                <img src="<?php echo esc_url(SN_THEME_URI); ?>/assets/img/awards/fast100-2020-light.png" alt="AFR Fast 100 2020" width="92" height="92" loading="lazy">
            </div>
        </div>
        <div class="ft__col" data-rise="rise" style="animation-delay:80ms">
            <h4>Explore</h4>
            <ul>
                <?php foreach ($footer_links as $link) :
                    if (empty($link['label']) || empty($link['anchor'])) { continue; }
                ?>
                    <li><a href="<?php echo esc_attr($link['anchor']); ?>" class="js-scroll-link"><?php echo esc_html($link['label']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="ft__col" data-rise="rise" style="animation-delay:160ms">
            <h4>Get in touch</h4>
            <ul class="ft__contact">
                <li><?php echo sn_icon('phone', 15); ?> <?php echo esc_html($phone); ?></li>
                <li><?php echo sn_icon('mail', 15); ?> <?php echo esc_html($email); ?></li>
                <li><?php echo sn_icon('map-pin', 15); ?> <?php echo esc_html($service_area); ?></li>
            </ul>
        </div>
        <div class="ft__col ft__cta" data-rise="rise" style="animation-delay:240ms">
            <h4><?php echo esc_html($cta_heading); ?></h4>
            <p><?php echo esc_html($cta_text); ?></p>
            <button class="btn btn--gold js-open-assess">Free assessment <span class="arw">&rarr;</span></button>
        </div>
    </div>
    <div class="wrap ft__bottom">
        <span>&copy; <?php echo esc_html(date('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?>. All rights reserved.</span>
        <?php if ($fine_print) : ?>
            <span class="ft__fine"><?php echo esc_html($fine_print); ?></span>
        <?php endif; ?>
    </div>
</footer>

<div class="amodal js-assess-modal" hidden>
    <div class="amodal__panel" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr($modal_heading); ?>">
        <button class="amodal__close js-close-assess" aria-label="Close"><?php echo sn_icon('x', 22); ?></button>
        <div class="amodal__head">
            <span class="eyebrow" style="color:var(--green-deep)"><?php echo esc_html($modal_eyebrow); ?></span>
            <h3 class="amodal__h"><?php echo esc_html($modal_heading); ?></h3>
            <p class="amodal__p"><?php echo esc_html($modal_text); ?></p>
        </div>
        <div class="af-wrap"><?php sn_render_gravity_form(sn_assessment_form_id()); ?></div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
