<?php
/**
 * Hero — full-bleed photographic hero with parallax background,
 * beams effect, copy column and award seal.
 * ACF tab: Hero
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow      = get_field('hero_eyebrow') ?: 'Bunbury · Solar & Battery Packages';
$headline     = get_field('hero_headline') ?: "Stop renting power<br>from the grid.<br><em>Start owning it.</em>";
$lede         = get_field('hero_lede') ?: 'Solar and battery systems installed by our own in-house team across Bunbury and the South West, in as little as four to six weeks. Backed by 18 years of experience and full system warranties.';
$cta_primary  = get_field('hero_primary_cta_label') ?: 'Get my free assessment';
$cta_secondary = get_field('hero_secondary_cta_label') ?: 'See our work';
$trust_left   = get_field('hero_trust_left') ?: 'Trusted by <b>65,000+</b> Australian families';
$trust_right  = get_field('hero_trust_right') ?: '<b>Since 2008</b> · in-house certified crew';
$bg_image     = get_field('hero_background_image');
$mobile_image = get_field('hero_mobile_image');
$seal_image   = get_field('hero_seal_image');
?>

<section class="hero grain" id="top">
    <?php if ($mobile_image && !empty($mobile_image['ID'])) : ?>
        <picture class="hero__mobile">
            <source media="(min-width: 769px)" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
            <?php echo wp_get_attachment_image((int) $mobile_image['ID'], 'sn-card', false, [
                'class'         => 'hero__mobile-img',
                'alt'           => esc_attr($mobile_image['alt'] ?? ''),
                'fetchpriority' => 'high',
                'loading'       => 'eager',
            ]); ?>
        </picture>
    <?php endif; ?>

    <div class="hero__bg">
        <?php echo sn_image_or_default($bg_image, 'scenario/home-exterior.jpg', 'sn-hero', 'hero__img js-parallax', [
            'data-speed'    => '0.1',
            'data-scale'    => '1.14',
            'fetchpriority' => 'high',
        ]); ?>
        <span class="hero__veil"></span>
    </div>
    <div class="beams beams--hero js-beams" data-palette="warm" data-intensity="0.5" aria-hidden="true"><canvas class="beams__c"></canvas></div>

    <div class="wrap hero__in">
        <div class="hero__copy">
            <div class="eyebrow-row" data-rise="rise" style="animation-delay:40ms"><span class="tick"></span><span class="eyebrow" style="color:var(--gold)"><?php echo esc_html($eyebrow); ?></span></div>
            <h1 class="hero__h1" data-rise="rise" style="animation-delay:120ms"><?php echo wp_kses($headline, ['br' => [], 'em' => [], 'b' => [], 'strong' => []]); ?></h1>
            <p class="hero__lede" data-rise="rise" style="animation-delay:220ms"><?php echo esc_html($lede); ?></p>
            <div class="hero__cta" data-rise="rise" style="animation-delay:320ms">
                <button class="btn btn--gold js-open-assess"><?php echo esc_html($cta_primary); ?> <span class="arw">&rarr;</span></button>
                <a class="btn btn--line-inv js-scroll-link" href="#gallery"><?php echo esc_html($cta_secondary); ?></a>
            </div>
            <div class="hero__trust" data-rise="rise" style="animation-delay:420ms">
                <span><?php echo sn_stars(); ?> <?php echo wp_kses($trust_left, ['b' => [], 'strong' => []]); ?></span>
                <span class="hero__sep"></span>
                <span><?php echo wp_kses($trust_right, ['b' => [], 'strong' => []]); ?></span>
            </div>
        </div>
        <div class="hero__seal" data-rise="fade" style="animation-delay:300ms">
            <?php echo sn_image_or_default($seal_image, 'awards/sunwiz-no1-wa-2025.png', 'medium', '', [
                'width'  => '170',
                'height' => '170',
            ]); ?>
        </div>
    </div>
    <button class="hero__scroll js-scroll-link" data-target="#whynow" aria-label="Scroll down">
        <span>Scroll</span><?php echo sn_icon('chevron-down', 18); ?>
    </button>
</section>
