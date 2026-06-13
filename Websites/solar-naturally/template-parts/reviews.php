<?php
/**
 * Reviews — heading with rating line and a one-at-a-time carousel.
 * ACF tab: Reviews
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow = get_field('reviews_eyebrow') ?: 'What our customers say';
$heading = get_field('reviews_heading') ?: "Straight talk, honest<br>systems, real savings";
$rating  = get_field('reviews_rating_line') ?: '<b>4.9</b> / 5 average · verified Google reviews';
$reviews = get_field('reviews_items');

if (!$reviews) {
    $reviews = [
        ['name' => 'Mark T.',           'location' => 'Australind, WA', 'text' => 'From quote to switch-on the whole thing was painless. Their own crew did the install, no subbies, and the production numbers they promised are exactly what we are seeing on the app.'],
        ['name' => 'Sandra & Paul',     'location' => 'Eaton, WA',      'text' => 'We were nervous about the cost. They sized a system to our actual usage instead of overselling us. Bill has gone from $390 a month to under $60. Wish we had done it years ago.'],
        ['name' => 'Dimitri K.',        'location' => 'Dalyellup, WA',  'text' => 'Honest from the first phone call. They told us our old inverter was worth replacing rather than a whole new system. That kind of straight talk is why we trusted them.'],
        ['name' => 'Rebecca H.',        'location' => 'Bunbury, WA',    'text' => 'Battery was installed in a day, tidy work, everything labelled and explained. We run the house off stored solar through the evening now. Genuinely impressed.'],
        ['name' => 'The Nguyen family', 'location' => 'Capel, WA',      'text' => 'Four to six weeks they said, and they delivered. Local team, answered every question, and followed up after to make sure it was all running right.'],
    ];
}
?>

<section class="section rs" id="reviews">
    <div class="wrap rs__in">
        <div class="rs__head">
            <div class="eyebrow-row" data-rise="rise"><span class="tick"></span><span class="eyebrow"><?php echo esc_html($eyebrow); ?></span></div>
            <h2 class="rs__h2" data-rise="rise" style="animation-delay:80ms"><?php echo wp_kses($heading, ['br' => []]); ?></h2>
            <div class="rs__rating" data-rise="fade" style="animation-delay:160ms">
                <?php echo sn_stars(); ?><span><?php echo wp_kses($rating, ['b' => [], 'strong' => []]); ?></span>
            </div>
        </div>
        <div class="rs__car" data-rise="rise" style="animation-delay:120ms">
            <div class="rev js-reviews">
                <div class="rev__main">
                    <?php echo sn_stars('rev__stars'); ?>
                    <blockquote class="rev__quote js-rev-quote"></blockquote>
                    <div class="rev__by">
                        <span class="rev__name js-rev-name"></span>
                        <span class="rev__loc js-rev-loc"></span>
                        <span class="rev__src"><?php echo sn_icon('badge-check', 15); ?> Verified · Google</span>
                    </div>
                </div>
                <div class="rev__ctrl">
                    <div class="rev__dots js-rev-dots">
                        <?php foreach ($reviews as $k => $r) : ?>
                            <button class="rev__dot<?php echo $k === 0 ? ' on' : ''; ?>" data-index="<?php echo (int) $k; ?>" aria-label="Review <?php echo (int) ($k + 1); ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="rev__arrows">
                        <button class="rev__arr js-rev-prev"><?php echo sn_icon('arrow-left', 20); ?></button>
                        <button class="rev__arr js-rev-next"><?php echo sn_icon('arrow-right', 20); ?></button>
                    </div>
                </div>
                <script type="application/json" class="js-rev-data"><?php
                    echo wp_json_encode(array_map(function($r) {
                        return [
                            'name' => (string) ($r['name'] ?? ''),
                            'loc'  => (string) ($r['location'] ?? ''),
                            'text' => (string) ($r['text'] ?? ''),
                        ];
                    }, $reviews));
                ?></script>
            </div>
        </div>
    </div>
</section>
