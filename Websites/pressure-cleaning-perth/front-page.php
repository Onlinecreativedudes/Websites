<?php
/**
 * Front page (Home). Ported from Homepage.html. Every section renders the design
 * copy by default and is overridable from the Home page editor (ACF group_pcp_home).
 */
if (!defined('ABSPATH')) { exit; }
get_header();

$phone_d = pcp_phone_display();
$phone_t = pcp_phone_tel();
$quote_url = pcp_field('nav_quote_url', home_url('/contact/'), 'option');
$services_url = pcp_archive_url('service', '/services/');

// ---- defaults that mirror the design, used when an ACF repeater is empty ----
$d_hero_bullets = ['60+ Five-Star Reviews', '10+ Years Experience', 'Licensed & Insured', 'Iron Clad Guarantee'];
$d_trust = [
    ['$20M Public Liability', 'Fully insured, every job'],
    ['Family-Owned', 'A local business, not a franchise'],
    ['Before & After Photos', 'Proof on every single job'],
    ['Iron Clad Guarantee', 'If it is not right, we fix it'],
];
$d_services = [
    ['driveway-pavers.jpg', 'Driveway pavers being pressure cleaned', 'Driveway Cleaning', 'Perth driveways cop a beating from dust, oil, moss and tyre marks. We restore concrete, limestone, exposed aggregate and brick paving to a condition you will actually be proud of.'],
    ['roof-tiles-clean.jpg', 'Tile roof being pressure cleaned in Perth', 'Roof Cleaning & Restoration', 'Lichen, algae and built-up debris age your roof faster and trap heat in your home. A thorough pressure clean and treatment restores your roof and protects it for years ahead.'],
    ['pool-pavers-vacuum.jpg', 'Pool paver cleaning with vacuum recovery', 'Patio & Paver Cleaning', 'Outdoor entertaining areas need more than a sweep. We clean and restore paved surfaces, including delicate limestone, without causing damage, and can seal them to keep them cleaner for longer.'],
    ['stone-tiles-clean.jpg', 'Exterior stone tile pressure wash', 'House Washing', 'Mould, grime and oxidisation build up on rendered, brick and cladded surfaces over time. A professional exterior house wash removes the grime and restores your home\'s street appeal.'],
    ['commercial-repco.jpg', 'Commercial building wash-down in Perth', 'Commercial Pressure Cleaning', 'We service shopping centres, hospitals, schools, factory floors, car parks and cool rooms. Jobs are scheduled to minimise disruption, and all work meets the insurance and compliance requirements of commercial sites.'],
    ['spray-nozzle.jpg', 'Soft wash spray nozzle close-up', 'Soft Wash & Specialised', 'Some surfaces cannot handle high pressure. Our soft wash technique uses controlled low pressure and cleaning agents to get the same result without the risk. We also handle graffiti removal and vacuum recovery.'],
];
$d_why = [
    ['Clear Pricing', 'What we quote is what you pay. No surprise charges when the job is done.'],
    ['Right Technique, Every Time', 'High pressure where it is safe. Soft wash where it is not. We do not use one setting on everything.'],
    ['Fully Insured', '$20 million public liability. You are covered, and so are your neighbours.'],
    ['Iron Clad Guarantee', 'One of the strongest service guarantees in Australia. If it is not right, we will fix it.'],
    ['Specialist Equipment', 'Professional-grade gear built for Perth\'s surfaces: limestone, aggregate, tiling and more.'],
    ['You Will See the Results', 'Before and after photos on every job. No guessing whether the service was worth it.'],
];
$d_process = [
    ['Get in Touch', 'Call us or send the form. Tell us what needs cleaning. We respond fast with a clear, no-obligation quote.'],
    ['We Scope & Price', 'We read the surface, agree the scope of work, and confirm the price up front. No surprises.'],
    ['We Clean & Show Proof', 'We turn up on time, use the right method, clean up after ourselves, and hand you before and after photos.'],
];
$d_reviews = [
    ['SR', 'Sue Rutherford', 'Perth homeowner', 'Totally thrilled with the result and could not fault your professionalism.'],
    ['JK', 'Jamie Kay', 'Bayswater', 'Such a professional job on our tiled roof. It looks amazing, and the clean-up afterwards was excellent.'],
    ['JD', 'Jon Dunn', 'Celtic Builders', 'Found both his attitude to his work and attention to detail well worth the investment.'],
];
$d_faqs = [
    ['How much does pressure cleaning cost in Perth?', 'It depends on the surface, the size and the condition, so we quote each job after a quick look. You\'ll get a clear, itemised, no-obligation price up front with no hidden costs. Call ' . $phone_d . ' for a free quote.'],
    ['Is pressure cleaning safe for my roof, render or pavers?', 'Yes, when it\'s matched to the surface. We use high pressure where it\'s safe, soft washing on delicate surfaces like render and painted timber, and the correct pressure for porous materials like limestone, so you get a clean result without damage.'],
    ['How often should I have my property pressure cleaned?', 'Most Perth homes benefit from a clean every year or two, depending on tree cover, shade and how quickly mould takes hold. Commercial sites and strata common areas usually need a regular schedule, which we\'re happy to set up.'],
    ['Do you do both residential and commercial pressure cleaning?', 'We do. We clean for homeowners, property and strata managers, and commercial clients including shopping centres, schools, hospitals, factories and cool rooms, all backed by full insurance and our guarantee.'],
];
$d_gallery = [
    ['driveway-pavers.jpg', 'Driveway pavers before and after', 'wide'],
    ['roof-tiles-clean.jpg', 'Freshly cleaned terracotta roof tiles', ''],
    ['backyard-tiles.jpg', 'Backyard tile pressure cleaning', ''],
    ['concrete-spray.jpg', 'Concrete pavement spray clean', ''],
    ['sidewalk-clean.jpg', 'Paver cleaning before and after', ''],
    ['warehouse-floor.jpg', 'Commercial warehouse floor pressure clean', 'wide'],
];
$d_local_points = ['Perth metro coverage across all suburbs', 'Bore stain and iron staining removal experience', 'Coastal property specialists', 'Responsive communication and quick turnaround on quotes'];
$d_sealing_points = [
    ['Penetrating & topical sealers', ' — the right product matched to your surface, not a one-size-fits-all coating'],
    ['Blocks oil, dirt & bore stains', ' from soaking in, so future cleans take half the work'],
    ['UV-stable & slip-rated', ' finishes available for pool surrounds, driveways and high-traffic commercial floors'],
    ['Bundled with a pressure clean', ' — save on the second mobilisation and lock in the price up front'],
];
$d_about_points = [
    'Family-owned and operated — you deal directly with the person doing the job',
    'Fully insured to $20M public liability — your property and your neighbours are covered',
    'Honest, fixed pricing — the quote you get is the price you pay, no day-of surprises',
    'Right method for every surface — limestone, render, tile, concrete; never one-size-fits-all',
    'Iron Clad Guarantee — if it\'s not right, we come back and make it right',
];

