<?php
/**
 * Template Name: Landing Page
 */
if (!defined('ABSPATH')) { exit; }

get_header();
?>

<main id="main" class="page page--landing">
    <?php
    get_template_part('template-parts/hero');
    get_template_part('template-parts/services');
    get_template_part('template-parts/cta-band-one');
    get_template_part('template-parts/about');
    get_template_part('template-parts/why-us');
    get_template_part('template-parts/value-built');
    get_template_part('template-parts/statement');
    get_template_part('template-parts/value-design');
    get_template_part('template-parts/value-scope');
    get_template_part('template-parts/cta-band-two');
    get_template_part('template-parts/gallery');
    get_template_part('template-parts/reviews');
    get_template_part('template-parts/contact');
    get_template_part('template-parts/final-cta');
    ?>
</main>

<?php
get_footer();
