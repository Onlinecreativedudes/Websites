<?php
/**
 * About section.
 * ACF tab: About (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$image     = get_field('about_image');
$stat_num  = get_field('about_stat_number');
$stat_lbl  = get_field('about_stat_label');
$eyebrow   = get_field('about_eyebrow');
$headline  = get_field('about_headline');
$lead      = get_field('about_lead');
$copy      = get_field('about_copy');
$checks    = get_field('about_checks');
$quote     = get_field('about_quote');
$quote_sig = get_field('about_quote_sig');

if (!$headline) { return; }
?>

<section class="section tint" id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-figure reveal">
                <div class="ph">
                    <?php
                    if ($image && !empty($image['ID'])) {
                        echo wp_get_attachment_image($image['ID'], 'portrait', false, [
                            'alt' => esc_attr($image['alt'] ?? ''),
                        ]);
                    }
                    ?>
                </div>
                <span class="frame" aria-hidden="true"></span>
                <?php if ($stat_num) : ?>
                    <div class="stat">
                        <div class="n" data-count="<?php echo esc_attr(preg_replace('/[^0-9]/', '', (string) $stat_num)); ?>"><?php echo esc_html($stat_num); ?></div>
                        <?php if ($stat_lbl) : ?><div class="l"><?php echo esc_html($stat_lbl); ?></div><?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="reveal">
                <?php if ($eyebrow) : ?><span class="eyebrow"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
                <h2 style="margin-top:20px;"><?php echo wp_kses_post($headline); ?></h2>
                <?php if ($lead) : ?><p class="lead" style="margin-top:24px;"><?php echo esc_html($lead); ?></p><?php endif; ?>
                <?php if ($copy) : ?><p style="color:var(--text-soft);"><?php echo esc_html($copy); ?></p><?php endif; ?>

                <?php if ($checks && is_array($checks)) : ?>
                    <ul class="checks">
                        <?php foreach ($checks as $c) :
                            $label = is_array($c) ? ($c['label'] ?? '') : (string) $c;
                            if (!$label) { continue; }
                        ?>
                            <li><?php echo ocd_icon('check'); ?><?php echo esc_html($label); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if ($quote) : ?>
                    <div class="quote">
                        <p><?php echo esc_html($quote); ?></p>
                        <?php if ($quote_sig) : ?><span class="sig"><?php echo esc_html($quote_sig); ?></span><?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