// ACF override helpers
$rows = function ($name) { $r = get_field($name); return (is_array($r) && $r) ? $r : null; };
$mobile_hero = pcp_field('mobile_hero_image', '', false);
?>

<section class="hero">
  <div class="wrap">
    <div class="reveal in">
      <?php pcp_mobile_hero(is_array($mobile_hero) ? $mobile_hero : null, 'Pressure cleaning in Perth'); ?>
      <span class="eyebrow"><span class="dot"></span> <?php echo esc_html(pcp_field('hero_eyebrow', 'Fully Insured · 5-Star Perth Pressure Cleaners')); ?></span>
      <h1><?php echo wp_kses_post(pcp_field('hero_heading', 'Pressure Cleaning Specialists<br>You Can <span class="hl">Trust</span> in Perth')); ?></h1>
      <p class="sub"><?php echo esc_html(pcp_field('hero_sub', 'Dirt, mould, oil stains and lichen are no match for a proper pressure clean. We show up on time, use the right method for every surface, and leave your property looking the way it should.')); ?></p>
      <ul class="hero-bullets">
        <?php foreach (($rows('hero_bullets') ?: array_map(fn($t) => ['text' => $t], $d_hero_bullets)) as $b) : ?>
          <li><?php echo pcp_icon('check'); ?> <?php echo esc_html(is_array($b) ? ($b['text'] ?? '') : $b); ?></li>
        <?php endforeach; ?>
      </ul>
      <div class="hero-cta">
        <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green btn-lg">Get a Free Quote</a>
        <a href="<?php echo esc_url($phone_t); ?>" class="btn btn-ghost btn-lg"><?php echo pcp_icon('phone'); ?> Call <?php echo esc_html($phone_d); ?></a>
      </div>
      <div class="hero-trust">
        <span class="stars" aria-hidden="true"><?php echo pcp_stars(5); ?></span>
        <?php echo esc_html(pcp_field('hero_rating_text', 'Rated 5.0 across 60+ Google reviews')); ?>
      </div>
    </div>

    <?php get_template_part('template-parts/lead-form', null, [
        'subtitle' => 'Clear pricing, fast response, no obligation. We will scope the job and give you a price before we start.',
        'form_id'  => (int) pcp_field('hero_form_id', 0),
    ]); ?>
  </div>
