<?php
/**
 * Reviews grid
 * ACF group: group_reviews_landing
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow  = get_field('reviews_eyebrow');
$headline = get_field('reviews_headline');
$intro    = get_field('reviews_intro');
$items    = get_field('reviews_items');

if (!$items || !is_array($items)) { return; }
?>

<section class="section section--off-white reviews" id="reviews">
    <div class="container">
        <div class="section__header reveal">
            <div class="section__header-lead">
                <?php if ($eyebrow) : ?>
                    <span class="eyebrow"><?php echo esc_html($eyebrow); ?></span>
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

        <div class="reviews__grid">
            <?php foreach ($items as $i => $item) :
                $quote    = $item['quote']    ?? '';
                $name     = $item['name']     ?? '';
                $location = $item['location'] ?? '';
                $rating   = (int) ($item['rating'] ?? 5);
                $rating   = max(1, min(5, $rating));
                $delay    = ($i % 3);
                if (!$quote) { continue; }
            ?>
                <article class="review-card reveal <?php echo $delay ? 'reveal--delay-' . (int) $delay : ''; ?>">
                    <div class="review-card__stars" aria-label="<?php echo esc_attr($rating); ?> out of 5">
                        <?php for ($s = 0; $s < $rating; $s++) {
                            echo ocd_icon('star', ['class' => 'review-card__star']);
                        } ?>
                    </div>
                    <p class="review-card__quote"><?php echo esc_html($quote); ?></p>
                    <div class="review-card__author">
                        <div>
                            <?php if ($name) : ?>
                                <div class="review-card__name"><?php echo esc_html($name); ?></div>
                            <?php endif; ?>
                            <?php if ($location) : ?>
                                <div class="review-card__location"><?php echo esc_html($location); ?></div>
                            <?php endif; ?>
                        </div>
                        <span class="review-card__source">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" class="review-card__google">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Google
                        </span>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
