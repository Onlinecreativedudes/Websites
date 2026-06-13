<?php
if (!defined('ABSPATH')) { exit; }

$urgency_text = get_field('urgency_bar_text', 'option') ?: 'No.1 Solar Retailer in Western Australia — SunWiz Award 2025';
$logo_light   = get_field('logo_light', 'option');
$logo_dark    = get_field('logo_dark', 'option');
$phone        = sn_site_phone_display() ?: '1300 168 138';
$nav_links    = get_field('header_nav_links', 'option');

if (!$nav_links) {
    $nav_links = [
        ['label' => 'Packages', 'anchor' => '#services'],
        ['label' => 'Battery',  'anchor' => '#services'],
        ['label' => 'Projects', 'anchor' => '#gallery'],
        ['label' => 'Why Us',   'anchor' => '#why'],
        ['label' => 'Reviews',  'anchor' => '#reviews'],
    ];
}

$logo_light_url = ($logo_light && !empty($logo_light['url'])) ? $logo_light['url'] : SN_THEME_URI . '/assets/img/logo-light.png';
$logo_dark_url  = ($logo_dark && !empty($logo_dark['url']))   ? $logo_dark['url']  : SN_THEME_URI . '/assets/img/logo-trans.png';
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="ub">
    <div class="wrap ub__in ub__in--center">
        <span class="ub__left"><span class="stars">&#9733;</span> <?php echo esc_html($urgency_text); ?></span>
    </div>
</div>

<header class="hd js-header">
    <div class="wrap hd__in">
        <a href="#top" class="hd__logo js-scroll-top">
            <img src="<?php echo esc_url($logo_light_url); ?>" data-light="<?php echo esc_url($logo_light_url); ?>" data-solid="<?php echo esc_url($logo_dark_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="js-header-logo">
        </a>
        <nav class="hd__nav">
            <?php foreach ($nav_links as $link) :
                if (empty($link['label']) || empty($link['anchor'])) { continue; }
            ?>
                <a href="<?php echo esc_attr($link['anchor']); ?>" class="js-scroll-link"><?php echo esc_html($link['label']); ?></a>
            <?php endforeach; ?>
        </nav>
        <div class="hd__right">
            <a class="hd__phone" href="<?php echo esc_attr(sn_tel_href($phone)); ?>"><?php echo sn_icon('phone', 16); ?> <?php echo esc_html($phone); ?></a>
            <button class="btn btn--gold hd__cta js-open-assess">Free Assessment</button>
        </div>
    </div>
</header>
