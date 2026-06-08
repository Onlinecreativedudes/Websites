<?php
/**
 * Lead / quote form card. Renders the Gravity Form set on the page (or the
 * site-wide default). Reused in the hero and contact sections.
 * Args: title, subtitle, footnote, form_id.
 */
if (!defined('ABSPATH')) { exit; }

$title    = $args['title']    ?? 'Get Your Free Quote';
$subtitle = $args['subtitle'] ?? 'Clear pricing, fast response, no obligation. We will scope the job and give you a price before we start.';
$footnote = $args['footnote'] ?? '';
$form_id  = $args['form_id']  ?? 0;
$phone_d  = pcp_phone_display();
$phone_t  = pcp_phone_tel();
?>
<div class="lead-card reveal" id="quote">
  <?php if ($title) : ?><h3><?php echo esc_html($title); ?></h3><?php endif; ?>
  <?php if ($subtitle) : ?><p class="lc-sub"><?php echo esc_html($subtitle); ?></p><?php endif; ?>

  <?php if (function_exists('gravity_form')) : ?>
    <?php pcp_render_gravity_form($form_id ?: null); ?>
  <?php else : ?>
    <p class="form-note">Quote form loads here once Gravity Forms is active. Call us on
      <a href="<?php echo esc_url($phone_t); ?>" style="color:var(--blue);font-weight:700"><?php echo esc_html($phone_d); ?></a>.</p>
  <?php endif; ?>

  <?php if ($footnote) : ?>
    <p class="form-note"><?php echo wp_kses_post($footnote); ?></p>
  <?php else : ?>
    <p class="form-note">Or call us now on <a href="<?php echo esc_url($phone_t); ?>" style="color:var(--blue);font-weight:700"><?php echo esc_html($phone_d); ?></a></p>
  <?php endif; ?>
</div>
