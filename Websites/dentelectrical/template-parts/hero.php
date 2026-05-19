<?php
/**
 * Hero section
 * ACF group: group_hero_landing
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow       = get_field('hero_eyebrow');
$headline      = get_field('hero_headline');
$subhead       = get_field('hero_subhead');
$trust_points  = get_field('hero_trust_points');
$primary_cta   = get_field('hero_primary_cta');
$show_phone    = get_field('hero_show_phone');
$hero_image    = get_field('hero_image');
$form_title    = get_field('hero_form_title');
$form_subtitle = get_field('hero_form_subtitle');
$form_footnote = get_field('hero_form_footnote');
$form_id       = get_field('hero_form_id');

$phone_display = ocd_site_phone_display();
$phone_tel     = ocd_site_phone_tel();

if (!$headline) { return; }
?>

<section class="hero">
    <div class="hero__bg">
        <?php if ($hero_image && !empty($hero_image['ID'])) : ?>
            <?php echo wp_get_attachment_image($hero_image['ID'], 'hero', false, [
                'class' => 'hero__bg-img',
                'alt'   => '',
                'fetchpriority' => 'high',
            ]); ?>
        <?php endif; ?>
    </div>
    <div class="hero__bg-accent" aria-hidden="true"></div>

    <div class="container">
        <div class="hero__grid">
            <div class="hero__content reveal">
                <?php if ($eyebrow) : ?>
                    <div class="hero__eyebrow">
                        <span class="hero__dot" aria-hidden="true"></span>
                        <?php echo esc_html($eyebrow); ?>
                    </div>
                <?php endif; ?>

                <h1 class="hero__headline"><?php echo wp_kses_post($headline); ?></h1>

                <?php if ($subhead) : ?>
                    <p class="hero__sub"><?php echo wp_kses_post($subhead); ?></p>
                <?php endif; ?>

                <?php if ($trust_points && is_array($trust_points)) : ?>
                    <div class="hero__trust">
                        <?php foreach ($trust_points as $point) :
                            $label = is_array($point) ? ($point['label'] ?? '') : (string) $point;
                            if (!$label) { continue; }
                        ?>
                            <span class="hero__trust-item">
                                <span class="hero__trust-check"><?php echo ocd_icon('check'); ?></span>
                                <?php echo esc_html($label); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="hero__actions">
                    <?php ocd_render_link($primary_cta, 'btn btn--primary hero__cta'); ?>

                    <?php if ($show_phone && $phone_display) : ?>
                        <a href="<?php echo esc_url($phone_tel); ?>" class="btn btn--outline">
                            <?php echo ocd_icon('phone'); ?>
                            <?php echo esc_html($phone_display); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="hero__form-wrap reveal reveal--delay-1">
                <div class="ocd-form hero__form">
                    <div class="hero__form-header">
                        <?php if ($form_title) : ?>
                            <h2 class="hero__form-title"><?php echo esc_html($form_title); ?></h2>
                        <?php endif; ?>
                        <?php if ($form_subtitle) : ?>
                            <p class="hero__form-sub"><?php echo esc_html($form_subtitle); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php ocd_render_gravity_form($form_id); ?>

                    <?php if ($form_footnote) : ?>
                        <p class="hero__form-footnote"><?php echo esc_html($form_footnote); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
