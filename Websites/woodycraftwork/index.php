<?php
if (!defined('ABSPATH')) { exit; }

get_header();
?>

<main id="main" class="page page--default">
    <div class="container" style="padding-top:140px;padding-bottom:80px;">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('post-entry'); ?>>
                    <header class="post-entry__header">
                        <h1 class="post-entry__title"><?php the_title(); ?></h1>
                    </header>
                    <div class="post-entry__content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <h1><?php esc_html_e('Nothing here yet.', 'woodycraftwork'); ?></h1>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
