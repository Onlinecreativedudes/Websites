<?php /* Template Name: Why Us */ if (!defined('ABSPATH')) { exit; } get_header(); ?>
<?php
/**
 * Why Us page template. Ported from Why_Us.html. Every section renders the
 * design copy by default and is overridable via ACF (group_pcp_why_us).
 */
$phone_d   = pcp_phone_display();
$phone_t   = pcp_phone_tel();
$quote_url = pcp_field('nav_quote_url', home_url('/contact/'), 'option');

$rows = function ($name) { $r = get_field($name); return (is_array($r) && $r) ? $r : null; };

// ---- defaults that mirror the design ----
$d_about_creds = [
    'Family-owned and operated since day one',
    'Fully insured to $20M public liability',
    "Iron Clad Guarantee - you pay only when you're happy",
];
$d_reasons = [
    ['The strongest guarantee in Australia', "We have such confidence in our equipment and our results that we back every single job with the strongest guarantee in the country. If it's not right, we make it right, before you pay."],
    ['Industry-leading technology', 'Extreme high pressure combined with intense heat tackles jobs other operators turn down, and produces the highest standard of cleaning available.'],
    ['Up to 80% less water', 'Our process is fast, efficient and uses up to 80% less water, and a fraction of the harsh chemicals, compared to standard pressure cleaning gear.'],
    ['Water-recovery system', "We don't just rearrange dirt and create a wastewater headache. Our system extracts the dirt and recycles the water, so we can use it again. Win-win for your site and the environment."],
    ['We work around you', "We understand the pressure of running a business. We schedule around your operation, after hours if that's what suits, so we're never in your way."],
];
$d_steps = [
    ['Site visit & quote', 'An on-site evaluation, a clear scope, and a written, itemised quote up front.'],
    ['Access & safety brief', 'We confirm access, brief your staff and the public on safety, and lock in the schedule.'],
    ['The work, done properly', 'The right pressure, the right method, with the people and gear to finish on time.'],
    ['Walk-through & report', 'We inspect the finished site with you, send before/after photos, and document everything, including a future maintenance schedule if you want one.'],
];
$d_g_points = [
    'Fully insured & fully accountable',
    'Fixed pricing, no day-of surprises',
    'The right method for every surface',
    'Before-and-after photos on every job',
    'We respect your site and your time',
    'We answer the phone, every time',
    "If it's not right, we make it right",
    "You don't pay until you're happy",
];
$d_tests = [
    ['I would like to say a big thank you for a job well done in a timely and professional manner. My pool was in desperate need of cleaning before it could be repaired, also the surrounding concrete areas from many years of use. The end result is amazing and I am one very happy customer.', 'Francis', 'Applecross'],
    ['Jamie came when he said he would, fully explained what he was going to be doing, and then proceeded to transform my driveway and garage floor. Everyone has commented on how good it looks. Thanks Jamie.', 'Jan', 'Ardross'],
    ['Jamie showed a can-do approach and was able to solve problems I thought were unsolvable, like getting concrete splatter off unsealed pavers, and oil stains off pavers with an innovative solution. I have no hesitation in recommending Pressure Cleaning Perth.', 'Craig Bailey', 'Builder, Ecovision Homes'],
];

$mobile_hero = pcp_field('mobile_hero_image', '', false);
$hero_img    = pcp_field('hero_image', '', false);
$hero_bg     = (is_array($hero_img) && !empty($hero_img['url']))
    ? $hero_img['url']
    : PCP_THEME_URI . '/assets/images/pool-pavers-vacuum.jpg';
$portrait    = pcp_field('about_image', '', false);
$portrait_alt = pcp_field('about_image_alt', 'Jamie - Director, Pressure Cleaning Perth');
?>

