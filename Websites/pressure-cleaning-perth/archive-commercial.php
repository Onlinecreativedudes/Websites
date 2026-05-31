<?php
/**
 * Commercial archive. Reuses the Services-archive design: a svc-tiles grid looping
 * the commercial CPT, wrapped in the hero / crumbs / cta-band / contact chrome.
 */
if (!defined('ABSPATH')) { exit; }
get_header();

$phone_d   = pcp_phone_display();
$phone_t   = pcp_phone_tel();
$quote_url = pcp_field('nav_quote_url', home_url('/contact/'), 'option');

$d_hero_bullets = ['Fully Insured to $20M', 'Family-Owned Perth Business', '5.0 Google Rating', 'Iron Clad Guarantee'];
?>

<section class="hero hero-svc hero-archive" style="background-image:url('<?php echo esc_url(PCP_THEME_URI . '/assets/images/warehouse-floor.jpg'); ?>')">
  <div class="wrap">
    <div class="reveal in">
      <nav class="crumbs" aria-label="Breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
        <?php echo pcp_icon('caret'); ?>
        <span>Commercial &amp; Industrial</span>
      </nav>
      <span class="eyebrow"><span class="dot"></span> Commercial &amp; Industrial Pressure Cleaning</span>
      <h1>Commercial Pressure Cleaning <span class="hl">Across Perth</span></h1>
      <p class="sub">Shopping centres, hospitals, schools, factory floors, car parks, cool rooms and resort complexes. We schedule around your operation, meet the compliance requirements of commercial sites, and back every job with full insurance.</p>
      <ul class="hero-bullets">
        <?php foreach ($d_hero_bullets as $b) : ?>
          <li><?php echo pcp_icon('check'); ?> <?php echo esc_html($b); ?></li>
        <?php endforeach; ?>
      </ul>
      <div class="hero-cta">
        <a href="#commercial-grid" class="btn btn-green btn-lg">Browse Industries</a>
        <a href="<?php echo esc_url($phone_t); ?>" class="btn btn-ghost btn-lg"><?php echo pcp_icon('phone'); ?> Call <?php echo esc_html($phone_d); ?></a>
      </div>
      <div class="hero-trust">
        <span class="stars" aria-hidden="true"><?php echo pcp_stars(5); ?></span>
        Rated 5.0 across 60+ Google reviews
      </div>
    </div>
  </div>
</section>

<section class="pad-sm">
  <div class="wrap">
    <div class="sec-head reveal" style="max-width:920px">
      <span class="kicker g">Commercial &amp; Industrial</span>
      <h2>Commercial pressure cleaning, done properly</h2>
      <p>From back-of-house loading bays to high-traffic car parks and guest-facing pool areas, we clean every hard surface on your site. Pick an industry to learn more, or call us and we will tailor the right approach for your property.</p>
    </div>
  </div>
</section>

<section class="services-archive pad-sm" id="commercial-grid">
  <div class="wrap">
    <div class="svc-tiles">
      <?php
      $fallbacks = ['commercial-repco.jpg', 'warehouse-floor.jpg', 'concrete-spray.jpg', 'sidewalk-clean.jpg', 'stone-tiles-clean.jpg', 'roof-clean-aerial.jpg'];
      $i = 0;
      if (have_posts()) :
          while (have_posts()) : the_post();
              $fb = $fallbacks[$i % count($fallbacks)];
              $i++;
              $img = has_post_thumbnail()
                  ? get_the_post_thumbnail(get_the_ID(), 'pcp-card', ['loading' => 'lazy'])
                  : pcp_image(null, 'pcp-card', '', ['loading' => 'lazy'], 'assets/images/' . $fb, get_the_title());
              ?>
              <a href="<?php the_permalink(); ?>" class="svc-tile reveal">
                <div class="svc-tile-img">
                  <?php echo $img; ?>
                </div>
                <div class="svc-tile-body">
                  <h3><?php the_title(); ?></h3>
                  <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
                  <span class="svc-tile-link">Learn more <?php echo pcp_icon('arrow'); ?></span>
                </div>
              </a>
          <?php endwhile;
      endif;
      ?>
    </div>
  </div>
</section>

<?php
get_template_part('template-parts/cta-band', null, ['extra_class' => 'cta-mid']);
get_template_part('template-parts/contact');
get_footer();
