<?php
if (!defined('ABSPATH')) { exit; }

$logo_mark   = get_field('logo_mark', 'option');
$brand_name  = get_field('brand_name', 'option') ?: get_bloginfo('name');
$brand_tag   = get_field('brand_tagline', 'option');
$footer_blurb = get_field('footer_blurb', 'option');
$copyright    = get_field('footer_copyright', 'option');
$footer_credit_right = get_field('footer_credit', 'option');

$phone_display = ocd_site_phone_display();
$phone_tel     = ocd_site_phone_tel();
$email         = get_field('business_email', 'option');
?>

<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="brand" rel="home">
                    <span class="mark">
                        <?php
                        if ($logo_mark && !empty($logo_mark['ID'])) {
                            echo wp_get_attachment_image($logo_mark['ID'], 'thumbnail', false, ['alt' => esc_attr($brand_name)]);
                        }
                        ?>
                    </span>
                    <span class="wm"><?php echo esc_html($brand_name); ?>
                        <?php if ($brand_tag) : ?><small><?php echo esc_html($brand_tag); ?></small><?php endif; ?>
                    </span>
                </a>
                <?php if ($footer_blurb) : ?>
                    <p><?php echo esc_html($footer_blurb); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <h4><?php esc_html_e('Quick Links', 'woodycraftwork'); ?></h4>
                <ul>
                    <?php
                    if (has_nav_menu('footer_quick')) {
                        wp_nav_menu([
                            'theme_location' => 'footer_quick',
                            'container'      => false,
                            'menu_class'     => '',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                            'items_wrap'     => '%3$s',
                        ]);
                    } else {
                        foreach (ocd_default_nav_links() as $link) {
                            printf('<li><a href="%s">%s</a></li>', esc_url($link['url']), esc_html($link['label']));
                        }
                    }
                    ?>
                </ul>
            </div>

            <div>
                <h4><?php esc_html_e('Our Services', 'woodycraftwork'); ?></h4>
                <ul>
                    <?php
                    if (has_nav_menu('footer_services')) {
                        wp_nav_menu([
                            'theme_location' => 'footer_services',
                            'container'      => false,
                            'menu_class'     => '',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                            'items_wrap'     => '%3$s',
                        ]);
                    } else {
                        $svc_links = [
                            __('Custom Kitchens', 'woodycraftwork'),
                            __('Bathroom Cabinetry', 'woodycraftwork'),
                            __('Laundry Cabinetry', 'woodycraftwork'),
                            __('Wardrobes & Robes', 'woodycraftwork'),
                            __('Home Offices', 'woodycraftwork'),
                        ];
                        foreach ($svc_links as $label) {
                            printf('<li><a href="%s">%s</a></li>', esc_url(home_url('/#services')), esc_html($label));
                        }
                    }
                    ?>
                </ul>
            </div>

            <div>
                <h4><?php esc_html_e('Get a Quote', 'woodycraftwork'); ?></h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/#contact')); ?>"><?php esc_html_e('Free consultation', 'woodycraftwork'); ?></a></li>
                    <?php if ($phone_display) : ?>
                        <li><a href="<?php echo esc_url($phone_tel); ?>"><?php echo esc_html($phone_display); ?></a></li>
                    <?php endif; ?>
                    <?php if ($email) : ?>
                        <li><a href="mailto:<?php echo esc_attr($email); ?>"><?php esc_html_e('Email us', 'woodycraftwork'); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>
                <?php
                if ($copyright) {
                    echo esc_html(str_replace('{year}', date('Y'), $copyright));
                } else {
                    printf('&copy; %s %s.', date('Y'), esc_html($brand_name));
                }
                ?>
            </span>
            <span class="footer-credit">
                <?php echo $footer_credit_right ? esc_html($footer_credit_right) : esc_html__('Made in Perth.', 'woodycraftwork'); ?>
            </span>
        </div>
    </div>
</footer>

<div class="lightbox" id="lightbox" aria-hidden="true">
    <button class="lb-close" type="button" aria-label="<?php esc_attr_e('Close', 'woodycraftwork'); ?>">&times;</button>
    <button class="lb-nav lb-prev" type="button" aria-label="<?php esc_attr_e('Previous', 'woodycraftwork'); ?>">&lsaquo;</button>
    <img id="lb-img" alt="">
    <button class="lb-nav lb-next" type="button" aria-label="<?php esc_attr_e('Next', 'woodycraftwork'); ?>">&rsaquo;</button>
</div>

<?php wp_footer(); ?>
</body>
</html>
