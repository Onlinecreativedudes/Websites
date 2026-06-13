<?php
/**
 * Value split — Built to last (text left, media right).
 * ACF tab: Built to Last (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow   = get_field('built_eyebrow');
$headline  = get_field('built_headline');
$copy      = get_field('built_copy');
$list      = get_field('built_list');
$image     = get_field('built_image');
$tag       = get_field('built_tag');
$primary   = get_field('built_primary_cta');
$secondary = get_field('built_secondary_cta');

if (!$headline) { return; }
?>

<section class="value">
    <div class="container">
        <div class="v-text reveal">
            <?php if ($eyebrow) : ?><span class="eyebrow"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
            <h2 style="margin-top:20px;"><?php echo wp_kses_post($headline); ?></h2>
            <?php if ($copy) : ?><p class="body" style="margin-top:24px;"><?php echo esc_html($copy); ?></p><?php endif; ?>

            <?php if ($list && is_array($list)) : ?>
                <ul class="v-list">
                    <?php foreach ($list as $li) :
                        $label = is_array($li) ? ($li['label'] ?? '') : (string) $li;
                        if (!$label) { continue; }
                    ?>
                        <li><?php echo ocd_icon('check'); ?><?php echo esc_html($label); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="v-actions">
                <?php
                ocd_render_link($primary, 'btn');
                if ($secondary && !empty($secondary['url'])) :
                    $st = !empty($secondary['target']) ? ' target="_blank" rel="noopener"' : '';
                    printf('<a href="%s" class="tlink"%s>%s %s</a>', esc_url($secondary['url']), $st, esc_html($secondary['title']), ocd_icon('arrow'));
                endif;
                ?>
            </div>
        </div>

        <div class="v-media reveal">
            <?php
            if ($image && !empty($image['ID'])) {
                echo wp_get_attachment_image($image['ID'], 'card', false, ['alt' => esc_attr($image['alt'] ?? '')]);
            }
            ?>
            <?php if ($tag) : ?><span class="tag"><?php echo esc_html($tag); ?></span><?php endif; ?>
        </div>
    </div>
</section>
