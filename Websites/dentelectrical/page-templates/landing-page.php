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
    get_template_part('template-parts/trust-bar');
    get_template_part('template-parts/services-grid');
    get_template_part('template-parts/cta-band-blue');
    get_template_part('template-parts/about');
    get_template_part('template-parts/why-us');
    get_template_part('template-parts/feature-compliance');
    get_template_part('template-parts/feature-inspections');
    get_template_part('template-parts/feature-process');
    get_template_part('template-parts/cta-band-yellow');
    get_template_part('template-parts/reviews');
    get_template_part('template-parts/contact');
    get_template_part('template-parts/final-cta');
    ?>
</main>

<?php
get_footer();
