<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Render an ACF Link field as an anchor.
 */
function ocd_render_link($link, $class = 'btn') {
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
 * Format a phone number into a tel: href (strips non-dialable characters).
 */
function ocd_tel_href($phone) {
    return 'tel:' . preg_replace('/[^0-9+]/', '', (string) $phone);
}

/**
 * Site phone — human-readable display version.
 */
function ocd_site_phone_display() {
    return (string) get_field('display_phone', 'option');
}

/**
 * Site phone — dialable tel: href.
 */
function ocd_site_phone_tel() {
    return ocd_tel_href((string) get_field('business_phone', 'option'));
}

/**
 * Render an ACF Image (array return format) via wp_get_attachment_image() so
 * srcset, width, height and native lazy-loading come for free.
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
 * Inline SVG icon set, ported from the design. Centralised so markup stays
 * clean. Pass ['stroke-width' => '1.7'] etc to override defaults per call.
 */
function ocd_icon($name, $attrs = []) {
    $icons = [
        'check'  => '<path d="M20 6 9 17l-5-5"/>',
        'arrow'  => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'phone'  => '<path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.3 0 .7-.2 1l-2.2 2.2z"/>',
        'mail'   => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/>',
        'pin'    => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'clock'  => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>',
        'price'  => '<path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
        'home'   => '<path d="M3 11l9-8 9 8M5 10v10h14V10"/>',
        'eye'    => '<path d="M2 12s4-8 10-8 10 8 10 8-4 8-10 8-10-8-10-8z"/><circle cx="12" cy="12" r="3"/>',
        'pencil' => '<path d="M14 4l6 6-9 9-6 1 1-6z"/><path d="M3 21h7"/>',
        'chat'   => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
        'star'   => '<path d="M12 2l3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.1-6.8-5-4.9 7-1z"/>',
    ];

    if (!isset($icons[$name])) { return ''; }

    $defaults = [
        'class'           => 'icon icon--' . $name,
        'viewBox'         => '0 0 24 24',
        'fill'            => 'none',
        'stroke'          => 'currentColor',
        'stroke-width'    => '2',
        'stroke-linecap'  => 'round',
        'stroke-linejoin' => 'round',
        'aria-hidden'     => 'true',
        'focusable'       => 'false',
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
 * Default primary nav links, used as a fallback before the install agent
 * builds the WordPress menu so the header still renders correctly.
 *
 * @return array<int,array{url:string,label:string}>
 */
function ocd_default_nav_links() {
    return [
        ['url' => '#services', 'label' => __('Cabinetry Services', 'woodycraftwork')],
        ['url' => '#about',    'label' => __('About', 'woodycraftwork')],
        ['url' => '#why',      'label' => __('Why Us', 'woodycraftwork')],
        ['url' => '#gallery',  'label' => __('Our Work', 'woodycraftwork')],
        ['url' => '#contact',  'label' => __('Contact', 'woodycraftwork')],
    ];
}
