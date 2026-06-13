<?php
/**
 * CTA banner 1 — "the offer": dark parallax band explaining the exposure review.
 * ACF tab: Offer Banner (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$image     = get_field('offer_image');
$kicker    = get_field('offer_kicker');
$headline  = get_field('offer_headline');
$intro     = get_field('offer_intro');
$ticks     = get_field('offer_ticks');
$cta_label = get_field('offer_cta_label');
$microcopy = get_field('offer_microcopy');

if (!$headline) { return; }
?>

<section class="cta-offer band band--dark">
    <?php if ($image && !empty($image['ID'])) : ?>
        <div class="band__bg" data-parallax data-speed="0.17">
            <?php echo ocd_render_image($image, 'hero', 'band__bg-img'); ?>
        </div>
        <div class="band__shade band__shade--ltr" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="band__inner container container--wide">
        <div class="cta-offer__grid">
            <div>
                <?php ocd_kicker($kicker, 'gold'); ?>
                <h2 class="section__headline"><?php echo ocd_kses_headline($headline); ?></h2>
            </div>
            <div>
                <?php if ($intro) : ?>
                    <p class="cta-offer__intro"><?php echo esc_html($intro); ?></p>
                <?php endif; ?>
                <?php if ($ticks) : ?>
                    <ul class="ticks ticks--gold">
                        <?php foreach ($ticks as $tick) : if (empty($tick['label'])) { continue; } ?>
                            <li><?php echo ocd_tick(); ?><?php echo esc_html($tick['label']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php ocd_book_cta($cta_label, 'btn btn--light'); ?>
                <?php if ($microcopy) : ?>
                    <p class="cta-offer__microcopy"><?php echo esc_html($microcopy); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