</section>

<div class="trust">
  <div class="wrap">
    <?php foreach (($rows('trust_items') ?: array_map(fn($t) => ['title' => $t[0], 'subtitle' => $t[1]], $d_trust)) as $i => $t) :
        $icons = ['shield', 'home', 'image', 'guarantee'];
    ?>
      <div class="trust-item"><span class="ico"><?php echo pcp_icon('check'); ?></span><div><b><?php echo esc_html($t['title'] ?? ''); ?></b><span><?php echo esc_html($t['subtitle'] ?? ''); ?></span></div></div>
    <?php endforeach; ?>
  </div>
</div>

<section class="pad-sm">
  <div class="wrap">
    <div class="sec-head reveal" style="max-width:860px">
      <span class="kicker g"><?php echo esc_html(pcp_field('intro_kicker', 'Pressure Cleaning Perth')); ?></span>
      <h2><?php echo esc_html(pcp_field('intro_heading', "Perth's pressure cleaning specialists")); ?></h2>
    </div>
    <div class="reveal" style="max-width:860px;margin:0 auto;color:var(--muted);font-size:1.05rem;line-height:1.78;display:grid;gap:16px">
      <?php echo wp_kses_post(pcp_field('intro_body', '<p>Pressure Cleaning Perth is a family-owned pressure cleaning service that does one thing and does it properly: bringing your exterior surfaces back to life. We pressure clean roofs, driveways, walls, patios, pool surrounds and commercial sites right across the Perth metro, for homeowners, property and strata managers, and commercial clients.</p><p>Most cleaning companies offer pressure cleaning as a sideline. We don\'t. Pressure cleaning is all we do, all the time, which is exactly why we get a better, safer result on your roof, driveway or building than a general cleaner with a hired gurney. Our gear combines very high pressure with intense heat to lift grime, oil and mould that cold-water machines just smear around.</p><p>For delicate surfaces like render and painted timber we switch to soft washing, a gentler low-pressure method that cleans without damage. Where runoff matters, we use vacuum recovery to capture the dirty water as we work. Every job is fully insured to $20 million and backed by our 8 Point Code of Conduct and Iron Clad Guarantee, so if you\'re not happy, we make it right before you pay.</p>')); ?>
    </div>
  </div>
</section>

