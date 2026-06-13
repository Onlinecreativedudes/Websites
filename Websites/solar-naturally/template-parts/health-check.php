<?php
/**
 * Health Check — copy plus captioned media card.
 * ACF tab: Health Check
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow = get_field('healthcheck_eyebrow') ?: 'System health check';
$heading = get_field('healthcheck_heading') ?: "Old system costing you<br>more than it saves?";
$lede    = get_field('healthcheck_lede') ?: "Ageing panels and failing inverters quietly bleed money. You keep paying rising grid prices while a system that should be working for you sits at half output. We assess what you've got, tell you straight whether it's worth upgrading or replacing, and size the right solution for your home.";
$cta     = get_field('healthcheck_cta_label') ?: 'Book a free health check';
$image   = get_field('healthcheck_image');
$caption = get_field('healthcheck_caption') ?: 'Upgrade or replace — we tell you straight';
?>

<section class="section hc">
    <div class="wrap hc__in">
        <div class="hc__copy">
            <div class="eyebrow-row" data-rise="rise"><span class="tick"></span><span class="eyebrow"><?php echo esc_html($eyebrow); ?></span></div>
            <h2 class="hc__h2" data-rise="rise" style="animation-delay:80ms"><?php echo wp_kses($heading, ['br' => []]); ?></h2>
            <p class="lede" data-rise="rise" style="animation-delay:160ms"><?php echo esc_html($lede); ?></p>
            <div data-rise="rise" style="animation-delay:240ms"><button class="btn btn--line js-open-assess"><?php echo esc_html($cta); ?> <span class="arw">&rarr;</span></button></div>
        </div>
        <div class="hc__media" data-rise="fade" style="animation-delay:120ms">
            <?php echo sn_image_or_default($image, 'scenario/healthcheck.jpg', 'sn-gallery', '', ['loading' => 'lazy']); ?>
            <span class="hc__cap"><?php echo sn_icon('search-check', 16); ?> <?php echo esc_html($caption); ?></span>
        </div>
    </div>
</section>
