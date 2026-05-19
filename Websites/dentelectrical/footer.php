<?php
if (!defined('ABSPATH')) { exit; }

$footer_blurb     = get_field('footer_blurb', 'option');
$footer_logo      = get_field('footer_logo', 'option');
$footer_copyright = get_field('footer_disclaimer', 'option');
$ec_number        = get_field('ec_number', 'option');
$phone_display    = ocd_site_phone_display();
$phone_tel        = ocd_site_phone_tel();
$email            = get_field('business_email', 'option');
$address_short    = get_field('address_short', 'option');

$schema_phone     = get_field('business_phone', 'option');
$schema_address   = get_field('business_address', 'option');
$schema_suburb    = get_field('schema_suburb', 'option');
$schema_state     = get_field('schema_state', 'option');
$schema_postcode  = get_field('schema_postcode', 'option');
$schema_type      = get_field('schema_business_type', 'option') ?: 'LocalBusiness';
$schema_abn       = get_field('schema_abn', 'option');
?>

<footer class="footer">
    <div class="container">
        <div class="footer__grid">
            <div class="footer__brand">
                <?php if ($footer_logo && !empty($footer_logo['ID'])) : ?>
                    <?php echo wp_get_attachment_image($footer_logo['ID'], 'medium', false, [
                        'class' => 'footer__logo',
                        'alt'   => esc_attr(get_bloginfo('name')),
                    ]); ?>
                <?php else : ?>
                    <span class="footer__logo-text"><?php echo esc_html(get_bloginfo('name')); ?></span>
                <?php endif; ?>
                <?php if ($footer_blurb) : ?>
                    <p class="footer__blurb"><?php echo esc_html($footer_blurb); ?></p>
                <?php endif; ?>
            </div>

            <?php if (has_nav_menu('footer_services')) : ?>
                <div class="footer__col">
                    <h4 class="footer__heading"><?php esc_html_e('Services', 'dentelectrical'); ?></h4>
                    <?php wp_nav_menu([
                        'theme_location' => 'footer_services',
                        'container'      => false,
                        'menu_class'     => 'footer__links',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ]); ?>
                </div>
            <?php endif; ?>

            <?php if (has_nav_menu('footer_company')) : ?>
                <div class="footer__col">
                    <h4 class="footer__heading"><?php esc_html_e('Company', 'dentelectrical'); ?></h4>
                    <?php wp_nav_menu([
                        'theme_location' => 'footer_company',
                        'container'      => false,
                        'menu_class'     => 'footer__links',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ]); ?>
                </div>
            <?php endif; ?>

            <div class="footer__col">
                <h4 class="footer__heading"><?php esc_html_e('Contact', 'dentelectrical'); ?></h4>
                <ul class="footer__links">
                    <?php if ($phone_display) : ?>
                        <li><a href="<?php echo esc_url($phone_tel); ?>"><?php echo esc_html($phone_display); ?></a></li>
                    <?php endif; ?>
                    <?php if ($email) : ?>
                        <li><a href="mailto:<?php echo esc_attr($email); ?>"><?php esc_html_e('Email us', 'dentelectrical'); ?></a></li>
                    <?php endif; ?>
                    <?php if ($address_short) : ?>
                        <li><?php echo esc_html($address_short); ?></li>
                    <?php endif; ?>
                    <?php if ($ec_number) : ?>
                        <li><?php echo esc_html($ec_number); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="footer__bottom">
            <p>
                <?php if ($footer_copyright) {
                    echo esc_html(str_replace('{year}', date('Y'), $footer_copyright));
                } else {
                    printf('&copy; %s %s. %s', date('Y'), esc_html(get_bloginfo('name')), esc_html__('All rights reserved.', 'dentelectrical'));
                } ?>
            </p>
            <p class="footer__credit">
                <?php esc_html_e('Site by', 'dentelectrical'); ?>
                <a href="https://onlinecreativedudes.com.au" target="_blank" rel="noopener">Online Creative Dudes</a>
            </p>
        </div>
    </div>
</footer>

<?php get_template_part('template-parts/mobile-bar'); ?>

<?php if ($schema_phone || $schema_address) :
    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => $schema_type,
        'name'     => get_bloginfo('name'),
        'url'      => home_url('/'),
    ];
    if ($schema_phone)   { $schema['telephone'] = $schema_phone; }
    if ($email)          { $schema['email']     = $email; }
    if ($schema_address) {
        $schema['address'] = [
            '@type'           => 'PostalAddress',
            'streetAddress'   => $schema_address,
            'addressLocality' => $schema_suburb,
            'addressRegion'   => $schema_state,
            'postalCode'      => $schema_postcode,
            'addressCountry'  => 'AU',
        ];
    }
    if ($schema_abn) { $schema['identifier'] = $schema_abn; }
    if ($footer_logo && !empty($footer_logo['url'])) { $schema['logo'] = $footer_logo['url']; }
?>
<script type="application/ld+json">
<?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
