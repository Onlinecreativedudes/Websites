<?php
/**
 * Who we help — heading plus alternating image/copy split rows.
 * ACF tab: Who We Help (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$kicker   = get_field('who_kicker');
$headline = get_field('who_headline');
$rows     = get_field('who_rows');

if (!$rows) { return; }
?>

<section class="who section section--flush" id="who">
    <div class="container container--wide who__head">
        <?php ocd_kicker($kicker); ?>
        <h2 class="section__headline"><?php echo ocd_kses_headline($headline); ?></h2>
    </div>

    <?php foreach ($rows as $i => $row) :
        $image_left = ($i % 2) === 1; // middle row carries the image on the left, on the alt band
    ?>
        <div class="who__row <?php echo $image_left ? 'who__row--alt' : ''; ?>">
            <div class="container container--wide who__row-grid <?php echo $image_left ? 'who__row-grid--flip' : ''; ?>">
                <div class="who__copy">
                    <?php if (!empty($row['label'])) : ?>
                        <div class="who__label"><?php echo esc_html($row['label']); ?></div>
                    <?php endif; ?>
                    <h3 class="who__headline"><?php echo esc_html($row['heading']); ?></h3>
                    <?php if (!empty($row['text'])) : ?>
                        <p class="who__text"><?php echo esc_html($row['text']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($row['ticks'])) : ?>
                        <ul class="ticks ticks--tight">
                            <?php foreach ($row['ticks'] as $tick) : if (empty($tick['label'])) { continue; } ?>
                                <li><?php echo ocd_tick(); ?><?php echo esc_html($tick['label']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <div class="who__actions">
                        <?php ocd_book_cta($row['cta_label'] ?? '', 'btn btn--dark'); ?>
                        <?php ocd_phone_link('phone-link'); ?>
                    </div>
                </div>
                <?php if (!empty($row['image']['ID'])) : ?>
                    <div class="who__media">
                        <div class="who__media-inner" data-parallax data-speed="0.14">
                            <?php echo ocd_render_image($row['image'], 'split', 'who__img'); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>
