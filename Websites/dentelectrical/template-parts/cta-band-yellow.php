<?php
/**
 * Mid-page CTA band (yellow)
 * ACF group: group_ctayellow_landing
 */
if (!defined('ABSPATH')) { exit; }

$headline = get_field('cta_yellow_headline');
$copy     = get_field('cta_yellow_copy');
$cta      = get_field('cta_yellow_cta');

if (!$headline) { return; }
?>

<section class="cta-band cta-band--yellow">
    <div class="cta-band__deco cta-band__deco--tr" aria-hidden="true"></div>
    <div class="cta-band__deco cta-band__deco--bl" aria-hidden="true"></div>

    <div class="container">
        <div class="cta-band__inner">
            <h2 class="cta-band__headline reveal"><?php echo wp_kses_post($headline); ?></h2>
            <div class="cta-band__aside reveal reveal--delay-1">
                <?php if ($copy) : ?>
                    <p><?php echo esc_html($copy); ?></p>
                <?php endif; ?>
                <?php ocd_render_link($cta, 'btn btn--dark'); ?>
            </div>
        </div>
    </div>
</section>
