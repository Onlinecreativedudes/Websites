<?php
/**
 * Single service. Ported from Internal_Service_Page_Template.html. The h1 is the
 * service title; every section renders the design copy by default and is
 * overridable from the service editor (ACF group_pcp_service).
 */
if (!defined('ABSPATH')) { exit; }
get_header();

$phone_d   = pcp_phone_display();
$phone_t   = pcp_phone_tel();
$quote_url = pcp_field('nav_quote_url', home_url('/contact/'), 'option');
$services_url = pcp_archive_url('service', '/services/');

// ACF override helper: return a repeater's rows or null when empty.
$rows = function ($name) { $r = get_field($name); return (is_array($r) && $r) ? $r : null; };

// ---- defaults that mirror the design, used when an ACF field/repeater is empty ----
$d_hero_bullets = ['Fully Insured to $20M', 'Family-Owned Perth Business', '5.0 Google Rating', 'Iron Clad Guarantee'];
$d_quote_points = ['Free on-site inspection', 'Fixed pricing — no surprises', 'Backed by our Iron Clad Guarantee'];
$d_problem_points = [
    '<b>Porous, brittle tiles</b> that absorb water',
    '<b>Blocked valleys &amp; gutters</b> that pool water',
    '<b>Lifted surface coatings</b> from spreading growth',
    '<b>Years off the lifespan</b> of the roof you paid for',
];
$d_methods = [
    ['Premium Clean', 'Most Popular', 'We treat the roof to kill black mould and lichen first so it stays cleaner for longer, then high-pressure wash the roof and gutters at the correct PSI for your tile type.', ['Mould &amp; lichen treatment', 'High-pressure wash to roof &amp; gutters', 'Free gutter clean included', 'Longest-lasting result']],
    ['Chemical-Free Clean', '', "Pressure washing only, including the gutters, for owners who'd rather we skip any chemical treatment. Strong physical clean with no residue left behind.", ['No chemicals, ever', 'Full pressure wash to roof &amp; gutters', 'Free gutter clean included', 'Pet- &amp; garden-friendly']],
    ['Soft Chemical Clean', '', "A gentle low-pressure treatment with no walking on fragile tiles. Ideal for older or brittle roofs where we don't want to risk a single tile cracking underfoot.", ['Zero foot traffic on tiles', 'Safe for older / brittle roofs', 'Gentle treatment, real result', 'Recommended for terracotta']],
];
$d_why = [
    ['Family-owned &amp; operated', 'You deal with Jamie and the team directly, not a call centre and not a subcontractor.'],
    ['Fully insured to $20M', 'Public liability that covers your property and your neighbours — on every single job.'],
    ['Right pressure, right surface', 'We match pressure, heat and technique to your tiles. Clean result, no damage.'],
    ['Before &amp; after photos', 'You see exactly what you paid for — documented on every single job.'],
    ['Iron Clad Guarantee', "Our 8-Point Code of Conduct means if you're not happy, we make it right — before you pay."],
    ['Around your schedule', "Including after-hours work for commercial sites, so we're never in your way."],
];
$d_process = [
    ['Free on-site quote', "We inspect the surface, explain what's involved and give you a clear, itemised price up front. No surprises later."],
    ['We do the work properly', 'The right pressure, hot water where it helps, and a full clean-up afterwards. We treat your place like our own.'],
    ['You see the difference', "We walk the finished job with you and send before and after photos. You don't pay a cent until you're happy."],
];
$d_faqs = [
    ['Will pressure cleaning damage my surface?', "Not when it's done right. We set the pressure to suit the surface type and condition, and for older or fragile surfaces we use a no-pressure chemical clean instead. The whole point of inspecting first is to pick the method that gets a great result without harm."],
    ['Do you clean up afterwards?', 'Yes. We clear the debris that washes down during the job and leave the area tidy, including your neighbours\' if needed.'],
    ['How often should this be cleaned?', "It depends on tree cover, shade and how much growth has taken hold. We'll give you an honest read at the quote rather than push you into a schedule you don't need."],
    ['How long does the job take?', "Most single-storey homes are a half-day job; a larger or double-storey home is usually a full day. We'll confirm at the quote."],
];
$d_reviews = [
    ['SR', 'Sue Rutherford', 'Perth homeowner', 'Totally thrilled with the result and could not fault your professionalism.'],
    ['JK', 'Jamie Kay', 'Bayswater', 'Such a professional job on our tiled roof. It looks amazing, and the clean-up afterwards was excellent.'],
    ['JD', 'Jon Dunn', 'Celtic Builders', 'Found both his attitude to his work and attention to detail well worth the investment.'],
];
$d_gallery = [
    ['driveway-pavers.jpg', 'Driveway pavers before and after', 'wide'],
    ['roof-tiles-clean.jpg', 'Freshly cleaned terracotta roof tiles', ''],
    ['backyard-tiles.jpg', 'Backyard tile pressure cleaning', ''],
    ['concrete-spray.jpg', 'Concrete pavement spray clean', ''],
    ['orange-suit-pavers.jpg', 'Paver cleaning before and after', ''],
    ['warehouse-floor.jpg', 'Commercial warehouse floor pressure clean', 'wide'],
];
$d_areas = ['Perth CBD', 'Subiaco', 'Cottesloe', 'Claremont', 'Nedlands', 'Mount Lawley', 'Bayswater', 'Inglewood', 'Morley', 'Joondalup', 'Hillarys', 'Scarborough', 'Innaloo', 'Fremantle', 'South Perth', 'Como &middot; Applecross', 'Canning Vale', 'Cannington', 'Willetton', 'Rockingham', 'Mandurah', 'Kalamunda', 'Mundaring', 'Ellenbrook', '+ all greater Perth'];

