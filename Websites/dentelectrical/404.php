<?php
if (!defined('ABSPATH')) { exit; }

get_header();
?>

<main id="main" class="page page--404">
    <div class="container container--narrow">
        <section class="error-404">
            <span class="eyebrow"><?php esc_html_e('404', 'dentelectrical'); ?></span>
            <h1><?php esc_html_e('Power cut on this page.', 'dentelectrical'); ?></h1>
            <p><?php esc_html_e("The page you're after isn't here. Try the homepage, or give us a call and we'll point you the right way.", 'dentelectrical'); ?></p>
            <div class="error-404__actions">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary">
                    <?php esc_html_e('Back to home', 'dentelectrical'); ?>
                    <?php echo ocd_icon('arrow', ['class' => 'btn__arrow']); ?>
                </a>
                <?php $phone = ocd_site_phone_display(); if ($phone) : ?>
                    <a href="<?php echo esc_url(ocd_site_phone_tel()); ?>" class="btn btn--outline">
                        <?php echo ocd_icon('phone'); ?>
                        <?php echo esc_html($phone); ?>
                    </a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<?php
get_footer();
