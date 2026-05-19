<?php
/**
 * Contact section
 * ACF group: group_contact_landing
 */
if (!defined('ABSPATH')) { exit; }

$eyebrow       = get_field('contact_eyebrow');
$headline      = get_field('contact_headline');
$form_title    = get_field('contact_form_title');
$form_subtitle = get_field('contact_form_subtitle');
$form_id       = get_field('contact_form_id');
$hours         = get_field('contact_hours_text');
$hours_extra   = get_field('contact_hours_extra');
$service_area  = get_field('contact_service_area') ?: 'Perth metro, WA';

$phone_display = ocd_site_phone_display();
$phone_tel     = ocd_site_phone_tel();
$email         = get_field('business_email', 'option');

if (!$headline) { return; }
?>

<section class="section contact" id="contact">
    <div class="container">
        <div class="contact__grid">
            <div class="contact__info reveal">
                <?php if ($eyebrow) : ?>
                    <span class="eyebrow eyebrow--yellow"><?php echo esc_html($eyebrow); ?></span>
                <?php endif; ?>
                <h2 class="contact__headline"><?php echo wp_kses_post($headline); ?></h2>

                <div class="contact__details">
                    <?php if ($phone_display) : ?>
                        <a href="<?php echo esc_url($phone_tel); ?>" class="contact__detail">
                            <div class="contact__detail-icon"><?php echo ocd_icon('phone'); ?></div>
                            <div>
                                <div class="contact__detail-label"><?php esc_html_e('Call us', 'dentelectrical'); ?></div>
                                <div class="contact__detail-value"><?php echo esc_html($phone_display); ?></div>
                            </div>
                        </a>
                    <?php endif; ?>

                    <?php if ($email) : ?>
                        <a href="mailto:<?php echo esc_attr($email); ?>" class="contact__detail">
                            <div class="contact__detail-icon"><?php echo ocd_icon('mail'); ?></div>
                            <div>
                                <div class="contact__detail-label"><?php esc_html_e('Email', 'dentelectrical'); ?></div>
                                <div class="contact__detail-value"><?php echo esc_html($email); ?></div>
                            </div>
                        </a>
                    <?php endif; ?>

                    <div class="contact__detail">
                        <div class="contact__detail-icon"><?php echo ocd_icon('pin'); ?></div>
                        <div>
                            <div class="contact__detail-label"><?php esc_html_e('Service area', 'dentelectrical'); ?></div>
                            <div class="contact__detail-value"><?php echo esc_html($service_area); ?></div>
                        </div>
                    </div>

                    <?php if ($hours) : ?>
                        <div class="contact__detail">
                            <div class="contact__detail-icon"><?php echo ocd_icon('clock'); ?></div>
                            <div>
                                <div class="contact__detail-label"><?php esc_html_e('Hours', 'dentelectrical'); ?></div>
                                <div class="contact__detail-value">
                                    <?php echo esc_html($hours); ?>
                                    <?php if ($hours_extra) : ?>
                                        <span class="contact__detail-extra"><?php echo esc_html($hours_extra); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="contact__form-wrap reveal reveal--delay-1">
                <div class="ocd-form contact__form">
                    <div class="contact__form-header">
                        <?php if ($form_title) : ?>
                            <h3 class="contact__form-title"><?php echo esc_html($form_title); ?></h3>
                        <?php endif; ?>
                        <?php if ($form_subtitle) : ?>
                            <p class="contact__form-sub"><?php echo esc_html($form_subtitle); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php ocd_render_gravity_form($form_id); ?>
                </div>
            </div>
        </div>
    </div>
</section>
