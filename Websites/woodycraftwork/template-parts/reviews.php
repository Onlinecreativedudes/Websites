<?php
/**
 * Testimonials / reviews (dark section).
 * ACF tab: Reviews (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow  = get_field('reviews_eyebrow');
$headline = get_field('reviews_headline');
$intro    = get_field('reviews_intro');
$items    = get_field('reviews_items');

if (!$items || !is_array($items)) { return; }
?>

<section class="section dark" id="reviews">
    <div class="container">
        <div class="section-head center reveal">
            <?php if ($eyebrow) : ?><span class="eyebrow center on-dark"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
            <?php if ($headline) : ?><h2><?php echo wp_kses_post($headline); ?></h2><?php endif; ?>
            <?php if ($intro) : ?><p><?php echo esc_html($intro); ?></p><?php endif; ?>
        </div>

        <div class="grid-reviews reveal">
            <?php foreach ($items as $item) :
                $quote  = $item['quote'] ?? '';
                $name   = $item['name'] ?? '';
                $source = $item['source'] ?? '';
                $rating = (int) ($item['rating'] ?? 5);
                $is_ph  = !empty($item['is_placeholder']);
                if (!$quote) { continue; }
                $initial = $name ? mb_strtoupper(mb_substr($name, 0, 1)) : '';
            ?>
                <article class="review<?php echo $is_ph ? ' ph' : ''; ?>">
                    <?php if ($is_ph) : ?><span class="flag"><?php esc_html_e('Placeholder — replace', 'woodycraftwork'); ?></span><?php endif; ?>
                    <div class="qm">&ldquo;</div>
                    <?php if ($rating > 0) : ?>
                        <div class="stars">
                            <?php for ($s = 0; $s < $rating; $s++) { echo ocd_icon('star'); } ?>
                        </div>
                    <?php endif; ?>
                    <p><?php echo esc_html($quote); ?></p>
                    <div class="who">
                        <span class="av"><?php echo esc_html($initial); ?></span>
                        <div>
                            <?php if ($name) : ?><div class="nm"><?php echo esc_html($name); ?></div><?php endif; ?>
                            <?php if ($source) : ?><div class="src"><?php echo esc_html($source); ?></div><?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
