<?php
/**
 * How we work — dark band with five numbered process steps.
 * ACF tab: How We Work (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$kicker   = get_field('how_kicker');
$headline = get_field('how_headline');
$intro    = get_field('how_intro');
$steps    = get_field('how_steps');

if (!$headline && !$steps) { return; }
?>

<section class="how section section--dark" id="how">
    <div class="container container--wide">
        <?php ocd_kicker($kicker, 'gold'); ?>

        <div class="section__head section__head--on-dark">
            <h2 class="section__headline"><?php echo ocd_kses_headline($headline); ?></h2>
            <?php if ($intro) : ?>
                <p class="section__intro"><?php echo esc_html($intro); ?></p>
            <?php endif; ?>
        </div>

        <?php if ($steps) : ?>
            <div class="steps" data-stagger>
                <?php foreach ($steps as $i => $step) : ?>
                    <div class="steps__item">
                        <span class="steps__number"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                        <h3 class="steps__title"><?php echo esc_html($step['title']); ?></h3>
                        <p class="steps__text"><?php echo esc_html($step['text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
