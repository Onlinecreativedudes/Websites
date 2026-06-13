<?php
if (!defined('ABSPATH')) { exit; }

get_header();
?>

<main id="main" class="page page--default">
    <div class="container" style="max-width:800px;padding-top:140px;padding-bottom:80px;">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('page-entry'); ?>>
                <header class="page-entry__header">
                    <h1 class="page-entry__title"><?php the_title(); ?></h1>
                </header>
                <div class="page-entry__content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
