<?php
if (!defined('ABSPATH')) { exit; }

/**
 * The theme fully styles its forms (see the .af / .af-wrap rules in main.css),
 * so switch off Gravity Forms' own front-end CSS — otherwise its default theme
 * and 2.5+ "orbital" framework paint the fields as boxes, run their own grid,
 * and colour the submit button, fighting and overriding the design styling.
 * This is the missing piece that made the form refuse to match the design.
 */
add_filter('pre_option_rg_gforms_disable_css', '__return_true');
add_filter('gform_disable_form_theme_css', '__return_true');
add_filter('gform_default_styles', function ($styles) {
    return ''; // no preset GF style tokens; the theme provides all styling
});

/**
 * Render a Gravity Form by ID retrieved from an ACF field.
 * Returns silently if Gravity Forms isn't active or no ID is set.
 *
 * @param int|null $form_id Optional. Pass an explicit form ID; otherwise
 *                          the current post's ACF `form_id` field is used.
 */
function sn_render_gravity_form($form_id = null) {
    if (!$form_id) {
        $form_id = function_exists('get_field') ? (int) get_field('form_id') : 0;
    }

    if (!$form_id || !function_exists('gravity_form')) {
        return;
    }

    gravity_form($form_id, false, false, false, null, true);
}

/**
 * Mirror the ported design's form markup: inject the design's own classes
 * (af, fld, fld__lab, fld__in, af__submit) onto the Gravity Forms output so
 * the design CSS styles the form directly, matching the design HTML. The
 * radio segmented control keeps its dedicated styling (no fld__in on radios).
 */
add_filter('gform_form_tag', function ($tag) {
    if (strpos($tag, 'class="') === false) {
        return preg_replace('/<form /', '<form class="af" ', $tag, 1);
    }
    return preg_replace('/class="/', 'class="af ', $tag, 1);
}, 10, 1);

add_filter('gform_field_css_class', function ($classes, $field) {
    return trim($classes . ' fld');
}, 10, 2);

add_filter('gform_field_content', function ($content, $field) {
    $type = method_exists($field, 'get_input_type') ? $field->get_input_type() : ($field->type ?? '');
    // Label -> fld__lab
    $content = str_replace('class="gfield_label', 'class="gfield_label fld__lab', $content);
    // Text-like inputs, selects and textareas -> fld__in (skip radios/checkboxes)
    if (!in_array($type, ['radio', 'checkbox'], true)) {
        $content = preg_replace('/(<(?:input|select|textarea)\b[^>]*\bclass=")/', '$1fld__in ', $content);
    }
    return $content;
}, 10, 2);

add_filter('gform_submit_button', function ($button) {
    if (strpos($button, 'class="') === false) {
        return preg_replace('/<(input|button) /', '<$1 class="btn af__submit" ', $button, 1);
    }
    return preg_replace('/class="/', 'class="btn af__submit ', $button, 1);
}, 10, 1);
