<?php
/**
 * CTA band two (parallax image band, two actions).
 * ACF tab: CTA Band Two (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow   = get_field('band_two_eyebrow');
$headline  = get_field('band_two_headline');
$copy      = get_field('band_two_copy');
$primary   = get_field('band_two_primary_cta');
$secondary = get_field('band_two_secondary_cta');
$image     = get_field('band_two_image');

if (!$headline) { return; }

$bg = ($image && !empty($image['url'])) ? ($image['sizes']['hero'] ?? $image['url']) : '';
?>

<section class="band"<?php if ($bg) : ?> style="background-image:url(<?php echo esc_url($bg); ?>)"<?php endif; ?>>
    <div class="container reveal">
        <?php if ($eyebrow) : ?><span class="eyebrow center on-dark"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
        <h2><?php echo wp_kses_post($headline); ?></h2>
        <?php if ($copy) : ?><p><?php echo esc_html($copy); ?></p><?php endif; ?>
        <div class="band-actions">
            <?php
            ocd_render_link($primary, 'btn on-dark');
            ocd_render_link($secondary, 'btn outline on-dark');
            ?>
        </div>
    </div>
</section>