<section class="services pad" id="services">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker g"><?php echo esc_html(pcp_field('services_kicker', 'Pressure Cleaning Services')); ?></span>
      <h2><?php echo esc_html(pcp_field('services_heading', 'Whatever the surface, we clean it properly')); ?></h2>
      <p><?php echo esc_html(pcp_field('services_intro', 'A grimy driveway, a lichen-covered roof, or a commercial building that needs a full wash-down. We have the equipment and the expertise to get it right.')); ?></p>
    </div>
    <div class="svc-grid">
      <?php
      $svc_rows = $rows('services');
      if ($svc_rows) {
          foreach ($svc_rows as $s) : ?>
            <article class="svc reveal">
              <div class="ph"><?php echo pcp_image($s['image'] ?? null, 'pcp-card', '', ['loading' => 'lazy'], '', ''); ?></div>
              <div class="svc-body">
                <h3><?php echo esc_html($s['title'] ?? ''); ?></h3>
                <p><?php echo esc_html($s['body'] ?? ''); ?></p>
                <a href="<?php echo esc_url($quote_url); ?>" class="svc-link">Get a Quote <?php echo pcp_icon('arrow'); ?></a>
              </div>
            </article>
          <?php endforeach;
      } else {
          foreach ($d_services as $s) : ?>
            <article class="svc reveal">
              <div class="ph"><img src="<?php echo esc_url(PCP_THEME_URI . '/assets/images/' . $s[0]); ?>" alt="<?php echo esc_attr($s[1]); ?>" width="800" height="600" loading="lazy" decoding="async"></div>
              <div class="svc-body">
                <h3><?php echo esc_html($s[2]); ?></h3>
                <p><?php echo esc_html($s[3]); ?></p>
                <a href="<?php echo esc_url($quote_url); ?>" class="svc-link">Get a Quote <?php echo pcp_icon('arrow'); ?></a>
              </div>
            </article>
          <?php endforeach;
      }
      ?>
    </div>
    <div class="svc-more">
      <a href="<?php echo esc_url($services_url); ?>" class="btn btn-blue btn-lg"><?php echo esc_html(pcp_field('services_more_label', 'View All Pressure Cleaning Services')); ?></a>
    </div>
  </div>
</section>

<section class="sealing" id="sealing">
  <div class="sealing-content reveal">
    <span class="kicker"><?php echo esc_html(pcp_field('sealing_kicker', 'Premium Sealing Services')); ?></span>
    <h2><?php echo wp_kses_post(pcp_field('sealing_heading', 'Lock in the clean. <span class="hl">Seal it for longer.</span>')); ?></h2>
    <p><?php echo wp_kses_post(pcp_field('sealing_body', 'Cleaning is only half the job. Sealing your concrete, pavers, limestone or exposed aggregate keeps the dirt, oil, weeds and bore stains <em>out</em>, so your surfaces stay looking the way we left them — for years longer, not weeks.')); ?></p>
    <ul class="sealing-list">
      <?php
      $seal_rows = $rows('sealing_points');
      if ($seal_rows) {
          foreach ($seal_rows as $p) : ?>
            <li><?php echo pcp_icon('check'); ?><span><?php echo wp_kses_post($p['text'] ?? ''); ?></span></li>
          <?php endforeach;
      } else {
          foreach ($d_sealing_points as $p) : ?>
            <li><?php echo pcp_icon('check'); ?><span><b><?php echo esc_html($p[0]); ?></b><?php echo esc_html($p[1]); ?></span></li>
          <?php endforeach;
      }
      ?>
    </ul>
    <div class="hero-cta">
      <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green btn-lg">Get a Sealing Quote</a>
      <a href="<?php echo esc_url($phone_t); ?>" class="btn btn-ghost"><?php echo pcp_icon('phone'); ?> <?php echo esc_html($phone_d); ?></a>
    </div>
  </div>
  <div class="sealing-img reveal">
    <span class="sealing-badge"><?php echo esc_html(pcp_field('sealing_badge', 'Seal & Protect')); ?></span>
    <?php
    $seal_img = pcp_field('sealing_image', '', false);
    echo pcp_image(is_array($seal_img) ? $seal_img : null, 'pcp-wide', '', ['loading' => 'lazy'], 'assets/images/roof-tiles-clean.jpg', 'Freshly sealed glossy terracotta roof tiles');
    ?>
  </div>
</section>

