<?php
/**
 * Why us grid
 * ACF group: group_whyus_landing
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow  = get_field('why_eyebrow');
$headline = get_field('why_headline');
$intro    = get_field('why_intro');
$items    = get_field('why_items');

if (!$items || !is_array($items)) { return; }
?>

<section class="section why" id="why">
    <div class="container">
        <div class="section__header reveal">
            <div class="section__header-lead">
                <?php if ($eyebrow) : ?>
                    <span class="eyebrow eyebrow--yellow"><?php echo esc_html($eyebrow); ?></span>
                <?php endif; ?>
                <?php if ($headline) : ?>
                    <h2 class="display-l"><?php echo wp_kses_post($headline); ?></h2>
                <?php endif; ?>
            </div>
            <?php if ($intro) : ?>
                <div class="section__header-aside">
                    <p><?php echo esc_html($intro); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="why__grid">
            <?php foreach ($items as $i => $item) :
                $title = $item['title'] ?? '';
                $copy  = $item['copy']  ?? '';
                $icon  = $item['icon']  ?? 'badge';
                $delay = ($i % 3);
                if (!$title) { continue; }
            ?>
                <div class="why__item reveal <?php echo $delay ? 'reveal--delay-' . (int) $delay : ''; ?>">
                    <div class="why__icon"><?php echo ocd_icon($icon); ?></div>
                    <h3 class="why__title"><?php echo esc_html($title); ?></h3>
                    <?php if ($copy) : ?>
                        <p class="why__copy"><?php echo esc_html($copy); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
