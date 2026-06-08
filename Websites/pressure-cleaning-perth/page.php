<?php
/**
 * Generic page template (privacy, terms, and any page without a dedicated template).
 */
if (!defined('ABSPATH')) { exit; }
get_header();
?>
<section class="hero hero-svc hero-page">
  <div class="wrap">
    <h1><?php the_title(); ?></h1>
  </div>
</section>

<nav class="crumbs" aria-label="Breadcrumb">
  <div class="wrap">
    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a> <span>/</span> <?php the_title(); ?>
  </div>
</nav>

<section class="pad-sm">
  <div class="wrap narrow article-body">
    <?php
    while (have_posts()) : the_post();
        the_content();
    endwhile;
    ?>
  </div>
</section>

<?php
get_template_part('template-parts/cta-band');
get_template_part('template-parts/contact');
get_footer();
