<?php
/**
 * Hero + lead form
 * ACF tab: Hero (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$kicker       = get_field('hero_kicker');
$headline     = get_field('hero_headline');
$intro        = get_field('hero_intro');
$ticks        = get_field('hero_ticks');
$image        = get_field('hero_image');
$mobile_image = get_field('hero_mobile_image');

$form_eyebrow    = get_field('hero_form_eyebrow');
$form_heading    = get_field('hero_form_heading');
$form_subheading = get_field('hero_form_subheading');
$form_id         = get_field('hero_form_id');
$form_footnote   = get_field('hero_form_footnote');

if (!$headline) { return; }
?>

<section class="hero" id="top">
    <?php if ($image && !empty($image['ID'])) : ?>
        <div class="hero__bg" data-parallax data-speed="0.22">
            <?php
            // Likely LCP element — load eagerly with priority.
            echo wp_get_attachment_image((int) $image['ID'], 'hero', false, [
                'class'         => 'hero__bg-img',
                'alt'           => esc_attr($image['alt'] ?: ''),
                'loading'       => 'eager',
                'fetchpriority' => 'high',
                'data-kenburns' => '',
            ]);
            ?>
        </div>
        <div class="hero__shade" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="hero__inner container container--wide">
        <div class="hero__grid">
            <div class="hero__content">
                <?php if ($mobile_image && !empty($mobile_image['ID'])) : ?>
                    <picture class="hero__mobile-media">
                        <source media="(min-width: 769px)" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
                        <?php
                        // Mobile LCP candidate — never lazy-load. Desktop gets a
                        // 1px placeholder source so the photo never downloads there.
                        echo wp_get_attachment_image((int) $mobile_image['ID'], 'card', false, [
                            'class'         => 'hero__mobile-img',
                            'alt'           => esc_attr($mobile_image['alt'] ?: ''),
                            'loading'       => 'eager',
                            'fetchpriority' => 'high',
                        ]);
                        ?>
                    </picture>
                <?php endif; ?>

                <?php ocd_kicker($kicker, 'gold'); ?>

                <h1 class="hero__headline"><?php echo ocd_kses_headline($headline); ?></h1>

                <?php if ($intro) : ?>
                    <p class="hero__intro"><?php echo esc_html($intro); ?></p>
                <?php endif; ?>

                <?php if ($ticks) : ?>
                    <ul class="ticks hero__ticks">
                        <?php foreach ($ticks as $tick) : if (empty($tick['label'])) { continue; } ?>
                            <li><?php echo ocd_tick(); ?><?php echo esc_html($tick['label']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="hero__form form-card-wrap" id="book">
                <div class="form-card">
                    <?php if ($form_eyebrow) : ?>
                        <div class="form-card__eyebrow"><?php echo esc_html($form_eyebrow); ?></div>
                    <?php endif; ?>
                    <?php if ($form_heading) : ?>
                        <h2 class="form-card__heading"><?php echo esc_html($form_heading); ?></h2>
                    <?php endif; ?>
                    <?php if ($form_subheading) : ?>
                        <p class="form-card__sub"><?php echo esc_html($form_subheading); ?></p>
                    <?php endif; ?>

                    <?php ocd_render_form($form_id, 'lead'); ?>

                    <?php if ($form_footnote) : ?>
                        <p class="form-card__footnote"><?php echo esc_html($form_footnote); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
