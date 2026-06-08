<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Get an ACF field with a fallback default. Lets template parts ship with the
 * design's copy so the site renders correctly before content is entered.
 */
function pcp_field($name, $default = '', $post_id = false) {
    $val = function_exists('get_field') ? get_field($name, $post_id) : null;
    if ($val === null || $val === '' || $val === false) {
        return $default;
    }
    return $val;
}

/** Site phone — dialable digits from Site Options. */
function pcp_phone_tel() {
    $tel = (string) pcp_field('business_phone', '1300667818', 'option');
    return 'tel:' . preg_replace('/[^0-9+]/', '', $tel);
}

/** Site phone — human-readable display from Site Options. */
function pcp_phone_display() {
    return (string) pcp_field('display_phone', '1300 667 818', 'option');
}

/**
 * Render an ACF Link field as an anchor. $default_url/$default_label render a
 * button even when the field is empty (keeps the design's CTAs intact).
 */
function pcp_render_link($link, $class = 'btn btn-green', $default_url = '', $default_label = '') {
    if ((!$link || empty($link['url'])) && !$default_url) { return; }
    if (!$link || empty($link['url'])) {
        $link = ['url' => $default_url, 'title' => $default_label, 'target' => ''];
    }
    $label  = !empty($link['title']) ? $link['title'] : $default_label;
    $target = !empty($link['target']) ? ' target="_blank" rel="noopener"' : '';
    printf(
        '<a href="%s" class="%s"%s>%s</a>',
        esc_url($link['url']),
        esc_attr($class),
        $target,
        esc_html($label)
    );
}

/**
 * Render an ACF Image (array return) via wp_get_attachment_image so srcset,
 * width/height and lazy-loading come for free. Falls back to a theme asset path
 * when no attachment is set (used for design seed imagery).
 *
 * @param array  $img        ACF image array.
 * @param string $size       Registered image size.
 * @param string $class      CSS class.
 * @param array  $extra      Extra <img> attributes.
 * @param string $fallback   Theme-relative asset path, eg assets/images/x.jpg.
 * @param string $fallback_alt Alt text for the fallback.
 */
function pcp_image($img, $size = 'pcp-card', $class = '', $extra = [], $fallback = '', $fallback_alt = '') {
    if ($img && !empty($img['ID'])) {
        $attrs = array_merge([
            'class' => $class,
            'alt'   => esc_attr($img['alt'] ?? ''),
        ], $extra);
        return wp_get_attachment_image((int) $img['ID'], $size, false, $attrs);
    }
    if ($fallback) {
        $rel = ltrim($fallback, '/');
        $w = $extra['width'] ?? '';
        $h = $extra['height'] ?? '';
        // Auto-detect dimensions from the bundled file so every img has width/height (no CLS).
        if (!$w || !$h) {
            $path = PCP_THEME_PATH . '/' . $rel;
            if (is_readable($path) && ($size = @getimagesize($path))) {
                $w = $w ?: $size[0];
                $h = $h ?: $size[1];
            }
        }
        return sprintf(
            '<img src="%s" alt="%s" class="%s"%s%s loading="%s" decoding="async">',
            esc_url(PCP_THEME_URI . '/' . $rel),
            esc_attr($fallback_alt),
            esc_attr($class),
            $w ? ' width="' . esc_attr($w) . '"' : '',
            $h ? ' height="' . esc_attr($h) . '"' : '',
            isset($extra['loading']) ? esc_attr($extra['loading']) : 'lazy'
        );
    }
    return '';
}

/**
 * Standing requirement — mobile hero image. Renders above the hero title on
 * mobile only and is hidden on desktop (where the hero background shows). The
 * desktop <source> serves a 1x1 transparent GIF so the real file never loads on
 * desktop. Not lazy-loaded: it is the likely mobile LCP element.
 *
 * @param array  $img        ACF image array (mobile_hero_image).
 * @param string $alt        Alt text (from ACF).
 * @param int    $breakpoint Max-width breakpoint in px.
 */
function pcp_mobile_hero($img, $alt = '', $breakpoint = 768) {
    if (!$img || empty($img['ID'])) { return; }
    $src = wp_get_attachment_image_url((int) $img['ID'], 'pcp-card');
    if (!$src) { return; }
    $meta = wp_get_attachment_metadata((int) $img['ID']);
    $w = $meta['width'] ?? 800;
    $h = $meta['height'] ?? 600;
    $alt = $alt !== '' ? $alt : ($img['alt'] ?? '');
    $blank = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
    printf(
        '<picture class="hero-mobile-img">'
        . '<source media="(min-width:%dpx)" srcset="%s">'
        . '<img src="%s" alt="%s" width="%d" height="%d" fetchpriority="high" decoding="async">'
        . '</picture>',
        (int) $breakpoint + 1,
        esc_attr($blank),
        esc_url($src),
        esc_attr($alt),
        (int) $w,
        (int) $h
    );
}

/**
 * Curated inline SVG icon set (the design uses inline SVGs; these are the reused ones).
 */
function pcp_icon($name) {
    $icons = [
        'caret'    => '<svg class="caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>',
        'phone'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.33 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
        'check'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>',
        'star'     => '<svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg>',
        'facebook' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
        'youtube'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23 7.5a3 3 0 0 0-2.1-2.1C19 5 12 5 12 5s-7 0-8.9.4A3 3 0 0 0 1 7.5 31 31 0 0 0 .5 12 31 31 0 0 0 1 16.5a3 3 0 0 0 2.1 2.1C5 19 12 19 12 19s7 0 8.9-.4a3 3 0 0 0 2.1-2.1 31 31 0 0 0 .5-4.5 31 31 0 0 0-.5-4.5zM9.5 15.5v-7l6 3.5z"/></svg>',
        'arrow'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>',
        'pin'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
        'mail'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/></svg>',
        'clock'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>',
    ];
    return $icons[$name] ?? '';
}

/**
 * Permalink for a CPT archive with a safe fallback to a home anchor.
 */
function pcp_archive_url($post_type, $fallback = '/') {
    $url = get_post_type_archive_link($post_type);
    return $url ? $url : home_url($fallback);
}

/**
 * Render the star rating row used in review cards.
 */
function pcp_stars($count = 5) {
    $out = '<div class="stars" aria-label="' . esc_attr($count) . ' out of 5 stars">';
    for ($i = 0; $i < (int) $count; $i++) { $out .= pcp_icon('star'); }
    $out .= '</div>';
    return $out;
}
