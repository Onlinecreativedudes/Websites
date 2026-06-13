<?php
/**
 * Why Choose — full-bleed photographic band with stat and awards.
 * ACF tab: Why Choose
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow    = get_field('whychoose_eyebrow') ?: 'Why Solar Naturally';
$heading    = get_field('whychoose_heading') ?: "Why Australia chooses<br>Solar Naturally";
$lede       = get_field('whychoose_lede') ?: "Plenty of companies will sell you the cheapest panels on the market and disappear once the job's done. That's not us. We install systems built to perform for years, installed by our fully qualified in-house team of electricians, and we put our production figures in writing so you know exactly what you're getting before you sign anything.";
$script_line = get_field('whychoose_script_line') ?: "We're locally based, and we've built our name on doing the job properly the first time, every time.";
$cta        = get_field('whychoose_cta_label') ?: 'Talk to our team';
$stat_number = get_field('whychoose_stat_number') ?: '18';
$stat_label = get_field('whychoose_stat_label') ?: "years doing the job<br>properly, first time";
$bg_image   = get_field('whychoose_background_image');
$awards     = get_field('whychoose_awards');
?>

<section class="wc" id="why">
    <div class="wc__bg">
        <?php echo sn_image_or_default($bg_image, 'gallery/residential/swan.jpg', 'sn-hero', 'wc__img js-parallax', [
            'data-speed' => '0.14',
            'data-scale' => '1.22',
            'loading'    => 'lazy',
        ]); ?>
        <span class="wc__veil"></span>
    </div>
    <div class="wrap wc__in">
        <div class="wc__copy">
            <div class="eyebrow-row" data-rise="rise"><span class="tick"></span><span class="eyebrow" style="color:var(--gold)"><?php echo esc_html($eyebrow); ?></span></div>
            <h2 class="wc__h2" data-rise="rise" style="animation-delay:80ms"><?php echo wp_kses($heading, ['br' => []]); ?></h2>
            <p class="lede wc__lede" data-rise="rise" style="animation-delay:160ms"><?php echo esc_html($lede); ?></p>
            <p class="wc__hand script" data-rise="rise" style="animation-delay:220ms"><?php echo esc_html($script_line); ?></p>
            <div data-rise="rise" style="animation-delay:260ms"><button class="btn btn--gold wc__contact js-open-assess"><?php echo esc_html($cta); ?> <span class="arw">&rarr;</span></button></div>
            <div class="wc__foot">
                <div class="wc__stat" data-rise="rise" style="animation-delay:260ms">
                    <span class="wc__sn"><?php echo esc_html($stat_number); ?></span>
                    <span class="wc__sl"><?php echo wp_kses($stat_label, ['br' => []]); ?></span>
                </div>
                <div class="wc__awards" data-rise="fade" style="animation-delay:340ms">
                    <?php if ($awards) : ?>
                        <?php foreach ($awards as $a) : ?>
                            <?php echo sn_render_image($a['image'], 'medium', '', ['loading' => 'lazy']); ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url(SN_THEME_URI); ?>/assets/img/awards/fast100-2020-light.png" alt="AFR Fast 100 2020" width="74" height="74" loading="lazy">
                        <img src="<?php echo esc_url(SN_THEME_URI); ?>/assets/img/awards/fast100-2018-light.png" alt="AFR Fast 100 2018" width="74" height="74" loading="lazy">
                        <img src="<?php echo esc_url(SN_THEME_URI); ?>/assets/img/awards/faststarters-2018-light.png" alt="AFR Fast Starters 2018" width="74" height="74" loading="lazy">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
