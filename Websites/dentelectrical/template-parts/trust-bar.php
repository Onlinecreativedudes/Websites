<?php
/**
 * Trust bar
 * ACF group: group_trustbar_landing
 */
if (!defined('ABSPATH')) { exit; }

$items = get_field('trust_bar_items');
if (!$items || !is_array($items)) { return; }
?>

<section class="trust-bar">
    <div class="container">
        <div class="trust-bar__grid">
            <?php foreach ($items as $item) :
                $icon  = $item['icon'] ?? 'shield';
                $line1 = $item['line_one'] ?? '';
                $line2 = $item['line_two'] ?? '';
                if (!$line1 && !$line2) { continue; }
            ?>
                <div class="trust-bar__item">
                    <div class="trust-bar__icon"><?php echo ocd_icon($icon); ?></div>
                    <div class="trust-bar__label">
                        <?php if ($line1) : ?><span><?php echo esc_html($line1); ?></span><?php endif; ?>
                        <?php if ($line2) : ?><span class="trust-bar__sub"><?php echo esc_html($line2); ?></span><?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