$alert_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 9v4M12 17h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>';
$shield_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>';

$hero_img = pcp_field('hero_image', '', false);
$mobile_hero = pcp_field('mobile_hero_image', '', false);
$title = get_the_title();
?>

<?php while (have_posts()) : the_post(); ?>

<section class="hero hero-svc">
  <div class="wrap">
    <div class="reveal in">
      <?php pcp_mobile_hero(is_array($mobile_hero) ? $mobile_hero : null, $title); ?>
      <nav class="crumbs" aria-label="Breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
        <a href="<?php echo esc_url($services_url); ?>">Pressure Cleaning</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
        <span><?php echo esc_html($title); ?></span>
      </nav>
      <?php $eyebrow = pcp_field('hero_eyebrow', ''); ?>
      <?php if ($eyebrow) : ?><span class="eyebrow"><span class="dot"></span> <?php echo esc_html($eyebrow); ?></span><?php endif; ?>
      <h1><?php the_title(); ?></h1>
      <p class="sub"><?php echo esc_html(pcp_field('hero_sub', "A surface covered in moss, grime and stains doesn't just look tired — it ages the material and drags down the look of the whole property. We bring it back to a clean, even finish using the right method, with no damage and no mess left behind.")); ?></p>
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
        'title'    => 'Free Quote',
        'subtitle' => "Tell us about the job and we'll come back fast with a clear, no-obligation price.",
        'form_id'  => (int) pcp_field('quote_form_id', 0),
    ]); ?>
  </div>
</section>

