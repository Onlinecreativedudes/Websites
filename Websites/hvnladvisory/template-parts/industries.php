<?php
/**
 * Industries — parallax photo band with four numbered glass cards.
 * ACF tab: Industries (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$image    = get_field('ind_image');
$kicker   = get_field('ind_kicker');
$headline = get_field('ind_headline');
$intro    = get_field('ind_intro');
$cards    = get_field('ind_cards');

if (!$headline && !$cards) { return; }
?>

<section class="industries band band--dark">
    <?php if ($image && !empty($image['ID'])) : ?>
        <div class="band__bg" data-parallax data-speed="0.18">
            <?php echo ocd_render_image($image, 'hero', 'band__bg-img band__bg-img--mid'); ?>
        </div>
        <div class="band__shade band__shade--ttb" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="band__inner container container--wide">
        <div class="section__head section__head--on-dark">
            <div>
                <?php ocd_kicker($kicker, 'gold'); ?>
                <h2 class="section__headline"><?php echo ocd_kses_headline($headline); ?></h2>
            </div>
            <?php if ($intro) : ?>
                <p class="section__intro"><?php echo esc_html($intro); ?></p>
            <?php endif; ?>
        </div>

        <?php if ($cards) : ?>
            <div class="card-grid card-grid--4 card-grid--glass" data-stagger>
                <?php foreach ($cards as $i => $card) : ?>
                    <div class="card card--glass">
                        <span class="card__number card__number--gold"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                        <h3 class="card__title card__title--sm"><?php echo esc_html($card['title']); ?></h3>
                        <p class="card__text"><?php echo esc_html($card['text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
