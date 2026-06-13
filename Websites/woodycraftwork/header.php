<?php
if (!defined('ABSPATH')) { exit; }

$annc_left  = get_field('announcement_left', 'option');
$annc_right = get_field('announcement_right', 'option');
$logo_mark  = get_field('logo_mark', 'option');
$brand_name = get_field('brand_name', 'option') ?: get_bloginfo('name');
$brand_tag  = get_field('brand_tagline', 'option');
$nav_cta    = get_field('nav_cta', 'option');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e('Skip to main content', 'woodycraftwork'); ?></a>

<header class="site-header" id="site-header">
    <?php if ($annc_left || $annc_right) : ?>
        <div class="annc">
            <?php if ($annc_left) : ?><span><?php echo esc_html($annc_left); ?></span><?php endif; ?>
            <?php if ($annc_left && $annc_right) : ?><span class="dot">/</span><?php endif; ?>
            <?php if ($annc_right) : ?><strong><?php echo esc_html($annc_right); ?></strong><?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <nav class="nav" aria-label="<?php esc_attr_e('Primary', 'woodycraftwork'); ?>">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="brand" rel="home" aria-label="<?php echo esc_attr($brand_name); ?>">
                <span class="mark">
                    <?php
                    if ($logo_mark && !empty($logo_mark['ID'])) {
                        echo wp_get_attachment_image($logo_mark['ID'], 'thumbnail', false, [
                            'alt' => esc_attr($brand_name),
                        ]);
                    }
                    ?>
                </span>
                <span class="wm"><?php echo esc_html($brand_name); ?>
                    <?php if ($brand_tag) : ?><small><?php echo esc_html($brand_tag); ?></small><?php endif; ?>
                </span>
            </a>

            <span class="nav-spacer"></span>

            <div class="nav-links">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => '',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                        'items_wrap'     => '%3$s',
                    ]);
                } else {
                    foreach (ocd_default_nav_links() as $link) {
                        printf('<a href="%s">%s</a>', esc_url($link['url']), esc_html($link['label']));
                    }
                }
                ?>
            </div>

            <?php
            if ($nav_cta && !empty($nav_cta['url'])) {
                ocd_render_link($nav_cta, 'btn on-dark nav-cta');
            } else {
                printf('<a href="%s" class="btn on-dark nav-cta">%s</a>', esc_url(home_url('/#contact')), esc_html__('Get a Quote', 'woodycraftwork'));
            }
            ?>

            <button class="burger" type="button" aria-label="<?php esc_attr_e('Open menu', 'woodycraftwork'); ?>" aria-controls="drawer" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </nav>
    </div>
</header>

<div class="drawer" id="drawer">
    <div class="drawer-top">
        <span class="wm display" style="font-size:1.3rem;color:#fff;letter-spacing:.04em;"><?php echo esc_html($brand_name); ?></span>
        <button class="drawer-close" type="button" data-drawer-close aria-label="<?php esc_attr_e('Close menu', 'woodycraftwork'); ?>">&times;</button>
    </div>
    <?php
    if (has_nav_menu('primary')) {
        wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => '',
            'depth'          => 1,
            'fallback_cb'    => false,
            'items_wrap'     => '%3$s',
        ]);
    } else {
        foreach (ocd_default_nav_links() as $link) {
            printf('<a href="%s">%s</a>', esc_url($link['url']), esc_html($link['label']));
        }
    }
    if ($nav_cta && !empty($nav_cta['url'])) {
        ocd_render_link($nav_cta, 'btn on-dark');
    } else {
        printf('<a href="%s" class="btn on-dark">%s</a>', esc_url(home_url('/#contact')), esc_html__('Get a Quote', 'woodycraftwork'));
    }
    ?>
</div>
