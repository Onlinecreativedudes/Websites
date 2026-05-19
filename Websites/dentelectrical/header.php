<?php
if (!defined('ABSPATH')) { exit; }

$is_thankyou = is_page_template('page-templates/thank-you.php');
$is_landing  = is_page_template('page-templates/landing-page.php');
$is_front    = is_front_page();

$meta_description = get_field('meta_description');
$og_image         = get_field('og_image');
$canonical_url    = get_permalink();

$promo_pills = get_field('promo_pills', 'option');
$promo_phone = ocd_site_phone_display();
$promo_tel   = ocd_site_phone_tel();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php if ($meta_description) : ?>
<meta name="description" content="<?php echo esc_attr($meta_description); ?>">
<?php endif; ?>

<?php if ($is_thankyou) : ?>
<meta name="robots" content="noindex, nofollow">
<?php endif; ?>

<?php if ($canonical_url) : ?>
<link rel="canonical" href="<?php echo esc_url($canonical_url); ?>">
<?php endif; ?>

<meta property="og:type" content="website">
<meta property="og:title" content="<?php echo esc_attr(wp_get_document_title()); ?>">
<?php if ($meta_description) : ?>
<meta property="og:description" content="<?php echo esc_attr($meta_description); ?>">
<?php endif; ?>
<meta property="og:url" content="<?php echo esc_url($canonical_url); ?>">
<?php if ($og_image && !empty($og_image['url'])) : ?>
<meta property="og:image" content="<?php echo esc_url($og_image['url']); ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="<?php echo esc_url($og_image['url']); ?>">
<?php endif; ?>

<?php
if ($is_landing || $is_front) {
    $hero_image = get_field('hero_image');
    if ($hero_image && !empty($hero_image['ID'])) {
        $preload_src = wp_get_attachment_image_url($hero_image['ID'], 'hero');
        if ($preload_src) {
            echo '<link rel="preload" as="image" href="' . esc_url($preload_src) . '" fetchpriority="high">' . "\n";
        }
    }
}
?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e('Skip to main content', 'dentelectrical'); ?></a>

<?php if ($promo_pills && is_array($promo_pills)) : ?>
<div class="promo">
    <div class="container">
        <div class="promo__inner">
            <div class="promo__pills">
                <?php foreach ($promo_pills as $pill) :
                    $label = is_array($pill) ? ($pill['label'] ?? '') : (string) $pill;
                    if (!$label) { continue; }
                ?>
                    <span class="promo__pill">
                        <?php echo ocd_icon('check', ['class' => 'promo__pill-icon']); ?>
                        <?php echo esc_html($label); ?>
                    </span>
                <?php endforeach; ?>
            </div>
            <?php if ($promo_phone) : ?>
                <a href="<?php echo esc_url($promo_tel); ?>" class="promo__cta">
                    <?php echo ocd_icon('phone', ['class' => 'promo__cta-icon']); ?>
                    <?php printf(esc_html__('Call %s', 'dentelectrical'), esc_html($promo_phone)); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<nav class="nav <?php echo $is_landing || $is_front ? 'nav--over-hero' : ''; ?>" id="site-nav" aria-label="<?php esc_attr_e('Primary', 'dentelectrical'); ?>">
    <div class="container">
        <div class="nav__inner">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="nav__logo" rel="home">
                <?php
                $logo = get_field('site_logo', 'option');
                if ($logo && !empty($logo['ID'])) {
                    echo wp_get_attachment_image($logo['ID'], 'medium', false, [
                        'class' => 'nav__logo-img',
                        'alt'   => esc_attr(get_bloginfo('name')),
                    ]);
                } else {
                    echo '<span class="nav__logo-text">' . esc_html(get_bloginfo('name')) . '</span>';
                }
                ?>
            </a>

            <button type="button" class="nav__toggle" aria-controls="nav-menu" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'dentelectrical'); ?>">
                <span class="nav__toggle-icon nav__toggle-icon--open"><?php echo ocd_icon('menu'); ?></span>
                <span class="nav__toggle-icon nav__toggle-icon--close"><?php echo ocd_icon('close'); ?></span>
            </button>

            <div class="nav__menu" id="nav-menu">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'nav__links',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ]);
                }
                ?>

                <div class="nav__actions">
                    <?php if ($promo_phone) : ?>
                        <a href="<?php echo esc_url($promo_tel); ?>" class="nav__phone">
                            <?php echo ocd_icon('phone'); ?>
                            <span><?php echo esc_html($promo_phone); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php
                    $nav_cta = get_field('nav_cta', 'option');
                    if ($nav_cta && !empty($nav_cta['url'])) {
                        ocd_render_link($nav_cta, 'btn btn--primary btn--sm');
                    } else {
                        printf(
                            '<a href="%s" class="btn btn--primary btn--sm">%s %s</a>',
                            esc_url(home_url('/#contact')),
                            esc_html__('Get a quote', 'dentelectrical'),
                            ocd_icon('arrow', ['class' => 'btn__arrow'])
                        );
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</nav>
