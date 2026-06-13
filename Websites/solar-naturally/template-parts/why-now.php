<?php
/**
 * Why Now — urgency copy with four proof points.
 * ACF tab: Why Now
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow  = get_field('whynow_eyebrow') ?: 'Why move now';
$heading  = get_field('whynow_heading') ?: "Why Bunbury homeowners<br>are moving now";
$lede     = get_field('whynow_lede') ?: "Electricity prices keep rising, the current government incentive won't last, and winter heating is about to send your bill skyrocketing. Take advantage of the rebate before power prices climb again from 1 July.";
$cta      = get_field('whynow_cta_label') ?: 'Lock in the rebate';
$points   = get_field('whynow_points');

if (!$points) {
    $points = [
        ['icon' => 'users',        'title' => 'In-house, certified crew',     'text' => 'Every install is done by our own electricians. No subcontractors handed your job.'],
        ['icon' => 'home',         'title' => 'Over 65,000 families served',  'text' => 'Eighteen years and tens of thousands of Australian homes powered properly.'],
        ['icon' => 'timer',        'title' => 'Fast turnaround',              'text' => 'Most systems designed, approved and switched on in four to six weeks.'],
        ['icon' => 'shield-check', 'title' => 'Full system warranties',       'text' => 'Panels, battery and workmanship — covered and put in writing.'],
    ];
}
?>

<section class="section wn" id="whynow">
    <div class="wrap wn__in">
        <div class="wn__lead">
            <div class="eyebrow-row" data-rise="rise"><span class="tick"></span><span class="eyebrow"><?php echo esc_html($eyebrow); ?></span></div>
            <h2 class="wn__h2" data-rise="rise" style="animation-delay:80ms"><?php echo wp_kses($heading, ['br' => []]); ?></h2>
            <p class="lede wn__p" data-rise="rise" style="animation-delay:160ms"><?php echo esc_html($lede); ?></p>
            <div data-rise="rise" style="animation-delay:240ms"><button class="btn js-open-assess"><?php echo esc_html($cta); ?> <span class="arw">&rarr;</span></button></div>
        </div>
        <div class="wn__list">
            <?php foreach ($points as $i => $p) : ?>
                <div class="wn__item" data-rise="rise" style="animation-delay:<?php echo (int) ($i * 90); ?>ms">
                    <span class="wn__ico"><?php echo sn_icon($p['icon'] ?: 'check', 24); ?></span>
                    <div>
                        <h3 class="wn__it"><?php echo esc_html($p['title']); ?></h3>
                        <p class="wn__id"><?php echo esc_html($p['text']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
