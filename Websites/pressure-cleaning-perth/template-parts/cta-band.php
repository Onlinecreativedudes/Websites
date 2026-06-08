<?php
/**
 * Shared big CTA band. Pulls copy from Site Options with design defaults.
 * Args: extra_class (eg 'cta-mid').
 */
if (!defined('ABSPATH')) { exit; }

$extra   = $args['extra_class'] ?? '';
$heading = pcp_field('cta_band_heading', 'Ready to', 'option');
$script  = pcp_field('cta_band_script', 'see the difference?', 'option');
$body    = pcp_field('cta_band_body', 'From driveways to rooftops, Pressure Cleaning Perth brings the results. Quick response, honest pricing, and work backed by our Iron Clad Guarantee.', 'option');
$quote_url = pcp_field('nav_quote_url', home_url('/contact/'), 'option');
$phone_d = pcp_phone_display();
$phone_t = pcp_phone_tel();
?>
<section class="cta-band pad <?php echo esc_attr($extra); ?>">
  <div class="wrap">
    <h2><?php echo esc_html($heading); ?> <span class="script"><?php echo esc_html($script); ?></span></h2>
    <?php if ($body) : ?><p><?php echo esc_html($body); ?></p><?php endif; ?>
    <div class="hero-cta">
      <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green btn-lg">Get a Free Quote Today</a>
      <a href="<?php echo esc_url($phone_t); ?>" class="btn btn-ghost btn-lg"><?php echo pcp_icon('phone'); ?> Call <?php echo esc_html($phone_d); ?></a>
    </div>
  </div>
</section>
