<?php /* Template Name: Gallery */ if (!defined('ABSPATH')) { exit; } get_header(); ?>
<?php
/**
 * Gallery / Portfolio page template. Ported from
 * Pressure_Cleaning_Perth_Portfolio.tpl.html. Renders the design copy and
 * imagery by default and is overridable via ACF (group_pcp_gallery).
 */
$phone_d   = pcp_phone_display();
$phone_t   = pcp_phone_tel();
$quote_url = pcp_field('nav_quote_url', home_url('/contact/'), 'option');

$rows = function ($name) { $r = get_field($name); return (is_array($r) && $r) ? $r : null; };

// ---- before/after defaults that mirror the design ----
$d_ba = [
    ['driveway-pavers.jpg', 'sidewalk-clean.jpg', 'Driveway', 'Driveway Pavers', 'Bayswater · before & after'],
    ['roof-tiles-clean.jpg', 'roof-clean-aerial.jpg', 'Roof', 'Tile Roof Restoration', 'Mount Lawley · before & after'],
    ['concrete-spray.jpg', 'stone-tiles-clean.jpg', 'Commercial', 'Concrete & Limestone', 'CBD walkway · before & after'],
    ['orange-suit-pavers.jpg', 'backyard-tiles.jpg', 'Patio', 'Backyard Tiles', 'Inglewood · before & after'],
];

// ---- portfolio grid image defaults (file, alt) ----
$d_grid = [
    ['sidewalk-clean.jpg', 'Bayswater driveway pressure clean'],
    ['roof-tiles-clean.jpg', 'Mount Lawley tile roof'],
    ['roof-clean-aerial.jpg', 'Terracotta roof restoration'],
    ['concrete-spray.jpg', 'Como concrete pavement'],
    ['pool-pavers-vacuum.jpg', 'Applecross pool surround'],
    ['stone-tiles-clean.jpg', 'Subiaco house wash'],
    ['backyard-tiles.jpg', 'Inglewood backyard tile'],
    ['warehouse-floor.jpg', 'Cannington warehouse floor'],
    ['spray-nozzle.jpg', 'Joondalup render soft wash'],
    ['commercial-repco.jpg', 'CBD sidewalk and limestone'],
    ['roof-washing-action.jpg', 'Morley commercial wash-down'],
    ['driveway-pavers.jpg', 'Scarborough paver restore'],
];
// size classes cycle to mirror the design masonry layout
$grid_sizes = ['pf-wide pf-featured', 'pf-tall', 'pf-standard', 'pf-standard', 'pf-tall', 'pf-standard', 'pf-standard', 'pf-wide pf-featured', 'pf-standard', 'pf-standard', 'pf-tall', 'pf-standard', 'pf-wide'];

$mobile_hero = pcp_field('mobile_hero_image', '', false);
$hero_img    = pcp_field('hero_image', '', false);
$hero_bg     = (is_array($hero_img) && !empty($hero_img['url']))
    ? $hero_img['url']
    : PCP_THEME_URI . '/assets/images/sidewalk-clean.jpg';
?>

<section class="hero hero-svc hero-portfolio" style="background-image:url('<?php echo esc_url($hero_bg); ?>')">
  <div class="wrap">
    <div class="reveal in">
      <?php pcp_mobile_hero(is_array($mobile_hero) ? $mobile_hero : null, 'Pressure cleaning portfolio'); ?>
      <nav class="crumbs" aria-label="Breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
        <?php echo pcp_icon('arrow'); ?>
        <span><?php echo esc_html(pcp_field('hero_crumb', 'Portfolio')); ?></span>
      </nav>
      <span class="eyebrow"><span class="dot"></span> <?php echo esc_html(pcp_field('hero_eyebrow', 'Real Jobs · Real Results')); ?></span>
      <h1><?php echo wp_kses_post(pcp_field('hero_heading', 'The <span class="hl">Portfolio</span>')); ?></h1>
      <p class="sub"><?php echo esc_html(pcp_field('hero_sub', 'Real Perth properties, real before-and-after. No filters, no stock photos, just the surfaces we have actually cleaned, exactly the way our customers saw them.')); ?></p>
    </div>
  </div>
