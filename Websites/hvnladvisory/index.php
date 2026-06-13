<?php
if (!defined('ABSPATH')) { exit; }

get_header();
?>

<main class="page page--index" id="main">
    <section class="plain">
        <div class="container container--narrow">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <h1><?php the_title(); ?></h1>
                    <div class="plain__content"><?php the_content(); ?></div>
                </article>
            <?php endwhile; else : ?>
                <h1><?php esc_html_e('Nothing found', 'hvnladvisory'); ?></h1>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
