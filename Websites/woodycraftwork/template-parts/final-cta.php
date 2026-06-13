<?php
/**
 * Final CTA band (parallax).
 * ACF tab: Final CTA (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow  = get_field('final_eyebrow');
$headline = get_field('final_headline');
$copy     = get_field('final_copy');
$cta      = get_field('final_cta');
$image    = get_field('final_image');

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
