<?php
/**
 * Services grid.
 * ACF tab: Services (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow  = get_field('services_eyebrow');
$headline = get_field('services_headline');
$intro    = get_field('services_intro');
$items    = get_field('services_items');

if (!$items || !is_array($items)) { return; }
?>

<section class="section" id="services">
    <div class="container">
        <div class="section-head center reveal">
            <?php if ($eyebrow) : ?><span class="eyebrow center"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
            <?php if ($headline) : ?><h2><?php echo wp_kses_post($headline); ?></h2><?php endif; ?>
            <?php if ($intro) : ?><p><?php echo esc_html($intro); ?></p><?php endif; ?>
        </div>

        <div class="grid-services reveal">
            <?php foreach ($items as $i => $item) :
                $title = $item['title'] ?? '';
                $copy  = $item['copy'] ?? '';
                $image = $item['image'] ?? null;
                $link  = $item['cta_link'] ?? null;
                $cta_text = $item['cta_text'] ?: __('Get a quote', 'woodycraftwork');
                $href  = (is_array($link) && !empty($link['url'])) ? $link['url'] : home_url('/#contact');
                if (!$title) { continue; }
            ?>
                <article class="svc">
                    <div class="svc-img">
                        <span class="svc-num"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
                        <?php
                        if ($image && !empty($image['ID'])) {
                            echo wp_get_attachment_image($image['ID'], 'card', false, [
                                'alt' => esc_attr($image['alt'] ?? $title),
                            ]);
                        }
                        ?>
                    </div>
                    <div class="svc-body">
                        <h3><?php echo esc_html($title); ?></h3>
                        <?php if ($copy) : ?><p><?php echo esc_html($copy); ?></p><?php endif; ?>
                        <a href="<?php echo esc_url($href); ?>" class="tlink"><?php echo esc_html($cta_text); ?> <?php echo ocd_icon('arrow'); ?></a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
