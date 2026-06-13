<?php
/**
 * Template Name: Landing Page
 */
if (!defined('ABSPATH')) { exit; }

get_header();
?>

<main id="main">
    <?php
    get_template_part('template-parts/hero');
    get_template_part('template-parts/why-now');
    get_template_part('template-parts/assess-section');
    get_template_part('template-parts/why-choose');
    get_template_part('template-parts/services');
    get_template_part('template-parts/cta-band');
    get_template_part('template-parts/gallery');
    get_template_part('template-parts/reviews');
    get_template_part('template-parts/health-check');
    get_template_part('template-parts/final-cta');
    ?>
</main>

<?php
get_footer();
