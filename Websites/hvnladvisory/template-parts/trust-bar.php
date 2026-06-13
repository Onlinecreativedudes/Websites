<?php
/**
 * Trust bar — single dark strip of credibility points with dot separators.
 * ACF tab: Trust Bar (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$items = get_field('trust_bar_items');
if (!$items) { return; }
?>

<section class="trust-bar">
    <div class="trust-bar__inner container container--wide">
        <?php foreach ($items as $i => $item) :
            if (empty($item['label'])) { continue; }
            if ($i > 0) { echo '<span class="trust-bar__dot" aria-hidden="true"></span>'; }
        ?>
            <span><?php echo esc_html($item['label']); ?></span>
        <?php endforeach; ?>
    </div>
</section>
