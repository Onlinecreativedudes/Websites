<?php
/*
 * Template Name: Contact
 *
 * Contact page. Ported from Contact.html: hero + crumbs, the contact-form section
 * with the cp-info aside (phone/email/area from Site Options via pcp helpers + ACF)
 * and the contact form (Gravity Forms), then the cp-map-section (ACF map_embed).
 */
if (!defined('ABSPATH')) { exit; }
get_header();

$home_url = home_url('/');
$phone_d  = pcp_phone_display();
$phone_t  = pcp_phone_tel();
$email    = pcp_field('business_email', 'Sales@PressureCleaningPerth.com.au', 'option');
$area     = pcp_field('address_short', 'The Perth Metro', 'option');
$form_id  = (int) pcp_field('contact_form_id', 0);
$map      = pcp_field('map_embed', '', false);

$mobile_hero = pcp_field('mobile_hero_image', '', false);

while (have_posts()) : the_post();
?>

<section class="hero hero-svc hero-contact">
  <div class="wrap">
    <div class="reveal in">
      <?php pcp_mobile_hero(is_array($mobile_hero) ? $mobile_hero : null, 'Contact Pressure Cleaning Perth'); ?>
      <nav class="crumbs" aria-label="Breadcrumb">
        <a href="<?php echo esc_url($home_url); ?>">Home</a>
        <?php echo pcp_icon('arrow'); ?>
        <span>Contact</span>
      </nav>
      <span class="eyebrow"><span class="dot"></span> <?php echo esc_html(pcp_field('hero_eyebrow', 'We Reply Fast · Free Quotes')); ?></span>
      <h1><?php echo wp_kses_post(pcp_field('hero_heading', 'Tell us about your <span class="hl">pressure cleaning requirements.</span>')); ?></h1>
      <p class="sub"><?php echo esc_html(pcp_field('hero_sub', 'Phone, email or the quick form below. We respond fast, quote clearly, and back every job with our Iron Clad Guarantee.')); ?></p>
      <div class="hero-cta">
        <a href="<?php echo esc_url($phone_t); ?>" class="btn btn-green btn-lg"><?php echo pcp_icon('phone'); ?> <?php echo esc_html($phone_d); ?></a>
        <a href="#contact-form" class="btn btn-ghost btn-lg">Send a Message</a>
      </div>
    </div>
  </div>
</section>

<section class="contact-page pad-sm" id="contact-form">
  <div class="wrap">
    <div class="cp-grid">

      <aside class="cp-info reveal r-left">
        <span class="kicker g"><?php echo esc_html(pcp_field('info_kicker', 'Reach Out')); ?></span>
        <h2><?php echo wp_kses_post(pcp_field('info_heading', 'Three ways to <span class="hl-green">get in touch.</span>')); ?></h2>
        <p><?php echo esc_html(pcp_field('info_intro', 'Whichever you prefer, you will always get a real human, a fast reply, and an honest answer.')); ?></p>

        <div class="cp-channels">
          <a href="<?php echo esc_url($phone_t); ?>" class="cp-channel">
            <span class="cp-ico"><?php echo pcp_icon('phone'); ?></span>
            <div><span class="cp-label">Call us</span><b><?php echo esc_html($phone_d); ?></b><span class="cp-meta">Fastest path to a quote &middot; 7 days</span></div>
          </a>
          <?php if ($email) : ?>
            <a href="mailto:<?php echo esc_attr($email); ?>" class="cp-channel">
              <span class="cp-ico"><?php echo pcp_icon('mail'); ?></span>
              <div><span class="cp-label">Email</span><b><?php echo esc_html($email); ?></b><span class="cp-meta">Photos &amp; job details welcome</span></div>
            </a>
          <?php endif; ?>
          <div class="cp-channel">
            <span class="cp-ico"><?php echo pcp_icon('pin'); ?></span>
            <div><span class="cp-label">Servicing</span><b><?php echo esc_html($area); ?></b><span class="cp-meta">Yanchep to Mandurah &middot; CBD to the Hills</span></div>
          </div>
        </div>

        <div class="cp-hours">
          <h3><?php echo pcp_icon('clock'); ?> Office Hours</h3>
          <ul>
            <li><span>Mon &ndash; Fri</span><span><?php echo esc_html(pcp_field('hours_weekday', '7:00 am - 6:00 pm')); ?></span></li>
            <li><span>Saturday</span><span><?php echo esc_html(pcp_field('hours_saturday', '8:00 am - 4:00 pm')); ?></span></li>
            <li><span>Sunday</span><span><?php echo esc_html(pcp_field('hours_sunday', 'By appointment')); ?></span></li>
          </ul>
          <p class="cp-after"><?php echo esc_html(pcp_field('hours_note', 'Site work also available after-hours by arrangement for commercial & strata clients.')); ?></p>
        </div>

        <div class="cp-trust">
          <div class="cp-tr">
            <span class="cp-tr-ico"><?php echo pcp_icon('check'); ?></span>
            <div><b>Fully Insured</b><span>$20M Public Liability</span></div>
          </div>
          <div class="cp-tr">
            <span class="cp-tr-ico"><?php echo pcp_icon('star'); ?></span>
            <div><b>5.0 Google Rating</b><span>60+ verified reviews</span></div>
          </div>
        </div>
      </aside>

      <div class="cp-form-wrap reveal r-right">
        <div class="cp-form-card">
          <span class="kicker g"><?php echo esc_html(pcp_field('form_kicker', 'Free Quote Form')); ?></span>
          <h2><?php echo esc_html(pcp_field('form_heading', 'Send us your job details.')); ?></h2>
          <p class="cp-form-sub"><?php echo esc_html(pcp_field('form_sub', 'We will come back within one business day, often sooner. Photos help us quote faster: attach what you can.')); ?></p>
          <?php if (function_exists('gravity_form')) : ?>
            <?php pcp_render_gravity_form($form_id ?: null); ?>
          <?php else : ?>
            <p class="form-note">Quote form loads here once Gravity Forms is active. Call us on
              <a href="<?php echo esc_url($phone_t); ?>" style="color:var(--blue);font-weight:700"><?php echo esc_html($phone_d); ?></a>.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if ($map) : ?>
<section class="cp-map-section">
  <div class="cp-map">
    <?php echo $map; // ACF textarea: raw Google Maps embed iframe. ?>
  </div>
</section>
<?php endif; ?>

<?php
endwhile;
get_footer();
