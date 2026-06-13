<?php
/**
 * CTA banner 2 — the gold "regulators assess evidence" strip.
 * ACF tab: Evidence Banner (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$headline  = get_field('evidence_headline');
$text      = get_field('evidence_text');
$cta_label = get_field('evidence_cta_label');

if (!$headline) { return; }
?>

<section class="cta-evidence">
    <div class="cta-evidence__grid container container--wide">
        <div>
            <h2 class="cta-evidence__headline"><?php echo ocd_kses_headline($headline); ?></h2>
            <?php if ($text) : ?>
                <p class="cta-evidence__text"><?php echo esc_html($text); ?></p>
            <?php endif; ?>
        </div>
        <div class="cta-evidence__action">
            <?php ocd_book_cta($cta_label, 'btn btn--dark btn--lg'); ?>
        </div>
    </div>
</section>
