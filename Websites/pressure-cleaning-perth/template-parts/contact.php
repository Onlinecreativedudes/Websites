<?php
/**
 * Shared contact section (contact info column + quote form card).
 * Pulls from Site Options with design defaults.
 */
if (!defined('ABSPATH')) { exit; }

$phone_d = pcp_phone_display();
$phone_t = pcp_phone_tel();
$email   = pcp_field('business_email', 'Sales@PressureCleaningPerth.com.au', 'option');
$area    = pcp_field('address_short', 'All Perth metro suburbs', 'option');
$lead    = pcp_field('contact_lead', 'Not sure what your property needs? Get in touch and we will tell you honestly. Whether it is a quick driveway clean or a full commercial wash-down, we will scope the job properly and give you a clear price before we start.', 'option');
?>
<section class="contact pad" id="contact">
  <div class="wrap">
    <div class="reveal">
      <span class="kicker" style="color:var(--cyan);background:rgba(127,202,238,.14)">Contact Us</span>
      <h2>Request a free quote</h2>
      <?php if ($lead) : ?><p class="lead"><?php echo esc_html($lead); ?></p><?php endif; ?>
      <div class="contact-rows">
        <div class="crow"><span class="ico"><?php echo pcp_icon('phone'); ?></span><div><a href="<?php echo esc_url($phone_t); ?>"><b><?php echo esc_html($phone_d); ?></b></a><span>Speak to the person doing the job</span></div></div>
        <?php if ($email) : ?><div class="crow"><span class="ico"><?php echo pcp_icon('mail'); ?></span><div><a href="mailto:<?php echo esc_attr($email); ?>"><b><?php echo esc_html($email); ?></b></a><span>Email us anytime</span></div></div><?php endif; ?>
        <div class="crow"><span class="ico"><?php echo pcp_icon('pin'); ?></span><div><b><?php echo esc_html($area); ?></b><span>And surrounding areas</span></div></div>
      </div>
      <div class="guar-badge">
        <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 5v6c0 5.5 3.8 9.7 8 11 4.2-1.3 8-5.5 8-11V5z"></path><path d="m9 12 2 2 4-4"></path></svg></span>
        <div><b>Backed by our Iron Clad Guarantee</b><span>One of the strongest service guarantees in Australia. Fully insured to $20M.</span></div>
      </div>
    </div>

    <?php get_template_part('template-parts/lead-form', null, [
        'title'    => 'Get Your Free Quote',
        'subtitle' => 'Clear pricing and a fast response. We will get back to you shortly.',
        'footnote' => 'No obligation. We respond fast.',
        'form_id'  => (int) pcp_field('contact_form_id', 0),
    ]); ?>
  </div>
</section>