<section class="quote-band">
  <div class="wrap">
    <div class="qb-card reveal">
      <div class="qb-left">
        <span class="kicker g"><?php echo esc_html(pcp_field('quote_kicker', 'Free, No-Obligation Quote')); ?></span>
        <h2><?php echo wp_kses_post(pcp_field('quote_heading', 'Tell us about the job — <span>we\'ll come back fast.</span>')); ?></h2>
        <p><?php echo esc_html(pcp_field('quote_body', 'Every quote starts with a real inspection. We pick the right method for your surface, give you a clear itemised price up front, and book you in around your schedule.')); ?></p>
        <ul class="qb-bullets">
          <?php foreach (($rows('quote_points') ?: array_map(fn($t) => ['text' => $t], $d_quote_points)) as $p) : ?>
            <li><?php echo pcp_icon('check'); ?> <?php echo esc_html(is_array($p) ? ($p['text'] ?? '') : $p); ?></li>
          <?php endforeach; ?>
        </ul>
        <div class="qb-call">
          <span class="ico"><?php echo pcp_icon('phone'); ?></span>
          <div><a href="<?php echo esc_url($phone_t); ?>"><b><?php echo esc_html($phone_d); ?></b></a><span>Or call us directly — we answer fast</span></div>
        </div>
      </div>
      <div class="qb-form lead-card" style="margin:0;padding:0;box-shadow:none;background:transparent">
        <?php get_template_part('template-parts/lead-form', null, [
            'title'    => 'Free Quote',
            'subtitle' => "No obligation. We'll get back to you shortly.",
            'form_id'  => (int) pcp_field('quote_form_id', 0),
        ]); ?>
      </div>
    </div>
  </div>
</section>

<section class="pad-sm">
  <div class="wrap">
    <div class="sec-head reveal" style="max-width:880px">
      <span class="kicker g"><?php echo esc_html(pcp_field('intro_kicker', $title . ' Perth')); ?></span>
      <h2><?php echo esc_html(pcp_field('intro_heading', 'Specialist cleaning, done the right way for your surface')); ?></h2>
    </div>
    <div class="reveal" style="max-width:880px;margin:0 auto;color:var(--muted);font-size:1.06rem;line-height:1.78;display:grid;gap:16px">
      <?php
      $intro = pcp_field('intro_body', '');
      if ($intro) {
          echo wp_kses_post($intro);
      } elseif (trim(get_the_content()) !== '') {
          the_content();
      } else {
          echo '<p>Pressure Cleaning Perth is a family-owned specialist serving the full Perth metro. We clean across residential, strata and commercial properties — safely, thoroughly, and with no risk to your surfaces.</p>';
          echo '<p>Every job starts with a free on-site inspection. We pick the right method, give you a clear itemised price up front, and back the work with our Iron Clad Guarantee. Call <a href="' . esc_url($phone_t) . '" style="color:var(--blue);font-weight:700">' . esc_html($phone_d) . '</a> and we\'ll get straight back to you.</p>';
      }
      ?>
    </div>
  </div>
</section>