<section class="why pad">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker" style="color:var(--cyan);background:rgba(127,202,238,.14)"><?php echo esc_html(pcp_field('why_kicker', 'Why Perth Chooses Us')); ?></span>
      <h2><?php echo esc_html(pcp_field('why_heading', 'A specialist, not a handyman with a borrowed gurney')); ?></h2>
      <p><?php echo esc_html(pcp_field('why_intro', 'A local pressure cleaning specialist who knows Perth surfaces, Perth conditions, and what Perth property owners expect.')); ?></p>
    </div>
    <div class="why-grid">
      <?php foreach (($rows('why_cells') ?: array_map(fn($w) => ['title' => $w[0], 'body' => $w[1]], $d_why)) as $w) : ?>
        <div class="why-cell reveal"><span class="ico"><?php echo pcp_icon('check'); ?></span><h3><?php echo esc_html($w['title'] ?? ''); ?></h3><p><?php echo esc_html($w['body'] ?? ''); ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="about-owner pad" id="about">
  <div class="wrap">
    <div class="reveal">
      <span class="kicker g"><?php echo esc_html(pcp_field('about_kicker', 'About Pressure Cleaning Perth')); ?></span>
      <h2><?php echo esc_html(pcp_field('about_heading', 'A Perth team that takes the job seriously')); ?></h2>
      <ul class="check-list">
        <?php foreach (($rows('about_points') ?: array_map(fn($t) => ['text' => $t], $d_about_points)) as $p) : ?>
          <li><?php echo pcp_icon('check'); ?> <?php echo esc_html(is_array($p) ? ($p['text'] ?? '') : $p); ?></li>
        <?php endforeach; ?>
      </ul>
      <div class="owner-quote">
        <p><?php echo wp_kses_post(pcp_field('about_quote', "<b>G'day, I'm Jamie</b> — We started Pressure Cleaning Perth because too many people were being let down by general cleaners using the wrong gear on the wrong surfaces. We do one thing, we do it properly, and we stand behind every job.")); ?></p>
      </div>
    </div>
    <div class="reveal owner-media">
      <div class="ph owner-mascot"><?php echo pcp_image(pcp_field('about_image', '', false) ?: null, 'pcp-portrait', '', [], 'assets/brand/mascot.png', 'Pressure Cleaning Perth mascot'); ?></div>
      <div class="stat-card">
        <b><?php echo esc_html(pcp_field('about_stat_number', '100%')); ?></b>
        <span><?php echo esc_html(pcp_field('about_stat_label', 'Satisfaction Rate')); ?></span>
      </div>
      <div class="ins-pill"><?php echo esc_html(pcp_field('about_insurance_pill', '$20,000,000 Public Liability')); ?></div>
    </div>
  </div>
</section>

<section class="pad-sm">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker"><?php echo esc_html(pcp_field('process_kicker', 'How It Works')); ?></span>
      <h2><?php echo esc_html(pcp_field('process_heading', 'Three steps to a cleaner property')); ?></h2>
    </div>
    <div class="proc-grid">
      <?php foreach (($rows('process_steps') ?: array_map(fn($p) => ['title' => $p[0], 'body' => $p[1]], $d_process)) as $p) : ?>
        <div class="proc reveal"><span class="pill"><?php echo pcp_icon('check'); ?></span><h3><?php echo esc_html($p['title'] ?? ''); ?></h3><p><?php echo esc_html($p['body'] ?? ''); ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="local pad">
  <div class="wrap">
    <div class="reveal">
      <span class="kicker g"><?php echo esc_html(pcp_field('local_kicker', 'Local Knowledge')); ?></span>
      <h2><?php echo esc_html(pcp_field('local_heading', 'We know Perth surfaces better than anyone')); ?></h2>
      <p><?php echo esc_html(pcp_field('local_body', "Perth's climate is harsh on external surfaces. High UV, coastal salt air, limestone soils and bore water staining all create cleaning challenges that are not the same anywhere else in Australia. That local knowledge means we choose the right approach every time, and get results that last. We cover the full Perth metro, from Fremantle, Cottesloe and the western suburbs to Joondalup, Midland, Rockingham, Mandurah and out to the hills.")); ?></p>
      <ul class="check-list">
        <?php foreach (($rows('local_points') ?: array_map(fn($t) => ['text' => $t], $d_local_points)) as $p) : ?>
          <li><?php echo pcp_icon('check'); ?> <?php echo esc_html(is_array($p) ? ($p['text'] ?? '') : $p); ?></li>
        <?php endforeach; ?>
      </ul>
      <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green btn-lg">Get a Free Quote</a>
    </div>
    <div class="reveal">
      <div class="ph" style="aspect-ratio:4/3"><?php echo pcp_image(pcp_field('local_image', '', false) ?: null, 'pcp-card', '', ['loading' => 'lazy'], 'assets/images/roof-clean-aerial.jpg', 'Freshly cleaned Perth tile roof'); ?></div>
    </div>
  </div>
