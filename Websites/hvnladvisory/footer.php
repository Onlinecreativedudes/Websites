<?php
if (!defined('ABSPATH')) { exit; }

$logo        = get_field('site_logo', 'option');
$blurb       = get_field('footer_blurb', 'option');
$cta_label   = get_field('footer_cta_label', 'option');
$email       = get_field('business_email', 'option');
$coverage    = get_field('coverage_text', 'option');
$copyright   = get_field('copyright_line', 'option');
$disclaimer  = get_field('footer_disclaimer', 'option');
?>

<footer class="footer">
    <div class="container container--wide">
        <div class="footer__top">
            <div class="footer__brand">
                <?php
                if ($logo && !empty($logo['ID'])) {
                    echo ocd_render_image($logo, 'medium', 'footer__logo logo-blend-dark');
                } else {
                    echo '<span class="footer__name">' . esc_html(get_bloginfo('name')) . '</span>';
                }
                ?>
                <?php if ($blurb) : ?>
                    <p class="footer__blurb"><?php echo esc_html($blurb); ?></p>
                <?php endif; ?>
                <?php ocd_book_cta($cta_label ?: 'Book an Exposure Review', 'btn btn--bronze footer__cta'); ?>
            </div>

            <div class="footer__cols">
                <?php if (has_nav_menu('footer-quick')) : ?>
                    <div class="footer__col">
                        <span class="footer__heading"><?php esc_html_e('Quick links', 'hvnladvisory'); ?></span>
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer-quick',
                            'container'      => false,
                            'menu_class'     => 'footer__links',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ]);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (has_nav_menu('footer-services')) : ?>
                    <div class="footer__col">
                        <span class="footer__heading"><?php esc_html_e('Services', 'hvnladvisory'); ?></span>
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer-services',
                            'container'      => false,
                            'menu_class'     => 'footer__links',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ]);
                        ?>
                    </div>
                <?php endif; ?>

                <div class="footer__col">
                    <span class="footer__heading"><?php esc_html_e('Contact', 'hvnladvisory'); ?></span>
                    <ul class="footer__links">
                        <?php if ($email) : ?>
                            <li><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></li>
                        <?php endif; ?>
                        <?php if (ocd_site_phone_display()) : ?>
                            <li><a href="<?php echo esc_url(ocd_site_phone_tel()); ?>"><?php echo esc_html(ocd_site_phone_display()); ?></a></li>
                        <?php endif; ?>
                        <?php if ($coverage) : ?>
                            <li><span><?php echo esc_html($coverage); ?></span></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer__bottom">
            <span><?php echo esc_html($copyright ?: '© ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.'); ?></span>
            <?php if ($disclaimer) : ?>
                <span class="footer__disclaimer"><?php echo esc_html($disclaimer); ?></span>
            <?php endif; ?>
        </div>
    </div>
</footer>

<?php
// LocalBusiness schema from Site Options. Yoast outputs Organization/WebSite
// schema only, so this does not duplicate it.
$schema_type = get_field('schema_business_type', 'option');
$phone       = get_field('business_phone', 'option');
if ($schema_type && ($phone || $email)) :
?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": <?php echo wp_json_encode($schema_type); ?>,
    "name": <?php echo wp_json_encode(get_bloginfo('name')); ?>,
    "url": <?php echo wp_json_encode(home_url('/')); ?><?php if ($phone) : ?>,
    "telephone": <?php echo wp_json_encode($phone); ?><?php endif; ?><?php if ($email) : ?>,
    "email": <?php echo wp_json_encode($email); ?><?php endif; ?><?php if ($coverage) : ?>,
    "areaServed": <?php echo wp_json_encode($coverage); ?><?php endif; ?>

}
</script>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
