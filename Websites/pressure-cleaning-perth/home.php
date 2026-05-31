<?php
/**
 * Blog posts index (home.php). Ported from Blog.html. Hero + crumbs, then The Loop
 * into the .post-cards grid, paginate_links() in .pagination, then the shared
 * CTA band and contact section.
 */
if (!defined('ABSPATH')) { exit; }
get_header();

$blog_page_id = (int) get_option('page_for_posts');
$home_url = home_url('/');

// Hero copy is editable from the Posts page (group_pcp_blog) where present, with the design copy as default.
$mobile_hero = $blog_page_id ? pcp_field('mobile_hero_image', '', $blog_page_id) : '';
$hero_eyebrow = pcp_field('hero_eyebrow', 'Tips, Guides & Case Studies', $blog_page_id ?: false);
$hero_heading = pcp_field('hero_heading', 'The Pressure Cleaning <span class="hl">Blog</span>', $blog_page_id ?: false);
$hero_sub = pcp_field('hero_sub', 'Real advice from the team that cleans Perth properties every day: what works on what surface, what to ask before you book, and the before-and-afters that prove it.', $blog_page_id ?: false);
?>

<section class="hero hero-svc hero-blog">
  <div class="wrap">
    <div class="reveal in">
      <?php pcp_mobile_hero(is_array($mobile_hero) ? $mobile_hero : null, 'The Pressure Cleaning Perth blog'); ?>
      <nav class="crumbs" aria-label="Breadcrumb">
        <a href="<?php echo esc_url($home_url); ?>">Home</a>
        <?php echo pcp_icon('arrow'); ?>
        <span>The Blog</span>
      </nav>
      <span class="eyebrow"><span class="dot"></span> <?php echo esc_html($hero_eyebrow); ?></span>
      <h1><?php echo wp_kses_post($hero_heading); ?></h1>
      <p class="sub"><?php echo esc_html($hero_sub); ?></p>
    </div>
  </div>
</section>

<section class="posts-grid pad-sm" id="posts">
  <div class="wrap">
    <div class="posts-header reveal">
      <div>
        <span class="kicker g"><?php echo esc_html(pcp_field('posts_kicker', 'Latest Articles', $blog_page_id ?: false)); ?></span>
        <h2><?php echo esc_html(pcp_field('posts_heading', 'Fresh from the gurney', $blog_page_id ?: false)); ?></h2>
      </div>
    </div>

    <?php if (have_posts()) : ?>
      <div class="post-cards">
        <?php while (have_posts()) : the_post(); ?>
          <a href="<?php the_permalink(); ?>" class="post-card reveal">
            <div class="post-img">
              <?php
              if (has_post_thumbnail()) {
                  the_post_thumbnail('pcp-card', [
                      'loading' => 'lazy',
                      'alt'     => esc_attr(get_the_title()),
                  ]);
              } else {
                  printf(
                      '<img src="%s" alt="%s" width="800" height="600" loading="lazy" decoding="async">',
                      esc_url(PCP_THEME_URI . '/assets/images/spray-nozzle.jpg'),
                      esc_attr(get_the_title())
                  );
              }
              $cats = get_the_category();
              if (!empty($cats)) :
              ?>
                <span class="post-cat"><?php echo esc_html($cats[0]->name); ?></span>
              <?php endif; ?>
            </div>
            <div class="post-body">
              <div class="post-meta">
                <span><?php echo esc_html(get_the_date()); ?></span>
              </div>
              <h3><?php the_title(); ?></h3>
              <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?></p>
              <span class="post-read">Read article <?php echo pcp_icon('arrow'); ?></span>
            </div>
          </a>
        <?php endwhile; ?>
      </div>

      <?php
      $pag = paginate_links([
          'type'      => 'array',
          'prev_text' => pcp_icon('caret'),
          'next_text' => pcp_icon('caret'),
      ]);
      if ($pag) :
      ?>
        <nav class="pagination reveal" aria-label="Pagination">
          <?php echo implode('', $pag); ?>
        </nav>
      <?php endif; ?>

    <?php else : ?>
      <div class="post-cards">
        <p>No articles have been published yet. Check back soon.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php
get_template_part('template-parts/cta-band');
get_template_part('template-parts/contact');
get_footer();
