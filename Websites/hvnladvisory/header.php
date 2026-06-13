<?php
if (!defined('ABSPATH')) { exit; }

$is_thankyou = is_page_template('page-templates/thank-you.php');
$is_landing  = is_page_template('page-templates/landing-page.php') || is_front_page();

$announcement = get_field('announcement_text', 'option');
$logo         = get_field('site_logo', 'option');
$nav_phone    = ocd_site_phone_display();
$nav_cta      = get_field('nav_cta_label', 'option');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php // Titles, descriptions, canonicals and Open Graph are owned by Yoast SEO. ?>
<?php if ($is_thankyou) : ?>
<meta name="robots" content="noindex, nofollow">
<?php endif; ?>

<?php
if ($is_landing) {
    $hero_image = get_field('hero_image');
    if ($hero_image && !empty($hero_image['ID'])) {
        $preload_src = wp_get_attachment_image_url($hero_image['ID'], 'hero');
        if ($preload_src) {
            echo '<link rel="preload" as="image" href="' . esc_url($preload_src) . '" fetchpriority="high" media="(min-width: 769px)">' . "\n";
        }
    }
    $hero_mobile = get_field('hero_mobile_image');
    if ($hero_mobile && !empty($hero_mobile['ID'])) {
        $preload_mobile = wp_get_attachment_image_url($hero_mobile['ID'], 'card');
        if ($preload_mobile) {
            echo '<link rel="preload" as="image" href="' . esc_url($preload_mobile) . '" fetchpriority="high" media="(max-width: 768px)">' . "\n";
        }
    }
}
?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e('Skip to main content', 'hvnladvisory'); ?></a>

<?php if ($announcement) : ?>
<div class="announce">
    <div class="announce__inner container container--wide">
        <span class="announce__dot" aria-hidden="true"></span>
        <span><?php echo esc_html($announcement); ?></span>
    </div>
</div>
<?php endif; ?>

<header class="nav" id="site-nav" data-nav>
    <div class="nav__inner container container--wide">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="nav__logo" rel="home" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?> home">
            <?php
            if ($logo && !empty($logo['ID'])) {
                echo ocd_render_image($logo, 'medium', 'nav__logo-img logo-blend');
            } else {
                echo '<span class="nav__logo-text">' . esc_html(get_bloginfo('name')) . '</span>';
            }
            ?>
        </a>

        <nav class="nav__desktop" aria-label="<?php esc_attr_e('Primary', 'hvnladvisory'); ?>">
            <?php ocd_primary_nav('nav__links'); ?>
            <?php if ($nav_phone) : ?><span class="nav__divider" aria-hidden="true"></span><?php endif; ?>
            <?php ocd_phone_link('nav__phone'); ?>
            <?php ocd_book_cta($nav_cta ?: 'Book an Exposure Review', 'btn btn--dark btn--sm nav__cta'); ?>
        </nav>

        <button type="button" class="nav__toggle" data-nav-toggle aria-controls="nav-menu" aria-expanded="false" aria-label="<?php esc_attr_e('Menu', 'hvnladvisory'); ?>">
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<div class="nav-menu" id="nav-menu" data-nav-menu>
    <?php ocd_primary_nav('nav-menu__links'); ?>
    <?php ocd_phone_link('nav-menu__phone'); ?>
    <?php ocd_book_cta($nav_cta ?: 'Book an Exposure Review', 'btn btn--bronze nav-menu__cta'); ?>
</div>
