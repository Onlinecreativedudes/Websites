<?php
if (!defined('ABSPATH')) { exit; }

get_header();
$phone = ocd_site_phone_display();
?>

<main id="main" class="page page--404">
    <div class="container" style="max-width:800px;padding-top:160px;padding-bottom:100px;text-align:center;">
        <span class="eyebrow center"><?php esc_html_e('404', 'woodycraftwork'); ?></span>
        <h1 style="margin-top:20px;"><?php esc_html_e('This page is off the bench.', 'woodycraftwork'); ?></h1>
        <p style="margin-top:18px;color:var(--text-soft);"><?php esc_html_e("The page you're after isn't here. Head back home, or get in touch and we'll point you the right way.", 'woodycraftwork'); ?></p>
        <div class="hero-actions" style="justify-content:center;margin-top:32px;">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn"><?php esc_html_e('Back to home', 'woodycraftwork'); ?> <?php echo ocd_icon('arrow', ['class' => 'icon']); ?></a>
            <?php if ($phone) : ?>
                <a href="<?php echo esc_url(ocd_site_phone_tel()); ?>" class="btn outline"><?php echo esc_html($phone); ?></a>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();
