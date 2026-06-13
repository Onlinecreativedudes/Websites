<?php
if (!defined('ABSPATH')) { exit; }

get_header();
?>

<main id="main">
    <section class="section">
        <div class="wrap">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <h1><?php the_title(); ?></h1>
                    <?php the_content(); ?>
                </article>
            <?php endwhile; endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
