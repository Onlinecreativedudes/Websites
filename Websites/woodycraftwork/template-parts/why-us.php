<?php
/**
 * Why choose us grid.
 * ACF tab: Why Us (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow  = get_field('why_eyebrow');
$headline = get_field('why_headline');
$intro    = get_field('why_intro');
$items    = get_field('why_items');

if (!$items || !is_array($items)) { return; }
?>

<section class="section" id="why">
    <div class="container">
        <div class="section-head center reveal">
            <?php if ($eyebrow) : ?><span class="eyebrow center"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
            <?php if ($headline) : ?><h2><?php echo wp_kses_post($headline); ?></h2><?php endif; ?>
            <?php if ($intro) : ?><p><?php echo esc_html($intro); ?></p><?php endif; ?>
        </div>

        <div class="grid-why reveal">
            <?php foreach ($items as $i => $item) :
                $icon  = $item['icon'] ?? 'check';
                $title = $item['title'] ?? '';
                $copy  = $item['copy'] ?? '';
                if (!$title) { continue; }
            ?>
                <div class="why">
                    <div class="why-top">
                        <span class="why-ic"><?php echo ocd_icon($icon, ['stroke-width' => '1.7']); ?></span>
                        <span class="why-n"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
                    </div>
                    <h3><?php echo esc_html($title); ?></h3>
                    <?php if ($copy) : ?><p><?php echo esc_html($copy); ?></p><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
