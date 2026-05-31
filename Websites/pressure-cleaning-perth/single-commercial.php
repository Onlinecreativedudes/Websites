<?php
/**
 * Single Commercial / Industry entry. Ported from the Commercial Cleaning Service
 * page template. Every section renders the design copy by default and is overridable
 * per entry from the Commercial editor (ACF group_pcp_commercial).
 */
if (!defined('ABSPATH')) { exit; }
get_header();

$phone_d   = pcp_phone_display();
$phone_t   = pcp_phone_tel();
$quote_url = pcp_field('nav_quote_url', home_url('/contact/'), 'option');
$commercial_url = pcp_archive_url('commercial', '/commercial/');

// ACF override helper: returns the repeater rows if present, else null.
$rows = function ($name) { $r = get_field($name); return (is_array($r) && $r) ? $r : null; };

// ---- defaults that mirror the design, used when ACF is empty ----
$d_intro_body = '<p>A sparkling, well-presented complex attracts more high-quality business. A regular pressure clean will not only improve the visual appeal of your resort or complex, it will also reduce health and safety risks for your guests and your staff.</p><p>Our state-of-the-art pressure cleaning system allows us to recover and safely re-use the water used during the cleaning process. That means less water used, and less chance of slippery surfaces causing accidents after we have cleaned.</p>';
$d_services = [
    ['warehouse-floor.jpg', 'Concrete pressure cleaning', 'Concrete Pressure Cleaning', 'High pressure and heat lift grime, oil and mould from concrete across your site.'],
    ['driveway-pavers.jpg', 'Driveway pressure cleaning', 'Driveway Pressure Cleaning', 'Concrete, aggregate, pavers and brick driveways brought back to a clean finish.'],
    ['sidewalk-clean.jpg', 'Car park pressure cleaning', 'Car Park Pressure Cleaning', 'Oil drips, tyre marks and built-up grime cleared from car parks and entrances.'],
    ['concrete-spray.jpg', 'Pavement pressure cleaning', 'Pavement Pressure Cleaning', 'Walkways and pavements cleaned safely with water-recovery technology.'],
    ['stone-tiles-clean.jpg', 'Tile and grout pressure cleaning', 'Tile & Grout Pressure Cleaning', 'Indoor and outdoor tiled areas cleaned, with grout treatment and sealing options.'],
    ['pool-pavers-vacuum.jpg', 'Pool area pressure cleaning', 'BBQ & Pool Areas', 'Slip-safe, algae-free pool surrounds and entertaining areas across the complex.'],
];
$d_bullets = [
    ['Water-recovery system', ' — less waste, safer surfaces after cleaning'],
    ['After-hours scheduling', ' — we work around your peak guest times'],
    ['Fully insured to $20M', ' — cover your property and your patrons'],
    ['Regular service plans', ' — consistent presentation across the property year-round'],
];

// Hero values
$mobile_hero = pcp_field('mobile_hero_image', '', false);
$hero_eyebrow = pcp_field('hero_eyebrow', 'Commercial & Industrial Pressure Cleaning');
$hero_intro   = pcp_field('hero_intro', 'We pressure clean every exterior hard surface across your site, with water-recovery technology that keeps people safe and your operation moving.');
?>

<section class="hero hero-svc hero-industry"<?php
    $hero_img = pcp_field('hero_image', '', false);
    if (is_array($hero_img) && !empty($hero_img['ID'])) {
        $hero_src = wp_get_attachment_image_url((int) $hero_img['ID'], 'pcp-hero');
    } elseif (has_post_thumbnail()) {
        $hero_src = get_the_post_thumbnail_url(get_the_ID(), 'pcp-hero');
    } else {
        $hero_src = PCP_THEME_URI . '/assets/images/commercial-repco.jpg';
    }
    echo ' style="background-image:url(\'' . esc_url($hero_src) . '\')"';
