<?php
/**
 * Why choose us — six cards with the short gold rule, on the darker parchment band.
 * ACF tab: Why Us (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$kicker   = get_field('why_kicker');
$headline = get_field('why_headline');
$cards    = get_field('why_cards');

if (!$headline && !$cards) { return; }
?>

<section class="why-us section section--alt">
    <div class="container container--wide">
        <?php ocd_kicker($kicker); ?>
        <h2 class="section__headline section__headline--solo"><?php echo ocd_kses_headline($headline); ?></h2>

        <?php if ($cards) : ?>
            <div class="card-grid card-grid--3" data-stagger>
                <?php foreach ($cards as $card) : ?>
                    <div class="card card--ruled">
                        <span class="card__rule" aria-hidden="true"></span>
                        <h3 class="card__title card__title--sm"><?php echo esc_html($card['title']); ?></h3>
                        <p class="card__text"><?php echo esc_html($card['text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
