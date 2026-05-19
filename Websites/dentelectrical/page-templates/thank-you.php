<?php
/**
 * Template Name: Thank You
 */
if (!defined('ABSPATH')) { exit; }

get_header();

$headline = get_field('ty_headline') ?: 'Thanks, we got your message.';
$body     = get_field('ty_body');
$cta      = get_field('ty_cta');
?>

<main id="main" class="page page--thank-you">
    <section class="thank-you">
        <div class="container container--narrow">
            <span class="eyebrow"><?php esc_html_e('Message received', 'dentelectrical'); ?></span>
            <h1 class="thank-you__headline"><?php echo esc_html($headline); ?></h1>

            <?php if ($body) : ?>
                <div class="thank-you__body">
                    <?php echo wp_kses_post($body); ?>
                </div>
            <?php endif; ?>

            <div class="thank-you__actions">
                <?php if ($cta && !empty($cta['url'])) : ?>
                    <?php ocd_render_link($cta, 'btn btn--primary thank-you__cta'); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary thank-you__cta">
                        <?php esc_html_e('Back to home', 'dentelectrical'); ?>
                        <?php echo ocd_icon('arrow', ['class' => 'btn__arrow']); ?>
                    </a>
                <?php endif; ?>

                <?php $phone = ocd_site_phone_display(); if ($phone) : ?>
                    <a href="<?php echo esc_url(ocd_site_phone_tel()); ?>" class="btn btn--outline">
                        <?php echo ocd_icon('phone'); ?>
                        <?php echo esc_html($phone); ?>
                    </a>
                <?php endif; ?>
            </div>

            <div id="conversion-event" data-event="form_submission" aria-hidden="true"></div>
        </div>
    </section>
</main>

<?php
get_footer();
