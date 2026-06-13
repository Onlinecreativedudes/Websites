<?php
/**
 * Blind spots — three numbered cards plus a closing CTA row.
 * ACF tab: Blind Spots (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$kicker    = get_field('bs_kicker');
$headline  = get_field('bs_headline');
$intro     = get_field('bs_intro');
$cards     = get_field('bs_cards');
$cta_text  = get_field('bs_cta_text');
$cta_label = get_field('bs_cta_label');

if (!$headline && !$cards) { return; }
?>

<section class="blind-spots section">
    <div class="container container--wide">
        <?php ocd_kicker($kicker); ?>

        <div class="section__head">
            <h2 class="section__headline"><?php echo ocd_kses_headline($headline); ?></h2>
            <?php if ($intro) : ?>
                <p class="section__intro"><?php echo esc_html($intro); ?></p>
            <?php endif; ?>
        </div>

        <?php if ($cards) : ?>
            <div class="card-grid card-grid--3" data-stagger>
                <?php foreach ($cards as $i => $card) : ?>
                    <div class="card card--numbered">
                        <span class="card__number card__number--lg"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                        <h3 class="card__title"><?php echo esc_html($card['title']); ?></h3>
                        <p class="card__text"><?php echo esc_html($card['text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($cta_text || $cta_label) : ?>
            <div class="blind-spots__cta">
                <?php if ($cta_text) : ?>
                    <p class="blind-spots__cta-text"><?php echo esc_html($cta_text); ?></p>
                <?php endif; ?>
                <?php ocd_book_cta($cta_label, 'btn btn--dark'); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
