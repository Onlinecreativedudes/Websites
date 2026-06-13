<?php
/**
 * Services — six numbered cards on the darker parchment band.
 * ACF tab: Services (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$kicker   = get_field('services_kicker');
$headline = get_field('services_headline');
$intro    = get_field('services_intro');
$items    = get_field('services_items');

if (!$headline && !$items) { return; }
?>

<section class="services section section--alt" id="services">
    <div class="container container--wide">
        <?php ocd_kicker($kicker); ?>

        <div class="section__head">
            <h2 class="section__headline"><?php echo ocd_kses_headline($headline); ?></h2>
            <?php if ($intro) : ?>
                <p class="section__intro"><?php echo esc_html($intro); ?></p>
            <?php endif; ?>
        </div>

        <?php if ($items) : ?>
            <div class="card-grid card-grid--3" data-stagger>
                <?php foreach ($items as $i => $item) : ?>
                    <div class="card card--numbered">
                        <span class="card__number"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                        <h3 class="card__title"><?php echo esc_html($item['title']); ?></h3>
                        <p class="card__text"><?php echo esc_html($item['text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
