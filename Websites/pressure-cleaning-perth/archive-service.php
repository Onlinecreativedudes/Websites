<?php
/**
 * Services archive. Ported from Pressure_Cleaning_Services_Perth.tpl.html. Runs
 * The Loop over the `service` CPT to build the .svc-tiles grid; the hero, intro,
 * sealing band, reviews and CTA chrome come from the design.
 */
if (!defined('ABSPATH')) { exit; }
get_header();

$phone_d   = pcp_phone_display();
$phone_t   = pcp_phone_tel();
$quote_url = pcp_field('nav_quote_url', home_url('/contact/'), 'option');

$d_hero_bullets = ['Fully Insured to $20M', 'Family-Owned Perth Business', '5.0 Google Rating', 'Iron Clad Guarantee'];
$d_reviews = [
    ['SR', 'Sue Rutherford', 'Perth homeowner', 'Totally thrilled with the result and could not fault your professionalism.'],
    ['JK', 'Jamie Kay', 'Bayswater', 'Such a professional job on our tiled roof. It looks amazing, and the clean-up afterwards was excellent.'],
    ['JD', 'Jon Dunn', 'Celtic Builders', 'Found both his attitude to his work and attention to detail well worth the investment.'],
];
$d_sealing_points = [
    '<b>Right product, right surface</b> — penetrating and topical sealers matched to your job',
    '<b>Blocks oil, dirt and bore stains</b> from soaking in, so future cleans take half the work',
    '<b>Bundled with a pressure clean</b> — save on the second mobilisation and lock in the price up front',
];

$arrow_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';

$arch_mobile_hero = pcp_field('archive_mobile_hero_image', '', 'option');
?>

<section class="hero hero-svc hero-archive">
  <div class="wrap">
    <div class="reveal in">
      <?php pcp_mobile_hero(is_array($arch_mobile_hero) ? $arch_mobile_hero : null, 'Pressure cleaning services in Perth'); ?>
      <nav class="crumbs" aria-label="Breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
        <span>Pressure Cleaning</span>
      </nav>
      <span class="eyebrow"><span class="dot"></span> Specialist Pressure Cleaning Services</span>
      <h1>Perth Pressure Cleaning, <span class="hl">Done Properly</span></h1>
      <p class="sub">From rooftops to driveways, from limestone to graffiti — we bring the right method, the right pressure and the right pair of hands to every surface. Pick a service to learn more, or call us and we will tailor the right approach for your property.</p>
      <ul class="hero-bullets">
        <?php foreach ($d_hero_bullets as $b) : ?>
          <li><?php echo pcp_icon('check'); ?> <?php echo esc_html($b); ?></li>
        <?php endforeach; ?>
      </ul>
      <div class="hero-cta">
        <a href="#services-grid" class="btn btn-green btn-lg">Browse All Services</a>
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
      <span class="kicker g">Pressure Cleaning Services</span>
      <h2>The full range of pressure cleaning, all under one roof</h2>
      <p>Roofs, driveways, render, limestone, fences, courts and everything in between. We are the team Perth property owners call when they want it done properly — matched to the surface, finished cleanly, and backed by our Iron Clad Guarantee.</p>
    </div>
  </div>
</section>

