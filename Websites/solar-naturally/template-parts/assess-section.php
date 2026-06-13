<?php
/**
 * Assessment — copy column plus the Gravity Forms assessment form.
 * ACF tab: Assessment
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow  = get_field('assess_eyebrow') ?: 'Free assessment';
$heading  = get_field('assess_heading') ?: "Get your solar<br>& battery assessment";
$lede     = get_field('assess_lede') ?: 'No pressure. No obligation. Just an honest look at what your roof could save you.';
$points   = get_field('assess_points');
$seal     = get_field('assess_seal_image');
$seal_caption = get_field('assess_seal_caption') ?: "Western Australia's<br><b>No.1 rated</b> solar retailer, 2025";

if (!$points) {
    $points = [
        ['icon' => 'ruler',      'text' => 'Sized to your real usage, a tailormade solution'],
        ['icon' => 'file-check', 'text' => 'Production figures in writing before you sign'],
        ['icon' => 'hard-hat',   'text' => 'No subcontractors'],
    ];
}
?>

<section class="section as" id="assess">
    <div class="beams js-beams" data-palette="gold" data-intensity="0.6" aria-hidden="true"><canvas class="beams__c"></canvas></div>
    <div class="wrap as__in">
        <div class="as__copy">
            <div class="eyebrow-row" data-rise="rise"><span class="tick"></span><span class="eyebrow"><?php echo esc_html($eyebrow); ?></span></div>
            <h2 class="as__h2" data-rise="rise" style="animation-delay:80ms"><?php echo wp_kses($heading, ['br' => []]); ?></h2>
            <p class="lede as__p" data-rise="rise" style="animation-delay:160ms"><?php echo esc_html($lede); ?></p>
            <div class="as__list" data-rise="rise" style="animation-delay:220ms">
                <?php foreach ($points as $p) : ?>
                    <div class="as__li"><?php echo sn_icon($p['icon'] ?: 'check', 20); ?><span><?php echo esc_html($p['text']); ?></span></div>
                <?php endforeach; ?>
            </div>
            <div class="as__seal" data-rise="fade" style="animation-delay:320ms">
                <?php echo sn_image_or_default($seal, 'awards/sunwiz-no1-wa-2025.png', 'medium', '', ['width' => '92', 'height' => '92', 'loading' => 'lazy']); ?>
                <span><?php echo wp_kses($seal_caption, ['br' => [], 'b' => [], 'strong' => []]); ?></span>
            </div>
        </div>
        <div class="as__panel" data-rise="rise" style="animation-delay:120ms">
            <div class="af-wrap"><?php sn_render_gravity_form(sn_assessment_form_id()); ?></div>
        </div>
    </div>
</section>
