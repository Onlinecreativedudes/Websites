<?php
/**
 * Mid-page CTA band (blue, with photo)
 * ACF group: group_ctablueblue_landing
 */
if (!defined('ABSPATH')) { exit; }

$headline = get_field('cta_blue_headline');
$copy     = get_field('cta_blue_copy');
$cta      = get_field('cta_blue_cta');
$photo    = get_field('cta_blue_photo');

if (!$headline) { return; }
?>

<section class="cta-band cta-band--blue cta-band--has-photo">
    <?php if ($photo && !empty($photo['ID'])) : ?>
        <div class="cta-band__photo">
            <?php echo wp_get_attachment_image($photo['ID'], 'hero', false, [
                'class' => 'cta-band__photo-img',
                'alt'   => '',
            ]); ?>
        </div>
    <?php endif; ?>

    <div class="cta-band__deco cta-band__deco--tr" aria-hidden="true"></div>
    <div class="cta-band__deco cta-band__deco--bl" aria-hidden="true"></div>

    <div class="container">
        <div class="cta-band__inner">
            <h2 class="cta-band__headline reveal"><?php echo wp_kses_post($headline); ?></h2>
            <div class="cta-band__aside reveal reveal--delay-1">
                <?php if ($copy) : ?>
                    <p><?php echo esc_html($copy); ?></p>
                <?php endif; ?>
                <?php ocd_render_link($cta, 'btn btn--primary'); ?>
            </div>
        </div>
    </div>
</section>