?>>
  <div class="wrap">
    <div class="reveal in">
      <?php pcp_mobile_hero(is_array($mobile_hero) ? $mobile_hero : null, get_the_title()); ?>
      <nav class="crumbs" aria-label="Breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
        <?php echo pcp_icon('caret'); ?>
        <a href="<?php echo esc_url($commercial_url); ?>">Commercial &amp; Industrial</a>
        <?php echo pcp_icon('caret'); ?>
        <span><?php echo esc_html(get_the_title()); ?></span>
      </nav>
      <span class="eyebrow"><span class="dot"></span> <?php echo esc_html($hero_eyebrow); ?></span>
      <h1><?php the_title(); ?></h1>
      <p class="sub"><?php echo esc_html($hero_intro); ?></p>
      <div class="hero-cta">
        <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green btn-lg">Get a Free Quote</a>
        <a href="<?php echo esc_url($phone_t); ?>" class="btn btn-ghost btn-lg"><?php echo pcp_icon('phone'); ?> Call <?php echo esc_html($phone_d); ?></a>
      </div>
    </div>
  </div>
</section>

<section class="industry-intro pad-sm">
  <div class="wrap">
    <div class="ind-intro-grid">
      <div class="reveal r-left">
        <span class="kicker g"><?php echo esc_html(pcp_field('intro_kicker', 'Why Clients Choose Us')); ?></span>
        <h2><?php echo esc_html(pcp_field('intro_heading', 'Cleaner site, better impressions, safer people.')); ?></h2>
      </div>
      <div class="reveal r-right ind-intro-copy">
        <?php echo wp_kses_post(pcp_field('intro_body', $d_intro_body)); ?>
        <ul class="ind-bullets">
          <?php
          $bullet_rows = $rows('intro_bullets');
          if ($bullet_rows) {
              foreach ($bullet_rows as $b) : ?>
                <li><?php echo pcp_icon('check'); ?><span><?php echo wp_kses_post($b['text'] ?? ''); ?></span></li>
              <?php endforeach;
          } else {
              foreach ($d_bullets as $b) : ?>
                <li><?php echo pcp_icon('check'); ?><span><b><?php echo esc_html($b[0]); ?></b><?php echo esc_html($b[1]); ?></span></li>
              <?php endforeach;
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="industry-services pad-sm">
  <div class="wrap">
    <div class="sec-head reveal" style="text-align:left;max-width:none;display:flex;justify-content:space-between;align-items:end;flex-wrap:wrap;gap:24px">
      <div>
        <span class="kicker g"><?php echo esc_html(pcp_field('services_kicker', 'What We Clean')); ?></span>
        <h2 style="font-size:1.9rem"><?php echo esc_html(pcp_field('services_heading', 'Every hard surface across your site')); ?></h2>
      </div>
      <a href="<?php echo esc_url($phone_t); ?>" class="btn btn-ghost"><?php echo pcp_icon('phone'); ?> <?php echo esc_html($phone_d); ?></a>
    </div>
    <div class="ind-svc-grid">
      <?php
      $svc_rows = $rows('industry_services');
      if ($svc_rows) {
          foreach ($svc_rows as $s) :
              $s_img = $s['image'] ?? null;
              $has_img = is_array($s_img) && !empty($s_img['ID']);
          ?>
            <div class="ind-svc reveal">
              <span class="ind-ico"><?php echo $has_img ? pcp_image($s_img, 'pcp-card', '', ['loading' => 'lazy'], '', '') : pcp_icon('check'); ?></span>
              <h3><?php echo esc_html($s['title'] ?? ''); ?></h3>
            </div>
          <?php endforeach;
      } else {
          foreach ($d_services as $s) : ?>
            <div class="ind-svc reveal">
              <span class="ind-ico"><img src="<?php echo esc_url(PCP_THEME_URI . '/assets/images/' . $s[0]); ?>" alt="<?php echo esc_attr($s[1]); ?>" width="44" height="44" loading="lazy" decoding="async"></span>
              <h3><?php echo esc_html($s[2]); ?></h3>
            </div>
          <?php endforeach;
      }
      ?>
    </div>
    <p class="ind-svc-note reveal"><?php echo pcp_icon('check'); ?> <?php echo esc_html(pcp_field('services_note', 'If your site needs something not on this list, tell us about it. Chances are we already do it.')); ?></p>
  </div>
</section>

<?php
get_template_part('template-parts/cta-band', null, ['extra_class' => 'cta-mid']);
get_template_part('template-parts/contact');
get_footer();