</section>

<section class="ba-section pad-sm">
  <div class="wrap">
    <div class="sec-head reveal" style="text-align:center;max-width:760px">
      <span class="kicker g"><?php echo esc_html(pcp_field('ba_kicker', 'Image Gallery')); ?></span>
      <h2><?php echo esc_html(pcp_field('ba_heading', 'Before & after - the proof is in the wash')); ?></h2>
      <p><?php echo esc_html(pcp_field('ba_intro', 'Every job we do gets photographed front-to-back. Here is a handful of recent splits, side by side.')); ?></p>
    </div>
    <div class="ba-grid">
      <?php
      $ba_rows = $rows('ba_cards');
      if ($ba_rows) {
          foreach ($ba_rows as $b) : ?>
            <article class="ba-card reveal">
              <div class="ba-split">
                <div class="ba-half ba-before"><?php echo pcp_image($b['before_image'] ?? null, 'pcp-card', '', ['loading' => 'lazy'], '', ''); ?><span class="ba-label">Before</span></div>
                <div class="ba-half ba-after"><?php echo pcp_image($b['after_image'] ?? null, 'pcp-card', '', ['loading' => 'lazy'], '', ''); ?><span class="ba-label ba-label-after">After</span></div>
              </div>
              <?php if (!empty($b['label'])) : ?><div class="ba-meta"><h3><?php echo esc_html($b['label']); ?></h3></div><?php endif; ?>
            </article>
          <?php endforeach;
      } else {
          foreach ($d_ba as $b) : ?>
            <article class="ba-card reveal">
              <div class="ba-split">
                <div class="ba-half ba-before"><img src="<?php echo esc_url(PCP_THEME_URI . '/assets/images/' . $b[0]); ?>" alt="<?php echo esc_attr($b[3] . ' before'); ?>" width="800" height="600" loading="lazy" decoding="async"><span class="ba-label">Before</span></div>
                <div class="ba-half ba-after"><img src="<?php echo esc_url(PCP_THEME_URI . '/assets/images/' . $b[1]); ?>" alt="<?php echo esc_attr($b[3] . ' after'); ?>" width="800" height="600" loading="lazy" decoding="async"><span class="ba-label ba-label-after">After</span></div>
              </div>
              <div class="ba-meta">
                <span class="ba-cat"><?php echo esc_html($b[2]); ?></span>
                <h3><?php echo esc_html($b[3]); ?></h3>
                <span class="ba-sub"><?php echo esc_html($b[4]); ?></span>
              </div>
            </article>
          <?php endforeach;
      }
      ?>
    </div>
  </div>
</section>

<section class="portfolio pad-sm">
  <div class="wrap">
    <div class="pf-header reveal">
      <div>
        <span class="kicker g"><?php echo esc_html(pcp_field('portfolio_kicker', 'Recent Projects')); ?></span>
        <h2><?php echo esc_html(pcp_field('portfolio_heading', 'Browse the full collection')); ?></h2>
      </div>
    </div>
    <div class="pf-grid">
      <?php
      $gallery = get_field('portfolio_images');
      if (is_array($gallery) && $gallery) {
          foreach ($gallery as $i => $img) {
              $size = $grid_sizes[$i % count($grid_sizes)];
              echo '<div class="pf-tile ' . esc_attr($size) . ' reveal">' . pcp_image($img, 'pcp-card', '', ['loading' => 'lazy']) . '</div>';
          }
      } else {
          foreach ($d_grid as $i => $g) {
              $size = $grid_sizes[$i % count($grid_sizes)];
              printf(
                  '<div class="pf-tile %s reveal"><img src="%s" alt="%s" width="800" height="600" loading="lazy" decoding="async"></div>',
                  esc_attr($size),
                  esc_url(PCP_THEME_URI . '/assets/images/' . $g[0]),
                  esc_attr($g[1])
              );
          }
      }
      ?>
    </div>
  </div>
</section>

<?php
get_template_part('template-parts/cta-band', null, ['extra_class' => 'cta-mid']);
get_template_part('template-parts/contact');
get_footer();