<section class="services-archive pad-sm" id="services-grid">
  <div class="wrap">
    <div class="svc-tiles">
      <?php
      if (have_posts()) :
          while (have_posts()) : the_post();
              $hero_img = get_field('hero_image');
              $tag = '';
              ?>
        <a href="<?php the_permalink(); ?>" class="svc-tile reveal">
          <div class="svc-tile-img">
            <?php
            if (is_array($hero_img) && !empty($hero_img['ID'])) {
                echo pcp_image($hero_img, 'pcp-card', '', ['loading' => 'lazy']);
            } elseif (has_post_thumbnail()) {
                the_post_thumbnail('pcp-card', ['loading' => 'lazy']);
            } else {
                printf('<img src="%s" alt="%s" width="800" height="600" loading="lazy" decoding="async">',
                    esc_url(PCP_THEME_URI . '/assets/images/spray-nozzle.jpg'),
                    esc_attr(get_the_title() . ' in Perth'));
            }
            ?>
            <?php if ($tag) : ?><span class="svc-tag"><?php echo esc_html($tag); ?></span><?php endif; ?>
          </div>
          <div class="svc-tile-body">
            <h3><?php the_title(); ?></h3>
            <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
            <span class="svc-tile-link">Learn more <?php echo $arrow_svg; ?></span>
          </div>
        </a>
      <?php
          endwhile;
      else :
          ?>
        <p>No services have been published yet. Call us on <a href="<?php echo esc_url($phone_t); ?>"><?php echo esc_html($phone_d); ?></a> and we will tailor the right approach for your property.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="sealing" id="sealing">
  <div class="sealing-content reveal r-left">
    <span class="kicker" style="color:#cbf2a3;background:rgba(109,211,60,.18);border:1px solid rgba(109,211,60,.35)">Premium Sealing Services</span>
    <h2>Lock in the clean. <span class="hl">Seal it for longer.</span></h2>
    <p>Cleaning is only half the job. Sealing your concrete, pavers, limestone or exposed aggregate keeps the dirt, oil, weeds and bore stains <em>out</em>, so your surfaces stay looking the way we left them — for years longer, not weeks.</p>
    <ul class="sealing-list">
      <?php foreach ($d_sealing_points as $p) : ?>
        <li><?php echo pcp_icon('check'); ?><span><?php echo wp_kses_post($p); ?></span></li>
      <?php endforeach; ?>
    </ul>
    <div class="hero-cta">
      <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green btn-lg">Get a Sealing Quote</a>
      <a href="<?php echo esc_url($phone_t); ?>" class="btn btn-ghost"><?php echo pcp_icon('phone'); ?> <?php echo esc_html($phone_d); ?></a>
    </div>
  </div>
  <div class="sealing-img reveal r-right">
    <span class="sealing-badge">Seal &amp; Protect</span>
    <img src="<?php echo esc_url(PCP_THEME_URI . '/assets/images/roof-tiles-clean.jpg'); ?>" alt="Freshly sealed glossy terracotta roof tiles" width="800" height="600" loading="lazy" decoding="async">
  </div>
</section>

<section class="cta-band cta-mid pad" style="background-image:url('<?php echo esc_url(PCP_THEME_URI . '/assets/images/concrete-spray.jpg'); ?>')">
  <div class="wrap">
    <span class="kicker" style="color:#cbf2a3;background:rgba(109,211,60,.18);border:1px solid rgba(109,211,60,.35)">Free, No-Obligation Quote</span>
    <h2>Not sure which service <span class="script">you need?</span></h2>
    <p>Tell us about your property and we will come back fast with the right approach — and a clear, itemised price up front.</p>
    <div class="hero-cta">
      <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green btn-lg">Get a Free Quote</a>
      <a href="<?php echo esc_url($phone_t); ?>" class="btn btn-ghost btn-lg"><?php echo pcp_icon('phone'); ?> <?php echo esc_html($phone_d); ?></a>
    </div>
  </div>
</section>

<section class="reviews pad" id="reviews">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker">Our Reviews</span>
      <h2>What Perth property owners say</h2>
      <p>Real feedback from customers who have seen the before-and-after difference first-hand.</p>
    </div>
    <div class="rev-grid">
      <?php foreach ($d_reviews as $r) : ?>
        <div class="rev reveal">
          <span class="q">&ldquo;</span>
          <span class="stars"><?php echo pcp_stars(5); ?></span>
          <p><?php echo esc_html($r[3]); ?></p>
          <div class="who"><span class="av"><?php echo esc_html($r[0]); ?></span><div><b><?php echo esc_html($r[1]); ?></b><span><?php echo esc_html($r[2]); ?></span></div></div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="rev-foot reveal">
      <span class="stars" style="color:#ffc83d"><?php echo pcp_stars(5); ?></span>
      <span class="big">5.0 out of 5 on Google</span>
      <span style="color:var(--muted)">Based on 60+ verified reviews from Perth customers</span>
    </div>
  </div>
</section>

<div class="trust">
  <div class="wrap">
    <div class="trust-item"><span class="ico"><?php echo pcp_icon('check'); ?></span><div><b>$20M Public Liability</b><span>Fully insured, every job</span></div></div>
    <div class="trust-item"><span class="ico"><?php echo pcp_icon('check'); ?></span><div><b>Family-Owned</b><span>A local business, not a franchise</span></div></div>
    <div class="trust-item"><span class="ico"><?php echo pcp_icon('check'); ?></span><div><b>Before &amp; After Photos</b><span>Proof on every single job</span></div></div>
    <div class="trust-item"><span class="ico"><?php echo pcp_icon('check'); ?></span><div><b>Iron Clad Guarantee</b><span>If it is not right, we fix it</span></div></div>
  </div>
</div>

<?php
get_template_part('template-parts/cta-band');
get_template_part('template-parts/contact');
get_footer();