<section class="hero hero-svc hero-why" style="background-image:url('<?php echo esc_url($hero_bg); ?>')">
  <div class="wrap">
    <div class="reveal in">
      <?php pcp_mobile_hero(is_array($mobile_hero) ? $mobile_hero : null, 'Pressure Cleaning Perth team'); ?>
      <nav class="crumbs" aria-label="Breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
        <?php echo pcp_icon('arrow'); ?>
        <span><?php echo esc_html(pcp_field('hero_crumb', 'Why Us')); ?></span>
      </nav>
      <span class="eyebrow"><span class="dot"></span> <?php echo esc_html(pcp_field('hero_eyebrow', 'About Us · Our Promise to You')); ?></span>
      <h1><?php echo wp_kses_post(pcp_field('hero_heading', 'Get to know the people <span class="hl">behind the pressure cleans.</span>')); ?></h1>
      <p class="sub"><?php echo esc_html(pcp_field('hero_sub', 'A family-owned Perth business with the strongest guarantee in the country, the cleanest tech in the trade, and a real human on the other end of the phone.')); ?></p>
      <div class="hero-cta">
        <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green btn-lg">Get a Free Quote</a>
        <a href="<?php echo esc_url($phone_t); ?>" class="btn btn-ghost btn-lg"><?php echo pcp_icon('phone'); ?> <?php echo esc_html($phone_d); ?></a>
      </div>
    </div>
  </div>
</section>

