<?php
if (!defined('ABSPATH')) { exit; }

/**
 * The landing page's in-page sections, used for the anchor navigation.
 * Each section template renders a matching id (#services, #who, etc).
 */
function ocd_nav_items() {
    return [
        '#services' => 'Services',
        '#who'      => 'Who We Help',
        '#how'      => 'How We Work',
        '#about'    => 'About',
        '#contact'  => 'Contact',
    ];
}

/**
 * Primary navigation. Uses an assigned WordPress menu if there is one,
 * otherwise falls back to the landing page's section anchors so the nav
 * always works on this single-page site.
 */
function ocd_primary_nav($menu_class) {
    if (has_nav_menu('primary')) {
        wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => $menu_class,
            'depth'          => 1,
            'fallback_cb'    => false,
        ]);
        return;
    }
    echo '<ul class="' . esc_attr($menu_class) . '">';
    foreach (ocd_nav_items() as $href => $label) {
        printf('<li><a href="%s">%s</a></li>', esc_url($href), esc_html($label));
    }
    echo '</ul>';
}

/**
 * Render an ACF Link field as an anchor.
 */
function ocd_render_link($link, $class = 'btn btn--dark') {
    if (!$link || empty($link['url'])) { return; }
    $target = !empty($link['target']) ? ' target="_blank" rel="noopener"' : '';
    printf(
        '<a href="%s" class="%s"%s>%s</a>',
        esc_url($link['url']),
        esc_attr($class),
        $target,
        esc_html($link['title'])
    );
}

/**
 * Format a phone number into a tel: href (strips spaces and non-dialable chars).
 */
function ocd_tel_href($phone) {
    return 'tel:' . preg_replace('/[^0-9+]/', '', (string) $phone);
}

/**
 * Headlines may carry an italic serif accent stored as limited HTML, e.g.
 * Advice from the <span class="accent">field</span>, not a textbook.
 * Only the accent span and basic emphasis survive output.
 */
function ocd_kses_headline($html) {
    return wp_kses((string) $html, [
        'span' => ['class' => true],
        'em'   => [],
        'br'   => [],
    ]);
}

/**
 * Section kicker — the small uppercase label with a dash that opens most sections.
 */
function ocd_kicker($text, $tone = 'bronze') {
    if (!$text) { return; }
    printf(
        '<div class="kicker kicker--%s"><span class="kicker__dash"></span>%s</div>',
        esc_attr($tone),
        esc_html($text)
    );
}

/**
 * Tick list item prefix — the gold check used in every list on the page.
 */
function ocd_tick() {
    return '<span class="tick" aria-hidden="true">&#10003;</span>';
}

/**
 * Render an ACF Image (array return format) via wp_get_attachment_image() so
 * srcset and lazy-loading come for free.
 */
function ocd_render_image($img, $size = 'large', $class = '', $extra_attrs = []) {
    if (!$img || empty($img['ID'])) { return ''; }

    $attrs = array_merge([
        'class' => $class,
        'alt'   => esc_attr($img['alt'] ?? ''),
    ], $extra_attrs);

    return wp_get_attachment_image((int) $img['ID'], $size, false, $attrs);
}

/**
 * Site phone — returns the display version (formatted for humans).
 */
function ocd_site_phone_display() {
    return (string) get_field('display_phone', 'option');
}

/**
 * Site phone — returns the dialable version (digits only).
 */
function ocd_site_phone_tel() {
    $tel = (string) get_field('business_phone', 'option');
    return ocd_tel_href($tel);
}

/**
 * Phone link used beside CTA buttons throughout the page.
 */
function ocd_phone_link($class = 'phone-link') {
    $display = ocd_site_phone_display();
    if (!$display) { return; }
    printf(
        '<a href="%s" class="%s">%s</a>',
        esc_url(ocd_site_phone_tel()),
        esc_attr($class),
        esc_html($display)
    );
}

/**
 * The booking CTA that repeats across sections. Label is editable per section
 * via ACF; the target defaults to the hero form anchor.
 */
function ocd_book_cta($label, $class = 'btn btn--dark', $url = '#book') {
    if (!$label) { return; }
    printf(
        '<a href="%s" class="%s">%s</a>',
        esc_url($url),
        esc_attr($class),
        esc_html($label)
    );
}
