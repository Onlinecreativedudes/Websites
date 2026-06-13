<?php
/**
 * Reviews — three testimonial cards with star ratings.
 * ACF tab: Reviews (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$kicker   = get_field('reviews_kicker');
$headline = get_field('reviews_headline');
$reviews  = get_field('reviews_items');

if (!$reviews) { return; }
?>

<section class="reviews section">
    <div class="container container--wide">
        <?php ocd_kicker($kicker); ?>
        <h2 class="section__headline section__headline--solo"><?php echo ocd_kses_headline($headline); ?></h2>

        <div class="reviews__grid" data-stagger>
            <?php foreach ($reviews as $review) : ?>
                <figure class="review">
                    <div class="review__stars" aria-label="<?php esc_attr_e('5 star review', 'hvnladvisory'); ?>">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    <blockquote class="review__quote"><?php echo esc_html($review['quote']); ?></blockquote>
                    <figcaption class="review__source">
                        <span class="review__name"><?php echo esc_html($review['name']); ?></span><br>
                        <?php echo esc_html($review['business']); ?>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>