<section class="problem">
  <div class="problem-img reveal r-left">
    <?php echo pcp_image(pcp_field('problem_image', '', false) ?: null, 'pcp-wide', '', ['loading' => 'lazy'], 'assets/images/roof-tiles-clean.jpg', 'Moss and lichen on a Perth surface'); ?>
    <span class="problem-badge"><?php echo esc_html(pcp_field('problem_badge', 'The Problem')); ?></span>
  </div>
  <div class="problem-content reveal r-right">
    <span class="kicker" style="color:#cbf2a3;background:rgba(109,211,60,.18);border:1px solid rgba(109,211,60,.35)"><?php echo esc_html(pcp_field('problem_kicker', 'Why It Matters')); ?></span>
    <h2><?php echo wp_kses_post(pcp_field('problem_heading', 'Moss and lichen do <span class="hl">more damage</span> than you think')); ?></h2>
    <?php
    $problem_body = pcp_field('problem_body', '');
    if ($problem_body) {
        echo wp_kses_post(wpautop($problem_body));
    } else {
        echo '<p>Perth surfaces cop everything: summer sun, winter damp, and enough airborne spores to turn a surface green within a few seasons. Once moss and lichen take hold they trap moisture, lift the surface coating and spread fast.</p>';
        echo '<p>Left alone, that means porous materials, blocked drainage and a surface that ages years before it should. A proper clean removes the growth, restores the look and buys you a lot more life out of what you have already paid for.</p>';
    }
    ?>
    <ul class="dmg-list">
      <?php foreach (($rows('problem_points') ?: array_map(fn($t) => ['text' => $t], $d_problem_points)) as $p) : ?>
        <li><?php echo $alert_svg; ?> <span><?php echo wp_kses_post(is_array($p) ? ($p['text'] ?? '') : $p); ?></span></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="methods pad" id="methods">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker g"><?php echo esc_html(pcp_field('methods_kicker', 'Our Methods')); ?></span>
      <h2><?php echo esc_html(pcp_field('methods_heading', 'How we clean your surface')); ?></h2>
      <p><?php echo esc_html(pcp_field('methods_intro', 'We start with a full inspection, then recommend the right approach. The options depend on the age and condition of the surface.')); ?></p>
    </div>
    <div class="method-grid">
      <?php
      $method_rows = $rows('methods');
      if ($method_rows) {
          $i = 0;
          foreach ($method_rows as $m) :
              $i++;
              $features = (is_array($m['methods_features'] ?? null) && $m['methods_features']) ? $m['methods_features'] : [];
      ?>
        <article class="method reveal">
          <div class="method-num"><span><?php echo esc_html(sprintf('%02d', $i)); ?></span><?php if (!empty($m['tag'])) : ?><b class="method-tag"><?php echo esc_html($m['tag']); ?></b><?php endif; ?></div>
          <h3><?php echo esc_html($m['title'] ?? ''); ?></h3>
          <p><?php echo esc_html($m['body'] ?? ''); ?></p>
          <?php if ($features) : ?>
          <ul>
            <?php foreach ($features as $f) : ?>
              <li><?php echo pcp_icon('check'); ?> <?php echo esc_html($f['text'] ?? ''); ?></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </article>
      <?php endforeach;
      } else {
          $i = 0;
          foreach ($d_methods as $m) :
              $i++;
      ?>
        <article class="method reveal">
          <div class="method-num"><span><?php echo esc_html(sprintf('%02d', $i)); ?></span><?php if ($m[1]) : ?><b class="method-tag"><?php echo esc_html($m[1]); ?></b><?php endif; ?></div>
          <h3><?php echo esc_html($m[0]); ?></h3>
          <p><?php echo esc_html($m[2]); ?></p>
          <ul>
            <?php foreach ($m[3] as $feat) : ?>
              <li><?php echo pcp_icon('check'); ?> <?php echo wp_kses_post($feat); ?></li>
            <?php endforeach; ?>
          </ul>
        </article>
      <?php endforeach;
      }
      ?>
    </div>
    <div class="method-note reveal">
      <?php echo $shield_svg; ?>
      <span><?php echo esc_html(pcp_field('methods_note', "We work to WorkSafe guidelines and our own strict height-safety standards, so you never have to get on the roof yourself. Full clean-up around your home and your neighbours' if needed.")); ?></span>
    </div>
  </div>
</section>

<section class="why pad">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker" style="color:var(--cyan);background:rgba(127,202,238,.14)"><?php echo esc_html(pcp_field('why_kicker', 'Why Perth Chooses Us')); ?></span>
      <h2><?php echo esc_html(pcp_field('why_heading', 'One of the strongest guarantees in Australia')); ?></h2>
      <p><?php echo esc_html(pcp_field('why_intro', "Pressure cleaning is all we do. Here's what that means for your property.")); ?></p>
    </div>
    <div class="why-grid">
      <?php foreach (($rows('why_cells') ?: array_map(fn($w) => ['title' => $w[0], 'body' => $w[1]], $d_why)) as $w) : ?>
        <div class="why-cell reveal"><span class="ico"><?php echo pcp_icon('check'); ?></span><h3><?php echo wp_kses_post($w['title'] ?? ''); ?></h3><p><?php echo wp_kses_post($w['body'] ?? ''); ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="pad-sm">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker g"><?php echo esc_html(pcp_field('process_kicker', 'What To Expect')); ?></span>
      <h2><?php echo esc_html(pcp_field('process_heading', 'From free quote to spotless surface')); ?></h2>
      <p><?php echo esc_html(pcp_field('process_intro', "Three steps, no surprises. You don't pay a cent until you're happy.")); ?></p>
    </div>
    <ol class="proc-grid">
      <?php foreach (($rows('process_steps') ?: array_map(fn($p) => ['title' => $p[0], 'body' => $p[1]], $d_process)) as $idx => $p) : ?>
        <li class="proc reveal"><div class="proc-num"><?php echo esc_html($idx + 1); ?></div><h3><?php echo esc_html($p['title'] ?? ''); ?></h3><p><?php echo esc_html($p['body'] ?? ''); ?></p></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<?php
