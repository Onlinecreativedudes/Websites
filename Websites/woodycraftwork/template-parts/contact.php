<?php
/**
 * Contact section (details + Gravity Form card).
 * ACF tab: Contact (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow   = get_field('contact_eyebrow');
$headline  = get_field('contact_headline');
$lead      = get_field('contact_lead');
$lines     = get_field('contact_lines');
$form_title = get_field('contact_form_title');
$form_sub   = get_field('contact_form_subtitle');
$form_id    = get_field('contact_form_id');

if (!$headline) { return; }
?>

<section class="section tint" id="contact">
    <div class="container">
        <div class="contact-grid">
            <div class="reveal">
                <?php if ($eyebrow) : ?><span class="eyebrow"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
                <h2 style="margin-top:20px;"><?php echo wp_kses_post($headline); ?></h2>
                <?php if ($lead) : ?><p class="lead" style="margin-top:18px;max-width:420px;"><?php echo esc_html($lead); ?></p><?php endif; ?>

                <?php if ($lines && is_array($lines)) : ?>
                    <div class="contact-lines">
                        <?php foreach ($lines as $line) :
                            $icon  = $line['icon'] ?? 'mail';
                            $label = $line['label'] ?? '';
                            $value = $line['value'] ?? '';
                            $url   = $line['link'] ?? '';
                            if (!$value) { continue; }
                        ?>
                            <div class="cline">
                                <span class="ic"><?php echo ocd_icon($icon, ['stroke-width' => '1.7']); ?></span>
                                <div>
                                    <?php if ($label) : ?><div class="lbl"><?php echo esc_html($label); ?></div><?php endif; ?>
                                    <div class="val">
                                        <?php
                                        if ($url) {
                                            printf('<a href="%s">%s</a>', esc_url($url), esc_html($value));
                                        } else {
                                            echo esc_html($value);
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-card reveal">
                <?php if ($form_title) : ?><h3><?php echo esc_html($form_title); ?></h3><?php endif; ?>
                <?php if ($form_sub) : ?><p class="sub"><?php echo esc_html($form_sub); ?></p><?php endif; ?>
                <div class="ocd-form ocd-form--contact">
                    <?php ocd_render_gravity_form($form_id); ?>
                </div>
            </div>
        </div>
    </div>
</section>
