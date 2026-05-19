<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Render an ACF Link field as an anchor.
 */
function ocd_render_link($link, $class = 'btn btn--primary') {
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
 * Inline SVG icon set used across the theme. Centralised so markup stays clean.
 */
function ocd_icon($name, $attrs = []) {
    $icons = [
        'check'    => '<path d="M20 6 9 17l-5-5"/>',
        'arrow'    => '<path d="M5 12h14m-7-7 7 7-7 7"/>',
        'phone'    => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.33 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>',
        'mail'     => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/>',
        'pin'      => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
        'clock'    => '<circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>',
        'shield'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'shield-check' => '<path d="M9 12l2 2 4-4"/><path d="M21 12c0 5-3 9-9 9s-9-4-9-9 3-9 9-9 9 4 9 9z"/>',
        'badge'    => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
        'star'     => '<polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>',
        'home'     => '<path d="M3 9 12 2l9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/>',
        'panel'    => '<rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18M8 6V4m8 2V4"/>',
        'menu'     => '<path d="M3 6h18M3 12h18M3 18h18"/>',
        'close'    => '<path d="M18 6 6 18M6 6l12 12"/>',
    ];

    if (!isset($icons[$name])) { return ''; }

    $defaults = [
        'class'        => 'icon icon--' . $name,
        'viewBox'      => '0 0 24 24',
        'fill'         => 'none',
        'stroke'       => 'currentColor',
        'stroke-width' => '2',
        'stroke-linecap'  => 'round',
        'stroke-linejoin' => 'round',
        'aria-hidden'  => 'true',
        'focusable'    => 'false',
    ];

    if ($name === 'star') {
        $defaults['fill']   = 'currentColor';
        $defaults['stroke'] = 'none';
    }

    $attrs = array_merge($defaults, $attrs);

    $attr_str = '';
    foreach ($attrs as $k => $v) {
        $attr_str .= ' ' . esc_attr($k) . '="' . esc_attr($v) . '"';
    }

    return '<svg' . $attr_str . '>' . $icons[$name] . '</svg>';
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
