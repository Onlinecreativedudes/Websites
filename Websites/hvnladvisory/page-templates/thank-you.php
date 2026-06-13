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

<main class="page page--thank-you" id="main">
    <section class="thank-you">
        <div class="thank-you__container container container--narrow">
            <h1 class="thank-you__headline"><?php echo esc_html($headline); ?></h1>

            <?php if ($body) : ?>
                <div class="thank-you__body">
                    <?php echo wp_kses_post($body); ?>
                </div>
            <?php endif; ?>

            <?php ocd_render_link($cta, 'btn btn--dark thank-you__cta'); ?>

            <div id="conversion-event" data-event="form_submission" aria-hidden="true"></div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
