<?php
/**
 * Hero section (full-bleed parallax) + call-back bar + trust strip.
 * ACF tab: Hero (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow      = get_field('hero_eyebrow');
$headline     = get_field('hero_headline');
$subhead      = get_field('hero_subhead');
$primary_cta  = get_field('hero_primary_cta');
$secondary_cta = get_field('hero_secondary_cta');
$hero_image   = get_field('hero_image');
$hero_mobile  = get_field('hero_image_mobile');
$cb_eyebrow   = get_field('cb_eyebrow');
$cb_title     = get_field('cb_title');
$cb_form_id   = get_field('hero_form_id');
$trust_items  = get_field('trust_items');

if (!$headline) { return; }

// 1x1 transparent gif so the desktop <picture> source loads nothing heavy.
$blank_gif = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
?>

<header class="hero" id="top">
    <div class="hero-bg" id="hero-bg">
        <?php
        if ($hero_image && !empty($hero_image['ID'])) {
            echo wp_get_attachment_image($hero_image['ID'], 'hero', false, [
                'alt'           => esc_attr($hero_image['alt'] ?? ''),
                'fetchpriority' => 'high',
                'decoding'      => 'async',
            ]);
        }
        ?>
    </div>

    <div class="hero-body">
        <div class="container">
            <div class="hero-inner reveal">
                <?php if ($hero_mobile && !empty($hero_mobile['url'])) :
                    $m_url = $hero_mobile['sizes']['large'] ?? $hero_mobile['url'];
                    $m_w   = $hero_mobile['sizes']['large-width'] ?? $hero_mobile['width'];
                    $m_h   = $hero_mobile['sizes']['large-height'] ?? $hero_mobile['height'];
                ?>
                    <picture class="hero-mobile-img">
                        <source media="(min-width:769px)" srcset="<?php echo esc_attr($blank_gif); ?>">
                        <img src="<?php echo esc_url($m_url); ?>" width="<?php echo esc_attr($m_w); ?>" height="<?php echo esc_attr($m_h); ?>" alt="<?php echo esc_attr($hero_mobile['alt'] ?? ''); ?>" loading="eager" fetchpriority="high" decoding="async">
                    </picture>
                <?php endif; ?>

                <?php if ($eyebrow) : ?>
                    <span class="eyebrow on-dark"><?php echo esc_html($eyebrow); ?></span>
                <?php endif; ?>

                <h1><?php echo wp_kses_post($headline); ?></h1>

                <?php if ($subhead) : ?>
                    <p class="hero-sub"><?php echo esc_html($subhead); ?></p>
                <?php endif; ?>

                <div class="hero-actions">
                    <?php
                    ocd_render_link($primary_cta, 'btn on-dark');
                    ocd_render_link($secondary_cta, 'btn outline on-dark');
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($cb_form_id || $cb_title) : ?>
        <div class="hero-callback">
            <div class="container">
                <div class="cb-shell">
                    <?php if ($cb_eyebrow || $cb_title) : ?>
                        <div class="cb-head">
                            <?php if ($cb_eyebrow) : ?><span class="cb-eyebrow"><?php echo esc_html($cb_eyebrow); ?></span><?php endif; ?>
                            <?php if ($cb_title) : ?><span class="cb-title"><?php echo esc_html($cb_title); ?></span><?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="cb-form-slot">
                        <div class="ocd-form ocd-form--callback">
                            <?php ocd_render_gravity_form($cb_form_id); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($trust_items && is_array($trust_items)) : ?>
        <div class="trust">
            <div class="container">
                <?php foreach ($trust_items as $item) :
                    $label = is_array($item) ? ($item['label'] ?? '') : (string) $item;
                    if (!$label) { continue; }
                ?>
                    <span class="it"><?php echo ocd_icon('check'); ?><?php echo esc_html($label); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</header>