$midcta_img = pcp_field('midcta_image', '', false);
$midcta_url = '';
if (is_array($midcta_img) && !empty($midcta_img['url'])) {
    $midcta_url = $midcta_img['url'];
} elseif (is_array($hero_img) && !empty($hero_img['url'])) {
    $midcta_url = $hero_img['url'];
} else {
    $midcta_url = PCP_THEME_URI . '/assets/images/concrete-spray.jpg';
}
?>
<section class="cta-band cta-mid pad" style="background-image:url('<?php echo esc_url($midcta_url); ?>')">
  <div class="wrap">
    <span class="kicker" style="color:#cbf2a3;background:rgba(109,211,60,.18);border:1px solid rgba(109,211,60,.35)"><?php echo esc_html(pcp_field('midcta_kicker', 'Free, No-Obligation Quote')); ?></span>
    <h2><?php echo wp_kses_post(pcp_field('midcta_heading', 'Let\'s get it <span class="script">looking right again</span>')); ?></h2>
    <p><?php echo esc_html(pcp_field('midcta_body', "Call now or send through your details — we'll inspect, quote and book you in fast. Every job backed by our Iron Clad Guarantee.")); ?></p>
    <div class="hero-cta">
      <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green btn-lg">Get a Free Quote</a>
      <a href="<?php echo esc_url($phone_t); ?>" class="btn btn-ghost btn-lg"><?php echo pcp_icon('phone'); ?> <?php echo esc_html($phone_d); ?></a>
    </div>
  </div>
</section>

<section class="faqs pad" id="faqs">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker" style="color:var(--cyan);background:rgba(127,202,238,.14)"><?php echo esc_html(pcp_field('faqs_kicker', 'FAQs')); ?></span>
      <h2><?php echo esc_html(pcp_field('faqs_heading', 'Your questions, answered')); ?></h2>
      <p><?php echo esc_html(pcp_field('faqs_intro', 'The things Perth property owners ask us most before booking.')); ?></p>
    </div>
    <div class="faq-grid">
      <?php foreach (($rows('faqs') ?: array_map(fn($f) => ['question' => $f[0], 'answer' => $f[1]], $d_faqs)) as $f) : ?>
        <div class="faq-cell reveal"><h3><?php echo esc_html($f['question'] ?? ''); ?></h3><p><?php echo esc_html($f['answer'] ?? ''); ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="reviews pad" id="reviews">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="kicker"><?php echo esc_html(pcp_field('reviews_kicker', 'Our Reviews')); ?></span>
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
?>

<section class="areas">
  <div class="wrap">
    <div class="reveal">
      <h3><?php echo wp_kses_post(pcp_field('areas_heading', 'Areas <span>we service</span>')); ?></h3>
      <p class="intro"><?php echo esc_html(pcp_field('areas_intro', 'We cover the full Perth metro — from the western and riverside suburbs to the northern and southern corridors and out to the hills. If you are in greater Perth, we can help.')); ?></p>
    </div>
    <div class="area-chips reveal">
      <?php foreach (($rows('areas') ?: array_map(fn($a) => ['areas_name' => $a], $d_areas)) as $a) : ?>
        <span class="area-chip"><?php echo wp_kses_post(is_array($a) ? ($a['areas_name'] ?? '') : $a); ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
