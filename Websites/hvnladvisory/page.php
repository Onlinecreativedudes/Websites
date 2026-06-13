<?php
if (!defined('ABSPATH')) { exit; }

get_header();
?>

<main class="page page--default" id="main">
    <section class="plain">
        <div class="container container--narrow">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <h1><?php the_title(); ?></h1>
                    <div class="plain__content"><?php the_content(); ?></div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
