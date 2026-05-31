<?php
/**
 * Fallback index. Specific templates (front-page, single-*, archive-*, page-*) handle
 * the real views; this catches anything else (eg search, date archives).
 */
if (!defined('ABSPATH')) { exit; }
get_header();
?>
<section class="hero hero-svc hero-page">
  <div class="wrap">
    <h1>
      <?php
      if (is_search()) {
          printf('Search results for &ldquo;%s&rdquo;', esc_html(get_search_query()));
      } elseif (is_archive()) {
          the_archive_title();
      } else {
          single_post_title();
      }
      ?>
    </h1>
  </div>
</section>

<section class="posts pad-sm">
  <div class="wrap">
    <?php if (have_posts()) : ?>
      <div class="post-grid">
        <?php while (have_posts()) : the_post(); ?>
          <article <?php post_class('post-card reveal'); ?>>
            <a href="<?php the_permalink(); ?>" class="post-card-link">
              <?php if (has_post_thumbnail()) : ?>
                <div class="post-card-img"><?php the_post_thumbnail('pcp-card', ['alt' => esc_attr(get_the_title())]); ?></div>
              <?php endif; ?>
              <div class="post-card-body">
                <h2><?php the_title(); ?></h2>
                <p><?php echo esc_html(get_the_excerpt()); ?></p>
                <span class="post-card-meta"><?php echo esc_html(get_the_date()); ?></span>
              </div>
            </a>
          </article>
        <?php endwhile; ?>
      </div>
      <nav class="pagination reveal"><?php echo paginate_links(); ?></nav>
    <?php else : ?>
      <p>Nothing found. Try a different search.</p>
    <?php endif; ?>
  </div>
</section>

<?php
get_template_part('template-parts/cta-band');
get_template_part('template-parts/contact');
get_footer();
