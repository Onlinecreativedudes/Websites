<?php
if (!defined('ABSPATH')) { exit; }

/**
 * The theme fully styles its forms (see .ocd-form in main.css), so switch off
 * Gravity Forms' own front-end CSS — otherwise its default theme paints the
 * fields as boxes and the submit button its own blue, fighting the design.
 * Covers both the legacy stylesheet and the 2.5+ "orbital" theme framework.
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
 * @param int|null $form_id Optional explicit form ID.
 */
function ocd_render_gravity_form($form_id = null) {
    $form_id = (int) $form_id;
    if (!$form_id || !function_exists('gravity_form')) {
        return;
    }
    gravity_form($form_id, false, false, false, null, true);
}
