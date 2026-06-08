<?php
if (!defined('ABSPATH')) { exit; }
get_header();
?>
<section class="hero hero-svc hero-page">
  <div class="wrap">
    <h1>Page not found</h1>
    <p>Sorry, the page you were after does not exist or has moved.</p>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-green">Back to home</a>
  </div>
</section>
<?php
get_template_part('template-parts/cta-band');
get_template_part('template-parts/contact');
get_footer();
