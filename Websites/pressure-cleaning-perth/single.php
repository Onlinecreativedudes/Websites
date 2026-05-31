<?php
/**
 * Single blog post. Ported from Blog_Post.html. Hero with the post title as the
 * single H1, crumbs, then .article-section with .article-body (the_content) and a
 * .article-sidebar aside (CTA + recent posts). Then the shared CTA band and contact.
 */
if (!defined('ABSPATH')) { exit; }
get_header();

$home_url  = home_url('/');
$blog_url  = get_permalink((int) get_option('page_for_posts')) ?: $home_url;
$phone_d   = pcp_phone_display();
$phone_t   = pcp_phone_tel();
$quote_url = pcp_field('nav_quote_url', home_url('/contact/'), 'option');

while (have_posts()) : the_post();
    $mobile_hero = pcp_field('mobile_hero_image', '', get_the_ID());
    $cats = get_the_category();
?>

<section class="hero hero-svc hero-post"<?php
    if (has_post_thumbnail()) {
        $bg = get_the_post_thumbnail_url(get_the_ID(), 'pcp-hero');
        if ($bg) { echo ' style="background-image:url(\'' . esc_url($bg) . '\')"'; }
    }
?>>
  <div class="wrap">
    <div class="reveal in">
      <?php pcp_mobile_hero(is_array($mobile_hero) ? $mobile_hero : null, get_the_title()); ?>
      <nav class="crumbs" aria-label="Breadcrumb">
        <a href="<?php echo esc_url($home_url); ?>">Home</a>
        <?php echo pcp_icon('arrow'); ?>
        <a href="<?php echo esc_url($blog_url); ?>">The Blog</a>
        <?php echo pcp_icon('arrow'); ?>
        <span><?php the_title(); ?></span>
      </nav>
      <?php if (!empty($cats)) : ?>
        <span class="post-cat in-body" style="margin-bottom:18px"><?php echo esc_html($cats[0]->name); ?></span>
      <?php endif; ?>
      <h1><?php the_title(); ?></h1>
      <?php if (has_excerpt()) : ?>
        <p class="sub"><?php echo esc_html(get_the_excerpt()); ?></p>
      <?php endif; ?>
      <div class="post-hero-meta">
        <span class="author-chip"><img src="<?php echo esc_url(PCP_THEME_URI . '/assets/brand/mascot.png'); ?>" alt="" width="44" height="44"><span><b><?php echo esc_html(get_the_author()); ?></b><i>Pressure Cleaning Perth</i></span></span>
        <span class="hm-meta"><?php echo pcp_icon('clock'); ?> <?php echo esc_html(get_the_date()); ?></span>
      </div>
    </div>
  </div>
</section>

<section class="article-section pad-sm">
  <div class="wrap">
    <div class="article-grid">

      <article class="article-body reveal">
        <?php if (has_post_thumbnail()) : ?>
          <figure class="article-figure">
            <?php the_post_thumbnail('pcp-wide', ['alt' => esc_attr(get_the_title())]); ?>
          </figure>
        <?php endif; ?>
        <?php the_content(); ?>

        <div class="article-cta">
          <div>
            <h3><?php echo esc_html(pcp_field('sidebar_cta_heading', 'Free inspection & quote', get_the_ID())); ?></h3>
            <p><?php echo esc_html(pcp_field('sidebar_cta_body', 'Tell us the suburb, send a photo if you have one, and we will come back fast.', get_the_ID())); ?></p>
          </div>
          <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green btn-lg">Get a Free Quote</a>
        </div>

        <?php
        $tags = get_the_tags();
        if ($tags) :
        ?>
          <div class="tags-share">
            <div class="tags">
              <?php foreach ($tags as $tag) : ?>
                <span class="tag"><?php echo esc_html($tag->name); ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </article>

      <aside class="article-sidebar">
        <div class="sb-card sb-cta">
          <span class="kicker" style="color:#cbf2a3;background:rgba(109,211,60,.18);border:1px solid rgba(109,211,60,.35)">Free Quote</span>
          <h4><?php echo esc_html(pcp_field('sidebar_cta_heading', 'Need a clean done right?', get_the_ID())); ?></h4>
          <p><?php echo esc_html(pcp_field('sidebar_cta_body', 'We are family-owned, fully insured to $20M, and have a 5.0 Google rating. Free, no-obligation quotes across Perth.', get_the_ID())); ?></p>
          <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-green">Get a Free Quote</a>
          <a href="<?php echo esc_url($phone_t); ?>" class="sb-call"><?php echo pcp_icon('phone'); ?> <?php echo esc_html($phone_d); ?></a>
        </div>

        <?php
        $recent = new WP_Query([
            'post_type'           => 'post',
            'posts_per_page'      => 3,
            'post__not_in'        => [get_the_ID()],
            'ignore_sticky_posts' => true,
            'no_found_rows'       => true,
        ]);
        if ($recent->have_posts()) :
        ?>
          <div class="sb-card sb-recent">
            <h4>Recent articles</h4>
            <?php while ($recent->have_posts()) : $recent->the_post();
                $r_cats = get_the_category();
            ?>
              <a href="<?php the_permalink(); ?>" class="sb-post">
                <div class="sb-img">
                  <?php
                  if (has_post_thumbnail()) {
                      the_post_thumbnail('pcp-card', ['loading' => 'lazy', 'alt' => '']);
                  } else {
                      printf(
                          '<img src="%s" alt="" width="800" height="600" loading="lazy" decoding="async">',
                          esc_url(PCP_THEME_URI . '/assets/images/spray-nozzle.jpg')
                      );
                  }
                  ?>
                </div>
                <div class="sb-text">
                  <?php if (!empty($r_cats)) : ?><span class="sb-cat"><?php echo esc_html($r_cats[0]->name); ?></span><?php endif; ?>
                  <h4><?php the_title(); ?></h4>
                  <span class="sb-date"><?php echo esc_html(get_the_date()); ?></span>
                </div>
              </a>
            <?php endwhile; ?>
          </div>
          <?php wp_reset_postdata(); ?>
        <?php endif; ?>
      </aside>

    </div>
  </div>
</section>

<?php
endwhile;

get_template_part('template-parts/cta-band');
get_template_part('template-parts/contact');
get_footer();
