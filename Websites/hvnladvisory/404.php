<?php
if (!defined('ABSPATH')) { exit; }

get_header();
?>

<main class="page page--404" id="main">
    <section class="plain">
        <div class="container container--narrow">
            <h1><?php esc_html_e('Page not found', 'hvnladvisory'); ?></h1>
            <p><?php esc_html_e("The page you're after doesn't exist or has moved.", 'hvnladvisory'); ?></p>
            <a class="btn btn--dark" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Back to home', 'hvnladvisory'); ?></a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
