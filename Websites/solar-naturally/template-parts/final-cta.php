<?php
/**
 * Final CTA — closing full-bleed band with the assessment form repeated.
 * ACF tab: Final CTA
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow = get_field('finalcta_eyebrow') ?: "Let's start";
$heading = get_field('finalcta_heading') ?: "Ready to take control of your<br>power bills before the next rebate cut?";
$lede    = get_field('finalcta_lede') ?: "Tell us a bit about your home and we'll come back with a fast, suitable and honest assessment. Solar and battery systems for Bunbury and the South West, installed properly by a local team.";
$points  = get_field('finalcta_points');
$bg      = get_field('finalcta_background_image');

if (!$points) {
    $points = [
        ['text' => 'Local in-house team'],
        ['text' => 'Honest, written figures'],
    ];
}
?>

<section class="section fc" id="start">
    <div class="fc__bg">
        <?php echo sn_image_or_default($bg, 'gallery/residential/riverway.jpg', 'sn-hero', 'fc__img js-parallax', [
            'data-speed' => '0.1',
            'data-scale' => '1.16',
            'loading'    => 'lazy',
        ]); ?>
        <span class="fc__veil"></span>
    </div>
    <div class="wrap fc__in">
        <div class="fc__copy">
            <div class="eyebrow-row" data-rise="rise"><span class="tick"></span><span class="eyebrow" style="color:var(--gold)"><?php echo esc_html($eyebrow); ?></span></div>
            <h2 class="fc__h2" data-rise="rise" style="animation-delay:80ms"><?php echo wp_kses($heading, ['br' => []]); ?></h2>
            <p class="fc__lede" data-rise="rise" style="animation-delay:160ms"><?php echo esc_html($lede); ?></p>
            <div class="fc__pts" data-rise="rise" style="animation-delay:240ms">
                <?php foreach ($points as $p) : ?>
                    <span class="fc__pt"><?php echo sn_icon('check', 16); ?> <?php echo esc_html($p['text']); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="fc__panel" data-rise="rise" style="animation-delay:120ms">
            <div class="af-wrap"><?php sn_render_gravity_form(sn_assessment_form_id()); ?></div>
        </div>
    </div>
</section>
