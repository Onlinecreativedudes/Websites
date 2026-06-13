<?php
/**
 * CTA band — "the cost of waiting" full-bleed band with Ken Burns image.
 * ACF tab: CTA Band
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow = get_field('ctaband_eyebrow') ?: 'The cost of waiting';
$heading = get_field('ctaband_heading') ?: "Every delay means the<br>grid keeps the money.";
$text    = get_field('ctaband_text') ?: "Your roof could be paying you back instead. Find out what it's worth in one free assessment.";
$cta     = get_field('ctaband_cta_label') ?: 'Get my free assessment';
$bg      = get_field('ctaband_background_image');
?>

<section class="cta1 grain">
    <div class="cta1__bg">
        <?php echo sn_image_or_default($bg, 'gallery/residential/coombe.jpg', 'sn-hero', 'kb', ['aria-hidden' => 'true', 'loading' => 'lazy']); ?>
        <span class="cta1__veil"></span>
    </div>
    <div class="beams beams--hero js-beams" data-palette="warm" data-intensity="0.45" aria-hidden="true"><canvas class="beams__c"></canvas></div>
    <div class="wrap cta1__in">
        <div class="cta1__eyebrow" data-rise="rise"><span class="eyebrow" style="color:var(--gold)"><?php echo esc_html($eyebrow); ?></span></div>
        <h2 class="cta1__h2" data-rise="rise" style="animation-delay:80ms"><?php echo wp_kses($heading, ['br' => []]); ?></h2>
        <div class="cta1__r" data-rise="rise" style="animation-delay:180ms">
            <p><?php echo esc_html($text); ?></p>
            <button class="btn btn--gold js-open-assess"><?php echo esc_html($cta); ?> <span class="arw">&rarr;</span></button>
        </div>
    </div>
</section>
