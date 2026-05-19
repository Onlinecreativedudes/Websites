<?php
/**
 * Feature block — Inspections (reversed layout, cool tone)
 * ACF group: group_feature_inspections_landing
 */
if (!defined('ABSPATH')) { exit; }

$image          = get_field('inspections_image');
$image_tag      = get_field('inspections_image_tag');
$image_tag_tone = get_field('inspections_image_tag_tone') ?: 'blue';
$eyebrow        = get_field('inspections_eyebrow');
$headline       = get_field('inspections_headline');
$copy           = get_field('inspections_copy');
$bullets        = get_field('inspections_bullets');
$primary_cta    = get_field('inspections_primary_cta');
$secondary_cta  = get_field('inspections_secondary_cta');

if (!$headline) { return; }
?>

<section class="section">
    <div class="container">
        <div class="feature feature--reverse feature--cool">
            <div class="feature__image reveal">
                <?php if ($image_tag) : ?>
                    <span class="feature__image-tag feature__image-tag--<?php echo esc_attr($image_tag_tone); ?>"><?php echo esc_html($image_tag); ?></span>
                <?php endif; ?>
                <?php if ($image && !empty($image['ID'])) : ?>
                    <?php echo wp_get_attachment_image($image['ID'], 'card', false, [
                        'class' => 'feature__image-img',
                        'alt'   => esc_attr($image['alt'] ?? ''),
                    ]); ?>
                <?php endif; ?>
            </div>

            <div class="feature__content reveal reveal--delay-1">
                <?php if ($eyebrow) : ?>
                    <span class="eyebrow"><?php echo esc_html($eyebrow); ?></span>
                <?php endif; ?>
                <h2 class="feature__headline"><?php echo wp_kses_post($headline); ?></h2>
                <?php if ($copy) : ?>
                    <p class="feature__copy"><?php echo esc_html($copy); ?></p>
                <?php endif; ?>

                <?php if ($bullets && is_array($bullets)) : ?>
                    <ul class="feature__list">
                        <?php foreach ($bullets as $row) :
                            $text = is_array($row) ? ($row['text'] ?? '') : (string) $row;
                            if (!$text) { continue; }
                        ?>
                            <li>
                                <span class="feature__list-icon"><?php echo ocd_icon('check'); ?></span>
                                <span><?php echo wp_kses_post($text); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <div class="feature__actions">
                    <?php ocd_render_link($primary_cta, 'btn btn--blue'); ?>
                    <?php ocd_render_link($secondary_cta, 'btn btn--outline'); ?>
                </div>
            </div>
        </div>
    </div>
</section>
