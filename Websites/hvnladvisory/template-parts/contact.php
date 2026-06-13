<?php
/**
 * Contact — dark split: direct contact rows beside the message form card.
 * ACF tab: Contact (group_landing_page)
 */
if (!defined('ABSPATH')) { exit; }

$kicker   = get_field('contact_kicker');
$headline = get_field('contact_headline');
$intro    = get_field('contact_intro');

$form_eyebrow = get_field('contact_form_eyebrow');
$form_sub     = get_field('contact_form_subheading');
$form_id      = get_field('contact_form_id');

$email    = get_field('business_email', 'option');
$coverage = get_field('coverage_text', 'option');

if (!$headline) { return; }
?>

<section class="contact section section--dark" id="contact">
    <div class="contact__split container container--wide">
        <div class="contact__details">
            <?php ocd_kicker($kicker, 'gold'); ?>
            <h2 class="section__headline"><?php echo ocd_kses_headline($headline); ?></h2>
            <?php if ($intro) : ?>
                <p class="contact__intro"><?php echo esc_html($intro); ?></p>
            <?php endif; ?>

            <div class="contact__rows">
                <?php if ($email) : ?>
                    <a class="contact__row" href="mailto:<?php echo esc_attr($email); ?>">
                        <span class="contact__row-label"><?php esc_html_e('Email', 'hvnladvisory'); ?></span>
                        <span class="contact__row-value"><?php echo esc_html($email); ?></span>
                    </a>
                <?php endif; ?>
                <?php if (ocd_site_phone_display()) : ?>
                    <a class="contact__row" href="<?php echo esc_url(ocd_site_phone_tel()); ?>">
                        <span class="contact__row-label"><?php esc_html_e('Phone', 'hvnladvisory'); ?></span>
                        <span class="contact__row-value"><?php echo esc_html(ocd_site_phone_display()); ?></span>
                    </a>
                <?php endif; ?>
                <?php if ($coverage) : ?>
                    <div class="contact__row contact__row--static">
                        <span class="contact__row-label"><?php esc_html_e('Coverage', 'hvnladvisory'); ?></span>
                        <span class="contact__row-value"><?php echo esc_html($coverage); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="contact__form form-card-wrap">
            <div class="form-card form-card--bordered">
                <?php if ($form_eyebrow) : ?>
                    <div class="form-card__eyebrow form-card__eyebrow--lg"><?php echo esc_html($form_eyebrow); ?></div>
                <?php endif; ?>
                <?php if ($form_sub) : ?>
                    <p class="form-card__sub form-card__sub--tight"><?php echo esc_html($form_sub); ?></p>
                <?php endif; ?>

                <?php ocd_render_form($form_id, 'contact'); ?>
            </div>
        </div>
    </div>
</section>
