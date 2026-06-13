<?php
/**
 * CTA band one (parallax image band).
 * ACF tab: CTA Band One (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow  = get_field('band_one_eyebrow');
$headline = get_field('band_one_headline');
$copy     = get_field('band_one_copy');
$cta      = get_field('band_one_cta');
$image    = get_field('band_one_image');

if (!$headline) { return; }

$bg = ($image && !empty($image['url'])) ? ($image['sizes']['hero'] ?? $image['url']) : '';
?>

<section class="band"<?php if ($bg) : ?> style="background-image:url(<?php echo esc_url($bg); ?>)"<?php endif; ?>>
    <div class="container reveal">
        <?php if ($eyebrow) : ?><span class="eyebrow center on-dark"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
        <h2><?php echo wp_kses_post($headline); ?></h2>
        <?php if ($copy) : ?><p><?php echo esc_html($copy); ?></p><?php endif; ?>
        <div class="band-actions">
            <?php ocd_render_link($cta, 'btn on-dark'); ?>
        </div>
    </div>
</section>