<section class="about pad-sm">
  <div class="wrap">
    <div class="about-grid">
      <div class="about-media reveal r-left">
        <div class="about-portrait"><?php echo pcp_image(is_array($portrait) ? $portrait : null, 'pcp-portrait', '', [], 'assets/brand/mascot-sitting.png', $portrait_alt); ?></div>
        <div class="exp-card">
          <b><?php echo esc_html(pcp_field('about_exp_number', '25')); ?></b>
          <span><?php echo wp_kses_post(pcp_field('about_exp_label', 'Years in the<br>building industry')); ?></span>
        </div>
        <div class="sig-card">
          <span class="sig-line"><?php echo esc_html(pcp_field('about_sig_line', "G'day from")); ?></span>
          <span class="sig-name"><?php echo esc_html(pcp_field('about_sig_name', 'Jamie')); ?></span>
          <span class="sig-role"><?php echo esc_html(pcp_field('about_sig_role', 'Director · Pressure Cleaning Perth')); ?></span>
        </div>
      </div>
      <div class="about-copy reveal r-right">
        <span class="kicker g"><?php echo esc_html(pcp_field('about_kicker', 'Meet the Owner')); ?></span>
        <h2><?php echo esc_html(pcp_field('about_heading', "Hi, I'm Jamie. I run Pressure Cleaning Perth.")); ?></h2>
        <?php echo wp_kses_post(pcp_field('about_body', '<p>Having worked in the building industry for 25 years, I truly understand the importance of keeping a workplace clean, and how frustrating finding a professional pressure cleaning company can be.</p><p>That is why I built this business the way I did: with as much information up front as I can give you, an 8-Point Code of Conduct that holds us accountable, and an Iron Clad Guarantee that makes it impossible for you to be unhappy with the result.</p><p>I am fastidious. I over-deliver on value and service. And I want every client to view Pressure Cleaning Perth as the commercial pressure cleaning company of choice in WA.</p>')); ?>
        <ul class="about-creds">
          <?php foreach (($rows('about_creds') ?: array_map(fn($t) => ['text' => $t], $d_about_creds)) as $c) : ?>
            <li><?php echo pcp_icon('check'); ?> <?php echo esc_html(is_array($c) ? ($c['text'] ?? '') : $c); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="reasons pad-sm">
  <div class="wrap">
    <div class="sec-head reveal" style="text-align:center;max-width:760px">
      <span class="kicker g"><?php echo esc_html(pcp_field('reasons_kicker', 'Why Choose Us')); ?></span>
      <h2><?php echo esc_html(pcp_field('reasons_heading', 'Five reasons Perth keeps calling us back')); ?></h2>
      <p><?php echo esc_html(pcp_field('reasons_intro', 'These are the differences our customers notice on day one, and the ones they tell their neighbours about.')); ?></p>
    </div>
    <ol class="reason-grid">
      <?php foreach (($rows('reasons') ?: array_map(fn($r) => ['title' => $r[0], 'body' => $r[1]], $d_reasons)) as $i => $r) : ?>
        <li class="reason reveal">
          <span class="r-num"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
          <h3><?php echo esc_html($r['title'] ?? ''); ?></h3>
          <p><?php echo esc_html($r['body'] ?? ''); ?></p>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="howwework pad-sm">
  <div class="wrap">
    <div class="sec-head reveal" style="text-align:left;max-width:760px">
      <span class="kicker" style="color:var(--cyan);background:rgba(127,202,238,.14)"><?php echo esc_html(pcp_field('howwework_kicker', 'How We Work')); ?></span>
      <h2><?php echo esc_html(pcp_field('howwework_heading', 'A repeatable system. Built for safety. Tuned for results.')); ?></h2>
      <p><?php echo esc_html(pcp_field('howwework_intro', 'Every project starts with a comprehensive site assessment to determine service, safety and environmental requirements, then we design a cleaning plan that suits the job.')); ?></p>
    </div>
    <ol class="ww-steps">
      <?php foreach (($rows('howwework_steps') ?: array_map(fn($s) => ['title' => $s[0], 'body' => $s[1]], $d_steps)) as $i => $s) : ?>
        <li class="ww-step reveal"><span class="ww-num"><?php echo esc_html((string) ($i + 1)); ?></span><div><h3><?php echo esc_html($s['title'] ?? ''); ?></h3><p><?php echo esc_html($s['body'] ?? ''); ?></p></div></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="guarantee" id="guarantee">
  <div class="guarantee-content reveal r-left">
    <span class="kicker" style="color:#cbf2a3;background:rgba(109,211,60,.18);border:1px solid rgba(109,211,60,.35)"><?php echo esc_html(pcp_field('guarantee_kicker', 'Our Promise')); ?></span>
    <h2><?php echo wp_kses_post(pcp_field('guarantee_heading', 'The <span class="hl">Iron Clad</span> Guarantee.')); ?></h2>
    <p><?php echo esc_html(pcp_field('guarantee_body', "Our 8-Point Code of Conduct means you don't have to take our word for it, you have it in writing. If anything about the job isn't right, we come back and fix it. You pay when you're happy. Not before.")); ?></p>
    <ul class="g-points">
      <?php foreach (($rows('guarantee_points') ?: array_map(fn($t) => ['text' => $t], $d_g_points)) as $i => $p) : ?>
        <li><b><?php echo esc_html(($i + 1) . '.'); ?></b> <?php echo esc_html(is_array($p) ? ($p['text'] ?? '') : $p); ?></li>
      <?php endforeach; ?>
    </ul>
    <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green btn-lg" style="margin-top:8px;align-self:flex-start">Get a Free Quote</a>
  </div>
  <div class="guarantee-img reveal r-right">
    <?php echo pcp_image(pcp_field('guarantee_image', '', false) ?: null, 'pcp-portrait', '', [], 'assets/brand/mascot-sitting.png', ''); ?>
  </div>
</section>

<section class="testimonials pad-sm">
  <div class="wrap">
    <div class="sec-head reveal" style="text-align:center;max-width:760px">
      <span class="kicker g"><?php echo esc_html(pcp_field('testimonials_kicker', 'In Their Words')); ?></span>
      <h2><?php echo esc_html(pcp_field('testimonials_heading', 'What our clients say.')); ?></h2>
    </div>
    <div class="t-grid">
      <?php foreach (($rows('testimonials') ?: array_map(fn($t) => ['quote' => $t[0], 'name' => $t[1], 'location' => $t[2]], $d_tests)) as $t) : ?>
        <figure class="t-card reveal">
          <span class="stars" aria-hidden="true"><?php echo pcp_stars(5); ?></span>
          <blockquote><?php echo esc_html($t['quote'] ?? ''); ?></blockquote>
          <figcaption><b><?php echo esc_html($t['name'] ?? ''); ?></b><span><?php echo esc_html($t['location'] ?? ''); ?></span></figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
get_template_part('template-parts/cta-band', null, ['extra_class' => 'cta-mid']);
get_template_part('template-parts/contact');
get_footer();
