<?php
/**
 * Template Name: Thank You
 */
if (!defined('ABSPATH')) { exit; }

get_header();

$headline = get_field('ty_headline') ?: __('Thanks, we have your details.', 'woodycraftwork');
$body     = get_field('ty_body');
$cta      = get_field('ty_cta');
$phone    = ocd_site_phone_display();
?>

<main id="main" class="page page--thank-you">
    <section class="section" style="padding-top:200px;">
        <div class="container" style="max-width:800px;text-align:center;">
            <span class="eyebrow center"><?php esc_html_e('Message received', 'woodycraftwork'); ?></span>
            <h1 style="margin-top:20px;"><?php echo esc_html($headline); ?></h1>

            <?php if ($body) : ?>
                <div class="lead" style="margin-top:22px;color:var(--text-soft);">
                    <?php echo wp_kses_post($body); ?>
                </div>
            <?php endif; ?>

            <div class="hero-actions" style="justify-content:center;margin-top:34px;">
                <?php if ($cta && !empty($cta['url'])) : ?>
                    <?php ocd_render_link($cta, 'btn'); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn"><?php esc_html_e('Back to home', 'woodycraftwork'); ?> <?php echo ocd_icon('arrow'); ?></a>
                <?php endif; ?>
                <?php if ($phone) : ?>
                    <a href="<?php echo esc_url(ocd_site_phone_tel()); ?>" class="btn outline"><?php echo esc_html($phone); ?></a>
                <?php endif; ?>
            </div>

            <div id="conversion-event" data-event="form_submission" aria-hidden="true"></div>
        </div>
    </section>
</main>

<?php
get_footer();