</section>

<section class="reviews pad" id="reviews">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker"><?php echo esc_html(pcp_field('reviews_kicker', 'Social Proof')); ?></span>
      <h2><?php echo esc_html(pcp_field('reviews_heading', 'What Perth property owners say')); ?></h2>
      <p><?php echo esc_html(pcp_field('reviews_intro', 'Real feedback from customers who have seen the before-and-after difference first-hand.')); ?></p>
    </div>
    <div class="rev-grid">
      <?php foreach (($rows('reviews') ?: array_map(fn($r) => ['initials' => $r[0], 'name' => $r[1], 'location' => $r[2], 'quote' => $r[3]], $d_reviews)) as $r) : ?>
        <div class="rev reveal">
          <span class="q">&ldquo;</span>
          <span class="stars"><?php echo pcp_stars(5); ?></span>
          <p><?php echo esc_html($r['quote'] ?? ''); ?></p>
          <div class="who"><span class="av"><?php echo esc_html($r['initials'] ?? ''); ?></span><div><b><?php echo esc_html($r['name'] ?? ''); ?></b><span><?php echo esc_html($r['location'] ?? ''); ?></span></div></div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="rev-foot reveal">
      <span class="stars" style="color:#ffc83d"><?php echo pcp_stars(5); ?></span>
      <span class="big"><?php echo esc_html(pcp_field('reviews_rating_big', '5.0 out of 5 on Google')); ?></span>
      <span style="color:var(--muted)"><?php echo esc_html(pcp_field('reviews_rating_sub', 'Based on 60+ verified reviews from Perth customers')); ?></span>
    </div>
  </div>
</section>

<section class="pad-sm gal" id="gallery">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker g"><?php echo esc_html(pcp_field('gallery_kicker', 'Portfolio')); ?></span>
      <h2><?php echo esc_html(pcp_field('gallery_heading', 'Take a look at some of our cleaned surfaces')); ?></h2>
    </div>
    <div class="gal-grid reveal">
      <?php
      $gal = get_field('gallery_images');
      if (is_array($gal) && $gal) {
          foreach ($gal as $i => $img) {
              $cls = ($i === 0 || $i === count($gal) - 1) ? 'ph wide' : 'ph';
              $style = ($cls === 'ph wide') ? ' style="aspect-ratio:2/1"' : '';
              echo '<div class="' . $cls . '"' . $style . '>' . pcp_image($img, 'pcp-card', '', ['loading' => 'lazy']) . '</div>';
          }
      } else {
          foreach ($d_gallery as $g) {
              $cls = $g[2] === 'wide' ? 'ph wide' : 'ph';
              $style = $g[2] === 'wide' ? ' style="aspect-ratio:2/1"' : '';
              printf('<div class="%s"%s><img src="%s" alt="%s" width="800" height="600" loading="lazy" decoding="async"></div>',
                  $cls, $style, esc_url(PCP_THEME_URI . '/assets/images/' . $g[0]), esc_attr($g[1]));
          }
      }
      ?>
    </div>
  </div>
</section>

<section class="faqs pad" id="faqs">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker" style="color:var(--cyan);background:rgba(127,202,238,.14)"><?php echo esc_html(pcp_field('faqs_kicker', 'FAQs')); ?></span>
      <h2><?php echo esc_html(pcp_field('faqs_heading', 'Pressure cleaning questions, answered')); ?></h2>
      <p><?php echo esc_html(pcp_field('faqs_intro', 'The things Perth property owners ask us most before booking a pressure clean.')); ?></p>
    </div>
    <div class="faq-grid">
      <?php foreach (($rows('faqs') ?: array_map(fn($f) => ['question' => $f[0], 'answer' => $f[1]], $d_faqs)) as $f) : ?>
        <div class="faq-cell reveal"><h3><?php echo esc_html($f['question'] ?? ''); ?></h3><p><?php echo esc_html($f['answer'] ?? ''); ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
get_template_part('template-parts/cta-band');
get_template_part('template-parts/contact');
get_footer();
