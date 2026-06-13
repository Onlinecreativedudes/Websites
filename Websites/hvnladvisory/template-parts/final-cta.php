<?php
/**
 * Final CTA — parallax photo band with the closing pitch.
 * ACF tab: Final CTA (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$image     = get_field('final_image');
$headline  = get_field('final_headline');
$text      = get_field('final_text');
$cta_label = get_field('final_cta_label');

if (!$headline) { return; }
?>

<section class="final-cta band band--dark band--slim">
    <?php if ($image && !empty($image['ID'])) : ?>
        <div class="band__bg" data-parallax data-speed="0.18">
            <?php echo ocd_render_image($image, 'hero', 'band__bg-img'); ?>
        </div>
        <div class="band__shade band__shade--ltr" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="band__inner container container--wide">
        <h2 class="final-cta__headline"><?php echo ocd_kses_headline($headline); ?></h2>
        <?php if ($text) : ?>
            <p class="final-cta__text"><?php echo esc_html($text); ?></p>
        <?php endif; ?>
        <div class="final-cta__actions">
            <?php ocd_book_cta($cta_label, 'btn btn--light btn--lg'); ?>
            <?php ocd_phone_link('phone-link phone-link--light'); ?>
        </div>
    </div>
</section>
