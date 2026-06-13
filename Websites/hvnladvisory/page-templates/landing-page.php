<?php
/**
 * Template Name: Landing Page
 */
if (!defined('ABSPATH')) { exit; }

get_header();
?>

<main class="page page--landing" id="main">
    <?php
    get_template_part('template-parts/hero');
    get_template_part('template-parts/trust-bar');
    get_template_part('template-parts/blind-spots');
    get_template_part('template-parts/services-grid');
    get_template_part('template-parts/cta-offer');
    get_template_part('template-parts/about');
    get_template_part('template-parts/why-us');
    get_template_part('template-parts/industries');
    get_template_part('template-parts/who-we-help');
    get_template_part('template-parts/how-we-work');
    get_template_part('template-parts/cta-evidence');
    get_template_part('template-parts/reviews');
    get_template_part('template-parts/final-cta');
    get_template_part('template-parts/contact');
    ?>
</main>

<?php get_footer(); ?>
