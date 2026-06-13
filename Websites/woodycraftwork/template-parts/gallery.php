<?php
/**
 * Gallery (masonry grid + lightbox; lightbox markup lives in footer.php).
 * ACF tab: Gallery (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow  = get_field('gallery_eyebrow');
$headline = get_field('gallery_headline');
$intro    = get_field('gallery_intro');
$cta      = get_field('gallery_cta');
$items    = get_field('gallery_items');

if (!$items || !is_array($items)) { return; }
?>

<section class="section" id="gallery">
    <div class="container">
        <div class="section-head center reveal">
            <?php if ($eyebrow) : ?><span class="eyebrow center"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
            <?php if ($headline) : ?><h2><?php echo wp_kses_post($headline); ?></h2><?php endif; ?>
            <?php if ($intro) : ?><p><?php echo esc_html($intro); ?></p><?php endif; ?>
        </div>

        <div class="gallery reveal" id="gallery-grid">
            <?php foreach ($items as $item) :
                $image = $item['image'] ?? null;
                $size  = $item['size'] ?? 'normal';
                if (!$image || empty($image['ID'])) { continue; }
                $cls = 'cell';
                if ($size === 'wide') { $cls .= ' wide'; }
                if ($size === 'tall') { $cls .= ' tall'; }
            ?>
                <a class="<?php echo esc_attr($cls); ?>" href="<?php echo esc_url($image['url']); ?>">
                    <?php
                    echo wp_get_attachment_image($image['ID'], 'square', false, [
                        'alt' => esc_attr($image['alt'] ?? ''),
                    ]);
                    ?>
                    <span class="view"><?php esc_html_e('View', 'woodycraftwork'); ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($cta && !empty($cta['url'])) : ?>
            <div style="text-align:center;margin-top:52px;" class="reveal">
                <?php ocd_render_link($cta, 'btn'); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
