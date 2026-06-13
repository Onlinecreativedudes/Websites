<?php
/**
 * Gallery — tabbed project grid (Residential/Commercial) with lightbox.
 * ACF tab: Gallery
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow  = get_field('gallery_eyebrow') ?: 'Our work';
$heading  = get_field('gallery_heading') ?: "Real systems, across<br>the South West";
$lede     = get_field('gallery_lede') ?: 'From architectural homes to working agribusiness and industry — installed and signed off by our own team.';
$defaults = [
    ['category' => 'Residential', 'title' => 'Coombe Residence',   'meta' => 'Architectural home · solar + battery',   'big' => true],
    ['category' => 'Residential', 'title' => 'West Coast',         'meta' => 'Two-storey rooftop array',               'big' => false],
    ['category' => 'Commercial',  'title' => 'Patane Produce',     'meta' => 'Agribusiness · large-scale rooftop',     'big' => true],
    ['category' => 'Residential', 'title' => 'River Way',          'meta' => 'Modern family home',                     'big' => false],
    ['category' => 'Residential', 'title' => 'Swan',               'meta' => 'Premium residence',                      'big' => false],
    ['category' => 'Commercial',  'title' => 'Tyrecycle',          'meta' => 'Industrial facility · full-roof system', 'big' => false],
    ['category' => 'Commercial',  'title' => 'South West Growers', 'meta' => 'Rural sheds · commercial solar',         'big' => true],
];

// Positional fallback photos, used for any project without its own ACF image
// so the gallery keeps its imagery even once the text fields are populated.
$fallback_imgs = [
    'gallery/residential/coombe.jpg',
    'gallery/residential/west-coast.jpg',
    'gallery/commercial/patane-produce.jpg',
    'gallery/residential/riverway.jpg',
    'gallery/residential/swan.jpg',
    'gallery/commercial/tyrecycle.jpg',
    'gallery/commercial/rural-sheds.jpg',
];

$projects = get_field('gallery_projects') ?: $defaults;

$counts = ['All' => count($projects), 'Residential' => 0, 'Commercial' => 0];
foreach ($projects as $p) {
    $cat = $p['category'] ?: 'Residential';
    if (isset($counts[$cat])) { $counts[$cat]++; }
}
?>

<section class="section gs" id="gallery">
    <div class="wrap">
        <div class="gs__head">
            <div>
                <div class="eyebrow-row" data-rise="rise"><span class="tick"></span><span class="eyebrow"><?php echo esc_html($eyebrow); ?></span></div>
                <h2 class="gs__h2" data-rise="rise" style="animation-delay:80ms"><?php echo wp_kses($heading, ['br' => []]); ?></h2>
            </div>
            <p class="lede gs__p" data-rise="fade" style="animation-delay:160ms"><?php echo esc_html($lede); ?></p>
        </div>

        <div class="gal js-gallery">
            <div class="gal__tabs">
                <?php foreach (['All', 'Residential', 'Commercial'] as $tab) : ?>
                    <button class="gal__tab<?php echo $tab === 'All' ? ' on' : ''; ?>" data-tab="<?php echo esc_attr($tab); ?>">
                        <?php echo esc_html($tab); ?><span class="gal__count"><?php echo (int) $counts[$tab]; ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
            <div class="gal__grid">
                <?php foreach ($projects as $i => $p) :
                    $cat   = $p['category'] ?: 'Residential';
                    $title = $p['title'] ?? '';
                    $meta  = $p['meta'] ?? '';
                    $big   = !empty($p['big']);
                ?>
                    <button class="gcard<?php echo $big ? ' gcard--big' : ''; ?>" data-cat="<?php echo esc_attr($cat); ?>" data-rise="fade" style="animation-delay:<?php echo (int) (($i % 3) * 90); ?>ms">
                        <?php if (!empty($p['image']['ID'])) : ?>
                            <?php echo sn_render_image($p['image'], 'sn-gallery', '', ['loading' => 'lazy']); ?>
                        <?php else :
                            $rel = $p['default_img'] ?? $fallback_imgs[$i % count($fallback_imgs)];
                        ?>
                            <img src="<?php echo esc_url(SN_THEME_URI . '/assets/img/' . $rel); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" width="1200" height="900">
                        <?php endif; ?>
                        <span class="gcard__scrim"></span>
                        <span class="gcard__meta">
                            <span class="gcard__cat"><?php echo esc_html($cat); ?></span>
                            <span class="gcard__title"><?php echo esc_html($title); ?></span>
                            <span class="gcard__sub"><?php echo esc_html($meta); ?></span>
                        </span>
                        <span class="gcard__view"><?php echo sn_icon('maximize-2', 18); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="lb js-lightbox" hidden>
                <button class="lb__close js-lb-close"><?php echo sn_icon('x', 26); ?></button>
                <button class="lb__nav lb__prev js-lb-prev"><?php echo sn_icon('chevron-left', 30); ?></button>
                <figure class="lb__fig">
                    <img src="" alt="">
                    <figcaption>
                        <span class="lb__cat"></span>
                        <span class="lb__title"></span>
                        <span class="lb__sub"></span>
                    </figcaption>
                </figure>
                <button class="lb__nav lb__next js-lb-next"><?php echo sn_icon('chevron-right', 30); ?></button>
            </div>
        </div>
    </div>
</section>
